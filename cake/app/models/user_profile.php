<?php

class UserProfile extends AppModel
{

	/*************************************
	* fields:
	*	- id
	*	- user_id
	*	- political_view
	*	- religion
	*	- books
	*	- music
	*	- about_me
	*
	*************************************/

	var $name = 'UserProfile';

	var $belongsTo = array(
			'User' => array(
					'className' => 'User'
				)
			);
}

?>