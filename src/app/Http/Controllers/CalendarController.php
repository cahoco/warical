<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CalendarController extends Controller
{
    public function index()
    {
        $expenses = DB::table('expenses')
            ->select(DB::raw('date, SUM(amount) as total'))
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        return view('calendar.index', compact('expenses'));
    }
}
