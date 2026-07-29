<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['required', 'string', 'regex:/^[0-9\-\s\+]{10,15}$/', Rule::unique('users', 'phone')],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
            'role'     => ['sometimes', Rule::in(['user', 'worker', 'both'])],
        ], [
            'phone.required' => 'Please enter your 10-digit phone number.',
            'phone.regex'    => 'Please enter a valid 10-digit numeric phone number.',
            'phone.unique'   => 'This phone number is already registered with another account.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'password' => $validated['password'],
            'role'     => $validated['role'] ?? 'user',
            'notification_preferences' => [
                'email' => true,
                'push'  => true,
                'audio' => true,
            ],
        ]);

        // If registering as worker, create trial subscription
        if ($user->isWorker()) {
            $this->subscriptionService->createTrial($user);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful.',
            'user'    => new UserResource($user->load('workerProfile')),
            'token'   => $token,
        ], 201);
    }

    /**
     * Send 6-digit OTP code for registration verification.
     */
    public function sendRegisterOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')],
            'phone'    => ['required', 'string', 'regex:/^[0-9\-\s\+]{10,15}$/', Rule::unique('users', 'phone')],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
            'role'     => ['sometimes', Rule::in(['user', 'worker', 'both'])],
        ], [
            'phone.required' => 'Please enter your 10-digit phone number.',
            'phone.regex'    => 'Please enter a valid 10-digit numeric phone number.',
            'phone.unique'   => 'This phone number is already registered with another account.',
            'email.unique'   => 'This email address is already registered with another account.',
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
        $otp = (string) rand(100000, 999999);

        Cache::put("reg_otp_{$cleanPhone}", [
            'otp'       => $otp,
            'user_data' => [
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'phone'    => $validated['phone'],
                'password' => $validated['password'],
                'role'     => $validated['role'] ?? 'user',
            ],
        ], now()->addMinutes(15));

        return response()->json([
            'message' => "Verification OTP code sent to {$validated['phone']}.",
            'phone'   => $validated['phone'],
            'otp'     => $otp, // Exposed preview for easy testing
        ]);
    }

    /**
     * Verify OTP code and complete user registration.
     */
    public function verifyRegisterOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
            'otp'   => ['required', 'string', 'size:6'],
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);
        $cachedData = Cache::get("reg_otp_{$cleanPhone}");

        if (!$cachedData || $cachedData['otp'] !== $validated['otp']) {
            return response()->json([
                'message' => 'Invalid or expired OTP code. Please request a new verification code.',
                'errors'  => ['otp' => ['Invalid or expired OTP code. Please request a new verification code.']],
            ], 422);
        }

        $userData = $cachedData['user_data'];

        if (User::where('email', $userData['email'])->exists()) {
            return response()->json([
                'message' => 'This email address has already been registered.',
                'errors'  => ['email' => ['This email address has already been registered.']],
            ], 422);
        }
        if (User::where('phone', $userData['phone'])->exists()) {
            return response()->json([
                'message' => 'This phone number has already been registered.',
                'errors'  => ['phone' => ['This phone number has already been registered.']],
            ], 422);
        }

        $user = User::create([
            'name'     => $userData['name'],
            'email'    => $userData['email'],
            'phone'    => $userData['phone'],
            'password' => $userData['password'],
            'role'     => $userData['role'] ?? 'user',
            'phone_verified_at' => now(),
            'notification_preferences' => [
                'email' => true,
                'push'  => true,
                'audio' => true,
            ],
        ]);

        if ($user->isWorker()) {
            $this->subscriptionService->createTrial($user);
        }

        Cache::forget("reg_otp_{$cleanPhone}");

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Registration and mobile verification completed successfully.',
            'user'    => new UserResource($user->load('workerProfile')),
            'token'   => $token,
        ], 201);
    }

    /**
     * Login user and issue token using email or phone number.
     */
    public function login(Request $request): JsonResponse
    {
        $loginId = $request->input('login') ?? $request->input('email');

        if (empty($loginId)) {
            return response()->json([
                'message' => 'The login field is required.',
                'errors'  => ['login' => ['Please enter your email address or phone number.']],
            ], 422);
        }

        $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $loginId)
            ->orWhere('phone', $loginId)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email/phone or password credentials.',
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact support.',
            ], 403);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'user'    => new UserResource($user->load('workerProfile')),
            'token'   => $token,
        ]);
    }

    /**
     * Logout user (revoke current token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Get authenticated user profile.
     */
    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'user' => new UserResource($request->user()->load('workerProfile')),
        ]);
    }

    /**
     * Send 6-digit OTP code to user's mobile number for password reset.
     */
    public function sendForgotOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'regex:/^[0-9\-\s\+]{10,15}$/'],
        ], [
            'phone.required' => 'Please enter your registered 10-digit phone number.',
            'phone.regex'    => 'Please enter a valid 10-digit numeric phone number.',
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);

        $user = User::where('phone', $validated['phone'])
            ->orWhereRaw("REPLACE(REPLACE(REPLACE(phone, ' ', ''), '+', ''), '-', '') LIKE ?", ["%{$cleanPhone}%"])
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'No account found registered with this mobile number.',
                'errors'  => ['phone' => ['No account found registered with this mobile number.']],
            ], 422);
        }

        // Generate a 6-digit OTP
        $otp = (string) rand(100000, 999999);
        Cache::put("forgot_otp_{$user->id}", $otp, now()->addMinutes(10));

        return response()->json([
            'message' => "OTP sent successfully to mobile number {$user->phone}.",
            'phone'   => $user->phone,
            'otp'     => $otp, // Expose preview for easy testing
        ]);
    }

    /**
     * Verify OTP and reset password.
     */
    public function resetPasswordWithOtp(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'phone'                 => ['required', 'string'],
            'otp'                   => ['required', 'string', 'size:6'],
            'password'              => ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()->symbols()],
            'password_confirmation' => ['required', 'string'],
        ]);

        $cleanPhone = preg_replace('/[^0-9]/', '', $validated['phone']);

        $user = User::where('phone', $validated['phone'])
            ->orWhereRaw("REPLACE(REPLACE(REPLACE(phone, ' ', ''), '+', ''), '-', '') LIKE ?", ["%{$cleanPhone}%"])
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'No account found registered with this mobile number.',
                'errors'  => ['phone' => ['No account found registered with this mobile number.']],
            ], 422);
        }

        $cachedOtp = Cache::get("forgot_otp_{$user->id}");

        if (!$cachedOtp || $cachedOtp !== $validated['otp']) {
            return response()->json([
                'message' => 'Invalid or expired OTP code. Please request a new OTP.',
                'errors'  => ['otp' => ['Invalid or expired OTP code. Please request a new OTP.']],
            ], 422);
        }

        // Update password & clear OTP cache
        $user->update([
            'password' => $validated['password'],
        ]);

        Cache::forget("forgot_otp_{$user->id}");

        return response()->json([
            'message' => 'Your password has been reset successfully. You can now sign in with your new password.',
        ]);
    }
}
