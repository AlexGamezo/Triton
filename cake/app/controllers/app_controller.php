<?php

class AppController extends Controller
{
	public $helpers    = array("Html","Form","Session","Time",);
	public $components = array('Auth', 'Session');
	
	public $uses = array('Poll', 'Debate', 'User', 'UserPermission');
	
	public $user_id_scope;
	public $user_group_id;
	
	public $user_model 				= 'User';
	public $user_permission_model 	= 'UserPermission';
	
	protected $auth_user_id       = false;
	protected $auth_group_id	  = false;

	protected $ignore_urls 			= array('users/login','users/logout', 'users/register');
	protected $ignore_controllers 	= array('css','js','img');

	//is admin account
	public $is_admin = false;
	
	/// MODEL FOR USER IN SCOPE
	public $user_data;
	
	function beforeFilter()
	{	
//		parent::beforeFilter();
		
		$this->Auth->authorize = 'controller';
		
//		if($this->controller == 'users' && $this->action == 'login')
		$cur_user_id = $this->Auth->user('id');
		if(empty($cur_user_id))
		{
			$this->Auth->autoRedirect = false;	
		}

		$this->loadModel($this->user_model);
		$this->loadModel($this->user_permission_model);

//		$this->Auth->allow("*");
		
		if ( !in_array(trim(strtolower($this->params['url']['url'])),$this->ignore_urls) && !in_array(strtolower($this->params['controller']),$this->ignore_controllers) ) {			
			$this->Session->write("here",$_SERVER['REQUEST_URI']);				
		}
		
		$this->initPermissions();
	}
	
	function beforeRender()
	{
		$this->set('current_user', $this->Auth->user());
		$this->set('latest_debates', $this->Debate->get_latest_debates());
		$this->set('latest_polls', $this->Poll->get_latest_polls());
	}
	
	function login() 
	{
		$cur_user_id = $this->Auth->user('id');
		
		if(!empty($cur_user_id))
		{
			$cur_user = $this->User->find('first', array('conditions'=>array('id'=> $cur_user_id), 'recursive'=>-1));
			
			$cur_user['User']['updated'] = false;
			$cur_user['User']['lastLogin'] = date('Y-m-d H:i:s');
			die('help');
			$this->User->id = $cur_user['User']['id'];
			$this->User->save($cur_user['User'], true, array('lastLogin'));
			
			$this->redirect(array('controller'=>'postings', 'action'=>'index'));
		}
	}

    function logout()
	{
		$this->redirect($this->Auth->logout());
	}
	
	/**
	 * Initialize the Authentication variables
	 * @return void
	 */
	private function initAuthVariables() {
		
		 /// DEFAULT BEHAVIOR
//		if ( $this->Auth->user("group") != '' ) {
//			$this->auth_group_id = $this->Auth->user("group");
//		}
	
		if ( $this->Auth->user("id") != '' ) {
			
			$my_groups = $this->User->Group->GroupsUser->find('all',
							array(
								'conditions' => array('user_id' => $this->Auth->user('id')),
								'fields' => array('user_id', 'group_id')));
		
			$this->auth_group_id = array();
			foreach($my_groups as $group)
			{
				if($group['GroupsUser']['group_id'] == 6)	// If Site Admins group
				{
					$this->setAdmin(true);
				}
				
				$this->auth_group_id[] = $group['GroupsUser']['group_id'];
			}
						
			$this->auth_user_id = $this->Auth->user("id");
			$this->user_id_scope = $this->Auth->user("id");
		
			$this->Session->write("user_id_scope",$this->Auth->User("id"));
		
		}
	
		if ( $this->Auth->user("parent_id") != '' ) {
			$this->user_id_scope = $this->Auth->user("parent_id");
			$this->Session->write("user_id_scope",$this->Auth->User("parent_id"));
		}
	
		if ( $this->Auth->user("group") == 'admin' ) {
			$this->setAdmin(true);	
		}
		else {
			$this->setAdmin(false);	
		}
	
	}

	////////////////////////////
		
	/**
	 * Accessor to set if the users scope is "admin"
	 * @param Bool $is_admin
	 * @return void
	 */
	protected function setAdmin($is_admin = false) {
		
		if ( $is_admin ) {                    
			$this->is_admin=true;
			$this->Session->write("is_admin",true);
		}
		else {
			$this->is_admin=false;
			//$this->Session->delete("is_admin");
		}
		
	}
		
	////////////////////////////
	
