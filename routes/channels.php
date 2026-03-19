<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('bus-tracking.{id}', function ($user, $id) {
    // Logic: Return true to allow public access to bus tracking
    return true;
});
