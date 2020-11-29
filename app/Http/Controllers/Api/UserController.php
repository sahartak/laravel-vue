<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }
        $user = User::getUserByEmail($request->get('email'));
        if (!$user || !$user->validatePassword($request->get('password'))) {
            return response(['error' => ['Login or password is invalid!']], 401);
        }
        $token = $user->loginUser();

        return response(['token' => $token, 'user' => $user], 200);
    }

    public function settings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maxAmount' => 'required|numeric|min:2|max:1000000',
        ]);
        if ($validator->fails()) {
            return response(['error' => $validator->errors()->all()], 422);
        }
        $user = $request->user;
        /* @var User $user*/
        $setting = $user->settings() ?? new UserSetting(['user_id' => $user->id]);
        $setting->max_amount = $request->get('maxAmount');
        $setting->save();
        $user->max_amount = $setting->max_amount;

        return response([
            'user' => $request->user,
            'data' => $setting
        ]);
    }

    public function account(Request $request)
    {
        return response([
            'user' => $request->user
        ]);
    }

}
