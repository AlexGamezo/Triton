<?php

class Vote extends AppModel
{

	/****************
	* Fields
	* - id
	* - user_id
	* - voteable_id
	* - type (what object the vote is for i.e poll_vote, posting_vote, user_vote)
	* - created
	* - updated
	*****************/
	
	var $name = 'Vote';
	
	var $belongsTo = array(
			'User' => array (
					'className' => 'User',
				),
			'Poll' => array (
					'className' => 'Poll',
					'foreignKey' => 'voteable_id',
					'counterCache' => true,
					'counterScope' => array('Vote.type' => 'poll_vote'),
				),
			'PollOption' => array (
					'className' => 'PollOption',
					'foreignKey' => 'voteable_id',
					'conditions' => array('Vote.type' => 'poll_option_vote'),
					'counterCache' => true,
					'counterScope' => array('Vote.type' => 'poll_option_vote'),
				),
			'Posting' => array (
					'className' => 'Posting',
					'foreignKey' => 'voteable_id',
					'conditions' => array('Vote.type' => 'posting_vote'),
					'counterCache' => true,
					'counterScope' => array('Vote.type' => 'posting_vote'),
				),
			'Debate' => array (
					'className' => 'Debate',
					'foreignKey' => 'voteable_id',
					'conditions' => array('Vote.type' => 'debate_vote'),
					'counterCache' => true,
					'counterScope' => array('Vote.type' => 'debate_vote'),
				),
			'Argument' => array (
					'className' => 'Argument',
					'foreignKey' => 'voteable_id',
					'conditions' => array('Vote.type' => 'argument_vote'),
					'counterCache' => true, 
					'counterScope' => array('Vote.type' => 'argument_vote'),
				)
			);

	var $validate = array(
						'voteable_id' => array(
							'duplicate_vote'=>array('rule'=>'duplicateVote', 'message'=>"You've already voted")
						)
				);
	
	var $actsAs = array("Containable");
	
	function duplicateVote($check)
	{
		$last_vote = null;
		if($this->data['Vote']['type'] == 'poll_option_vote')
		{
			$current_poll_option = $this->PollOption->findById($this->data['Vote']['voteable_id'], array('fields'=>'poll_id'));
			$poll_options = $this->PollOption->find('all', array('conditions'=>array('poll_id'=>$current_poll_option['PollOption']['poll_id'])));
			$poll_option_ids = array();
			foreach($poll_options as $poll_option)
				$poll_option_ids[] = $poll_option['PollOption']['id'];
			
			$last_vote = $this->find('first', array('conditions'=>array(
											'Vote.user_id' => $this->data['Vote']['user_id'],
											'voteable_id' => $poll_option_ids,
											'type' => 'poll_option_vote'
											),
											'recursive'=>-1,
										)
						);
		}
		elseif($this->data['Vote']['type'] == 'argument_vote')
		{
			$current_argument = $this->Argument->findById($this->data['Vote']['voteable_id'], array('fields'=>'debate_id'));
			$arguments = $this->Argument->find('all', array('conditions'=>array('debate_id'=>$current_argument['Argument']['debate_id'])));
			$argument_ids = array();
			foreach($arguments as $argument)
				$argument_ids[] = $argument['Argument']['id'];
			
			$last_vote = $this->find('first', array('conditions'=>array(
											'Vote.user_id' => $this->data['Vote']['user_id'],
											'voteable_id' => $argument_ids,
											'type' => 'argument_vote'
											),
											'recursive'=>-1,
										)
						);
		}
		else
		{
			$last_vote = $this->find('first', array('conditions'=>array(
											'Vote.user_id' => $this->data['Vote']['user_id'],
											'voteable_id' => $this->data['Vote']['voteable_id'],
											'type' => $this->data['Vote']['type']
											), 'recursive'=>-1,
										)
						);		
		}
		
		if(!empty($last_vote))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}

?>