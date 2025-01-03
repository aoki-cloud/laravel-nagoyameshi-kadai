<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /*カテゴリ一覧ページ*/
    public function index(Request $request) {

        $keyword = $request->input('keyword');

        if ($keyword !== null) {
            $categories=Category::where('name','LIKE',"%{$keyword}%")
                       ->paginate(15);
            $total=$categories->total();
        }else{
            $categories=Category::paginate(15);
            $total=0;
            $keyword=null;
        }   
        // ビューに変数を渡して返します
        return view('admin.categories.index', compact('categories', 'keyword', 'total'));
    }

    /**/
    /*カテゴリ登録機能*/
    public function store(Request $request) {
        $validatedData = $request->validate([
            // 入力必須
            'name' => 'required',
        ]);

        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを登録しました。');
    }

    /*カテゴリ更新機能*/
    public function update(Request $request, Category $category) {
        $request->validate([
            'name' => 'required',
        ]);

        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを編集しました。');
    }

    /*カテゴリ削除機能*/
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('flash_message', 'カテゴリを削除しました。');
    }
}
