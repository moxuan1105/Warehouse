<?php

    require '../extr/db_connect.php';

    $username = addslashes($_POST['username']);
    $password = md5($_POST['password']);

    // echo $password;

    $sql_user = "select * from user where `username` = '{$username}' and `password` = '{$password}' ";
    // echo $sql_user;
    $result = $db->query($sql_user);
    $message = false;
    if($result){
        session_start();
        $_SESSION['userId'] = $result[0]['id'];
        $_SESSION['username'] = $result[0]['username'];
        $_SESSION['user_cname'] = $result[0]['user_cname'];
        $message = true;
    }    
    echo json_encode($message);