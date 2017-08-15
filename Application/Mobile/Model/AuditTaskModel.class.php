<?php
namespace Mobile\Model;

use Think\Model;
/**
 * @author Lizhongjian 20170519
 * 我的任务
 */
class AuditTaskModel extends CommonModel {
    protected $tablePrefix = 'think_';
    protected $tableName = 'auth_group_access';



    /*
     * 获取用户角色
     *$uid 用户id
     */
    public function getUserRole($uid){

    	$result = M("Auth_group_access a")
            ->join('LEFT JOIN __AUTH_GROUP__ b ON b.id = a.group_id')
            ->field("a.*,b.title,b.status")
            ->where(array("a.uid" => $uid))->select();

        return $result;
    }
    
    /**
     * 任务列表
     * taskStatus 任务状态 1待我审核 (type 1待办任务 2已完成)   2我发起的  不传默认taskStatus为1，type为1
     * page 页码，不传默认第一页
     */
    public function getAuditTaskList($taskType,$type,$page,$userId,$pageLen)
    {

        //接收用户id,获取用户角色
        $userRole = $this->getUserRole($userId);

        //循环获取用户角色id放入新数组容器中
        $arr = array();
        foreach ($userRole as $v) {
            array_push($arr, $v['group_id']);//array_push往数组中插入一个或者多个元素
        }

        //把数组用英文逗号拼接成字符串
        $group_id = implode(',', $arr);

        //根据用户拥有角色和审核状态判断审核级别
        //待我审核 $taskType = 1
        //b.type 1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册
        if ($taskType == 1) {
            //$type = 1 待办任务   待审核状态的任务列表
            if ($type == 1) {

                //项目审核条件
                $where1['b.type'] = 1;
                $where1['a.type'] = 2;

                //课程审核条件
                $where2['b.type'] = 2;
                $where2['a.status'] = 0;

                //新建试卷审核条件
                $where3['b.type'] = 3;
                $where3['a.status'] = 0;

                //课程审核条件 array('b.type' => 4, 'a.status' => 0)
                $where4['b.type'] = 4;
                $where4['a.status'] = 0;

                //发布工作圈审核条件 array('a.status' => 0, 'a.pid' => 0, 'b.type' => 5)
                $where5['b.type'] = 5;
                $where5['a.pid'] = 0;
                $where5['a.status'] = 0;

                //发起调研审核条件 array('a.audit_state' => 0, 'b.type' => 6)
                $where6['b.type'] = 6;
                $where6['a.audit_state'] = 0;

                //发起考试审核条件 array('a.audit_status' => 1, 'b.type' => 7)
                $where7['b.type'] = 7;
                $where7['a.audit_status'] = 1;

                //加分申请审核条件 array('a.status' => 0, 'b.type' => 8)
                $where8['b.type'] = 8;
                $where8['a.status'] = 0;

                //用户注册审核条件 array('a.status' => 2, 'b.type' => 9)
                $where9['b.type'] = 9;
                $where9['a.status'] = 2;

                //项目审核  b.type = 1
                //a.type'=>2 表示待审核的项目
                $list1 = M('admin_project')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where1)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.audit_status,a.add_time as create_time,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //新建课程审核  b.type = 2
                //a.status=>0 表示待审核的新建课程
                $list2 = M('course')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where2)
                    ->order('a.id desc')
                    ->field("a.*,FROM_UNIXTIME(a.create_time,'%Y-%m-%d %H:%i:%s') as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num")
                    ->select();


                //新建试卷  b.type = 3
                //a.status=>0 表示待审核的新建试卷
                $list3 = M('examination')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where3)
                    ->order('a.id desc')
                    ->field('a.*,a.test_upload_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //新建问卷  b.type = 4
                //a.status=>0 表示待审核的新建问卷
                $list4 = M('survey')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where4)
                    ->order('a.id desc')
                    ->field('a.*,a.survey_upload_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //发布工作圈  b.type = 5
                //a.status=>0 表示待审核的工作圈
                $list5 = M('friends_circle')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where5)
                    ->order('a.id desc')
                    ->field('a.*,a.publish_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //发起调研  b.type = 6
                //a.audit_state=>0=>1 表示待审核的调研
                $list6 = M('research')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where6)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //发起考试  b.type = 7
                //a.audit_status'=>1 表示待审核的考试
                $list7 = M('test')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where7)
                    ->order('a.id desc')
                    ->field('a.audit_status as status,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //加分申请  b.type = 8
                //a.status=>0 表示待审核的加分申请
                $list8 = M('integration_apply')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where8)
                    ->order('a.id desc')
                    ->field("a.*,FROM_UNIXTIME(a.add_time,'%Y-%m-%d %H:%i:%s') as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num")
                    ->select();


                //用户注册  b.type = 9
                //a.status=>2 表示待审核的注册用户
                $list9 = M('users')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where("( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id) )")
                    ->where($where9)
                    ->order('a.id desc')
                    ->field('a.*,a.register_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();
                $data = array_merge_recursive($list1,$list2,$list3,$list4,$list5,$list6,$list7,$list8,$list9);
                $array_data = array();
                foreach($data as $k => $v){
                    $array_data[$k]['id'] = $v['id'];
                    $array_data[$k]['audit_id'] = $v['audit_id'];
                    $array_data[$k]['audit_status'] = $v['audit_status'];
                    $array_data[$k]['create_time'] = $v['create_time'];
                    switch($v['type']){
                        //type=1 新建项目
                        case 1;
                            $array_data[$k]['common_name'] = '新建项目';
                            $array_data[$k]['type'] = 1;
                            break;

                        //type=2 新建课程
                        case 2;
                            $array_data[$k]['common_name'] = '新建课程';
                            $array_data[$k]['type'] = 2;
                            break;

                        //type=3 新建试卷
                        case 3;
                            $array_data[$k]['common_name'] = '新建试卷';
                            $array_data[$k]['type'] = 3;
                            break;


                        //type=4 新建问卷
                        case 4;
                            $array_data[$k]['common_name'] = '新建问卷';
                            $array_data[$k]['type'] = 4;
                            break;


                        //type=5 新建互动
                        case 5;
                            $array_data[$k]['common_name'] = '新建互动';
                            $array_data[$k]['type'] = 5;
                            break;


                        //type=6 发起调研
                        case 6;
                            $array_data[$k]['common_name'] = '发起调研';
                            $array_data[$k]['type'] = 6;
                            break;


                        //type=7 发起考试
                        case 7;
                            $array_data[$k]['common_name'] = '发起考试';
                            $array_data[$k]['type'] = 7;
                            break;


                        //type=8 发起加分
                        case 8;
                            $array_data[$k]['common_name'] = '发起加分';
                            $array_data[$k]['type'] = 8;
                            break;

                        //type=9 用户注册
                        case 9;
                            $array_data[$k]['common_name'] = '用户注册';
                            $array_data[$k]['type'] = 9;
                            break;
                    }
                }
            } else if ($type == 2) {//$type = 2 已完成(包括已拒绝和已通过的审核)

                //项目审核条件  array('a.type'=>array('in','0,3,4')) 0已通过 3已拒绝 4已完成
                $where1['b.type'] = 1;
                $where1['a.type'] = array('in', '0,3,4');

                //课程审核条件   array('a.status'=>array('in','1,2')) 1已通过 2已拒绝
                $where2['b.type'] = 2;
                $where2['a.status'] = array('in', '1,2');

                //新建试卷审核条件   array('a.status'=>array('in','1,2')) 1已通过 2已拒绝
                $where3['b.type'] = 3;
                $where3['a.status'] = array('in', '1,2');

                //新建问卷审核条件 array('a.status'=>array('in','1,3')) 1已通过 3已拒绝
                $where4['b.type'] = 4;
                $where4['a.status'] = array('in', '1,3');

                //发布工作圈审核条件 array('a.status' => 0, 'a.pid' => 0, 'b.type' => 5)  1已通过 2已拒绝
                $where5['b.type'] = 5;
                $where5['a.pid'] = 0;
                $where5['a.status'] = array('in', '1,2');

                //发起调研审核条件 array('a.audit_state' => 0, 'b.type' => 6) 1已通过  2已拒绝
                $where6['b.type'] = 6;
                $where6['a.audit_state'] = array('in', '1,2');

                //发起考试审核条件 array('a.audit_status' => 1, 'b.type' => 7)  1已通过  2已拒绝
                $where7['b.type'] = 7;
                $where7['a.audit_status'] = array('in', '1,2');

                //加分申请审核条件 array('a.status' => 0, 'b.type' => 8)   1已通过  2已拒绝
                $where8['b.type'] = 8;
                $where8['a.status'] = array('in', '1,2');

                //用户注册审核条件 array('a.status' => 2, 'b.type' => 9)  1已通过  0已拒绝
                $where9['b.type'] = 9;
                $where9['a.status'] = array('in', '1,0');

                //已拒绝和已完成的培训班
                $list1 = M('admin_project')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where1)
                    ->field('a.*,a.add_time as create_time,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //已拒绝和已通过的课程审核
                $list2 = M('course')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where2)
                    ->order('a.id desc')
                    ->field("a.*,FROM_UNIXTIME(a.create_time,'%Y-%m-%d %H:%i:%s') as create_time,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num")
                    ->select();


                //已拒绝和已通过的试卷审核
                $list3 = M('examination')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where3)
                    ->order('a.id desc')
                    ->field('a.*,a.test_upload_time as create_time,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //已拒绝和已通过的问卷审核
                $list4 = M('survey')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where4)
                    ->order('a.id desc')
                    ->field('a.*,a.survey_upload_time as create_time,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //已拒绝和已通过的工作圈审核
                $list5 = M('friends_circle')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where5)
                    ->order('a.id desc')
                    ->field('a.*,a.publish_time as create_time,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //已拒绝和已通过的调研审核
                $list6 = M('research')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where6)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //已拒绝和已通过的考试审核
                $list7 = M('test')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where7)
                    ->order('a.id desc')
                    ->field('a.audit_status as status,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();


                //已拒绝和已通过的加分审核
                $list8 = M('integration_apply')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where8)
                    ->order('a.id desc')
                    ->field("a.*,FROM_UNIXTIME(a.add_time,'%Y-%m-%d %H:%i:%s') as create_time,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num")
                    ->select();


                //已拒绝和已通过的用户注册审核
                $list9 = M('users')->alias('a')
                    ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                    ->where($where9)
                    ->order('a.id desc')
                    ->field('a.*,a.register_time as create_time,b.id as audit_id,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();

                $data = array_merge_recursive($list1, $list2, $list3, $list4, $list5, $list6, $list7, $list8, $list9);
                $array_data = array();
                foreach ($data as $k => $v) {
                    $array_data[$k]['id'] = $v['id'];
                    $array_data[$k]['audit_id'] = $v['audit_id'];
                    $array_data[$k]['audit_status'] = $v['audit_status'];
                    $array_data[$k]['create_time'] = $v['create_time'];
                    switch ($v['type']) {
                        //type=1 新建项目
                        case 1;
                            $array_data[$k]['common_name'] = '新建项目';
                            $array_data[$k]['type'] = 1;
                            break;

                        //type=2 新建课程
                        case 2;
                            $array_data[$k]['common_name'] = '新建课程';
                            $array_data[$k]['type'] = 2;
                            break;

                        //type=3 新建试卷
                        case 3;
                            $array_data[$k]['common_name'] = '新建试卷';
                            $array_data[$k]['type'] = 3;
                            break;


                        //type=4 新建问卷
                        case 4;
                            $array_data[$k]['common_name'] = '新建问卷';
                            $array_data[$k]['type'] = 4;
                            break;


                        //type=5 新建互动
                        case 5;
                            $array_data[$k]['common_name'] = '新建互动';
                            $array_data[$k]['type'] = 5;
                            break;


                        //type=6 发起调研
                        case 6;
                            $array_data[$k]['common_name'] = '发起调研';
                            $array_data[$k]['type'] = 6;
                            break;


                        //type=7 发起考试
                        case 7;
                            $array_data[$k]['common_name'] = '发起考试';
                            $array_data[$k]['type'] = 7;
                            break;


                        //type=8 发起加分
                        case 8;
                            $array_data[$k]['common_name'] = '发起加分';
                            $array_data[$k]['type'] = 8;
                            break;

                        //type=9 用户注册
                        case 9;
                            $array_data[$k]['common_name'] = '用户注册';
                            $array_data[$k]['type'] = 9;
                            break;
                    }
                }
            }



        } else if ($taskType == 2) {//我发起的  $taskType = 2

            //项目审核条件
            $where1['b.type'] = 1;
            $where1['a.type'] = array('in','0,2,3,4');


            //课程审核条件
            $where2['b.type'] = 2;
            $where2['a.status'] = array('in','0,1,2');


            //新建试卷审核条件
            $where3['b.type'] = 3;
            $where3['a.status'] = array('in','0,1,2');


            //问卷审核条件 array('b.type' => 4, 'a.status' => 0)
            $where4['b.type'] = 4;
            $where4['a.status'] = array('in','0,1,2');


            //发布工作圈审核条件 array('a.status' => 0, 'a.pid' => 0, 'b.type' => 5)
            $where5['b.type'] = 5;
            $where5['a.pid'] = 0;
            $where5['a.status'] = array('in','0,1,2');


            //发起调研审核条件 array('a.audit_state' => 0, 'b.type' => 6)
            $where6['b.type'] = 6;
            $where6['a.audit_state'] = array('in','0,1,2');


            //发起考试审核条件 array('a.audit_status' => 1, 'b.type' => 7)
            $where7['b.type'] = 7;
            $where7['a.audit_status'] = array('in','0,1,2');


            //加分申请审核条件 array('a.status' => 0, 'b.type' => 8)
            $where8['b.type'] = 8;
            $where8['a.status'] = array('in','0,1,2');


            //用户注册审核条件 array('a.status' => 2, 'b.type' => 9)
            $where9['b.type'] = 9;
            $where9['a.status'] = array('in','0,1,2');

            //关联用户id为uid
            $condition1 = "a.uid = ".$userId." AND (( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id)) OR b.audit_status = 3 ) OR ( b.audit_status = 4 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 5 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 6 AND b.threeaudit_role in ($group_id))";

            //关联用户id为user_id
            $condition2 = "a.user_id = ".$userId." AND (( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id)) OR b.audit_status = 3 ) OR ( b.audit_status = 4 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 5 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 6 AND b.threeaudit_role in ($group_id))";

            //关联用户id为test_heir
            $condition3 = "a.test_heir = ".$userId." AND (( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id)) OR b.audit_status = 3 ) OR ( b.audit_status = 4 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 5 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 6 AND b.threeaudit_role in ($group_id))";

            //关联用户id为create_user
            $condition4 = "a.survey_heir = ".$userId." AND (( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id)) OR b.audit_status = 3 ) OR ( b.audit_status = 4 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 5 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 6 AND b.threeaudit_role in ($group_id))";

            //关联用户id为survey_heir
            $condition5 = "a.create_user = ".$userId." AND (( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id)) OR b.audit_status = 3 ) OR ( b.audit_status = 4 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 5 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 6 AND b.threeaudit_role in ($group_id))";

            //关联用户id为id
            $condition6 = "a.id = ".$userId." AND (( b.audit_status = 0 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 1 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 2 AND b.threeaudit_role in ($group_id)) OR b.audit_status = 3 ) OR ( b.audit_status = 4 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 5 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 6 AND b.threeaudit_role in ($group_id))";


            //$where = "( b.audit_status = 3 ) OR ( b.audit_status = 4 AND b.oneaudit_role in ($group_id) ) OR ( b.audit_status = 5 AND b.twoaudit_role in ($group_id) ) OR ( b.audit_status = 6 AND b.threeaudit_role in ($group_id)";
            //项目审核  b.type = 1
            //a.type'=>2 表示待审核的项目
            //a.type'=>2 表示待审核的项目 audit_status=0 audit_status=1 audit_status=2  audit_status=3 (已通过)   audit_status=4 audit_status=5 audit_status=6(已拒绝)
            $list1 = M('admin_project')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition1)//uid
                ->where($where1)
                ->order('a.id desc')
                ->field('a.*,b.id as audit_id,b.audit_status,a.add_time as create_time,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();





            //新建课程审核  b.type = 2
            //a.status=>0 表示待审核的新建课程
            $list2 = M('course')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition2)//user_id
                ->where($where2)
                ->order('a.id desc')
                ->field("a.*,FROM_UNIXTIME(a.create_time,'%Y-%m-%d %H:%i:%s') as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num")
                ->select();




            //新建试卷  b.type = 3
            //a.status=>0 表示待审核的新建试卷
            $list3 = M('examination')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition3)//test_heir
                ->where($where3)
                ->order('a.id desc')
                ->field('a.*,a.test_upload_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();



            //新建问卷  b.type = 4
            //a.status=>0 表示待审核的新建问卷
            $list4 = M('survey')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition4)//survey_heir
                ->where($where4)
                ->order('a.id desc')
                ->field('a.*,a.survey_upload_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();




            //发布工作圈  b.type = 5
            //a.status=>0 表示待审核的工作圈
            $list5 = M('friends_circle')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition1)//uid
                ->where($where5)
                ->order('a.id desc')
                ->field('a.*,a.publish_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();




            //发起调研  b.type = 6
            //a.audit_state=>0=>1 表示待审核的调研
            $list6 = M('research')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition5)//create_user
                ->where($where6)
                ->order('a.id desc')
                ->field('a.*,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();



            //发起考试  b.type = 7
            //a.audit_status'=>1 表示待审核的考试
            $list7 = M('test')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition5)//create_user
                ->where($where7)
                ->order('a.id desc')
                ->field('a.audit_status as status,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();




            //加分申请  b.type = 8
            //a.status=>0 表示待审核的加分申请
            $list8 = M('integration_apply')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition1)
                ->where($where8)
                ->order('a.id desc')
                ->field("a.*,FROM_UNIXTIME(a.add_time,'%Y-%m-%d %H:%i:%s') as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num")
                ->select();




            //用户注册  b.type = 9
            //a.status=>2 表示待审核的注册用户
            $list9 = M('users')->alias('a')
                ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                ->where($condition6)
                ->where($where9)
                ->order('a.id desc')
                ->field('a.*,a.register_time as create_time,b.id as audit_id,b.audit_status,b.type,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();

            $data = array_merge_recursive($list1,$list2,$list3,$list4,$list5,$list6,$list7,$list8,$list9);

            $array_data = array();
            foreach($data as $k => $v){
                $array_data[$k]['id'] = $v['id'];
                $array_data[$k]['audit_id'] = $v['audit_id'];
                $array_data[$k]['audit_status'] = $v['audit_status'];
                $array_data[$k]['create_time'] = $v['create_time'];
                switch($v['type']){
                    //type=1 新建项目
                    case 1;
                        $array_data[$k]['common_name'] = '新建项目';
                        $array_data[$k]['type'] = 1;
                        break;

                    //type=2 新建课程
                    case 2;
                        $array_data[$k]['common_name'] = '新建课程';
                        $array_data[$k]['type'] = 2;
                        break;

                    //type=3 新建试卷
                    case 3;
                        $array_data[$k]['common_name'] = '新建试卷';
                        $array_data[$k]['type'] = 3;
                        break;


                    //type=4 新建问卷
                    case 4;
                        $array_data[$k]['common_name'] = '新建问卷';
                        $array_data[$k]['type'] = 4;
                        break;


                    //type=5 新建互动
                    case 5;
                        $array_data[$k]['common_name'] = '新建互动';
                        $array_data[$k]['type'] = 5;
                        break;


                    //type=6 发起调研
                    case 6;
                        $array_data[$k]['common_name'] = '发起调研';
                        $array_data[$k]['type'] = 6;
                        break;


                    //type=7 发起考试
                    case 7;
                        $array_data[$k]['common_name'] = '发起考试';
                        $array_data[$k]['type'] = 7;
                        break;


                    //type=8 发起加分
                    case 8;
                        $array_data[$k]['common_name'] = '发起加分';
                        $array_data[$k]['type'] = 8;
                        break;

                    //type=9 用户注册
                    case 9;
                        $array_data[$k]['common_name'] = '用户注册';
                        $array_data[$k]['type'] = 9;
                        break;
                }
            }
        }
        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'create_time',       //排序字段
        );
        $arrSort = array();
        foreach($array_data AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $array_data);
        }
        global $countpage; //定全局变量
        //$page=(empty($page))?'1':$page; //判断当前页面是否为空 如果为空就表示为第一页面
        $start=($page-1)*$pageLen; //计算每次分页的开始位置
        /*if($order==1){
            $array=array_reverse($array);
        }*/
        $totals=count($array_data);
        $countpage=ceil($totals/$pageLen); //计算总页面数
        $arrayData=array_slice($array_data,$start,$pageLen);
        return $arrayData;
    }



    /**
     * 审核通过后，申请加分记录插入积分记录表
     */
    public function applyIntegration($id){
        
        $dataone = M('integration_apply')->where(array('id'=>$id))->find();
        $arr = array(
            'time'=>time(),
            'uid'=>$dataone['uid'],
            'score'=>$dataone['add_score'],
            'type'=>'申请加分',
            'describe'=>'申请加分-'.$dataone['apply_title'],
            'apply_id'=>$dataone['id'],
        );
        $ret1 = M('integration_record')->add($arr);
        if ($ret1 === false) {
            $this->rollback();
            return false;
        }else{
            return ture;
        }
    }
    
    
    
    /*
     *判断是否已经审核过，若审核过则提示“该条数据已审核，请勿重复审核”
     *@param array $ids
     *@param array $audit_status
     *@return bool
     */
    public function repetitiveAudit($ids,$audit_statuses)
    {
        if(count($ids) == 1){
            $exist  = M('audit')->where(array('id'=>$ids[0],'audit_status'=>$audit_statuses[0]))->find();
        }else{
            foreach($ids as $k=>$v){
                $exist  = M('audit')->where(array('id'=>$v,'audit_status'=>$audit_statuses[$k]))->find();
            }
        }
        if(!$exist){
            return 0;
        }else{
            return 1;
        }

    }


    /**
     *三期批量审核
     * audit_status  审核级别状态 0:待审核 1:一审通过 2:二审通过 3:三审通过 4:一审拒绝 5:二审拒绝 6:三审拒绝',
     * audit_id 任务 id
     * taskType 任务类型 1 培训项目、 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 申请积分加分、 9 用户注册
     */

    public function operateAuditTask($id,$type,$ids,$audit_status,$audit_state,$userId){

        //判断是否存在重复审核
        $res = $this->repetitiveAudit($ids,$audit_status);
        if($res == 1){
            return 1;
        }
       
        if($audit_state == 2){ //批量拒绝部分
            if($type == 1){ //项目审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>3));
                    //$res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }
            }else if($type == 2){ //课程审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2));
                    //$res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }


            }else if($type == 3){ //新建试卷审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>3));
                   // $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }


            }else if($type == 4){ //新建问卷审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>3));
                    //$res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }


            }else if($type == 5){ //新建互动审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2));
                   // $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }


            }else if($type == 6){ //发起调研审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>2));
                   // $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }


            }else if($type == 7){ //发起考试审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>2));
                    //$res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }


            }else if($type == 8){ //加分申请审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2));
                    //$res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }

            }else if($type == 9){ //用户注册审核-拒绝
                foreach($ids as $k=>$v){
                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['audit_status'] == 0){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));

                    }else if($dataone['audit_status'] == 1){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5));
                    }else if($dataone['audit_status'] == 2){
                        $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6));
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                    $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>0));
                    //$res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
                }
            }

        }else if($audit_state == 1){ //批量通过部分
            if($type == 1){ //项目审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                        }

                    }else{ //有条件

                        $tianjian = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->find();
                        $project_time = $this->diffBetweenTwoDays($tianjian['start_time'],$tianjian['end_time']);
                        //项目指定人员（人数）
                        $designeeNum = M('admin_project')->alias('a')
                            ->join('left join __DESIGNATED_PERSONNEL__ as b on b.project_id=a.id')
                            ->where(array('a.id'=>$dataone['correlate_id']))
                            ->count();

                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                            }else{
                                if($dataone['condition_id'] == 1){
                                    //预留项目时长
                                    if($project_time < $dataone['conditiona']){
                                        $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                                    }
                                }else if($dataone['condition_id'] == 2){
                                    if($tianjian['project_budget'] < $dataone['conditiona']){
                                        $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                                    }
                                }else if($dataone['condition_id'] == 3){
                                    if($designeeNum < $dataone['conditiona']){
                                        $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                                    }
                                }
                            }


                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                            }else{
                                if($dataone['condition_id'] == 1){
                                    //预留项目时长
                                    if($project_time < $dataone['conditiona']){
                                        $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                                    }
                                }else if($dataone['condition_id'] == 2){
                                    if($tianjian['project_budget'] < $dataone['conditionb']){
                                        $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                                    }
                                }else if($dataone['condition_id'] == 3){
                                    if($designeeNum < $dataone['conditionb']){
                                        $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                                    }
                                }
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                        }
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }

            }else if($type == 2){ //新建课程审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                        }

                    }else{ //有条件

                        $tianjian = M('course')->where(array('id'=>$dataone['correlate_id']))->find();

                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }else{
                                if($dataone['condition_id'] == 4){
                                    //授课时长
                                    if($tianjian['course_time'] < $dataone['conditiona']){
                                        $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                    }
                                }else if($dataone['condition_id'] == 5){
                                    if($tianjian['credit'] < $dataone['conditiona']){
                                        $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                    }
                                }
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }else{
                                if($dataone['condition_id'] == 4){
                                    if($tianjian['course_time'] < $dataone['conditionb']){
                                        $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                    }
                                }else if($dataone['condition_id'] == 5){
                                    if($tianjian['credit'] < $dataone['conditionb']){
                                        $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                    }
                                }
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                        }
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }

            }else if($type == 3){ //新建试卷审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                        }

                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }

            }else if($type == 4){ //新建问卷审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                        }

                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }

            }else if($type == 5){ //新建互动审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                        }

                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }

            }else if($type == 6){ //发起调研审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                        }

                    }else{ //有条件

                        $tianjian = M('research')->where(array('id'=>$dataone['correlate_id']))->find();

                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                            }else{
                                if($dataone['condition_id'] == 6){
                                    //调研时长
                                    $survey_time = $this->diffBetweenTwoDays($tianjian['start_time'], $tianjian['end_time']);
                                    if($survey_time < $dataone['conditiona']){
                                        $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                                    }
                                }else if($dataone['condition_id'] == 7){
                                    if($tianjian['credits'] < $dataone['conditiona']){
                                        $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                                    }

                                }

                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                            }else{
                                if($dataone['condition_id'] == 6){
                                    //调研时长
                                    $survey_time = $this->diffBetweenTwoDays($tianjian['start_time'], $tianjian['end_time']);
                                    if($survey_time < $dataone['conditionb']){
                                        $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                                    }
                                }else if($dataone['condition_id'] == 7){
                                    if($tianjian['credits'] < $dataone['conditionb']){
                                        $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                                    }
                                }
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                        }
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }
            }else if($type == 7){ //发起考试审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                        }

                    }else{ //有条件

                        $tianjian = M('test')->where(array('id'=>$dataone['correlate_id']))->find();
                        $test_time = $this->diffBetweenTwoMinutes($tianjian['start_time'], $tianjian['end_time']);
                        $testerNum = M('test')->alias('a')
                            ->join('left join __TEST_USER_REL__ as b on b.test_id=a.id')
                            ->where(array('a.id'=>$dataone['correlate_id']))
                            ->count();

                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                            }else{
                                if($dataone['condition_id'] == 8){
                                    //考试时长（分钟）

                                    if($test_time < $dataone['conditiona']){
                                        $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                                    }
                                }else if($dataone['condition_id'] == 9){ //学分（分）
                                    if($tianjian['score'] < $dataone['conditiona']){
                                        $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                                    }

                                }else if($dataone['condition_id'] == 10){ //指定人员（人数）
                                    if($testerNum < $dataone['conditiona']){
                                        $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                                    }

                                }

                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                            }else{
                                if($dataone['condition_id'] == 8){
                                    //调研时长
                                    if($test_time < $dataone['conditionb']){
                                        $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                                    }
                                }else if($dataone['condition_id'] == 9){
                                    if($tianjian['score'] < $dataone['conditionb']){
                                        $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                                    }
                                }else if($dataone['condition_id'] == 10){
                                    if($testerNum < $dataone['conditionb']){
                                        $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                                    }
                                }
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                        }
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }
            }else if($type == 8){ //加分申请审核-通过
                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();
                    if($dataone['is_condition'] == 0){ //无条件
                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));

                            if($dataone['num'] == 1){
                                $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                $rest = $this->applyIntegration($dataone['correlate_id']);
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                $rest = $this->applyIntegration($dataone['correlate_id']);
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            $rest = $this->applyIntegration($dataone['correlate_id']);
                        }

                    }else{ //有条件

                        $tianjian = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->find();

                        if($dataone['audit_status'] == 0){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                $rest = $this->applyIntegration($dataone['correlate_id']);
                            }else{
                                if($dataone['condition_id'] == 11){
                                    //积分分值
                                    if($tianjian['add_score'] < $dataone['conditiona']){
                                        $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                        $rest = $this->applyIntegration($dataone['correlate_id']);
                                    }
                                }
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                $rest = $this->applyIntegration($dataone['correlate_id']);
                            }else{
                                if($dataone['condition_id'] == 11){
                                    //调研时长
                                    if($tianjian['add_score'] < $dataone['conditionb']){
                                        $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                                        $rest = $this->applyIntegration($dataone['correlate_id']);
                                    }
                                }
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            $rest = $this->applyIntegration($dataone['correlate_id']);
                        }
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }
            }else if($type == 9){ //用户注册审核-通过

                foreach($ids as $k=>$v){

                    $dataone = M('audit')->where(array('id'=>$v))->find();

                    if($dataone['is_condition'] == 0){ //无条件

                        if($dataone['audit_status'] == 0){

                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                            if($dataone['num'] == 1){
                                $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 1){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2));
                            if($dataone['num'] == 2){
                                $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                            }
                        }else if($dataone['audit_status'] == 2){
                            $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3));
                            $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                        }
                    }
                    $this->threeAuditMan($v,$dataone['audit_status'],$userId);
                }
            }
        }
        if($res){
            return 2;
        }else{
            return 3;
        }
    }



    /**
     *三期批量审核时写入审核人
     */
    public function threeAuditMan($id,$auditstatus,$userId){

        if($auditstatus == 0){
            M('audit')->where(array('id'=>$id))->save(array('levalone_man'=>$userId));
        }else if($auditstatus == 1){
            M('audit')->where(array('id'=>$id))->save(array('levaltwo_man'=>$userId));
        }else if($auditstatus == 2){
            M('audit')->where(array('id'=>$id))->save(array('levalthree_man'=>$userId));
        }
    }


    /**
     * 求两个日期之间相差的天数
     * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     * @param string $day1
     * @param string $day2
     * @return number
     */
    public function diffBetweenTwoDays ($day1, $day2)
    {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
    }

    /**
     * 求两个日期之间相差的分钟数
     * (针对1970年1月1日之后，求之前可以采用泰勒公式)
     * @param string $time1
     * @param string $time2
     * @return number
     */
    public function diffBetweenTwoMinutes ($time1, $time2)
    {
        $second1 = strtotime($time1);
        $second2 = strtotime($time2);

        if ($second1 < $second2) {
            $tmp = $second2;
            $second2 = $second1;
            $second1 = $tmp;
        }
        return ($second1 - $second2) / 60;
    }



    /**
     * 审核任务详情
     * $id 审核任务id
     * $audit_id 审核表id
     * $type 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 申请积分加分、 9 用户注册
     * $audit_status 0:待审核(一级审核) 1:一审通过（二级审核） 2:二审通过（三级审核） 3:三审通过 4:一审拒绝 5:二审拒绝 6:三审拒绝
     */
    public function auditTaskDetail($id,$type,$audit_id,$audit_status,$userId){

        //接收用户id,获取用户角色
        $userRole = $this->getUserRole($userId);

        //循环获取用户角色id放入新数组容器中
        $arr = array();
        foreach ($userRole as $v) {
            array_push($arr, $v['group_id']);//array_push往数组中插入一个或者多个元素
        }

        //把数组用英文逗号拼接成字符串
        $group_id = implode(',', $arr);

        //判断审核级别  $audit_status 0一级审核中  1二级审核中  2三级审核中
		//1新建项目 admin_project
        $where['id'] =  $id;
		if($type == 1){
			$detail = M("admin_project")->field("id,project_name,add_time as create_time")->where($where)->find();
            $detail['common_name'] = '新建项目';
		//2新建课程 course
		}elseif ($type == 2){
			$detail = M("course")->field("id,course_name,FROM_UNIXTIME(create_time,'%Y-%m-%d %H:%i:%s') as create_time")->where($where)->find();
            $detail['common_name'] = '新建课程';
		//3新建试卷 examination
		}elseif ($type == 3){
			$detail = M("examination")->field("*,test_upload_time as create_time")->where($where)->find();
            $detail['common_name'] = '新建试卷';
		//4新建问卷 survey
		}elseif ($type == 4){
			$detail = M("survey")->field("*,survey_upload_time as create_time")->where($where)->find();
            $detail['common_name'] = '新建问卷';

		//5 新建话题（工作圈内容发布） friends_circle
        }elseif ($type == 5){
			$detail = M("friends_circle")->field("id,uid,content,images,publish_time as create_time")->where($where)->find();
            $userName = M("users")->where(array('id' => $detail['uid']))->getField('username');
            $detail['username'] = $userName;
            $detail['common_name'] = '新建话题';
		//6发起调研 research
		}elseif ($type == 6){
			$detail = M("research")->field("*")->where($where)->find();
            $detail['common_name'] = '发起调研';
		//7发起考试 test
		}elseif ($type == 7){
			$detail = M("test")->field("*")->where($where)->find();
            $detail['common_name'] = '发起考试';
        //8申请加分
        }elseif ($type == 8){
            $detail = M("integration_apply")->field("id,add_score,apply_description,attachment,FROM_UNIXTIME(add_time,'%Y-%m-%d %H:%i:%s') as create_time")->where($where)->find();
            $detail['common_name'] = '申请加分';

            //9 用户注册 integration_apply
        }elseif ($type == 9){
            $detail = M("users")->field("id,username,avatar,phone,register_time as create_time")->where($where)->find();
            $detail['common_name'] = '用户注册';
            //status 审核状态:0-待审核,1-已通过,2-已拒绝
            //数据库 status` '用户状态 0：拒绝； 1：审核通过 ；2：待审核 ; 3 逻辑删除',  强制转化下
        }
        if(!empty($detail['id'])){
            $detail['audit_status'] = $audit_status;//根据此字段判断通过或拒绝 3为通过
            $detail['audit_id'] = $audit_id;
            $detail['type'] = $type;
            return $detail;
        }else{
            return false;
        }

    }
    


}