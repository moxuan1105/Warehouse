<?php
/*
 * @Author: moxuan
 * @Date: 2018-12-27 08:59:24
 * @Last Modified by: moxuan
 * @Last Modified time: 2018-12-27 17:49:24
 */

require '../../extr/db_connect.php';

$action = $_GET['action'];

switch ($action) {
    case 'style_list':
        # code...
        $result = style_list($db);
        // echo $result;
        break;
    case 'style_add':
        $result = style_add($db);
        // echo $result;
        break;
    case 'color_edit':
        $result = style_color_edit($db);
        // echo $result;
        break;
    case 'stop_sell':
        $result = style_stop_sell($db);
        // echo $result;
        break;
    case 'style_recovery':
        $result = style_recovery($db);
        // echo $action;
        // echo $result;
        break;
    case 'style_stop_list':
        $result = style_stop_list($db);
        break;
    default:
        $message = false;
        $result = json_encode($message);
        break;
}
echo $result;
/**
 * 获取款式列表
 *
 * @param object $db 数据库操作对象
 * @return json 返回json字符串
 */
function style_list($db)
{
    $limit = $_POST['limit'];
    $page = ($_POST['page'] - 1) * $limit;

    $sql = "select * from clothes_name where is_del=0 order by id desc limit $page,$limit";
    $sql_count = "select count(id) as count from clothes_name where is_del=0";

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
 * 添加新的款式
 *
 */
function style_add($db)
{
    $flag = false;
    $time = time();
    $clothes_name = $_POST['clothes_name'];
    $clothes_color = $_POST['clothes_color'];
    $clothes_color = trim($clothes_color, '|');
    $arrayDataValue = array(
        'clothes_name' => addslashes($clothes_name),
        'clothes_color' => addslashes($clothes_color),
        'createTime' => $time,
        'updateTime' => $time,
    );
    $clothes_colorsz = explode('|', $clothes_color);
    $clothes_colorsz = array_filter($clothes_colorsz);
    // 事务开始
    $db->beginTransaction();
    try {
        // 插入clothes_name表
        $table = "clothes_name";
        $db->insert($table, $arrayDataValue);
        for ($i = 0; $i < count($clothes_colorsz); $i++) {
            // 对于clothes_stock表进行插入操作
            $sql = 'insert into clothes_stock(`clothes_name`,`clothes_color`) value("' . $clothes_name . '","' . $clothes_colorsz[$i] . '")';
            $db->execSql($sql);
        }
        // 提交
        $db->commit();
        $flag = true;
        return json_encode($flag);
    } catch (Exception $e) {
        $db->rollback();
        $message = $e->getMessage();
        return json_encode($message);
    }
}
/**
 * 款式颜色修改
 */
function style_color_edit($db)
{
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
        return json_encode($flag);
    } catch (Exception $e) {
        $db->rollback();
        $message = $e->getMessage();
        return json_encode($message);
    }
}

/**
 * 款式停止出售
 */
function style_stop_sell($db)
{
    $id = $_POST['id'];
    $sql = "update `clothes_name` set `is_del` = 1 ,`updateTime` = " . time() . " where `id` = $id ";
    $result = $db->execSql($sql);
    return json_encode($result);
}
/**
 * 款式恢复出售
 *
 * @param object $db
 * @return json
 */
function style_recovery($db)
{
    $id = $_POST['id'];

    $sql = "update `clothes_name` set is_del = 0 , updateTime = " . time() . " where id = $id";
    // echo $sql;
    $result = $db->execSql($sql);
    return json_encode($result);
}
/**
 * 停止出售款式
 *
 * @param object $db
 * @return void
 */
function style_stop_list($db)
{
    $limit = $_GET['limit'];
    $page = ($_GET['page'] - 1) * $limit;

    $sql = "select * from clothes_name where is_del=1 limit $page,$limit";
    $sql_count = "select count(id) as count from clothes_name where is_del=1";

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
