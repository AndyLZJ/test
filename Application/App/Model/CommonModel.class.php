<?php
/**
 * Created by PhpStorm.
 * User: @Andy-lizhongjian
 * Date: 2017/2/23
 * Time: 11:00
 */

namespace App\Model;

use Think\Model;

/**
 * 基类模型
 * Class CommonModel
 * @package Mobile\Model
 */
class CommonModel extends Model
{
    const TIME_KEY = 'MOB_time';
    const HASH_PRE = '(%*&)(&^#@!Adadf$$^*shoes';//用来生成token的hash字符串
    const TOKEN_EXPIRE = 2592000;  // token有效期一个月
    const SECRET_KEY = 'rA21VeE8347bScsuIDNq';
    /**
     * 封装成功返回的方法(Model层返回）
     * @param $code  返回信息提示号
     * @param $message  返回信息
     * @param array $data  返回数据集
     * @return array
     */
    public function success($code,$message, $data = array()) {
        $data = $this->dealNull($data);
        if (empty($data)) {//当需要返回数据为空时执行
            return array(
                'code' => $code,
                'message' => $message,
            );
        }else{
            return array(//当需要返回数据不为空时执行
                'code' => $code,
                'message' => $message,
                'data' => $data
            );
        }
    }

    /**
     * 封装失败返回的方法(Model层返回）
     * @param $code  返回信息提示号
     * @param $message  返回信息
     * @param array $data  返回数据集
     * @return array
     */
    public function error($code, $message, $data = array()) {
        $data = $this->dealNull($data);
        if (empty($data)) {//当需要返回数据为空时执行
            return array(
                'code' => $code,
                'message' => $message,
            );
        }else{
            return array(//当需要返回数据不为空时执行
                'code' => $code,
                'message' => $message,
                'data' => $data
            );
        }
    }

    /**
     * 生成token
     * @param  int $mobile 手机号
     * @return string 加密后的token值
     */
    public function makeToken($mobile) {
        return md5(self::HASH_PRE . $mobile . time());
    }


    /*@param $str
     * 允许密码输入6-12位
     */
    public function isPassword($str)
    {
        if (!preg_match('/^[_0-9a-z]{6,16}$/i', $str)) {
            return false;
        } else {
            return true;
        }
    }

    /*
     * 验证邮箱格式
     */
    public function is_email($email_address){
        $pattern = "/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i";
        if(preg_match($pattern, $email_address)){
            return  true;
        }else{
            return false;
        }
    }

