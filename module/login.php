<?
    /*
        登录后台统一访问mysql接口进行处理
    */
    //ini_set('display_errors',1);            //错误信息
    //ini_set('display_startup_errors',1);    //php启动错误信息
    //error_reporting(-1);
    header("Content-type: text/html; charset=utf-8"); 
    include_once('../core/lcf_mysql.php');
    //include_once('../core/lcf_core.php');
    include_once('../core/encrypt.php'); //引入加密模块
    //echo "用户名".$_GET['usr']." 密码:".$_GET['pwd'];
    $Back=json_decode(MakeQuery("select pwd from user where name='{$_POST['usr']}';"));
    session_start(); // 初始化session
    if(is_null($_POST['pwd'])||is_null($_POST['usr'])||is_null($Back[0])){ //用户名或者密码为空或者数据库中不存在该用户
        echo "用户名或密码不能为空";
    }
    elseif($_POST['pwd']==$Back[0]->{'pwd'}){
        echo "登录成功!";
        $_SESSION['usr']=lock_url($_POST['usr']);
    }
    else {
        echo "登录失败，请检查用户名或者密码";
    }
?>