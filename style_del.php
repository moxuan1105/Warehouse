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
	<title>停售款式</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<!-- <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" /> -->
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="./css/font.css">
	<link rel="stylesheet" href="./css/xadmin.css">
	<!-- <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> -->
	<script type="text/javascript" src="./js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="./js/timestampToTime.js" charset="utf-8"></script>
	<script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
	<script type="text/javascript" src="./js/xadmin.js"></script>
	<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
	<!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!--  -->

<body>
	<div class="x-nav">
		<span class="layui-breadcrumb">
			<a href="">首页</a>
			<a href="">款式管理</a>
			<a>
				<cite>停售款式</cite>
			</a>
		</span>
		<a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);"
		 title="刷新">
			<i class="layui-icon" style="line-height:30px">ဂ</i></a>
	</div>
	<div class="x-body">
		<table id="tsshow" lay-filter="tsshow"></table>
	</div>
	<script>
		layui.use('table', function(){
			var table = layui.table;

			table.render({
				elem: '#tsshow',
				// height: 'full-250',
				url: './src/clothes_style/clothes_style.php?action=style_stop_list', //数据接口
				page: true, //开启分页
				limit: 15,
				limits: [15],
				loading: true,
				cols: [[ //表头
					{field: 'id', title: 'id', unresize:true, sort: true, fixed: 'left',align:'center',width:80},
					{field: 'clothes_name', title: '款式名称',align:'center', unresize:true},
					{field: 'clothes_color', title: '颜色',align:'center', unresize:true},
					{field: 'createTime', title: '创建时间',  sort: true,align:'center', unresize:true,templet:'#createTime'},
					{field: 'updateTime', title: '更新时间',  sort: true,align:'center', unresize:true,templet:'#updateTime'},
					{field: 'is_del', title: '状态', unresize:true,align:'center',templet:'#is_del'}
					<?php
if ($_SESSION['username'] == 'admin') {
    ?>
					,{field: 'action', title: '操作',align:'center', toolbar: '#barTool', unresize:true}
					<?php
}
?>
				]],
				// 初始化排序
				initSort: {
					field: 'id', //排序字段，对应 cols 设定的各字段名
					type: 'desc' //排序方式  asc: 升序、desc: 降序、null: 默认排序
				}
			});

			table.on('tool(tsshow)',function(obj){
				var data = obj.data;
				var layEvent = obj.event;
				var tr = obj.tr;
				if(layEvent === 'recovery'){
					layer.confirm('确定恢复款式: ' + data.clothes_name + ' 吗?', function (index) {
						$.ajax({
							url: "./src/clothes_style/clothes_style.php?action=style_recovery",
							data: { 'id': data.id },
							type: "post",
							dataType: 'json',
							success: function (data) {
								if (data) {4
									layer.msg('恢复成功', {
										icon: 1,
										time: 500
									}, function () {
										layer.close(index);
										table.reload('tsshow', {
											page: true
										})
									})
								}else{
									alert('恢复失败');
								}
							},
							error: function (data) {
								alert('恢复失败');
							}
						})
					})
				}
			});
		});
  	</script>
	<?php
if ($_SESSION['username'] == 'admin') {
    ?>
	<script type="text/html" id="barTool">
		<a class="layui-btn layui-btn-xs" lay-event="recovery">恢复</a>
	</script>
	<?php
}
?>
	<script id="is_del" type="text/html">
		{{# if(d.is_del == 1 ){ }}
		<span class="layui-btn layui-btn-danger layui-btn-mini" style="line-height: inherit;">已停售</span>
		{{# } }}
	</script>
	<script id="createTime" type="text/html">
		{{ timestampToTime(d.createTime) }}
	</script>
	<script id="updateTime" type="text/html">
		{{ timestampToTime(d.updateTime) }}
	</script>
</body>

</html>