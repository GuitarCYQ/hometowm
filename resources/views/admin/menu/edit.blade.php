@extends('admin.layout.main')


@section('content')

    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" action="" method="post">
                @csrf

                @foreach($data as $item)
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>菜单名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" value="{{ $item['name'] }}" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>

                </div>

                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>父级菜单</label>
                    <div class="layui-input-inline">
                        <select id="parent_id" name="parent_id" class="valid">
                            <option value="0" > ==顶级== </option>
                            @foreach($all as $i)
                                <option @if($item['parent_id'] == $i['id'] )   selected="selected"  @endif value="{{$i['id']}}">{{$i['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>是否有子菜单
                    </label>
                        <input name="action" @if($item['action'] == 1) checked @endif id="action" lay-skin="primary" type="checkbox" value="1">
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">
                        <span class="x-red">*</span>模块
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="models" name="models" value="{{ $item['models'] }}" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">
                        <span class="x-red">*</span>控制器
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="controller" name="controller" value="{{ $item['controller'] }}" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">
                        <span class="x-red">*</span>方法
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="methods" name="methods" value="{{ $item['methods'] }}"  lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>是否直接显示
                    </label>
                    <td>
                        <input type="checkbox" name="accord" id="type" lay-text="是|否" value="1"  @if($item['accord'] == 1) checked @endif lay-skin="switch">
                    </td>
                </div>

                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>是否要权限
                    </label>
                    <td>
                        <input type="checkbox" name="type" id="type" value="1" lay-text="需要|不需要"  @if($item['type'] == 1) checked @endif lay-skin="switch">
                    </td>
                </div>
                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="add" lay-submit="">
                        修改
                    </button>
                </div>
                    @endforeach
            </form>
        </div>
    </div>

@endsection

@section('js')

    <script>layui.use(['form', 'layer'],
            function() {
                $ = layui.jquery;
                var form = layui.form,
                    layer = layui.layer;

                //监听提交
                form.on('submit(add)',
                    function(data) {
                        var name = $('#name').val();
                        var parent_id = $('#parent_id').val();
                        var models = $('#models').val();
                        var controller = $('#controller').val();
                        var methods = $('#methods').val();
                        var action = $("input[name='action']:checked").val();
                        var type = $("input[name='type']:checked").val();
                        var accord = $("input[name='accord']:checked").val();

                        if (accord) {
                            accord = 1;
                        }else {
                            accord = 0;
                        }
                        if (action){
                            action = 1;
                        }else{
                            action = 0;
                        }
                        if (type) {
                            type = 1;
                        }else{
                            type = 0;
                        }

                        //发异步，把数据提交给php
                        $.ajax({
                            type:"PUT",
                            url:"{{ route('admin.menu.update',$id) }}",
                            dataType:"json",
                            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                            data:{
                                'name' : name,
                                'parent_id' : parent_id,
                                'action' : action,
                                'models' : models,
                                'controller' : controller,
                                'methods' : methods,
                                'type' : type,
                                'accord' : accord,
                            },
                            success : function (data) {
                                if (data.status == 1)
                                {
                                    layer.alert("修改成功", {
                                        icon: 1
                                    },
                                    function() {
                                        //关闭当前frame
                                        xadmin.close();

                                        // 可以对父窗口进行刷新
                                        xadmin.father_reload();
                                    });
                                }else
                                {
                                    layer.alert("修改失败", {
                                        icon: 2
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

            });</script>

@endsection