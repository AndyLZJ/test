<?php 
namespace Admin\Model;

use Think\Model;

class ProjectExaminationModel extends Model{
	/***
	写入项目考试关联表
	*/
	public function adds($data){
		$map["project_id"]=$data["project_id"];
		$map["test_id"]=$data["test_id"];


		$res=$this->where($map)->find();
		
		if(!empty($res)){
			$result=$this->where($map)->save($data);
		}else{
			$result=$this->add($data);
		}
		return $result;
	}
	/***
	删除项目下面的试卷
	*/
	public function deleteExam($id){
		$map["project_id"]=$_SESSION["project"]["id"];
		$res=$this->field("test_id,specific_information")->where($map)->find();
		$data["test_id"]=implode(",",array_diff(explode(",",$res["test_id"]),$id));
		$data["specific_information"]=json_decode($res["specific_information"],true);
		unset($data['specific_information'][$id[0]]);
		$data["specific_information"]=json_encode($data['specific_information']);
		$result=$this->where($map)->save($data);
		return $result;
	}

	/***
	获取所有已经选定的考试
	*/

	public function getAll(){
		$projectExaminations=$this->select();
		return $projectExaminations;
	}


	/*
	获取新建项目考试信息
	***/

	public function getOne($id){
		$map["project_id"]=$id;
		$examination=$this->where($map)->find();
		return $examination;
	}


	/***
    删除培训项目
    */
    public function deleteData($id){
		$map["project_id"]=$id;
        $res=$this->where($map)->delete();
        return $res; 
    }

      /***
	编辑更新信息
    */
   public function saveData($data){
   		$map["project_id"]=$data["project_id"];
		$res=$this->where($map)->save($data);
		return $res;
   }

	/**
	 * 获取项目考试试题
	 */
	public function getExamination($project_id){

		$where['a.test_id'] = array("eq",$project_id);

		$list = M("project_examination a")->field("a.*,b.test_name,b.principal,b.test_mode")->join("LEFT JOIN __EXAMINATION__ b on a.test_id = b.id")->where($where)->select();


		return $list;

	}



}