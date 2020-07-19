<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $params)
    {
        //当前的路由别名
        $currentRouteName = $request->route()->getName();

        if ($currentRouteName != $params){
            //检测用户是否登陆
            if (!auth()->check()){
                //先清空session
                session()->flush();
                //跳转登陆页面
                return redirect(route('admin.login'))->withErrors(['errors' => '请登录']);
            }
        }
        return $next($request);
    }

}
