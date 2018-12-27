<?php
/*
 * @Author: moxuan
 * @Date: 2018-12-27 17:19:16
 * @Last Modified by: moxuan
 * @Last Modified time: 2018-12-27 17:51:50
 */
require "../../extr/db_connect.php";
session_start();
$action = $_GET['action'];
switch ($action) {
    case 'stock_list':
        # code...
        $result = stock_list($db);
        break;
    case 'stock_add':
        $result = stock_add($db);
        break;
    case 'stock_out':
        $result = stock_out($db);
        break;
    case 'stock_info':
        # code...
        $result = stock_info($db);
        break;
    case 'stock_info_sreach':
        # code...
        $result = stock_info_sreach($db);
        break;

    default:
        # code...
        $message = false;
        $result = json_encode($message);
        break;
}
echo $result;
/**
 * 库存列表
 *
 * @param object $db
 * @return void
 */
function stock_list(object $db)
{
    $limit = $_GET['limit'];
    $page = ($_GET['page'] - 1) * $limit;

    $sql = "select clothes_stock.* from clothes_stock join clothes_name on clothes_stock.clothes_name = clothes_name.clothes_name and clothes_name.is_del=0 order by clothes_name desc limit $page,$limit";
    // echo $sql;
    $sql_count = "select count(clothes_stock.id) as count from clothes_stock join clothes_name on clothes_stock.clothes_name = clothes_name.clothes_name and clothes_name.is_del=0";
    // $result = $db->query($sql);

    $datas = $db->query($sql);
    $count = $db->query($sql_count);
    $response = array(
        'code' => 0,
        'msg' => '',
        'data' => $datas,
        'count' => $count[0]['count'],
    );
    return json_encode($response);
}
/**
 * 商品入库
 *
 * @param object $db
 * @return string
 */
function stock_add(object $db)
{
    $id = addslashes($_POST['id']);

    $clothes_name = addslashes($_POST['clothes_name']);

    $clothes_color = addslashes($_POST['clothes_color']);

    $clothes_size = addslashes($_POST['clothes_size']);

    $clothes_num = addslashes($_POST['clothes_num']);

    $sql_clothes_stock = "update `clothes_stock` set `$clothes_size`=`$clothes_size`+$clothes_num where `id` = $id ";
    $sql_info = "insert into `info`(clothes_name,clothes_color,clothes_size,clothes_num,action_time,action,czr) value('$clothes_name','$clothes_color','$clothes_size',$clothes_num," . time() . ",'入库','" . addslashes($_SESSION['user_cname']) . "')";
    $flag = false;
    // 进行事务处理
    $db->beginTransaction();

    try {
        $db->execSql($sql_clothes_stock);
        $db->execSql($sql_info);
        $db->commit();
        $flag = true;
        return json_encode($flag);
    } catch (Exception $e) {
        $message = $e->getMessage();
        $db->rollback();
        return json_encode($message);
    }
}
/**
 * 商品出库
 *
 * @param object $db
 * @return void
 */
function stock_out(object $db)
{
    $id = addslashes($_POST['id']);

    $clothes_name = addslashes($_POST['clothes_name']);

    $clothes_color = addslashes($_POST['clothes_color']);

    $clothes_size = addslashes($_POST['clothes_size']);

    $clothes_num = addslashes($_POST['clothes_num']);

    $sql_clothes_stock = "update `clothes_stock` set `$clothes_size`=`$clothes_size`-$clothes_num where `id` = '$id'";
    $sql_info = "insert into `info`(clothes_name,clothes_color,clothes_size,clothes_num,action_time,action,czr) value('$clothes_name','$clothes_color','$clothes_size',$clothes_num," . time() . ",'出库','" . addslashes($_SESSION['user_cname']) . "')";

    $flag = false;
    // 进行事务处理
    $db->beginTransaction();
    try {
        $db->execSql($sql_clothes_stock);
        $db->execSql($sql_info);
        $db->commit();
        $flag = true;
        return json_encode($flag);
    } catch (Exception $e) {
        $a = $e->getMessage();
        $db->rollback();
        return json_encode($a);
    }
}
/**
 * 库存操作信息
 *
 * @param object $db
 * @return void
 */
function stock_info(object $db)
{
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
    return json_encode($response);
}
/**
 * 库存操作信息搜索
 *
 * @param object $db
 * @return void
 */
function stock_info_sreach(object $db)
{
    $limit = $_POST['limit'];
    $page = ($_POST['page'] - 1) * $limit;
    $startTime = strtotime($_POST['startTime']);
    $endTime = strtotime($_POST['endTime']);
    $action = $_POST['action'];
    $clothes_name = $_POST['clothes_name'];

    $sql_info = "select * from info where action_time between $startTime and $endTime ";
    $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime ";
    if ('' == $clothes_name) {
        if ('' == $action) {

        } else if ('0' == $action) {
            $sql_info .= " and action = '入库' ";
            $sql_count .= " and action = '入库' ";
        } else if ('1' == $action) {
            $sql_info .= " and action = '出库' ";
            $sql_count .= " and action = '出库' ";
        }
    } else {
        if ('' == $action) {
            $sql_info .= " and clothes_name = '$clothes_name' ";
            $sql_count .= " and clothes_name = '$clothes_name' ";
        } else if ('0' == $action) {
            $sql_info .= " and clothes_name = '$clothes_name' and action = '入库' ";
            $sql_count .= " and clothes_name = '$clothes_name' and action = '入库' ";
        } else if ('1' == $action) {
            $sql_info .= " and clothes_name = '$clothes_name' and action = '出库' ";
            $sql_count .= " and clothes_name = '$clothes_name' and action = '出库' ";
        }
    }

    $sql_info .= " order by action_time desc limit $page,$limit";
    $datas = $db->query($sql_info);
    $count = $db->query($sql_count);

    $response = array(
        'code' => 0,
        'msg' => '',
        'data' => $datas,
        'count' => $count[0]['count'],
    );
    return json_encode($response);
}
