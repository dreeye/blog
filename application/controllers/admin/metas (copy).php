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
 * ProNoz Category Controller Class
 *
 * 控制台控制器
 *
 * @package		PRONOZ
 * @subpackage	Models
 * @category	Models
 * @author		Eugene <nozwang@gmail.com>
 */

class Metas extends PN_Auth_Controller
{

	/**
	* 用户数据
	* @access private
	* @var array
	*/
	private $_data = array();

	/**
	* 当前mid，默认是check时候掠过mid=0，meta表从1开始
        * @access private
	* @var array
	*/
	private $_mid = 0;
	
	/**
	* 当前操作Meta类型,validation时候需要
	*
     	* @access private
     	* @var string
     	*/
	private $_type = 'category';
	
	/**
     	* 中英文转化表
     	*
     	* @access private
     	* @var array
     	*/
	private $_map = array('category' => '分类', 'tag' => '标签');


	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('metas_mod');
	}
	
	/*
	*入口跳转到manage方法	
	*	
	*/
	public function index()
	{
		redirect('admin/metas/manage');
	}

	/**
	 * 验证规则
	 *
	 *
	 * @access private
	 * @return void
	 */
	private function _load_validation_rules()
	{	

		$this->form_validation->set_rules('name', '名称', 'required|trim|callback__name_check|callback__name_to_slug|htmlspecialchars');
		if('category' == $this->_type)
		{
			$this->form_validation->set_rules('slug', '缩略名', 'trim|callback__slug_check|alpha_dash|htmlspecialchars');
		}
		else
		{
			$this->form_validation->set_rules('slug', '缩略名', 'trim|callback__slug_check|htmlspecialchars');	
		}
		
		$this->form_validation->set_rules('intro', '描述', 'trim|htmlspecialchars');	
	}
	
	/**
	 * 分类公共入口文件
	 * 
	 * @access public
	 * @param $type 分类或标签类型，默认category
	 * @param $mid 分类或标签id
	 */
	public function manage($type='category', $mid=NULL)
	{	
		$this->_data['focus'] = ($type=='category') ? 'category' : 'tag';
		//title
		$this->_data['title'] = ($type == 'category') ? '分类目录' : '标签';
			
		//如果获取到mid，为编辑模式
		if($mid && is_numeric($mid))
		{
			//title
		    $this->_data['title'] = ($type == 'category') ? '编辑分类' : '编辑标签';
		    //当前编辑的id
			$this->_data['mid'] = $mid;
			
			//以mid获取meta表数据
			$meta = $this->metas_mod->get_meta('BYID', $mid);
			if($meta)
			{
				$this->_data['name'] = $meta->name;
				$this->_data['slug'] = $meta->slug;
				$this->_data['intro'] = $meta->description;
			}
			else
			{
				//如果mid错误，跳转.判断下是否从删除数据那里跳转过来的
				if( ! $this->session->flashdata('success'))
				{
					$this->session->set_flashdata('error','没有找到相应的数据');
				}
				else
				{
					//在编辑状态中删除数据
					$this->session->set_flashdata('success',$this->session->flashdata('success'));
					
				}
				
				redirect('admin/metas/manage/'.$type);	
			}
			
			
			unset($meta);
		}		
		//validation时候根据type不同有不同的验证方式，category的slug会多alpha_dash
		$this->_type = $type;
		//需要validation name字段是否重复，但在修改时候需要排除本身mid 
		$this->_mid = $mid;
		
		$this->_load_validation_rules();
		
		if ($this->form_validation->run() == FALSE)
		{
			
			//操作类型，来判断调用哪个form模板 
			$this->_data['type'] = $type;
			//获取数据列表
			$this->_data[$type] = $this->metas_mod->list_metas($type);
			
			$this->load->view('admin/manage_metas', $this->_data);
		}
		else
		{
			$name	= $this->input->post('name',TRUE);
			$slug	= $this->input->post('slug',TRUE);
			$intro 	= $this->input->post('intro',TRUE);	
			$action = $this->input->post('do',TRUE);	
			
			$data = array(
				'name' => $name,
				'type' => $type,
				'slug' => Common::repair_slugName((!empty($slug))?$slug:$name),
				'description' => (!$intro)? NULL : $intro
			);
			
			
			if('insert' == $action)
			{
				$this->metas_mod->add_meta($data);
				
				$this->session->set_flashdata('success', $this->_map[$type].'添加成功');
			}
			
			if('update' == $action)
			{
			//	print_r($mid.$data);exit();
				$this->metas_mod->update_meta($mid, $data);
				
				$this->session->set_flashdata('success', $this->_map[$type].'更新成功');
			}
			
			go_back();
		}
		
	}
	
	
	
	

	 /**
     * 回调函数：检查Name是否唯一
     * 
     * @access public
     * @param $str 输入值
     * @return bool
     */
	public function _name_check($str)
	{
		//print_r(13);exit();
		if($this->metas_mod->check_exist($this->_type, 'name', $str, $this->_mid))
		{
			$this->form_validation->set_message('_name_check', '已经存在一个为 '.$str.' 的名称');
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
     * 回调函数：名称不能与已有的缩略名重复
     * 
     * @access public
     * @param $str 输入值
     * @return bool
     */
	public function _name_to_slug($str)
	{
		$slug = Common::repair_slugName($str);
		
        if(empty($slug) || $this->metas_mod->check_exist($this->_type, 'slug',$slug, $this->_mid)) 
        {
        	$this->form_validation->set_message('_name_to_slug', '分类无法转换为缩略名');
        	return FALSE;
        }
        
        return TRUE;
	}
	
	/**
     * 回调函数：检查Slug是否唯一
     * 
     * @access public
     * @param $str 输入值
     * @return bool
     */
	public function _slug_check($str)
	{
		if($this->metas_mod->check_exist($this->_type, 'slug', Common::repair_slugName($str), $this->_mid))
		{
			$this->form_validation->set_message('_slug_check', '已经存在一个为 '.$str.' 的缩略名');
			
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
     * 操作分发
     *
     * @access public
     * @param  string $type 类型
     * @return void
     */
     public function operate($type)
     {
     	$action = $this->input->post('do',TRUE);
     	
     	switch ($action)
     	{
			case 'no':
				$this->_no();
				break;
				     		
     		case 'delete':
     			$this->_remove($type);
     			break;
     			
     	}
     }
     
     /*
	 *  没有选择action    
	 *     
     */
     private function _no()
     {
     	$this->session->set_flashdata('error','没有选择操作');
     	go_back();	
     }
     /**
     * 删除
     *
     * @access private
     * @param  string $type 类型
     * @return void
     */
	private function _remove($type)
	{
		//print_r($_POST);exit();
		$metas = $this->input->post('mid',TRUE);
        $deleted = 0;
        
        if ($metas && is_array($metas)) 
        {
            foreach ($metas as $meta) 
            {
                if($this->metas_mod->remove_meta($meta))
                {
                	$this->metas_mod->remove_relationship('mid',$meta);
                	$deleted ++;
                }
            }
        }
        
        $msg = ($deleted>0) ? $this->_map[$type].'删除成功' : '没有'.$this->_map[$type].'被删除';
        $notify = ($deleted>0) ? 'success':'error';
        
        $this->session->set_flashdata($notify, $msg);
		go_back();
	}
}
