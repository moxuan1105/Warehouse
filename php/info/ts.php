<?php
/**
 *  停售商品信息
 * 
 */

require_once '../../extr/db_connect.php';


$limit = $_GET['limit'];
$page = ($_GET['page']-1)*$limit;

$sql = "select * from ks where is_del=1 limit $page,$limit";
$sql_count = "select count(id) as count from ks where is_del=1";

$datas = $db->query($sql);
$count = $db->query($sql_count);
$response = array(
	'code'=>0,
	'msg'=>'',
	'data'=>$datas,
	'count'=>$count[0]['count']
	);
echo json_encode($response);