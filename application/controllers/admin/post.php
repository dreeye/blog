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
 * ProNoz Post Controller Class
 *
 * 文章控制器
 *
 * @package		PRONOZ
 * @subpackage	Models
 * @category	Models
 * @author		Eugene Wang <nozwang@gmail.com>
 */

class Post extends PN_Auth_Controller
{

	//给view传递的数据		
	private $_data = array();
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('metas_mod');
        $this->load->model('posts_mod');
	}
	
	/**
     * 默认执行函数
     *
     * @access public
     * @return void
     */
	public function index()
	{
		redirect('admin/post/write');
	}

	/**
     * 函数转发
     *
     * @access public
     * @return void
     */
	public function write()
	{
		if (FALSE === $this->uri->segment(4))
		{
			$this->_write();
		}
		else
		{
			$pid = $this->security->xss_clean($this->uri->segment(4));
			is_numeric($pid)?$this->_edit($pid):show_error('禁止访问：危险操作');
		}
	}

	public function _write()
	{
		
		/*编辑器*/
		$this->_data['js_files'] = array('ckeditor/ckeditor','ckfinder/ckfinder','js/edit');
		/*title*/
		$this->_data['title'] = '添加文章';
		$this->_data['page_title'] = '添加文章';
		/*导航焦点*/
		$this->_data['focus'] = 'write';
			
		/*获取所有分类*/
		$this->_data['all_categories'] = $this->metas_mod->list_metas('category');
		
		/** validation rules */
		$this->_load_validation_rules();
		
		/** validation passed or failed? */
		if($this->form_validation->run() === FALSE)
		{
			/** validation failed */
			$this->form_validation->month = date('n');
			$this->form_validation->day = date('j');
			$this->form_validation->year = date('Y');
			$this->form_validation->hour = date('G');
			$this->form_validation->minute = date('i');
			
			$this->load->view('admin/post', $this->_data);
		}
		else
		{  
    //       echo '<pre>';print_r($_POST);echo '</pre>'; exit();  
            /** 插入文章*/
            $this->_insert_post();
		}
	}
    
    private function _insert_post()
    {
        /** 获取title,content*/
        $content = $this->_get_form_data();
        /** 前台显示创建时间*/
        $content['created'] = $this->_get_created_time();
        /** 获取状态*/
        $content['status'] = $this->input->post('status', TRUE);        
        $insert_struct = array(
            'title'     =>  empty($content['title'])     ? 'NULL'    : $content['title'],
            'content'   =>  empty($content['content'])   ? 'NULL'    : $content['content'],
            'created'   =>  empty($content['created'])   ? now()     : $content['created'],
            'modified'  =>  now(),
            'status'    =>  $content['status'] == 0    ? 'public'  : 'draft'

        );
        /** 插入主要数据*/
        $insert_id = $this->posts_mod->add_post($insert_struct);

        /** 更新类别 slug tag*/
        if($insert_id > 0)
        {
            $this->_apply_slug($insert_id);
			/** 插入分类 */
            $this->_set_categories($insert_id, $content['category'], false, 'public' == $insert_struct['status']);
            /** 插入标签 */
            $this->_set_tags($insert_id, empty($content['tags']) ? NULL : $content['tags'], false, 'public' == $insert_struct['status']);
        }
      // echo '<pre>';print_r($insert_struct['status']);echo '</pre>';exit(); 

		if($content['status'] == 1)
		{
			$this->session->set_flashdata('success', '草稿"'.$content['title'].'"已经保存');
			redirect('admin/post/write'.'/'.$insert_id);
		}
		else
		{
			$this->session->set_flashdata('success', '文章 <b>'.$content['title'].'</b> 已经被创建');
			redirect('admin/post/manage');
		}
        
    }
    
    /**
     * 设置分类
     * 
     * @access public
     * @param integer $cid 内容id
     * @param array $categories 分类id的集合数组
     * @param boolean $count 是否参与计数
     * @return integer
     */
    public function _set_categories($pid, $categories = array(), $before_count = true, $after_count = true)
    {
        /** 遍历分类数组去掉空格，保证分类名称不重复*/
        $categories = array_unique(array_map('trim', $categories));
        
        /** 取出已有meta */
        $this->metas_mod->get_metas($pid);
        /** 抽取已有category的mid ,修改文章时用*/
        $exist_categories = Common::array_flatten($this->metas_mod->metas['category'], 'mid');

        /** 删除已有category, 修改文章时用*/
        if ($exist_categories) 
        {
            foreach ($exist_categories as $category) 
            {
                $this->metas_mod->remove_relationship_strict($pid, $category);
                
                /** 已有的category标签count字段-1*/
                if ($before_count) 
                {
                    $this->metas_mod->meta_num_minus($category);
                }
            }
        }
        
        /** 插入新的category */
        if ($categories) 
        {
            foreach ($categories as $category) 
            {
                /** 如果分类不存在 */
                if (!$this->metas_mod->get_meta('BYID', $category)) 
                {
                    continue;
                }
            
                $this->metas_mod->add_relationship(array('pid' => $pid,'mid' => $category));
               
                /** count+1*/ 
                if ($after_count) 
                {
                    $this->metas_mod->meta_num_plus($category);
                }
            }
        }
    }

    /**
     * 设置内容标签
     * 
     * @access public
     * @param integer $cid
     * @param string $tags
     * @param boolean $count 是否参与计数
     * @return string
     */
    private function _set_tags($pid, $tags, $before_count = true, $after_count = true)
    {
        $tags = str_replace('，', ',', $tags);
        $tags = array_unique(array_map('trim', explode(',', $tags)));
        
        /** 取出已有meta */
        $this->metas_mod->get_metas($pid);

        /** 取出已有tag的mid */
        $exist_tags = Common::array_flatten($this->metas_mod->metas['tag'], 'mid');
        
        /** 删除已有tag 编辑时候用*/
        if ($exist_tags) 
        {
            foreach ($exist_tags as $tag) 
            {
                $this->metas_mod->remove_relationship_strict($pid, $tag);
                
                if ($before_count) 
                {
                    $this->metas_mod->meta_num_minus($tag);
                }
            }
        }
        
        /** 返回本次插入（包括新tag）tag */
        $insert_tags = $this->metas_mod->scan_tags($tags);
        
        /** 插入tag */
        if ($insert_tags) 
        {
            foreach ($insert_tags as $tag) 
            {
                $this->metas_mod->add_relationship(array('pid' => $pid,'mid' => $tag));
                
                if ($after_count)
                {
                    $this->metas_mod->meta_num_plus($tag);
                }
            }
        }
    }
    
    /**
    *文章操作
    *
    *
    *
    */ 
    public function operate()
    {
        $action = $this->input->post('do');
        switch ($action)
        {
            case 'no':
                $this->_no();
                break;

            case 'draft':
                $this->_draft();
                break;
            case 'trash':
                $this->_trash();
                break;
            default:
                show_404();
                break;
        } 
    }

    private function _no()
    {
        $this->session->set_flashdata('error','没有任何操作');
        redirect('admin/post/manage'); 
    }

    private function _draft()
    {
        $pids = $this->input->post('pid',TRUE);

        if(isset($pids) && is_array($pids))
        {
            $draft = 0;
            foreach($pids as $pid)
            {
                if($this->posts_mod->update_post($pid,array('status' => 'draft')))
                {
                    $draft++;
                }  
            }
        }
        
        ($draft > 0)
            ?$this->session->set_flashdata('success','被选文章已移至草稿箱')
            :$this->session->set_flashdata('error','没有选中任何文章');
            
        redirect('admin/post/manage/draft'); 
                    
    }
    private function _trash()
    {
        $pids = $this->input->post('pid',TRUE);

        if(isset($pids) && is_array($pids))
        {
            $trash = 0;
            foreach($pids as $pid)
            {
                if($this->posts_mod->update_post($pid,array('status' => 'trash')))
                {
                    $trash++;
                }  
            }
        }
        
        ($trash > 0)
            ?$this->session->set_flashdata('success','被选文章已移至回收站')
            :$this->session->set_flashdata('error','没有选中任何文章');
            
        redirect('admin/post/manage/draft'); 
                    
    }

    /**
    *文章列表
    *
    *
    *
    */ 
    public function manage($status = 'public')
    {
        $this->_data['focus'] = 'manage';
        $this->_data['title'] = '文章管理';

        /** 文章数量*/
        $this->_data['draft_count'] = $this->posts_mod->get_posts('draft')->num_rows;
        $this->_data['public_count'] = $this->posts_mod->get_posts('public')->num_rows;

        /** 记录各分类的文章总数*/        
        if($status == 'public')
        {
            $posts_count = $this->_data['public_count'];
        }
        elseif($status == 'draft')
        {
            $posts_count = $this->_data['draft_count'];
        }
        else
        {
            show_404();
        }
        
        /** 加载分页类*/
		$page = $this->input->get('p',TRUE);
		$page = (!empty($page) && is_numeric($page)) ? intval($page) : 1;
		$limit = 12;
		$offset = ($page - 1) * $limit;
        if($posts_count > $limit)
        {
            $this->load->library('dpagination');
            $this->dpagination->currentPage($page);
            $this->dpagination->items($posts_count);
            $this->dpagination->limit($limit);
            $this->dpagination->adjacents(2);
            $this->dpagination->target(site_url('admin/post/manage/'.$status.'?'));
            $this->dpagination->parameterName('p');
            $this->dpagination->nextLabel('下一页');
            $this->dpagination->PrevLabel('上一页');
            $this->dpagination->showCounter(TRUE);
            $pagination = $this->dpagination->getOutput();
            $this->_data['pagination'] = $pagination;
        }

        /** 调出文章*/
        $posts = $this->posts_mod->get_posts($status,$limit,$offset);
        if($posts->num_rows() > 0)
        {
            foreach($posts->result() as $post)
            {
                /** 根据每篇文章pid获取分类和标签*/
                $this->metas_mod->get_metas($post->pid);
                $post->categories = $this->metas_mod->metas['category'];
                $post->tags = $this->metas_mod->metas['tag'];
            }
        }
        $this->_data['post_list'] = $posts;
        $this->_data['status'] = $status;
        $this->load->view('admin/manage',$this->_data); 
    }

	/**
     * 修改一个日志（与用户交互）
     *
     * @access private
     * @return void
     */
	private function _edit($pid)
	{
        
        $this->_data['title'] = '编辑文章';    
        $this->_data['focus'] = 'write';    
		/** get post data **/
		$post_db = $this->posts_mod->get_post_by_id('pid', $pid);
		
        /** test if it exists or not **/
		if(empty($post_db))
		{
			show_error('发生错误：文章不存在或已被删除。');
			exit();
		}
		
		//获取文章的分类和标签
		$this->metas_mod->get_metas($pid);
		$pop_categories = Common::array_flatten($this->metas_mod->metas['category'], 'mid');
		$pop_tags = Common::format_metas($this->metas_mod->metas['tag'], ',' , FALSE);
		
        //populated the rest data to the view
		$this->_data['page_title'] = ($post_db->status=='public')?'编辑文章-'.$post_db->title:'编辑草稿-'.$post_db->title;
		$this->_data['all_categories'] = $this->metas_mod->list_metas('category');
		$this->_data['all_tags'] = $this->metas_mod->list_metas('tag');
		$this->_data['pid'] = $pid;
		$this->_data['p_title'] = $post_db->title;
		$this->_data['content'] = $post_db->content;
		$this->_data['post_category'] = $pop_categories;
		$this->_data['created'] = $post_db->created;
		$this->_data['slug'] = $post_db->slug;
		$this->_data['tags'] = $pop_tags;
		
		
		//validation stuff
		$this->_load_validation_rules();
		$this->form_validation->month = date('n', $post_db->created);
		$this->form_validation->day = date('j',$post_db->created);
		$this->form_validation->year = date('Y', $post_db->created);
		$this->form_validation->hour = date('G', $post_db->created);
		$this->form_validation->minute = date('i', $post_db->created);
		
		//validation passed or failed?
		if($this->form_validation->run() === FALSE)
		{
            /*编辑器*/
		    $this->_data['js_files'] = array('ckeditor/ckeditor','ckeditor/adapters/jquery','ckfinder/ckfinder','js/edit');
			$this->load->view('admin/post',$this->_data);
		}
		else
		{
			$this->_update_post($pid, $post_db);	
		}

    }

	/**
     * 修改一个日志（与数据库交互）
     *
     * @access private
     * @return void
     */
	private function _update_post($pid, $exist_post)
    {
        /** 获取title,content*/
        $content = $this->_get_form_data();
        /** 前台显示创建时间*/
        $content['created'] = $this->_get_created_time();
        /** 获取状态*/
        $content['status'] = $this->input->post('status', TRUE);        
        $update_struct = array(
            'title'     =>  empty($content['title'])     ? 'NULL'    : $content['title'],
            'content'   =>  empty($content['content'])   ? 'NULL'    : $content['content'],
            'created'   =>  empty($content['created'])   ? now()     : $content['created'],
            'modified'  =>  now(),
            'status'    =>  $content['status'] == 0    ? 'public'  : 'draft'

        );
        /** 插入主要数据*/
        $updated_rows = $this->posts_mod->update_post($pid,$update_struct);
		
        /** 应用缩略名 */
		$this->_apply_slug($pid);

		if($updated_rows >0)
		{
			/** 插入分类 */
            $this->_set_categories($pid, $content['category'], 'public' == $exist_post->status, 'public' == $update_struct['status']);
            
            /** 插入标签 */
            $this->_set_tags($pid, empty($content['tags']) ? NULL : $content['tags'], 'public' == $exist_post->status, 'public' == $update_struct['status']);
		}

        
		if($content['status'] == 1)
		{
			$this->session->set_flashdata('success', '草稿"'.$content['title'].'"已经保存');
			redirect('admin/post/write'.'/'.$pid);
		}
		else
		{
			$this->session->set_flashdata('success', '文章 <b>'.$content['title'].'</b> 修改成功');
			redirect('admin/post/manage');
		}
    }
    
    /** 获取表单内容*/
    private function _get_form_data()
    {
        return array(
            'title' => $this->input->post('p_title', TRUE),
            'content' => $this->input->post('content'),
            'category' => $this->input->post('category'),
            'tags' => $this->input->post('tags'),
        );
    }
	
	
    /**
     * 获取创建时间
     *
     *	TODO: 时区设置
     *	
     * 
     * @access private
     * @return integer
     */
	private function _get_created_time()
	{
	    $created = now();
	    
		$second = 0;
		$min = intval($this->input->post('minute',TRUE));
		$hour = intval($this->input->post('hour',TRUE));
            
        $year = intval($this->input->post('year',TRUE));
        $month = intval($this->input->post('month',TRUE));
        $day = intval($this->input->post('day',TRUE));
        
        return mktime($hour, $min, $second, $month, $day, $year);
	}

    /**
     * 为内容应用缩略名
     * 
     * @access private
     * @param string $slug 缩略名
     * @return string
     */
	private function _apply_slug($pid)
	{
		$slug = $this->input->post('slug', TRUE);
		$slug = (!empty($slug)) ? $slug : NULL;
		$slug = Common::repair_slugName($slug,$pid);
	    /** 确定slug不重复后更新slug字段*/	
		$this->posts_mod->update_post($pid,array('slug' => $this->posts_mod->get_slug_name($slug,$pid)));
	}



   /**
    * 加载验证规则
    *
    * @access private
    * @return void
    */
	private function _load_validation_rules()
	{
		$this->form_validation->set_rules('p_title', '标题', 'required|trim|htmlspecialchars');
		$this->form_validation->set_rules('content', '正文', 'trim|required');
		$this->form_validation->set_rules('category[]', '分类', 'required|trim');	
		$this->form_validation->set_rules('slug', '缩略名', 'trim|alpha_dash|htmlspecialchars');
		$this->form_validation->set_rules('tags', '标签', 'trim|htmlspecialchars');
	}

    /**
     * ajax tag 
     *
     */
    public function ajax_tag()
    {
		/*获取所有tag*/
        $query = $this->metas_mod->list_metas('tag');
        $data = array();
        foreach($query->result_array() as $val)
        {
            $json = array();
            $json['name'] = $val['name'];
            $json['value'] = $val['name'];
            $data[] = $json;
        }
        header("Content-type: application/json");
        echo json_encode($data);
    }	
}
