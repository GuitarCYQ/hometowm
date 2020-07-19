<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\CommentController;
use App\Models\Admin;
use App\Models\Admin_group;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AdminController extends CommentController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('admins')
            ->leftJoin('Admin_groups', 'admins.group_id', '=', 'Admin_groups.id')
            ->select('admins.*','Admin_groups.group_name')
            ->orderBy('id','desc')
            ->paginate(5);

        return view('admin.admin.list',compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = Admin_group::all();
        return view('admin.admin.create',compact('group'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $username = $input['username'];
        $password = Crypt::encrypt($input['password']);
        $email = $input['email'];
        $group_id = $input['group_id'];
        $admin = Admin::create(['username'=>$username,'password'=>$password,'email'=>$email,'group_id'=>$group_id]);
        if ($admin)
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
        $status = Admin::where('id',$id)->value('status');
        if ($status == 1){
            Admin::where('id',$id)->update(['status'=>'0']);
            $data =  [
                'status' => '1',
            ];
        }else
        {
            Admin::where('id',$id)->update(['status'=>'1']);
            $data = [
                'status' => '0',
            ];
        }

        return $data;

    }

    /*
     *恢复删除
     */
    public function restore(Request $request)
    {
//        dd($request->get('id'));
        DB::connection()->enableQueryLog();
        $id = $request->get('id');
        $result = Db::table('admins')->where('id','=',$id)->update(['deleted_at'=>null]);
        if($result !== false){
            $data = [
                'status' => '1',
                'message' => '恢复成功'
            ];
        }else{
            $data = [
                'status' => '0',
                'message' => '恢复失败'
            ];
        }
        return $data;
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
            $where[] = ['admins.username','like',"%{$username}%"];
        }
        if($start)
        {
            $where[] = ['admins.created_at','>=',$start];
        }
        if ($end)
        {
            $where[] = ['admins.created_at','<=',$end];
        }
        $d = DB::table('admins')
            ->where($where)
            ->leftJoin('Admin_groups', 'admins.group_id', '=', 'Admin_groups.id')
            ->select('admins.*','Admin_groups.group_name')
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $group = Admin_group::all();
        $data = Admin::where('id',$id)->get();
        return view('admin.admin.edit',compact('group','data','id'));
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
        $input = $request->except('username');
        $password = $input['password'];
        $email = $input['email'];
        $group_id = $input['group_id'];
        if (trim($password) == '')
        {
            $admin = Admin::where('id',$id)->update(['email'=>$email,'group_id'=>$group_id]);
        }else{
            $admin = Admin::where('id',$id)->update(['password'=>Crypt::encrypt($password),'email'=>$email,'group_id'=>$group_id]);
        }

        if ($admin)
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
        $del = Admin::where('id',$id)->delete();
        if ($del)
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
            $res = Admin::where('id',$v)->delete();
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
