<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use App\Models\Profile;

class SettingController extends Controller
{
    public function editPayment()
    {
        $setting = PaymentSetting::first();

        // 初期データが存在しない場合はデフォルト作成
        if (!$setting) {
            $setting = PaymentSetting::create([
                'a_share' => 50,
                'b_share' => 50,
            ]);
        }

        $profile = Profile::first();

        return view('settings.payment', compact('setting', 'profile'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'a_share' => 'required|integer|min:0|max:100',
            'b_share' => 'required|integer|min:0|max:100',
        ]);

        $setting = PaymentSetting::first();
        $setting->update([
            'a_share' => $request->a_share,
            'b_share' => $request->b_share,
        ]);

        return redirect()->back()->with('success', '支払い割合を更新しました。');
    }
}
