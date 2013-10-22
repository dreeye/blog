<?php require 'header.php'?>
<?php require 'top.php'?>
<?php require 'menu.php'?>
<div class="content">
    <div class="body-wrap">
        <div class="body">
            <div class="icon icon-catefory"></div>
            <h2>
                <!--标题，编辑状态返回添加状态链接-->
                <?php echo $title;?>
                <?php if(isset($mid) && is_numeric($mid)):?>
                    <?php if($type == 'category'):?>
                        <a class="addtag" href="/admin/metas/manage">添加分类</a>
                    <?php else:?>
                        <a class="addtag" href="/admin/metas/manage/tag">添加标签</a>
                    <?php endif;?>
                <?php endif;?>                          
            </h2>
            
            <!--提交成功失败提示-->
            <?php require 'notify.php';?>
            <div class="category-left">
                <?php if($type == 'category'):?>
                    <?php require 'metas_cate_form.php';?>
                <?php endif;?>
                    
                <?php if($type == 'tag'):?>
                    <?php require 'metas_tag_form.php';?>
                <?php endif;?>
            </div><!--.category-left-->

            <div class="category-right">
                <?php if($type == 'category'):?>
                    <?php echo form_open("admin/metas/operate/".$type);?>
                    <?php if( ! empty($category)):?>
                    <div class="category-control"> 
                        <select name="do" >
                            <option value="no" selected="selected">批量操作</option>
                            <option value="delete" >删除</option>
                        </select>
                        <input type="submit" value="操作">
                    </div>
                    <?php endif;?>
                    <table class="category-table-wrap">
                        <colgroup>
                            <col width="25">
                            <col width="300">
                            <col width="150">
                            <col width="70">
                        </colgroup>
                        <thead>
                            <tr>
                                <th class="radius-topleft-user"> 
                                    <input type="checkbox" id="checkedAll">
                                </th>
                                <th>名称</th>
                                <th>别名</th>
                                <th class="radius-topright-user">文章</th>
                            </tr>
                        </thead>
                        <!--分类list-->
                        <?php if( ! empty($category)):?>
                        <tbody>
                        <?php foreach($category->result_array() as $val):?>
                            <tr>
                                <td>
                                    <input type="checkbox" name="mid[]" value="<?php echo $val['mid'];?>">
                                </td>
                                <td>
                                    <a href="/admin/metas/manage/category/<?php echo $val['mid'];?>"><?php echo $val['name'];?></a>
                                </td>
                                <td><?php echo $val['slug'];?></td>
                                <td>
                                    <a class="balloon-button-user"><?php echo $val['count'];?></a>
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                        <?php endif;?>
                        </table><!--.category-table-wrap-->
<?php echo isset($pagination) ? $pagination : '';?>
                    </form>
                <?php endif;?>

                <!--标签list-->
                <?php if($type == 'tag'): ?>
                <?php echo form_open("admin/metas/operate/".$type);?>
                <?php if( ! empty($tag)):?>
                <div class="category-control"> 
                    <select name="do" >
                        <option value="no" selected="selected">批量操作</option>
                        <option value="delete" >删除</option>
                    </select>
                    <input type="submit" value="操作">
                </div>
                <?php endif;?>
                <table class="category-table-wrap">
                <colgroup>
                    <col width="25">
                    <col width="300">
                    <col width="150">
                    <col width="70">
                </colgroup>
                <thead>
                    <tr>
                        <th class="radius-topleft-user"> 
                            <input type="checkbox" id="checkedAll">
                        </th>
                        <th>名称</th>
                        <th>别名</th>
                        <th class="radius-topright-user">文章</th>
                    </tr>
                </thead>
                <?php if( ! empty($tag)):?>
                <tbody>
                <?php foreach($tag->result_array() as $val):?>
                    <tr>
                        <td>
                            <input type="checkbox" name="mid[]" value="<?php echo $val['mid'];?>">
                        </td>
                        <td>
                            <a href="/admin/metas/manage/tag/<?php echo $val['mid'];?>"><?php echo $val['name'];?></a>
                        </td>
                        <td><?php echo $val['slug'];?></td>
                        <td><?php echo $val['count'];?></td>
                    </tr>
                <?php endforeach;?>
                </tbody>
                <?php endif;?>
                </table>
<?php echo isset($pagination) ? $pagination : '';?>
                </form>
                <?php endif;?>
            </div><!--.category-right-->
            <script type="text/javascript">
            $("tbody > tr").click(function()
            {
                var ischeck = $(this).children('td').hasClass('selected');
                $(this).children('td')[ischeck?'removeClass':'addClass']('selected')
                    .find(':checkbox').attr('checked',!ischeck);
            });
            $("#checkedAll").click(function()
            {
                if(this.checked)
                {
                    $("tbody > tr").find(":checkbox").attr("checked",true)
                        .end().children('td').addClass('selected');
                }
                else
                {
                    $("tbody > tr").find(":checkbox").attr("checked",false)
                        .end().children('td').removeClass('selected');
                }
            });
            </script>
            </div><!--body end-->
        </div>
        </div>
<?php require 'footer.php';?>

