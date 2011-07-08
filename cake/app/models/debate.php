<?php

class Debate extends AppModel
{

	/****************
	* Fields
	* - id
	* - user_id
	* - title
	* - summary
	* - content
	* - argument_count
	* - comment_count
	* - vote_count
	* - created
	* - updated
	*****************/
	
	var $name = 'Debate';
	
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
					'conditions'=> array('Vote.type' => 'debate_vote'),
					'order'		=> 'Vote.created ASC'
				),
			'Argument' => array (
					'className' => 'Argument',
					'foreignKey' => 'debate_id',
					'dependent' => true,
					'order'		=> 'Argument.created ASC'
				),
			);
			
	var $hasAndBelongsToMany = array(
			'Category' => array(
					'className' => 'Category',
				),
			);

	var $validate = array(
				);
	
	
	function afterFind($results)
	{
		foreach($results as $key => $val)
		{
			if(isset($val['Argument']))
				$results[$key]['argument_count'] = count($val['Argument']);
		}
		
		return $results;
	}
	
	function get_latest_debates($limit = null)
	{
		if(empty($limit))
			$limit = 10;
	
		$debates = $this->find('all', array('conditions'=>array(), 'order' => 'Debate.modified DESC', 'limit' => $limit));
		
		return $debates;
	}
}

?>