<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $title_for_layout?></title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="/cake/css/cake.generic.css" /></head> 
		<link rel="stylesheet" type="text/css" href="/cake/css/general.css" /></head> 
		<!-- Include external files and scripts here (See HTML helper for more info.) -->
		<?php echo $scripts_for_layout ?>
	</head>
	<body>
		<div id="container"> 
			<div id="header">
				<div id='menu' style="float:right;"><?php 
				if(!empty($current_user))
				{
					echo $current_user['User']['username'].' | ';
					echo $html->link('My Profile', array('controller'=>'users', 'action'=>'profile', $current_user['User']['id']));
					echo ' | ';
					echo $html->link('Logout', array('controller' => 'Users', 'action' => 'logout'));
				}
				else
				{
					echo $html->link('Register', array('controller'=>'users', 'action'=>'register'));
					echo ' | ';
					echo $html->link('Login', array('controller' => 'Users', 'action' => 'login'));
				
				}
				?></div>
				<h1><a href="http://cakephp.org">Debate This - </a></h1>
				<br />
				<br />
				<div id="menu">
					<?= $html->link('Home', '/pages/home') ?> |
					<?= $html->link('Postings', array('controller'=>'postings', 'action'=>'index')) ?> |
					<?= $html->link('Polls', array('controller'=>'polls', 'action'=>'index')) ?> |
					<?= $html->link('Debates', array('controller'=>'debates', 'action'=>'index')) ?> |
					<?= $html->link('Groups', array('controller'=>'groups', 'action'=>'index')) ?> |
					<?= $html->link('Users', array('controller'=>'users', 'action'=>'index')) ?>
				</div>
			</div>
			<div id='content' style='width:700px; overflow:hidden; float:left; margin-left:20px;'>
				<?= $content_for_layout ?>
			</div>
			<div class='sidebar' style='padding-left:50px; width:200px; overflow:hidden; float:left;'>
				<?= $this->element('item_display_small', array('items' => $latest_debates, 'item_type'=>'Debate')); ?>
				<?= $this->element('item_display_small', array('items' => $latest_polls, 'item_type'=>'Poll')); ?>
			</div>
		</div>
		<?php if( Configure::read('debug')) echo $this->element('sql_dump'); ?>
	</body>

</html>