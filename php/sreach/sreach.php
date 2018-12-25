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
    $ks = $_POST['c_style'];

//  $a = array($limit,$page,$startTime,$endTime,$action,$ks);
//  var_dump($a);

    if(''==$ks){
        if(''==$action){
            $sql_info = "select * from info where action_time between $startTime and $endTime limit $page,$limit";
            $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime";
        }else if('0' == $action){
            $sql_info = "select * from info where action_time between $startTime and $endTime and action = '入库' limit $page,$limit";
            $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime and action = '入库'";
        }else if('1' == $action){
            $sql_info = "select * from info where action_time between $startTime and $endTime and action = '出库' limit $page,$limit";
            $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime and action = '出库'";
        }
    }else{
        if(''==$action){
            $sql_info = "select * from info where action_time between $startTime and $endTime and ks = '$ks' limit $page,$limit";
            $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime and ks = '$ks'";
        }else if('0' == $action){
            $sql_info = "select * from info where action_time between $startTime and $endTime and ks = '$ks' and action = '入库' limit $page,$limit";
            $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime and ks = '$ks' and action = '入库'";
        }else if('1' == $action){
            $sql_info = "select * from info where action_time between $startTime and $endTime and ks = '$ks' and action = '出库' limit $page,$limit";
            $sql_count = "select count(id) as count from info where action_time between $startTime and $endTime and ks = '$ks' and action = '出库'";
        }
    }

    $datas = $db->query($sql_info);
    $count = $db->query($sql_count);

    $response = array(
        'code'=>0,
        'msg'=>'',
        'data'=>$datas,
        'count'=>$count[0]['count']
        );
    echo json_encode($response);
    


