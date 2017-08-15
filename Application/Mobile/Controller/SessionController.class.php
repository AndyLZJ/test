<?php
namespace Mobile\Controller;

use Think\Controller;

/**
 * 已经登陆的会话基础类
 * @author lizhngjian
 */
class SessionController extends BaseController {

    const VERIFY_KEY_PRE = 'MOB';
    const HASH_PRE = '(%*&)(&^#@!Adadf$$^*Jab';
    const COOKIE_EXPIRE = 15552000; // 半年
    const TIMES_PRE = 'MOB_CC';

    public $currentUser;
    public $currentUserId;
    public $unCheckAction = array('login', 'register', 'sendSms');

    public function __construct() {
        if (in_array(ACTION_NAME, $this->unCheckAction)) {
            return;
        }
        session('[start]');
        // 判断是否登陆
        if (session('?isLogin')) {
            $this->currentUser = session('mobile');
        }
        // 如果会话结束，尝试COOKIE登陆
        $token = cookie('token');
        if (!$this->currentUser && $token) {
            $User = M('User');
            $cond['token'] = $token;
            $res = $User->where($cond)->find();
            $this->initLoginSession($res['mobile'], $res['deviceInfo'], $res['verifyCode']);
            $this->currentUser = $res['mobile'];
        }
        if ($this->currentUser) {
            $this->currentUserId = D('User')->where("mobile='" . $this->currentUser . "'")->getField('id');
            return;
        }
        $this->error('还没登陆，请重新登陆');
    }

    /**
     * 登陆或者注册成功后要设置会话
     * TO-DO 优化不更新数据
     * */
    public function initLoginSession($mobile, $deviceInfo, $verifyCode) {
        // 更新数据
        $token = $this->makeToken($mobile, $deviceInfo, $verfityCode);
        $User = M('user');
        $data = array(
            'device_info' => $deviceInfo,
            'token' => $token,
            'verify_code' => $verifyCode,
            'last_login_time' => time()
        );
        $cond['mobile'] = $mobile;
        $User->where('mobile=' . $mobile)->save($data);
        // 种COOKIE
        session('isLogin', 1);
        session('mobile', $mobile);
        $this->currentUser = session('mobile');
        cookie('mobile', $mobile, self::COOKIE_EXPIRE);
        cookie('hash', md5($deviceInfo), self::COOKIE_EXPIRE);
        cookie('token', $token, self::COOKIE_EXPIRE);
    }

    /**
     * 生成TOKEN
     */
    public function makeToken($mobile, $deviceInfo, $verfityCode) {
        return md5($mobile . $deviceInfo . self::HASH_PRE . $verifyCode);
    }

   
    //统计登录提交次数xingcuntian
    static public function submitTimes($mobile) {
        if (!session(self::TIMES_PRE . $mobile)) {
            session(self::TIMES_PRE . $mobile, 1);
        } else {
            session(self::TIMES_PRE . $mobile, (session(self::TIMES_PRE . $mobile) + 1));
        }
        return session(self::TIMES_PRE . $mobile);
    }

}
