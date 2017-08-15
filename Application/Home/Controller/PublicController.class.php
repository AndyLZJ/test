<?php
namespace Home\Controller;
use Think\Controller;
use Think\Verify;

class PublicController extends Controller {
		
		/**
	    *生成验证码
	    *@return [type] [description]
	    */
	    public function verify(){

	        $config=array('fontSize'    =>    12,
	                       'length'     =>    4,        
	                       'useNoise'   =>    false,
	                       'imageW'     =>86,
	                       'imageH'     =>40,
						   'codeSet'    => '0123456789',
						   'useCurve'   => false,
						   
	                     );

	        $Verify = new \Think\Verify($config);

	        $Verify->entry();

	    }

	    /**
	    *短信发送
	    *@return [type] [description]
	    */
	    public function sendMessage(){

	    	Vendor('Sms.Sms');

	    	$Sms = new \Sms();

			$mobile=I('post.mobile');

			$sendCode=rand(10000,99999);

			$txt="您的验证码为".$sendCode.",请在30分钟内输入验证码,切勿将信息泄露给其他人";

			$result=$Sms->send_sms($mobile,$txt);

			if(substr($result, 0, 1)!=0){

				$data=array("code"=>0,"message"=>"短信验证码发送失败，请重新发送");

				$this->ajaxReturn($data);
			
			}else{

				$data=array("code"=>0,"message"=>"短信验证码发送成功，请稍后！");

				$sendCode = array(
					"time"=>time()+1800,
					"mobile"=>$mobile,
					"code"=>$sendCode
				);

				F('sendCode',$sendCode);

				$this->ajaxReturn($data);

			}
		}

		public function index(){

			echo "he";exit;
		}

	
	    /**
	    *邮箱验证码发送
	    *@return [type] [description]
	    */
	    public function sendEmailMessage()
        {


            $email = I('post.email');
            $data['email'] = $email;
            $User = D("Public"); // 实例化User对象

            if (!$User->token(false)->create($data, 1)) { // 指定新增数据
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->ajaxReturn(array('code' => 1030, 'message' => $User->getError()));
            } else {
                $email = $data['email'];
                $sendCode = rand(10000, 99999);

                $emailtitle = "培训系统验证码";

                $txt = "您的验证码为<span style='color:#FF0000;font-weight:bold'>" . $sendCode . "</span>,请在30分钟内输入验证码,切勿将信息泄露给其他人";

                $emailcontent = $txt . "<br /><br />" . "此邮件由培训平台系统自动发出,请勿回复！";
                $res = send_email($email, $emailtitle, $emailcontent);
                if ($res['error'] == 0) {
                    $data = array("code" => 1000, "message" => "邮箱验证码发送成功，请稍后！");
                    $sendCode = array(
                        "time" => time() + 1800,
                        "email" => $email,
                        "code" => $sendCode
                    );

                    F('sendCode', $sendCode);

                    $this->ajaxReturn($data);
                } else {
                    $data = array("code" => 1030, "message" => "邮箱验证码发送失败，请重新发送");

                    $this->ajaxReturn($data);
                }

            }

       }

	}

?>