@extends('admin.layout.main')

@section('content')
<div class="layui-fluid">
    <div class="layui-row">
        <form action="" method="post" class="layui-form layui-form-pane">
            @csrf
            @foreach($datas as $d)
                <div class="layui-form-item">
                    <label for="name" class="layui-form-label">
                        <span class="x-red">*</span>角色名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="group_name" value="{{$d['group_name']}}" name="group_name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label class="layui-form-label">
                        拥有权限
                    </label>
                    <div id="menu_tree"></div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="desc" class="layui-form-label">
                        描述
                    </label>
                    <div class="layui-input-block">
                        <textarea placeholder="请输入内容"  id="groupinfo" name="group_info" class="layui-textarea">{{$d['group_info']}}</textarea>
                    </div>
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn" lay-submit="" lay-filter="add">修改</button>
                </div>
            @endforeach
        </form>
    </div>
</div>

@endsection

@section('js')

    <script>
        layui.use(['form','layer','tree'],
            function() {
                var datas = <?php echo $menu;?>;
                $ = layui.jquery;
                var form = layui.form,
                    layer = layui.layer,
                    tree = layui.tree,
                    data = datas;


                //渲染
                tree.render({
                    elem: '#menu_tree',  //绑定元素
                    data:data,
                    showCheckbox: true,//是否显示复选框
                    isJump: true ,//是否允许点击节点时弹出新窗口跳转
                    showLine: true,
                    accordion:true
                });


                //监听提交
                form.on('submit(add)',
                    function(data) {
                        var data_menu = $('form').serialize();

                        //发异步，把数据提交给php
                        $.ajax({
                            type:"PUT",
                            url:"{{ route('admin.group.update',$d['id']) }}",
                            dataType:"json",
                            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                            data:data_menu,
                            success : function (data) {
                                if (data.status == 1)
                                {
                                    layer.alert(data.message, {
                                            icon: 6
                                        },
                                        function() {
                                            //关闭当前frame
                                            xadmin.close();

                                            // 可以对父窗口进行刷新
                                            xadmin.father_reload();
                                        });
                                }else
                                {
                                    layer.alert(data.message, {
                                            icon: 5
                                        },
                                        function() {
                                            //关闭当前frame
                                            xadmin.close();

                                            // 可以对父窗口进行刷新
                                            xadmin.father_reload();
                                        });
                                }
                            }
                        });
                        return false;
                    });


                form.on('checkbox(father)', function(data){

                    if(data.elem.checked){
                        $(data.elem).parent().siblings('td').find('input').prop("checked", true);
                        form.render();
                    }else{
                        $(data.elem).parent().siblings('td').find('input').prop("checked", false);
                        form.render();
                    }
                });
            });



    </script>

@endsection