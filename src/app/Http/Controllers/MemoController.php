<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

class MemoController extends Controller
{
    public function store(Request $request)
    {
        Memo::create([
            'content' => $request->input('memo'),
        ]);

        return redirect()->route('expenses.index')->with('success', 'メモを保存しました');
    }

    public function autoSave(Request $request)
    {
        // 最新1件を上書き、なければ新規作成
        $memo = \App\Models\Memo::latest()->first();

        if ($memo) {
            $memo->update(['content' => $request->input('memo')]);
        } else {
            $memo = \App\Models\Memo::create(['content' => $request->input('memo')]);
        }

        return response()->json(['status' => 'saved']);
    }

}
