<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * ProNoz Blogging System
 *
 * 基于Codeigniter的单用户多权限开源博客系统
 * 
 * ProNoz is an open source multi-privilege blogging System built on the 
 * well-known PHP framework Codeigniter.
 *
 * @package		PRONOZ
 * @author		Eugene <nozwang@gmail.com>
 * @copyright	Copyright (c) 2012 - 2013, pronoz.net.
 * @version		1.0
 */

//--------------------------------------------------------------------------------

/**
 * ProNoz的用户处理类
 *
 * 在autoload.php中自动加载。
 *
 */

class Auth
{
	//接受CI核心类
	private $_CI;
	
	//是否登录，boolean
	private $_hasLogin;
	
	//用户数据
	private $_user;

       /**
     	* 用户组
     	*
     	* @access public
     	* @var array
     	*/
    	public $groups = array(
            'administrator' => 0,
            'editor'		=> 1,
            'contributor'	=> 2
            );


	public function __construct()
	{
		$this->_CI =& get_instance();

		$this->_CI->load->model('users_mod');
		//获取浏览器中cookie
		$this->_user = unserialize($this->_CI->session->userdata('user'));
		log_message('debug', "PRONOZ: Authentication library Class Initialized");
	}
	
        /**
     	* 判断用户是否已经登录
     	*
     	* @access public
     	* @return boolean
     	*/
	public function hasLogin()
	{
		/** 如果process_logion更新完用户信息，直接返回 */
		if ($this->_hasLogin !== NULL) 
		{
			return $this->_hasLogin;
      	}
		else 
		{
			//判断cookie是否为空或cookie中有无管理员uid
			if( ! empty($this->_user) && $this->_user['uid'] !==NULL)
			{
				$user = $this->_CI->users_mod->get_user_by_id($this->_user['uid']);
				
				//是否多点登陆
				if($user && $user['token'] == $this->_user['token'])
				{
					//库中要更新的活跃时间
					$user['activated'] = time();
					//cookie要更新的活跃时间
					$this->_user['activated'] = time();

					//cookie的expire比当前时间小，视为过期。如果expire没意义，永不过期
					if($this->_user['expire'] && $this->_user['expire'] < $user['activated'])
					{

						return ($this->_hasLogin = FALSE);
					}
					else
					{
						if($this->_user['expire'])
						{
							//expire延长更新时间
							$this->_set_session('false');
						}
						else
						{
							//expire为0，永不过期
							$this->_set_session('true');
						}
					}

					//更新库中活跃时间
					$this->_CI->users_mod->update_user($this->_user['uid'],$user);
				
					
					return ($this->_hasLogin = TRUE);
				}
			}

			return ($this->_hasLogin = FALSE);
		}
	}


	/**
	 * 更新用户数据
	 * @access public
	 * @param userdata
	 * @param remember me
	 * @return boolean
	 */
	public function process_login($user,$remember)
	{
		/** 用户信息替换 */
		$this->_user = $user;
		
		/** 每次登陆时需要更新的数据 */
		$this->_user['logged'] = now();
		$this->_user['activated'] = $user['logged'];
		
		/** 每登陆一次更新一次token */
		$this->_user['token'] = sha1(now().rand());
		
		if($this->_CI->users_mod->update_user($this->_user['uid'],$this->_user))
		{
			
			/** 设置session */
			$this->_set_session($remember);
			$this->_hasLogin = TRUE;
			
			return TRUE;
		}
		
		return FALSE;
	}

	/**
     * 设置session
     *
     * @access private
     * @return void
     */
	 private function _set_session($remember = 'false')
	 {

		//把userdata和remember存到session
		$session_init = $this->_user;

		if($remember == 'true')
		{
			$session_init['expire'] = 0;
		}
		else
		{
			$session_init['expire'] = time() + PN_EXPIRE;
		}
		
		
		$session_data = array('user'=>serialize($session_init));
		$this->_CI->session->set_userdata($session_data);
	 }

	 /**
     * 处理用户登出
     * 
     * @access public
	 * @param 默认不掉转，只有用户自主登出参数才是TRUE
     * @return void
     */
	public function process_logout($redirect = FALSE)
	{
		$this->_CI->session->sess_destroy();

		if($redirect == TRUE)
		{
			redirect('admin/login');
		}	
	}

	/**
     * 判断用户权限
     *
     * @access 	public
     * @param 	string 	$group 	用户组
     * @param 	boolean $return 默认直接show_error,TURE会return false
     * @return 	boolean
     */
	public function exceed($group, $return = false)
	{
		/** 权限验证通过 */
        if(array_key_exists($group, $this->groups) && $this->groups[$this->_user['group']] <= $this->groups[$group]) 
		{
            return TRUE;
        }
		
		/** 权限验证未通过，同时为返回模式 */
		if($return)
		{
			return FALSE;
		}
		
		/** 非返回模式 */
		show_error('禁止访问：你的权限不足');
		return;
	}
}
