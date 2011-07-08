<?php

class UsersController extends AppController
{
	var $name = 'Users';
	var $helpers = array('Html', 'Form');
	
	function index()
	{
		$users = $this->User->find('all');
		
		$this->set('users', $users);
		
		if($this->__has_permission('users', 'new_user', $this->Auth->user('id')))
			$this->set('allowed_user_create', 1);
	}
	
	function all($format = null)
	{
		if($format == 'json')
		{
			$users = $this->User->find('list', array('fields'=>array('id', 'full_name'),
											   'conditions'=>
													array('User.full_name LIKE' => '%'.$_GET['partial_user'].'%')));
			
			$users_json = json_encode($users);
			
			$this->set('json', $users_json);
			$this->layout = 'blank';
			$this->render('/elements/json_response');
		}
	}
	
	function profile($id = null)
	{
		if($id == null)
			$id = $this->Auth->user('id');
		
//		$this->User->unbindModel('Posting');
//		$this->User->unbindModel(array('hasMany'=>array('Vote', 'Posting', 'Debate', 'Argument')));
		$user = $this->User->findById($id);

		$all_actions = $this->User->getUserActivities();		
				
		$activities = $all_actions['activities'];		
		$votes = $all_actions['votes'];		
		
		if(empty($user))
		{
			die("Setting flash");
			$this->Session->setFlash('Invalid profile URL');
			$this->redirect('/pages/home');
		}
		
		$this->set('user', $user);
		$this->set('my_votes', $votes);
		$this->set('my_activities', $activities);
	}
		
	function register()
	{
		if(empty($this->data))
		{
			$this->set('title_for_layout', 'Create a new User');
		}
		else
		{	
			$this->User->create();
			$user = $this->User->save($this->data);
			
			
			if(!empty($user))
			{
				$user_id = $this->User->id;
				$this->redirect(array('controller'=>'users', 'action'=>'index'));
			}
			else
			{
				unset($this->data['User']['password']);
				unset($this->data['User']['password2']);
				
				if(isset($this->User->validationErrors['password2']))
				{
					if(!isset($this->User->validationErrors['password']))
						$this->User->validationErrors['password'] = $this->User->validationErrors['password2'];
					unset($this->User->validationErrors['password2']);
				}
			}
		}
	}
	
	function edit($id = null)
	{
		if(empty($this->data))
		{
			$this->data = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		
			$this->User->unbindModel(array('hasMany' => array('Posting', 'UserPermission', 'Vote')));
		}
		else
		{
			$this->User->id = $id;
			
			$fields = array('full_name', 'email');
			
			if(!empty($this->data['User']['new_pass']))
			{
				if($this->data['User']['new_pass'] == $this->data['User']['new_pass2'])
				{
					$fields = array_merge($fields, array('password'));
					$this->data['User']['password'] = $this->Auth->password($this->data['User']['new_pass']);
				}
				else
					$this->data['User']['password'] = false;
			}
			
			$this->User->save($this->data, array('fields'=>$fields));
		
			$this->redirect(array('action'=>'index', $id));
		}
	}

	function delete($id)
	{
		$group = $this->Group->find('first', array('conditions' => array('Group.id'=>$id)));
		
		if(empty($group))
		{
			$this->Session->setFlash('Invalid Group URL');
			$this->redirect('index');
			exit();
		}
	
		$this->Group->id = $id;
		$this->Group->delete($id, true);
		
		$this->Session->setFlash("You have successfully deleted Group \"".$group['Group']['name']."\"!");
		$this->redirect('index');
	}

	function beforeFilter()
	{		
		$this->Auth->allow('register');
		
		$this->initPermissions();
		
		$current_user = $this->User->findById($this->Auth->user('id'), array(
//							'joins'=> array(
//								array('alias'=>'GroupUser', 'table'=>'groups_users', 'conditions'=> 'GroupUser.user_id = User.id')
//							)
						)
					);
		
		$this->set('current_user', $current_user);
	}
}

?>