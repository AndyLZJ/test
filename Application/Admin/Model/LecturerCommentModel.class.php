<?php 

namespace Admin\Model;

use Think\Model;
/**
 * 讲师评价管理模型
 */
class LecturerCommentModel extends Model{

	//获取对应学员对讲师的评价
	public function comment($user_id,$lecturer_id){

		$map["user_id"]=$user_id;

		$map["lecturer_id"]=$lecturer_id;

		$lecturerComment=$this->where($map)->find();

		return $lecturerComment['lecturer_evaluation'];	

	}

}