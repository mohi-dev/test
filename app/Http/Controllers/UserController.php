<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required',
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(1, $validate->errors()->messages());
        }
        $user = User::where('user_name', '=', $request->user_name)
            ->where('password', '=', $request->password)->first();
        if (is_null($user)) {
            return Utils::errorResponse(6, 'wrong Username or Password !');
        }
        return Utils::successResponse('success');
    }
}
