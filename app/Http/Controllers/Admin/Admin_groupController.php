<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CommentController;
use App\Models\Admin_group;
use App\Models\Admin_menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class Admin_groupController extends CommentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Admin_group::paginate(5);

        return view('admin.group.index',compact('data'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Admin_menu::get();
        $menu = json_encode($this->getMenuData($data));
        return view('admin.group.create',compact('menu'));
    }


    public function getAllMenu($controllers = [])
    {
        if(!empty($controllers)){
            $this->controllers = $controllers;
        }
        $data = Admin_menu::get();
        $data = json_decode($data,true);
        return $this->getMenuData($data);
    }

    private function getMenuData($data,$parent_id=0){
        $menu = [];
        foreach ($data as $key=>$value){
            $value['title'] = $value['name'];
            if($value['parent_id'] == $parent_id){
                $menu[] = [
                    'id' => $value['id'],
                    'title' => $value['name'],
                    'field' => 'menu[]',
                    'spread' => true,
                ];
                unset($data[$key]);
            }
        }
//        dd($menu);
        foreach ($menu as $k=>&$v){
            $v['children'] = $this->getMenuData($data,$v['id']);
            if (empty($v['children'])){
                if (!empty($this->controllers) && in_array($v['id'],$this->controllers)){
                    $v['checked'] = 1;
                }
            }
        }
        return $menu;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->get('menu');
        $group_name = $request->get('group_name');
        $group_info = $request->get('group_info');
        $group = Admin_group::create(['controller'=>json_encode($id),'group_name'=>$group_name,'group_info'=>$group_info]);
        if ($group)
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

    public function search(Request $request)
    {
        $input = $request->all();
        $username = $input['username_s'];
        $start = $input['start'];
        $end = $input['end'];
        $pageNum = isset($input['num'])&&$input['num']?$input['num']:1; //给num字段加个默认值
        $nowpage = $input['nowpage'];
        $lastpage = $input['lastpage'];

        if ($pageNum == -1){
            $pageNum = $nowpage-1;
            if ($pageNum <= 0)
            {
                $pageNum = 1;
            }

        }else if ($pageNum == -2)
        {
            $pageNum = $nowpage+1;
            if ($pageNum >= $lastpage)
            {
                $pageNum = $lastpage;
            }
        }
        $where = [];
        if($username)
        {
            $where[] = ['Admin_groups.group_name','like',"%{$username}%"];
        }
        if($start)
        {
            $where[] = ['Admin_groups.created_at','>=',$start];
        }
        if ($end)
        {
            $where[] = ['Admin_groups.created_at','<=',$end];
        }
        $d = DB::table('Admin_groups')
            ->where($where)
            ->orderBy('id','desc')
            ->paginate(5,'*','',$pageNum);//分页

//        dump($d->currentPage());

        $page = $d->links();

        $th = preg_replace('/<a .*?href="(.*?)".*?>/is','<a href="javascript:;" class="djfy">',$page);
        if ($d)
        {
            $data = [
                'status' => '1',
                'data' =>$d->items(),
                'page' => trim($th),
                'nowpage'=>$d->currentPage(),
                'lastpage' => $d->lastPage(),
            ];
        }
        else
        {
            $data = [
                'status' => '0',
                'message' => '搜索失败'
            ];
        }
        return $data;
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
        $status = Admin_group::where('id',$id)->value('status');
        if ($status == 1){
            Admin_group::where('id',$id)->update(['status'=>'0']);
            $data =  [
                'status' => '1',
            ];
        }else
        {
            Admin_group::where('id',$id)->update(['status'=>'1']);
            $data = [
                'status' => '0',
            ];
        }

        return $data;

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $datas = Admin_group::where('id',$id)->get();
        $result = Admin_group::where('id',$id)->first();
        $menu_role = json_decode($result->controller,true);

        $menu = json_encode($this->getAllMenu($menu_role));
       // dd($menu);

        return view('admin.group.edit',compact('datas','menu'));
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
        $i = $request->get('menu');
        $group_name = $request->get('group_name');
        $group_info = $request->get('group_info');
        $group = Admin_group::where('id',$id)->update(['controller'=>json_encode($i),'group_name'=>$group_name,'group_info'=>$group_info]);
        if ($group)
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

        $del = Admin_group::where('id',$id)->delete();
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
            $res = Admin_group::where('id',$v)->delete();
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
