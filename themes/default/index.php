<?php require 'header.php';?>
<div class="left">
    <?php if(isset($num)):?>
    <div class="filter">
       正在查看:  <?php echo $type.'为'.'&nbsp'.'<b>'.$type_name.'</b>'.'&nbsp';?>的文章内容(共<?php echo $num;?>篇) 
    </div>
    <?php endif;?>
    <?php foreach($post_list->result() as $post):?>
    <div id="<?php echo $post->slug;?>" class="content">   
        <div class="title"> 
            <h3>
                <a href="/post/<?php echo $post->slug;?>"><?php echo $post->title;?></a>
                
            </h3>
        </div>
        <div class="noz">
            <li class="nozli">  
            <a class="noza" href="#">本页预览</a>
                <ul class="nozul">
                    <?php foreach($post_list->result() as $noz):?>
                    <a <?php echo ($post->title == $noz->title) ? 'class="notice"': '';?> href="#<?php echo $noz->slug;?>"><?php echo $noz->title;?></a>
                    <?php endforeach;?>
                    <?php if(isset($pagination)&&($page<$total_pages)):?>
                        <a class="noticepage" href="?p=<?php echo $page+1?>">下一页</a>
                    <?php endif;?>
                </ul>
            </li>
        </div>
        <div class="meta">
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
                    <a href="/post/<?php echo $post->slug;?>#comment">发表评论</a>
                </span>
            </h4>
         </div><!--end meta-->
            <div class="post">
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
                            echo '">' . $val['name'] . '</a>' . ($key < $length - 1 ? ', ' : '') ?>
                        <?php endforeach;?>
                    </span>
                </div>
            </div><!-- end post-->
    </div><!--end content-->
    <?php endforeach;?>
<?php echo isset($pagination) ? $pagination : '' ?>
</div><!--end left-->
<?php require 'sidebar.php' ?>
</div><!--end body-->
<?php require 'footer.php';
