<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /*会員一覧ページ*/
    public function index(Request $request) {

        $keyword = $request->input('keyword');

        if ($keyword !== null) {
            $users=User::where('name','LIKE',"%{keyword}%")
                       ->orwhere('kana','LIKE',"%{keyword}%")
                       ->paginate(15);
            $total=$users->total();
        }else{
            $users=User::paginate(15);
            $total=0;
            $keyword=null;
        }   
        // ビューに変数を渡して返します
         return view('admin.users.index', compact('users', 'keyword', 'total'));
    }

    /*会員詳細ページ*/
    public function show(User $user)
    {
        return view('admin.users.show',compact('user'));
    }
}
