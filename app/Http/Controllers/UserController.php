<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;

class UserController extends Controller
{
    public function login()
    {
        return View::make('user.login');
    }

    public function settings()
    {
        return View::make('user.settings');
    }

}
