<?php require 'header.php'?>
<?php require 'top.php'?>
<?php require 'menu.php'?>
	<div class="content">
		<div class="body-wrap">
			<div class="body">
				<div class="icon icon-users"></div>
				<h2>用户</h2>
				<div class="column-user">
					<form method="get">
						<p class="operate">
							<a class="button-user" href="<?php echo site_url('admin/users/user')?>">添加用户</a>
							操作:
							<span class="">全选</span>
							,
							<span class="">不选</span>
							,    选中项:
							<span class="" lang="你确认要删除这些用户吗?" rel="delete">删除</span>
						</p>
					</form>
				</div><!--column-user end-->
				<form class="form-user">
					<table class="table-user">
						<colgroup>
							<col width="25">
							<col width="150">
							<col width="150">
							<col width="30">
							<col width="300">
							<col width="165">
							<col width="70">
						</colgroup>
						<thead>
							<tr>
								<th class="radius-topleft-user"> </th>
								<th>用户名</th>
								<th>昵称</th>
								<th> </th>
								<th>电子邮件</th>
								<th>用户组</th>
								<th class="radius-topright-user">文章</th>
							</tr>
						</thead>
						<tbody>
							<tr id="user-1" class="even">
								<td>
								<input type="checkbox" name="uid[]" value="1">
								</td>
								<td>
								<a href="#">admin</a>
								</td>
							<td>王维</td>
								<td>
								
								</td>
								<td>
								<a href="mailto:nozwang@gmail.com">nozwang@gmail.com</a>
								</td>
							<td>管理员</td>
								<td>
								<a class="balloon-button-user" href="#">2</a>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div><!--body end-->
		</div>
	</div>
</body>
</html>


