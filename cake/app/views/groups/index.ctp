<div>
	<?= $session->flash() ?>
	<table>
		<th>Name</th>
		<th># Members</th>
		<th>Actions</th>
		
		<?php
			foreach($groups as $group)
			{
		?>
		<tr>
			<td><? echo $html->link($group['Group']['name'], array('controller'=>'groups', 'action'=>'members', $group['Group']['id'])); ?></td>
			<td><?= $group['Group']['member_count'] ?></td>
			<td>
				<? echo $html->link('Edit', array('controller'=>'groups', 'action'=>'edit', $group['Group']['id'])); ?> |
				<? echo $html->link('Delete', array('controller'=>'groups', 'action'=>'delete', $group['Group']['id'])); ?>
			</td>
		</tr>
		<?php
			}
		?>
	</table>
</div>
<br />
<div>
	<?= $html->link('New Group', 'new_group') ?>
</div>