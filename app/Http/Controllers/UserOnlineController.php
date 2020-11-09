<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Events\LoginEvent;

class UserOnlineController extends Controller
{
    public function __invoke(User $user)
    {
        $user->is_online = 1;
        $user->save();

        broadcast(new LoginEvent($user));
    }

}
