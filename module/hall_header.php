<!doctype html>
<?
include_once("is_login.php");
?>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="static/layui/css/layui.css">
    <style>
        body {
            margin: 10px;
        }
        .demo-carousel { height: 200px;
            line-height: 200px;
            text-align: center;
        }
    </style>
    <!-- Bootstrap CSS -->
    <script src="static/layui/layui.js" charset="utf-8"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="static/Source/jBox.css">
    <link rel="stylesheet" href="static/Source/plugins/Notice/jBox.Notice.css">
    <link rel="stylesheet" href="static/Source/plugins/Confirm/jBox.Confirm.css">
    <link rel="stylesheet" href="static/Source/plugins/Image/jBox.Image.css">
    <link rel="stylesheet" href="static/Source/themes/NoticeFancy.css">
    <link rel="stylesheet" href="static/Source/themes/TooltipBorder.css">
    <link rel="stylesheet" href="static/Source/themes/TooltipBorderThick.css">
    <link rel="stylesheet" href="static/Source/themes/TooltipDark.css">
    <link rel="stylesheet" href="static/Source/themes/TooltipSmall.css">
    <link rel="stylesheet" href="static/Source/themes/TooltipSmallGray.css">
    <link rel="stylesheet" href="static/Source/themes/TooltipError.css">

    <link rel="stylesheet" href="static/demo/Playground/Playground.Avatars.css">
    <link rel="stylesheet" href="static/demo/Playground/Playground.Login.css">

    <title>lcf物业管理系统</title>
</head>
<body>
    <!-导航开始 -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">LCF物业系统大厅</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="?hall">大厅</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?center">用户中心</a>
                    </li>
                    <li class="nav-item">
                           <a class="nav-link" href="module/mix.php">合并</a>
                    </li>   

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            模块列表
                        </a><!-这里以及首页都要动态获取plugin列表-->
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?
                                $ps = getPlugin();
                                //获取插件列表
                                foreach ($ps as $p) {
                                    //显示插件目录
                                    include_once('module/plugin/'.$p.'/index.php');
                                    echo '<a class="dropdown-item" href="?hall/'.$p.'">'.$p::name().'</a>';
                                }
                                ?>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" href="#">Disabled</a>
                        </li>
                    </ul>
                    <form id="search1" class="form-inline my-2 my-lg-0" method="post" action="core/jump.php" >
                        <input name="str" class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                        <button id="str_s" class="btn btn-outline-success my-2 my-sm-0" type="button">模糊搜索用户名</button>
                    </form>
                    <script>
                        $("#str_s").click(function(e){
                            if($("input[name='str']") .val()==""){
                                alert("输入不能为空");
                                return;
                            }
                            else
                            {
                                $("#search1").submit();
                            }
                            
                          });
                    </script>
                    &nbsp;
                    <a class="btn btn-primary" href="?adv_search" role="button">高级搜索</a>
                    <li class="nav-item">
                        <a class="nav-link" href="#">当前用户 ：<? include_once('core/encrypt.php');
                            echo unlock_url($_SESSION['usr']);
                            ?></a>
                    </li>
                </div>
            </nav>