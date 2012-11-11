<?php defined('SYSPATH') or die('No direct script access.');
/**
 * description...
 *
 * @author		Chema <chema@garridodiaz.com>
 * @package		OC
 * @copyright	(c) 2009-2011 Open Classifieds Team
 * @license		GPL v3
 * *
 */
class Model_User extends ORM {

    /**
     * Table name to use
     *
     * @access	protected
     * @var		string	$_table_name default [singular model name]
     */
    protected $_table_name = 'users';

    /**
     * Column to use as primary key
     *
     * @access	protected
     * @var		string	$_primary_key default [id]
     */
    protected $_primary_key = 'id_user';

    protected $_has_many = array(
        'post' => array('through' => 'id_user'),
    );

    /**
     * Role constants
     */
    const ROLE_USER= 1; // normal User
    const ROLE_ADMIN = 10; // Admin User
    
    
    /**
     * Rule definitions for validation
     *
     * @return array
     */
    public function rules()
    {
    	return array(
				        'id_user'	    => array(array('numeric')),
				        'name'	        => array(array('max_length', array(':value', 145))),
				        'email'	        => array(array('not_empty'), array('max_length', array(':value', 145)), ),
				        'password'	    => array(array('not_empty'), array('max_length', array(':value', 64)), ),
				        'status'	    => array(array('numeric')),
				        'role'	        => array(array('numeric')),
				        'id_location'   => array(),
				        'created'	    => array(),
				        'last_modified' => array(),
				        'logins'	    => array(),
				        'last_login'    => array(),
				        'last_ip'	    => array(),
				        'user_agent'	=> array(),
				        'token'	        => array(array('max_length', array(':value', 40))),
				        'token_created'	=> array(),
				        'token_expires'	=> array(),
				    );
    }
    
    

    /**
     * Label definitions for validation
     *
     * @return array
     */
    public function labels()
    {
    	return array(
    					'id_user'	    => 'Id',
				        'name'	    	=> 'Name',
				        'email'	    	=> 'Email',
				        'password'		=> 'Password',
				        'status'		=> 'Status',
				        'role'			=> 'Role',
				        'id_location'	=> 'Location',
				        'created'	    => 'Created',
				        'last_modified'	=> 'Last modified',
				        'last_login'	=> 'Last login',
				    );
    }

    /**
     * Filters to run when data is set in this model. The password filter
     * automatically hashes the password when it's set in the model.
     *
     * @return array Filters
     */
    public function filters()
    {
        return array(
    			'password' => array(
                                array(array(Auth::instance(), 'hash'))
                              )
        );
    }

    
	/**
	 * complete the login for a user
	 * incrementing the logins and saving login timestamp
	 * @param integer $lifetime Regenerates the token used for the autologin cookie
	 * 
	 */
	public function complete_login($lifetime=NULL)
	{
		if ($this->_loaded)
		{   
			//want to remember the login using cookie
		    if (is_numeric($lifetime))
		    	$this->create_token($lifetime);
		    
			// Update the number of logins
			$this->logins = new Database_Expression('logins + 1');

			// Set the last login date
			$this->last_login = Date::unix2mysql(time());
			
			// Set the last ip address
			$this->last_ip = ip2long(Request::$client_ip);

			try 
			{
				// Save the user
				$this->update();
			}
			catch (ORM_Validation_Exception $e)
			{
				Form::set_errors($e->errors(''));
			}
			catch(Exception $e)
			{
				throw new HTTP_Exception_500($e->getMessage());
			}
			
		}
	}
	
	/**
	 * Creates a unique token for the autologin
	 * @param integer $lifetime token alive
	 * @return string
	 */
	public function create_token($lifetime=NULL)
	{
		if ($this->_loaded)
		{
			//we need to be sure we have a lifetime
			if ($lifetime==NULL)
			{
				$config = Kohana::$config->load('auth');
				$lifetime = $config['lifetime'];
			}
			
			//we assure the token is unique
			do
			{
				$this->token = sha1(uniqid(Text::random('alnum', 32), TRUE));
			}
			while(ORM::factory('user', array('token' => $this->token))->limit(1)->loaded());
			
			// user Token data
			$this->user_agent    = sha1(Request::$user_agent);
			$this->token_created = Date::unix2mysql(time());
			$this->token_expires = Date::unix2mysql(time() + $lifetime);
			
			try
			{
				$this->update();
			}
			catch(Exception $e)
			{
				throw new HTTP_Exception_500($e->getMessage());
			}
		}
		
	    
	}
	
} // END Model_User