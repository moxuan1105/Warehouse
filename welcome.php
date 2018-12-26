<?php
require './extr/db_connect.php';
session_start();
if(!isset($_SESSION['username'])){
    header('Location:./login.html');
}
$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;

$sql_clothes = "select count(id) as count from clothes_name where is_del=0";
$sql_in = "select COALESCE(sum(clothes_num),0) as sum from info where action='入库' and action_time between $beginToday and $endToday ";
$sql_out = "select COALESCE(sum(clothes_num),0) as sum from info where action='出库' and action_time between $beginToday and $endToday ";
$sql_sum_in =  "select COALESCE(sum(clothes_num),0) as sum from info where action='入库'";
$sql_sum_out =  "select COALESCE(sum(clothes_num),0) as sum from info where action='出库'";


$result_clothes = $db->query($sql_clothes);
$result_in = $db->query($sql_in);
$result_out = $db->query($sql_out);
$result_sum_in = $db->query($sql_sum_in);
$result_sum_out = $db->query($sql_sum_out);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>欢迎页面</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <!-- <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" /> -->
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="./css/font.css">
        <link rel="stylesheet" href="./css/xadmin.css">
        <script src="./js/jquery-3.3.1.js"></script>
        <script src="./js/timestampToTime.js"></script>
        <script>

            function nowTime(){
                var times = parseInt(Date.now()/1000)
                
                times = timestampToTime(times);

                $('#nowTime').text(times);
            }
            $(function(){
                setInterval(function(){
                    nowTime()
                },1000);
            });
            
           
        </script>
    </head>
    <body>
    <div class="x-body layui-anim layui-anim-up">
        <blockquote class="layui-elem-quote">欢迎用户：
            <span class="x-red"><?php echo $_SESSION['user_cname']?></span>！当前时间:<span id="nowTime"></span></blockquote>
        <fieldset class="layui-elem-field">
            <legend>数据统计</legend>
            <div class="layui-field-box">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                <div carousel-item="">
                                    <ul class="layui-row layui-col-space10 layui-this">
                                        <li class="layui-col-xs3">
                                            <a href="javascript:;" class="x-admin-backlog-body" style="text-align: center;">
                                                <h3>衣服款式数目(在售)</h3>
                                                <p>
                                                    <cite><?php echo $result_clothes[0]['count']?></cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs3">
                                            <a href="javascript:;" class="x-admin-backlog-body" style="text-align: center;">
                                                <h3>今日入库数</h3>
                                                <p>
                                                    <cite><?php echo $result_in[0]['sum']?></cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs3">
                                            <a href="javascript:;" class="x-admin-backlog-body" style="text-align: center;">
                                                <h3>今日出库数</h3>
                                                <p>
                                                    <cite><?php echo $result_out[0]['sum']?></cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs3">
                                            <a href="javascript:;" class="x-admin-backlog-body" style="text-align: center;">
                                                <h3>仓库库存数</h3>
                                                <p>
                                                    <cite><?php echo ($result_sum_in[0]['sum']-$result_sum_out[0]['sum'])?></cite></p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </body>
</html>