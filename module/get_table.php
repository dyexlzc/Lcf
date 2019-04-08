<?php
include_once("../core/lcf_mysql.php");
//1.接受参数
$input = $_POST['input'];
$str = $_POST['qstr'];
$table = $_POST['table'];
//2.查询参数

//如果没有传来参数列表
$sqtr = "select * from ".$table." where ".$str." like '%".$input."%'";
//通过session中转
$commit_ = "SELECT  column_name, column_comment FROM information_schema.columns WHERE table_schema ='dyexlzc' AND table_name = '".$table."'";
$arr = json_decode(MakeQuery($sqtr));
//执行语句
$commit = json_decode(MakeQuery($commit_));
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