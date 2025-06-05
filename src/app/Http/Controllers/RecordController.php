<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaymentSetting;
use App\Models\Profile;

class RecordController extends Controller
{
    public function create()
    {
        $setting = PaymentSetting::first() ?? PaymentSetting::create([
            'a_share' => 50,
            'b_share' => 50,
        ]);

        $profile = Profile::first(); // もし名前表示などに使っている場合

        return view('records.create', compact('setting', 'profile'));
    }
}
