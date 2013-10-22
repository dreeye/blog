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

 // ------------------------------------------------------------------------

/**
 * ProNoz User  Class
 *
 * userdata登录信息
 *
 * @package		PRONOZ
 * @subpackage	Models
 * @category	Models
 * @author		Eugene <nozwang@gmail.com>
 */
class User
{
	/**
     * user domain
     *
     * @access private
     * @var array
     */
    private $_user = array();

	/**
     * 用户ID
     *
     * @access public
     * @var integer
     */
	public $uid = 0;

	/**
     * 登录用户名
     *
     * @access public
     * @var string
     */
	public $username = '';

	/**
     * Email
     *
     * @access public
     * @var string
     */
	public $mail = '';

	/**
     * 昵称
     *
     * @access public
     * @var string
     */
	public $screenName = '';

	/**
     * 帐号创建日期
     *
     * @access public
     * @var string
     */
	public $created = 0;

	/**
     * 最后活跃时间
     *
     * @access public
     * @var string
     */
	public $activated = 0;

	/**
     * 上次登录
     *
     * @access public
     * @var string
     */
	public $logged = 0;

	/**
     * 所属用户组
     *
     * @access public
     * @var string
     */
	public $group = 'visitor';

	/**
     * 本次登录Token
     *
     * @access public
     * @var string
     */
	public $token = '';

	/**
    * CI句柄
    * 
    * @access private
    * @var object
    */
	private $_CI;

	 /**
     * 构造函数
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        /** 获取CI句柄 */
		$this->_CI = & get_instance();
		
		$this->_user = unserialize($this->_CI->session->userdata('user'));
		
		/** 初始化工作 */
		if(!empty($this->_user))
		{
			$this->uid = $this->_user['uid'];
			$this->username = $this->_user['username'];
			$this->mail = $this->_user['mail'];
			$this->url = $this->_user['url'];
			$this->screenName = $this->_user['screenName'];
			$this->created = $this->_user['created'];
			$this->activated = $this->_user['activated'];
			$this->logged = $this->_user['logged']; 
			$this->group = $this->_user['group']; 
			$this->token = $this->_user['token'];
		}
		
		log_message('debug', "PRONOZ: User Domain library Class Initialized");
    }
}

/* End of file User.php */
/* Location: ./application/libraries/User.php */