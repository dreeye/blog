<?php

	//定义一个常量，控制PHP的报错等级
    define('ENVIRONMENT', $_SERVER['PN_ENVIRONMENT']);

    
    if (defined('ENVIRONMENT'))
    {
        switch (ENVIRONMENT)
        {
            case 'development':
                error_reporting(E_ALL);
            break;
        
            case 'testing':
            case 'production':
                error_reporting(0);
            break;

            default:
                exit('The application environment is not set correctly.');
        }
    }

    //CI主程序目录的路径(服务器中所有网站都可以调用统一的system，所以下面会替换成绝对路径)
	$system_path = '../system';

    //自定义程序目录的路径(每个网站都要有一个，所以是相对路径)
	$application_folder = 'application';
	
    // Set the current directory correctly for CLI requests
	if (defined('STDIN'))
	{
		chdir(dirname(__FILE__));
	}
    
    //是否能在此位置找到system目录，能的话就把$system_path改成system目录的绝对路径（pwd system）
	if (realpath($system_path) !== FALSE)
	{
		$system_path = realpath($system_path).'/';
	}
    
    //确定绝对路径后面只有一个斜线
	$system_path = rtrim($system_path, '/').'/';

	// Is the system path correct?
	if ( ! is_dir($system_path))
	{
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
	}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// index.php
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// system绝对路径
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// 根目录绝对路径
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// system目录名
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

    //application只能在根目录或system下面
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */
