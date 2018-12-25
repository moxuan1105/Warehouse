<?php
/**
 *  新增款式
 *
 *    
 * */ 
header("Content-type:text/html;charset=utf-8");
// 引入pdo
require_once '../../extr/db_connect.php';

$flag = false;

$ks = $_GET['ks'];
$ys = $_GET['ys'];
$ys = trim($ys,'|');
$arrayDataValue = array(
    'ks'=> addslashes($ks),
    'ys'=> addslashes($ys),
    'createTime'=>time()
);
$yssz = explode('|',$ys);
$yssz = array_filter($yssz);
// 事务开始
$db->beginTransaction();
try {
    // 插入ks表
    $table = "ks";
    $db->insert($table,$arrayDataValue);
    for($i=0;$i<count($yssz);$i++){
        // 对于kc表进行插入操作
        $sql = 'insert into kc(`ks`,`ys`) value("'.$ks.'","'.$yssz[$i].'")';
        $db->execSql($sql);
    }
    // 提交
    $db->commit();
    $flag = true;
    echo json_encode($flag);
} catch (Exception $e) {
    $db->rollback();
    $a = $e->getMessage();
    echo json_encode($a);
}





