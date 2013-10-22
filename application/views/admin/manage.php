<?php require 'header.php'?>
<?php require 'top.php'?>
<?php require 'menu.php'?>
	<div class="content">
		<div class="body-wrap">
			<div class="body">
            <!--提交成功失败提示-->
            <?php require 'notify.php';?>
				<div class="icon icon-users"></div>
				<h2>文章管理
				    <a class="manage-add" href="<?php echo site_url('admin/post')?>">写文章</a>
                </h2>
	
                <?php echo form_open("admin/post/operate/");?>
                <div class="manage-select">
                    <a href="/admin/post/manage" <?php echo ($status=='public')?"class=current":''?>>已发布<span class="count">(<?php echo $public_count;?>)</span></a> | 
                    <a href="/admin/post/manage/draft" <?php echo ($status=='draft')?"class=current":''?>>草稿箱<span class="count">(<?php echo $draft_count;?>)</span></a>
                </div>
                <div class="manage-control"> 
                    <select name="do" >
                        <option value="no" selected="selected">批量操作</option>
                        <?php if($status != 'draft'):?>
                        <option value="draft" >移至草稿箱</option>
                        <?php endif;?>
                        <?php if($status == 'draft'):?>
                        <option value="trash" >移至回收站</option>
                        <?php endif;?>
                    </select>
                    <input type="submit" value="操作">
                </div>
					<table class="table-user">
						<colgroup>
							<col width="25">
							<col width="250">
							<col width="100">
							<col width="100">
							<col width="180">
							<col width="70">
							<col width="150">
						</colgroup>
						<thead>
							<tr>
								<th class="radius-topleft-user"> 
                                <input id="checkAll" type="checkbox">
                                </th>
								<th>标题</th>
								<th>分类</th>
								<th>作者</th>
								<th>标签</th>
								<th class="radius-topright-user">评论</th>
								<th>日期</th>
							</tr>
						</thead>
						<tbody>
                            <?php foreach($post_list->result() as $post):?>
							<tr>
								<td>
								<input type="checkbox" name="pid[]" value="<?php echo $post->pid;?>">
								</td>
							<td><a href="<?php echo site_url('/admin/post/write/'.$post->pid);?>"><?php echo $post->title;?></a></td>
						    <td>
                            	<?php 
                            	$length = count($post->categories);
                            	foreach($post->categories as $key => $val):?>
								<?php
									echo '<a href="'; 
									echo site_url("admin/posts/manage/$status?category=".$val['mid']);
									echo '">' . $val['name'] . '</a>' . ($key < $length - 1 ? ', ' : ''); ?>
								<?php endforeach;?>
                            </td>
								<td>
								<a href="#">admin</a>
								</td>
								<td>
                            	<?php 
                            	$length = count($post->tags);
                            	foreach($post->tags as $key => $val):?>
								<?php
									echo '<a href="'; 
									echo site_url("admin/posts/manage/$status?category=".$val['mid']);
									echo '">' . $val['name'] . '</a>' . ($key < $length - 1 ? ', ' : ''); ?>
								<?php endforeach;?>
								</td>
								<td>
								<a class="balloon-button-user" href="#">2</a>
								</td>
							<td><?php echo $this->common->dateWord($post->created,time());?></td>
							</tr>
                            <?php endforeach;?>
						</tbody>
					</table>
				</form>
                    <?php echo isset($pagination) ? $pagination : '';?>
			</div><!--body end-->
		</div>
	</div>
    <script type="text/javascript">
    $("tbody > tr").click(function()
    {
        var ischeck = $(this).children('td').hasClass('selected');
        $(this).children('td')[ischeck?'removeClass':'addClass']('selected')
            .find(':checkbox').attr('checked',!ischeck);
    });      
    
    $("#checkAll").click(function(){
        if(this.checked)
        {
            $('tbody > tr').find(':checkbox').attr("checked",true)
                .end().children('td').addClass('selected');
        }
        else
        {
            $('tbody > tr').find(':checkbox').attr("checked",false)
                .end().children('td').removeClass('selected');

        }
    });
    </script>
<?php require 'footer.php';?>
