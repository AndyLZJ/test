<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 考试考勤model
 */
class ExaminationAttendanceModel extends BaseModel{

	/**获取各种状态的考勤信息
	*/
	public function  salary($data){
		$map["project_id"]=$data["project_id"];
		$map["test_id"]=$data["exam_id"];
		$map["status"]=$data["status"];
		$data=array();
		//统计该状态的人数
		$data=$this->where($map)->count();
		return $data;
	}
	
	/***
	导入考勤数据
	*/
	public function importAttendance($file){
		$list = import_excel($file);
		if($list[1][0] !='姓名' || $list[1][1] != '手机号码' || $list[1][2] != '得分'){
			return false;
		}
		$lists=array();
		for($a=0;$a<count($list);$a++){
			if(!empty($list[$a][1])){
				$lists[]=$list[$a];
			}
		}
		$data=array();
		$user=D("Users");
		$tissueGroupAccess=D("TissueGroupAccess");
		for($i=1;$i<count($lists);$i++){
			$data[$i]["username"]=$lists[$i][0];
			$data[$i]["mobile"]=$lists[$i][1];
			$data[$i]["total_score"]=$lists[$i][2];
			if(empty($data[$i]["username"])||empty($data[$i]["mobile"])||empty($data[$i]["total_score"])){
				$data[$i]["status"]=false;
				$data[$i]["message"]="请完善第".$i."行信息";
			}else{
				$map["phone"]=$data[$i]["mobile"];
				$userInfo=$user->where($map)->find();
				$data[$i]["user_id"]= $userInfo["id"];
				$maps["user_id"]=$data[$i]["user_id"];
				$data[$i]['department_id']=$tissueGroupAccess->field("tissue_id")->where($maps)->find();
				$data[$i]["position_id"]=$tissueGroupAccess->field("job_id")->where($maps)->find();
			    if($userInfo==null){
					$data[$i]["status"]=false;
					$data[$i]["message"]="第".$i."行用户不存在";
				}
			}
		}

		return $data;
	}





}
