<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Concerns\HasHashId;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasHashId;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'avatar',
        'role',
        'is_active',
        'notification_preferences',
        'admin_email_override',
        'admin_push_override',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'array',
            'is_active' => 'boolean',
            'admin_email_override' => 'boolean',
            'admin_push_override' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────

    public function workerProfile(): HasOne
    {
        return $this->hasOne(WorkerProfile::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function chatsAsUser(): HasMany
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    public function chatsAsWorker(): HasMany
    {
        return $this->hasMany(Chat::class, 'worker_id');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    // ─── Helpers ──────────────────────────────────────────────

    public function isWorker(): bool
    {
        return in_array($this->role, ['worker', 'both']);
    }

    public function isUser(): bool
    {
        return in_array($this->role, ['user', 'both']);
    }

    /**
     * Get all chats for this user (as user or worker).
     */
    public function allChats()
    {
        return Chat::where('user_id', $this->id)
            ->orWhere('worker_id', $this->id);
    }
}
