<?php
    session_start();
    //1.接受参数
    $str=$_POST['str'];
    //2.查询参数
    if($_POST['param']==""){ //如果没有传来参数列表
        $_SESSION['sqtr']="select * from wuye_usr where name like '%".$str."%'";//通过session中转
        $_SESSION['commit']="SELECT  column_name, column_comment FROM information_schema.columns WHERE table_schema ='dyexlzc' AND table_name = 'wuye_usr'";
        header("Location: ../?search_post");//跳转到搜索路由显示结果
    }
    else  //传来参数就简单的where就可以了
    {
        echo "复杂查询";
    }