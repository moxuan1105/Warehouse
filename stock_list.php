<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>库存列表</title>
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
	<script type="text/javascript" src="./js/timestampToTime.js"></script>
	<!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
	<!--[if lt IE 9]>
      <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
      <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
	<style>
		.layui-table-view .layui-table {
			width:100%
		}

    	.x-body>table{
			overflow-y: scroll;
		}

	</style>
</head>

<body>
	<div class="x-nav">
		<span class="layui-breadcrumb">
			<a href="">首页</a>
			<a href="">演示</a>
			<a>
				<cite>库存列表</cite>
			</a>
		</span>
		<a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);"
		 title="刷新">
			<i class="layui-icon" style="line-height:30px">ဂ</i></a>
	</div>
	<div class="x-body">
		<table id="clothes_stocklist" lay-filter="clothes_stocklist"></table>
	</div>

	<!-- 页面编辑 -->
	<form class="layui-form" id="editForm" onsubmit="return false;" style="display:none;">
		<div class="layui-form-item" style="margin-top:20px;display:none ;">
			<label class="layui-form-label">id</label>
			<div class="layui-input-inline">
				<input type="text" id="id" name="id" required lay-verify="required" placeholder="款式名称" autocomplete="off" class="layui-input"
				 readonly>
			</div>
		</div>
		<div class="layui-form-item" style="margin-top:20px;">
			<label class="layui-form-label">款式名称</label>
			<div class="layui-input-inline">
				<input type="text" id="clothes_name" name="clothes_name" required lay-verify="required" placeholder="款式名称"
				 autocomplete="off" class="layui-input" readonly>
			</div>
		</div>

		<div class="layui-form-item">
			<label class="layui-form-label">款式颜色</label>
			<div class="layui-input-inline">
				<input type="text" id="clothes_color" name="clothes_color" required lay-verify="required" placeholder="款式颜色"
				 autocomplete="off" class="layui-input" readonly>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">选择尺码</label>
			<div class="layui-input-inline">
				<select name="clothes_size" id='clothes_size' lay-verify="required">
					<option value="">选择尺码</option>
					<option value="S">S</option>
					<option value="M">M</option>
					<option value="L">L</option>
					<option value="XL">XL</option>
					<option value="XXL">XXL</option>
					<option value="XXXL">XXL</option>
				</select>
			</div>
		</div>

		<div class="layui-form-item">
			<label class="layui-form-label">数量</label>
			<div class="layui-input-inline">
				<input type="text" id="clothes_num" name="clothes_num" required lay-verify="required|number" autocomplete="off"
				 class="layui-input">
			</div>
		</div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" lay-submit lay-filter="editform">提交</button>
			</div>
		</div>
	</form>



	<script>
		layui.use('table',function(){
			var table = layui.table;
			table.render({
				elem:'#clothes_stocklist',
				url:'./src/clothes_stock/clothes_stock.php?action=stock_list',
				page: true, //开启分页
				limit: 15,
				limits: [15],
				loading: true,
				cellMinWidth: 80,
				// id:'idTest',
				cols: [[ //表头
					{field: 'id', title: 'id', sort: true, align:'center',hide:true},
					{field: 'clothes_name', title: '款式名称',align:'center', unresize:true, sort: true,},
					{field: 'clothes_color', title: '颜色',align:'center', unresize:true},
					{field: 'S', title: 'S', align:'center', unresize:true},
					{field: 'M', title: 'M', unresize:true,align:'center'},
					{field: 'L', title: 'L',align:'center',  unresize:true},
					{field: 'XL', title: 'XL',align:'center', unresize:true},
					{field: 'XXL', title: 'XXL',align:'center', unresize:true},
					{field: 'XXXL', title: 'XXXL',align:'center', unresize:true},
					{field: 'clothes_stock', title: '库存',align:'center', unresize:true,templet:'#clothes_stock'},
					{field: 'action', title: '操作',align:'center', toolbar: '#barTool', unresize:true,width:150},
				]],
			});

			table.on('tool(clothes_stocklist)',function(obj){
				var data = obj.data;
				var layEvent = obj.event;
				var layer= layui.layer;

				if(layEvent === 'edit') {
					layer.open({
						// type 1表示是加载centent的内容 2加载centent链接的页面
						type: 1,
						title: '商品入库',
						area: ['500px;', '600px'],
						anim: 1, //弹出动画
						id: 'btn_edit',
						moveType: 1,
						// shade: 0.8,
						content: $('#editForm'),
						success: function (layer, index) {
							$('#clothes_name').val(data.clothes_name);
							$('#clothes_color').val(data.clothes_color);
							$('#id').val(data.id);
						}
					});
				} else if(layEvent === 'del') {
					layer.open({
						// type 1表示是加载centent的内容 2加载centent链接的页面
						type: 1,
						title: '商品出库',
						area: ['500px;', '600px'],
						anim: 1, //弹出动画
						id: 'btn_del',
						moveType: 1,
						// shade: 0.8,
						content: $('#editForm'),
						success: function (layer, index) {
							$('#clothes_name').val(data.clothes_name);
							$('#clothes_color').val(data.clothes_color);
							$('#id').val(data.id);
						}
					});
				}
			});
		});

		layui.use('form',function(){
			var form = layui.form;
			var lock = false;
			form.on('submit(editform)',function(data){
				if(!lock){
					lock = true;
				}else{
					return false;
				}
				var datas = data.field;
				var actionId = data.form.parentNode.id;
				if(actionId === 'btn_edit'){
					// alert(actionId);
					$.ajax({
						url:'./src/clothes_stock/clothes_stock.php?action=stock_add',
						data:{
							'id':datas.id,
							'clothes_name':datas.clothes_name,
							'clothes_color':datas.clothes_color,
							'clothes_size':datas.clothes_size,
							'clothes_num':datas.clothes_num
						},
						dataType:'json',
						type:'post',
						success:function(data){
							if(data == true){
								layer.msg("入库成功", {time:500,icon: 6}, function () {
									// 获得frame索引
									var index = layer.index;
									//关闭当前frame
									layer.close(index);
									window.location.reload();
								});
							}else{
								layer.msg("入库失败", {time:500,icon: 5}, function () {
									// 获得frame索引
									var index = layer.index;
									//关闭当前frame
									layer.close(index);
									window.location.reload();
								});
							}
						},
					});
				}else if(actionId === 'btn_del'){
					$.ajax({
						url:'./src/clothes_stock/clothes_stock.php?action=stock_out',
						data:{
							'id':datas.id,
							'clothes_name':datas.clothes_name,
							'clothes_color':datas.clothes_color,
							'clothes_size':datas.clothes_size,
							'clothes_num':datas.clothes_num
						},
						dataType:'json',
						type:'post',
						success:function(data){
							if(data == true){
								layer.msg("出库成功", {time:500,icon: 6}, function () {
									// 获得frame索引
									var index = layer.index;
									//关闭当前frame
									layer.close(index);
									window.location.reload();
								});
							}else{
								layer.msg("出库失败", {time:500,icon: 5}, function () {
									// 获得frame索引
									var index = layer.index;
									//关闭当前frame
									layer.close(index);
									window.location.reload();
								});
							}
						},
					});
				}
				// console.log(data);
				// console.log(datas);
				// console.log(data.form.parentNode.id);

			});
		});

	</script>
	<script type="text/html" id="barTool">
		<a class="layui-btn layui-btn-xs" lay-event="edit">入库</a>
		<a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">出库</a>
	</script>
	<script id="clothes_stock" type="text/html">
		{{ parseInt(d.S)+parseInt(d.M)+parseInt(d.L)+parseInt(d.XL)+parseInt(d.XXL)+parseInt(d.XXXL)}}
	</script>
</body>

</html>
