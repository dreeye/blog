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
 * ProNoz Metas Model Class
 *
 * 用户操作Model
 *
 * @package		PRONOZ
 * @subpackage	Models
 * @category	Models
 * @author		Eugene <nozwang@gmail.com>
 */

class Metas_mod extends CI_Model
{
	const TBL_METAS = 'metas';
	const TBL_RELATIONSHIPS = 'relationships';
	const TBL_POSTS = 'posts';
    
    /**
    * 内容类型：分类/标签
    * 
    * @access private
    * @var array
    */
	private $_type = array('category','tag');
	
    /*
    * @access public
    * @var mixed
    */
    public $metas = NULL;

	public function __construct()
	{
		parent::__construct();
		log_message('debug', "PRONOZ: Metas Model Class Initialized");
	}
	
    /**
     * 检查meta是否存在
     * 
     * @access public
	 * @param string - $type 类型
	 * @param string - $key 字段名
	 * @param string - $value 内容
	 * @param int    - $exclude_mid 要排除的mid
     * @return bool
     */
	public function check_exist($type = 'category', $key = 'name', $value = '', $exclude_mid = 0)
	{
		$this->db->select('mid')->from(self::TBL_METAS)->where($key, trim($value));
		
		if(!empty($exclude_mid) && is_numeric($exclude_mid))
		{
			$this->db->where('mid !=', $exclude_mid);	
		}
		
		if($type && in_array($type, $this->_type))
		{
			$this->db->where('type', $type);
		}
		
		$query = $this->db->get();
		
		$num = $query->num_rows();
		
		$query->free_result();
		
		return ($num > 0) ? TRUE : FALSE;	
	}
	
	// -----------------------CRUD---------------------------------------------
	/**
     * 添加meta
     * 
     * @access public
	 * @param  array $meta_data  内容
     * @return boolean 成功与否
     */
	public function add_meta($meta_data)
	{
		$this->db->insert(self::TBL_METAS, $meta_data);
		
		return ($this->db->affected_rows() ==1) ? $this->db->insert_id() : FALSE;
	}
	
	/**
    * 修改内容
    * 
    * @access public
	* @param int - $data 内容信息
    * @return boolean - success/failure
    */	
	public function update_meta($mid, $data)
	{
		$this->db->where('mid', intval($mid));
		$this->db->update(self::TBL_METAS, $data);
		
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}
	
	/**
     *  获取元数据
     * 
     *  @access public
	 *	@param string $type 元数据类别：｛"category"|"tag"|"byID"｝
	 *	@param string $name 元数据名称
	 *	@return object － result object
     */
	public function get_meta($type = 'category', $name = '')
	{
		if(empty($name)) exit();
		
		//以type和name字段获取。如果$type='BYID',就不执行下面这个if语句
		if($type && in_array($type, $this->_type))
		{
			$this->db->where(self::TBL_METAS.'.type',$type);
			$this->db->where(self::TBL_METAS.'.name',$name);
		}
		//以mid获取。
		if($type && strtoupper($type) == 'BYID')
		{
			$this->db->where(self::TBL_METAS.'.mid', intval($name));
		}
		
		return $this->db->get(self::TBL_METAS)->row();
	}
    /**
     * 检查分类或标签在meta表中是否存在 
     *
     *
     *
     */	
	public function get_meta_by_slug($slug)
	{
		$this->db->where(self::TBL_METAS.'.slug', $slug);
		
		return $this->db->get(self::TBL_METAS)->row();
	}

   /**
    * 获取所有metas
    * 
    * @access public
    * @param  strint $type 类型
    * @return object
    */
	public function list_metas($type = 'category', $limit = NULL, $offset = NULL)
	{
		if(in_array($type, $this->_type))
		{	
			$this->db->where(self::TBL_METAS.'.type', $type);
		}
        if($limit && is_numeric($limit))
        {
            $this->db->limit(intval($limit));
        }

        if($offset && is_numeric($offset))
        {
            $this->db->offset(intval($offset));
        }

		
		return $this->db->get(self::TBL_METAS);
	}
	
