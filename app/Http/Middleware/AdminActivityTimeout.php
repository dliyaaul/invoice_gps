<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AdminActivityTimeout
{

    protected $timeout = 3600;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $lastActivity = Session::get('lastActivityTime');
            $currentTime = time();

            if ($lastActivity && ($currentTime - $lastActivity > $this->timeout)) {
                Auth::logout(); // Logout pengguna
                Session::flush(); // Hapus semua sesi

                return redirect()->route('login')->with('message', 'Anda telah keluar karena tidak ada aktivitas.');
            }

            // Update waktu aktivitas terakhir
            Session::put('lastActivityTime', $currentTime);
        }

        return $next($request);
    }
}
