<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends PN_Controller {

	private $_data = array();
   
    public function __construct()
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

    public function index($slug)
    {
        /** 调出文章*/
        if(empty($slug))
        {
            redirect(site_url());
        }

        $post = $this->posts_mod->get_post_by_id('slug',$slug);
        if( ! $post)
        {
            show_404();
        }

        /** 如果文章没有被发布，回首页*/
        if($post && $post->status != 'public')
        {
            redirect();
        } 

        $this->metas_mod->get_metas($post->pid);
        $post->categories = $this->metas_mod->metas['category'];
        $post->tags = $this->metas_mod->metas['tag'];
        $this->_data['title'] = $post->title;
        $this->_data['post'] = $post;
		$this->load->view('post',$this->_data);
    }

}
