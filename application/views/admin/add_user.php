<?php require 'header.php'?>
<?php require 'top.php'?>
<?php require 'menu.php'?>
<div class="content">
		<div class="body-wrap">
			<div class="body">
				<div class="icon icon-users"></div>
				<h2>添加新用户</h2>
				<div class="wrap-adduser">
				<?php echo (empty($success))?'':'<p class="message-adduser">'.$success.'</p>'; ?>
				<form action="" method="post" enctype="application/x-www-form-urlencoded">
					<ul>
						<li>
							<label class="name-adduser">用户名*</label>
							<input class="text-adduser" type="text" name="username" <?php if(isset($uid) && is_numeric($uid)){echo 'readonly';}?> value="<?php echo set_value('username',(isset($username))?$username:''); ?>" >
							<p class="description">
							 此用户名将作为用户登录时所用的名称.
							 请不要与系统中现有的用户名重复.
							</p>
							<?php echo form_error('username', '<p class="message-adduser">', '</p>'); ?>
						</li>
					</ul>
					<ul>
						<li>
							<label class="name-adduser">电子邮件*</label>
							<input class="text-adduser" type="text" name="mail" value="<?php echo set_value('mail',(isset($mail))?$mail:''); ?>" >
							<p class="description">
							 电子邮箱地址将作为此用户的主要联系方式.
							 请不要与系统中现有的电子邮箱地址重复.
							</p>
							<?php echo form_error('mail', '<p class="message-adduser">', '</p>'); ?>
						</li>
					</ul>
					<ul>
						<li>
							<label class="name-adduser">用户昵称</label>
							<input class="text-adduser" type="text" name="screenName" value="<?php echo set_value('screenName',(isset($screenName))?$screenName:''); ?>">
							<p class="description">
							 用户昵称可以与用户名不同, 用于前台显示.
							 如果你将此项留空,将默认使用用户名.
							</p>
							<?php echo form_error('screenName', '<p class="message-adduser">', '</p>'); ?>
						</li>
					</ul>
					<ul>
						<li>
							<label class="name-adduser">用户密码*</label>
							<input class="text-adduser" type="password" name="password" value="">
							<p class="description">
							 为此用户分配一个密码.
							 建议使用特殊字符与字母的混编样式,以增加系统安全性.
							</p>
							<?php echo form_error('password', '<p class="message-adduser">', '</p>'); ?>
						</li>
					</ul>
					<ul>
						<li>
							<label class="name-adduser">用户密码确认*</label>
							<input class="text-adduser" type="password" name="confirm" value="" >
							<p class="description">
							 请确认你的密码, 与上面输入的密码保持一致.
							</p>
							<?php echo form_error('confirm', '<p class="message-adduser">', '</p>'); ?>
						</li>
					</ul>
					<ul>
						<li>
							<label class="name-adduser">个人主页地址</label>
							<input class="text-adduser" type="text" name="url" value="<?php echo set_value('url',(isset($url))?$url:''); ?>" >
						</li>
					</ul>
					<ul>
						<li>
							<label class="name-adduser">用户组</label>
							<select id="group" name="group">
								<option  value="contributor" <?php echo set_select('group', 'contributor', ('contributor' != $group)?FALSE:TRUE); ?>>贡献者</option>
								<option value="editor" <?php echo set_select('group', 'editor', ('contributor' != $group)?FALSE:TRUE); ?>>编辑</option>
								<option value="administrator" <?php echo set_select('group', 'administrator', ('contributor' != $group)?FALSE:TRUE); ?>>管理员</option>
							</select>
							</select>
						</li>
					</ul>
					<ul class="submit-adduser">
						<li>
							<input type="submit" value="添加用户" >
						</li>
					</ul>
				</form>
				</div><!--wrap-adduser end-->
			</div><!--body end-->
		</div>
	</div>
<?php require 'footer.php'?>