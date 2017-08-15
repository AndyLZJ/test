<?php
namespace App\Controller;

use Think\Controller;

/**
 * 手机验证码基础类
 * @author lizhongjian
 */
class MobileSMSController extends CommonController {

   /* private $sms_conf = array(
        'name' => '13332961795',
        'pwd' => '4FA2C1D750E20B7377C6938BFE6C',
        'sign' => '金融教育在线'
    );*/

    /**
     * @获取验证码
     * @param $mobile  手机号
     * @param $type   用户类型1为新用户 2为会员
     * @return array 状态码及状态信息
     */
    public function getCode($mobile,$type) {

        $model = null;
        $data = array();
        if(!isset($type) || empty($type)){
            $this->error(1036, '用户类型必须');
        }
        if (!is_numeric($mobile)) {
            $this->error(1016, '手机号码必须为数字');
        }

        if (!preg_match('/^1[34578]{1}\d{9}$/',$mobile)) {
            $this->error(1004, '手机号码不正确');
        }
        $verify_code = rand(10000, 99999);
        $data['phone'] = $mobile;
        $data['verify_code'] = $verify_code;
        $data['verify_time'] = time() + 300;
        $model = M('Users');
        $codeModel = M('VerifyCode');
        if ($type === '1') {
            //判断注册注册用户的手机号是否有被注册过
            $result = $model->field('phone')->where(array('phone' => $mobile))->find();
            if($result){
                $this->error(1003, '手机号已被注册');die();
            }else {
                //该手机号没被注册过，可以发送短信,同时判断该手机有无发送过短信
                $info = $codeModel->where(array('phone' => $mobile))->find();
                if ($info) {//该手机发送过短信
                    //查询该手机号发送短信所获得的验证码是否有效
                    if ($info['verify_time'] > time()) {
                        $this->error(1035, '手机验证码未过期');die();
                    } else {
                        //短信验证码已经过期
                        $_time = strtotime(date('Y-m-d', time()));
                        //定义一天的时间戳
                        $one_day_time = 86400;
                        $time_out = $_time - $info['create_time'];
                        if ($time_out <= $one_day_time) {
                            if($info['times'] < 3){ //判断一天内发送短信次数不能超过三次

                                $msg="您的验证码为:".$data['verify_code'].",5分钟内输入有效,请勿将信息泄露给其他人。";
                                $param = $this->send_sms($data['phone'],$msg);
                                if(substr($param, 0, 1)!=0){

                                    $this->error(1005,'短信验证码发送失败，请重新发送');

                                }else{
                                    $data['create_time'] = $_time;
                                    $data['m_time'] = date('Y-m-d H:i:s',time());
                                    $codeModel->where(array('phone' => $mobile))->save($data);
                                    $codeModel->where(array('phone' => $mobile))->setInc('times', 1); // 发送短信次数加1
                                    $this->success(1000,'短信验证码发送成功，请稍后');
                                }
                            }else{
                                $this->error(1039, '已超过当天短息发送次数');die();
                            }
                        } else {
                            $codeModel->where(array('phone' => $mobile))->setField('times', 0); // 次日将记录次数初始化为0
                        }
                    }
                } else {
                    //该手机没发送过验证码
                    //发送短信

                    $msg="您的验证码为:".$data['verify_code'].",5分钟内输入有效,请勿将信息泄露给其他人。";
                    $param = $this->send_sms($data['phone'],$msg);
                    if(substr($param, 0, 1)!=0){

                        $this->error(1005,'短信验证码发送失败，请重新发送');

                    }else{
                        $data['times'] = 1;//记录发送短信次数为第1次
                        $time = date('Y-m-d', time());
                        $data['m_time'] = date('Y-m-d H:i:s',time());
                        $data['create_time'] = strtotime($time);
                        $codeModel->add($data);
                        $this->success(1000,'短信验证码发送成功，请稍后');
                    }

                }
            }
        } else if ($type === '2') {
            //判断用户是否注册过
            $result = $model->where(array('phone' => $mobile))->find();
            if($result){
                //该手机号注册过，可以发送短信,同时判断该手机注册时发送的验证码是否还在有效期内
                $msg = $codeModel->where(array('phone' => $mobile))->find();
                //查询该手机号发送短信所获得的验证码是否有效
                if ($msg['verify_time'] > time()) {
                    $this->error(1035, '手机验证码未过期');die();
                } else {
                    //短信验证码已经过期
                    $_time = strtotime(date('Y-m-d', time()));
                    //定义一天的时间戳
                    $one_day_time = 86400;
                    $time_out = $_time - $msg['create_time'];
                    if ($time_out <= $one_day_time) {
                        if($msg['times'] < 3){ //判断一天内发送短信次数不能超过三次

                            $msg="您的验证码为:".$data['verify_code'].",5分钟内输入有效,请勿将信息泄露给其他人。";
                            $param = $this->send_sms($data['phone'],$msg);
                            if(substr($param, 0, 1)!=0){

                                $this->error(1005,'短信验证码发送失败，请重新发送');

                            }else{
                                $data['create_time'] = $_time;
                                $data['m_time'] = date('Y-m-d H:i:s',time());
                                $codeModel->where(array('phone' => $mobile))->save($data);
                                $codeModel->where(array('phone' => $mobile))->setInc('times', 1); // 发送短信次数加1
                                $this->success(1000,'短信验证码发送成功，请稍后');
                            }

                        }else{
                            $this->error(1039, '已超过当天短息发送次数');die();
                        }
                    }else{
                        $msg="您的验证码为:".$data['verify_code'].",5分钟内输入有效,请勿将信息泄露给其他人。";
                        $param = $this->send_sms($data['phone'],$msg);
                        if(substr($param, 0, 1)!=0){

                            $this->error(1005,'短信验证码发送失败，请重新发送');

                        }else{
                            $data['create_time'] = $_time;
                            $data['m_time'] = date('Y-m-d H:i:s',time());
                            $data['times'] = 1;
                            $codeModel->where(array('phone' => $mobile))->save($data);
                            $this->success(1000,'短信验证码发送成功，请稍后');
                        }
                        }
                    }

            }else{
                $this->error(1024, '用户不存在');
            }

        }

    }


    /**
     * 发送至手机短信API
     * @param $tel string 接收短信的手机
     * @param $txt string 短信的内容
     * @param $timeout int 发送失败，重试次数
     * @return bool
     */
    public static function send_sms($tel, $txt, $timeout = 0)
    {
        $flag = 0;
        $params='';//要post的数据
        $argv = array(
            'name'=>'13332961795',     //必填参数。用户账号
            'pwd'=>'4FA2C1D750E20B7377C6938BFE6C',     //必填参数。（web平台：基本资料中的接口密码）
            'content'=>$txt,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
            'mobile'=>$tel,   //必填参数。手机号码。多个以英文逗号隔开
            'stime'=>'',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
            'sign'=>'金融教育在线',    //必填参数。用户签名。
            'type'=>'pt',  //必填参数。固定值 pt
            'extno'=>''    //可选参数，扩展码，用户定义扩展码，只能为数字
        );
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

        foreach ($argv as $key=>$value) {
            if ($flag!=0) {
                $params .= "&";
                $flag = 1;
            }
            $params.= $key."="; $params.= urlencode($value);// urlencode($value);
            $flag = 1;
        }

        $url = "http://web.cr6868.com/asmx/smsservice.aspx?".$params;
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
