<?php
namespace App\Http\Middleware;

use Auth;
use Closure;

class SubscriberAuthenticated
{
    public function handle($request, Closure $next)
    {
        if(Auth::check()){
            // if user is not designer take him to his dashboard
            if ( Auth::user()->user_role == 2 ) {
                return $next($request);
            }else{
                return redirect(route('access-denied'));
            }
        }

        abort(404);  // for other user throw 404 error
    }
}