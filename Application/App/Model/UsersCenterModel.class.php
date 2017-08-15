<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 17:18
 */

namespace App\Model;

use Think\Model;

/**个人中心模型
 * @user lizhongjian
 * Class UsersCenterModel
 * @package Mobile\Model
 */

class UsersCenterModel extends CommonModel {

    protected $tablePrefix = 'think_';
    protected $tableName = 'users';


    protected $_validate = array(
        array('username', 'empty', '用户名不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('username', '5,100', '用户名长度超出限制', Model::EXISTS_VALIDATE, 'length'),
        array('phone', '/1[34578]{1}\d{9}$/', '请输入正确的手机号格式', Model::EXISTS_VALIDATE, 'regex'),
        array('password', 'empty', '密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('confirm', 'empty', '确认密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        //array('password', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'checkValid', '密码不能有中文', Model::EXISTS_VALIDATE, 'callback'),
        array('oldPassword', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'isPassword', '只允许输入6-20位由 数字、字母、下划线组成的密码', Model::EXISTS_VALIDATE, 'callback'),
        array('phone', 'empty', '手机号码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('phone', 'is_numeric', '手机号码必须为数字', Model::EXISTS_VALIDATE, 'function')
    );


    public function checkValid() {
        $pass = I('post.password', '', 'trim');
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $pass)) {
            return false;
        }
        return true;
    }

    /*
     * 修改密码
     * @param array $data  修改密码前所需的字段
     * @param string $oldPassword 原密码
     */
    public function setPassword($data) {
        if (empty($data['oldPassword'])) {
            return $this->error(1019, '旧密码不能为空');
        }
        $model = M('Users');
        $result = $model->where(array("phone" => $data['phone']))->order('id desc')->find();
        $data['oldPassword'] = md5($data['oldPassword']);

        if ($data['oldPassword'] != $result['password'] ) {
            return $this->error(1022, '请输入正确的原密码');
        }
        unset($data['oldPassword']);
        if (!$this->create($data)) {
            return $this->error(1023, $this->getError());
        }
        $data['password'] = md5($data['password']);
        $res = $model->where(array('phone' => $data['phone']))->save($data);
        if (!$res) {
            return $this->error(1029, '修改失败');
        }
        $info['id'] = $res;
        return $this->success(1000,'修改成功',$info);
    }

    /**
     * 获取登录用户个人资料
     * @param $uid 用户id
     * @return $info 用户个人信息数组
     */
    public function getPersonInfo($uid){
        $info = M('Users as a')
            ->field('a.id,a.avatar,a.username,a.phone,a.email,a.personalized_signature,c.name,d.name as dname')
            ->join('LEFT JOIN __TISSUE_GROUP_ACCESS__ as b ON b.uid = a.id LEFT JOIN  __TISSUE_RULE__ as c ON b.tissue_id = c.id LEFT JOIN  __JOBS_MANAGE__ as d ON b.job_id = d.id')
            ->where(array('a.id'=>$uid))
            ->find();
        $info['avatar'] ? $info['avatar'] : '/Upload/avatar/20170216/58a55160712b1.jpg';
        return $info;
    }


    /*
     * 验证修改个人资料数据输入合法性
     * @param $post
     * @param $uid
     */
    public function checkData($post,$uid){
        $data['avatar'] = $post['headImg'];
        $data['personalized_signature'] = $post['personalized_signature'];
        $data['email'] = $post['email'];
        if(empty($data['avatar'])){
            return  $this->error(1023,'缺少必要参数');die();
        }
        if(empty($data['personalized_signature'])){
            $post['personalized_signature'] = "这家伙很懒，还没有介绍自己哦";
        }
        if(empty($data['email'])){
            return  $this->error(1026,'邮箱不能为空');die();
        }else{
            
        if($this->is_email($data['email'])){
                $info = M('Users')->where(array('id'=>$uid))->data($data)->save();
                if($info){
                    $info['id'] = $info;
                    return $this->success(1000,'修改成功',$info);//修改成功
                }else{
                    return $this->error(1029,'修改失败');die();//修改失败
                }
            }else{
                return $this->error(1028,'请填写正确邮箱');die();//请填写正确邮箱
            }
        }

    }


    /**
     * 找人Pk
     */
    public function my_pk($userId){
        //mktime() 函数用于从日期取得时间戳，成功返回时间戳，否则返回 FALSE 。
        $start_time = mktime(0,0,0,date('m'),1,date('Y'));
       //计算当月至结束时间所包含的天数
        $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));

