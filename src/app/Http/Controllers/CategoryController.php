<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('settings.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        Category::create(['name' => $request->name]);
        return redirect()->route('categories.index')->with('success', 'カテゴリを追加しました。');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        return redirect()->route('categories.index')->with('success', 'カテゴリを削除しました。');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string']);
        $category = Category::findOrFail($id);
        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'カテゴリを更新しました。');
    }

}
