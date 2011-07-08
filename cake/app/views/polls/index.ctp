<div>
	<?= $session->flash() ?>
	<table>
		<th>Title</th>
		<th>Author</th>
		<th>Posted</th>
		<th>Actions</th>
		
		<?php
			foreach($polls as $poll)
			{
		?>
		<tr>
			<td><? echo $html->link($poll['Poll']['title'], array('controller'=>'polls', 'action'=>'view', $poll['Poll']['id'])); ?></td>
			<td><?= $poll['User']['full_name'] ?></td>
			<td><?= date('m/d/y', strtotime($poll['Poll']['created'])) ?></td>
			<td>
				<? echo $html->link('Edit', array('controller'=>'polls', 'action'=>'edit', $poll['Poll']['id'])); ?> |
				<? echo $html->link('Delete', array('controller'=>'polls', 'action'=>'delete', $poll['Poll']['id'])); ?>
			</td>
		</tr>
		<?php
			}
		?>
	</table>
</div>
<br />
<div>
	<?= $html->link('New Poll', 'new_poll') ?>
</div>