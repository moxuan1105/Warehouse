<?php
/**
 *  商品停售
 * */

 require "../../extr/db_connect.php";

 $id = $_POST['id'];

 $sql = "update `ks` set is_del = 1 where id = $id";
 $result = $db->execSql($sql);
 echo json_encode($result);

