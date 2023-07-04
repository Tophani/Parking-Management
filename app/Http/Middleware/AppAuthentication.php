<?php

namespace App\Http\Middleware;

use Closure, Lang;

class AppAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $roles = array_slice(func_get_args(), 2); 

        foreach ($roles as $role) 
        { 
            try 
            { 
                if ($role == "client" && session()->get("isLogin")==true)
                {
                    return $next($request);
                }
                else if($role == "client" && session()->get("isLogin")==false) {
                    alert()->error(Lang::label('You are not authorized to view this page!'));
                    return redirect('/');
                }
                else if (auth()->check() && $request->user()->hasRole($role)) 
                {
                    return $next($request);
                }
            } 
            catch (ModelNotFoundException $exception) 
            {
                dd('Could not find role ' . $role);
            }
        } 

        if (empty($roles))
        alert()->error(Lang::label('You are not authorized to view this page!'));
        return redirect('login');
    }
}
