<?php

class Group extends AppModel
{
	var $name = 'Group';
	
	var $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
//			'unique' => false,
		)
	);
	
	function afterFind($results)
	{
		foreach($results as $key => $val)
		{
			if(isset($val['User']))
				$results[$key]['Group']['member_count'] = count($val['User']);
		}
		
		return $results;
	}
}

?>