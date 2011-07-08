<?php

class ArgumentsController extends AppController
{
	var $name = 'Arguments';
	
	var $helpers = array('Html');
	
	function vote($id)
	{
		$argument = $this->Argument->findById($id);
		
		$current_user = $this->Auth->user('id');
				
		$this->Argument->id = $argument['Argument']['id'];
		
		$this->Argument->Vote->create();
		$this->data['Vote']['voteable_id'] = $this->Argument->id;
		$this->data['Vote']['user_id'] = $current_user['id'];
		$this->data['Vote']['type'] = 'argument_vote';
		
		$this->Argument->Vote->set($this->data);
		if($this->Argument->Vote->validates())
			$this->Argument->Vote->save($this->data, array('validate'=>false));
		else
		{
			$this->Session->setFlash(implode('<br />\r\n', $this->Argument->Vote->validationErrors));
		}
	
		$this->redirect(array('controller'=>'debates', 'action'=>'view', $argument['Argument']['debate_id']));
	}

	function new_argument($type, $id)
	{
		if(empty($this->data))
		{
			if(strtolower($type) != 'debate')
			{
				$this->Session->setFlash("Invalid URL for Argument creation");
				$this->redirect(array('controller'=>'debates', 'action'=>'index'));
			}
			
			$this->data['Debate']['id'] = $id;
			
			$debate = $this->Argument->Debate->findById($id);
			
			$this->set('debate', $debate);
			$this->set('title_for_layout', 'Argue your point for Debate "'.$debate['Debate']['title'].'"');
		}
		else
		{
			$this->Argument->create();
			
			$current_user = $this->Auth->user('id');
			
			$this->data['Argument']['user_id'] = $current_user['id'];
			$this->data['Argument']['debate_id'] = $this->data['Debate']['id'];
			
			$this->Argument->save($this->data);
			
			$this->redirect(array('controller'=>'debates', 'action'=>'view', $this->data['Debate']['id']));
		}
	}
}