<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class login extends CI_Controller
{
	
	/**
	*视图参数
	*	
	*@access private
	*@var array
	*/
	private $_data;
	
	/**
	*referrer
	*不能private，因为模板form要用	
	*@access public
	*@var string
	*/
	public $referrer;


	function __construct()
	{
		parent::__construct();

		$this->load->model('users_mod');
		//加载登录语言
		$this->lang->load('login');

		$this->_get_referrer();

		$this->_data['title'] = '登录';
	}

	private function _get_referrer()
	{
		$ref = $this->input->get('ref', TRUE);
		$this->referrer = (!empty($ref)) ? $ref : 'admin/dashboard';
	}

	public function index()
	{
		
		//如果已经登录，跳转到后台首页
		//之前我尝试在hasLogin为false时直接销毁cookie再返回false，有问题，原因是提交登录信息都会执行此hasLogin,会把flashdata也删除，就没有登录错误提示了
		if($this->auth->hasLogin())
		{
			redirect($this->referrer);
		}

		$submitted 	= $this->input->post('submitted');

		if($submitted)
		{
			
			//验证用户名密码是否正确，返回userdate/FALSE
			$user = $this->users_mod->validate_user(
					$this->input->post('username'),
					$this->input->post('password')
			);
			
            //登录成功并返回用户数据
			if( ! empty($user))
		    {
				
				if($this->auth->process_login($user, $this->input->post('remember')))
				{	
					
					redirect($this->referrer);
				}

			}
			else
			{
				$this->session->set_flashdata('error',lang('login_error'));
				redirect('admin/login?ref='.urlencode($this->referrer));
			}
		}
		
		$this->load->view('admin/login', $this->_data);

	}

	public function logout($redirect = TRUE)
	{
		$this->auth->process_logout($redirect);
	}
}
