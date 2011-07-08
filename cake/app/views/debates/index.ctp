<div class='content' style='width:700px; overflow:hidden; float:left;'>
	<?= $session->flash() ?>
	
	<div id='latest_debate' class='list_block'>
		<div class='block_heading'>Latest Debate</div>
		<div class='title'><?= $html->link($debates[0]['Debate']['title'], array('controller'=>'debates', 'action'=>'view', $debates[0]['Debate']['id'])); ?></div>
		<div class='author'>Posted by <?= $debates[0]['User']['full_name'] ?></div>
		<div class='summary'>
			<?= $debates[0]['Debate']['summary'] ?>
		</div>
	</div>
	
	<br />
	<br />
	
	<div id='debates_list' class='list_block'>
		<div class='block_heading'>Other Debates</div>
		<ul>
	<?php
		for($i = 1; $i < count($debates); $i++)
		{
			$debate = $debates[$i];
	?>
			<li><? echo $html->link($debate['Debate']['title'], array('controller'=>'debates', 'action'=>'view', $debate['Debate']['id'])); ?>
				by <span class='author'><?= $debate['User']['full_name'] ?></span>
				| <span class='date'><?= date('m/d/y', strtotime($debate['Debate']['created'])) ?></span>
				| <span><?= $debate['Debate']['argument_count'] ?> have voted</span>
			</li>
	<?php
		}
	?>
		</ul>
	</div>
</div>
<br clear='both' />
<br />
<div>
	<?= $html->link('Start a Debate', 'new_debate') ?>
</div>