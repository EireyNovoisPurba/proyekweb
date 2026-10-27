<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Visitor;

class TrackVisitor
{
    public function handle(Request $request, Closure $next)
    {
        // Menyimpan IP dan Tanggal Hari Ini.
        // firstOrCreate mencegah duplikasi IP pada hari yang sama.
        Visitor::firstOrCreate([
            'ip_address' => $request->ip(),
            'visit_date' => now()->toDateString(),
        ]);

        return $next($request);
    }
}