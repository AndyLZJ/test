<?php 
namespace Admin\Model;

use Think\Model;

class ProjectSummaryModel extends Model{

	/**
	添加培训项目总结
	**/
	public function addSummary($data){

		$map["project_id"]=$data["project_id"];

		$summary=$this->where($map)->find();

		if($summary==null){

			$res=$this->add($data);

			
		
		}else{

			$res=$this->where($map)->save($data);

			
		}

		return $res;

		
	}


	/**
	更新培训项目总结
	**/
	public function updateSummary($data){

		$map["project_id"]=$data["project_id"];

		$res=$this->where($map)->save($data);

		return $res;
	}

	/**
	获取项目总结信息
	**/
	public function getOne($id){

		$map["project_id"]=$id;

		$summary=$this->where($map)->find();

		return $summary;
	}




}