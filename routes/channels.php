<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('users', function ($user) {
   // return (int) $user->id === (int) $id;
   return $user;
});

Broadcast::channel('chat', function ($user) {
   // return (int) $user->id === (int) $id;
   //broadcast(new App\Events\TypingEvent($user))->toOthers();
   return Auth::check();
});


Broadcast::channel('chat-{id}', function ($user) {
    // return (int) $user->id === (int) $id;
    //broadcast(new App\Events\TypingEvent($user))->toOthers();
    return Auth::check();
 });
