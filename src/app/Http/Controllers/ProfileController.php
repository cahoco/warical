<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    // 編集画面の表示
    public function edit()
    {
        $profile = Profile::first(); // 1件目のデータを取得
        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'a_name' => 'nullable|string|max:255',
            'a_birthday' => 'nullable|date',
            'a_dislike' => 'nullable|string',
            'b_name' => 'nullable|string|max:255',
            'b_birthday' => 'nullable|date',
            'b_dislike' => 'nullable|string',
            'anniversary' => 'nullable|date',
        ]);

        $profile = Profile::firstOrNew(); // 1レコード前提
        $profile->fill($validated)->save();

        return redirect()->back()->with('status', 'プロフィールを保存しました');
    }

}
