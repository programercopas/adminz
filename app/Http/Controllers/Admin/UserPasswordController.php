<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Activity;
use App\Services\Admin;
use Illuminate\Http\Request;

class UserPasswordController extends Controller
{
    public function password()
    {
        return view('components.admin.change-password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed',
        ]);
        $newPassword = bcrypt($request->password);
        $users = Admin::injectModel('User');
        $users->where(['id' => auth()->user()->id])->update(['password' => $newPassword]);
        Activity::eventLogs([
            'user_id' => auth()->user()->id,
            'activity' => 'change-password',
            'description' => 'change to new password',
        ]);
        return redirect()->route('password.user')->with('success','Password has been change');
    }
}
