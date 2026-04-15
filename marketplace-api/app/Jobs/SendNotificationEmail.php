<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\NotificationGatekeeper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $userId,
        public string $subject,
        public string $body,
        public string $type = 'general',
    ) {}

    public function handle(NotificationGatekeeper $gatekeeper): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            return;
        }

        // Check if email is allowed through gatekeeper
        if (!$gatekeeper->canSendEmail($user)) {
            Log::info("Email blocked by gatekeeper", [
                'user_id' => $this->userId,
                'type' => $this->type,
            ]);
            return;
        }

        try {
            Mail::raw($this->body, function ($message) use ($user) {
                $message->to($user->email)
                    ->subject($this->subject);
            });

            Log::info("Notification email sent", [
                'user_id' => $this->userId,
                'type' => $this->type,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to send notification email", [
                'user_id' => $this->userId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
