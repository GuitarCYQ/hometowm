<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CommentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends CommentController
{
    public function index()
    {
        //判断是否登陆 登陆直接跳转到主页
        if (auth()->check()) {
            return redirect()->route('admin.index.index');
        }

        return view('admin.login.index');
    }

    public function login(Request $request)
    {
        if (auth()->attempt($request->only(['username','password']))){
            session()->put('username',$request->get('username'));
            return redirect()->route('admin.index.index')->with('msg','登陆成功');
        }else
        {
            return redirect()->back()->withErrors(['errors' => '登陆失败']);
        }
    }
}
