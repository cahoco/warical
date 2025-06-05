<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Profile;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $carbonDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::now();
        $month = $carbonDate->month;
        $year = $carbonDate->year;

        // 支出取得（その月分のみ）
        $expenses = DB::table('expenses')
            ->select(DB::raw('date, SUM(amount) as total'))
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        // プロフィール（1件のみ前提）
        $profile = Profile::first();
        $events = [];

        // イベント追加：誕生日・記念日（毎年同じ日付で表示）
        if ($profile) {
            if ($profile->a_birthday) {
                $date = Carbon::parse($profile->a_birthday)->setYear($year)->format('Y-m-d');
                $events[$date] = '🎂 ' . ($profile->a_name ?? 'Aさん');
            }

            if ($profile->b_birthday) {
                $date = Carbon::parse($profile->b_birthday)->setYear($year)->format('Y-m-d');
                $events[$date] = '🎂 ' . ($profile->b_name ?? 'Bさん');
            }

            if ($profile->anniversary) {
                $date = Carbon::parse($profile->anniversary)->setYear($year)->format('Y-m-d');
                $events[$date] = '💐 記念日';
            }
        }

        return view('calendar.index', [
            'expenses' => $expenses,
            'date' => $carbonDate,
            'events' => $events
        ]);
    }
}
