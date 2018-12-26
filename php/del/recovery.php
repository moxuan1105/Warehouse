<?php
/**
 *  商品恢复出售
 * */

 require "../../extr/db_connect.php";

 $id = $_POST['id'];

 $sql = "update `clothes_name` set is_del = 0 , updateTime = ".time()." where id = $id";
 $result = $db->execSql($sql);
 echo json_encode($result);

