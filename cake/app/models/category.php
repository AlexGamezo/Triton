<?php

class Category extends AppModel
{
	var $name = "Category";
	
	var $hasAndBelongsToMany = array(
			'Debate' => array(
					'className' => 'Debate',
				),
			);
			
	var $actsAs = array('Tree');
}