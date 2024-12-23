<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;


class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    protected function getUserPosition($user)
{
    $positions = [
        'local_secretary' => 'Local Secretary',
        'university_secretary' => 'University Secretary',
        'board_sec' => 'Board Secretary',
        'admin_council_membership' => 'Admin Council Member',
        'academic_council_membership' => 'Academic Council Member',
    ];

    foreach ($positions as $table => $position) {
        if (\DB::table($table)->where('users_id', $user->id)->exists()) {
            return $position;
        }
    }

    return null; // Default if no specific role found
}
    public function handleGoogleLogin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'google_id' => 'required',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $user = $this->findOrCreateUser($request);

        // Log the user in
        Auth::login($user);

        $userPosition = $this->getUserPosition($user);


        // Determine redirect URL based on the user's membership
        return response()->json([
            'message' => 'Login successful.',
            'redirect_url' => $this->getRedirectUrlBasedOnMembership($user),
            'user_position' => $userPosition
        ]);
    }

    protected function findOrCreateUser($request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Register the new user
            $user = User::create([
                'email' => $request->email,
                'google_id' => $request->google_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => Hash::make(Str::random(10)), // Random password
            ]);
        } else {
            // Update Google ID if needed
            $user->update(['google_id' => $request->google_id]);
        }

        return $user;
    }

    protected function getRedirectUrlBasedOnMembership($user)
    {
        $membershipTables = [
            'local_secretary' => 'local_secretary',
            'university_secretary' => 'university_secretary',
            'board_sec' => 'board_sec',
            'admin_council_membership' => 'admin_council_membership',
            'academic_council_membership' => 'academic_council_membership',
        ];

          foreach ($membershipTables as $table => $route) {
        if (\DB::table($table)->where('users_id', $user->id)->exists()) {
            if ($table === 'local_secretary') {
                return route('localsec.meetings');
            } elseif ($table === 'university_secretary') {
                return route('universitysec.dashboard');
            } elseif ($table === 'board_sec') {
                return route('boardsec.proposals');
            } elseif ($table === 'admin_council_membership') {
                return route('admin.submit.proposal');
            } elseif ($table === 'academic_council_membership') {
                return route('academic.submit.proposal');
            } else {
                return route('home');
            }
        }
    }

        // Default redirect if no membership is found
        return route('login');
    }

    public function logout(Request $request)
{
    // Perform logout
    Auth::logout();

    // Invalidate the session
    $request->session()->invalidate();

    // Regenerate the session token
    $request->session()->regenerateToken();

    // Redirect to the login page or send a JSON response if requested
    if ($request->expectsJson()) {
        return response()->json([
            'message' => 'Logout successful.',
            'redirect_url' => route('login'),
        ]);
    }

    return redirect()->route('login')->with('message', 'Logout successful.');
}
    

}
