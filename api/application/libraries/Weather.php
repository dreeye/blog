<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Weather
{
    private $_url = 'http://m.weather.com.cn/data/';
    
    private $_agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322)';

    private $_init;
    private $_day = 0;
    private $_weather;


    public function show($day,$citycode)
    {
        
        $this->_init = curl_init($this->_url.$citycode.'.html');
        $this->_setopt($this->_init); 
        $content = curl_exec($this->_init);
        curl_close($this->_init);
        $arr = json_decode($content,TRUE);
        $this->_weather = $arr['weatherinfo'];
        $this->_day = $day;
        return  $weather = $this->_date();
    }

    private function _setopt($init)
    {
        curl_setopt($init, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($init, CURLOPT_USERAGENT,$this->_agent );
    }
    
    private function _date()
    {
        if(is_numeric($this->_day))
        {
            for($i=0;$i<=$this->_day;$i++)
            {
                $dayarr[] = array(date("m月d日",strtotime("+$i day")),date("N",strtotime("+$i day"))); 
            }
                    $l = 1;
                    foreach($dayarr as &$val)
                    {
                        switch ($val[1]):
                        case 1:
                            $val['week'] = "周一";
                            break;
                        case 2:
                            $val['week'] = "周二";
                            break;
                        case 3:
                            $val['week'] = "周三";
                            break;
                        case 4:
                            $val['week'] = "周四";
                            break;
                        case 5:
                            $val['week'] = "周五";
                            break;
                        case 6:
                            $val['week'] = "周六";
                            break;
                        case 7:
                            $val['week'] = "周日";
                            break;
                        endswitch;
                            $val['wendu'] = $this->_weather["temp$l"];
                            $val['city'] = $this->_weather['city'];
                            $val['date'] = $val[0];
                            $val['weather'] = $this->_weather["weather$l"];
                            $val['wind'] = $this->_weather["fl$l"];
                            $l++;
                    }
        }
        else
        {
            return false;
        }
        return $dayarr; 
    } 

}
