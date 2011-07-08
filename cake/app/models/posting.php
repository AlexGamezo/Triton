<?php

class Posting extends AppModel
{
	var $name = 'Posting';
	
	var $belongsTo = array(
			'User' => array (
					'className' => 'User',
					'fields' => array('id', 'full_name', 'username', 'email', 'lastLogin')
				)
			);
			
	var $hasMany = array(
			'Vote' => array (
					'className' => 'Vote',
					'foreignKey'=> 'voteable_id',
					'dependent' => true,
					'conditions'=> array('Vote.type' => 'posting_vote'),
					'order'		=> 'Vote.created ASC'
				)
			);
			
	function save($data)
	{
		$data['Posting']['modified_date'] = date('Y-m-d H:i:s');
	
		parent::save($data);
	}
}

?>