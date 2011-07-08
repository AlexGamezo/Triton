<?php

class PollOption extends AppModel
{
	var $name = 'PollOption';
	
	var $belongsTo = array(
			'Poll' => array (
					'className' => 'Poll',
				)
			);
	
	var $hasMany = array(
			'Vote' => array(
					'className'		=> 'Vote',
					'foreignKey'	=> 'voteable_id',
					'dependent' => true,
					'conditions'	=> array('Vote.type' => 'poll_option_vote'),
					'order' 		=> 'Vote.created ASC'
				)
			);
}

?>