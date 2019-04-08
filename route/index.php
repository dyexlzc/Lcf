<?
/*
        route/index.php：
        此文件负责转发所有的php请求，使其通过类具体化，方便制作MVC框架
        url结构为 module/function/parmas
    */
session_start();
include_once("./core/lcf_mysql.php");
include_once("./core/Lcf_core.php");
$route = array(
    'home' => 0,
    'center' => 1,
    'hall' => 2,
    'find' => 3,
    'add' => 4,
    'qianfei' => 5,
    'search_post' => 6, //传入表名，字段名，返回查询结构
    'adv_search' => 7,  //高级搜索页面
    'add2' => 8,
);
//编写路由规则
$route_plugin = array(//插件的路由规则
    'wuyefei' => 0,    //注册物业费的插件
    'carfei' => 1,
);

switch (route_module()) {
    //根据路由执行功能
    case $route['home']: {
        include_once("module/home_index.php");
        break;
    }
    case $route['center']: {
            include_once("module/hall_header.php");
            echo "<a>打开中心</a>";
            break;
        }
    case $route['hall']: {
            include_once("module/hall_header.php");
            //为了统一，暂时不在hall中合并页脚
            //查找具体用户并且返回为json字符串，具体路径为 find/wuyefei/lou/n栋所有层/n层所有户/具体
            switch (route_plugin()) {
                case $route_plugin['wuyefei']: {
                    include_once('module/plugin/wuyefei/body.php');

                    //统一使用页头,在plugin中只写body页面
                    break;
                }
                case $route_plugin['carfei']: {
                        include_once('module/plugin/carfei/body.php');

                        //统一使用页头,在plugin中只写body页面
                        break;
                    }
                default:
                    {
                        include_once("module/hall_body.php");
                        break;
                    }
            }
            include_once("module/home_footer.php");
            break;
        }
    case $route['find']: {
            if (!strcmp(param(1),"wuyefei")) {
                if (!strcmp(param(2),"lou")) {
                    if (param(3) == "") {
                        //如果没有指定楼层，则输出所有楼
                        $lou = json_decode(MakeQuery("select distinct lou from wuye_usr  ORDER BY lou+0 "));

                        {
                            //输出按钮
                            $n = count($lou);
                            ?>
                            <div class="card">
                                <h3 class="card-header">楼号选择</h3>
                                <div class="card-body">
                                    <p>
                                        在此处选择您要查看的楼号
                                    </p>
                                    <?
                                    for ($i = 0;$i < $n;$i++) {
                                        ?>
                                        <button id="i-<?echo $lou[$i]->lou;
                                            ?>" type="button" class="btn btn-outline-primary" data="<?echo $lou[$i]->lou;
                                            ?>"> <? echo $lou[$i]->lou;
                                            ?> 号楼</button>
                                        <?
                                    }
                                    ?>
                                </div>
                            </div>

                            <?
                        }
                    } else {
                        if (param(3) != "" && param(4) == "" && param(5) == "") {
                            //如果有楼层，就输出该楼的层:按从小到大排序
                            $ceng = json_decode(MakeQuery("select distinct ceng from wuye_usr where lou=".param(3)." ORDER BY ceng+0"));

                            {
                                //输出按钮
                                $n = count($ceng);
                                ?>
                                <div class="card">
                                    <button id="back-l" type="button" class="btn btn-outline-primary"><=楼号选择</button>
                                        <h3 class="card-header"><?echo param(3);
                                            //在路由页面正常，但在主页面获取param(3)时发生错误 ?>楼层选择</h3>
                                        <div class="card-body">
                                            <p>
                                                在此处选择您要查看的楼层
                                            </p>
                                            <?
                                            for ($i = 0;$i < $n;$i++) {
                                                ?>
                                                <button id="f-<?echo $ceng[$i]->ceng;
                                                    ?>" type="button" class="btn btn-outline-secondary" data="<?echo $ceng[$i]->ceng;
                                                    ?>"><? echo $ceng[$i]->ceng;
                                                    ?></button>
                                                <?
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <?
                                }
                            } elseif (param(3) != "" && param(4) != "" && param(5) == "") {
                                //如果有具体的层数，则表明需要返回具体的用户数
                                $hu = json_decode(MakeQuery("select hu,name,aera from wuye_usr where lou=".param(3)." and ceng=".param(4)." ORDER BY hu+0"));
                                //print_r($hu);
                                {
                                    //输出按钮
                                    $n = count($hu);
                                    ?>

                                    <div class="card" style="width: auto;">
                                        <button id="back-l2" type="button" class="btn btn-outline-primary"><=层数选择</button>
                                            <h3 class="card-header">住户选择</h3>
                                            <div class="card-body">
                                                <p>
                                                    在此处选择您要查看的
                                                </p>
                                                <div class="row">
                                                    <?
                                                    for ($i = 0;$i < $n;$i++) {
                                                        ?>
                                                        <div class="card" style="width: 17rem;">

                                                            <div class="card-body">
                                                                <h5 class="card-title"><? echo $hu[$i]->name;
                                                                    ?></h5>
                                                                <p class="card-text">
                                                                    门牌号:<?echo param(3)."/".param(4)."/".$hu[$i]->hu;
                                                                    ?>
                                                                </p>
                                                                <p class="card-text">
                                                                    面积:<?echo $hu[$i]->aera;
                                                                    ?>
                                                                </p>
                                                            </div>

                                                            <div class="card-body">
                                                                <button id="h-" type="button" class="btn btn-info h-<?echo $hu[$i]->hu;
                                                                    ?>" data="<?echo param(3).'/'.param(4).'/'.$hu[$i]->hu;
                                                                    ?>">详情</button>
                                                                <button id="edit-h-<?echo $hu[$i]->hu;
                                                                    ?>" type="button" class="btn btn-info edit-h-<?echo $hu[$i]->hu;
                                                                    ?>" data="<?echo param(3).'/'.param(4).'/'.$hu[$i]->hu;
                                                                    ?>">编辑</button>
                                                                <button id="jiaofei-h-<?echo $hu[$i]->hu;
                                                                    ?>" type="button" class="btn btn-info jiaofei-h-<?echo $hu[$i]->hu;
                                                                    ?>" data="<?echo param(3).'/'.param(4).'/'.$hu[$i]->hu;
                                                                    ?>">缴费</button>
                                                            </div>
                                                        </div>

                                                        <?
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>

                                        <?
                                    }

                                } elseif (param(3) != "" && param(4) != "" && param(5) != "") {
                                    //显示具体住户的信息
                                    global $_command;
                                    //print_r("select * from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5));
                                    $hu = json_decode(MakeQuery("select * from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5)));
                                    //var_dump($hu);
                                    ?>

                                    <div class="container">
                                        <div class="row clearfix">
                                            <div class="col-md-6 column">
                                                <h3>户名:<?echo $hu[0]->name;
                                                    ?></h3>
                                            </div>
                                            <div class="col-md-6 column">
                                                <h3>门牌号:<?echo param(3)."-".param(4).'-'.param(5);
                                                    ?></h3>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row clearfix">
                                            <div class="col-md-4 column">
                                                <h5>户型面积：<? echo $hu[0]->aera;
                                                    ?></h5>
                                            </div>
                                            <div class="col-md-4 column">
                                                <h5>联系电话：<? echo $hu[0]->number;
                                                    ?></h5>
                                            </div>
                                            <div class="col-md-4 column">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row clearfix">
                                            <div class="card" style="width: 100%;">
                                                <div class="card-header">
                                                    备注信息
                                                </div>
                                                <div class="card-body">
                                                    <p>
                                                        <?
                                                        if (strlen($hu[0]->other) == 0)
                                                            echo "暂无信息";
                                                        else
                                                            echo $hu[0]->other;
                                                        ?>
                                                    </p>

                                                </div>
                                            </div>

                                        </div>
                                        <hr>
                                        <div class="row clearfix">
                                            <div class="card" style="width: 100%;">
                                                <div class="card-header">
                                                    缴费记录
                                                </div>
                                                <div class="card-body">
                                                    <p>
                                                        <? $p = json_decode(MakeQuery("select * from wuye_usr_time where id=".$hu[0]->id." and name='".$hu[0]->name."';"));
                                                        ?>
                                                    </p>
                                                    <div class="layui-form">
                                                        <table class="layui-table">
                                                            <colgroup>
                                                                <col width="150">
                                                                <col width="150">
                                                                <col width="200">
                                                                <col>
                                                            </colgroup>
                                                            <thead>
                                                                <tr>
                                                                    <th>人物</th>
                                                                    <th>缴费时间</th>
                                                                    <th>物业费开始</th>
                                                                    <th>物业费结束</th>
                                                                    <th>金额备注</th>
                                                                </tr>
                                                            </thead>
                                                            <?
                                                            foreach ($p as $u) {
                                                                //循环显示所有缴费记录
                                                                ?>
                                                                <tbody>
                                                                    <tr>
                                                                        <td><?echo $u->name;
                                                                            ?></td>
                                                                        <td><?echo $u->jiaofei_time;
                                                                            ?></td>
                                                                        <td><?echo $u->start_time;
                                                                            ?></td>
                                                                        <td><?echo $u->over_time;
                                                                            ?></td>
                                                                        <td><?echo $u->money;
                                                                            ?></td>
                                                                    </tr>
                                                                </tbody>

                                                                <?
                                                            }
                                                            ?>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    <?
                                }
                            }
                        } else if (!strcmp(param(2),"edit")) {
                            if (param(3) != "" && param(4) != "" && param(5) != "") {
                                //显示具体住户的信息并且编辑
                                global $_command;
                                //print_r("select * from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5));
                                $hu = json_decode(MakeQuery("select * from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5)));
                                //var_dump($hu);
                                ?>

                                <div class="container">
                                    <div class="row clearfix">
                                        <div class="col-md-6 column">
                                            <h3>户名:</h3>
                                            <input type="text" class="form-control" id="name" placeholder="First name" value="<?echo $hu[0]->name;
                                            ?>" required>
                                        </div>
                                        <div class="col-md-6 column">
                                            <h3>门牌号:</h3>
                                            <input type="text" class="form-control" id="num" placeholder="First name" value="<?echo param(3)."-".param(4).'-'.param(5);
                                            ?>" required>

                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row clearfix">
                                        <div class="col-md-4 column">
                                            <h3>户型面积:</h3>
                                            <input type="text" class="form-control" id="aera" placeholder="First name" value="<?echo $hu[0]->aera;
                                            ?>" required>
                                        </div>
                                        <div class="col-md-4 column">
                                            <h3>联系电话:</h3>
                                            <input type="text" class="form-control" id="number" placeholder="First name" style="width:200%" value="<?echo $hu[0]->number;
                                            ?>" required>
                                        </div>
                                        <div class="col-md-4 column">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row clearfix">
                                        <div class="card" style="width: 100%;">
                                            <div class="card-header">
                                                备注信息
                                            </div>
                                            <div class="card-body">
                                                <p>
                                                    <textarea class="form-control" id="other" rows="3"><?echo $hu[0]->other;
                                                        ?></textarea>
                                                </p>
                                                <button id="btn-updata" type="button" class="btn btn-primary">更新信息</button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <script>
                                    $("#btn-updata").on("click", function () {

                                        $.post("core/lcf_mysql.php", {
                                            str: "update wuye_usr set other='"+$(" #other ").val()+"',number='"+$(" #number ").val()+"',aera='"+$(" #aera ").val()+"',name='"+$(" #name ").val()+"' where lou=<?echo param(3);
                                            ?> and ceng=<?echo param(4);
                                            ?> and hu=<?echo param(5);
                                            ?>"

                                        }, function(result) {
                                            new jBox('Notice', {
                                                theme: 'NoticeFancy',
                                                attributes: {
                                                    x: 'left',
                                                    y: 'bottom'
                                                },
                                                color: 'green',
                                                content: "修改成功!",
                                                title: '！',
                                                maxWidth: 600,
                                                audio: 'static/Source/audio/bling2',
                                                volume: 80,
                                                autoClose: Math.random() * 8000 + 2000,
                                                animation: {
                                                    open: 'slide:bottom', close: 'slide:left'
                                                },
                                                delayOnHover: true,
                                                showCountdown: true,
                                                closeButton: true
                                            }).open();
                                        });

                                    });
                                </script>


                                <?
                            }
                        } else if (!strcmp(param(2),"jiaofei")) {
                            //缴费按钮逻辑
                            echo "缴费界面";

                            $a = json_decode(MakeQuery("select id,name from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5)));
                            //获取到id，name
                            $uid = $a[0]->id;
                            $uname = $a[0]->name;

                            echo "当前日期&nbsp;" . date("Y-m-d") . "<br>";
                            echo "当前用户&nbsp;" . $uname . "<br>";
                            ?>
                            <div class="row clearfix">
                                <div class="col-md-6 column">
                                    <div class="card" style="width: 100%;">
                                        <div class="card-header">
                                            开始日期
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <input type="text" class="layui-input" id="<?echo param(3)."-".param(4)."-".param(5)."-";
                                                ?>kaishi" placeholder="yyyy-MM-dd">
                                                <div class="layui-inline" id="test-n1"></div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 column">
                                    <div class="card" style="width: 100%;">
                                        <div class="card-header">
                                            结束日期
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <input type="text" class="layui-input" id="<?echo param(3)."-".param(4)."-".param(5)."-";
                                                ?>jieshu" placeholder="yyyy-MM-dd">
                                                <div class="layui-inline" id="test-n2"></div>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    layui.use('laydate', function() {
                                        var laydate = layui.laydate;

                                        //常规用法
                                        laydate.render({
                                            elem: '#test-n1', position: 'static', done: function(value, date, endDate) {

                                                $("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                    ?>kaishi").attr("value", value);
                                                console.log($("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                    ?>kaishi").val()); //得到日期生成的值，如：2017-08-18
                                                //console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                                                //console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                                            }
                                        });
                                        laydate.render({
                                            elem: '#test-n2', position: 'static', done: function(value, date, endDate) {

                                                $("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                    ?>jieshu").attr("value", value);
                                                console.log($("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                    ?>jieshu").val()); //得到日期生成的值，如：2017-08-18
                                                //console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                                                //console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                                            }
                                        });
                                    });
                                </script>
                            </div>
                            <hr>
                            <div class="row clearfix">
                                <div class="col-md-12 column">
                                    <div class="card" style="width: 100%;">
                                        <div class="card-header">
                                            缴费金额及备注
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <input type="text" class="layui-input" id="money" placeholder="缴费金额">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>


                            <button id="btn-jiaofei" type="button" class="btn btn-primary">缴费</button>

                            <script>
                                $("#btn-jiaofei").on("click", function () {
                                    var usr_name = "<? echo $uname;
                                    //先通过PHP查找lou ceng hu对应的id name ?>";
                                    var usr_id = "<? echo $uid;
                                    ?>";
                                    console.log("INSERT INTO wuye_usr_time (id,name,jiaofei_time,start_time,over_time,money) VALUES ('"+usr_id+"','"+usr_name+"',CURDATE(),'"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                        ?>kaishi").val()+"','"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                        ?>jieshu").val()+"','"+$("#money").val()+"');");
                                    $.post("core/lcf_mysql.php", {
                                        str: "INSERT INTO wuye_usr_time (id,name,jiaofei_time,start_time,over_time,money) VALUES ('"+usr_id+"','"+usr_name+"',CURDATE(),'"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                            ?>kaishi").val()+"','"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                            ?>jieshu").val()+"','"+$("#money").val()+"');"

                                    }, function(result) {
                                        new jBox('Notice', {
                                            theme: 'NoticeFancy',
                                            attributes: {
                                                x: 'left',
                                                y: 'bottom'
                                            },
                                            color: 'green',
                                            content: "缴费成功!",
                                            title: '！',
                                            maxWidth: 600,
                                            audio: 'static/Source/audio/bling2',
                                            volume: 80,
                                            autoClose: Math.random() * 8000 + 2000,
                                            animation: {
                                                open: 'slide:bottom', close: 'slide:left'
                                            },
                                            delayOnHover: true,
                                            showCountdown: true,
                                            closeButton: true
                                        }).open();

                                    });

                                });
                            </script>
                            <?
                        }
                    }
                    // break;
                    else if (!strcmp(param(1),"carfei")) {
                        if (!strcmp(param(2),"lou")) {
                            if (param(3) == "") {
                                //如果没有指定楼层，则输出所有楼
                                $lou = json_decode(MakeQuery("select distinct lou from wuye_car  ORDER BY lou+0 "));

                                {
                                    //输出按钮
                                    $n = count($lou);
                                    ?>
                                    <div class="card">
                                        <h3 class="card-header">楼号选择</h3>
                                        <div class="card-body">
                                            <p>
                                                在此处选择您要查看的楼号
                                            </p>
                                            <?
                                            for ($i = 0;$i < $n;$i++) {
                                                ?>
                                                <button id="i-<?echo $lou[$i]->lou;
                                                    ?>" type="button" class="btn btn-outline-primary" data="<?echo $lou[$i]->lou;
                                                    ?>"> <? echo $lou[$i]->lou;
                                                    ?> 号楼</button>
                                                <?
                                            }
                                            ?>
                                        </div>
                                    </div>

                                    <?
                                }
                            } else {
                                if (param(3) != "" && param(4) == "" && param(5) == "") {
                                    //如果有楼层，就输出该楼的层:按从小到大排序
                                    $ceng = json_decode(MakeQuery("select distinct ceng from wuye_car where lou=".param(3)." ORDER BY ceng+0"));

                                    {
                                        //输出按钮
                                        $n = count($ceng);
                                        ?>
                                        <div class="card">
                                            <button id="back-l" type="button" class="btn btn-outline-primary"><=楼号选择</button>
                                                <h3 class="card-header"><?echo param(3);
                                                    //在路由页面正常，但在主页面获取param(3)时发生错误 ?>楼层选择</h3>
                                                <div class="card-body">
                                                    <p>
                                                        在此处选择您要查看的楼层
                                                    </p>
                                                    <?
                                                    for ($i = 0;$i < $n;$i++) {
                                                        ?>
                                                        <button id="f-<?echo $ceng[$i]->ceng;
                                                            ?>" type="button" class="btn btn-outline-secondary" data="<?echo $ceng[$i]->ceng;
                                                            ?>"><? echo $ceng[$i]->ceng;
                                                            ?></button>
                                                        <?
                                                    }
                                                    ?>
                                                </div>
                                            </div>

                                            <?
                                        }
                                    } elseif (param(3) != "" && param(4) != "" && param(5) == "") {
                                        //如果有具体的层数，则表明需要返回具体的用户数
                                        $hu = json_decode(MakeQuery("select hu,name,carnumber,pos from wuye_car where lou=".param(3)." and ceng=".param(4)." ORDER BY hu+0"));
                                        //print_r($hu);
                                        {
                                            //输出按钮
                                            $n = count($hu);
                                            ?>

                                            <div class="card" style="width: auto;">
                                                <button id="back-l2" type="button" class="btn btn-outline-primary"><=层数选择</button>
                                                    <h3 class="card-header">住户选择</h3>
                                                    <div class="card-body">
                                                        <p>
                                                            在此处选择您要查看的
                                                        </p>
                                                        <div class="row">
                                                            <?
                                                            for ($i = 0;$i < $n;$i++) {
                                                                ?>
                                                                <div class="card" style="width: 17rem;">

                                                                    <div class="card-body">
                                                                        <h5 class="card-title"><? echo $hu[$i]->name;
                                                                            ?></h5>
                                                                        <p class="card-text">
                                                                            车牌号:<?echo $hu[$i]->carnumber;
                                                                            ?>
                                                                        </p>
                                                                        <p class="card-text">
                                                                            车位号:<?echo $hu[$i]->pos;
                                                                            ?>
                                                                        </p>
                                                                    </div>

                                                                    <div class="card-body">
                                                                        <button id="h-" type="button" class="btn btn-info h-<?echo $hu[$i]->hu;
                                                                            ?>" data="<?echo param(3).'/'.param(4).'/'.$hu[$i]->hu;
                                                                            ?>">详情</button>
                                                                        <button id="edit-h-<?echo $hu[$i]->hu;
                                                                            ?>" type="button" class="btn btn-info edit-h-<?echo $hu[$i]->hu;
                                                                            ?>" data="<?echo param(3).'/'.param(4).'/'.$hu[$i]->hu;
                                                                            ?>">编辑</button>
                                                                        <button id="jiaofei-h-<?echo $hu[$i]->hu;
                                                                            ?>" type="button" class="btn btn-info jiaofei-h-<?echo $hu[$i]->hu;
                                                                            ?>" data="<?echo param(3).'/'.param(4).'/'.$hu[$i]->hu;
                                                                            ?>">缴费</button>
                                                                    </div>
                                                                </div>

                                                                <?
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?
                                            }

                                        } elseif (param(3) != "" && param(4) != "" && param(5) != "") {
                                            //显示具体车位的信息
                                            global $_command;
                                            //print_r("select * from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5));
                                            $hu = json_decode(MakeQuery("select * from wuye_car where lou=".param(3)." and ceng=".param(4)." and hu=".param(5)));
                                            //var_dump($hu);
                                            ?>

                                            <div class="container">
                                                <div class="row clearfix">
                                                    <div class="col-md-6 column">
                                                        <h3>户名:<?echo $hu[0]->name;
                                                            ?></h3>
                                                    </div>
                                                    <div class="col-md-6 column">
                                                        <h3>门牌号:<?echo param(3)."-".param(4).'-'.param(5);
                                                            ?></h3>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row clearfix">
                                                    <div class="col-md-4 column">
                                                        <h5>户型面积：<? echo $hu[0]->aera;
                                                            ?></h5>
                                                    </div>
                                                    <div class="col-md-4 column">
                                                        <h5>联系电话：<? echo $hu[0]->number;
                                                            ?></h5>
                                                    </div>
                                                    <div class="col-md-4 column">
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row clearfix">
                                                    <div class="card" style="width: 100%;">
                                                        <div class="card-header">
                                                            备注信息
                                                        </div>
                                                        <div class="card-body">
                                                            <p>
                                                                <?
                                                                if (strlen($hu[0]->other) == 0)
                                                                    echo "暂无信息";
                                                                else
                                                                    echo $hu[0]->other;
                                                                ?>
                                                            </p>

                                                        </div>
                                                    </div>

                                                </div>
                                                <hr>
                                                <div class="row clearfix">
                                                    <div class="card" style="width: 100%;">
                                                        <div class="card-header">
                                                            车位缴费记录
                                                        </div>
                                                        <div class="card-body">
                                                            <p>
                                                                <? $p = json_decode(MakeQuery("select * from wuye_car_time where id=".$hu[0]->id." and name='".$hu[0]->name."';"));
                                                                ?>
                                                            </p>
                                                            <div class="layui-form">
                                                                <table class="layui-table">
                                                                    <colgroup>
                                                                        <col width="150">
                                                                        <col width="150">
                                                                        <col width="200">
                                                                        <col>
                                                                    </colgroup>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>人物</th>
                                                                            <th>缴费时间</th>
                                                                            <th>车位费开始</th>
                                                                            <th>车位费结束</th>
                                                                            <th>金额备注</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <?
                                                                    foreach ($p as $u) {
                                                                        //循环显示所有缴费记录
                                                                        ?>
                                                                        <tbody>
                                                                            <tr>
                                                                                <td><?echo $u->name;
                                                                                    ?></td>
                                                                                <td><?echo $u->jiaofei_time;
                                                                                    ?></td>
                                                                                <td><?echo $u->start_time;
                                                                                    ?></td>
                                                                                <td><?echo $u->over_time;
                                                                                    ?></td>
                                                                                <td><?echo $u->money;
                                                                                    ?></td>
                                                                            </tr>
                                                                        </tbody>

                                                                        <?
                                                                    }
                                                                    ?>

                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>



                                            <?
                                        }
                                    }
                                } else if (!strcmp(param(2),"edit")) {
                                    if (param(3) != "" && param(4) != "" && param(5) != "") {
                                        //显示具体住户的信息并且编辑
                                        global $_command;
                                        //print_r("select * from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5));
                                        $hu = json_decode(MakeQuery("select * from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5)));
                                        //var_dump($hu);
                                        ?>

                                        <div class="container">
                                            <div class="row clearfix">
                                                <div class="col-md-6 column">
                                                    <h3>户名:</h3>
                                                    <input type="text" class="form-control" id="name" placeholder="First name" value="<?echo $hu[0]->name;
                                                    ?>" required>
                                                </div>
                                                <div class="col-md-6 column">
                                                    <h3>门牌号:</h3>
                                                    <input type="text" class="form-control" id="num" placeholder="First name" value="<?echo param(3)."-".param(4).'-'.param(5);
                                                    ?>" required>

                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="col-md-4 column">
                                                    <h3>户型面积:</h3>
                                                    <input type="text" class="form-control" id="aera" placeholder="First name" value="<?echo $hu[0]->aera;
                                                    ?>" required>
                                                </div>
                                                <div class="col-md-4 column">
                                                    <h3>联系电话:</h3>
                                                    <input type="text" class="form-control" id="number" placeholder="First name" style="width:200%" value="<?echo $hu[0]->number;
                                                    ?>" required>
                                                </div>
                                                <div class="col-md-4 column">
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row clearfix">
                                                <div class="card" style="width: 100%;">
                                                    <div class="card-header">
                                                        备注信息
                                                    </div>
                                                    <div class="card-body">
                                                        <p>
                                                            <textarea class="form-control" id="other" rows="3"><?echo $hu[0]->other;
                                                                ?></textarea>
                                                        </p>
                                                        <button id="btn-updata" type="button" class="btn btn-primary">更新信息</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <script>
                                            $("#btn-updata").on("click", function () {

                                                $.post("core/lcf_mysql.php", {
                                                    str: "update wuye_usr set other='"+$(" #other ").val()+"',number='"+$(" #number ").val()+"',aera='"+$(" #aera ").val()+"',name='"+$(" #name ").val()+"' where lou=<?echo param(3);
                                                    ?> and ceng=<?echo param(4);
                                                    ?> and hu=<?echo param(5);
                                                    ?>"

                                                }, function(result) {
                                                    new jBox('Notice', {
                                                        theme: 'NoticeFancy',
                                                        attributes: {
                                                            x: 'left',
                                                            y: 'bottom'
                                                        },
                                                        color: 'green',
                                                        content: "修改成功!",
                                                        title: '！',
                                                        maxWidth: 600,
                                                        audio: 'static/Source/audio/bling2',
                                                        volume: 80,
                                                        autoClose: Math.random() * 8000 + 2000,
                                                        animation: {
                                                            open: 'slide:bottom', close: 'slide:left'
                                                        },
                                                        delayOnHover: true,
                                                        showCountdown: true,
                                                        closeButton: true
                                                    }).open();
                                                });

                                            });
                                        </script>


                                        <?
                                    }
                                } else if (!strcmp(param(2),"jiaofei")) {
                                    //缴费按钮逻辑
                                    echo "缴费界面";

                                    $a = json_decode(MakeQuery("select id,name from wuye_usr where lou=".param(3)." and ceng=".param(4)." and hu=".param(5)));
                                    //获取到id，name
                                    $uid = $a[0]->id;
                                    $uname = $a[0]->name;

                                    echo "当前日期&nbsp;" . date("Y-m-d") . "<br>";
                                    echo "当前用户&nbsp;" . $uname . "<br>";
                                    ?>
                                    <div class="row clearfix">
                                        <div class="col-md-6 column">
                                            <div class="card" style="width: 100%;">
                                                <div class="card-header">
                                                    开始日期
                                                </div>
                                                <div class="card-body">
                                                    <p>
                                                        <input type="text" class="layui-input" id="<?echo param(3)."-".param(4)."-".param(5)."-";
                                                        ?>kaishi" placeholder="yyyy-MM-dd">
                                                        <div class="layui-inline" id="test-n1"></div>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 column">
                                            <div class="card" style="width: 100%;">
                                                <div class="card-header">
                                                    结束日期
                                                </div>
                                                <div class="card-body">
                                                    <p>
                                                        <input type="text" class="layui-input" id="<?echo param(3)."-".param(4)."-".param(5)."-";
                                                        ?>jieshu" placeholder="yyyy-MM-dd">
                                                        <div class="layui-inline" id="test-n2"></div>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            layui.use('laydate', function() {
                                                var laydate = layui.laydate;

                                                //常规用法
                                                laydate.render({
                                                    elem: '#test-n1', position: 'static', done: function(value, date, endDate) {

                                                        $("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                            ?>kaishi").attr("value", value);
                                                        console.log($("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                            ?>kaishi").val()); //得到日期生成的值，如：2017-08-18
                                                        //console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                                                        //console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                                                    }
                                                });
                                                laydate.render({
                                                    elem: '#test-n2', position: 'static', done: function(value, date, endDate) {

                                                        $("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                            ?>jieshu").attr("value", value);
                                                        console.log($("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                            ?>jieshu").val()); //得到日期生成的值，如：2017-08-18
                                                        //console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
                                                        //console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                    <hr>
                                    <div class="row clearfix">
                                        <div class="col-md-12 column">
                                            <div class="card" style="width: 100%;">
                                                <div class="card-header">
                                                    缴费金额及备注
                                                </div>
                                                <div class="card-body">
                                                    <p>
                                                        <input type="text" class="layui-input" id="money" placeholder="缴费金额">
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>


                                    <button id="btn-jiaofei" type="button" class="btn btn-primary">缴费</button>

                                    <script>
                                        $("#btn-jiaofei").on("click", function () {
                                            var usr_name = "<? echo $uname;
                                            //先通过PHP查找lou ceng hu对应的id name ?>";
                                            var usr_id = "<? echo $uid;
                                            ?>";
                                            console.log("INSERT INTO wuye_usr_time (id,name,jiaofei_time,start_time,over_time,money) VALUES ('"+usr_id+"','"+usr_name+"',CURDATE(),'"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                ?>kaishi").val()+"','"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                ?>jieshu").val()+"','"+$("#money").val()+"');");
                                            $.post("core/lcf_mysql.php", {
                                                str: "INSERT INTO wuye_usr_time (id,name,jiaofei_time,start_time,over_time,money) VALUES ('"+usr_id+"','"+usr_name+"',CURDATE(),'"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                    ?>kaishi").val()+"','"+$("#<?echo param(3)."-".param(4)."-".param(5)."-";
                                                    ?>jieshu").val()+"','"+$("#money").val()+"');"

                                            }, function(result) {
                                                new jBox('Notice', {
                                                    theme: 'NoticeFancy',
                                                    attributes: {
                                                        x: 'left',
                                                        y: 'bottom'
                                                    },
                                                    color: 'green',
                                                    content: "缴费成功!",
                                                    title: '！',
                                                    maxWidth: 600,
                                                    audio: 'static/Source/audio/bling2',
                                                    volume: 80,
                                                    autoClose: Math.random() * 8000 + 2000,
                                                    animation: {
                                                        open: 'slide:bottom', close: 'slide:left'
                                                    },
                                                    delayOnHover: true,
                                                    showCountdown: true,
                                                    closeButton: true
                                                }).open();

                                            });

                                        });
                                    </script>
                                    <?
                                }
                            }

                            break;
                        }
                        case $route['add']: {
                            //添加用户路由
                            // header("Access-Control-Allow-Origin: *"); //允许跨域访问，暂时测试用
                            ?>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6 column" sytle="width:100px">
                                        <div class="row">
                                            <h3>户名:</h3>
                                            <input type="text" class="form-control" style="width:100px" id="name" placeholder="户名" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 column" sytle="width:100px">
                                        <div class="row">
                                            <h3>门牌号:</h3>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" style="width:50px" id="lou" placeholder="楼" value="" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" style="width:50px" id="ceng" placeholder="层" value="" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" style="width:50px" id="hu" placeholder="户" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row clearfix">
                                    <div class="col-md-4 column">
                                        <h3>户型面积:</h3>
                                        <input type="text" class="form-control" id="aera" placeholder="面积" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                        <h3>联系电话:</h3>
                                        <input type="text" class="form-control" id="number" placeholder="电话" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                    </div>
                                </div>
                                <hr>
                                <div class="row clearfix">
                                    <div class="card" style="width: 100%;">
                                        <div class="card-header">
                                            备注信息
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <textarea class="form-control" id="other" value="" rows="3"></textarea>
                                            </p>
                                            <button id="btn-add" type="button" class="btn btn-primary">添加用户</button>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $("#btn-add").on("click", function () {
                                        //执行添加用户的命令
                                        var s = 'INSERT INTO wuye_usr (name,lou,ceng,hu,aera,number,other) VALUES ("';
                                        s += $("#name").val()+'"';
                                        s += ',"';
                                        s += $("#lou").val()+'"';
                                        s += ',"';
                                        s += $("#ceng").val()+'"';
                                        s += ',"';
                                        s += $("#hu").val()+'"';
                                        s += ',"';
                                        s += $("#aera").val()+'"';
                                        s += ',"';
                                        s += $("#number").val()+'"';
                                        s += ',"';
                                        s += $("#other").val()+'"';
                                        s += ')';
                                        console.log(s);
                                        $.ajax({
                                            type: "POST", <? //post方式加密后传给后台 ?>
                                            url: "core/lcf_mysql.php",
                                            data: {
                                                str: s
                                            },
                                            //dataType: "json",
                                            success: function(data) {
                                                //成功后弹出对话框，退出BOX
                                                new jBox('Notice', {
                                                    theme: 'NoticeFancy',
                                                    attributes: {
                                                        x: 'left',
                                                        y: 'bottom'
                                                    },
                                                    color: 'green',
                                                    content: "添加成功!",
                                                    title: '！',
                                                    maxWidth: 600,
                                                    audio: 'static/Source/audio/bling2',
                                                    volume: 80,
                                                    autoClose: Math.random() * 8000 + 2000,
                                                    animation: {
                                                        open: 'slide:bottom', close: 'slide:left'
                                                    },
                                                    delayOnHover: true,
                                                    showCountdown: true,
                                                    closeButton: true
                                                }).open();
                                                open_win.close(); //添加成功后，执行操作
                                            }
                                        });


                                    });
                                </script>
                            </div>
                            <?
                            break;
                        }
                        case $route['add2']: {
                            //添加车位的路由
                            //添加用户路由
                            // header("Access-Control-Allow-Origin: *"); //允许跨域访问，暂时测试用
                            ?>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-6 column" sytle="width:100px">
                                        <div class="row">
                                            <h3>车主姓名:</h3>
                                            <input type="text" class="form-control" style="width:100px" id="name" placeholder="车主姓名" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 column" sytle="width:100px">
                                        <div class="row">
                                            <h3>门牌号:</h3>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" style="width:50px" id="lou" placeholder="楼" value="" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" style="width:50px" id="ceng" placeholder="层" value="" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" style="width:50px" id="hu" placeholder="户" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row clearfix">
                                    <div class="col-md-4 column">
                                        <h3>车牌号:</h3>
                                        <input type="text" class="form-control" id="carnumer" placeholder="车牌号" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                        <h3>联系电话:</h3>
                                        <input type="text" class="form-control" id="number" placeholder="电话" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                        <h3>停车卡号:</h3>
                                        <input type="text" class="form-control" id="cardnum" placeholder="停车卡号" value="" required>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-4 column">
                                        <h3>租售分类:</h3>
                                        <input type="text" class="form-control" id="type" placeholder="租售分类" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                        <h3>租金:</h3>
                                        <input type="text" class="form-control" id="money" placeholder="租金" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                        <h3>车位号:</h3>
                                        <input type="text" class="form-control" id="pos" placeholder="车位号" value="" required>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-md-4 column">
                                        <h3>时长:</h3>
                                        <input type="text" class="form-control" id="long" placeholder="时长" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                        <h3>押金:</h3>
                                        <input type="text" class="form-control" id="yajin" placeholder="押金" value="" required>
                                    </div>
                                    <div class="col-md-4 column">
                                        <h3>停车分区:</h3>
                                        <input type="text" class="form-control" id="block" placeholder="停车分区" value="" required>
                                    </div>

                                </div>
                                <hr>
                                <div class="row clearfix">
                                    <div class="card" style="width: 100%;">
                                        <div class="card-header">
                                            备注信息
                                        </div>
                                        <div class="card-body">
                                            <p>
                                                <textarea class="form-control" id="other" value="" rows="3"></textarea>
                                            </p>
                                            <button id="btn-add" type="button" class="btn btn-primary">添加用户</button>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $("#btn-add").on("click", function () {
                                        //执行添加用户的命令

                                        var s = 'INSERT INTO wuye_car(name, lou, ceng, hu, carnumber, cardnum, type, money, pos, number, yajin, long, other, block) VALUES ("';
                                        s += $("#name").val()+'"';
                                        s += ',"';
                                        s += $("#lou").val()+'"';
                                        s += ',"';
                                        s += $("#ceng").val()+'"';
                                        s += ',"';
                                        s += $("#hu").val()+'"';
                                        s += ',"';
                                        s += $("#carnumber").val()+'"';
                                        s += ',"';
                                        s += $("#cardnum").val()+'"';
                                        s += ',"';
                                        s += $("#type").val()+'"';
                                        s += ',"';
                                        s += $("#money").val()+'"';
                                        s += ',"';
                                        s += $("#pos").val()+'"';
                                        s += ',"';
                                        s += $("#number").val()+'"';
                                        s += ',"';
                                        s += $("#yajin").val()+'"';
                                        s += ',"';
                                        s += $("#long").val()+'"';
                                        s += ',"';
                                        s += $("#other").val()+'"';
                                        s += ',"';
                                        s += $("#block").val()+'"';
                                        s += ')';
                                        console.log(s);
                                        $.ajax({
                                            type: "POST", <? //post方式加密后传给后台 ?>
                                            url: "core/lcf_mysql.php",
                                            data: {
                                                str: s
                                            },
                                            //dataType: "json",
                                            success: function(data) {
                                                //成功后弹出对话框，退出BOX
                                                new jBox('Notice', {
                                                    theme: 'NoticeFancy',
                                                    attributes: {
                                                        x: 'left',
                                                        y: 'bottom'
                                                    },
                                                    color: 'green',
                                                    content: "添加成功!",
                                                    title: '！',
                                                    maxWidth: 600,
                                                    audio: 'static/Source/audio/bling2',
                                                    volume: 80,
                                                    autoClose: Math.random() * 8000 + 2000,
                                                    animation: {
                                                        open: 'slide:bottom', close: 'slide:left'
                                                    },
                                                    delayOnHover: true,
                                                    showCountdown: true,
                                                    closeButton: true
                                                }).open();
                                                open_win.close(); //添加成功后，执行操作
                                            }
                                        });


                                    });
                                </script>
                            </div>
                            <?
                            break;
                        }
                        case $route['qianfei']: {
                            //欠费物业费页面,暂时先编写物业测试的部分
                            include_once("module/hall_header.php");
                            //Step1.执行语句获取注释,填充列头
                            ?>
                            <style type="text/css">  
                            <!--  
                            /************ Table ************/  
                            .xwtable {width: 100%;border-collapse: collapse;border: 1px solid #ccc;}                  
                            .xwtable thead td {font-size: 12px;color: #333333;text-align: center;
                            /*background: url(table_top.jpg) repeat-x top center;*/
                            border: 1px solid #ccc; font-weight:bold;}  
                            .xwtable tbody tr {background: #fff;font-size: 12px;color: #666666;}             
                            .xwtable tbody tr.alt-row {background: #f2f7fc;}                 
                            .xwtable td{line-height:20px;text-align: left;padding:4px 10px 3px 10px;height: 18px;border: 1px solid #ccc;}  
                            -->  
                            </style>
                            <hr>
                            <h5>30天内到期列表 </h5>
                            <hr>
                            <table class="xwtable">
                            <?
                            if(!strcmp(param(1),"wuye_usr")){
                            ?>
                            
                                <tr>
                                    <td>ID</td>
                                    <td>到期时间</td>
                                    <td>姓名</td>
                                    <td>楼</td>
                                    <td>层</td>
                                    <td>户</td>
                                    <td>电话</td>
                                    <td>备注</td>
                                </tr>
                            <?
                            }else{
                            ?>
                                <tr>
                                    <td>ID</td>
                                    <td>到期时间</td>
                                    <td>姓名</td>
                                    <td>楼</td>
                                    <td>层</td>
                                    <td>户</td>
                                    <td>车牌号</td>
                                    <td>电话</td>
                                    <td>备注</td>
                                </tr>
                            <?
                            }
                            //$commit = json_decode(MakeQuery("SELECT  column_name, column_comment FROM information_schema.columns WHERE table_schema ='dyexlzc' AND table_name = '".param(1)."'"));
                            //print_r($commit);
                            //echo stripslashes("asdf\asdf");
                            //$n = count($commit);
                            /*for ($i = 0;$i < $n-1;$i++) {
                                echo "'".$commit[$i]->column_name."'";
                                echo "'".$commit[$i]->column_comment."'";

                            }*/
                            //Step2.从wuye_usr表中查找所有不同的name
                           // echo "SELECT id,name from '".param(1)."'";
                           if(!strcmp(param(1),"wuye_usr")){
                                $NameId_list = json_decode(MakeQuery("SELECT id,name,lou,ceng,hu,number,other from ".param(1).""));
                           }
                           else
                           {
                               $NameId_list = json_decode(MakeQuery("SELECT id,name,lou,ceng,hu,number,carnumber,other from ".param(1).""));
                           }
                            $n = count($NameId_list);
                            for ($i = 0;$i < $n-1;$i++) {
                                //echo
                               // echo "SELECT over_time FROM `wuye_usr_time` WHERE id=".$NameId_list[$i]->id." order by over_time DESC limit 1"."<br>";
                               // echo "'".$NameId_list[$i]->id."'";
                                //echo "'".$NameId_list[$i]->name."'";
                               // echo "<br>";
                                //Step3.使用id和name去time表里面找出最新的日期
                                //echo "SELECT id,name from '".param(1)."'";
                                $over_time = json_decode(MakeQuery("SELECT over_time FROM `wuye_usr_time` WHERE id=".$NameId_list[$i]->id." order by over_time DESC limit 1")); //获得该用户的到期时间
                                //Step4.判断是否过期
                                
                                if(strtotime($over_time[0]->over_time)>=strtotime("-30 day")){
                                      // echo "跳过";
                                       continue;
                                }
                                else{
                                //echo $NameId_list[$i]->name."：".$over_time[0]->over_time;
                               // echo "<br>";
                               ?>
                                <tr>
                                    <td><?echo $NameId_list[$i]->id;?></td>
                                    <td><?echo $over_time[0]->over_time;?></td>
                                    <td><?echo $NameId_list[$i]->name;?></td>
                                    <td><?echo $NameId_list[$i]->lou;?></td>
                                    <td><?echo $NameId_list[$i]->ceng;?></td>
                                    <td><?echo $NameId_list[$i]->hu;?></td>
                                    <?
                                    if(!strcmp(param(1),"wuye_car")){
                                    ?>
                                    <td><?echo $NameId_list[$i]->carnumber;?></td>
                                    
                                    <?
                                    }
                                    ?>
                                    <td><?echo $NameId_list[$i]->number;?></td>
                                    <td><?echo $NameId_list[$i]->other;?></td>
                                </tr>
                               <?
                                }
                                //$n = count($NameId_list);
                            }
                            //echo strtotime($over_time[0]->over_time)+strtotime("+30 day")."<br>";
                            //echo strtotime("today");

                            ?>
                            </table>
                            <?
                            include_once("module/home_footer.php");
                            break;
                        }
                        case $route['search_post']: {

                            //直接获取服务器session['search_str']的值
                            include_once("module/hall_header.php");
                            //接受post参数，post参数是一串sql语句，执行后排版即可
                            //echo $_SESSION['sqtr'];

                            $arr = json_decode(MakeQuery($_SESSION['sqtr']));
                            //执行语句
                            $commit = json_decode(MakeQuery($_SESSION['commit']));
                            //执行语句获取注释

                            ?>
                            <hr>
                            <div class="layui-form">
                                <table class="layui-table">
                                    <colgroup>
                                        <?
                                        foreach ($commit as $u) {
                                            //将列头固定
                                            ?>
                                            <col width="50">
                                            <?
                                        }
                                        ?>
                                        <col>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <?
                                            foreach ($commit as $u) {
                                                //将列头固定
                                                ?>
                                                <th><? echo $u->column_comment;
                                                    ?></th>
                                                <?
                                            }
                                            ?>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?

                                        foreach ($arr as $u) {
                                            ?>
                                            <tr>

                                                <?
                                                //循环显示所有缴费记录
                                                foreach ($commit as $i) {
                                                    ?>
                                                    <td>
                                                        <?
                                                        $temp = $i->column_name;
                                                        eval ("echo\$u->{$temp};");
                                                    }
                                                    ?>
                                                </td>
                                            </tr><?
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <?
                            include_once("module/home_footer.php");
                            break;
                        }
                        case $route['adv_search']:  //将结果在搜索框下方显示,节约空间，前端只传递表名以及信息给后台，显示则是返回json由layui数据表格显示
                        {
                            include_once("module/hall_header.php");
                            ?>
                            <hr>
                            <script>
                                var action_tab = 0;
                                __name = "";
                                table_txt = "";
                                txt = "";
                                table_raw = "";
                                function get_item(i) {
                                    switch (i) {
                                        //获取表
                                        case(i = 1): //物业用户所代表的语句
                                            {
                                                table_txt = "物业表";
                                                table_raw = "wuye_usr";
                                                $.post("core/lcf_mysql.php", {
                                                    str: "SELECT  column_name, column_comment FROM information_schema.columns WHERE table_schema ='dyexlzc' AND table_name = 'wuye_usr'"
                                                }, function(result) {
                                                    var dataObj = eval("("+result+")"); //转换为json对象
                                                    console.log(dataObj); //输出root的子对象数量
                                                    var inr_html = "";
                                                    $.each(dataObj, function(idx, item) {
                                                        if (idx == 0) {
                                                            return true;
                                                        }

                                                        //输出每个root子对象的名称和值
                                                        //alert("name:"+item.column_comment+",value:"+item.column_name);
                                                        inr_html += '<a class="dropdown-item" href="javascript:void(0);" onclick="__name=\''+item.column_name+'\';txt=\''+item.column_comment+'\'; $(\'#s_btn\').html(\'搜索-\'+table_txt+\'中的\'+txt);">'+item.column_comment+'</a> '
                                                    });
                                                    $("#select2").html(
                                                        inr_html
                                                    );
                                                });
                                                break;
                                            }
                                        case(i = 2): //车位用户所代表的语句
                                            {
                                                table_txt = "车位表";
                                                table_raw = "wuye_car";
                                                $.post("core/lcf_mysql.php", {
                                                    str: "SELECT  column_name, column_comment FROM information_schema.columns WHERE table_schema ='dyexlzc' AND table_name = 'wuye_car'"
                                                }, function(result) {


                                                    var dataObj = eval("("+result+")"); //转换为json对象
                                                    console.log(dataObj); //输出root的子对象数量
                                                    var inr_html = "";
                                                    $.each(dataObj, function(idx, item) {
                                                        if (idx == 0) {
                                                            return true;
                                                        }

                                                        //输出每个root子对象的名称和值
                                                        //alert("name:"+item.column_comment+",value:"+item.column_name);
                                                        inr_html += '<a class="dropdown-item" href="javascript:void(0);" onclick="__name=\''+item.column_name+'\';txt=\''+item.column_comment+'\'; $(\'#s_btn\').html(\'搜索-\'+table_txt+\'中的\'+txt);">'+item.column_comment+'</a> '
                                                    });
                                                    $("#select2").html(
                                                        inr_html
                                                    );
                                                });
                                                break;
                                            }
                                    }

                                }
                            </script>
                            <div class="container">
                                <div class="row clearfix">
                                    <div class="col-md-12 column">
                                        <div class="input-group">
                                            <input id="input_str" type="text" class="form-control" aria-label="Text input with dropdown button">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">选择查询表</button>
                                                <div class="dropdown-menu" id="select">
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="get_item(1)">物业用户</a>
                                                    <a class="dropdown-item" href="javascript:void(0);" onclick="get_item(2)">车位用户</a>
                                                </div>
                                            </div>
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">选择查询项目</button>
                                                <div class="dropdown-menu" id="select2">

                                                </div>

                                                <button id="s_btn" class="btn btn-primary" type="button">请选择搜索项目</button>
                                                <script>
                                                    $("#s_btn").click(function(e) {
                                                        if (typeof(table_txt) == "undefined" || typeof(txt) == "undefined") {
                                                            alert("请选择搜索项目");
                                                            return;
                                                        }
                                                        $.post("module/get_table.php", {
                                                            qstr: __name, //字段名
                                                            input: $("#input_str").val(), //查询名
                                                            table: table_raw//表名

                                                        }, function(result) {
                                                            $("#content").html(result);
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                    
                               
                            </div>
                            <div id="content" class="col-md-12 column">

                             </div>

                            <?
                            include_once("module/home_footer.php");
                            break;
                        }
                        default: {
                            //当找不到模块时默认跳转到首页
                            include_once("module/home_index.php");
                            break;
                        }
                    }


                    ?>