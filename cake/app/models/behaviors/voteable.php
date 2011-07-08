<?php


class VoteBehavior extend ModelBehavior
{

	var $__settings = array();
	
	function setup(&$Model, $settings = array())
	{
		$default = array('incrementFieldName' => 'vote_count');
		
		if(!isset($this->__settings[$Model->alias]))
			$this->__settings[$Model->alias] = $default;
		else
		{
			$this->__settings[$Model->alias] = array_merge($this->__settings[$Model->alias], (is_array($settings) ? $settings : array()));
		}
	}
	
	function beforeFind(&$model, $query) {} 

    function afterFind(&$model, $results, $primary)  {} 
     
    function beforeSave(&$model)  {} 

    function afterSave(&$model, $created) {} 

    function beforeDelete(&$model)  {} 

    function afterDelete(&$model)  {} 

    function onError(&$model, $error)  {}
	
	function vote(&$model, $id, $incrementValue = 1, $field = null)
	{
	
		$answer = false;
		
		if(empty($field))
			$field = $this->__settings[$model->alias]['incrementFieldName'];
			
		$recursiveLevel = $model->recursive;
		$data = $model->data;
		
		$model->recursive = -1;
		$model->data = $model->findById(($id), array('id', $field));
		
		if(!empty($model->data))
		{
			$counter = (int)$model->data[$model->alias][$field] + (int)$incrementValue;
			
			$conditions = array($model->alias.'.id' => $id);
			
			$fields = array($field => $counter);
			
			$answer = $model->updateAll($fields, $conditions);
		}
		
		$model->data = $data;
		$model->recursive = $recursiveLevel;
		
		return $answer;
	}

}