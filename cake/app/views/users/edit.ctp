<?php
    //let's load up the jQuery core
    echo $html->script('jquery');
?>

<div class="edit_user"> 
<h2>Edit User - <?= $this->data['User']['username'] ?></h2>     
    <?php echo $form->create('User', array('action' => 'edit'));?> 
        <?php echo $form->input('full_name');?> 
        <?php echo $form->input('email');?> 
        <?php 
		echo $form->input('new_pass', array('label'=>'Password', 'type'=>'password'));?> 
        <?php 
		echo $form->input('new_pass2', array('label'=>'Confirm', 'type'=>'password'));?> 
		<br />
        <?php echo $form->submit('Modify');?> 
    <?php echo $form->end(); ?> 
</div> 