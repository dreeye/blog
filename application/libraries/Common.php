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

//--------------------------------------------------------------------------------

/**
 * ProNoz的公共静态类
 *
 * 在autoload.php中自动加载。
 *
 */

class Common 
{


	/**
	 * 管理员登陆输入密码与库中获取加过密的密码比对
	 * @access public
	 * @param string $source 管理员输入的密码
     * @param string $target 根据用户名获取的加过密的密码(如果这个参数为空，说明要加密第一个参数用来修改密码或新生成密码)
     * @return boolean
     */
    public static function hash_validate($source, $target)
    {	
        return (self::do_hash($source, $target) == $target);
    }


	/**
     * 密码的单向加密规则
     * 
     * @access public
     * @param string $string 管理员输入的密码
     * @param string $salt 根据用户名获取的加过密的密码
     * @return string 
     */
    public static function do_hash($string, $salt = NULL)
    {
        //没传过加密的密码，就准备明文密码加密
		if (null === $salt)
		{
		    $salt = substr(md5(uniqid(rand(), true)), 0, PN_SALT_LENGTH);
		}
		else
		{
            //准备两个密码比对
		    $salt = substr($salt, 0, PN_SALT_LENGTH);
		}

    	return $salt . sha1($salt . $string);
    }
    
    /**
	* 修整缩略名
	*
	* @access public
	* @param string $str 需要生成缩略名的字符串
	* @param string $default 默认的缩略名
	* @param integer $maxLength 缩略名最大长度
	* @param string $charset 字符编码
	* @return string
	*/
	public static function repair_slugName($str, $default = NULL, $maxLength = 200,$charset = 'UTF-8')
	{
	    $str = str_replace(array("'", ":", "\\", "/"), "", $str);
	    $str = str_replace(array("+", ",", " ", ".", "?", "=", "&", "!", "<", ">", "(", ")", "[", "]", "{", "}"), "_", $str);
	    $str = trim($str, '_');
	    $str = empty($str) ? $default : $str;
	    
	    return function_exists('mb_get_info') ? mb_strimwidth($str, 0, 128, '', $charset) : substr($str, $maxLength);
	}

    /**
     * 抽取多维数组的某个元素,组成一个新数组,使这个数组变成一个扁平数组
     * 使用方法:
     * <code>
     * <?php
     * $fruit = array(array('apple' => 2, 'banana' => 3), array('apple' => 10, 'banana' => 12));
     * $banana = Common::arrayFlatten($fruit, 'banana');
     * print_r($banana);
     * //outputs: array(0 => 3, 1 => 12);
     * ?>
     * </code>
     *
     * @access public
     * @param array $value 被处理的数组
     * @param string $key 需要抽取的键值
     * @return array
     */
    public static function array_flatten($value = array(), $key)
    {
        $result = array();

        if($value) 
        {
            foreach ($value as $inval) 
            {
                if(is_array($inval) && isset($inval[$key])) 
                {
                    $result[] = $inval[$key];
                } 
                else 
                {
                    break;
                }
            }
        }

        return $result;
    }

	/**
	* 词义化时间
	* 
	* @access public
	* @param string $from 起始时间
	* @param string $now 终止时间
	* @return string
	*/
	public static function dateWord($from, $now)
	{
		//fix issue 3#6 by saturn, solution by zycbob
		
		/** 如果不是同一年 */
        if (idate('Y', $now) != idate('Y', $from)) 
        {
            return date('Y年m月d日', $from);
        }
		
		/** 以下操作同一年的日期 */
		$seconds = $now - $from;
        $days = idate('z', $now) - idate('z', $from);
        
        /** 如果是同一天 */
        if ($days == 0) 
        {
        	/** 如果是一小时内 */
            if ($seconds < 3600) 
            {
            	/** 如果是一分钟内 */
                if ($seconds < 60)
                {
                    if (3 > $seconds) 
                    {
                        return '刚刚';
                    } 
                    else 
                    {
                        return sprintf('%d秒前', $seconds);
                    }
                }

                return sprintf('%d分钟前', intval($seconds / 60));
            }

            return sprintf('%d小时前', idate('H', $now) - idate('H', $from));
        }

		/** 如果是昨天 */
        if ($days == 1) 
        {
            return sprintf('昨天 %s', date('H:i', $from));
        }
        
        /** 如果是前天 */
        if ($days == 2) 
        {
        	return sprintf('前天 %s', date('H:i', $from));
        }

        /** 如果是7天内 */
        if ($days < 7) 
        {
            return sprintf('%d天前', $days);
        }

        /** 超过一周 */
        return date('n月j日', $from);
	}

	/**
     * 格式化metas输出
     * 
     * @access public
	 * @param array - $metas metas内容数组
	 * @param string - $split 分割符
	 * @param boolean - $link 是否输出连接
     * @return string - 格式化输出
     */
	public static function format_metas($metas = array(), $split = ',', $link = true)
    {
    
    	$format = '';
    	
        if ($metas) 
        {
            $result = array();
            
            foreach ($metas as $meta) 
            {
                $result[] = $link ? '<a href="' . site_url($meta['type'].'/'.$meta['slug']) . '">'
                . $meta['name'] . '</a>' : $meta['name'];
            }

            $format = implode($split, $result);
        }
        
        return $format;
    }

    
}
