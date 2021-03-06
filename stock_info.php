<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>操作记录</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="./css/font.css">
	<link rel="stylesheet" href="./css/xadmin.css">
	<!-- <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> -->
	<script type="text/javascript" src="./js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="./js/timestampToTime.js"></script>
	<script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
	<script type="text/javascript" src="./js/xadmin.js"></script>
	<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
	<!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		.layui-table-view .layui-table {
			width:100%
		}
	</style>

</head>

<body>
	<div class="x-nav">
		<span class="layui-breadcrumb">
			<a href="">首页</a>
			<a href="">演示</a>
			<a>
				<cite>库存信息</cite></a>
		</span>
		<a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);"
		 title="刷新">
			<i class="layui-icon" style="line-height:30px">ဂ</i></a>
	</div>
	<div class="x-body">
		<div class="layui-row">
			<form class="layui-form layui-col-md12 x-so">
				<input class="layui-input" placeholder="开始日" name="start" id="start" lay-verify="required" readonly>
				<input class="layui-input" placeholder="截止日" name="end" id="end" lay-verify="required" readonly>
				<div class="layui-input-inline">
					<select name="action">
						<option value="">操作状态</option>
						<option value="0">入库</option>
						<option value="1">出库</option>
					</select>
				</div>
				<input type="text" name="clothes_name" placeholder="请输入款式名称" autocomplete="off" class="layui-input">
				<button class="layui-btn" lay-submit lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
			</form>
		</div>
		<table id="sp_action" lay-filter="sp_show"></table>


	</div>
	<script>
		layui.use(['table','form'],function(){
			var table = layui.table;
			var form = layui.form;

			table.render({
				elem:'#sp_action',
				url:'./src/clothes_stock/clothes_stock.php?action=stock_info',
				page:true,
				limit:15,
				limits:[15],
				loading:true,
				id:'spAction',
				cols:[[
					{field:'id',title:'id',unresize:true,align:'center',hide:true},
					{field:'clothes_name',title:'款式名称',unresize:true,align:'center'},
					{field:'clothes_color',title:'颜色',unresize:true,align:'center'},
					{field:'clothes_size',title:'尺码',unresize:true,align:'center'},
					{field:'clothes_num',title:'数量',unresize:true,align:'center'},
					{field:'action_time',title:'操作时间',unresize:true,align:'center',sort:true,templet:'#time'},
					{field:'action',title:'操作项',unresize:true,align:'center'},
					{field:'czr',title:'操作人',unresize:true,align:'center'}
				]],
			});


			form.on('submit(sreach)',function(data){
				var datas = data.field;
				var startTime = datas.start;
				var endTime = datas.end;
				var action = datas.action;
				var clothes_name = datas.clothes_name;
				// 表格重载
				table.reload('spAction', {
					url: "./src/clothes_stock/clothes_stock.php?action=stock_info_sreach",
					method:'post',
					page: {
						curr: 1
					},
					where: {
						startTime: startTime,
						endTime:endTime,
						action:action,
						clothes_name:clothes_name
					}
				});
				return false;
			})
		});


		layui.use('laydate', function(){
			var laydate = layui.laydate;
			//执行一个laydate实例
			laydate.render({
				elem: '#start' //指定元素
			});

			//执行一个laydate实例
			laydate.render({
				elem: '#end' //指定元素
			});
		});

	</script>

	<script id="time" type="text/html">
		{{ timestampToTime(d.action_time) }}
	</script>
</body>

</html>
