<?php

class PostingsController extends AppController
{
	var $name = 'Postings';
	
	var $helpers = array ('Html');
		
	function index()
	{
		$postings = $this->Posting->find('all');
		
		$this->set('title_for_layout', 'All Postings');
		$this->set('postings', $postings);
	}
	
	function read($id)
	{
		$posting = $this->Posting->find('first', array('conditions' => array('Posting.id'=>$id)));
		
		if(empty($posting))
		{
			$this->Session->setFlash('Invalid posting URL');
			$this->redirect('index');
			exit();
		}
				
		$this->set('title_for_layout', $posting['Posting']['title']);
		$this->set('posting', $posting);
	}
	
	function new_posting()
	{
		if(empty($this->data))
		{
			$this->set('title_for_layout', 'Create a new Posting');
		}
		else
		{
			$this->Posting->create();
			
			$current_user = $this->Auth->user('id');
			
			$this->data['Posting']['user_id'] = $current_user['id'];

			$this->Posting->save($this->data);
			
			$this->redirect(array('controller'=>'postings', 'action'=>'read', $this->Posting->id));
		}
	}
	
	function vote($id = null)
	{
		$posting = $this->Posting->findById($id);
		
		$current_user = $this->Auth->user('id');
				
		$this->Posting->id = $posting['Posting']['id'];
		
		$this->Posting->Vote->create();
		$this->data['Vote']['voteable_id'] = $this->Posting->id;
		$this->data['Vote']['user_id'] = $current_user['id'];
		$this->data['Vote']['type'] = 'posting_vote';
		
		$this->Posting->Vote->set($this->data);
		if($this->Posting->Vote->validates())
			$this->Posting->Vote->save($this->data, array('validate'=>false));
		else
		{
			$this->Session->setFlash(implode('<br />\r\n', $this->Posting->Vote->validationErrors));
		}
	
		$this->redirect(array('controller'=>'postings', 'action'=>'read', $id));
	}

	
	function edit($id = null)
	{
/*		echo "id = $id<br />";
		print_r($this->data);
		
		die();
		$posting = $this->Posting->find('first', array('conditions' => array('Posting.id'=>$id)));
		
		if(empty($posting))
		{
			$this->Session->setFlash('Invalid posting URL');
			$this->redirect('index');
			exit();
		}
		
		$this->set('posting', $posting);
*/
		if(empty($this->data))
			$this->data = $this->Posting->findById($id);
		else
		{
			$this->Posting->id = $id;	// Save Logic
			
			$this->Posting->save($this->data);
			$this->redirect(array('action'=>'read', $id));
		}	
		
	}
	
	function delete($id)
	{
		$posting = $this->Posting->find('first', array('conditions' => array('Posting.id'=>$id)));
		
		if(empty($posting))
		{
			$this->Session->setFlash('Invalid posting URL');
			$this->redirect('index');
			exit();
		}
	
		$this->Posting->id = $id;
		$this->Posting->delete();
		
		$this->Session->setFlash("You have successfully deleted Posting \"".$posting['Posting']['title']."\"!");
		$this->redirect('index');
	}
}