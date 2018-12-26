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

$clothes_name = $_GET['clothes_name'];
$clothes_color = $_GET['clothes_color'];
$clothes_color = trim($clothes_color,'|');
$arrayDataValue = array(
    'clothes_name'=> addslashes($clothes_name),
    'clothes_color'=> addslashes($clothes_color),
    'createTime'=>time(),
    'updateTime'=>time()
);
$clothes_colorsz = explode('|',$clothes_color);
$clothes_colorsz = array_filter($clothes_colorsz);
// 事务开始
$db->beginTransaction();
try {
    // 插入clothes_name表
    $table = "clothes_name";
    $db->insert($table,$arrayDataValue);
    for($i=0;$i<count($clothes_colorsz);$i++){
        // 对于clothes_stock表进行插入操作
        $sql = 'insert into clothes_stock(`clothes_name`,`clothes_color`) value("'.$clothes_name.'","'.$clothes_colorsz[$i].'")';
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





