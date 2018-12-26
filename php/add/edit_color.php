<?php

/**
 *  新增款式的颜色
 */

require "../../extr/db_connect.php";

$clothes_name = $_POST['clothes_name'];
$clothes_color = $_POST['clothes_color'];
$add_clothes_color = $_POST['add_clothes_color'];
$add_clothes_color = trim($add_clothes_color, '|');

$new_clothes_color = $clothes_color . '|' . $add_clothes_color;
$new_clothes_color = addslashes($new_clothes_color);
$clothes_name = addslashes($clothes_name);

$add_clothes_color_array = explode('|', $add_clothes_color);
$add_clothes_color_array = array_filter($add_clothes_color_array);

$sql_clothes_name = "update `clothes_name` set clothes_color= '{$new_clothes_color}' , updateTime = " . time() . " where `clothes_name` = '{$clothes_name}' ";

$db->beginTransaction();

$flag = false;
try {

    $db->execSql($sql_clothes_name);
    // 对于多个颜色插入到 clothes_stock 表中
    for ($i = 0; $i < count($add_clothes_color_array); $i++) {
        // 对于clothes_stock表进行插入操作
        $sql = 'insert into clothes_stock(`clothes_name`,`clothes_color`) value("' . $clothes_name . '","' . $add_clothes_color_array[$i] . '")';
        $db->execSql($sql);
    }
    $db->commit();
    $flag = true;
    echo json_encode($flag);
} catch (Exception $e) {
    $db->rollback();
    $a = $e->getMessage();
    echo json_encode($a);
}
