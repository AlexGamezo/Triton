<?php

class PollsController extends AppController
{
	var $name = 'Polls';
	
	var $helpers = array ('Html');
		
	function index()
	{
		$polls = $this->Poll->find('all');
		
		$this->set('title_for_layout', 'All Polls');
		$this->set('polls', $polls);
	}
	
	function view($id)
	{
		$poll = $this->Poll->find('first', array('conditions' => array('Poll.id'=>$id)));
		
		if(empty($poll))
		{
			$this->Session->setFlash('Invalid poll URL');
			$this->redirect('index');
			exit();
		}
		
		$end_date_passed = false;
		if($poll['Poll']['end_time'] != null && $poll['Poll']['end_time'] < date('Y-m-d H:i:s'))
		{
			$end_date_passed = true;
		}
		
		
//		$poll_options = $this->Poll->PollOption->find('all', array('conditions' => array('PollOption.poll_id' => $id)));
		$options = array();
				
		foreach($poll['PollOption'] as $option)
			$options[$option['id']] = $option['option'] . ' (' .$option['vote_count'] . ')';
		
		$this->Poll->Category->bindModel(array('hasOne' => array('CategoriesPoll' => array('className' => 'CategoriesPoll'))));
		$categories = $this->Poll->Category->find('all', array('conditions' => array('CategoriesPoll.poll_id' => $poll['Poll']['id'])));
		
		$this->set('title_for_layout', $poll['Poll']['title']);
		$this->set('poll', $poll);
		$this->set('end_date_passed', $end_date_passed);
		$this->set('poll_options', $options);
		$this->set('categories', $categories);
	}
	
	function new_poll()
	{
		if(empty($this->data))
		{
			$this->set('title_for_layout', 'Create a new Poll');
		}
		else
		{		
			$this->Poll->create();
			
			$current_user = $this->Auth->user('id');
			
			$this->data['Poll']['user_id'] = $current_user['id'];
			$this->Poll->saveAll($this->data);
			$poll_id = $this->Poll->id;
			
			$this->redirect(array('controller'=>'polls', 'action'=>'view', $poll_id));
		}
	}
	
	function vote($id = null)
	{
		if(empty($this->data['PollOption']['option']))
		{
			$this->Session->setFlash("You didn't select an option");
			$this->redirect(array('controller'=>'polls', 'action'=>'view', $this->data['Poll']['id']));
		}
	
		$poll_option = $this->Poll->PollOption->findById($this->data['PollOption']['option']);
		
		$current_user = $this->Auth->user('id');
		
		if($poll_option['PollOption']['poll_id'] != $this->data['Poll']['id'])
		{
			$this->Session->setFlash("Invalid Vote Submission");
			$this->redirect(array('controller'=>'polls', 'action'=>'index'));
		}
		
		$this->Poll->PollOption->id = $poll_option['PollOption']['id'];
		
		$this->Poll->PollOption->Vote->create();
		$this->data['Vote']['voteable_id'] = $poll_option['PollOption']['id'];
		$this->data['Vote']['user_id'] = $current_user['id'];
		$this->data['Vote']['type'] = 'poll_option_vote';
		
		$this->Poll->PollOption->Vote->set($this->data);
		
		if($this->Poll->PollOption->Vote->validates())
			$this->Poll->PollOption->Vote->save($this->data, array('validate'=>false));
		else
		{
			$validation_errors = $this->Poll->PollOption->Vote->validationErrors;
			
			if($validation_errors['voteable_id'] == "You've already voted")
			{
				$current_poll_option = $this->Poll->PollOption->findById($this->data['Vote']['voteable_id'], array('fields'=>'poll_id'));
				$poll_options = $this->Poll->PollOption->find('all', array('conditions'=>array('poll_id'=>$current_poll_option['PollOption']['poll_id'])));
				$poll_option_ids = array();
				foreach($poll_options as $poll_option)
					$poll_option_ids[] = $poll_option['PollOption']['id'];
				
				$last_vote = $this->Poll->PollOption->Vote->find('first', array('conditions'=>array(
												'Vote.user_id' => $this->data['Vote']['user_id'],
												'voteable_id' => $poll_option_ids
												)
											)
							);
								
				if($last_vote['Vote']['voteable_id'] != $this->data['Vote']['voteable_id'])
				{
					$this->Poll->PollOption->Vote->id = $last_vote['Vote']['id'];
					$this->Poll->PollOption->Vote->save($this->data, array('validate'=>false));
				}
				else
					$this->Session->SetFlash(implode('<br />\r\n', $validation_errors));
			}
		}
			
		$this->redirect(array('controller'=>'polls', 'action'=>'view', $id));
	}
	
	function edit($id = null)
	{
		if(empty($this->data))
			$this->data = $this->Poll->findById($id);
		else
		{
			$this->Poll->id = $id;	// Save Logic
			$this->data['Poll']['id'] = $id;
			foreach($this->data['PollOption'] as $option)
				$option['poll_id'] = $id;
		
			$this->Poll->saveAll($this->data);
			$this->Poll->PollOption->deleteAll(array('poll_id' => $id, 'option'=>''));
			
			$this->redirect(array('action'=>'view', $id));
		}	
		
	}
	
	function delete($id)
	{
		$poll = $this->Poll->find('first', array('conditions' => array('Poll.id'=>$id)));
		
		if(empty($poll))
		{
			$this->Session->setFlash('Invalid poll URL');
			$this->redirect('index');
			exit();
		}
	
		$this->Poll->id = $id;
		$this->Poll->delete($id, true);
		
		$this->Session->setFlash("You have successfully deleted Poll \"".$posting['Poll']['title']."\"!");
		$this->redirect('index');
	}
}