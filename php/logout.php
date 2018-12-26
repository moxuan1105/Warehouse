<?php
session_start();

unset($_SESSION["username"]); //清空指定的session
unset($_SESSION["userId"]); //清空指定的session
unset($_SESSION['user_cname']); //清空指定的session
//使用内置session_destroy()函数调用撤销会话
session_destroy();
//location首部使浏览器重定向到另一个页面
header('Location:../login.html');
