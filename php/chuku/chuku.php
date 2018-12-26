<?php
require "../../extr/db_connect.php";

// echo "aaa";
// var_dump($_REQUEST);
$id = addslashes($_POST['id']);

$clothes_name = addslashes($_POST['clothes_name']);

$clothes_color = addslashes($_POST['clothes_color']);

$clothes_size = addslashes($_POST['clothes_size']);

$clothes_num = addslashes($_POST['clothes_num']);

$sql_clothes_stock = "update `clothes_stock` set `$clothes_size`=`$clothes_size`-$clothes_num where `id` = '$id'";
$sql_info = "insert into `info`(clothes_name,clothes_color,clothes_size,clothes_num,action_time,action,czr) value('$clothes_name','$clothes_color','$clothes_size',$clothes_num,".time().",'出库','admin')";
$flag = false;
// 进行事务处理
$db->beginTransaction();
try {
    $db->execSql($sql_clothes_stock);
    $db->execSql($sql_info);
    $db->commit();
    $flag = true;
    echo json_encode($flag);
} catch (Exception $e) {
     $a = $e->getMessage();
     $db->rollback();
     echo json_encode($a);
}

