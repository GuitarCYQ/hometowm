@extends('admin.layout.main')


@section('content')

    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" action="{{ route('admin.menu.store') }}" method="post">
                @csrf
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>菜单名
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="name" name="name" required="" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>

                </div>


                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>父级菜单</label>
                    <div class="layui-input-inline">
                        <select id="parent_id" name="parent_id" class="valid">
                            <option value="0"> ==顶级== </option>
                            @foreach($data as $item)
{{--                                <option value="{{$item['id']}}">@if($item['parent_id'] == 0)|--- @elseif($item['parent_id'] == 1) |------ @else |-------- @endif {{$item['name']}}</option>--}}
                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>是否有子菜单
                    </label>
                        <input name="action" id="action" lay-skin="primary" type="checkbox" value="1">
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">
                        <span class="x-red">*</span>模块
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="models" name="models" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">
                        <span class="x-red">*</span>控制器
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="controller" name="controller" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="" class="layui-form-label">
                        <span class="x-red">*</span>方法
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="methods" name="methods" lay-verify="required"
                               autocomplete="off" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>是否要权限
                    </label>
                    <td>
                        <input type="checkbox" name="type" id="type" lay-text="需要|不需要" value="1"  checked="" lay-skin="switch">
                    </td>
                </div>

                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>是否直接显示
                    </label>
                    <td>
                        <input type="checkbox" name="accord" id="type" lay-text="是|否" value="1"  checked="" lay-skin="switch">
                    </td>
                </div>

                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="add" lay-submit="">
                        增加
                    </button>
                </div>
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

                //自定义验证规则
                // form.verify({
                //     name: function(value) {
                //         if (value.length < 5) {
                //             return '昵称至少得5个字符啊';
                //         }
                //     },
                //     pass: [/(.+){6,12}$/, '密码必须6到12位'],
                //     repass: function(value) {
                //         if ($('#L_pass').val() != $('#L_repass').val()) {
                //             return '两次密码不一致';
                //         }
                //     }
                // });

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

                        //发异步，把数据提交给php
                        $.ajax({
                            type:"POST",
                            url:"{{ route('admin.menu.store') }}",
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
                                    layer.alert("增加成功", {
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
                                    layer.alert("增加失败", {
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

            });</script>

@endsection