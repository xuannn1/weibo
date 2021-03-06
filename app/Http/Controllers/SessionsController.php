<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials, $request->has('remember'))) {
            //登录成功后的操作
            if(Auth::user()->activated) {
                //邮箱验证成功后的操作
                session()->flash('success', '欢迎回来！');
                $fallback = route('users.show', Auth::user());
                return redirect()->intended($fallback);
            }
            else {
                //邮箱未激活时的操作
                Auth::logout;
                session()->flash('warning', '您的账号未激活，请检查邮箱中的注册邮件进行激活');
                return redirect('/');
            }
        } else {
            //登录失败后的操作
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }

        return;
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
