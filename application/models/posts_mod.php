<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Posts_mod extends CI_Model
{
    
	const TBL_POSTS = 'posts';
	const TBL_METAS = 'metas';
	const TBL_RELATIONSHIPS = 'relationships';
	const TBL_COMMENTS = 'comments';


	/**
     * 内容状态：发布/草稿
     * 
     * @access private
     * @var array
     */
	private $_post_status = array('public', 'draft');
	
	/**
     * 内容的唯一栏：pid/slug
     * 
     * @access private
     * @var array
     */
	private $_post_unique_field = array('pid','slug');
    
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     *添加新文章
     *
     *
     */
    public function add_post($content_data)
    {
        $this->db->insert(self::TBL_POSTS, $content_data);
        return ($this->db->affected_rows() == 1) ? $this->db->insert_id() : FALSE;
    }  

	/**
    * 修改一个内容
    * 
    * @access public
	* @param int $pid 内容ID
	* @param array   $data 内容数组
    * @return boolean 成功或失败
    */	
	public function update_post($pid,$data)
	{
		$this->db->where('pid', intval($pid));
		$this->db->update(self::TBL_POSTS, $data);
		
		return ($this->db->affected_rows() == 1)?TRUE:FALSE;
	}


	/**
     * 获取合法的slug名称(如果发现slug重复，自动slug后面加数字，知道不重复返回slug)
     * 
     * @access public
	 * @param string $slug slug name
	 * @param int $pid 内容id
     * @return string slug
     */
	public function get_slug_name($slug, $pid)
	{
		$result = $slug;
		$count = 1;
		
		while($this->db->select('pid')->where('slug',$result)->where('pid <>',$pid)->get(self::TBL_POSTS)->num_rows() > 0)
		{
			$result = $slug . '_' . $count;
			$count ++;
		}
		
		return $result;
	}
    
    /**
    * 获取文章列表
    * 
    *
    */
    public function get_posts($status = 'public', $limit = NULL, $offset = NULL)
    {
        if($status && in_array($status,$this->_post_status))
        {
            $this->db->where('status',$status);   
        }
        
        if($limit && is_numeric($limit))
        {
            $this->db->limit(intval($limit));
        }

        if($offset && is_numeric($offset))
        {
            $this->db->offset(intval($offset));
        }

        $this->db->order_by('created','DESC');    
        return $this->db->get(self::TBL_POSTS);
    }

    
	/**
     * 根据唯一键获取单个内容信息
     * 
     * @access public
	 * @param  string $identity 内容标识栏位：{"pid"｜"slug"}
	 * @param  mixed  $value    标识栏位对应的值
     * @return array  内容信息
     */
	public function get_post_by_id($identity, $value)
	{
		if(!in_array($identity, $this->_post_unique_field))
		{
			return FALSE;
		}
		
		$this->db->where($identity, $value);

		return $this->db->get(self::TBL_POSTS)->row();
	}

	/**
     * 根据元数据获取内容
     * 
     * @access public
	 * @param string $meta_slug 	元数据缩略名
	 * @param string $meta_type 	元数据类型：{"category"｜"tag"}
	 * @param string $post_status 	内容状态	 
	 * @param string $fields 	要筛选的栏位值 (optional)
	 * @param int    $limit 		条数 (optional)
	 * @param int    $offset 		偏移量 (optional)
     * @return array - 内容信息
     */	
	public function get_posts_by_meta($meta_slug, $meta_type = 'category', $post_status = 'public', $fields = 'posts.*', $limit = NULL, $offset = NULL)
	{
		$this->db->select($fields);
		$this->db->from('posts,metas,relationships');
		$this->db->where('posts.pid = pn_relationships.pid');
		$this->db->where('posts.status', $post_status);
		$this->db->where('metas.mid = pn_relationships.mid');
		$this->db->where('metas.type',$meta_type);
		$this->db->where('metas.slug',$meta_slug);
		$this->db->order_by('posts.created','DESC');
        	
		if($limit && is_numeric($limit))
		{
			$this->db->limit(intval($limit));
		}
		
		if($offset && is_numeric($offset))
		{
			$this->db->offset(intval($limit));
		}
		
		return $this->db->get();
	}

}