    /*
     * 需要校验的字符串 $val
     * $min 最小长度  
     * $max  最大长度
     */
    function isStrLength($val, $min, $max)
    {
        $val= trim($val);
        return (preg_match("^[a-zA-Z0-9]{".$min.",".$max."}$",$val)) ? true : false;
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

    /*
     * 获取树状结构方法
     */
    function tree($data = array(), $pid = 0, $deep = 0, &$new = array()) {

        static $i;
        $i++;
        $deep++;

        foreach ($data as $k => $v) {
            //循环第一次把一级的评论数据放入一个数组中
                 $item[$k] = $v;
            foreach($data as $list){

                if($v['id'] == $list['pid']){
                    $item[$k]['child'][] = $list;
                }
            }
        }
        return $item;
    }

    /**
     * 组织构架左侧树形
     */
    public function trees($pid){

        $rule_list = M("tissue_rule")->select();
        if($pid==null){
            $pid=0;
        }
        //获取一级分类
        $top = M("tissue_rule")->where("id=".$pid)->find();
        // 获取一级下所有下级分类
        $item = \Org\Nx\Data::channelLevel($rule_list,$pid,'&nbsp;','id');
        $top['second_level'] = $item;
        return $top;
    }



    //获取子评论pid
    public function getCommentChild($cid, $cidStr){
        $cid += 0;
        if(!is_int($cid) || $cid < 0){
            return false;
        }

        $cat = M("colligate_comment")->where("pid=".$cid)->select();
        if($cat){
            foreach ($cat as $key=>$v){
                $cidStr .= $v["id"] . ",";
                $cidStr = self::getCommentChild($v["id"], $cidStr);
            }
        }
        return $cidStr;
    }

/*
 * 获取子评论pid
 */
    public function getFriendCommentChild($cid, $cidStr){
        $cid += 0;
        if(!is_int($cid) || $cid < 0){
            return false;
        }
        $cat = M("FriendsCircle")->where("pid=".$cid)->select();
        if($cat){
            foreach ($cat as $key=>$v){
                $cidStr .= $v["id"] . ",";
                $cidStr = self::getFriendCommentChild($v["id"], $cidStr);
            }
        }
        return $cidStr;
    }
    /**
     * 获取子孙全部数据
     * @param  string $$arr  传入的数组
     * @param  string $type  tree获取树形结构 level获取层级结构
     * @param  string $order 排序方式
     * @return array         结构数据
     */

    public function getTreeDatas($arr,$type='tree',$order='',$name='name',$child='id',$parent='pid'){

        $data = $arr;
        // 获取树形或者结构数据
        if($type=='tree'){
            $data=\Org\Nx\Data::tree($data,$name,$child,$parent);
        }elseif($type="level"){
            $data=\Org\Nx\Data::channelLevel($data,0,'&nbsp;',$child);
        }
        return $data;
    }


    /*
     * 根据子评论id查找父级id（工作圈）
     */
    public function findParentId($pid){
        $info = M('FriendsCircle')->where(array('id'=>$pid))->find();
        if($info['pid'] != 0){
            $info['id'] =  $this->findParentId($info['pid']);
        }
            return $info['id'];

    }

    /*
    * 根据子评论id查找父级id(课程)
    */
    public function findCourseParentId($pid){
        $info = M('Colligate_comment')->where(array('id'=>$pid))->find();
        if($info['pid'] != 0){
            $info['id'] =  $this->findParentId($info['pid']);
        }
        return $info['id'];

    }


    /**
     * 个人中心 学分 学时 学分来源
     * @$typeid 学分类型
     * @$source_id 课程id
     * @$project_id 项目id
     */
    public function centerStudy($typeid,$source_id){

            switch($typeid){
                case 0:
                    $examination = M("examination")->field("test_name")->where("id=".$source_id)->find();
                    $rows = "我的考试-".$examination['test_name'];
                    break;
                case 1:
                    $rows = "项目调研";
                    $survey = M("survey")->field("survey_name")->where("id=".$source_id)->find();
                    $rows = "项目调研-".$survey['survey_name'];
                    break;
                case 2:
                    $rows = "我的课程";
                    break;
                case 3:
                    $research = M("research")->field("research_name")->where("id=".$source_id)->find();
                    $rows = "其它调研-".$research['research_name'];
                    break;
                case 4:
                    $course = M("course")->field("course_name")->where("id=".$source_id)->find();
                    $rows = "必修课程-".$course['course_name'];
                    break;
                case 5:
                    $course = M("course")->field("course_name")->where("id=".$source_id)->find();
                    $rows = "选修课程-".$course['course_name'];
                    break;
                default:
                    $rows = "其它";
            }

            return $rows;

    }


    /**
     * 用户学分添加
     * $typeid 添加学分的类型
     * $credit 学分
     * $source_id 考试/调研/课程id
     * $project_id 关联项目id 如果是被指定的则有项目id,否则就给默认值0
     * $userId 用户id
     *
     */
    public function creditAdd($typeid,$credit,$source_id,$project_id = 0,$userId){

        try {

            $DB = M('center_study');

            $DB->startTrans();

            $data = array(
                "create_time"=>date("Y-m-d H:i:s",time()),
                "typeid"=>$typeid,
                "credit"=>$credit,
                "source_id"=>$source_id,
                "project_id"=>$project_id,
                "user_id"=>$userId
            );

            $DB->data($data)->add();

            $DB->commit();

        } catch ( Exception $e ) {

            $DB->rollback();

        }
    }



    /**
     * @param $input
     * @param $columnKey
     * 对数组按照某个字段作排序
     * @param null $indexKey
     * @return array
     */
    function i_array_column($input, $columnKey, $indexKey=null){
        if(!function_exists('array_column')){
            $columnKeyIsNumber  = (is_numeric($columnKey))?true:false;
            $indexKeyIsNull            = (is_null($indexKey))?true :false;
            $indexKeyIsNumber     = (is_numeric($indexKey))?true:false;
            $result                         = array();
            foreach((array)$input as $key=>$row){
                if($columnKeyIsNumber){
                    $tmp= array_slice($row, $columnKey, 1);
                    $tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null;
                }else{
                    $tmp= isset($row[$columnKey])?$row[$columnKey]:null;
                }
                if(!$indexKeyIsNull){
                    if($indexKeyIsNumber){
                        $key = array_slice($row, $indexKey, 1);
                        $key = (is_array($key) && !empty($key))?current($key):null;
                        $key = is_null($key)?0:$key;
                    }else{
                        $key = isset($row[$indexKey])?$row[$indexKey]:0;
                    }
                }
                $result[$key] = $tmp;
            }
            return $result;
        }else{
            return array_column($input, $columnKey, $indexKey);
        }
    }
}