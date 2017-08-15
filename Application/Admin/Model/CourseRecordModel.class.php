<?php 

namespace Admin\Model;

use Think\Model;

class CourseRecordModel extends Model{

	/***
	加入我的课程
	*/
	public function accretion($data){

		$map["course_id"]= array("eq",$data["course_id"]);

		$map["user_id"]= array("eq",$data["user_id"]);

		$result=$this->where($map)->find();

		if(empty($result)){

			$res=$this->add($data);

			return $res;
		}

		return false;
		
		
	}

	/***
	判断课程记录表里面是否存在该记录！
	*/
	public function judge($course_id){

		$map["course_id"]=$course_id;

		$map["user_id"]=$_SESSION['user']['id'];

		$res=$this->where($map)->count();

		

		if($res>0){

			return true;
		}

		return false;

	}

	

}