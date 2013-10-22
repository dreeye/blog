<?php if (isset($js_files)) $this->js($js_files);?>
<script type="text/javascript">
    jQuery(function($){
    $.scrolltotop({
    className: 'totop',
   // controlHTML : '<a href="javascript:;">回到顶部↑</a>',
        //此处可以换成图片如 '' ,
    controlHTML : '<img style="width: 50px; height: 50px" src="/themes/default/images/top_arrow.png"/>',
       
    offsety:0
   });
});
</script>
</body>
</html>
