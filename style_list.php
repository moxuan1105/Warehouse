<?php
session_start();
if(!isset($_SESSION['username'])){
    header('Location:./login.html');
}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>款式列表</title>
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="./css/font.css">
	<link rel="stylesheet" href="./css/xadmin.css">
	<!-- <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script> -->
	<script type="text/javascript" src="./js/jquery-3.3.1.js"></script>
	<script type="text/javascript" src="./lib/layui/layui.js" charset="utf-8"></script>
	<script type="text/javascript" src="./js/timestampToTime.js" charset="utf-8"></script>
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

<body class="layui-anim layui-anim-up">
	<div class="x-nav">
		<span class="layui-breadcrumb">
			<a href="">首页</a>
			<a href="">款式管理</a>
			<a>
				<cite>款式列表</cite></a>
		</span>
		<a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);"
		 title="刷新">
			<i class="layui-icon" style="line-height:38px">ဂ</i></a>
	</div>
	<div class="x-body">
		<?php 
			if($_SESSION['username'] == 'admin'){
		?>
		<xblock>
			<button class="layui-btn" onclick="x_admin_show('新增款式','./style_add.php',600,400)"><i class="layui-icon"></i>添加</button>
		</xblock>

		<!-- 页面编辑 -->
		<form class="layui-form" id="editForm" onsubmit="return false;" style="display:none;">
			<div class="layui-form-item" style="margin-top:20px;">
				<label class="layui-form-label">款式名称</label>
				<div class="layui-input-inline">
					<input type="text" id="c_style" name="c_style" required lay-verify="required" placeholder="款式名称" autocomplete="off"
					 class="layui-input" readonly>
				</div>
			</div>

			<div class="layui-form-item">
				<label class="layui-form-label">款式颜色</label>
				<div class="layui-input-inline">
					<input type="text" id="c_color" name="c_color" required lay-verify="required" placeholder="款式颜色" autocomplete="off"
					 class="layui-input" readonly>
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">新增颜色</label>
				<div class="layui-input-inline">
					<input type="text" id="c_color_add" name="c_color_add" required lay-verify="required" autocomplete="off" class="layui-input">
				</div>
				<div class="layui-form-mid layui-word-aux">
					<span class="x-red">*</span>只能增加不能修改
				</div>
			</div>
			<div class="layui-form-item">
				<div class="layui-input-block">
					<button class="layui-btn" lay-submit lay-filter="editform">提交</button>
				</div>
			</div>
		</form>
		<?php 
			}
		?>
		<table id="spshow" lay-filter="spshow"></table>
	</div>
	<script>
		layui.use('table', function(){
			var table = layui.table;    
			//第一个实例
			table.render({
				elem: '#spshow',
				// height: 'full-250',
				url: './php/info/spinfo.php', //数据接口
				page: true, //开启分页
				limit: 15,
				limits: [15],
				loading: true,
				cols: [[ //表头
				/**
				 *  fidld 重要属性  接口返回的值的key为field的值才能对应
				 *  title 显示在页面头部的值
				 * 	unresize 是否可以拖动头部
				 *  sort 排序
				 *  fixed 固定
				 *  align 对齐方式
				 *  width 宽度
				 *  templet 使用自定义模板 把显示的内容按照自己的方式去显示
				 * */
					{field: 'id', title: 'id', unresize:true, sort: true, align:'center',width:80},
					{field: 'clothes_name', title: '款式名称',align:'center', unresize:true},
					{field: 'clothes_color', title: '颜色',align:'center', unresize:true},
					{field: 'createTime', title: '创建时间',  sort: true,align:'center', unresize:true,templet:'#createTime'}, 
					{field: 'updateTime', title: '更新时间',  sort: true,align:'center', unresize:true,templet:'#updateTime'}, 
					{field: 'is_del', title: '状态', unresize:true,align:'center',templet:'#is_del'}
					<?php 
						if($_SESSION['username'] == 'admin'){
					?>
					,{field: 'action', title: '操作',align:'center', toolbar: '#barTool', unresize:true}
					<?php 
						}
					?>
				]],
				initSort: {
					field: 'createTime', //排序字段，对应 cols 设定的各字段名
					type: 'desc' //排序方式  asc: 升序、desc: 降序、null: 默认排序
				}
			});

			table.on('tool(spshow)',function(obj){
				var data = obj.data;
				var layEvent = obj.event;
				var tr = obj.tr;
				if(layEvent === 'edit') {
					layer.open({
						// type 1表示是加载centent的内容 2加载centent链接的页面
						type: 1,
						title: '颜色编辑',
						area: ['500px;', '600px'],
						anim: 1, //弹出动画
						id: 'btn_edit',
						moveType: 1,
						// shade: 0.8,
						content: $('#editForm'),
						success: function (layer, index) {
							$('#c_style').val(data.clothes_name);
							$('#c_color').val(data.clothes_color);
						}
					});
				}else if(layEvent === 'del'){
					layer.confirm('确定删除款式: ' + data.clothes_name + ' 吗?', function (index) {
						$.ajax({
							url: "./php/del/del.php",
							data: { 'id': data.id },
							type: "post",
							dataType: 'json',
							success: function (data) {
								if (data) {
									layer.msg('删除成功', {
										icon: 1,
										time: 500
									}, function () {
										// obj.del();
										layer.close(index);
										table.reload('spshow', {
											page: true
										})
									})
								}else{
									alert('删除失败');
								}
							},
							error: function (data) {
								alert('删除失败');
							}
						})
					})
				}
			});
		});

		layui.use('form',function(){
			var form = layui.form;
			form.on('submit(editform)',function(data){
				var datas = data.field;
				// console.log(layer.index);
				$.ajax({
					url:'./php/add/edit_color.php',
					data:{
						'clothes_name':datas.c_style,
						'clothes_color':datas.c_color,
						'add_clothes_color':datas.c_color_add
					},
					type:'post',
					dataType:'json',
					success:function(data){
						if(data){
							layer.alert("增加成功", {icon: 6}, function () {
								// 获得frame索引
								var index = layer.index;
								//关闭当前frame
								layer.close(index);
								window.location.reload();
							});
						}else{
							layer.alert("增加失败", {icon: 5}, function () {
								// 获得frame索引
								var index = layer.index;
								//关闭当前frame								
								layer.close(index);
								window.location.reload();
							});
						}						
					}
				});
			});
		});
    </script>
	<?php 
		if($_SESSION['username'] == 'admin'){
	?>
	<script type="text/html" id="barTool">
		<a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
    	<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
	</script>
	<?php 
		}
	?>
	<script id="is_del" type="text/html">
		{{# if(d.is_del == 0) }}
		<span class="layui-btn layui-btn-normal layui-btn-mini" style="line-height: inherit;">已启用</span>
	</script>
	<script id="createTime" type="text/html">
		{{ timestampToTime(d.createTime) }}
	</script>
	<script id="updateTime" type="text/html">
		{{ timestampToTime(d.updateTime) }}
	</script>
</body>

</html>