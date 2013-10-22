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
 * ProNoz Users Model Class
 *
 * 用户操作Model
 *
 * @package		PRONOZ
 * @subpackage	Models
 * @category	Models
 * @author		Eugene <nozwang@gmail.com>
 */

class Users_mod extends CI_Model {
	
	const TBL_USERS = 'users';
	
	/**
     	* 标识用户的唯一键：{"name"|"screenName"|"mail"}
     	* 
     	* @access private
     	* @var array
     	*/
	private $_unique_key = array('username', 'screenName', 'mail');
	
	public function __construct()
    	{
        	parent::__construct();
    	}
    
	/**
	 * 验证管理员登陆用户名和密码
	 * @access public
	 * @param username
	 * @param password
	 * @return userdata/false
	 */
	 public function validate_user($username,$password)
	 {
		$data = FALSE;

		$query = $this->db->get_where(self::TBL_USERS, array('username'=>$username));

		if($query->num_rows() == 1)
		{
			$data = $query->row_array();
		}

		if( ! empty($data))
		{
			//管理员输入的密码单向加密与库中密码比对，返回boolean
			$data = (Common::hash_validate($password, $data['password'])) ? $data : FALSE;
		}

		$query->free_result();

		return $data;
	 }
	
	/**
    * 添加一个用户
    * 
    * @access public
	* @param int - $data 用户信息
    * @return boolean - success/failure
    */	
	public function add_user($data)
	{
		$data['password'] = Common::do_hash($data['password']);
		
        $this->db->insert(self::TBL_USERS, $data);
		
		return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
	}

	 /**
    * 修改用户信息
    * 
    * @access public
	* @param int - $uid 用户ID
	* @param int - $data 用户信息
	* @param int - $cipher_password 密码是否被加密	
    * @return boolean - success/failure
    */	
	public function update_user($uid, $data, $hashed = TRUE)
	{
		if(!$hashed)
		{
		  $data['password'] = Common::do_hash($data['password']);
		}
		
		$this->db->where('uid', intval($uid));
		$this->db->update(self::TBL_USERS, $data);
		
		return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
	}

	/**
     	* 获取单个用户信息
     	* 
     	* @access public
	* @param int $uid 用户id
     	* @return array - 用户信息
     	*/
	public function get_user_by_id($uid)
	{
		$data = array();
		
		$query = $this->db->get_where(self::TBL_USERS, array('uid'=>$uid));

		if($query->num_rows() == 1)
		{
			$data = $query->row_array();
		}

		$query->free_result();
		
		return $data;
	}

	/**
    	* 检查是否存在相同{用户名/昵称/邮箱}
    	* 
    	* @access public
	* @param int - $key {name,screenName,mail}
	* @param int - $value {用户名/昵称/邮箱}的值
	* @param int - $exclude_uid 需要排除的uid
    	* @return boolean - success/failure
    	*/	
	public function check_exist($key = 'username',$value = '', $exclude_uid = 0)
	{
		if(in_array($key, $this->_unique_key) && !empty($value))
		{
			
			$this->db->select('uid')->from(self::TBL_USERS)->where($key, $value);
			
			if(!empty($exclude_uid) && is_numeric($exclude_uid))
			{
				$this->db->where('uid <>', $exclude_uid);	
			}
			
			$query = $this->db->get();
			$num = $query->num_rows();
			
			$query->free_result();
			
			return ($num > 0) ? TRUE : FALSE;
		}
		
		return FALSE;		
	}
}
