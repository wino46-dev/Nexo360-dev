<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsurePanelAccess
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string $panel expected panel: 'admin' or 'external'
     */
    public function handle(Request $request, Closure $next, string $panel)
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Totems (or users with role "totem") never access backoffices; redirect them to /home
        $isTotemEffective = false;
        if (method_exists($user, 'isTotem') && $user->isTotem()) {
            $isTotemEffective = true;
        } else {
            try {
                // Treat any user having the role named 'totem' as a totem (even if user->type is internal)
                if (method_exists($user, 'roles') && $user->roles()->whereRaw('LOWER(title) = ?', ['totem'])->exists()) {
                    $isTotemEffective = true;
                }
            } catch (\Throwable $e) {
                // ignore if roles relation not available
            }
        }
        if ($isTotemEffective) {
            return redirect('/home');
        }

        if ($panel === 'admin') {
            // only internal users
            if (method_exists($user, 'isInternal') && $user->isInternal()) {
                return $next($request);
            }
            // external users trying to hit admin go to external home
            return redirect('/external');
        }

        if ($panel === 'external') {
            if (method_exists($user, 'isExternal') && $user->isExternal()) {
                return $next($request);
            }
            // internal users go to admin, others (totem already handled) to /home
            if (method_exists($user, 'isInternal') && $user->isInternal()) {
                return redirect('/admin');
            }
            return redirect('/home');
        }

        return $next($request);
    }
}
