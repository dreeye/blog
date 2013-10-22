<div class="right">
    <h4>近期文章</h4>
    <ul>
    <?php foreach($new_posts->result() as $val):?>
        <li><a href="/post/<?php echo $val->slug;?>"><?php echo $val->title;?></a></li>
    <?php endforeach;?>
    </ul>
    <h4>分类</h4>
    <ul>
    <?php foreach($cate_list->result() as $cate):?>
        <li><a href="/category/<?php echo $cate->slug;?>"><?php echo $cate->name;?></a></li>
    <?php endforeach;?>
    </ul>
    <h4>微博</h4>
    <ul>
        <li><a href="http://weibo.com/pronoz">@王维pronoz</a></li>
    </ul>
</div>
