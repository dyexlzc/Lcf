<?
        /*
            数据库接口文件，网站中所有的数据库查询必须经过此文件转发，目的是为了记录查询人以及查询内容，方便灾难恢复
            任何SQL请求都通过post请求发送过来
        */
        header("Content-type: text/html; charset=utf-8"); 
        
        //ini_set('display_errors',1);            //错误信息
        //ini_set('display_startup_errors',1);    //php启动错误信息
        //error_reporting(-1);     
        include_once("db.php");//引入数据库文件
        /*通过ajax后台请求后无需加密，因为前台看不见*/
        function MakeQuery($qstr)
        {
            global $db_host,$db_usr,$db_pwd, $db_name;
            $con = mysqli_connect($db_host,$db_usr,$db_pwd);//连接数据库
            mysqli_select_db( $con, $db_name );
            if (!$con){
              die('Could not connect: ' . mysqli_error());
            }
            // 设置编码，防止中文乱码
            mysqli_query($con , "set names utf8");
            $retval = mysqli_query( $con,$qstr);
            if(! $retval )
            {
                die('无法读取数据: ' . mysqli_error($con));
            }
    
            /*while($row = mysqli_fetch_assoc($retval))
            {
                print_r($row);
            }*/
            //  json编码类别
            $arr = array(); 
            while($row = mysqli_fetch_array($retval)) { 
              $count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
              for($i=0;$i<$count;$i++){ 
                unset($row[$i]);//删除冗余数据 
              } 
              
              array_push($arr,$row); 
              
            } 
            mysqli_close($con);
            return json_encode($arr,JSON_UNESCAPED_UNICODE);
            
        }
        if(!empty($_POST['str'])){
            $con = mysqli_connect($db_host,$db_usr,$db_pwd);//连接数据库
            mysqli_select_db( $con, $db_name );
            if (!$con){
              die('Could not connect: ' . mysqli_error());
            }
            // 设置编码，防止中文乱码
            mysqli_query($con , "set names utf8");
            $retval = mysqli_query( $con,$_POST['str']);
            if(! $retval )
            {
                die('无法读取数据: ' . mysqli_error($con));
            }
    
            /*while($row = mysqli_fetch_assoc($retval))
            {
                print_r($row);
            }*/
            //  json编码类别
            $arr = array(); 
            while($row = mysqli_fetch_array($retval)) { 
              $count=count($row);//不能在循环语句中，由于每次删除 row数组长度都减小 
              for($i=0;$i<$count;$i++){ 
                unset($row[$i]);//删除冗余数据 
              } 
              
              array_push($arr,$row); 
              
            } 
            print(json_encode($arr,JSON_UNESCAPED_UNICODE));
            mysqli_close($con);
            //return 
        }
            
?>