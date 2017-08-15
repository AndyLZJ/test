<?php
namespace Mobile\Controller;
use Think\Controller;

/**@User lizhongjian
 * Class CommonController
 * @package Mobile\Controller
 */
class CommonController extends Controller {

    /**
     * @var string 生成token用的hash码
     */
    const HASH_PRE = '(%*&)(&^#@!Adadf$$^*shoes';
    /**
     * @var string token过期时间(1月)
     */
    const TOKEN_EXPIRE = 2592000; 

    /**
     * @var string 秘钥
     */
    const SECRET_KEY = 'rA21VeE8347bScsuIDNq';

    /* 不必验证的接口 */

    public $unCheckAction = array('getToken','login', 'register', 'sendCode','forgotPwdLogin');
    protected $token;
    protected $secret_key;

    /**
     *  所有接口的公共入口
     *  初始化相关对象与参数, 
     *  并检测登录状态(除不需验证的接口)
     */
    public function __construct() {
        parent::__construct();
        if (('Users' == CONTROLLER_NAME) || in_array(ACTION_NAME, $this->unCheckAction)) {
            return;
        }
        $this->token = I('request.token', '', 'trim');
        $this->secret_key = I('request.secret_key', '', 'trim');
        if (empty($this->token) || empty($this->secret_key)) {
            $this->error(1023, '缺少必要参数token/secret_key');
        } 
        if (self::SECRET_KEY != $this->secret_key) {
            $this->error(1032, '不合法的secret_key');
        }
        $field = 'token_expires,status';

        $model = M('Users');

        $result = $model->where(array('token' => $this->token, 'is_login' => 1))->field($field)->find();
		
        if (empty($result)) {
           $this->error(1033, '不合法的token');
        }
        if (time() >= $result['token_expires']) {
           $this->error(1034, 'token超时');
        }
        if (isset($result['status']) && 1 != $result['status']) {
           $this->error(1031, '账号待审核中');
        }
    }


    /*
       * token过期重新获取有效token
       * $uid 用户id
       */
    public function getToken() {
        $uid = I('get.id');
        $token = M('Users')->where(array('id' => $uid))->getField('token');
        $this->success('成功', $token);
    }
   
    /**
     * 成功返回方法
     * @param String $code  信息提示号
     * @param String $message  返回客户端的内容
     * @param Array $data 返回客户端的数据体
     * @param String 数据传输格式(JSON,JSONP,XML)
     * @param Array 附加的数据体 
     */
    public function success($code,$message, $data = array(), $type = null) {
        $data = $this->dealNull($data);
        if(!empty($data)){
            $return = array(
                'code' => $code,
                'message' => $message,
                'data'    =>$data
            );
        }else{
            $return = array(
                'code' => $code,
                'message' => $message,
            );
        }

        if (!empty($type)) {
            $this->ajaxReturn($return, $type);
        } else {
            $this->ajaxReturn($return);
        }
    }
    
    /**
     * 错误返回方法
     * @param Int $code 错误码
     * @param String $message 错误信息
     * @param Array $data 附加数据体
     * @param String 数据传输格式(JSON,JSONP,XML)
     */
    public function error($code, $message, $data = array(), $type = null) {
        $data = $this->dealNull($data);
        if (!empty($data)) {
            $return = array(
                'code' => $code,
                'message' => $message,
                'data' => $data
            );
        }else{
            $return = array(
                'code' => $code,
                'message' => $message,
            );
        }

        if (!empty($type)) {
            $this->ajaxReturn($return, $type);
        } else {
            $this->ajaxReturn($return);
        }
    }

    
    /**
     * 跨域设置
     */
    protected function setHeader()
    {
        header('Content-Type:application/json; charset=utf-8');
        header("Access-Control-Allow-Origin:*");
        header("Access-Control-Allow-Methods:POST,GET");
    }

    /**
     * 获取用户id
     * @return int 用户id
     */
    protected function getUserId() {
        $model = null;
        $model = M('Users');
        $id = $model->where(array('token' => $this->token))->getField('id');
        if (empty($id)) {
            return false;
        }
        return $id;
    }

    /**
     * 获取用户id,判断用户提交方式是否是POST请求
     * @return int 用户id
     */
    protected function  verifyUserDataPost() {
        $model = null;
        $model = M('Users');
        $id = $model->where(array('token' => $this->token))->getField('id');
        if (empty($id)) {
            return $this->error(1024,'用户不存在');
        }
        if (!IS_POST) {
            return $this->error(1013,'错误请求方式');
        }
        return $id;
    }

    /**
     * 获取用户id,判断用户提交方式是否是GET请求
     * @return int 用户id
     */
    protected function  verifyUserDataGet() {
        $model = null;
        $model = M('Users');
        $id = $model->where(array('token' => $this->token))->getField('id');
        if (empty($id)) {
            return $this->error(1024,'用户不存在');
        }
        if (!IS_GET) {
            return $this->error(1013,'错误请求方式');
        }
        return $id;
    }

    /**
     * 图片上传公共方法
     * @param  $config
     */
    public function uploadImages($config){
        /*$config = array(
            'maxSize' => 3145728,
            'savePath' => '',//保存子目录
            'rootPath' => './Upload',//保存根目录
            'saveName' => array('uniqid', ''),
            'exts' => array('jpg', 'gif', 'png', 'jpeg'),
            'autoSub' => false,
            'subName' => array('date', 'Ymd')
        );*/


        $upload = new \Think\Upload($config);// 实例化上传类

        $info = $upload->upload();


        if (!$info) {

            return $this->error(1030,$upload->getError());
        } else {

            if(!empty($info[0])){
                foreach($info as $k => $v){
                    $photo['img_url'][$k] =  $v['savepath'] . $v['savename'];
                }
            }
            if(!empty($info['file'])){

                $photo['img_url'] =  $info['file']['savepath'] . $info['file']['savename'];

            }
            //保存图片路径
            //$image = new \Think\Image();
            //$image->open($photo);
            //$image->thumb(271, 188)->save($photo);
            //$maps["img"] = substr_replace($photo, "", 0, 1);
            return  $photo;

        }
    }

    //去除数组null值
    public function dealNull($inputArray){
        $newArr = array();
        foreach ($inputArray as $key=>$value){
            if(is_array($value)){
                $newArr[$key] = self::dealNull($value);
            }else{
                if(is_null($value)){
                    $value = "";
                }
                $newArr[$key] = $value;
            }
        }
        return $newArr;
    }

}