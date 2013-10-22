<?php echo form_open('admin/metas/manage/category'.((isset($mid) && is_numeric($mid))?'/'.$mid:''));?>
	<ul class="category-add-ul">
		<li class="category-add-li">
			<?php echo form_error('name','<p class="category-error">','</p>');?>
				<label>分类名称</label>
				<input name="name" value="<?php echo set_value('name', (isset($name) ? $name : '') );?>">
				<p>这里是文章分类的名称</p>
		</li>
	</ul>
	
	<ul class="category-add-ul">
		<li class="category-add-li">
			<?php echo form_error('slug','<p class="category-error">','</p>');?>
				<label >别名</label>
				<input name="slug" value="<?php echo set_value('slug', (isset($slug) ? $slug : '') );?>">
				<p >分类缩略名用于创建友好的链接形式,建议使用字母,数字,下划线和横杠.</p>
		</li>
	</ul>
	
	<ul class="category-add-ul">
		<li class="category-add-li">
			<?php echo form_error('intro','<p class="category-error">','</p>');?>
				<label >分类描述</label>
				<textarea name="intro" ><?php echo set_value('intro', (isset($intro) ? $intro : '') );?></textarea>
				<p>描述只会在一部分主题中显示。</p>
		</li>	
	</ul>
	<input name="do" type="hidden" value="<?php echo (isset($mid) && is_numeric($mid))?'update':'insert'?>"/>
	<p>
				<input type="submit" value="添加分类">
	</p>					
</form>
