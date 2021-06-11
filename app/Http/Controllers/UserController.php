<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getAccess(Request $request)
    {
        $session = $request->get('_session');
        if (isset($session->data['user_id'])) {
            return Utils::successResponse('You Already Have Access');
        }
        $validate = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required'
        ]);
        if ($validate->fails()) {
            return Utils::errorResponse(406, $validate->errors()->messages());
        }

        $user_name = $request->get('user_name');
        $password = $request->get('password');
        $user = User::where('user_name', '=', $user_name)->where('password', '=', $password)->first();
        if (is_null($user)) {
            $user = new User();
            $user->user_name = $user_name;
            $user->password = $password;
            try {
                $user->save();
                $user->roles()->sync(1);
            } catch (\Exception $exception) {
                $errorCode = $exception->getCode();
                if ($errorCode == 1062) {
                    return Utils::errorResponse(406, 'Duplicate Error For This UserName');
                }
                return Utils::errorResponse(500, $exception->getMessage());
            }
        }
        $session->setdata('user_id', $user->id);
        return Utils::successResponse('You Have Access Now!');
    }
}
