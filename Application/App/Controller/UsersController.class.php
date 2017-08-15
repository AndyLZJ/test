<?php
namespace App\Controller;
use Think\Controller;
use Think\Upload;
class UsersController extends CommonController {

    public function __construct() {
        parent::__construct();
    }
    
    /*@lizhongjian
     * @用户注册
     * @return array 状态码及状态信息
     */
    public function register() {

        $post = array();
        $post = I('post.','trim');
        $sendCode = I('post.verifyCode');
        $confirm  = I('post.confirm');
        $result = D('Users')->_register($post,$sendCode,$confirm);
        if(empty($result['data'])){
            return $this->error($result['code'],$result['message']);
        }else{
            return $this->success($result['code'],$result['message'],$result['data']);
        }
    }
    
   
 
    /**
     * 用户登录
     * @return array 用户信息数组
     */
    public function login() {
        
        $post = I('post.');
        $model = M('Users');
        $result = D('Users')->_login($post,$model);
        if($result['code'] == 1000){
            $this->success($result['code'],$result['message'],$result['data']);
        }else{
            $this->error($result['code'],$result['message']);
        }
    }
    
    /**
     * 用户退出
     * @return array 状态信息
     */
    public function logout() {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataPost();
            $token = I('post.token');
            $model = M('Users');
            $data = array();
            $data['token'] = '';
            $data['token_expires'] = 0;
            $data['last_login_time'] = '';
            $data['is_login'] = 0;
            $result = $model->where(array('token' => $token))->save($data);
            if ($result){
                $this->success(1000,'成功退出');
            }else{
                $this->error(1023, '请求参数有误');
            }
    }
    
    
    /**
     * 忘记密码手机+验证码登录
     */
    public function forgotPwdLogin(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $post = I('post.');
        $_data = $this->_checkValidateData($post);
        $result = D('Users')->checkLoginData($_data);
        if ($result['data'] != 1000) {
            $this->error($result['code'],$result['message']);
        }else{
            $this->success($result['code'],$result['message'],$result['data']);
        }
    }


    /**
     * 检测手机验证码登录数据合法性
     * @return array 数据集
     */
    protected function _checkValidateData($post) {
        $data = array();
        if (isset($post['token'])) {
            $data['token'] = $post['token'];
            $data['secret_key'] = $post['secret_key'];
            if (empty($data['token']) || empty($data['secret_key']) || self::SECRET_KEY != $data['secret_key']) {
                $this->error(1023, '请求参数有误');
            }
        } else {
            $data['phone'] = $post['mobile'];
            $data['verify_code'] = $post['sendCode'];
        }
        return $data;
    }
    
    
    /**
     * 忘记密码登录设置新密码
     * $token 身份标识
     * $password  密码
     */
    public function setNewPassword(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataPost();
            $post = I('post.');
            $result = D('Users')->setNewPassword($post);
            if($result['code'] != 1000){
                $this->error($result['code'],$result['message']);
            }else{
                $this->success($result['code'],$result['message'],$result['data']);
            }
    }
    

    /*
     *@短信发送获取验证码
     *@return [type] [description]
     *@param $mobile 手机号
     *@param $type  1注册,2手机短信验证码登录
     *@return array 状态码&状态信息
     */
   
    public function sendCode() {
        $mobile = I("request.mobile",'','trim,htmlentities');
        $type = I('request.type','','trim,htmlentities');   // 1注册,2手机短信验证码登录
        A('MobileSMS')->getCode($mobile,$type);
    }


    /*
     * 安卓手机端生成apk二维码
     */
    public function  qrcode(){
        $url = 'http://'.$_SERVER['SERVER_NAME'].'/Upload/融易点.apk';
        //$url = 'http://www.baidu.com';
        //$url=json_encode(array($url));
        echo  $url;exit;
        qrcode($url,10);
    }
}