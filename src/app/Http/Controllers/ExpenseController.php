<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Memo;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    // 一覧表示
    public function index()
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();

        $expenses = Expense::where('date', '>=', $monthStart)->orderBy('date', 'desc')->get();

        $totalAmount = $expenses->sum('amount');
        $amountA = $expenses->where('payer', 'Aさん')->sum('amount');
        $amountB = $expenses->where('payer', 'Bさん')->sum('amount');

        if ($amountA < $amountB) {
            $payer = 'Aさん';
            $receiver = 'Bさん';
            $settlementAmount = ($amountB - $amountA) / 2;
        } elseif ($amountB < $amountA) {
            $payer = 'Bさん';
            $receiver = 'Aさん';
            $settlementAmount = ($amountA - $amountB) / 2;
        } else {
            $payer = $receiver = 'なし';
            $settlementAmount = 0;
        }

        $memo = Memo::latest()->first();

        return view('index', compact(
            'today', 'expenses', 'totalAmount',
            'payer', 'receiver', 'settlementAmount', 'memo'
        ));
    }

    // 保存処理
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'nullable|string',
            'amount' => 'required|integer|min:0',
            'payer' => 'required|string|in:Aさん,Bさん',
        ]);

        Expense::create($request->only('date', 'category', 'amount', 'payer'));

        return redirect()->route('expenses.index')->with('success', '支出を登録しました。');
    }
}
