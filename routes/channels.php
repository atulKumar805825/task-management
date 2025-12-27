<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     // Only allow user to listen to their own channel
//     return (int) $user->id === (int) $id;
// });
Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});