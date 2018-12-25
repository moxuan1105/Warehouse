<?php
    header("Content-type:text/html;charset:utf-8");

    require "pdo.php";

    $db = mypdo::getInstance('127.0.0.1', 'root', 'root', 'mx', 'utf8');