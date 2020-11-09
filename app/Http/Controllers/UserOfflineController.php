<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Events\LogoutEvent;

class UserOfflineController extends Controller
{
    public function __invoke(User $user)
    {
        $user->is_online = 0;
        $user->save();

        broadcast(new LogoutEvent($user));
    }

}
