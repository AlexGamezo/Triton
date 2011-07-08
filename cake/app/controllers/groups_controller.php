<?php

class GroupsController extends AppController
{
	var $name = 'Groups';
	
	var $components = array('RequestHandler');
	var $helpers = array('Html', 'Js');
	
	function index()
	{
		$groups = $this->Group->find('all');
		
		$this->set('groups', $groups);
	}
	
	function new_group()
	{
		if(empty($this->data))
		{
			$this->set('title_for_layout', 'Create a new Group');
		}
		else
		{	
			$this->Group->create();
			$this->Group->saveAll($this->data);
			$group_id = $this->Group->id;
			
			$this->redirect(array('controller'=>'groups', 'action'=>'members', $group_id));
		}
	}
	
	function edit($id = null)
	{
		if(empty($this->data))
		{
			$this->data = $this->Group->find('first', array('conditions' => array('Group.id' => $id)));
		
	//		$this->User->Group->bindModel(array('hasOne' => array('GroupsUser')));
			$users = $this->User->Group->find('all', array('conditions' => array('Group.id' => $id)));

			$this->User->unbindModel(array('hasMany' => array('Posting', 'UserPermission', 'Vote')));
			$avail_users = $this->User->find('all',
						array(
							'conditions' => array(
									'OR' => array('Group.group_id IS NULL', 'Group.group_id != ' => $id)
									),
							'joins' => array(
								array(
								'table'=>'groups_users',
								'alias'=>'Group',
								'type'=>'LEFT',
								'conditions' => array('Group.user_id = User.id')
								)
							),
							'recursive' => -1
						)
					);
						
			$group_users = array();
			$available_users = array();
			
			foreach($users[0]['User'] as $user)
			{
				$group_users[$user['id']] = $user['full_name'];
			}
			
			foreach($avail_users as $user)
			{
				$available_users[$user['User']['id']] = $user['User']['full_name'];
			}
					
			$this->set('users', $group_users);
			$this->set('available_users', $available_users);
		}
		else
		{
			$this->Group->id = $id;
			
			$this->Group->save($this->data);
		
			$this->redirect(array('action'=>'members', $id));
		}
	}
	
	function members($id = null)
	{
		if(empty($id))
		{
			$this->Session->setFlash('Invalid URL');
			$this->redirect('index');
			exit();
		}
		
		$group = $this->Group->findById($id);
		$this->data = $group;
		
		$this->Group->User->bindModel(array('hasOne' => array('GroupsUser')));
		
		$members = $this->Group->User->find('all', array('conditions' => array('GroupsUser.group_id'=>$id), 'fields'=>'User.*'));

		$this->set('group', $group);
		$this->set('members', $members);
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
}

?>