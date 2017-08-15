<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class DesignatedPersonnelModel extends BaseModel{

	/***
	获取某项课程的指定参与人员
	*/
	public function getOne($id){

		 $map["a.project_id"]=$id;
		 $dpersonnels=$this->alias('a')
              ->field('a.id,a.project_id,a.user_id,b.username')
              ->join('left join __USERS__ b on a.user_id=b.id')
              ->where($map)
              ->select();

        return  $dpersonnels;
	}

	/***
	获取所有可能的参与人员
	*/

	public function getDpersonnels($user_id){

		$map["user_id"]=$user_id;

		$res =D("TissueGroupAccess a")->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")->field("tissue_id,rules")->where($map)->find();

		if(empty($res['rules'])){
			$rulesid = $res['tissue_id'];
		}else{

			$rules_arr = explode(",",$res['rules']);

			$rulesid = implode(",",array_merge($rules_arr,array($res['tissue_id'])));
			
		}

		$arr["a.tissue_id"]= array("in",$rulesid);

		$participates=D("TissueGroupAccess")->alias('a')
				->field('a.user_id,b.username')
				->join('left join __USERS__ b on a.user_id=b.id')
				->where($arr)->select();

		return $participates;
	}

	/*
	指定参与人员更新
	*/
	public function updateDpersonnels($project_id,$user_id){

		$map["project_id"]=$project_id;

		$map["user_id"]=$user_id;
		
		$dpersonnels=$this->where($map)->find();

		if($dpersonnels==null){

			$res=$this->add($map);

		}

		return $res;
	}

	/**
	计算应考人数
	**/
	public function counts($id){

		$map["project_id"]=$id;

		$count=$this->where($map)->count();

		return $count;
	}

	

	/***
	获取某项课程的指定参与人员
	*/
	public function getlist($id){

		 $map["a.project_id"]=$id;
		 $list=$this->alias('a')
              ->field('a.id,a.project_id,a.user_id,b.username,c.tissue_id,c.job_id,d.status,d.mobile_scanning')
              ->join('left join __USERS__ b on a.user_id=b.id')
              ->join('left join __TISSUE_GROUP_ACCESS__ c on a.user_id=c.user_id')
              ->join('left join __ATTENDANCE__ d on a.user_id=d.user_id')
		      ->where($map)
			  ->select();

		$tissue=D("TissueRule");

		$jobsManage=D("JobsManage");

		for($i=0;$i<count($list);$i++){

			$maps["id"]=$list[$i]["tissue_id"];

			$arr["id"]=$list[$i]["job_id"];

			$list[$i]['tissue']=$tissue->field("name")->where($maps)->find();

			$list[$i]["job"]=$jobsManage->field("name")->where($arr)->find();
		}
		
        return  $list;
	}






}
