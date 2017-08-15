<?php
namespace App\Model;
use Think\Model;

/**
 * Class UsersModel
 * @package Mobile\Model
 * User: @Andy-lizhongjian
 */
class UsersModel extends CommonModel {
    
    protected $tablePrefix = 'think_';
    protected $tableName = 'users';
    
    protected $_validate = array(
        array('username', 'empty', '用户名不能为空', Model::EXISTS_VALIDATE, 'function'),
        //array('username', '5,100', '用户名长度超出限制', Model::EXISTS_VALIDATE, 'length'),
        array('phone', '/1[34578]{1}\d{9}$/', '请输入正确的手机号格式', Model::EXISTS_VALIDATE, 'regex'),
        array('password', 'empty', '密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('confirm', 'empty', '确认密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('password', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'isPassword', '只允许输入6-20位由 数字、字母、下划线组成的密码', Model::EXISTS_VALIDATE, 'callback'),
        array('phone', 'empty', '手机号码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('phone', 'is_numeric', '手机号码必须为数字', Model::EXISTS_VALIDATE, 'function')
    );
    /*protected $_auto = array (
        array('register_time','time',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
       
    );*/


   
    /**
     * 验证邮箱输入合法性
     */
    function is_email($email){
        $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if(preg_match($pattern,$email))
        {      
            return true;
        }else{
            return false;
        }        
    }

    /**
     * 忘记密码手机加验证码登录-设置新密码字段验证
     * $param $post 接收的参数
     * password 密码
     *
     *
     */
    public function setNewPassword($post){
        $data['token'] = $post['token'];
        $data['password'] = $post['password'];
        if(!empty($data['token'])) {
            $info = M('Users')->where(array('token' => $data['token']))->find();
            if (empty($info) || (time() >= $info['token_expires'])) {
                return $this->error(4100, '授权失败或过期');
            } else {
                if ($info['token'] != $data['token']) {
                    return $this->error(1033, '不合法的token');
                }else{
                    if(empty($data['password'])){
                        return $this->error(1006,'密码不能为空');die();
                    }
                    $msg = $this->isPassword($data['password']);
                    if(!$msg){
                        return $this->error(1018,'只允许输入6-20位由 数字、字母、下划线组成的密码');die();
                    }else{
                        $data['password'] = md5($data['password']);
                        $info = M('Users')->where(array('token'=>$data['token']))->setField('password',$data['password']);
                        if($info){
                            $msg['token'] = $data['token'];
                            return $this->success(1000,'修改成功',$msg);die();
                        }else{
                            return $this->error(1029,'修改失败');die();
                        }
                    }
                }
            }
        }else{
          return $this->error(1023,'缺少必要参数');
        }
    }
    
    /**
     * 注册
     * @param $sendCode          手机短信验证码
     * @param $confirm           确认密码
     * @param  array $post       注册所需字段
     * @return array             状态值&状态信息|用户标识字段
     */

    public function _register($post, $sendCode, $confirm) {
        $data = array();
        $data['username'] = $post['account'];
        $data['phone'] = $post['mobile'];
        $data['password'] = $post['password'];
        if (empty($data['username'])){
            return $this->error(1012, '请输入用户名');
        }
        if (empty($data['phone'])) {
            return $this->error(1017, '请输入手机号');
        }
        if (empty($data['password'])) {
            return $this->error(1006, '请输入密码');
        }

        if (empty($confirm)) {
            return $this->error(1006, '请输入确认密码');
        }
        if (empty($sendCode)) {
            return $this->error(1001, '请输入验证码');
        }
        $model = M('Users');
        $codeModel = M('VerifyCode');
        $info = $model->where(array('phone' => $data['phone']))->order('id desc')->find();
        if (!empty($info['username'])){
            return $this->error(1003, '号码已被注册');
        }
        $msg = $codeModel->where(array('phone' => $data['phone']))->order('id desc')->find();
        if (empty($msg['verify_code'])) {
            return $this->error(1001, '请获取验证码');
        }
        if (time() > $msg['verify_time']){
            return $this->error(1038, '验证码已过期');
        }
        if ($sendCode != $msg['verify_code']){
            return $this->error(1002, '请输入正确的验证码');
        }
        if($data['password'] !== $confirm){
            return $this->error(1007, '密码和确认密码不一致');
        }
        if (!$this->create($data)){
            return $this->error(1013, $this->getError());
        }
        $data['password'] = md5($data['password']);
        $data['register_time'] = date('Y-m-d H:i:s',time());
        $add = $model->add($data);
        if (!$add) {
            return $this->error(1009, '注册失败');
        }
        $message = $model->where(array('phone'=>$data['phone']))->find();
        M('auth_group_access')->add(array("uid"=>$message['id'],"group_id"=>3));
        M('tissue_group_access')->add(array("uid"=>$message['id'],"tissue_id"=>0,"job_id"=>0,"manage_id"=>0));
        return $this->success(1000,'注册成功',$message);
    }

    /**
     * 用户登录
     */
    public function _login($post,$model){

        return $this->_checkData($post,$model);

    }

    /**
     * 验证数据合法性并返回用户信息集
     * @param  array  $post      需验证的数据集
     * @param  object $model     数据模型
     * @return array             用户信息集
     *
     */
    protected function _checkData($post, $model) {
        $data = $this->_checkValidateData($post);
        if(!empty($data['token'])){
                $info = M('Users')->where(array('token' => $data['token']))->find();
                if (empty($info) || (time() >= $info['token_expires'])) {
                    return $this->error(4100, '授权失败或过期');
                }else{
                    if($info['token'] != $data['token']){
                        return $this->error(1033, '不合法的token');
                    }else{
                        $info['last_login_time'] = date('Y:m:d H:i:s',time());
                        $ip=$_SERVER['REMOTE_ADDR'];
                        $info['last_login_ip'] = $ip;
                        $info['secret_key'] = parent :: SECRET_KEY;;
                        $model->where(array('token' => $data['token']))->save($info);
                        $uid = $model->where(array('token' => $data['token']))->getField('id');
                        //查询用户身份
                        $user_type = M('Users a')->join('LEFT JOIN __AUTH_GROUP_ACCESS__ b ON a.id = b.uid LEFT JOIN __AUTH_GROUP__ c ON b.group_id = c.id')->where(array('a.id'=>$uid))->getField('c.id');
                        $info['user_type'] = $user_type ? $user_type: 3;
                        $res = D('Trigger')->intergrationTrigger($uid,1);
                        return $this->success(1000, '登录成功!',$info);
                    }
                }
        }else{
            $info = null;

            if (empty($data['phone'])) {
                return $this->error(1017, '请输入手机号');
            }

            if (empty($data['password'])) {
                return $this->error(1006, '请输入密码');
            }

            if (!$this->isMobile($data['phone'])) {
                return $this->error(1004 , '请填写正确格式的手机号码');
            }
            $info = $model->where(array('phone' => $data['phone']))->find();
            if (empty($info)) {
                return $this->error(1024, '用户不存在');
            }
            if ($info['status'] != 1) {
                return $this->error(1031, '账号待审核中');
            }
            $account = $info['phone'];

            if (md5($data['password'])!== $info['password']) {
                return $this->error(1015, '密码错误!');
            }
            $info['token'] = $this->makeToken($account);
            $info['token_expires'] = time() + self::TOKEN_EXPIRE;
            $info['is_login'] = 1;
            $info['last_login_time'] = date('Y:m:d H:i:s',time());
            $ip=$_SERVER['REMOTE_ADDR'];
            $info['last_login_ip'] = $ip;
            $info['secret_key'] = parent :: SECRET_KEY;
            $model->where(array('phone' => $data['phone']))->save($info);
            $uid = $model->where(array('phone' => $data['phone']))->getField('id');
            //查询用户身份
            $user_type = M('Users a')->join('LEFT JOIN __AUTH_GROUP_ACCESS__ b ON a.id = b.uid LEFT JOIN __AUTH_GROUP__ c ON b.group_id = c.id')->where(array('a.id'=>$uid))->getField('c.id');
            $info['user_type'] = $user_type ? $user_type: 3;
            $res = D('Trigger')->intergrationTrigger($uid,1);
            return $this->success(1000, '登录成功!',$info);
        }
    }

    /**
     * 检测登录数据合法性
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
            $data['password'] = $post['pwd'];
        }
        return $data;
    }
   

    
   
    
    /**
     * 验证忘记密码验证码登录数据合法性
     */
    public function checkLoginData($data){
        if(!empty($data['token'])){
            $model = M('Users');
            $info = $model->where(array('token' => $data['token']))->find();
            if (empty($info) || (time() >= $info['token_expires'])) {
                return $this->error(4100, '授权失败或过期');
            }else{
                if($info['token'] != $data['token']){
                    return $this->error(1033, '不合法的token');
                }else{
                    $info['last_login_time'] = date('Y:m:d H:i:s',time());
                    $ip=$_SERVER['REMOTE_ADDR'];
                    $info['last_login_ip'] = $ip;
                    $info['secret_key'] = parent :: SECRET_KEY;
                    $model->where(array('token' => $data['token']))->save($info);
                    $uid = $model->where(array('token' => $data['token']))->getField('id');
                    //查询用户身份
                    $user_type = M('Users a')->join('LEFT JOIN __AUTH_GROUP_ACCESS__ b ON a.id = b.uid LEFT JOIN __AUTH_GROUP__ c ON b.group_id = c.id')->where(array('a.id'=>$uid))->getField('c.id');
                    $info['user_type'] = $user_type;
                    return $this->success(1000, '登录成功!',$info);
                }
            }
        }else{
            if(!empty($data['phone'])){
                $ret = $this->isMobile($data['phone']);
                if($ret){
                    if(!empty($data['verify_code'])){
                        $info = M('Users a')->field('a.*,b.verify_code')->join('LEFT JOIN __VERIFY_CODE__ b ON a.phone = b.phone')->where(array('a.phone' => $data['phone']))->find();
                        if($info){
                            $model = M('Users');
                            if($data['verify_code'] == $info['verify_code']){
                                $info['token'] = $this->makeToken($data['phone']);
                                $info['token_expires'] = time() + self::TOKEN_EXPIRE;
                                $info['is_login'] = 1;
                                $info['last_login_time'] = date('Y:m:d H:i:s',time());
                                $ip=$_SERVER['REMOTE_ADDR'];
                                $info['last_login_ip'] = $ip;
                                $info['secret_key'] = parent :: SECRET_KEY;
                                $model->where(array('phone' => $data['phone']))->save($info);
                                $uid = $model->where(array('phone' => $data['phone']))->getField('id');
                                //查询用户身份
                                $user_type = M('Users a')->join('LEFT JOIN __AUTH_GROUP_ACCESS__ b ON a.id = b.uid LEFT JOIN __AUTH_GROUP__ c ON b.group_id = c.id')->where(array('a.id'=>$uid))->getField('c.id');
                                $info['user_type'] = $user_type;
                                return $this->success('1000','登录成功',$info);
                            }else{
                                return $this->error(1002, '手机验证码不正确');
                            }
                        }else{
                            return $this->error(1024,'用户不存在');
                        }
                    }else{
                        return $this->error(1001,'手机验证码不能为空');//手机验证码不能为空
                    }
                }else{
                    return $this->error(1004,'请输入正确的手机号码');
                }
            }else{
                return $this->error(1012,'请输入手机号');
            }
        }
    }
    
    /**
     * 允许密码输入6-12位
     */
   public function isPassword($str) {
       if (!preg_match('/^[_0-9a-z]{6,16}$/i',$str)){
            return false;
        }else{
            return true;
       }
}


    /*
     * 验证手机号是否正确
     * @author lizhongjian
     * @param  $mobile
     */
    public function isMobile($mobile) {
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#',$mobile) ? true : false;
    }





    /**
     * 获取客户端ip的方法
     * @param int $type
     * @return mixed
     */
    function get_client_ip($type = 0) {
        $type       =  $type ? 1 : 0;
        static $ip  =   NULL;
        if ($ip !== NULL) return $ip[$type];
        if($_SERVER['HTTP_X_REAL_IP']){//nginx 代理模式下，获取客户端真实IP
            $ip=$_SERVER['HTTP_X_REAL_IP'];
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {//客户端的ip
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {//浏览当前页面的用户计算机的网关
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];//浏览当前页面的用户计算机的ip地址
        }else{
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u",ip2long($ip));
        $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }
}