   /**
    * 删除一个内容
    * 
    * @access public
	* @param int - $mid 内容id
    * @return boolean - success/failure
    */
	public function remove_meta($mid)
	{
		$this->db->delete(self::TBL_METAS, array('mid' => intval($mid))); 
		
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}
	
	/**
     * 删除关系
     * 
     * @access public
	 * @param  string   $column  唯一PK
	 * @param  int $value  值
     * @return boolean 成功与否
     */
	public function remove_relationship($column = 'pid', $value)
	{
		$this->db->delete(self::TBL_RELATIONSHIPS, array($column => intval($value))); 
	
		return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
	}

    /**
    * 根据文章pid返回该文章的分类和标签
    * @param int $pid 文章id
    * @param boolean $return 是否返回值
    */	
    public function get_metas($pid = 0, $return = FALSE)
    {
        $this->metas = NULL;
        
        $metas = array();
       
        /** 获取文章对应的分类和标签名(用于编辑文章)*/ 
        if(!empty($pid))
        {
            $this->db->select(self::TBL_METAS.'.*,'.self::TBL_RELATIONSHIPS.'.pid');
            $this->db->join(self::TBL_RELATIONSHIPS,self::TBL_RELATIONSHIPS.'.mid = '.self::TBL_METAS.'.mid');
            $query = $this->db->get_where(self::TBL_METAS,array('pid'=>intval($pid)));
        }
        else
        {
            $query = $this->db->get(self::TBL_METAS);
        }
        
        
        if ($query->num_rows() > 0)
        {
            $metas = $query->result_array();
        }

        $query->free_result();
        
        /** 如果第二个参数是true，返回取出的文章分类和标签,如果是false就放到$this->metas[]*/        
        if($return)
        {
            return $metas;
        }
        
        /** $this->metas['category'], $this->metas['tag']*/    
        foreach($this->_type as $type)
        {
            $this->metas[$type] = array();
        }
    
        if(!empty($metas))
        {
            foreach($metas as $meta)
            {
                foreach($this->_type as $type)
                {
                    //category=category;tag=tag
                    if($type == $meta['type'])
                    {
                        //把该本章的category和tag内容都放到$this->metas['category'],$this->metas['tag']中
                        array_push($this->metas[$type], $meta);
                    }
                }
            }   
        }
    }

    public function add_relationship($relation_data)
    {
        $this->db->insert(self::TBL_RELATIONSHIPS, $relation_data);
        
        return ($this->db->affected_rows()==1) ? $this->db->insert_id() : FALSE;
    }
    
    public function remove_relationship_strict($pid, $mid)
    {
        $this->db->delete(self::TBL_RELATIONSHIPS,
                          array(
                            'pid'=> intval($pid),
                            'mid'=> intval($mid)
                         )); 
        
        return ($this->db->affected_rows() ==1) ? TRUE : FALSE;
    }

    /** meta count-1*/
    public function meta_num_minus($mid)
    {
        $this->db->where('mid',$mid);
        $this->db->update(self::TBL_METAS,array('count'=>'count'-1));
    }
    
    /** meta count+1*/
    public function meta_num_plus($mid)
    {
        $this->db->where('mid',$mid);
        $this->db->update(self::TBL_METAS,array('count'=>'count'+1));
    }

    public function scan_tags($inputTags)
    {
        $tags = is_array($inputTags) ? $inputTags : array($inputTags);
        $result = array();
        
        foreach ($tags as $tag) 
        {
            if (empty($tag)) 
            {
                continue;
            }
        
        	$row = $this->db->select('*')
        					->from(self::TBL_METAS)
        					->where('type','tag')
        					->where('name',$tag)
        					->limit(1)
        					->get()
        					->row();
            
            if ($row) 
            {
                $result[] = $row->mid;
            } 
            else 
            {
                $slug = Common::repair_slugName($tag);
                
                if ($slug) 
                {
                    $result[] = $this->add_meta(array(
			                        'name'  =>  $tag,
			                        'slug'  =>  $slug,
			                        'type'  =>  'tag',
			                        'count' =>  0,
			                        'order' =>  0,
			                    ));
                }
            }
        }
        
        return is_array($inputTags) ? $result : current($result);
    }
}
