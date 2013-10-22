	<div class="menu">
		<ul>
			<li>
				<a class="menu-parent" href="<?php echo site_url('admin');?>">
					<div class="menu-parent-image bashboard-image"></div>
					<div class="menu-name">仪表盘</div>
				</a>
				<ul class="menu-son" >
					<li><a href="<?php echo site_url('admin');?>">首页</a></li>
				</ul>
			</li>
			<li>
				<a class="menu-parent" href="#">
					<div class="menu-parent-image bashboard-image"></div>
					<div class="menu-name">文章</div>
				</a>
				<ul class="menu-son" >
					<li><a href="<?php echo site_url('admin/post/manage');?>" <?php echo (isset($focus) && $focus=='manage') ? "id='focus' ": ''?>>所有文章</a></li>
					<li><a href="<?php echo site_url('admin/post');?>" <?php echo (isset($focus) && $focus=='write') ? "id='focus' ": ''?>>写文章</a></li>
					<li><a href="<?php echo site_url('admin/metas/manage');?>" <?php echo (isset($focus) && $focus=='category') ? "id='focus' ": ''?>>分类目录</a></li>
					<li><a href="<?php echo site_url('admin/metas/manage/tag');?>" <?php echo (isset($focus) && $focus=='tag') ? "id='focus' ": ''?>>标签</a></li>
				</ul>
			</li>
			<li>
				<a class="menu-parent" href="<?php echo site_url('admin/users/manage');?>">
					<div class="menu-parent-image bashboard-image"></div>
					<div class="menu-name">用户</div>
				</a>
				<ul class="menu-son" >
					<li><a href="<?php echo site_url('admin/users/manage');?>" <?php echo (isset($focus) && $focus=='user_manage') ? "id='focus' ": ''?>>所有用户</a></li>
					<li><a href="<?php echo site_url('admin/users/user');?>"<?php echo (isset($focus) && $focus=='add_user') ? "id='focus' ": ''?>>添加用户</a></li>
					<li><a href="#">个人设置</a></li>
				</ul>
			</li>
		</ul>
	</div>
