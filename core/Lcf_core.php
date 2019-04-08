<?
    /*
        Lcf_core：
        此文件包含了lcf框架绝大部分的函数。
    
    
    */
/*--------------------过程----------------------------------------*/    
    //print_r($_SERVER);
    error_reporting( E_ALL&~E_NOTICE );//取消显示notice的错误
    $url = $_SERVER['QUERY_STRING'];//其中包含了?后面的参数
    $_command = explode('/', $url);  //将参数分割
    $_var_global=[];

/*--------------------类----------------------------------------*/    
class Page{   //定义一个基础的module页面，往其中按顺序添加文件后进行合成后显示
    private $els=[];
    private $Page_name='null';
    public function add_el($file){ //按照网页组成顺序依次添加文件进行合并
        $this->els[]=$file;
    }
    public function get_el(){
        return $this->els;
    }
    public function merge($file){ //合并网页元素
        $fp   = fopen($file,"w");               //每次均是从头开始写文件,将目标文件打开
        foreach($this->els as $el)     //$el中存放的是各个元素的文件名
        {  
            $handle = fopen($el,"r");   //依次打开每一个文件
            fwrite($fp,fread($handle,filesize($el)));   //写入文件
            fclose($handle);   //关闭文件指针
            unset($handle);    //释放变量
        }
        print_r($this->els);
        fclose($fp);  
    }

}

/*--------------------函数----------------------------------------*/    
    function param($n){  //
        global $_command;  //通过global类型引入参数
        return $_command[$n];  //显示参数
    }
    function str_is($a,$b){//字符串比较，相同为1，不同为0
        return !strcmp($a,$b);
    }
    function route_module(){  //?aaa/bbb/ccc/ddd中的aaa
        global $route;
        if (is_null($route[param(0)]) ) return -1;  //试图访问一个不存在的模块时返回-1
        return $route[param(0)];
    }
    function route_plugin(){  //aaa中的bbb
        global $route_plugin;
        if (is_null($route_plugin[param(1)]) ) return -1;  //试图访问一个不存在的模块时返回-1
        return $route_plugin[param(1)];
    }
    function set_global($name,$var){  //向lcf框架存入全局变量
        global $_var_global;
        $_var_global[$name]=$var;
    }
    function get_global($name){  //获得存入的全局变量
        global $_var_global;
        return $_var_global[$name];
    }
    function getDir($dir) {
    $dirArray[]=NULL;
    if (false != ($handle = opendir ( $dir ))) {
        $i=0;
        while ( false !== ($file = readdir ( $handle )) ) {
            //去掉"“.”、“..”以及带“.xxx”后缀的文件
            if ($file != "." && $file != ".."&&!strpos($file,".")) {
                $dirArray[$i]=$file;
                $i++;
            }
        }
        //关闭句柄
        closedir ( $handle );
    }
    return $dirArray;
    }
    function getPlugin(){
        return getDir("./module/plugin");
    }
?> 