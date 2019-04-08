
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