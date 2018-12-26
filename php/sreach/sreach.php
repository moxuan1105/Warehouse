<?php

    require '../../extr/db_connect.php';

/**
 * 搜索是用到的PHP文件
 */

    $limit = $_POST['limit'];
    $page = ($_POST['page']-1)*$limit;
    $startTime = strtotime($_POST['startTime']);
    $endTime = strtotime($_POST['endTime']);
    $action = $_POST['action'];
    $clothes_name = $_POST['c_style'];

//  $a = array($limit,$page,$startTime,$endTime,$action,$clothes_name);
//  var_dump($a);
    $sql_info = "select * from info where action_time between $startTime and $endTime ";
    $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime ";
    if(''==$clothes_name){
        if(''==$action){

        }else if('0' == $action){
            $sql_info .= " and action = '入库' ";
            $sql_count .= " and action = '入库' ";
        }else if('1' == $action){
            $sql_info .= " and action = '出库' ";
            $sql_count .= " and action = '出库' ";
        }
    }else{
        if(''==$action){
            $sql_info .= " and clothes_name = '$clothes_name' ";
            $sql_count .= " and clothes_name = '$clothes_name' ";
        }else if('0' == $action){
            $sql_info .= " and clothes_name = '$clothes_name' and action = '入库' ";
            $sql_count .= " and clothes_name = '$clothes_name' and action = '入库' ";
        }else if('1' == $action){
            $sql_info .= " and clothes_name = '$clothes_name' and action = '出库' ";
            $sql_count .= " and clothes_name = '$clothes_name' and action = '出库' ";
        }
    }

    $sql_info .= " order by action_time desc limit $page,$limit";
    $datas = $db->query($sql_info);
    $count = $db->query($sql_count);

    $response = array(
        'code'=>0,
        'msg'=>'',
        'data'=>$datas,
        'count'=>$count[0]['count']
        );
    echo json_encode($response);
    


