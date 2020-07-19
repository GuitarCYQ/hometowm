<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CommentController;
use App\Models\Admin_menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class IndexController extends CommentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Admin_menu::where([['accord','=','1'], ['status','=','1'],])->get();
        $menu = $this->getMenuData($data);
//        dd($menu);

        return view('admin.index.index',compact('menu'));
    }

    private function getMenuData($data,$parent_id=0){
        $menu = [];
//        $data = Admin_menu::get();
        foreach ($data as $key=>$value){
            if($value['parent_id'] == $parent_id){
                if($value['models'] == 'none' && $value['controller'] == 'none' && $value['methods'] == 'none' ){
                    $value['url'] = 'javascript:;';
                }else{
                    $value['url'] = route($value['models'].'.'.$value['controller'].'.'.$value['methods']);
                }
                $menu[] = $value;
                unset($data[$key]);
            }
        }
        foreach ($menu as $k=>$v){
            $v['child'] = $this->getMenuData($data,$v['id']);
        }
        return $menu;
    }

    public function getinfo()
    {
        
    }


    public function welcome()
    {
        return view('admin.index.welcome');
    }

    public function logout()
    {
        session()->flush('username');
        return redirect(route('admin.login'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
