<?php require('header.php')?>
<div class="login">
	<h1>
		<a title="基于Codeignter的blog系统" href="<?echo site_url();?>">ProNoz</a>
	</h1>
	<?php if(!empty($error)):?>
	<div class="attention">
		<ul>
			<?php echo '<li>'.$error.'</li>';?>
	   </ul>
	</div>
	<?php endif;?>
	<?php echo form_open('admin/login?ref='.urlencode($this->referrer));?>
		<fieldset>			
			<div class="item">
				<label><?php echo lang('login_name')?></label>
				<input type="text" name="username">
			</div>
			<div class="item">
				<label>密码</label>
				<input type="password" name="password">
			</div>
			<div class="item2">
				<label>
					<input type="checkbox" name="remember" value="true">记住我
				</label>
			</div>
			<div class="item2">
				<input type="submit" class="pn-submit" value="登 录">
			</div>
			<input type="hidden" value="submitted" name="submitted"/>
		</fieldset>
	</form>
	<div class="return_dash">
		<p>
			<a href="<?php echo site_url();?>">&laquo;返回站点</a>
		</p>
	</div>
</div>
<script type="text/javascript">
$(function()
{
   $("input[name='username']").focus();
});
</script>
<?php require('footer.php')?>
