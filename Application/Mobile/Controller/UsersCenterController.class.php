<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 17:14
 */

namespace Mobile\Controller;

use Think\Controller;

/**
 * Class UsersCenterController
 * @package Mobile\Controller
 * 个人中心控制器
 */

class UsersCenterController extends CommonController
{
    /**
     * 初始化
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * @邮箱验证码登录后，设置新密码
     * @email 邮箱
     * @token 用户身份标识
     * @secret_key 秘钥
     */
    public function setNewPassword(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $data['token'] = I('post.token','','trim,htmlentities');
        $data['secret_key'] = I('post.secret_key','','trim,htmlentities');
        $data['password'] = I('post.password','','trim,htmlentities');
        $data['repassword'] = I('post.repassword','','trim,htmlentities');
        $Users = D('UsersCenter');
        if(!$Users->create($data,1)){
            $this->error(1030,$Users->getError());
        }else{
            $result = $Users->setNewPassword($data);
            if (!empty($result['data'])) {
                $this->success($result['code'], $result['message'], $result['data']);
            } else {
                $this->error($result['code'], $result['message']);
            }
        }
    }
    /**
     * 修改密码
     */
    public function modifyPassWord()
    {
        $data = array();
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $data['oldPassword'] = I('post.oldPwd', '', 'trim');
        $data['password'] = I('post.password', '', 'trim');
        $data['repassword'] = I('post.confirm', '', 'trim');
        $Users = D('UsersCenter');
        if(!$Users->create($data,1)){
            $this->error(1030,$Users->getError());
        }else{
            $result = $Users->setPassword($data,$userId);
            if (!empty($result['data'])) {
                $this->success($result['code'], $result['message'], $result['data']);
            } else {
                $this->error($result['code'], $result['message']);
            }
        }

    }


    /**
     * 密码修改
     * @param   array $data 参数数组
     * @param   string $confirm 确认密码
     * @error   array                    错误代码,错误信息
     */
    protected function _callPasswordFunc($data = array(), $confirm)
    {
        if ($confirm != $data['password']) {
            $this->error(1007, '密码和确认密码不一致');
        }
        $result = D('UsersCenter')->setPassword($data);
        if (!empty($result['data'])) {
            $this->success($result['code'], $result['message'], $result['data']);
        } else {
            $this->error($result['code'], $result['message']);
        }
    }

    /**
     * 个人资料获取
     */
    public function getPersonInfo()
    {
        if (IS_GET) {
            $user_id = $this->getUserId();
            if (empty($user_id)) {
                $this->error(1024, '用户不存在');
                die();
            }
            $info = D('UsersCenter')->getPersonInfo($user_id);
            $this->success(1000, '获取数据成功', $info);
        } else {
            $this->error(1013, '不合法数据请求');
        }
    }

    /**
     * 个人资料修改
     */
    public function modifyPersonInfo()
    {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataPost();
            $post = I('post.');
            $info = D('UsersCenter')->checkData($post, $userId);
            if (empty($info['data'])) {
                $this->error($info['code'], $info['message']);
            } else {
                //修改个人资料触发获取积分
                $res = D('Trigger')->intergrationTrigger($userId,3);
                $this->success($info['code'], $info['message'], $info['data']);

            }
    }


    /**
     * 修改头像
     */
    public function editAvatar()
    {


        //echo 9;exit;
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        if (!empty($_FILES["file"]["name"])) {


            //图片上传设置
            $config = array(
                'maxSize' => 3145728,
                'savePath' => '/Upload/avatar/',
                'rootPath' => './',
                'saveName' => array('uniqid', ''),
                'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub' => true,
                'subName' => array('date', 'Ymd')
            );

            $xhr = $this->uploadImages($config);


            if ($xhr) {
                $this->success(1000, '上传成功', $xhr);
            } else {
                $this->error(1025, '上传失败');
            }
        } else {
            $this->error(1025, '没有文件被上传');
        }
    }


    /**
     * 找人PK
     */
    public function my_pk()
    {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $items = D('UsersCenter')->my_pk($userId);
        $items['token'] = I('get.token');
        $items['secret_key'] = I('get.secret_key');
        $items['my_list'] = json_encode($items['my_list']);
        $this->assign('items', $items);
        $this->display("my_pk");
    }

    /**
     * 获取pk成员列表
     */
    public function pkMember()
    {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $items = D('UsersCenter')->pkMember($userId);

        $items['token'] = I('get.token');
        $items['secret_key'] = I('get.secret_key');
        if ($items) {
            $this->assign("items", $items);
            $this->assign("list", $items['part_list']);
        } else {
            $this->assign("data", '赞无数据返回');
        }
        $this->display('select_personnel');

    }

    /**
     *  获取成员PK结果
     */
    public function memberPk()
    {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        //pk对象的用户id
        $pk_id = I("get.pk_id", '', 'int');
        $items = D('UsersCenter')->memberAjax($pk_id, $userId);
        $items['token'] = I('get.token');
        $items['secret_key'] = I('get.secret_key');
        if ($items) {
            $items['my_list'] = json_encode($items['my_list']);
            $items['pk_list'] = json_encode($items['pk_list']);
            $this->assign("items", $items);
        } else {
            $this->assign("items", '赞无数据返回');
        }
        $this->display('personnel_pk');
    }

