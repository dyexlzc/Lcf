<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
     <link rel="stylesheet" href="static/layui/css/layui.css">
    <style>
        body{margin: 10px;}
        .demo-carousel{height: 200px; line-height: 200px; text-align: center;}
    </style>
    <!-- Bootstrap CSS -->
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
<script src="static/layui/layui.js" charset="utf-8"></script>
<link rel="stylesheet" href="static/demo/Playground/Playground.Avatars.css">
<link rel="stylesheet" href="static/demo/Playground/Playground.Login.css">

    <title>lcf物业管理系统</title>
  </head>
  <body>
<div class="container-fluid">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="text-center ">
				<br><br>
			    <img alt="140x140" src="https://www.baidu.com/img/baidu_jgylogo3.gif" class="img-circle" />
				<br><br>
            </div>
		</div>
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column">
			<br><br>
            <?
                require_once("module/is_login.php");
            ?>
		</div>
	</div>
	
	<div class="row clearfix">
		<div class="col-md-4 column">
		</div>
		<div class="col-md-4 column">
		
				<fieldset>
					<legend>欢迎使用LC物业系统</legend>
				    <label>用户名</label><input id="usr" type="text" class="form-control" placeholder="用户名"/> 
                    <label>密码</label><input id="pwd" type="password" class="form-control" id="pwd" placeholder="密码"/>
                    
				</fieldset>
				<hr>
				<div class="row" id='div_word'>
						<div class="col">
							
						</div>
						<div class="col-ms">
							<button id="login-btn" class="btn btn-primary" >登录</button>
						</div>
						<div class="col-ms">
						&nbsp;
						</div>
				</div>
				

		</div>
		<div class="col-md-4 column">
		</div>
	</div>
</div>
<script src="static/Source/jBox.js"></script>
<script src="static/Source/plugins/Notice/jBox.Notice.js"></script>
<script src="static/Source/plugins/Confirm/jBox.Confirm.js"></script>
<script src="static/Source/plugins/Image/jBox.Image.js"></script>
<script src="static/demo/Demo.js"></script>
<script src="static/js/win.js"></script>
<script src="static/demo/Playground/Playground.Avatars.js"></script>
<script src="static/demo/Playground/Playground.Login.js"></script>

  </body>
</html>