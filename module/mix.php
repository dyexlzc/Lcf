<?php
    include_once("../core/Lcf_core.php");        //引入lcf_core主要文件

    $Page_hall=new Page;
    $Page_hall->add_el('hall_header.php');
    $Page_hall->add_el('hall_body.php');
   // $Page_hall->add_el('home_footer.php'); //添加页面元素,可以将网站公用头或者公用页脚作为统一的el载入 

    $Page_hall->merge('hall_index.php');   //合并文件

    $Page_index=new Page;
    $Page_index->add_el('home_header.php');
    $Page_index->add_el('home_body.php');
    $Page_index->add_el('home_footer.php'); //添加页面元素,可以将网站公用头或者公用页脚作为统一的el载入 

    $Page_index->merge('home_index.php');   //合并文件
?>