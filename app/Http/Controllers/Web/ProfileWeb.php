<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ProfileWeb extends Controller
{
    protected $title;
    public function __construct()
    {
        $this->title = 'Profile Management';
    }

    public function index()
    {
        $user = Auth::user();
        $activeSessions = DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                $session->time_ago = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
                return $session;
            });
        $activeSessionsCount = $activeSessions->count();
        $lastLogin = $activeSessions->isNotEmpty() ? $activeSessions->first()->last_activity : null;
        if ($lastLogin !== null) {
            $lastLogin = date('Y-m-d H:i:s', $lastLogin);
        }

        return view('profile.index', [
            'title'     => $this->title,
            'sessions'  => $activeSessions,
            'lastLogin' => $lastLogin
        ]);
    }

    public function pageChangePic()
    {
        $user = Auth::user();
        return view('profile.changePic', [
            'title' => $this->title,
            'user' => $user,
        ]);
    }

    public function changeImg(Request $request)
    {
        $user = Auth::user();
        $userData = User::where('id', $user->id)->first();
        $avatar = $request->input('avatar');
        $userData->image = "user-$avatar.jpg";
        $userData->save();
        return response()->json([
            'status'  => 'Success',
            'message' => 'Data saved'
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $userData = User::where('id', $user->id)->first();
        if ($request->input('current_password') != '') {
            if (!Hash::check($request->input('current_password'), $userData->password)) {
                return response()->json([
                    'status'  => 'Error',
                    'message' => 'Wrong password'
                ]);
            }
            $request->validate([
                'new_password' => ['max:100', 'min:8'],
            ]);

            if ($request->input('new_password') != $request->input('confirm_password')) {
                return response()->json([
                    'status'  => 'Error',
                    'message' => 'Password not match'
                ]);
            }
            $userData->password = Hash::make($request->input('new_password'));
        }
        if ($request->input('name') != '') {
            $userData->name = $request->input('name');
        }
        if ($request->input('email') != '') {
            $userData->email = $request->input('email');
        }
        $userData->save();
        return response()->json([
            'status'  => 'Success',
            'message' => 'Data saved'
        ]);
    }
}
