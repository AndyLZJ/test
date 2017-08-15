<?php 

namespace Admin\Model;

use Think\Model;

class SurveyAnswerModel extends Model{

	/***
	统计各种答案的人数百分比
	*/
	public function statistics($project_id,$survey_id,$question_number){
		$map["project_id"]=$project_id;
		$map["survey_id"]=$survey_id;
		$map["question_number"]=$question_number;
		$result=$this->where($map)->select();
		return $result;
	}


}