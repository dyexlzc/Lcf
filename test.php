<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<div class="hu">
    <div id="demo">
    </div>
</div>
<div id="demo2">
    <button id="h-1">aaaa</button>
    <button id="h-3">aaaa</button>
    <button id="h-5">aaaa</button>
    <button id="h-7">aaaa</button>
    <button id="h-9">aaaa</button>
</div>
<script type="text/javascript">
    //动态像ul的末尾追加一个新元素
    for (i = 0; i < 10; i++)
    if(i%2==0)continue;
    else
        $("#demo").append('<button id="h-'+i+'">aaaa</button>');
    for (var i = 0; i < 10; i++) {
        $("#demo").on('click', '#h-'+i ,function(){ 
            alert($(this).attr("id"));
        });
    }
    // 示范一
    for (var i = 0; i < 10; i++) {
        if ($("#h-" + i).length > 0) 
        $("#demo2").on('click', '#h-'+i ,function(){ 
            alert($(this).attr("id"));
        });
    }
</script>