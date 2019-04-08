{
"code": 0,
"msg": "",
"data": [
<?

$page_s=((int)($_GET['page'])-1)*$_GET['limit'];
$page_o=$_GET['limit'];

include_once("../../../core/lcf_mysql.php");
header('Content-Type:application/json; charset=utf-8');

echo "
    SELECT
   a.id,
   a.name,
   a.lou,
   a.ceng,
   a.hu,
   a.aera,
   a.number,
   a.other,
   a.start_time,
   a.over_time
FROM
  {$table} a
WHERE
  NOT EXISTS(
    SELECT b.name FROM wuye_usr_time b WHERE a.name=b.name
  ) OR
  NOT EXISTS(
    SELECT b.id FROM wuye_usr_time b WHERE a.id=b.id  #以上两段从wuye_usr总表查询没有更新的信息
  ) 
UNION
SELECT
   wuye_usr.id,
   wuye_usr.name,
   wuye_usr.lou,
   wuye_usr.ceng,
   wuye_usr.hu,
   wuye_usr.aera,
   wuye_usr.number,
   wuye_usr.other,
   wuye_usr_time.start_time,
   wuye_usr_time.over_time
FROM
   {$table}
right join {$table}_time on {$table}.id={$table}_time.id and date_add(curdate(), interval 30 day)>{$table}_time.over_time 
  ";
  exit();
$table = $_GET['table'];
$arr = json_decode(  (
    MakeQuery("
    SELECT
   a.id,
   a.name,
   a.lou,
   a.ceng,
   a.hu,
   a.aera,
   a.number,
   a.other,
   a.start_time,
   a.over_time
FROM
  {$table} a
WHERE
  NOT EXISTS(
    SELECT b.name FROM wuye_usr_time b WHERE a.name=b.name
  ) OR
  NOT EXISTS(
    SELECT b.id FROM wuye_usr_time b WHERE a.id=b.id  #以上两段从wuye_usr总表查询没有更新的信息
  ) 
UNION
SELECT
   wuye_usr.id,
   wuye_usr.name,
   wuye_usr.lou,
   wuye_usr.ceng,
   wuye_usr.hu,
   wuye_usr.aera,
   wuye_usr.number,
   wuye_usr.other,
   wuye_usr_time.start_time,
   wuye_usr_time.over_time
FROM
   {$table}
right join {$table}_time on {$table}.id={$table}_time.id and date_add(curdate(), interval 30 day)>{$table}_time.over_time 
  "
    )));
//先选择表中没有更新信息的用户
//执行语句获取注释,填充列头

$commit = json_decode(MakeQuery("SELECT  column_name, column_comment FROM information_schema.columns WHERE table_schema ='dyexlzc' AND table_name = '{$table}'"));


$end = end($commit); //选择最后一个元素
$n=count($arr);
$tok=false;
$count=0;
for($i_=0;$i_<$n;$i_++){
    //选择单条数据
    if($arr[$i_]->id==""&&$i_!=$n-1){  //如果找到没过期的,且不是最后一条数据，就跳过
        continue;
    }
    else if($i_==$n-1){ //如果找到了过期的，且是最后一条，就不输出,
        $tok=true;
    }
    ?>
    {
    <?
    foreach ($commit as $i) { //单条数据
        //选择表头并且填充数据
        $count++;
            ?>
            "<?echo $i->column_name ?>":"<?$temp = $i->column_name;
            eval ("echo preg_replace('/\r|\n/', '', stripslashes(\$arr[\$i_]->{$temp}));"); 
            if($i->column_name!=$end->column_name) //如果没有到达最后，就输出",
                echo "\",";
            else
                echo "\"";
            ?>
        <?
    }
        //这里输出json单条数据的}
    if($tok==true)
    {
        echo "}";
    }
    else
    {
            echo "},";
    }
}
//页脚php标志 
?>
],
    "count": <?echo $count+1;?>
}