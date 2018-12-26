<?php
require_once '../../extr/db_connect.php';

$limit = $_GET['limit'];
$page = ($_GET['page']-1)*$limit;

$sql = "select clothes_stock.* from clothes_stock join clothes_name on clothes_stock.clothes_name = clothes_name.clothes_name and clothes_name.is_del=0 order by clothes_name desc limit $page,$limit";
// echo $sql;
$sql_count = "select count(clothes_stock.id) as count from clothes_stock join clothes_name on clothes_stock.clothes_name = clothes_name.clothes_name and clothes_name.is_del=0";
// $result = $db->query($sql);

$datas = $db->query($sql);
$count = $db->query($sql_count);
$response = array(
	'code'=>0,
	'msg'=>'',
	'data'=>$datas,
	'count'=>$count[0]['count']
	);
echo json_encode($response);
