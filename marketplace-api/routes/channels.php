<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. Given a channel name and a callback, the callback
| is called when an authenticated user wants to join the channel.
|
*/

// Default user model channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// User private channel — for service status updates, notifications
Broadcast::channel('user.{userId}', function (User $user, int $userId) {
    return $user->id === $userId;
});

// Chat channel — both participants can join
Broadcast::channel('chat.{chatId}', function (User $user, int $chatId) {
    $chat = Chat::find($chatId);

    if (!$chat) {
        return false;
    }

    // Allow if user is the client or the worker
    return $user->id === $chat->user_id || $user->id === $chat->worker_user_id;
});
