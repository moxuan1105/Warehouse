<?php
require_once '../../extr/db_connect.php';

$limit = $_GET['limit'];
$page = ($_GET['page']-1)*$limit;

$sql = "select kc.* from kc join ks on kc.ks = ks.ks and ks.is_del=0 order by ks desc limit $page,$limit";
// echo $sql;
$sql_count = "select count(kc.id) as count from kc join ks on kc.ks = ks.ks and ks.is_del=0";
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
