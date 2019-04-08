<? ?>
<body>
    <div class="container-fluid">
        <div class="row-fluid">
            <hr>
                    <p>
                        <a class="btn btn-primary btn-large" href="?qianfei/wuye_usr">查看物业欠费情况</a>&ensp; <button id="btn-add-usr" type="button" class="btn btn-primary">添加用户</button>
                    </p>
            
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <div class="container-fluid">
                <div class="lou"></div>
                <div class="ceng"></div>
                <div class="hu"></div>
                <div class="tmp" data=""></div>
                <script>
                    
                    $(document).ready(function () {
                        var varsaaa = {};
                        $.post("?find/wuyefei/lou", {
                            // name: "Donald Duck",
                            // city: "Duckburg"
                        }, function (data, status) {
                            // alert("数据：" + data + "\n状态：" + status);
                            $(".lou").html(data); //获取到以后给每一个按钮都添加上动作：显示ceng的div
                            for (var i = 0; i < 100; i++) {
                                if ($("#i-" + i).length > 0) {
                                    //如果该号楼存在就执行
                                    //元素存在时执行的代码
                                    //alert("?find/wuyefei/lou/"+i);
                                    $("#i-" + i).on("click", function () {
                                        var data = $(this).attr("data");
                                        $(".tmp").attr("data", data); //通过空白div转移变量，表示为具体楼号
                                        $.post("?find/wuyefei/lou/" + data, {}, function (data, status) {

                                            $(".ceng").html(data); //改变ceng的div
                                            for (var i = 0; i < 100; i++) {
                                                //添加按钮的动作是在此添加
                                                if ($("#f-" + i).length > 0) {
                                                    //如果该层存在就执行
                                                    $("#f-" + i).on("click", function () {
                                                        var data = $(this).attr("data");
                                                        console.log("?find/wuyefei/lou/" + $(".tmp").attr("data") + "/" + data);
                                                        $.post("?find/wuyefei/lou/" + $(".tmp").attr("data") + "/" + data, {
                                                            //表现为具体层数

                                                        }, function (data, status) {

                                                            $(".hu").html(data); //改变hu的div
                                                            for (var i = 0; i < 10; i++) {
                                                                if ($(".h-" + i).length > 0) {
                                                                    //如果该户存在就执行,这个是查看按钮
                                                                    //$(document).ready(function(){
                                                                        
                                                                        console.log(i+"存在");
                                                                        
                                                                        $(".hu").on('click',".h-" + i,function(){
                                                                            var arr = $(this).attr("data").split('/');
                                                                            $.post('?find/wuyefei/lou/'+arr[0]+'/'+arr[1]+'/'+arr[2],{

                                                                            },function(result){
                                                                                $("#detail").html(result);
                                                                                
                                                                            }); 
                                                                        });
                                                                        
                                                                        $(".hu").on('click',".edit-h-" + i,function(){
                                                                            var arr = $(this).attr("data").split('/');
                                                                            $.post('?find/wuyefei/edit/'+arr[0]+'/'+arr[1]+'/'+arr[2],{

                                                                            },function(result){
                                                                               $("#detail").html(result);
                                                                                
                                                                            });
                                                                        });
                                                                        $(".hu").on('click',".jiaofei-h-" + i,function(){
                                                                            var arr = $(this).attr("data").split('/');
                                                                            $.post('?find/wuyefei/jiaofei/'+arr[0]+'/'+arr[1]+'/'+arr[2],{

                                                                            },function(result){
                                                                                $("#detail").html(result);
                                                                                
                                                                            });
                                                                        });
                                                                    //});
                                                                }
                                                                else
                                                                {
                                                                    continue;
                                                                }
                                                                
                                                            }
                                                            
                                                            $(".hu").show();
                                                            $(".ceng").hide();
                                                            $("#back-l2").on("click", function () {
                                                                //添加返回上一层的操作
                                                                $(".hu").hide(); //获取楼层数据
                                                                $(".ceng").show();
                                                            });
                                                        });
                                                    });
                                                }
                                            }
                                            $(".ceng").show();
                                            $(".lou").hide(); //获取楼层数据
                                            $("#back-l").on("click", function () {
                                                //添加返回上一层的操作
                                                $(".lou").show(); //获取楼层数据
                                                $(".ceng").hide();
                                            });
                                        });

                                    });
                                }
                            }


                        });
                        //$("#btn-add-usr").on("click", function () { 
                            open_win = new jBox('Modal', {//打开“添加用户”的界面,不用open,这样更适合
                                attach: "#btn-add-usr",
                                width: 950,
                                height: 550,
                                closeButton: 'title',
                                animation: false,
                                title: '正在查询...',
                                ajax: {
                                    url: '?add',
                                    data: {},
                                    reload: 'strict',
                                    setContent: false,
                                    beforeSend: function() {
                                        this.setContent('');
                                        this.setTitle('<div class="ajax-sending">正在查询，请稍等</div>');
                                    },
                                    complete: function(response) {
                                        this.setTitle('<div class="ajax-complete">查询完毕</div>');
                                    },
                                    success: function(response) {
                                        this.setContent(response);
                                    },
                                    error: function() {
                                        this.setContent('<div class="ajax-error">查询出错，请联管理员</div>');
                                    }
                                }
                            });
                        //});
                        
                    });
                    
                </script>
                <div class="row placeholders"></div>
            </div>
            </div> 
            <div id="detail" class="col">
                
            </div>
        </div>
    </div>
</body>