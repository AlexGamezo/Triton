<?php

class UserPermission extends AppModel
{
	var $name = 'UserPermission';
	
	var $belongsTo = array(
		'User' => array(
			'className' => 'User'
		)
	);
}

?>