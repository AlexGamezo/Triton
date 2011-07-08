<div class="new_user"> 
<h2>Create User</h2>
	<div><?= $session->flash() ?></div>
    <?php echo $form->create('User', array('action' => 'register'));?> 
        <?php echo $form->input('username');?> 
        <?php echo $form->input('full_name');?> 
        <?php echo $form->input('email');?> 
        <?php echo $form->input('password', array('type'=>'password'));?> 
        <?php echo $form->input('password2', array('type'=>'password', 'label'=>'Confirm'));?>
        <?php echo $form->submit('Create');?> 
    <?php echo $form->end(); ?> 
</div> 