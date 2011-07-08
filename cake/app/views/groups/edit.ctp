<?php
    //let's load up the jQuery core
    echo $html->script('jquery');
?>

<script type='text/javascript'>

function add_groups()
{
	var available_users = document.getElementById('GroupAvailableUsers');
	var available_user_options = available_users.options;
	var group_members = document.getElementById('UserUser');
	
	while(available_users.selectedIndex > -1)
	{
		group_members.options.add(available_user_options[available_users.selectedIndex]);
	}
}

function remove_groups()
{
	var group_members = document.getElementById('UserUser');
	var group_member_options = group_members.options;
	var available_users = document.getElementById('GroupAvailableUsers');
	
	while(group_members.selectedIndex > -1)
	{
		available_users.options.add(group_member_options[group_members.selectedIndex]);
	}
}

</script>

<div class="edit_group"> 
<h2>Edit Group</h2>     
    <?php echo $form->create('Group', array('action' => 'edit'));?> 
        <?php echo $form->input('name');?> 
        <?php echo $form->input('User');?>
		<div><?php echo $form->submit('Add',
								array('type'=>'button',
									  'onclick'=>'add_groups()'));
				   echo $form->submit('Remove', array('type'=>'button', 'onclick'=>'remove_groups()')); ?>
        <?php echo $form->input('available_users',
								array(
									'type'=>'select',
									'options' =>$available_users,
									'multiple'=>true,
									'label'=>'Available Users',
								)
							);?> 
        <?php echo $form->submit('Modify', array('onclick'=>'$("#UserUser option").attr("selected", "selected");'));?> 
    <?php echo $form->end(); ?> 
</div> 