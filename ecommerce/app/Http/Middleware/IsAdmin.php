<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
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
      // Check if user is login or not
      if (Auth::check()) {
        // Check if user is admin
          if (Auth::user()->level == 'ADMIN') {
            return $next($request);
          } else {
            // If user is not admin, they cannot login to admin page
            return redirect('/home');
          }
      } else {
            return redirect('/login');
      }
    }
}
