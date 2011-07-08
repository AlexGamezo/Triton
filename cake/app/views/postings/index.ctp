<div>
	<?= $session->flash() ?>
	<table>
		<th>Title</th>
		<th>Author</th>
		<th>Posted</th>
		<th>Summary</th>
		<th>Actions</th>
		
		<?php
			foreach($postings as $posting)
			{
		?>
		<tr>
			<td><? echo $html->link($posting['Posting']['title'], array('controller'=>'postings', 'action'=>'read', $posting['Posting']['id'])); ?></td>
			<td><?= $posting['User']['full_name'] ?></td>
			<td><?= date('m/d/y', strtotime($posting['Posting']['created'])) ?></td>
			<td><?= $posting['Posting']['summary'] ?></td>
			<td>
				<? echo $html->link('Edit', array('controller'=>'postings', 'action'=>'edit', $posting['Posting']['id'])); ?> |
				<? echo $html->link('Delete', array('controller'=>'postings', 'action'=>'delete', $posting['Posting']['id'])); ?>
			</td>
		</tr>
		<?php
			}
		?>
	</table>
</div>
<br />
<div>
	<?= $html->link('New Posting', 'new_posting') ?>
</div>