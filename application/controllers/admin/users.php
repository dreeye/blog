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
 * ProNoz Dashboard Controller Class
 *
 * 控制台控制器
 *
 * @package		PRONOZ
 * @subpackage	Models
 * @category	Models
 * @author		Eugene <nozwang@gmail.com>
 */

class Users extends PN_Auth_Controller
{
	/**
     * 传递到对应视图的数据
     *
     * @access private
     * @var array
     */
	private $_data = array();
	
	/**
     * 编辑用户时为当前用户ID，添加用户为避免uid=0因为users库中uid从1开始
     *
     * @access private
     * @var integer
     */	
	private $_uid = 0;

	/**
     * 构造函数
     *
     * @access public
     * 
     */
	public function __construct()
	{
		parent::__construct();
		
		//用户权限检测，只允许admin访问
		$this->auth->exceed('administrator');
	    //验证类	
		$this->load->library('form_validation');
	}
	
	/**
     * 配置表单验证规则
     * 
     * @access private
     * @return void
     */
	private function _load_validation_rules()
	{
		$this->form_validation->set_rules('username', '用户名', 'required|trim|alpha_numeric|callback__username_check|strip_tags');
		$this->form_validation->set_rules('password', '新的密码', 'required|min_length[6]|trim|matches[confirm]');
		$this->form_validation->set_rules('confirm', '确认的密码', 'required|min_length[6]|trim');
		$this->form_validation->set_rules('screenName', '昵称', 'trim|callback__screenName_check|strip_tags');
		$this->form_validation->set_rules('url', '个人主页', 'trim|prep_url');
		$this->form_validation->set_rules('mail', '邮箱地址', 'required|trim|valid_email|callback__email_check');
		$this->form_validation->set_rules('group', '用户组', 'trim');
	}
	
	/**回调函数都由form_validation调用，所以必须是public
     * 回调函数：检查Name是否唯一
     * 
     * @access 	public
     * @param 	$str 输入值
     * @return 	bool
     */
	public function _username_check($str)
	{
		
		if($this->users_mod->check_exist('username', $str, $this->_uid))
		{	
			$this->form_validation->set_message('_username_check', '系统已经存在一个为 '.$str.' 的用户名');
			
			return FALSE;
		}
			
		return TRUE;
	}
	
	
	 /**
     * 回调函数：检查Email是否唯一
     * 
     * @access 	public
     * @param 	$str 输入值
     * @return 	bool
     */
	public function _email_check($str)
	{	
		if($this->users_mod->check_exist('mail', $str, $this->_uid))
		{

			$this->form_validation->set_message('_email_check', '系统已经存在一个为 '.$str.' 的邮箱');
			
			return FALSE;
		}
			
		return TRUE;
	}
	
	 /**
     * 回调函数：检查screenName是否唯一
     * 
     * @access 	public
     * @param 	$str 输入值
     * @return 	bool
     */
	public function _screenName_check($str)
	{
		if($this->users_mod->check_exist('screenName', $str, $this->_uid))
		{
			$this->form_validation->set_message('_screenName_check', '系统已经存在一个为 '.$str.' 的昵称');
			
			return FALSE;
		}
			
		return TRUE;
	}
	
	/**
	 * 用户公共访问入口
	 *
	 * 
	 *
	 */
	public function user()
	{
		if($this->uri->segment(4) == FALSE)
		{	
			$this->_add_user();
		}		
	}

	/**
     * 添加一个用户
     * 
     * @access 	private
     * @return 	void
     */
	private function _add_user()
	{
		$this->_data['title'] = '添加用户';
		$this->_data['focus'] = 'add_user';
		$this->_data['group'] = 'contributor';
			
		$this->_load_validation_rules();
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('admin/add_user',$this->_data);
		}
		else
		{
			$this->users_mod->add_user(
				array(
					'username' 		=>	$this->input->post('username',TRUE),
					'password' 	=>	$this->input->post('password',TRUE),
					'mail'		=>	$this->input->post('mail',TRUE),
					'url'		=>	$this->input->post('url',TRUE),
					'screenName'=>	($this->input->post('screenName'))?$this->input->post('screenName',TRUE):$this->input->post('uname',TRUE),
					'created'	=>	time(),
					'activated'	=>	0,
					'logged'	=>	0,
					'group'		=>	$this->input->post('group',TRUE)
				)
			);
			
			$this->session->set_flashdata('success', '成功添加一个用户账号');
			redirect('admin/users/user');
		}
	}

	/**
	 * 用户管理
	 *
	 * $access public
	 *
	 */
	 public function manage()
	 {
		$this->_data['title'] = '仪表盘';	
		$this->_data['focus'] = 'user_manage';
		$this->load->view('admin/manage_users', $this->_data);
	 }
}
