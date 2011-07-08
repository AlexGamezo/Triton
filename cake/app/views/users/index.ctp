<div>
	<table>
		<th>Username</th>
		<th>Full Name</th>
		<th>Email</th>
		<th>Last Login</th>
	<?php
		foreach($users as $user)
		{
			echo "
		<tr>
			<td>".$html->link($user['User']['username'], array('controller'=>'users', 'action'=>'edit', $user['User']['id']))."</td>
			<td>{$user['User']['full_name']}</td>
			<td>{$user['User']['email']}</td>
			<td>".(empty($user['User']['lastLogin']) ? 'Never' : date('m/d/y H:i', strtotime($user['User']['lastLogin'])))."</td>
		</tr>";
		}
	?>
	</table>
</div>
<br />
<div>
	<?php 
		if(!empty($allowed_user_create))
			echo $html->link('New User', 'new_user');
	?>
</div>