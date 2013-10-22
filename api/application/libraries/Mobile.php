<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Mobile 
{
    //api url（无参数）
    private $_url = 'http://www.096.me/api.php?phone=';
    
    private $_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322)';

    private $_init;
    private $_mobilearr;


    public function data($phonenum)
    {
        
        $this->_init = curl_init($this->_url.$phonenum);
        $this->_setopt($this->_init); 
        $content = curl_exec($this->_init);
        $this->_toarray($content);
        curl_close($this->_init);
        return $this->_mobilearr;
    
    }

    private function _setopt($init)
    {
        curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($init, CURLOPT_USERAGENT,$this->_agent );
    }

    private function _toarray($content)
    {
        if($content)
        {
            $content = strstr(trim($content),' ',TRUE);
            $this->_mobilearr = explode('||',$content);
        }
        else
        {
            return false;
        }
    }
}
