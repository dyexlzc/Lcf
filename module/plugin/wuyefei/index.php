<?php
class wuyefei {
    //类名必须和文件夹名相同
    public static function name() {
        return "物业费";
    }
}

//不用header，只需编写body
//设计思路：大button表示楼，逐层点入，到期做初表格，查看的话则默认用简易方式查看
//设计路由访问楼层并用ajax动态更新
?>
