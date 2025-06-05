<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Memo;
use App\Models\Profile;
use Carbon\Carbon;
use App\Models\PaymentSetting;
use App\Models\Category;

class ExpenseController extends Controller
{
    // 一覧表示
    public function index()
    {
        $today = Carbon::today();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        // プロフィール名を取得（集計前に必要）
        $profile = Profile::first();
        $nameA = $profile->a_name ?? 'Aさん';
        $nameB = $profile->b_name ?? 'Bさん';

        // 今月の支出を取得
        $expenses = Expense::whereBetween('date', [$monthStart, $monthEnd])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // 合計・清算計算（名前で）
        $totalAmount = $expenses->sum('amount');
        $amountA = $expenses->where('payer', $profile->a_name)->sum('amount');
        $amountB = $expenses->where('payer', $profile->b_name)->sum('amount');

        if ($amountA < $amountB) {
            $payer = $nameA;
            $receiver = $nameB;
            $settlementAmount = ($amountB - $amountA) / 2;
        } elseif ($amountB < $amountA) {
            $payer = $nameB;
            $receiver = $nameA;
            $settlementAmount = ($amountA - $amountB) / 2;
        } else {
            $payer = $receiver = 'なし';
            $settlementAmount = 0;
        }

        // メモ取得
        $memo = Memo::latest()->first();

        // 🔽 カテゴリ別合計を集計
        $categoryData = $expenses
            ->groupBy('category')
            ->mapWithKeys(function ($group, $category) {
                return [$category ?? '未分類' => $group->sum('amount')];
            })
            ->toArray();

        return view('index', compact(
            'today', 'expenses', 'totalAmount',
            'payer', 'receiver', 'settlementAmount',
            'memo', 'categoryData', 'profile'
        ));
    }

    // 保存処理
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'category' => 'nullable|string',
            'amount' => 'required|integer|min:0',
            'payer' => 'required|string',
        ]);

        Expense::create($request->only('date', 'category', 'amount', 'payer'));
        return redirect()->route('expenses.index')->with('success', '支出を登録しました。');
    }

    public function create()
    {
        $profile = Profile::first();

        // 割合設定の初期化
        $setting = PaymentSetting::first();
        if (!$setting) {
            $setting = PaymentSetting::create([
                'a_share' => 50,
                'b_share' => 50,
            ]);
        }

        // 🔽 カテゴリー一覧を取得
        $categories = Category::all();

        // Bladeに渡す
        return view('records.create', compact('profile', 'setting', 'categories'));
    }

}
