<?php
require_once '../../extr/db_connect.php';

$limit = $_GET['limit'];
$page = ($_GET['page'] - 1) * $limit;

$sql = "select * from info order by id desc limit $page,$limit";
$sql_count = "select count(id) as count from info";

$datas = $db->query($sql);
$count = $db->query($sql_count);
$response = array(
    'code' => 0,
    'msg' => '',
    'data' => $datas,
    'count' => $count[0]['count'],
);
echo json_encode($response);

// echo json_encode($result);
