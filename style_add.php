<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:./login.html');
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>新增款式</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="./css/font.css">
    <link rel="stylesheet" href="./css/xadmin.css">
    <!-- <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> -->
    <script type="text/javascript" src="./js/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="./js/xadmin.js"></script>
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <div class="x-body layui-anim layui-anim-up">
        <form class="layui-form">
            <div class="layui-form-item">
                <label for="clothes_name" class="layui-form-label">
                    <span class="x-red">*</span>款式名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="clothes_name" name="clothes_name" required="" lay-verify="" autocomplete="off"
                        class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="clothes_color" class="layui-form-label">
                    <span class="x-red">*</span>款式颜色
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="clothes_color" name="clothes_color" required="" lay-verify="" autocomplete="off"
                        class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>实例：红色|蓝色|……
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button class="layui-btn" lay-filter="add" lay-submit="">
                    增加
                </button>
            </div>
        </form>
    </div>
    <script>
        layui.use(['form', 'layer'], function() {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer;

            //监听提交
            form.on('submit(add)', function(data) {
                var datas = data.field;
                // 发异步，把数据提交给php
                $.ajax({
                    url: "./src/add/add.php",
                    data: {
                        'clothes_name': datas.clothes_name,
                        'clothes_color': datas.clothes_color
                    },
                    dataType: 'json',
                    success: function(data) {
                        // console.log(data);
                        layer.msg("增加成功", {
                            time: 500,
                            icon: 6
                        }, function() {
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            // console.log(index);
                            //关闭当前frame
                            parent.layer.close(index);
                            parent.location.reload();
                        });
                    },
                });
                // 阻止submit的提交
                return false;
            });

        });
    </script>
</body>

</html>
