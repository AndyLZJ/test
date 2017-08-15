<?php 

namespace Admin\Model;

use Think\Model;

class CourseCommentModel extends Model{

	//获取相应学员对应课程的评价
	public function comment($user_id,$course_id){

		$map["user_id"]=$user_id;

		$map['course_id']=$course_id;

		$comment=$this->where($map)->find(); //关联表得到学员对课程评价的星级

		return $comment["student_evaluation"];   

	}

}