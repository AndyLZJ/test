<?php
namespace App\Model;

use Think\Model;
/**
 * @author Dujunqiang 20170311
 * 我的任务--审核加分
 */
class TaskModel extends CommonModel {
    protected $tablePrefix = 'think_';
    protected $tableName = 'designated_personnel';

    //自动验证
    protected $_validate = array(
        array('username', 'empty', '用户名不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('username', '5,100', '用户名长度超出限制', Model::EXISTS_VALIDATE, 'length'),
        array('phone', '/1[34578]{1}\d{9}$/', '请输入正确的手机号格式', Model::EXISTS_VALIDATE, 'regex'),
        array('password', 'empty', '密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('confirm', 'empty', '确认密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('password', 'checkValid', '密码不能有中文', Model::EXISTS_VALIDATE, 'callback'),
        array('oldPassword', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'isPassword', '只允许输入6-20位由 数字、字母、下划线组成的密码', Model::EXISTS_VALIDATE, 'callback'),
        array('phone', 'empty', '手机号码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('phone', 'is_numeric', '手机号码必须为数字', Model::EXISTS_VALIDATE, 'function')
    );

    //验证是否有权限审核
    //think_auth_group用户组表
    //think_auth_group_access 用户组明细表 这两个表也可以判断权限 ???
    public function judgeUser($uid){
    	$role = M("tissue_group_access")->field("manage_id")->where("uid=".$uid)->limit(1)->select();
    	if(!$role){
    		return array("code"=>1022, "message"=>'您还没有加入组织');
    	}
    	/*if($role[0]["manage_id"] == 0){
    		return array("code"=>1023, "message"=>'您的身份为普通学员，没有权限审核');
    	}*/
    	return array("code"=>1000, "message"=>'身份符合');
    }
    
    /**
     * 任务列表
     * taskStatus 任务状态 1待办任务 2已完成,不传默认1
     * page 页码，不传默认第一页
     */
    public function getList($param,$uid){
   		if(!$param || !$uid){
			return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
		}
    	
		$judgeUser = self::judgeUser($uid);
		if($judgeUser["code"] != 1000){
			return array("code"=>$judgeUser["code"], "message"=>$judgeUser["message"]);
		}
		
     	//taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
		if($param["taskStatus"] == 1){
			$typeCondition1 = " type='2'";
			$typeCondition2 = " status='0'";
			$typeCondition3 = " status='0'";
			$typeCondition4 = " status='0'";
			$typeCondition5 = " status='0' and pid = 0";
			$typeCondition6 = " audit_state='0'";
			$typeCondition7 = " audit_status='1'";
			$typeCondition8 = " status='2'";
			$typeCondition9 = " status='0'";
		}else{
			$typeCondition1 = " type='3' OR type='0'";
			$typeCondition2 = " status='1' OR status='2'";
			$typeCondition3 = " status='1' OR status='3'";
			$typeCondition4 = " status='1' OR status='3'";
			$typeCondition5 = " (status='1' OR status='2') and pid = 0";
			$typeCondition6 = " audit_state='1' OR audit_state='2'";
			$typeCondition7 = " audit_status='0' OR audit_status='2'";
			$typeCondition8 = " status='0' OR status='1'";
			$typeCondition9 = " status='1' OR status='2'";
		}
		
		$condition = "";
		if($param["keyword"] != ""){
			$condition = array('like',"%".$param["keyword"]."%");
		}
		
     	//taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
		//1培训项目
		$table1 = "SELECT 1 as task_type,'培训项目审核' as task_desc,id as task_id,project_name as task_name,start_time,end_time,add_time as create_time
			FROM __ADMIN_PROJECT__ WHERE $typeCondition1";
		
		//2新建课程
		$table2 = "SELECT 2 as task_type,'新建课程审核' as task_desc,id as task_id,course_name as task_name,start_time,end_time,FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%i:%s') 
			FROM __COURSE__ WHERE $typeCondition2";
		
		//3新建试卷
		$table3 = "SELECT 3 as task_type,'新建试卷审核' as task_desc,id as task_id,test_name as task_name,start_time,end_time,test_upload_time as create_time
			FROM __EXAMINATION__ WHERE $typeCondition3";
		
		//4新建问卷
		$table4 = "SELECT 4 as task_type,'新建问卷审核' as task_desc,id as task_id,survey_name as task_name,start_time,end_time,survey_upload_time as create_time
			FROM __SURVEY__ WHERE $typeCondition4";
		
		//5 新建话题（工作圈内容发布）
		$table5 = "SELECT 5 as task_type,'工作圈内容发布审核' as task_desc,id as task_id,content as task_name,'' as start_time,'' as end_time,publish_time as create_time
			FROM __FRIENDS_CIRCLE__ WHERE $typeCondition5";
		
		//6发起调研
		$table6 = "SELECT 6 as task_type,'发起调研审核' as task_desc,id as task_id,research_name as task_name,start_time,end_time,create_time
			FROM __RESEARCH__ WHERE $typeCondition6";
		
		//7发起考试
		$table7 = "SELECT 7 as task_type,'发起考试审核' as task_desc,id as task_id,name as task_name,start_time,end_time,create_time
			FROM __TEST__ WHERE $typeCondition7";
		
		//8用户注册
		$table8 = "SELECT 8 as task_type,'用户注册审核' as task_desc,id as task_id,username as task_name,'' as start_time,'' as end_time,register_time as create_time
			FROM __USERS__ WHERE $typeCondition8";
		
		//9申请积分加分
		$table9 = "SELECT 9 as task_type,'申请积分加分审核' as task_desc,id as task_id,apply_description as task_name,'' as start_time,'' as end_time,FROM_UNIXTIME(add_time, '%Y-%m-%d %H:%i:%s' ) as create_time
			FROM __INTEGRATION_APPLY__ WHERE $typeCondition9";
		
		$pageLen = $param["pageLen"] + 0;//默认每页显示30
		if(!is_int($pageLen) || $pageLen < 1){
			$pageLen = 30;
		}
		
		$page = $param["page"] + 0;
		if(!$page || !is_int($page) || $page < 0){
			$page = 1;
		}
		$start = $pageLen * ($page - 1);
		$sql = $table1." UNION ".$table2." UNION ".$table3." UNION ".$table4." UNION ".$table5." UNION ".$table6." UNION ".$table7." UNION ".$table8." UNION ".$table9."order by create_time desc LIMIT $start,$pageLen";
		$list = M()->query($sql);
		$totalNum = 0;
		if(count($list) <= $pageLen){
			//减少数据操作消耗
			//1培训项目
			$table1 = "SELECT id FROM __ADMIN_PROJECT__ WHERE $typeCondition1";
			//2新建课程
			$table2 = "SELECT id FROM __COURSE__ WHERE $typeCondition2";
			//3新建试卷
			$table3 = "SELECT id FROM __EXAMINATION__ WHERE $typeCondition3";
			//4新建问卷
			$table4 = "SELECT id FROM __SURVEY__ WHERE $typeCondition4";
			//5 新建话题（工作圈内容发布）
			$table5 = "SELECT id FROM __FRIENDS_CIRCLE__ WHERE $typeCondition5";
			//6发起调研
			$table6 = "SELECT id FROM __RESEARCH__ WHERE $typeCondition6";
			//7发起考试
			$table7 = "SELECT id FROM __TEST__ WHERE $typeCondition7";
			//8用户注册
			$table8 = "SELECT id FROM __USERS__ WHERE $typeCondition8";
			//9申请积分加分
			$table9 = "SELECT id FROM __INTEGRATION_APPLY__ WHERE $typeCondition9";
			$sqlCount = $table1." UNION ".$table2." UNION ".$table3." UNION ".$table4." UNION ".$table5." UNION ".$table6." UNION ".$table7." UNION ".$table8." UNION ".$table9;
			$total = M()->query($sqlCount);
			$totalNum = count($total);
		}else{
			$totalNum = count($list);
		}
		
		foreach ($list as $key => $val){
			if(is_int($val["create_time"]) && $val["create_time"] > 0){
				$list[$key]["create_time"] = date("Y-m-d H:i:s", $val["create_time"]);
            }
            $list[$key]["task_name"] = str_replace('&nbsp;','',strip_tags($val['task_name']));
		}
		$data["total_num"] = $totalNum;
		$data["list"] = $list;
		return array("code"=>1000, "message"=>'操作成功', "data"=>$data);
    }
    
    /**
     * 查看任务（待办任务  已完成 同一个接口）
     * taskId 任务id
     * taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
     */
    public function detail($param,$uid){
    	if(!$param || !$uid){
			return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
		}
    	
		$judgeUser = self::judgeUser($uid);
		if($judgeUser["code"] != 1000){
			return array("code"=>$judgeUser["code"], "message"=>$judgeUser["message"]);
		}
		//1新建项目 admin_project
		if($param["taskType"] == 1){

			$detail = M("admin_project")->field("*,add_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
		    /*foreach($detail as $key => $val){
                if($val['type'] == 1 || $val['type'] == 2){
                    unset($detail[$key]);
                }
            }*/
		//2新建课程 course
		}elseif ($param["taskType"] == 2){
			$detail = M("course")->field("*")->where("id=".$param["taskId"])->limit(1)->select();
		//3新建试卷 examination
		}elseif ($param["taskType"] == 3){
			$detail = M("examination")->field("*,test_upload_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
		//4新建问卷 survey
		}elseif ($param["taskType"] == 4){
			$detail = M("survey")->field("*,survey_upload_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
		//5 新建话题（工作圈内容发布） friends_circle
		}elseif ($param["taskType"] == 5){
			$detail = M("friends_circle")->field("*,publish_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
		//6发起调研 research
		}elseif ($param["taskType"] == 6){
			$detail = M("research")->field("*")->where("id=".$param["taskId"])->limit(1)->select();
		//7发起考试 test
		}elseif ($param["taskType"] == 7){
			$detail = M("test")->field("*")->where("id=".$param["taskId"])->limit(1)->select();
		//8用户注册 users
		}elseif ($param["taskType"] == 8){
			$detail = M("users")->field("id,username,avatar,phone,register_time,province,city,register_time as create_time,status")->where("id=".$param["taskId"])->limit(1)->select();
			//status 审核状态:0-待审核,1-已通过,2-已拒绝
			//数据库 status` '用户状态 0：拒绝； 1：审核通过 ；2：待审核 ; 3 逻辑删除',  强制转化下
			if($detail[0]["status"] == 0){
				$detail[0]["status"] = 2;
			}elseif ($detail[0]["status"] == 1){
				$detail[0]["status"] = 1;
			}elseif ($detail[0]["status"] == 2){
				$detail[0]["status"] = 0;
			}
		//9申请积分加分 integration_apply
		}elseif ($param["taskType"] == 9){
			$detail = M("integration_apply")->field("*,add_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
		}
		if(!$detail){
			return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
		}else{
			$isFormat = $detail[0]["create_time"] + 0;
			if(preg_match("/^[0-9]{10,}$/", $isFormat)){
				$detail[0]["create_time"] = date("Y-m-d H:i:s", $detail[0]["create_time"]);
			}
		}
		return array("code"=>1000, "message"=>"操作成功", "data"=> $detail);
    }
    
    /**
     * 审核任务
     * taskId 任务id
     * taskStatus 审核状态 1审核通过 2审核拒绝
     * taskType 任务类型 1 培训项目 2 新建课程、3 新建试卷、4 新建问卷、5 新建话题（工作圈内容发布）、6 发起调研、7 发起考试、8 用户注册、 9 其他（申请积分加分）
     * 		$typeCondition1 = " type='3' OR type='4'";
			$typeCondition2 = " status='1' OR status='2'";
			$typeCondition3 = " status='1' OR status='3'";
			$typeCondition4 = " status='1' OR status='3'";
			$typeCondition5 = " status='1' OR status='2'";
			$typeCondition6 = " audit_state='1' OR audit_state='2'";
			$typeCondition7 = " audit_status='0' OR audit_status='2'";
			$typeCondition8 = " status='1' OR status='2'";
			$typeCondition9 = " status='0' OR status='1'";
     */
    public function operate($param,$uid){
    	if(!$param || !$uid){
			return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
		}
		
		$judgeUser = self::judgeUser($uid);
		if($judgeUser["code"] != 1000){
			return array("code"=>$judgeUser["code"], "message"=>$judgeUser["message"]);
		}
		
		//1新建项目 admin_project
		if($param["taskType"] == 1){
			$detail = M("admin_project")->field("*,add_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["type"] != 2){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["type"] = 0;//通过
			}else{
				$data["type"] = 3;//拒绝
			}
			$return = M("admin_project")->where("id=".$param["taskId"])->limit(1)->save($data);
		//2新建课程 course
		}elseif ($param["taskType"] == 2){
			$detail = M("course")->field("*")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["status"] != 0){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["status"] = 1;
			}else{
				$data["status"] = 2;
			}
			$return = M("course")->where("id=".$param["taskId"])->limit(1)->save($data);
		//3新建试卷 examination
		}elseif ($param["taskType"] == 3){
			$detail = M("examination")->field("*,test_upload_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["status"] != 0){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["status"] = 1;
			}else{
				$data["status"] = 3;
			}
			$return = M("examination")->where("id=".$param["taskId"])->limit(1)->save($data);
		//4新建问卷 survey
		}elseif ($param["taskType"] == 4){
			$detail = M("survey")->field("*,survey_upload_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["status"] != 0){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["status"] = 1;
			}else{
				$data["status"] = 3;
			}
			$return = M("survey")->where("id=".$param["taskId"])->limit(1)->save($data);
		//5 新建话题（工作圈内容发布） friends_circle
		}elseif ($param["taskType"] == 5){
			$detail = M("friends_circle")->field("*,publish_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["status"] != 0){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["status"] = 1;
			}else{
				$data["status"] = 2;
			}
			$return = M("friends_circle")->where("id=".$param["taskId"])->limit(1)->save($data);
		//6发起调研 research
		}elseif ($param["taskType"] == 6){
			$detail = M("research")->field("*,create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["audit_state"] != 0){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["audit_state"] = 1;
			}else{
				$data["audit_state"] = 2;
			}
			$return = M("research")->where("id=".$param["taskId"])->limit(1)->save($data);
		//7发起考试 test
		}elseif ($param["taskType"] == 7){
			$detail = M("test")->field("*,create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["audit_status"] != 1){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["audit_status"] = 0;
			}else{
				$data["audit_status"] = 2;
			}
			$return = M("test")->where("id=".$param["taskId"])->limit(1)->save($data);
		//8用户注册 users
		}elseif ($param["taskType"] == 8){
			$detail = M("users")->field("*,register_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["status"] != 2){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["status"] = 1;
			}else{
				$data["status"] = 0;
			}
			$return = M("users")->where("id=".$param["taskId"])->limit(1)->save($data);
		//9申请积分加分 integration_apply
		}elseif ($param["taskType"] == 9){
			$detail = M("integration_apply")->field("*,add_time as create_time")->where("id=".$param["taskId"])->limit(1)->select();
			if(!$detail){
				return array("code"=>1022, "message"=>"当前taskId没有获取到内容");
			}
			if($detail[0]["status"] != 0){
				return array("code"=>1023, "message"=>"您好，当前任务已审核过");
			}
			if($param["taskStatus"] == 1){
				$data["status"] = 1;
		    	//审核通过加计分 think_integration_record
				$inteData["time"] = time();
				$inteData["uid"] = $detail[0]["uid"];
				$inteData["department"] = "";//废弃
				$inteData["score"] = $detail[0]["add_score"];
				$inteData["type"] = "申请加分";
				$inteData["describe"] = "申请加分";
				$inteData["apply_id"] = $param["taskId"];
		    	M("integration_record")->add($inteData);
			}else{
				$data["status"] = 2;
			}
			$return = M("integration_apply")->where("id=".$param["taskId"])->limit(1)->save($data);
		}
		return array("code"=>1000, "message"=>"审核成功");
    }
}