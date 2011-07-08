<div>
	<div id='poll-container'>
		<div class='title'><?= $poll['Poll']['title'] ?></div>
		<div class='author'>
			Posted by <?= $poll['User']['full_name'] ?> | 
			<?= ($poll['Poll']['created'] != $poll['Poll']['modified'] ?
						"updated on ".date('m/d/y H:i', strtotime($poll['Poll']['modified'])) :
						"posted on ".date('m/d/y H:i', strtotime($poll['Poll']['created'])))
			?>
			| <?= $poll['Poll']['vote_count']; ?> people like this
		</div>
		
		<div class='category_list'><b>Categories:</b>
			<?php
			foreach($categories as $index => $category)
			{ 
				if($index > 0) echo ', ';
				echo $html->link($category['Category']['category'], array('controller'=>'categories', 'action'=>'view', $category['Category']['id']));
			}
			?>
		</div>
		<br />
		
		<h4>
			<?= (!$end_date_passed ? (!empty($poll['Poll']['end_time']) ? "Ending on ".$poll['Poll']['end_time'] : "Never ending") : "This poll is closed.") ?>
		</h4>
		
		<div class='content'>
			<?= $poll['Poll']['content'] ?>
			<br />
			<br />
		<?= $session->flash() ?>
		<?php echo $form->create('Poll', array('controller'=>'polls', 'action' => 'vote'));?> 
			<?= $form->radio('PollOption.option', $poll_options, array('legend'=>'Your choice')) ?>
			<?= $form->hidden('Poll.id') ?>
			<?php echo $form->submit('Vote');?> 
		<?php echo $form->end(); ?> 
		</div>
	</div>
</div>
