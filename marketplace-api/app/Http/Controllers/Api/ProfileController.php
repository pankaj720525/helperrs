<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\WorkerProfileResource;
use App\Models\WorkerProfile;
use App\Services\SubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    /**
     * Get current user profile.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load('workerProfile');

        return response()->json([
            'user' => new UserResource($user),
        ]);
    }

    /**
     * Update current user profile.
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'   => ['sometimes', 'string', 'max:255'],
            'phone'  => ['nullable', 'string', 'regex:/^[0-9\-\s\+]{10,15}$/', Rule::unique('users', 'phone')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ], [
            'phone.regex'  => 'Please enter a valid 10-digit numeric phone number.',
            'phone.unique' => 'This phone number is already registered with another account.',
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user'    => new UserResource($user->fresh()->load('workerProfile')),
        ]);
    }

    /**
     * Create or update worker profile.
     */
    public function workerProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'address'      => ['nullable', 'string', 'max:500'],
            'city'         => ['nullable', 'string', 'max:100'],
            'bio'          => ['nullable', 'string', 'max:2000'],
            'phone_public' => ['nullable', 'string', 'max:20'],
            'email_public' => ['nullable', 'string', 'email', 'max:255'],
            'specialties'  => ['nullable', 'array'],
            'specialties.*'=> ['string', 'max:100'],
            'latitude'     => ['required', 'numeric', 'between:-90,90'],
            'longitude'    => ['required', 'numeric', 'between:-180,180'],
            'is_available' => ['sometimes', 'boolean'],
        ]);

        // Update user role to worker or both
        if ($user->role === 'user') {
            $user->update(['role' => 'both']);

            // Create trial subscription if they didn't have one
            if (!$this->subscriptionService->hasActiveSubscription($user)) {
                $this->subscriptionService->createTrial($user);
            }
        }

        $profile = WorkerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'address'      => $validated['address'] ?? null,
                'city'         => $validated['city'] ?? null,
                'bio'          => $validated['bio'] ?? null,
                'phone_public' => $validated['phone_public'] ?? null,
                'email_public' => $validated['email_public'] ?? null,
                'specialties'  => $validated['specialties'] ?? null,
                'is_available' => $validated['is_available'] ?? true,
            ]
        );

        // Set location using spatial data
        $profile->setLocation($validated['latitude'], $validated['longitude']);

        return response()->json([
            'message' => 'Worker profile updated successfully.',
            'worker_profile' => new WorkerProfileResource($profile->fresh()->load('user')),
        ]);
    }

    /**
     * Change current user password.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password'     => ['required', 'string', Password::min(8)->letters()->numbers()->symbols()],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json([
                'message' => 'The provided current password does not match your record.',
            ], 422);
        }

        $user->update([
            'password' => $validated['new_password'],
        ]);

        return response()->json([
            'message' => 'Password updated successfully.',
        ]);
    }
}
