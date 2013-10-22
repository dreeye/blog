<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Explain 
{
    private $_url = 'http://fanyi.youdao.com/openapi.do?keyfrom=ProNoz&key=1895968500&type=data&doctype=json&version=1.1';
    
    private $_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322)';

    private $_init;
    //json数组
    private $_return = FALSE;
    //要返回的数据
    private $_format = array();


    public function show($word)
    {
        
        $this->_curl($word);
        if($this->_return)
        {
            $this->_format();
        }

        return $this->_format;
    }

    private function _curl($word)
    {
        $this->_init = curl_init();
        curl_setopt($this->_init, CURLOPT_URL, $this->_url.'&q='.$word);
        curl_setopt($this->_init, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->_init, CURLOPT_USERAGENT,$this->_agent );
        $content = curl_exec($this->_init);
        $this->_return = json_decode($content,TRUE);
        curl_close($this->_init);
        return $this->_return;
    }

    private function _format()
    {
        $arr = array();
        $basic = array();
        if(isset($this->_return['basic']))
        {
            $basic = $this->_return['basic'];
            if($basic)
            {
                $arr['phonetic'] = isset($basic['phonetic']) ? $basic['phonetic'] : '';
                $arr['explains'] = isset($basic['explains']) ? $basic['explains'] : '';
                $this->_format = $arr;
            }
            else
            {
                $this->_format = FALSE;
            }
        }
        else
        {
            $this->_format = FALSE;
        }
    
    }
    

}
