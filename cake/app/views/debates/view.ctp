<div>
	<div>
		<div class='title'><?= $debate['Debate']['title'] ?></div>
		<?= $session->flash() ?>
		
		<div class='author' id='author_vote_line'>
			Posted by <?= ($is_author ? 'You' : $html->link($author['User']['full_name'], array('controller'=>'users', 'action'=>'profile', $author['User']['id']))) ?> |
			<?= ($debate['Debate']['created'] != $debate['Debate']['modified'] ?
						"updated on ".date('m/d/y H:i', strtotime($debate['Debate']['modified'])) :
						"posted on ".date('m/d/y H:i', strtotime($debate['Debate']['created'])))
			?>
			| <?= $debate['Debate']['vote_count']; ?> people like this 
			(<?= $html->link('Vote Up', array('controller'=>'debates', 'action'=>'vote', $debate['Debate']['id'])) ?>)
		</div>
		<?php if($show_edit_link || !empty($show_delete_link)) { ?>
		<div class='item_action_links'>
			<?php if($show_edit_link) { echo $html->link('Edit this debate', array('controller'=>'debates', 'action'=>'edit', $debate['Debate']['id'])); }?>
			<?php
			if(!empty($show_admin_links)) {
				echo " | " . $html->link('Delete this debate', array('controller'=>'debates', 'action'=>'delete', $debate['Debate']['id']));
				echo " | " . $html->link((empty($debate['Debate']['locked']) ? 'Unlock' : 'Lock') . ' this debate', array('controller'=>'debates', 'action'=>'lock', $debate['Debate']['id'], empty($debate['Debate']['locked'])));
			}?>
		</div>
		<?php } ?>
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
			<?= (!$end_date_passed ? (!empty($debate['Debate']['end_time']) ? "Ending on ".$debate['Debate']['end_time'] : "Never ending") : "This debate is closed. You can still vote on the argument you agree with.") ?>
		</h4>

		<br />
		<div class='content'><?= $debate['Debate']['content'] ?></div>
		<br />
		<div id='arguments'>
		<? if($end_date_passed) {
			?><h3>Winning Argument</h3>
			<div id='winning-argument' style='border:solid thin black; background-color:lightpink; width:400px; overflow:hidden;'>
				<?= $winning_argument['Argument']['argument']?>
			<br /><br />
			</div>
			Posted by <?= $winning_argument['User']['full_name'] ?> - 
				
				<?= $winning_argument['Argument']['vote_count']?> people agree
		<?php
			}
			else
			{
			?>
			<h3>All Arguments</h3>
		<?php
				$i = 0;
				foreach($arguments as $argument)
				{
					$i++;
		?>
			<div id='argument-$i' style='border:solid thin black; background-color:lightyellow; width:400px; overflow:hidden;'>
				<?= $argument['Argument']['argument']?>
			<br /><br />
			</div>
			Posted by <?= $argument['User']['full_name'] ?> - 
				
				<?=  $html->link('Agree', array('controller'=>'arguments', 'action'=>'vote', $argument['Argument']['id'])) ?> -
				<?= $argument['Argument']['vote_count']?> people agree
			<?php
				}
			?>
			<br /><br />
			<?
				if(!$end_date_passed)
				{
					echo $html->link('Add an Argument', array('controller'=>'arguments', 'action'=>'new_argument', 'debate', $debate['Debate']['id']));
				}
		}
			?>
		</div>
	</div>
</div>