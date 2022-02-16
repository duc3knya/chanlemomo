<?php

namespace App\Http\Middleware;

use App\Http\Repositories\AttendanceSessionRepository;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class MaintenanceSystem extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        $repo = new AttendanceSessionRepository();
        $config = $repo->getSettingWebsite();
        if ($config['baotri'] == 1){
            return redirect(route('bao_tri'));
        }
        return  $next($request);
    }
}
