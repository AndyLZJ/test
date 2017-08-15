<?php
class Sms
{
    
    private $sms_conf = array(
                            'user' => '13332961795',
                            'pwd' => '4FA2C1D750E20B7377C6938BFE6C',
                            'sign' => '金融教育在线'
                        );

    /**
     * 发送至手机短信API
     * @param $tel string 接收短信的手机
     * @param $txt string 短信的内容
     * @param $timeout int 发送失败，重试次数
     * @return bool
     */
    public static function send_sms($tel, $txt, $timeout = 0)
    {
        // if(\utility::is_mobile($tel) == false || empty($txt))
        // {
        //     return false;
        // }

        //如果是测试号码则不发送短信，默认验证码为：1234
        $test = array(
            '13800138000', '13800138001', '13800138002',
            '13800138003', '13800138004', '13800138005',
            '13800138006', '13800138007', '13800138008',
            '13800138009', '13800138010', '13800138011',
        );
        if(in_array($tel, $test))
        {
            $_SESSION[$tel.'_code'] = 1234;
            die('ok');
        }

        global $sms_conf;

        $txt = urlencode($txt);
        $url = "http://web.cr6868.com/asmx/smsservice.aspx?name=13332961795&pwd=4FA2C1D750E20B7377C6938BFE6C&content=$txt&mobile=$tel&sign=金融教育在线&type=pt";
        
        if(function_exists('file_get_contents'))
        {
            $result = file_get_contents($url);
        }
        else
        {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $result = curl_exec($ch);
            curl_close($ch);
        }

        //短信发送失败时尝试重新发送，最多重复两次
        if($timeout <= 1 && substr($result, 0, 1) != 0)
        {
            self::send_sms($tel, $txt, $timeout + 1);
        }

        return $result;
    }
}