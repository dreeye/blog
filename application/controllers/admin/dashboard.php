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


class Dashboard extends PN_Auth_Controller
{
	

	public function __construct()
	{
		parent::__construct();
		$this->_data['title'] = '仪表盘';	
	}

	public function index()
	{
		$this->load->view('admin/dashboard', $this->_data);
	}
    
    public function tiao()
    {
        redirect();
    }
}
