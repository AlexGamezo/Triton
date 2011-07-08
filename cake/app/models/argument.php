<?php

class Argument extends AppModel
{

	/****************
	* Fields
	* - id
	* - user_id
	* - argument
	* - vote_count
	* - created
	* - updated
	*****************/
	
	var $name = 'Argument';
	
	var $belongsTo = array(
			'User' => array (
					'className' => 'User',
					'fields' => array('id', 'full_name', 'username', 'email', 'lastLogin')
				),
			'Debate' => array (
					'className' => 'Debate',
					'foreignKey' => 'debate_id',
//					'counterCache' => true
				),
			);
	
//	var $useTable = 'postings';
//	var $actsAs = array(
//			'Subclass' => array(
//				'parentClass' => 'Posting',
//				'typeAlias'	  => '2',
//			)
//		);
	
	var $hasMany = array(
			'Vote' => array (
					'className' => 'Vote',
					'foreignKey'=> 'voteable_id',
					'conditions'=> array('Vote.type' => 'argument_vote'),
					'dependent' => true,
					'order'		=> 'Vote.created ASC'
				)
			);

	var $validate = array(
				);
	
}

?>