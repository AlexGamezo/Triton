<div>
	<h1><?= $group['Group']['name'] ?></h1>
	<table>
		<th>Username</th>
		<th>Full Name</th>
		<th>Email</th>
		<th>Last Login</th>
	<?php
		foreach($members as $member)
		{
			echo "
		<tr>
			<td>".$html->link($member['User']['username'], array('controller'=>'users', 'action'=>'edit', $member['User']['id']))."</td>
			<td>{$member['User']['full_name']}</td>
			<td>{$member['User']['email']}</td>
			<td>".(empty($member['User']['lastLogin']) ? 'Never' : date('m/d/y H:i', strtotime($member['User']['lastLogin'])))."</td>
		</tr>";
		}
	?>
</div>