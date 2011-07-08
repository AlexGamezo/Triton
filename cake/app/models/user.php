<?php


class User extends AppModel
{
	var $name = 'User';
	var $displayField = 'full_name';
	
	var $hasMany = array(
		'Posting' => array(
			'className' => 'Posting',
		),
		'Debate' => array(
			'className' => 'Debate',
		),
		'Poll' => array(
			'className' => 'Poll',
		),
		'Argument' => array(
			'className' => 'Argument',
		),
		'UserPermission' => array(
			'className' => 'UserPermission'
		),
		'Vote' => array(
			'className' => 'Vote'
		)
	);
	
	var $hasOne = array(
		'UserProfile' => array(
			'className' => 'UserProfile',
			'dependent' => true
			)
		);
	
	var $hasAndBelongsToMany = array(
		'Group' => array(
			'className' => 'Group',
			'unique' => false
		)
	);
	
	var $validate = array(
			'username' => array(
				'rule' => 'isUnique',
				'required' => true,
				'allowEmpty' => false,
				'on' => 'create',
				'message' => 'The username must be unique!'
			),
			'password' => array(
				'password_confirmed' => array(
					'rule' => 'confirm_password',
					'required' => true,
					'on' => 'create',
					'message' => 'Password is not confirmed',
					)
				),
			'password2' => array(
				'password_length' => array(
					'rule' => array('minLength', 4),
					'required' => true,
					'allowEmpty' => false,
					'message' => "Password must least 4 characters long"
					),
				)

		);
		
	var $actsAs = array("Containable");
	
	function getUserActivities()
	{
		$all_items = $this->find('first', array(
				'conditions' => array('id'=>1),
				'contain'=>array(
					'Posting',
					'Debate',
					'Argument',
					'Poll' => array('PollOption'),
				)
			)
		);
				
		$all_votes = $this->Vote->find('all', array(
				'conditions' => array('Vote.user_id' => 1),
				'contain'=>array(
					'Posting',
					'Debate',
					'Argument',
					'Poll',
					'PollOption'
				)
			)
		);
		
		$activities = array();
		$votes = array();
		
		$polls		= $all_items['Poll'];
		$debates	= $all_items['Debate'];
		$postings	= $all_items['Posting'];
		$arguments	= $all_items['Argument'];
		
		foreach($polls as $poll)
		{
			$activity = array();
			$activity['type'] = 'Poll';
			$activity['title'] = $poll['title'];
			$activity['summary'] = $poll['summary'];
			$activity['date'] = $poll['created'];
			$activity['vote_count'] = $poll['vote_count'];
			
			$activities[] = $activity;
		}
		
		foreach($debates as $debate)
		{
			$activity = array();
			$activity['type'] = 'Debate';
			$activity['title'] = $debate['title'];
			$activity['summary'] = $debate['summary'];
			$activity['date'] = $debate['created'];
			$activity['vote_count'] = $debate['vote_count'];
			
			$activities[] = $activity;
		}
		
		foreach($arguments as $argument)
		{
			$debate = $this->Debate->find('first', array('conditions'=>array('Debate.id' => $argument['debate_id'])));
			if($debate == null)
				continue;
				
			$activity = array();
			$activity['id'] = $argument['id'];
			$activity['type'] = 'Argument';
			$activity['title'] = 'Argument for ' . $debate['Debate']['title'];
			$activity['summary'] = $argument['argument'];
			$activity['date'] = $argument['created'];
			$activity['vote_count'] = $argument['vote_count'];
			
			$activities[] = $activity;
		}
		
		foreach($postings as $posting)
		{
			$activity = array();
			$activity['type'] = 'Posting';
			$activity['title'] = $posting['title'];
			$activity['summary'] = $posting['summary'];
			$activity['date'] = $posting['created'];
			$activity['vote_count'] = $posting['vote_count'];
			
			$activities[] = $activity;
		}
		
		foreach($all_votes as $ind_vote)
		{
			$voteable_item = null;
			$voteable_array = null;
			$voteable_type = '';
			
			if($ind_vote['Vote']['type'] == 'poll_vote')
			{
				$voteable_type = 'Poll';
			}
			elseif($ind_vote['Vote']['type'] == 'poll_option_vote')
			{
				$voteable_type = 'PollOption';
			}
			elseif($ind_vote['Vote']['type'] == 'debate_vote')
			{
				$voteable_type = 'Debate';
			}
			elseif($ind_vote['Vote']['type'] == 'posting_vote')
			{
				$voteable_type = 'Posting';
			}
			elseif($ind_vote['Vote']['type'] == 'argument_vote')
			{
				$voteable_type = 'Argument';
			}
			else
			{
				die($ind_vote['Vote']['type']);
			}
	
			$vote = array();
			if($voteable_type == 'Argument')
			{
				$debate = $this->Debate->find('first', array('conditions' => array('Debate.id' => $ind_vote['Argument']['debate_id'])));
				$vote['title'] = 'Vote on Argument for '.$debate['Debate']['title'];
			}
			elseif($voteable_type == 'PollOption')
			{
				$debate = $this->Poll->find('first', array('conditions' => array('Poll.id' => $ind_vote['PollOption']['poll_id'])));
				$vote['title'] = 'Vote in Poll '.$debate['Poll']['title'];
			}
			else
			{
				$vote['title'] = 'Vote for '.$voteable_type.' '.$ind_vote[$voteable_type]['title'];
			}
			
			$vote['date'] = $ind_vote['Vote']['created'];
			
			$votes[] = $vote;
		}

		return array('activities' => $activities, 'votes' => $votes);
	}
	
	function confirm_password($data)
	{
		App::import('Component', 'Auth');
		
		$Auth = new AuthComponent();
		
		if($Auth->password($this->data['User']['password2']) == $data['password'])
			return true;
		else
			return false;
	}
/*
	function validateLogin($data)
	{
//		die('validateLogin');
		$user = $this->find(array('username'=>$data['username'], 'password'=>md5($data['password'])), array('id', 'username'));
		
		if(empty($user) === false)
			return $user['User'];
		return false;
	}
*/		
}

?>