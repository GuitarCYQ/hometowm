<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Admin_group;
use App\Models\Admin_menu;
use Illuminate\Support\Facades\View;

class CommentController extends Controller
{
    public function __construct()
    {
        $actionStr = \Route::current()->getActionName();
        list($controllerName,$action) = explode('@',$actionStr);


        # 模块名
        $modules = str_replace(
            '\\',
            '.',
            str_replace(
                'App\\Http\\Controllers\\',
                '',
                trim(
                    implode('\\', array_slice(explode('\\', $actionStr), 0, -1)),
                    '\\'
                )
            )
        );


        $controllerName = substr(strrchr($controllerName,'\\'),1);
        $controllerName = str_replace('Controller','',$controllerName);
        $controllerName = str_replace('Admin_','',$controllerName);
        $this->actions['controller'] = strtolower($controllerName);
        $this->actions['action'] = strtolower($action);
        $m = [];
        $c = [];
        $me = [];


        if (!empty(session('username'))) {
            $group_id = Admin::where('username',session('username'))->value('group_id');
            $controller = Admin_group::where('id',$group_id)->value('controller');
            foreach(json_decode($controller) as $ck=>$cv){
                $m[] = Admin_menu::where('id',$cv)->value('models');
                $c[] = Admin_menu::where('id',$cv)->value('controller');
                $me[] = Admin_menu::where('id',$cv)->value('methods');
            }

                if ((strtolower($modules) == 'admin' && strtolower($controllerName) == 'index' && strtolower($action) == 'index') || (strtolower($modules) == 'admin' && strtolower($controllerName) == 'index' && strtolower($action) == 'welcome') || (in_array(strtolower($modules), $m) && in_array(strtolower($controllerName), $c) && in_array(strtolower($action), $me))) {

                } else {

                    echo "<script>alert('您无权限！111');</script>";
////                    exit;
//                    echo "暂无权限！";exit;
                }

            dump(strtolower($controllerName),$c,in_array(strtolower($modules),$m),in_array(strtolower($controllerName),$c),in_array(strtolower($action),$me));
                // dump($m,$c,$me);
//             dump($group_id,json_decode($controller),$modules,strtolower($controllerName),$action);

        }else{
            return redirect(route('admin.login'));
        }

        view::share('actions',$this->actions);

    }
}