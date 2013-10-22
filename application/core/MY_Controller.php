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
 * ProNoz MY_Controller Class
 *
 * STBLOG 后台父控制器
 *
 * @package		PRONOZ
 * @subpackage		Controller
 * @category		Controller
 * @author		Eugene <nozwang@gmail.com>
 */
class PN_Auth_Controller extends CI_Controller 
{

    function __construct() 
    {
		
        parent::__construct();
            
        /** 检查登陆 */		
        if( ! $this->auth->hasLogin())
        {
            $this->auth->process_logout();
            redirect('admin/login?ref='.urlencode($this->uri->uri_string()));
        }

            
        /** 加载后台控制器公共库 */
        $this->load->library('user');
        
        /** 加载后台控制器公共模型 */
        $this->load->model('users_mod');
		
    }
}

class PN_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        
        /** 使用前台模板*/
        $this->load->switch_theme_on();  

    }

}
/* End of file MY_Controller.php */
/* Location: ./application/libraries/MY_Controller.php */
