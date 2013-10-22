<?php
/**
 *返回今天以及未来几天的日期以及周期
 *
 *@param $day 未来多少天，0为今天
 *@param $date 是否返回周几
 *@param $format 格式化日期
 */
function date_time($day=0,$date = FALSE,$format= FALSE)
{
    if(is_numeric($day))
    {
        for($i=0;$i<=$day;$i++)
        {
            if($date)
            {
                $dayarr[] = array(date("Y年m月d日",strtotime("+$i day")),date("N",strtotime("+$i day")));
                if($format)
                {
                    foreach($dayarr as &$val)
                    {
                        switch ($val[1]):
                        case 1:
                            $val[1] = "周一";
                            break;
                        case 2:
                            $val[1] = "周二";
                            break;
                        case 3:
                            $val[1] = "周三";
                            break;
                        case 4:
                            $val[1] = "周四";
                            break;
                        case 5:
                            $val[1] = "周五";
                            break;
                        case 6:
                            $val[1] = "周六";
                            break;
                        case 7:
                            $val[1] = "周日";
                            break;
                        endswitch;
                    }
                }
            }
            else
            {
                $dayarr[] = date("Y-m-d",strtotime("+$i day"));

            }
        }
    }
    else
    {
        return false;
    }
    return $dayarr;

    

}