        $where['time'] = array(array('egt',$start_time),array('elt',$end_time));

        $where['uid'] = array("eq",$userId);

        $where['score'] = array("gt",0);

        //获取当月积分
        $total_integral = M('integration_record')->where($where)->sum('score');

        //获取自己数据

        //查询自己当前月的积分
        $my_items = M('integration_record')->where($where)->select();

        $my_user = $this->pkData($my_items);

        $total_integral = array_sum($my_user);
       /* $my_list =  array();

        //比较PK数据
        for($i=0;$i<=5;$i++){

            $result = $this->myPercentage($my_user[$i]);
            $my_list[$i] = $result;

        }*/
        $total_integral = $total_integral ? $total_integral : 0;
       //获取我的头像和用户名
        $user = M('Users')->field('username,avatar')->where(array('id'=>$userId))->find();
        $data = array(
            "username"=>$user['username'],
            "avatar"=>$user['avatar'],
            "integral"=>$total_integral,
            "my_list" => $my_user
        );

        return $data;
    }


    /**
     * 获取pk成员列表
     */

    public function pkMember($userId){
            if(!$userId){
                return array("code"=>1021, "message"=>'提交失败，未获取到用户id');
            }
            $part = array();

            //一级
            $tissue1 = M("tissue_rule")->field("id,pid,name")->where("pid=0")->limit(1)->find();

            $part["id"] = $tissue1["id"];
            //$part["pid"] = $tissue1[0]["pid"];
            $part["name"] = $tissue1["name"];

            //二级（总公司部门 / 分公司）
            $tissue2 = M("tissue_rule")->field("id,pid,name")->where("pid=".$tissue1["id"])->select();
            $key2 = 0;
            $sub_list = array();
            foreach($tissue2 as $key2=>$value2){
                $sub_list[$key2]["id"] = $value2["id"];
                //$sub_list[$key2]["pid"] = $value2["pid"];
                $sub_list[$key2]["name"] = $value2["name"];
                $sub_list[$key2]["is_part"] = 0;

                //三级（分公司部门）
                $tissue3 = M("tissue_rule")->field("id,pid,name")->where("pid=".$value2["id"])->select();
                if($tissue3){
                    $key3 = 0;
                    $sub_list2 = array();
                    foreach($tissue3 as $key3=>$value3){
                        $sub_list2[$key3]["id"] = $value3["id"];
                        //查询每个部门下面的成员
                        $tissue4 = M("Tissue_group_access")->field("uid")->where("tissue_id=".$value3["id"])->select();
                        foreach($tissue4 as $key4 => $val4){
                            $sub_lists = M('Users')->field('id,username,avatar')->where(array('id'=>$val4['uid']))->find();
                            $sub_list2[$key3]["sub_lists"][$key4] =  $sub_lists;
                        }
                        $sub_list2[$key3]["name"] = $value3["name"];
                        $key3 ++;
                    }

                    $sub_list[$key2]["is_part"] = 1;
                    $sub_list[$key2]["sub_list"] = $sub_list2;
                }
                $key2 ++;
            }

            $part["part_list"] = $sub_list;
            /*
            //获取该公司下的所有管理员
            $admin = M("tissue_group_access as a")
                ->field("a.uid,b.username,b.avatar")
                ->join("JOIN __USERS__ as b ON a.uid=b.id")
                ->where("manage_id=1")->select();
            $key = count($part);
            foreach ($admin as $akey=>$value){
                $part[$key]["uid"] = $admin[$akey]["uid"];
                $part[$key]["username"] = $admin[$akey]["username"];
                $part[$key]["dataType"] = 2;
                $key ++;
            } */
            return $part;
    }

    /**
     * 自己和个人pk结果计算
     */
    public function memberAjax($pk_id,$userId){

       if($pk_id == ''){
           return $this->error(1023,'缺少必要参数');
       }
        //获取PK对象数据
        $where['uid'] = array("eq",$pk_id);
        $where['score'] = array("gt",0);
        //mktime() 函数用于从日期取得时间戳，成功返回时间戳，否则返回 FALSE 。
        //当前月开始时间
        $start_time = mktime(0,0,0,date('m'),1,date('Y'));
        //当前月结束时间
        $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));

        $where['time'] = array(array('egt',$start_time),array('elt',$end_time));
        //查询pk对象当前月的积分
        $pk_items = M('integration_record')->where($where)->select();

        //查询用户名
        $pk_username =  M('users')->field("username,avatar")->where("id=".$pk_id)->find();
        $my_username =  M('users')->field("username,avatar")->where(array('id'=>$userId))->find();
        $pk_user = $this->pkData($pk_items);

        //获取自己数据
        $condition['uid'] = array("eq",$userId);
        $condition['score'] = array("gt",0);
        $condition['time'] = array(array('egt',$start_time),array('elt',$end_time));
       //查询自己当前月的积分
        $my_items = M('integration_record')->where($condition)->select();
        //$my_integral = M('integration_record')->where($condition)->sum('score');

        $my_user = $this->pkData($my_items);

       /* $my_list = $pk_list = array();

        //比较PK数据
        for($i=0;$i<=5;$i++){

            $result = $this->percentage($pk_user[$i],$my_user[$i]);
            $pk_list[$i] = $result[0];
            $my_list[$i] = $result[1];

        }*/

        //PK对象当月月积分
        //$pk_integral = M('integration_record')->where($where)->sum('score');


        //PK月积分
        $pk_integral = array_sum($pk_user);
        $pk_integral = $pk_integral ? $pk_integral : 0;
        $my_integral = array_sum($my_user);
        $my_integral = $my_integral ? $my_integral : 0;
        $data = array(
            "pk_name"=>$pk_username['username'],
            "pk_avatar"=>$pk_username['avatar'],
            "pk_integral"=>$pk_integral,
            "pk_list"=>$pk_user,
            "my_name"=>$my_username['username'],
            "my_avatar"=>$my_username['avatar'],
            "my_integral" => $my_integral,
            "my_list"=>$my_user,
        );
        return $data;

    }


    /**
     * @param $data
     * @return array
     * 获取PK前用户数据
     */
    public function myPkData($data){

        $type_name = array("好为人师","乐分享","系统达人","任务范儿","爱学习","我是学霸");

        $pk_user = array(0,0,0,0,0,0);

        foreach($data as $item){

            switch($item['type']){
                case $type_name[0]:
                    $pk_user[0] += $item['score'];
                    break;
                case $type_name[1]:
                    $pk_user[1] += $item['score'];
                    break;
                case $type_name[2]:
                    $pk_user[2] += $item['score'];
                    break;
                case $type_name[3]:
                    $pk_user[3] += $item['score'];
                    break;
                case $type_name[4]:
                    $pk_user[4] += $item['score'];
                    break;
                case $type_name[5]:
                    $pk_user[5] += $item['score'];
                    break;
            }

        }

        return $pk_user;

    }

    /**
     * @param $data
     * @return array
     * 获取PK后 自己和pk对象的数据
     */
    public function pkData($data){

        $type_name = array("好为人师","乐分享","系统达人","任务范儿","爱学习","我是学霸");

        $pk_user = array(0,0,0,0,0,0);

        foreach($data as $item){

            switch($item['type']){
                case $type_name[0]:
                    $pk_user[0] += $item['score'];
                    break;
                case $type_name[1]:
                    $pk_user[1] += $item['score'];
                    break;
                case $type_name[2]:
                    $pk_user[2] += $item['score'];
                    break;
                case $type_name[3]:
                    $pk_user[3] += $item['score'];
                    break;
                case $type_name[4]:
                    $pk_user[4] += $item['score'];
                    break;
                case $type_name[5]:
                    $pk_user[5] += $item['score'];
                    break;
            }

        }

        return $pk_user;

    }

    /**
     * 选择部门PK列表
     */
    public function pkDepartment($userId){
        if(!$userId){
            return array("code"=>1021, "message"=>'提交失败，未获取到用户id');
        }
        $part = array();
        //根据用户id查询其所属的分公司或者公司
        $partMent = M('tissue_group_access a')->join('LEFT JOIN __TISSUE_RULE__ b ON b.id = a.tissue_id')->where(array('a.uid'=>$userId))->find();
        if($partMent['pid'] != 1){
            $partMent = M("tissue_rule")->field("id,pid,name")->where(array('id'=>$partMent['pid']))->limit(1)->find();
        }
        $tissue1 = M("tissue_rule")->field("id,pid,name")->where(array("pid"=>$partMent['id']))->select();
        //循环部门拼接分公司
        $partMent['sub_list'] = $tissue1;
        return $partMent;


    }

    /**
     * 获取组织架信息
     */
    public function treeInfo($id){

        //获取左侧树形结构
        $tree_items = $this->trees($id);

        return $tree_items;
    }

    /**
     * 计算部门pk结果
     */
    public function departmentPk($dpk_id,$userId){


           if($dpk_id === ''){
               return $this->error(1023,'缺少部门参数id');
           }

            //获取PK对象数据
            $dpk_total = $this->getpk($dpk_id);
            $dpk_name =  M('tissue_rule')->field("name")->where("id=".$dpk_id)->find();

            //获取自己数据
            $condition['uid'] = array("eq",$userId);
            $my_tissue_id = M('tissue_group_access')->field('tissue_id')->where($condition)->find();
            $my_total = $this->getpk($my_tissue_id['tissue_id']);
            $my_name =  M('tissue_rule')->field("name")->where("id=".$my_tissue_id['tissue_id'])->find();

            //计算平均值   getpk 已计算平均值
            /*
            $my = M('tissue_group_access')->field('tissue_id')->where("tissue_id=".$my_tissue_id['tissue_id'])->count();
           	$pk = M('tissue_group_access')->field('tissue_id')->where("tissue_id=".$dpk_id)->count();

            if(empty($my)){
                $my_total = 0;
            }else{
                $my_total = round($my_total / $my);
            }

            if(empty($pk)){
                $pk_total = 0;
            }else{
                $pk_total  = round($dpk_total / $pk);
            }
			*/
            
            $pk_total = $dpk_total;
            
            $data = array(
                "pk_name"=>$dpk_name['name'],
                "pk_total"=>$pk_total,
                "my_total"=>$my_total,
                "my_name"=>$my_name['name']
            );

            return $data;
    }

    /**
     * 部门PK公共函数
     */
    public function getpk($tissue_id){

        $where['tissue_id'] = array("eq",$tissue_id);

        $list = array();

        $items = M('tissue_group_access')->field('uid')->where($where)->select();

        if(empty($items)){

            $total = 0;

        }else{

            foreach($items as $item){
                $list[] = $item['uid'];
            }

            $where = array();
            $where['score'] = array("gt",0);
            $where['uid'] = array("in",$list);
            $start_time = mktime(0,0,0,date('m'),1,date('Y'));
            $end_time = mktime(23,59,59,date('m'),date('t'),date('Y'));
            $where['time'] = array(array('egt',$start_time),array('elt',$end_time));

            $integration_list = M('integration_record')->where($where)->avg('score');
            $integration_list = $integration_list ? $integration_list : 0;

            //合并部门总值
            $total = round($integration_list);

        }

        return $total;

    }
}