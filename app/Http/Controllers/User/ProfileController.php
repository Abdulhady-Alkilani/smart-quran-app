<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile ?? $user->profile()->create(); // إن لم يكن لديه بروفايل، ننشئ واحداً

        return view('user.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string',
            'country' => 'nullable|string',
            'bio' => 'nullable|string',
        ]);

        $user = $request->user();
        $user->update(['name' => $request->name]);

        $user->profile()->update([
            'phone' => $request->phone,
            'country' => $request->country,
            'bio' => $request->bio,
        ]);

        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح.');
    }
}
