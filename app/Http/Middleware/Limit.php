<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Role;

class Limit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed 
     */
    public function handle($request, Closure $next)
    {
        $role = new Role;
        if($role->limited() && !$role->admin() && !$role->boss()) abort('401');
        
        return $next($request);
    }
}
