<?php
namespace Home\Model;
use Think\Model;

/**
 * Class PublicModel
 * @package Home\Model
 * User: @Andy-lizhongjian
 */
class PublicModel extends Model {
    
    protected $tablePrefix = 'think_';
    protected $tableName = 'users';
    
    protected $_validate = array(
        array('username', 'require', '用户名不能为空', Model::EXISTS_VALIDATE),
        array('username', '5,10', '用户名长度为5-10个字符组成', Model::EXISTS_VALIDATE, 'length'),
        //array('username', 'unique', '用户名已被占用', Model::EXISTS_VALIDATE),
        array('username','','用户名已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('phone', '/1[34578]{1}\d{9}$/', '请输入正确的手机号格式', Model::EXISTS_VALIDATE, 'regex'),
        array('password', 'require', '密码不能为空', Model::EXISTS_VALIDATE),
        //array('password', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'isPassword', '只允许输入6-20位由 数字、字母、下划线组成的密码', Model::EXISTS_VALIDATE, 'callback'),
        array('repassword','password','确认密码不正确',0,'confirm'),
        array('confirm', 'require', '确认密码不能为空', Model::EXISTS_VALIDATE),
        array('phone', 'empty', '手机号码不能为空', Model::EXISTS_VALIDATE),
        array('phone', 'is_numeric', '手机号码必须为数字', Model::EXISTS_VALIDATE),
        array('email', 'require', '邮箱不能为空', Model::EXISTS_VALIDATE),
        array('email', 'email', '邮箱格式不正确', Model::EXISTS_VALIDATE)
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
     * 允许密码输入6-12位
     */
   public function isPassword($str) {
       if (!preg_match('/^[_0-9a-z]{6,16}$/i',$str)){
            return false;
        }else{
            return true;
       }
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