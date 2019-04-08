<?
ini_set('display_errors',1);
//错误信息
ini_set('display_startup_errors',1);
//php启动错误信息
error_reporting(-1);
//打印出所有的 错误信息

require("route/index.php");
//基本每个页都要引入这个文件，将所有连接都转发到core中处理
//引入以后，在Lcf_core中解析参数，并在route中解析参数并且跳转,执行module程序
/*
         *
             注意:由于包含文件时文件关系复杂，请务必使用include_once
         */

?>