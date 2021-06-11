<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;

class SessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $session_pass = $request->cookie('session_pass');
        if (is_null($session_pass)) {
            $current_session = $this->generateNewSession();
        } else {
            $current_session = Session::getWithPass($session_pass);
            if (is_null($current_session)) {
                $session_pass = null;
                $current_session = $this->generateNewSession();
            }
        }

        $user_agent = $this->getUserAgent();
        if ($user_agent) {
            $current_session->setData('user_agent', $user_agent);
        }

        $request->attributes->add(['_session' => $current_session]);
        $response = $next($request);

        if (is_null($session_pass)) {
            $random = $current_session->pass;
            $response->withCookie(cookie('session_pass', $random));
        }

        return $response;
    }

    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] . '' : 'unknown';
    }

    public function generateNewSession()
    {
        $session = new Session();
        do {
            $random = rand(111111111, 999999999) . rand(111111111, 999999999);
            $session->pass = $random;
            $session->data = [];
            $insert_session = $session->save();
        } while ($insert_session == false);
        return $session;
    }
}
