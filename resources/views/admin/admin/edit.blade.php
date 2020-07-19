@extends('admin.layout.main')


@section('content')

    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form" >
                @csrf
                @foreach($data as $item)
                <div class="layui-form-item">
                    <label for="username" class="layui-form-label">
                        <span class="x-red">*</span>登录名
                    </label>
                    <div class="layui-input-inline layui-input-disabled">
                        <input type="text" id="username" name="username" required="" lay-verify="required"
                               autocomplete="off" disabled value="{{$item['username']}}" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>将会成为您唯一的登入名
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_email" class="layui-form-label">
                        <span class="x-red">*</span>邮箱
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="email" value="{{$item['email']}}" name="email" required="" lay-verify="email"
                               autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label"><span class="x-red">*</span>角色</label>
                    <div class="layui-input-block">
                        @foreach($group as $g)
{{--                            <input type="checkbox" class="group_id" value="{{$g['id']}}" name="group_id[]" lay-skin="primary" title="{{ $g['group_name'] }}">--}}
                            <input type="radio" @if($item['group_id'] == $g['id']) checked @endif class="group_id" value="{{$g['id']}}" name="group_id" lay-skin="primary" title="{{ $g['group_name'] }}">
                        @endforeach
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_pass" class="layui-form-label">
                        <span class="x-red">*</span>密码
                    </label>
                    <div class="layui-input-inline">
                        <input type="password" id="password" name="password"
                               autocomplete="off" class="layui-input">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        6到16个字符
                    </div>
                </div>

                <div class="layui-form-item">
                    <label for="L_repass" class="layui-form-label">
                    </label>
                    <button  class="layui-btn" lay-filter="add" lay-submit="">
                        增加
                    </button>
                </div>
                    @endforeach
            </form>
        </div>
    </div>

@endsection

@section('js')

    <script>




        layui.use(['form', 'layer'],

            function() {
                $ = layui.jquery;
                var form = layui.form,
                    layer = layui.layer;

                // 自定义验证规则
                form.verify({
                    username: function(value) {
                        if (value.length < 5) {
                            return '昵称至少得5个字符啊';
                        }
                    },
                    pass: [/(.+){6,12}$/, '密码必须6到12位'],
                    // repass: function(value) {
                    //     if ($('#password').val() != $('#L_repass').val()) {
                    //         return '两次密码不一致';
                    //     }
                    // }
                });


                //监听提交
                form.on('submit(add)',
                    function(data) {
                        var username = $("#username").val();
                        var password = $("#password").val();
                        var email = $("#email").val();
                        //单选
                        var group_id = $("input[name='group_id']:checked").val()


                        //发异步，把数据提交给php
                        $.ajax({
                            url:'{{ route('admin.admin.update',$id) }}',
                            type: 'PUT',
                            data: {
                                "username" : username,
                                "password" : password,
                                "email" : email,
                                "group_id" : group_id,
                            },
                            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' },
                            dataType: 'json',
                            success:function (data) {
                                // console.log(data);return;
                                if (data.status == 1)
                                {
                                    layer.alert(data.message, {icon: 6}, function() {
                                        //关闭当前frame
                                        xadmin.close();
                                        // 可以对父窗口进行刷新
                                        xadmin.father_reload();
                                    });
                                }else {
                                    layer.alert(data.message, {icon: 5}, function() {
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