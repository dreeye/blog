<?php require 'header.php';?>
    <div class="body">
        <div class="left">
            <div class="content">    
                <h3>
                    <?php echo $post->title;?>
                </h3>
                <h4>
                    <span>发布于:<?php echo date('Y-m-d H:i',$post->created);?></span>
                    <span>分类:
                        <?php 
                        $length = count($post->categories);
                        foreach($post->categories as $key => $val):?>
                        <?php
                            echo '<a href="'; 
                            echo site_url("/category/".$val['slug']);
                            echo '">' . $val['name'] . '</a>' . ($key < $length - 1 ? ', ' : ''); ?>
                        <?php endforeach;?>
                    </span>
                    <span class="no-border">
                        <a href="#comment">发表评论</a>
                    </span>
                </h4>
                    <div class="post single_post">
                     <?php echo $post->content;?>
                        <div class="tags">
                            <!-- 标签-->
                            <span class="no-border">标签:
                                <?php 
                                $length = count($post->tags);
                                foreach($post->tags as $key => $val):?>
                                <?php
                                    echo '<a href="'; 
                                    echo site_url("/tag/".$val['slug']);
                                    echo '">' . $val['name'] . '</a>' . ($key < $length - 1 ? ', ' : ''); ?>
                                <?php endforeach;?>
                            </span>
                        </div>
                    </div><!-- end post-->
<div id="comment">
<script type="text/javascript">
(function(){
var url = "http://widget.weibo.com/distribution/comments.php?width=0&url=auto&ralateuid=1967715215&appkey=4192325494&dpc=1";
url = url.replace("url=auto", "url=" + document.URL); 
document.write('<iframe id="WBCommentFrame" src="' + url + '" scrolling="no" frameborder="0" style="width:100%"></iframe>');
})();
</script>
<script src="http://tjs.sjs.sinajs.cn/open/widget/js/widget/comment.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
window.WBComment.init({
    "id": "WBCommentFrame"
});
</script>
</div>
    </div><!-- content end-->
    </div><!-- left end-->
        <?php require 'sidebar.php';?>

</div><!--body end-->
<?php require 'footer.php';
