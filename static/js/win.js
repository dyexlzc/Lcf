$(document).ready(function() {
    $("#login-btn").click(function() { //绑定登录的按钮
        var _usr = ($("#usr").val());
        var _pwd = ($("#pwd").val());
        $.post("module/login.php", {
            usr: _usr,
            pwd: _pwd
        }, function(data, status) { //先用post获取状态，然后判断
            //alert(data);

            if (data == "登录成功!") {
                var win1 = new jBox('Notice', {
                    attributes: {
                        x: 'right',
                        y: 'bottom'
                    },
                    stack: false,
                    animation: {
                        open: 'tada',
                        close: 'zoomIn'
                    },
                    color: "green",
                    title: "登录成功",
                    content: "欢迎使用lc物业系统,2秒后自动进入",
                    
                });
                win1.open();
                window.setTimeout("window.location='?hall'",2000); 
            } else if (data == "用户名或密码不能为空") {
                //alert("aa");
                var win2 = new jBox('Notice', {
                    attributes: {
                        x: 'right',
                        y: 'bottom'
                    },
                    stack: false,
                    animation: {
                        open: 'tada',
                        close: 'zoomIn'
                    },
                    autoClose: Math.random() * 8000 + 2000,
                    color: 'yellow',
                    title: '不好！发生了错误！',
                    content: '用户名或密码没有填写哦',
                    delayOnHover: true,
                    showCountdown: true,
                    closeButton: true
                });
                win2.open();
            } else if (data == "登录失败，请检查用户名或者密码") {
                var win3 = new jBox('Notice', {
                    attributes: {
                        x: 'right',
                        y: 'bottom'
                    },
                    stack: false,
                    animation: {
                        open: 'tada',
                        close: 'zoomIn'
                    },
                    autoClose: Math.random() * 8000 + 2000,
                    color: 'red',
                    title: '不好！发生了错误！',
                    content: '登录错误…看看密码是否不对',
                    delayOnHover: true,
                    showCountdown: true,
                    closeButton: true
                });
                win3.open();

            }
        });

    });

});