	protected function initPermissions() {

	    $this->initAuthVariables();		
		
	    $controller = $this->name;
		
	    $action = $this->params['action'];
		
	    //load in the group permission actions
		$conditions = array(
					"conditions"=>array(
						"group_id"=>$this->auth_group_id,
						"controller"=>array("*",$controller)
						),
					"order"=>array("action")
				);
            
	    $group_perms = $this->{$this->user_model}->{$this->user_permission_model}->find("all",$conditions);

	    foreach( $group_perms as $v ) {
                
			if ( intval($v[$this->user_permission_model]['allowed']) ) {
				$this->Auth->allow($v[$this->user_permission_model]['action']);
			}
			else {
				$this->Auth->deny($v[$this->user_permission_model]['action']);
			}
		
	    }

	    //load permissions for the user 
	    if ( $this->auth_user_id != '' ) {

			$conditions = array(
				"conditions"=>array(
					"user_id"=>$this->auth_user_id,
					"controller"=>array("*",$controller)
				),
				"order"=>array("action"),
				'recursive'=>-1
			);

			$user_perms = $this->{$this->user_model}->{$this->user_permission_model}->find("all",$conditions);
			
//			print_r($group_perms);
//			print_r($user_perms);
			
			foreach( $user_perms as $v ) {

				if ( intval($v[$this->user_permission_model]['allowed']) ) {
					$this->Auth->allow($v[$this->user_permission_model]['action']);
				}
				else {
					$this->Auth->deny($v[$this->user_permission_model]['action']);
				}
	
			}
	    
        }
		
//		print_r($this->Auth->allowedActions);
//		die();
		
	    if ( intval($this->Auth->user("g_id")) ) {
            $this->Session->write("Auth.User.allowedActions",$this->Auth->allowedActions);
	    }
	    
		$this->afterInitPermissions();
		
	}
        
    ///////////////////////
	function __has_permission($controller, $action, $user_id = null, $group_id = null)
	{		
		if($group_id)
		{
			$group_permissions = $this->UserPermission->find('all', array(
						'conditions'=>array(
							'controller'=>array('*', $controller),
							'action'=>array('*', $action),
							'group_id' => $group_id
						),
						'order' => array('controller', 'action', 'allowed')
					)
				);
		
			foreach($group_permissions as $perm)
			{
				if($perm['UserPermission']['allowed'])
				{
					echo "group perm - " . print_r($perm, true) . '<br />';
					$allowed = true;
					break;
				}
			}		
		}
		
		if($user_id)
		{
			$user_permissions = $this->UserPermission->find('all', array(
						'conditions'=>array(
							'controller'=>array('*', $controller),
							'action'=>array('*', $action),
							'user_id' => $user_id,
						),
						'order' => array('controller', 'action', 'allowed')
					)
				);
		}
			
		$allowed = false;
		
		foreach($user_permissions as $perm)
		{
			if($perm['UserPermission']['allowed'])
			{
				$allowed = true;
//				break;
			}
			else
			{
				$allowed = false;
			}
		}
	
		return $allowed;
	}

	
	///////////////////////
    
		public function afterInitPermissions() {}
	
	///////////////////////
	
	/**
	 * Stub Method Needed to extend the Auth components ACL
	 * @see public_html/cake/libs/controller/Controller#isAuthorized()
	 */
	public function isAuthorized() {
		if(!in_array($this->action, $this->Auth->allowedActions));
			$this->Session->setFlash("You do not have permission to view that page");
	}
	
        ///////////////////////    
        
	/**
	 * Redirect User With Invalid URL Message
	 * @param String $url realtive URL to redirect to
	 * @return void
	*/
	public function invalidUrl($url='/') {
		
	    $this->Session->setFlash("Invalid URL");
		
	    return $this->redirect($url);
	    
	}
    
	///////////////////////

	public function initCollection($id='') {
		
		$find_params = array(
			'conditions'=>array($this->{$this->collection}->name.'.'.$this->{$this->collection}->primaryKey=>$id)
		);
		
		return $this->{$this->collection}->find('first',array_merge($find_params,$this->edit_load_params));		
		
	}

	///////////////////////

	public function collectionLoad() {
		
		if ( $this->collection === null ) {
			return false;
		}
		
		if ( isset($this->params['pass'][0]) && intval($this->params['pass'][0]) ) {
		
			$id = $this->params['pass'][0];		
		
			$this->data = $this->initCollection($id);
		
			$this->verifySecurity();
		
			$this->set('id',$this->data[$this->collection][$this->{$this->collection}->primaryKey]);
		
			return true;
		   
		}		
		
		return false;
		
	}
}