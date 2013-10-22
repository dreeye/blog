<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{

	function __construct()
    {
        parent::__construct();
    }

	protected function js()
    {
		$CI =& get_instance();
    	$js_num = func_num_args();
        $js_files = func_get_args();
        if ($js_num == 1 && is_array($js_files[0])) $js_files = $js_files[0];
        foreach ($js_files as $js_file)
        {
            echo '<script language="javascript" type="text/javascript" src="' .$CI->config->base_url(). PN_STATIC . '/' . $js_file . '.js" charset="utf-8"></script>' . PHP_EOL;
        }
    }

	protected function css()
    {
		$CI =& get_instance();
    	$css_num = func_num_args();
    	$css_files = func_get_args();
    	if ($css_num == 1 && is_array($css_files[0])) $css_files = $css_files[0];
        foreach ($css_files as $css_file)
        {
            echo '<link href="' . $CI->config->base_url(). PN_STATIC . '/' . $css_file . '.css?225" rel="stylesheet" type="text/css" />' . PHP_EOL;
        }
    }

	
	 /**
	 * 打开皮肤功能
	 * 
	 * @access public
	 * @return void
	 */ 
    public function switch_theme_on()
    {
		$this->_ci_view_paths = array(FCPATH . PN_THEMES_DIR . DIRECTORY_SEPARATOR . PN_THEMES_NAME . DIRECTORY_SEPARATOR	=> TRUE);
		
    }
}
