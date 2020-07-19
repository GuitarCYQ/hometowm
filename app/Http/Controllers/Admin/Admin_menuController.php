<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin_menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\CommentController;

class Admin_menuController extends CommentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->getmenu();
        $data = $this->menu;
//        dump($data['menu']);exit;
        return view('admin.menu.index',compact('data'));
    }

    public $sym1 = '|_';
    public $sym2 = '|-';
    public $sym3 = '-';
    public $count_sym = 0;



    public function getmenu($parent_id=0)
    {
        $this->sym2 = $parent_id ? '|':'';
        $this->sym3 = '';

        $data = Admin_menu::where('parent_id',$parent_id)->get();
//        dump($data);

        foreach($data as $key=>&$value){
            for ($i = 0;$i<$this->count_sym;$i++)
            {
                $this->sym3 .= '------ ';
            }
            $value->name = $this->sym2.$this->sym3.$value->name;
            $this->menu[] = ['id' => $value->id,'sort_order' => $value->sort_order,'name' => $value->name, 'status' => $value->status];
            $count = Admin_menu::where('parent_id',$value->id)->count();
            if ($count)
            {
                $this->count_sym ++;
                $this->getmenu($value->id);
                $this->count_sym --;
            }
            $this->sym2 = $parent_id ? '|-':'';
            $this->sym3 = '';
            $this->count_sym = $parent_id?$this->count_sym:0;

        }
        return $data;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->getmenu();
        $data = $this->menu;
        return view('admin.menu.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dump($request->all());exit;
        $menu = Admin_menu::create($request->all());
        if ($menu)
        {
            $data = [
                'status' => '1',
                'message' => '添加成功',
            ];
        }else
        {
            $data = [
                'status' => '0',
                'message' => '添加失败',
            ];
        }
        return $data;
    }

    public function sort(Request $request)
    {
        $sort_order = $request->get('sort_order');
        $id = $request->get('id');
        foreach ($id as $k=>$v){
            if($v){
                Admin_menu::where('id',$v)->update(['sort_order'=>$sort_order[$k]]);
            }

        }

    }


    public function status(Request $request)
    {
        $id = $request->get('id');
        if ($id == null )
        {
            return $emp = [
                'status' => '2',
                'message' => '您选择的目标不存在',
            ];
        }
        $status = Admin_menu::where('id',$id)->value('status');
        if ($status == 1){
            Admin_menu::where('id',$id)->update(['status'=>'0']);
          $data =  [
                'status' => '1',
            ];
        }else
        {
            Admin_menu::where('id',$id)->update(['status'=>'1']);
            $data = [
                'status' => '0',
            ];
        }

        return $data;

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

        $data = Admin_menu::where('id',$id)->get();
        $this->getmenu();
        $all = $this->menu;

        return view('admin.menu.edit',compact('data','id','all'));
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
        $update = Admin_menu::where('id',$id)->update($request->all());
        if ($update)
        {
            $data = [
                'status' => '1',
                'message' => '修改成功',
            ];
        }else
        {
            $data = [
                'status' => '0',
                'message' => '修改失败',
            ];
        }
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = Admin_menu::where('id',$id)->delete();
        if ($del)
        {
            $data = [
                'status' => '1',
                'message' => '删除成功',
            ];
        }else
        {
            $data = [
                'status' => '0',
                'message' => '删除失败',
            ];
        }
        return $data;
    }

    public function delAll(Request $request){
        $id = $request->get('id');
        if ($id == null || empty(array_filter($id)))
            {
            return $emp = [
                'status' => '2',
                'message' => '请选择目标',
            ];
        }
        foreach ($id as $v)
        {
            $res = Admin_menu::where('id',$v)->delete();
        }
        if ($res)
        {
            $data = [
                'status' => '1',
                'message' => '删除成功',
            ];
        }
        else
        {
            $data = [
                'status' => '0',
                'message' => '删除失败',
            ];
        }
        return $data;
    }
}
