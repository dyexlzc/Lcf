<?php
    /* 
        判断是否登录，否则不允许访问
    */
    session_start();
    header("Content-type: text/html; charset=utf-8"); 
    include_once('core/lcf_mysql.php');
    include_once('core/lcf_core.php');
    include_once('core/encrypt.php'); //引入加密模块
   
    if(is_null($_SESSION['usr'])){
        switch(route_module()){                //根据路由执行功能
                case $route['home']: 
                {?>
                    
                    <?break;
                }
                case -1: 
                {?>
                    
                    <?break;
                }
                default: //不在首页才需要跳转到首页进行登录
                {   
                   ?>
                    <script> 
                        alert("清先登录");
                        window.setTimeout("window.location='?home'"); 
                    </script>
                    <?break;
                }
        }
    }
    else{ 
        switch(route_module()){                //根据路由执行功能
                case $route['home']: 
                {?>
                    <script>  //如果是在首页检测，则跳转到大厅，否则不做动作
                        window.setTimeout("window.location='?hall'"); 
                    </script>
                    <?break;
                }
                case -1: 
                {?>
                    <script>  //如果是在首页检测，则跳转到大厅，否则不做动作
                        window.setTimeout("window.location='?hall'"); 
                    </script>
                    <?break;
                }
                default:
                {
                    break;
                    
                }
            }
        }

?>