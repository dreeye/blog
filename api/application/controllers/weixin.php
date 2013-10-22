<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Weixin extends CI_Controller {
    
    private $_token = "wangwei1989";
    
    //去空格后的用户文本
    private $_init = '';
    //处理后的天气信息
    private $_weatherlist = false;
    //公共提示信息
    private $_notice = <<<EOD
欢迎来到ProNoz,它的功能有：
天气查询:请回复"天气".
手机号归属查询:请输入您的手机号.
EOD;
    //不符合标准来源的返回信息(是要反馈给用户的)
    private $_manual = false;

    //处理后的手机信息
    private $_mobilelist = false;

        
    public function __construct()
    {
        parent::__construct();
        $echoStr = isset($_GET["echostr"]) ? $_GET["echostr"] : '';

        //valid signature , option
        if( ! $this->checkSignature())
        {
            exit;
        }
    }

    //请求信息判断入口
	public function index()
	{
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr))
        {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msgType = $postObj->MsgType;

            switch($msgType)
            {
                case "text":
                    $resultStr = $this->_handleText($postObj);
                    break;
                case "event":
                    $resultStr = $this->_handleEvent($postObj);
                    break;
                default:
                    $resultStr = "未知的来源".$msgType; 
                    
            }

            echo $resultStr;
        }
        else 
        {
            echo "";
            exit;
        }
	}

    
    //文本类型处理    
    private function _handleText($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $content = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             
        if(!empty( $content ))
        {
            $msgType = "text";
            $post = $content;
            //去掉多余的空格
            $this->_init = preg_replace('/\s(?=\s)/','',trim($post));
            $this->_analysis();
            //如果manual不为空，输出错误提示
            if($this->_manual)
            {
                $contentStr = $this->_manual;
                return $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }

            //返回天气信息
            if($this->_weatherlist)
            {
                $contentStr = '';
                $weatherlist = $this->_weatherlist;
                foreach($weatherlist as $val)
                {
                    $contentStr .= $val['date'].','.$val['week'].','.$val['city'].':'."\n".'早晚气温'.$val['wendu']."\n".$val['weather'].','.'风力'.$val['wind']."\n"."\n";
                }
                return $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);

            }
            //返回手机归属
            if($this->_mobilelist)
            {
                $mdata = $this->_mobilelist;
                $mdata[1] = iconv("GBK","UTF-8",$mdata[1]); 
                $mdata[2] = iconv("GBK","UTF-8",$mdata[2]); 
                $contentStr = '您的号码:'.$mdata[0]."\n".'归属地:'.$mdata[1]."\n".'赠言:'."\n".$mdata[2];
            
                return $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            }
        }
        else
        {
            echo "Input something...";
        }

    }
    //新用户推送
    private function _handleEvent($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $time = time();
        $msgType = "text";
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             

        switch($postObj->Event)
        {
            case "subscribe":
                $contentStr = $this->_notice; 
                break;
            case "unsubscribe":
                $contentStr = <<<EOD
感谢您曾经的支持，我们仍在努力，欢迎随时回来
EOD;
                break;
            default:
                $contentStr = $this->_notice; 
                break;
        }       
        return $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
    }
 
    //分析文本意图
    private function _analysis()
    {
        $laiyuan = trim($this->_init);
        $front = strstr($laiyuan,' ',true);
        $keyword = strtolower(substr($front,0,1));
        if($keyword == 'w')
        {
            $this->_weatherlist = $this->_weather();
            if(!$this->_weatherlist)
            {
            //返回总天气教程
            $this->_manual = <<<EOD
格式为：w+天数(不写天数就只查询当天天气,最大天数5)+空格+地区名(比如‘丰台’)
EOD;
            }
        } 
        elseif($laiyuan == '天气')
        {
            //返回总天气教程
            $this->_manual = <<<EOD
格式为：w+天数(不写天数就只查询当天天气,最大天数5)+空格+地区名(比如‘丰台’)
EOD;
        }
        elseif(is_numeric($laiyuan))
        {
            if(preg_match("/^13[0-9]{1}[0-9]{8}$|15[0189]{1}[0-9]{8}$|189[0-9]{8}$/",$laiyuan))
            {
                $this->load->library('mobile');
                $this->_mobilelist = $this->mobile->data($laiyuan);
            }
            else
            {
                $this->_manual = <<<EOD
您是要查手机号归属吗？号码不对哦亲～再输一次～
EOD;

            }
        }
        else
        {
                //如果来源都不符合，输出教程信息
                $this->_manual = $this->_notice; 
        }
    }

    private function _weather()
    {
        $yuan = explode(' ',$this->_init);
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
                        $end = $this->weather->show($frontnum,$citycode); 
                        return $end;
                        exit();
                    }
                }
            }
        }
        return false;
    
        
    }
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    
                
        $token = $this->_token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
