<?php

class Poll extends AppModel
{
	var $name = 'Poll';
	
	var $belongsTo = array(
			'User' => array (
					'className' => 'User',
					'fields' => array('id', 'full_name', 'username', 'email', 'lastLogin')
				)
			);
	
	var $hasMany = array(
			'PollOption' => array (
					'className' => 'PollOption',
					'dependent' => true,
					),
			'Vote' => array (
					'className' => 'Vote',
					'foreignKey'=> 'voteable_id',
					'dependent' => true,
					'conditions'=> array('Vote.type' => 'poll_vote'),
					'order'		=> 'Vote.created ASC'
				),
			);
	
	var $hasAndBelongsToMany = array(
			'Category' => array(
					'className' => 'Category',
				),
			);

			
	function get_latest_polls($limit = null)
	{
		if(empty($limit))
			$limit = 10;
	
		$polls = $this->find('all', array('conditions'=>array(), 'order' => 'Poll.modified DESC', 'limit' => $limit));
		
		return $polls;
	}
}

?>