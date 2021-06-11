<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Utils;
use Closure;
use Illuminate\Http\Request;

class AclMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $session = $request->get('_session');
        if (!isset($session->data['user_id'])) {
            return Utils::errorResponse(403, 'You Have No Access, For That Please Get Access First :http://localhost:8000/api/user/getAccess');
        }
        $user = User::whereId($session->getData('user_id'))->first();
        if (is_null($user)) {
            return Utils::errorResponse(403, 'You Have No Access, For That Please Get Access First :http://localhost:8000/api/user/getAccess');
        }
        $user_roles = $user->roles()->first()->id;
        if ($user_roles != 1) {
            return abort(Utils::errorResponse(403, 'You Have No Access, For That Please Get Access First : http://localhost:8000/api/user/getAccess'));
        }
        return $next($request);
    }
}
