<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {

            if($request->is('api/admin/*')){

                return response()->json([
                    'status'=>0,
                    'message'=>'Access Token time expired',

                ]);
            }
            // return route('login');
        }
    }
}
