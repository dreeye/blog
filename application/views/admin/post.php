<?php require 'header.php'?>
<?php require 'top.php'?>
<?php require 'menu.php'?>
<?php
    //标题
    $_title =array(
        'name'=>'p_title',
        'type'=>'text',
        'class'=>'post-title',
        'value'=>set_value('p_title',(isset($p_title)?htmlspecialchars_decode($p_title):'')),   
    );
    //正文
    $_content=array(
        'id'  =>'editor1',
        'name'=>'content',
        'style'=>'width:100%;height:500px;',
        'value'=>set_value('content',isset($content)?$content:''),
    );
    //保存草稿
    $_save=array(
        'name'=>'save',
        'id'  =>'save',
        'class'=>'post-savepost-input',
        'type'=>'submit',
        'value'=>'保存草稿',
    );
    //预览
    $_view = array(
        'name'=>'view',
        'class'=>'post-viewpost-input',
        'type'=>'submit',
        'value'=>'预览',
    );
    //年
    $_year = array(
        'name'=>'year',
        'id'=>'year',
        'maxlength'=>'4',
        'type'=>'text',
        'value'=>$this->form_validation->year,
    );
    //月(select)
    $_month = array(
        '1'=>'一月',
        '2'=>'二月',
        '3'=>'三月',
        '4'=>'四月',
        '5'=>'五月',
        '6'=>'六月',
        '7'=>'七月',
        '8'=>'八月',
        '9'=>'九月',
        '10'=>'十月',
        '11'=>'十一月',
        '12'=>'十二月',
    ); 
    //当前是哪月     
    switch ($this->form_validation->month)
    {
        case 1:
            $select_month='1';
            break;
        case 2:
            $select_month='2';
            break;
        case 3:
            $select_month='3';
            break;
        case 4:
            $select_month='4';
            break;
        case 5:
            $select_month='5';
            break;
        case 6:
            $select_month='6';
            break;
        case 7:
            $select_month='7';
            break;
        case 8:
            $select_month='8';
            break;
        case 9:
            $select_month='9';
            break;
        case 10:
            $select_month='10';
            break;
        case 11:
            $select_month='11';
            break;
        case 12:
            $select_month='12';
            break;

    }
    $id_month=array('id'=>'month');
    //日
    $_day = array(
            'id'=>'day',
            'name'=>'day',
            'type'=>'text',
            'value'=>$this->form_validation->day,                   
    );
    //时
    $_hour = array(
            'id'=>'hour',
            'name'=>'hour',
            'type'=>'text',
            'value'=>$this->form_validation->hour,                  
    );
    //秒
    $_minute = array(       
            'id'=>'minute',
            'name'=>'minute',
            'type'=>'text',
            'value'=>$this->form_validation->minute,                        
    );
    //发布（input）
    $_submit = array(
            'id'=>'post-submit',
            'name'=>'submit',
            'type'=>'submit',
            'value'=>'发布',
    );    
    //slug（input）
    $_slug = array(
            'name'=>'slug',
            'type'=>'text',
            'class'=>'slug',
            'value'=>set_value('slug',isset($slug)?$slug:''),
    );   
    //tag(input)
    $_tag = array(
            'name'=>'tags',
            'rows'=>2,
            'class'=>'slug',
            'id'=>'tag',
            'value'=>set_value('tags',isset($tags)?$tags:''),
    ); 
?>
<div class="content">
    <div class="body-wrap">
        <div class="body">
        <?php require 'notify.php';?>
            <div class="icon icon-users"></div>
            <h2><?php echo $page_title;?></h2>
            <div class="wrap-adduser" >
                <!--提交表单-->
                <?php echo form_open();?>
                <div class="post-left">
                    <!--标题-->
                    <?php echo form_error('p_title', '<div class="attention"><ul><li>', '</li></ul></div>'); ?>
                    <?php echo form_input($_title);?>
                    <!--正文-->
                    <?php echo form_error('content', '<div class="attention"><ul><li>', '</li></ul></div>'); ?>
                    <?php echo form_textarea($_content);?>  
                </div><!--.post-left end-->
                <!--右边内容栏-->
                <div class="post-right">
                    <ul class="post-handle-ul">
                        <li class="post-public-li">
                            <label class="post-handle-label">发布</label>
                            <!--草稿 预览-->
                            <p class="post-public-action">
                                <?php echo form_input($_save);?>
                                <?php echo form_input($_view);?> 
                            </p>
                            <!--日期-->        
                            <p class="post-public-date">
                                <?php echo form_input($_year);?>
                                <?php echo form_dropdown('month',$_month,$select_month,$id_month);?>    
                                <?php echo form_input($_day);?>@
                                <?php echo form_input($_hour);?>:
                                <?php echo form_input($_minute);?>
                            </p>
                            <p>
                                <!--发布-->
                                <?php echo form_input($_submit);?>
                            </p>
                            <p class="post-fontnum">
                                已经输入了<span class="word_count">0</span>个字
                            </p>
                        </li><!--post-public-li-->
                        <!--status-->
                        <?php echo form_hidden('status','0');?> 
                        <!--分类checkbox-->
                        <li class="post-public-li">
                            <label class="post-handle-label">分类</label>
                            <?php echo form_error('category[]', '<div class="attention"><ul><li>', '</li></ul></div>'); ?>
                            <ul class="post-category-label">
                                <?php if($all_categories->num_rows() > 0):?>
                                <?php foreach($all_categories->result() as $cate):?>
                                <li>
                                    
                                    <input type="checkbox" name="category[]" id="post-checkout" value="<?php echo $cate->mid;?>" "<?php echo set_checkbox('category[]', $cate->mid,(isset($post_category) && in_array($cate->mid, $post_category))?TRUE:FALSE); ?>">
                                    <label><?php echo $cate->name;?></label>
                                </li>
                                <?php endforeach;?>
                                <?php endif;?>
                            </ul>
                        </li><!--.post-public-li-->
                        <!--分类checkbox-->
                        <li class="post-public-li">
                            <?php echo form_error('slug', '<div class="attention"><ul><li>', '</li></ul></div>'); ?>
                            <label class="post-handle-label">slug</label>
                            <?php echo form_input($_slug);?>
                        </li><!--.post-public-li-->
                        <li class="post-public-li">
                            <label class="post-handle-label">tag</label>
                            <?php echo form_textarea($_tag);?>
                        </li><!--.post-public-li-->
                   </ul><!--.post-handle-ul-->
                </div><!--.post-right end-->
                </form>
            </div><!--wrap-adduser end-->
        </div><!--body end-->
    </div><!--.body-wrap-->
</div><!--.content-->
<script type="text/javascript">

/** 点击存草稿status input=1*/
$("#save").click(function(){
    $("input[name='status']").attr('value','1');
});

/** 点击发布status input=0*/
$("#post-submit").click(function(){
    $("input[name='status']").attr('value','0');
});

/** 分类选中样式*/
$('.post-category-label > li').click(function(){
    var ischeck = $(this).hasClass('cate_check');
    $(this)[ischeck ? 'removeClass' : 'addClass']('cate_check')
        .find(':checkbox').attr('checked',!ischeck);
});

</script>
<?php require 'footer.php'?>
