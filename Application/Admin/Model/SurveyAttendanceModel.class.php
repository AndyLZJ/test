<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 调研考勤model
 */
class SurveyAttendanceModel extends BaseModel{

	/**获取各种状态的考勤信息
	*/
	public function  salary($survey_id){

		$map["survey_id"]=$survey_id;

		$map["status"]=0;

		$data=array();

		//统计缺勤人数

		$data['absenteeism']=$this->where($map)->count();


		return $data;
	}



}
