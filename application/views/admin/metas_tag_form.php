<?php echo form_open('admin/metas/manage/tag'.((isset($mid) && is_numeric($mid))?'/'.$mid:''));?>
	<ul class="category-add-ul">
		<li class="category-add-li">
			<?php echo form_error('name','<p class="category-error">','</p>');?>
				<label>标签名称</label>
				<input name="name" value="<?php echo set_value('name', (isset($name) ? $name : '') );?>">
				<p>这是标签在站点中显示的名称.可以使用中文,如"地球".</p>
		</li>
	</ul>
	
	<ul class="category-add-ul">
		<li class="category-add-li">
			<?php echo form_error('slug','<p class="category-error">','</p>');?>
				<label >标签缩略名</label>
				<input name="slug" value="<?php echo set_value('slug', (isset($slug) ? $slug : '') );?>">
				<p >标签缩略名用于创建友好的链接形式,如果留空则默认使用标签名称.,建议使用字母,数字,下划线和横杠.</p>
		</li>
	</ul>
	

	<input name="do" type="hidden" value="<?php echo (isset($mid) && is_numeric($mid))?'update':'insert'?>"/>
	<p>
				<input type="submit" value="添加新标签">
	</p>					
</form>
