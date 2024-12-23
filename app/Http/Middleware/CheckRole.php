<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$memberships)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('/')->with('error', 'You need to log in first.');
        }

        // Map user roles/memberships to their respective tables
        $roleMembershipTables = [
            'local_secretary' => 'local_secretary',
            'university_secretary' => 'university_secretary',
            'board_sec' => 'board_sec',
            'admin_council' => 'admin_council_membership',
            'academic_council' => 'academic_council_membership',
        ];

        foreach ($memberships as $membership) {
            if (isset($roleMembershipTables[$membership]) && \DB::table($roleMembershipTables[$membership])->where('users_id', $user->id)->exists()) {
                return $next($request);
            }
        }

        // If none of the memberships match, deny access
        return redirect('/')->with('error', 'Unauthorized access.');
    }
}
