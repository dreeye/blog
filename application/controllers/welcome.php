<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends PN_Controller {

	private $_data = array();
  
    private $_posts = array();
    
    public function  __construct()
    {
        
        parent::__construct();
        $this->load->model('posts_mod');
        $this->load->model('metas_mod');
        $this->_data['css_files'] = array('css/index','css/styles/shCore','css/styles/shThemeEmacs');
        $this->_data['js_files'] = array('js/shCore','js/shAutoloader','js/syntax','js/jquery.scrolltotop');
        /** 最新5篇文章*/
        $this->_data['new_posts'] = $this->posts_mod->get_posts('public',5);
        /** 分类*/
        $this->_data['cate_list'] = $this->metas_mod->list_metas('category');
    }
 
    public function index()
	{
        /** 加载分页类*/
		$page = $this->input->get('p',TRUE);

        /** 如果$page不是int或小于0，为1*/
		$page = (!empty($page) && is_numeric($page) && $page > 0) ? $page : 1;

        /** 首页文章的分页*/
		$limit = 4;
		$offset = ($page - 1) * $limit;
        $this->_data['public_count'] = $this->posts_mod->get_posts('public')->num_rows;
        $posts_count = $this->_data['public_count'];
        if($posts_count > $limit)
        {
            $this->load->library('dpagination');
            $this->dpagination->currentPage($page);
            $this->dpagination->items($posts_count);
            $this->dpagination->limit($limit);
            $this->dpagination->adjacents(2);
            $this->dpagination->target(site_url(''));
            $this->dpagination->parameterName('p');
            $this->dpagination->nextLabel('下一页');
            $this->dpagination->PrevLabel('上一页');
            $this->dpagination->showCounter(TRUE);
            $pagination = $this->dpagination->getOutput();
            $this->_data['pagination'] = $pagination;
        }
        /** 当前页数和总页数*/
        $this->_data['total_pages'] = $posts_count/$limit;
        $this->_data['page'] = $this->dpagination->page;
        /** 调出文章*/
        $this->_posts = $this->posts_mod->get_posts('public',$limit,$offset);
        if($this->_posts->num_rows() > 0)
        {
            $this->_prepare_posts();
        }
        $this->_data['post_list'] = $this->_posts;
		$this->load->view('index',$this->_data);
	}
    
    public function category($slug,$page=1)
    {
		if(empty($slug) || !is_numeric($page))
		{
			redirect(site_url());
		}

		
		$category = $this->metas_mod->get_meta_by_slug(trim($slug));
		if(!$category)
		{
			show_error('分类不存在或已被管理员删除');
			exit();
		}
        /** 获取当前筛选的category文章*/
		$page = $this->input->get('p',TRUE);
		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
		$limit = 4;
		$offset = ($page - 1) * $limit;
        $this->_data['num'] = $this->posts_mod->get_posts_by_meta($slug,'category','public','posts.*')->num_rows();
        if($this->_data['num'] > $limit)
        {
            $this->load->library('dpagination');
            $this->dpagination->currentPage($page);
            $this->dpagination->items($this->_data['num']);
            $this->dpagination->limit($limit);
            $this->dpagination->adjacents(2);
            $this->dpagination->target(site_url('/category/'.$slug));
            $this->dpagination->parameterName('p');
            $this->dpagination->nextLabel('下一页');
            $this->dpagination->PrevLabel('上一页');
            $this->dpagination->showCounter(TRUE);
            $pagination = $this->dpagination->getOutput();
            $this->_data['pagination'] = $pagination;
        }
        $this->_posts = $this->posts_mod->get_posts_by_meta($slug,'category','public','posts.*',$limit,$offset);
        /** 配置文章*/
        $this->_data['post_list'] = $this->_prepare_posts();
        $this->_data['type'] = '分类';
        $this->_data['type_name'] = $category->name;
        $this->_data['title'] = $category->name;
        $this->_data['post_list'] = $this->_prepare_posts();
        
		$this->load->view('index',$this->_data);
    }
    
    public function tag($slug,$page=1)
    {
		if(empty($slug) || !is_numeric($page))
		{
			redirect(site_url());
		}
        /** CI传中文参数会加密，需要解密*/
        $slug = rawurldecode($slug); 		
		$tag = $this->metas_mod->get_meta_by_slug(trim($slug));
		if(!$tag)
		{
			show_error('标签不存在或已被管理员删除');
			exit();
		}
        /** 获取当前筛选的tag文章*/
		$page = $this->input->get('p',TRUE);
		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
		$limit = 4;
		$offset = ($page - 1) * $limit;
        $this->_data['num'] = $this->posts_mod->get_posts_by_meta($slug,'tag','public','posts.*')->num_rows();
        if($this->_data['num'] > $limit)
        {
            $this->load->library('dpagination');
            $this->dpagination->currentPage($page);
            $this->dpagination->items($this->_data['num']);
            $this->dpagination->limit($limit);
            $this->dpagination->adjacents(2);
            $this->dpagination->target(site_url('/tag/'.$slug));
            $this->dpagination->parameterName('p');
            $this->dpagination->nextLabel('下一页');
            $this->dpagination->PrevLabel('上一页');
            $this->dpagination->showCounter(TRUE);
            $pagination = $this->dpagination->getOutput();
            $this->_data['pagination'] = $pagination;
        }
        $this->_posts = $this->posts_mod->get_posts_by_meta($slug,'tag','public','posts.*',$limit,$offset);
        /** 配置文章*/
        $this->_data['post_list'] = $this->_prepare_posts();
        $this->_data['type'] = '标签';
        $this->_data['type_name'] = $tag->name;
        $this->_data['title'] = $tag->name;
        $this->_data['post_list'] = $this->_prepare_posts();
		$this->load->view('index',$this->_data);
    }

    /** 给文章加上分类和标签*/
    private function _prepare_posts()
    {
    
        foreach($this->_posts->result() as $post)
        {
            /** 根据每篇文章pid获取分类和标签*/
            $this->metas_mod->get_metas($post->pid);
            $post->categories = $this->metas_mod->metas['category'];
            $post->tags = $this->metas_mod->metas['tag'];
        }
        
        return $this->_posts;
    } 
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