    /**
     *  获取部门列表
     */
    public function pkDepartment()
    {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $items = D('UsersCenter')->pkDepartment($userId);
        $items['token'] = I('get.token');
        $items['secret_key'] = I('get.secret_key');
        $this->assign("tree_items", $items['tree_items']);
        $this->assign("tissue_name", $items['tissue_name']);
        $this->assign("items", $items);
        $this->display('select_department');
    }

    /**
     * 计算部门pk结果
     */
    public function departmentPk()
    {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        //接收所选部门$dpk_id
        $dpk_id = I("get.dpk_id", '', 'int');
        $items = D('UsersCenter')->departmentPk($dpk_id, $userId);
        $items['token'] = I('get.token');
        $items['secret_key'] = I('get.secret_key');
        $this->assign("items", $items);
        $this->display('department_pk');
    }


    /*
    * app扫码pc端登录
    */
    public function qrcodeLogin()
    {
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $qrCode = I('post.qrcode', '', 'trim');
        $uid = I('post.uid', '', 'int');
        if ($qrCode == '' || $uid == '') {
            return $this->error(1023, '缺少用户id或二维码标识');
        }
        if ($qrCode)
            $res = M('users')->where(array('id' => $uid))->save(array('qrcode' => $qrCode));
        if (!$res) {
            return $this->error(1023, '扫码登录失败，请刷新二维码后重新扫描');
        } else {
            return $this->success(1000, '扫码登录成功');
        }
    }

   /*
    * 比较两个字符串的长度
    */
    public function  qrt(){
           $a = I('get.gg');
           $b = I('get.b');
           $c = strlen($a);
           $d = strlen($b);
           echo $c.'--'.$d;
        }


    /***
     *扫码考勤接口
    MOBILE接口调用实例,参数：$project_id,$course_id,$uid
    public function  test1(){
    $data =  R('Admin/Attendance/qrcodeAttendance',array(157,85,312));
    }
     */

    public function  qrCodeAttendance(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $course_id = I('post.id','','int');//课程id
        $project_id = I('post.pid','','int');//课程关联项目id
        $uid = I('post.uid','','int');//用户id
        //$data = R('Admin/Attendance/qrcodeAttendance',array($project_id,$course_id,$uid));
        //$data =  D('Trigger')->qrcodeAttendance(157,85,312);
        $data = D('Trigger')->qrcodeAttendance($project_id,$course_id,$uid);
        if($data){
            $this->success($data['code'],$data['message']);
        }
    }

    /***
     *扫码考勤接口
    MOBILE接口调用实例,参数：$project_id,$course_id,$uid
    public function  test1(){
    $data =  R('Admin/Attendance/qrcodeAttendance',array(157,85,312));
    }
     */

    public function  qrcodeAttendances($project_id,$course_id,$uid){
        $map = array(
            'pid'=>$project_id,
            'uid'=>$uid,
            'course_id'=>$course_id,
        );
        $where = array(
            'project_id'=>$project_id,
            'course_id'=>$course_id,
        );
        $res = M('attendance')->where($map)->find();
        if(!$res){
            $data = array(
                "code" => 1023,
                "message" => "用户不在考勤范围!",
            );
            return $data;
        }

        if($res['mobile_scanning'] == 1 || $res['status'] != ''){
            $data = array(
                "code" => 1024,
                "message" => "你已考勤，请勿重复考勤!",
            );
            return $data;
        }


        $res = M('project_course')->where($where)->find();
        if(time() <= strtotime($res['start_time'])){
            $svaeStatus = array('status'=>1,'mobile_scanning'=>1,'attendance_time'=>date('Y-m-d H:i:s'));
            $res = M('attendance')->where($map)->save($svaeStatus);
            $data = array(
                "code" => 1000,
                "message" => "考勤-按时",
            );
            return $data;
        }else if(strtotime($res['end_time']) > time() && time() > strtotime($res['start_time'])){
            $svaeStatus = array('status'=>2,'mobile_scanning'=>1,'attendance_time'=>date('Y-m-d H:i:s'));
            $res = M('attendance')->where($map)->save($svaeStatus);
            $data = array(
                "code" => 1025,
                "message" => "考勤-迟到",
            );
            return $data;
        }else if(strtotime($res['end_time']) < time()){
            $svaeStatus = array('status'=>0,'mobile_scanning'=>1,'attendance_time'=>date('Y-m-d H:i:s'));
            $res = M('attendance')->where($map)->save($svaeStatus);
            $data = array(
                "code" => 1026,
                "message" => "考勤-缺勤",
            );
            return $data;
        }

    }



    /*
     * 关于我们  ----app版权
     */
    public function copyRight(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $this->display('index');
    }

    /*
     * 帮助中心
     */
    public function helpCenter(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $this->display('help');
    }
}
