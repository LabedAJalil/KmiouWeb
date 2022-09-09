<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class RedirectIfAdminAuthenticated
{
  public function handle($request, Closure $next)
  {
      //If request comes from logged in user, he will
      //be redirect to home page.
      if (Auth::guard('admin')->check()) {
          return redirect('/admin/dashboard');
      }

      if (Auth::guard('shipper')->check()) {
          return redirect('/shipper/dashboard');
      }

      if (Auth::guard('transporter')->check()) {
          return redirect('/transporter/dashboard');
      }

      if (Auth::guard('driver')->check()) {
          return redirect('/driver/dashboard');
      }

      return $next($request);
  }
}
