<?php
    //let's load up the jQuery core
    echo $html->script('jquery');
?>

<script type='text/javascript'>

var avail_users = Array();

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

function remove_member()
{
	var group_members = document.getElementById('UserUser');
	var group_member_options = group_members.options;
	
	while(group_members.selectedIndex > -1)
	{
		group_member_options.remove(group_members.selectedIndex);
	}
}

function add_member(value, text)
{
	var group_members = $('#UserUser');
	
	var match_option = $('#UserUser').children("[value='"+value+"']").text();
	
	if(match_option != text)
		group_members.append("<option value='"+value+"'>"+avail_users[value]+"</option>");
		
	clear_avail_users();
}

function set_available_users(response)
{
	avail_users = jQuery.parseJSON(response);
	var available_users_list = $('#available_users_list');
	
	available_users_list.empty();
	
	for(var i in avail_users)
	{
		available_users_list.append("<li class='unselected' style='border:solid thin black;background-color:lightyellow;'>"+avail_users[i]+"</li>");
	}	
	
	available_users_list.find('li').each(function(i, user) {
				$(user).hover(
					function() {$(this).css('background-color', 'lightblue');},
					function() {$(this).css('background-color', 'lightyellow');}
					);
				$(user).click(
					function() {
						
						for(var i in avail_users)
						{
//							alert($(user).text());
							if(avail_users[i] == $(user).text())
							{
								add_member(i, avail_users[i]);
							}
						}
					}
				);
		});
}

function clear_avail_users()
{
	$('#GroupUserName').val('');
	$('#available_users_list').empty();
	
	avail_users = Array();
}

</script>

<div class="new_group"> 
<h2>New Group</h2>     
    <?php echo $form->create('Group', array('action' => 'new_group'));?> 
        <?php echo $form->input('name');?> 
        <?php echo $form->input('User');?>
		<div><?php echo $form->submit('Remove', array('type'=>'button', 'onclick'=>'remove_member()')); ?></div>
        <?php echo $form->input('user_name',
								array(
									'label'=>'Available Users',
								)
							);?> 
		<div style='font-size:15pt; positiong:relative; height:1px;'>
			<ul id='available_users_list' style='list-style:none; width:10em; position:relative; top: -30px; left:-25px;'>
			</ul>
		</div>
        <?php echo $form->submit('Create Group', array('onclick'=>'$("#UserUser option").attr("selected", "selected");'));?> 
	<?php 
		$js->get('#GroupUserName');
		echo $html->scriptBlock(
			$js->event('keyup',
				'if($("#GroupUserName").val().length > 0) {'.
					$js->request(array('controller'=>'users', 'action'=>'all', 'json'),
					 array('success'=>'set_available_users(data)', 'json', 'data'=>'"partial_user="+$("#GroupUserName").val()', 'dataExpression'=>true)
					).'
				}
				else 
					clear_avail_users();
				', false));
		?>
    <?php echo $form->end(); ?> 
</div>

