<?php

class DebatesController extends AppController
{
	var $name = 'Debates';
	
	public $components = array('Auth');
	
	var $helpers = array ('Html');
	
	var $uses = array('Poll', 'Debate');
		
	function index()
	{
		$debates = $this->Debate->find('all');
		
		$this->set('title_for_layout', 'All Debates');
		$this->set('debates', $debates);
	}
	
	function view($id)
	{
		$debate = $this->Debate->find('first', array('conditions' => array('Debate.id'=>$id), 'recursive'=>-1));
		$author = $this->Debate->User->find('first', array('conditions' => array('User.id'=>$debate['Debate']['user_id']), 'recursive'=>-1));
		
		// Does this debate belong to the user
		if($author['User']['id'] == $this->Auth->user('id')) { $is_author = true; }
		else { $is_author = false; }
		
		$sort = @$this->params['named']['sort'];
		
		$end_date_passed = false;
		if($debate['Debate']['end_time'] != null && $debate['Debate']['end_time'] < date('Y-m-d H:i:s'))
		{
			$end_date_passed = true;
		}
				
		$argument_params = array('conditions' => array('Argument.debate_id'=>$debate['Debate']['id']));
		if($end_date_passed)
		{
			$argument_params['order'] = 'Argument.vote_count DESC';
			$sort = 'v_high';
		}
		
		$arguments = $this->Debate->Argument->find('all', $argument_params);
		
		if($end_date_passed)
		{
			$winning_argument = $arguments[0];
		}
		
		$this->Debate->Category->bindModel(array('hasOne' => array('CategoriesDebate' => array('className' => 'CategoriesDebate'))));
		$categories = $this->Debate->Category->find('all', array('conditions' => array('CategoriesDebate.debate_id' => $debate['Debate']['id'])));
				
		if(empty($debate))
		{
			$this->Session->setFlash('Invalid debate URL');
			$this->redirect('index');
			exit();
		}
						
		$this->set('title_for_layout', $this->data['Debate']['title']);
		$this->set('is_author', $is_author);
		$this->set('show_edit_link', $is_author || $this->Session->read('is_admin'));
		$this->set('show_admin_links', $is_author || $this->Session->read('is_admin'));
		$this->set('debate', $debate);
		$this->set('author', $author);
		$this->set('arguments', $arguments);
		
		if($end_date_passed)
		{
			$this->set('winning_argument', $winning_argument);
		}
		
		$this->set('end_date_passed', $end_date_passed);
		$this->set('categories', $categories);
	}
	
	function new_debate()
	{
		if(empty($this->data))
		{
			$this->set('title_for_layout', 'Create a new Debate');
		}
		else
		{		
			$this->Debate->create();
			
			$current_user = $this->Auth->user('id');
			
			$this->data['Debate']['user_id'] = $current_user['id'];
			$this->Debate->saveAll($this->data);
			$debate_id = $this->Debate->id;
			
			$this->redirect(array('controller'=>'debates', 'action'=>'view', $debate_id));
		}
	}
	
	function vote($id = null)
	{
		$debate = $this->Debate->findById($id);
		
		$current_user = $this->Auth->user('id');
				
		$this->Debate->id = $debate['Debate']['id'];
		
		$this->Debate->Vote->create();
		$this->data['Vote']['voteable_id'] = $this->Debate->id;
		$this->data['Vote']['user_id'] = $current_user['id'];
		$this->data['Vote']['type'] = 'debate_vote';
		
		$this->Debate->Vote->set($this->data);
		
		if($this->Debate->Vote->validates())
			$this->Debate->Vote->save($this->data, array('validate'=>false));
		else
		{
			$this->Session->setFlash(implode('<br />\r\n', $this->Debate->Vote->validationErrors));
		}
	
		$this->redirect(array('controller'=>'debates', 'action'=>'view', $id));
	}
	
	function edit($id = null)
	{
		if(empty($this->data))
			$this->data = $this->Debate->findById($id);
		else
		{
			$this->Debate->id = $id;	// Save Logic
			$this->data['Debate']['id'] = $id;
		
			$this->Debate->saveAll($this->data);
			
			$this->redirect(array('action'=>'view', $id));
		}	
		
	}
	
	function delete($id)
	{
		$debate = $this->Debate->find('first', array('conditions' => array('Debate.id'=>$id)));
		
		if(empty($debate))
		{
			$this->Session->setFlash('Invalid debate URL');
			$this->redirect('index');
			exit();
		}
	
		$this->Debate->id = $id;
		$this->Debate->delete($id, true);
		
		$this->Session->setFlash("You have successfully deleted Debate \"".$debate['Debate']['title']."\"!");
		$this->redirect('index');
	}
}
