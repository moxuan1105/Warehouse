<?php
require "../../extr/db_connect.php";

// echo "aaa";
// var_dump($_REQUEST);
$id = addslashes($_POST['id']);

$ks = addslashes($_POST['ks']);

$ys = addslashes($_POST['ys']);

$cm = addslashes($_POST['cm']);

$sl = addslashes($_POST['sl']);

$sql_kc = "update `kc` set `$cm`=`$cm`-$sl where `id` = '$id'";
$sql_info = "insert into `info`(ks,ys,cm,sl,action_time,action,czr) value('$ks','$ys','$cm',$sl,".time().",'出库','admin')";
$flag = false;
// 进行事务处理
$db->beginTransaction();
try {
    $db->execSql($sql_kc);
    $db->execSql($sql_info);
    $db->commit();
    $flag = true;
} catch (Exception $e) {
     $a = $e->getMessage();
     $db->rollback();
     echo json_encode($a);
     die;
}

echo json_encode($flag);