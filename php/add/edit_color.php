<?php

    /**
     *  新增款式的颜色
     */

    require "../../extr/db_connect.php";

    $ks = $_POST['ks'];
    $ys = $_POST['ys'];
    $add_ys = $_POST['add_ys'];
    $add_ys = trim($add_ys,'|');

    $new_ys = $ys.'|'.$add_ys;
    $new_ys = addslashes($new_ys);
    $ks = addslashes($ks);

    $add_ys_array = explode('|',$add_ys);
    $add_ys_array = array_filter($add_ys_array);

    $sql_ks = "update `ks` set ys= '{$new_ys}' where `ks` = '{$ks}' ";

    $db->beginTransaction();

    $flag = false;
    try {

        $db->execSql($sql_ks);
        // 对于多个颜色插入到 kc 表中
        for($i=0;$i<count($add_ys_array);$i++){
            // 对于kc表进行插入操作
            $sql = 'insert into kc(`ks`,`ys`) value("'.{$ks}.'","'.{$add_ys_array[$i]}.'")';
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
    

    

