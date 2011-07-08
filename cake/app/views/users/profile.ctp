<div>
	<?= $session->flash(); ?>
	<h1><?= $user['User']['full_name'] ?></h1>
	<br />
	<?= $html->image('placeholder.png', array('height'=>196, 'align'=>'left')); ?>
	<div><div class='label' style='float:left; display:block; width:150px; font-weight:bold; overflow:hidden; padding-right:10px; text-align:right'>Email:</div><?= $user['User']['email'] ?></div>
	<div><span class='label' style='float:left; display:block; width:150px; font-weight:bold; overflow:hidden; padding-right:10px; text-align:right'>Username:</span><?= $user['User']['username'] ?></div>
	<div>
		<span class='label' style='float:left; display:block; width:150px; font-weight:bold; overflow:hidden; padding-right:10px; text-align:right'>
			About Me:
		</span>
		<?= $user['UserProfile']['about_me'] ?>
	</div>
	<div>
		<span class='label' style='float:left; display:block; width:150px; font-weight:bold; overflow:hidden; padding-right:10px; text-align:right'>
			Political View:
		</span>
		<?= $user['UserProfile']['political_view'] ?>
	</div>
	<div>
		<span class='label' style='float:left; display:block; width:150px; font-weight:bold; overflow:hidden; padding-right:10px; text-align:right'>
			Religion:
		</span>
		<?= $user['UserProfile']['religion'] ?>
	</div>
	<br clear=both />
	
	<?= $this->element('my_activities', array('my_activities' => $my_activities)); ?>
	<?= $this->element('my_votes', array('my_votes' => $my_votes)); ?>
	
</div>