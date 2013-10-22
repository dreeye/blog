<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{
    private $_init = '';

    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $post = "w2    北京";
        $this->_analysis();
    }
    
    //分析来源
    private function _analysis()
    {
        $laiyuan = trim($this->_init);
        $front = strstr($laiyuan,' ',true);
        $keyword = strtolower(substr($front,0,1));
        if($keyword == 'w')
        {
            $this->_weather();
        } 

    }

    private function _weather()
    {
        $yuan = explode(' ',$this->_init);
echo '<pre>';print_r($yuan);echo '</pre>';exit(); 
        //必须分解为两个数组
        if(count($yuan) == 2)
        {
            $frontnum = (int)substr($yuan[0],1);
            $city = $yuan[1];
            //如果符合标准
            if(is_numeric($frontnum))
            {
                if($frontnum < 6)
                {
                    $this->config->load('city');
                    $citylist = $this->config->item('city');
                    $citycode = array_search($city,$citylist);
                    if($citycode) 
                    {
                        $this->load->library('weather');
                        $weatherlist = $this->weather->show($frontnum,$citycode); 
                        
                    }
                   echo '<pre>';print_r($citycode);echo '</pre>';exit();  
                }
            }
        }
        return false;
    
        
    }

}
