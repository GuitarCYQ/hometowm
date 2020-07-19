@extends('admin.layout.main')

@section('content')

    <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
{{--                        <form class="layui-form-search layui-col-space5">--}}
{{--                            <div class="layui-inline layui-show-xs-block">--}}
{{--                                <input class="layui-input"  autocomplete="off" value="2020-04-05" placeholder="开始日" name="start" id="start">--}}
{{--                            </div>--}}
{{--                            <div class="layui-inline layui-show-xs-block">--}}
{{--                                <input class="layui-input"  autocomplete="off" value="2020-06-05" placeholder="截止日" name="end" id="end">--}}
{{--                            </div>--}}
{{--                            <div class="layui-inline layui-show-xs-block">--}}
{{--                                <input type="text" name="username" id="username_s" placeholder="请输入菜单名" autocomplete="off" class="layui-input">--}}
{{--                            </div>--}}
{{--                            <div class="layui-inline layui-show-xs-block">--}}
{{--                                <button class="layui-btn searbtn" lay-submit="" lay-filter=""><i class="layui-icon">&#xe615;</i></button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn" onclick="xadmin.open('添加用户','{{route('admin.menu.create')}}',600,600)"><i class="layui-icon"></i>添加</button>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="ids[]"  lay-skin="primary">
                                </th>
                                <th>ID</th>
                                <th>排序</th>
                                <th>菜单名称</th>
                                <th>状态</th>
                                <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach($data as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="ck" name="delall_id[]"  lay-skin="primary" value="{{$item['id']}}">
                                    </td>
                                    <td>{{ $item['id'] }}</td>
                                    <td><input class="sort_order" name="sort_order[]" val="{{$item['id']}}" style="width:50px; text-align: center;" type="text" value='{{ $item['sort_order'] }}'></td>
                                    <td>
{{--                                        @if($item['parent_id'] == 0)--}}
{{--                                            |_--}}
{{--                                        @elseif($item['parent_id'] == 1)--}}
{{--                                            |___--}}
{{--                                        @elseif($item['parent_id'] == 2)--}}
{{--                                            |______--}}
{{--                                        @else--}}
{{--                                            |________--}}
{{--                                        @endif--}}
                                        {{ $item['name'] }}
                                    </td>
                                    <td class="td-status">
                                        <span class="layui-btn layui-btn-normal layui-btn-mini @if($item['status'] == 0) layui-btn-disabled @endif">@if($item['status'] == 0) 已停用 @else 已启用 @endif</span>
                                    </td>
                                    <td class="td-manage">
                                        <a onclick="member_stop(this,{{ $item['id'] }} )" href="javascript:;"  title="启用">
                                            <i class="layui-icon">&#xe601;</i>
                                        </a>
                                        <a title="编辑"  onclick="xadmin.open('编辑','{{route('admin.menu.edit',$item['id'])}}')" href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" val="{{ route('admin.menu.destroy',$item['id']) }}" class="delbtn" onclick="member_del(this,'{{$item['id']}}')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td><button class="layui-btn order_edit" >修改</button></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div class="page">
                            <div>
{{--                                {{$data->links()}}--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>



        layui.use(['laydate','form'], function(){
            var laydate = layui.laydate;
            var form = layui.form;

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });


            //排序
            $(".order_edit").click(function () {
                // var sort_order = $(".sort_order").val();

                var id = new Array();
                var sort_order = new Array();
                $('.sort_order').each(function () {
                    id.push($(this).attr('val'));
                    sort_order.push($(this).val());
                });

                $.ajax({
                    url: '{{ route('admin.menu.sort') }}',
                    type: 'post',
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    data: {
                        'id': id,
                        'sort_order': sort_order,
                    },
                    dataType: 'json',
                    success:function(data){
                        console.log(url);
                    },
                });
            });




        });


        /*用户-停用*/
        function member_stop(obj,id){
            layer.confirm('确认要不显示吗？',function(index){

                if($(obj).attr('title')=='启用'){
                    $.ajax({
                        type:"post",//提交的方式
                        url:"{{route('admin.menu.status')}}",//路径
                        dataType:'json',
                        headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                        data : {
                            id:id
                        },//数据，这里使用的是Json格式进行传输
                        success:function (data,emp) {
                            if (data.status == 1){
                                //发异步把用户状态进行更改
                                $(obj).attr('title','停用')
                                $(obj).find('i').html('&#xe62f;');

                                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                                layer.msg('不显示!',{icon: 5,time:1000});
                            }
                            else{
                                $(obj).attr('title','启用')
                                $(obj).find('i').html('&#xe601;');

                                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                                layer.msg('已显示!',{icon: 6,time:1000});
                            }

                            if (emp.status == 2)
                            {
                                layer.msg(data.message, {icon: 5, time: 1000});
                            }
                        }
                    });
                }
            });
        }

        /*用户-删除*/
        function member_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                var url = $(obj).attr('val');
                // alert(url);return;
                //发异步删除数据
                $.ajax({
                    type:"DELETE",//提交的方式
                    url:url,//路径
                    dataType:'json',
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    data : {
                        id:id
                    },//数据，这里使用的是Json格式进行传输
                    success : function (data) {
                        if (data.status == 1) {
                            $(obj).parents("tr").remove();
                            layer.msg(data.message, {icon: 6, time: 1000});
                        }else
                        {
                            // $(obj).parents("tr").remove();
                            layer.msg(data.message, {icon: 5, time: 1000});
                        }
                    }
                });
            });
        }

        function delAll (argument) {
            var ck = $(".ck");
            // var data = tableCheck.getData();

            var delall_id = new Array();
            for (var i = 0;i < ck.length;i++)
            {
                if (ck[i].checked)
                {
                    delall_id.push(ck[i].value);
                }
            }
            if (delall_id == null || delall_id.length == 0)
            {
                return false;
            }

            layer.confirm('确认要删除吗？',function(index){
                //捉到所有被选中的，发异步进行删除
                $.ajax({
                    type:"DELETE",//提交的方式
                    url:'{{route('admin.menu.delAll',$item['id'])}}',//路径
                    dataType:'json',
                    headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                    data : {
                        id:delall_id
                    },//数据，这里使用的是Json格式进行传输
                    success : function (data,emp) {
                        // console.log(data);

                        if (data.status == 1) {
                            layer.msg('删除成功', {icon: 1});
                            $(".layui-form-checked").not('.header').parents('tr').remove();
                            layer.msg(data.message, {icon: 6, time: 1000});
                        }else
                        {
                            // $(obj).parents("tr").remove();
                            layer.msg(data.message, {icon: 5, time: 1000});
                        }

                        if (emp.status == 2)
                        {
                            layer.msg(data.message, {icon: 5, time: 1000});
                        }
                    }
                });

            });
        }

    </script>

@endsection