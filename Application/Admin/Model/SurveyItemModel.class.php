<?php 

namespace Admin\Model;

use Think\Model;

class SurveyItemModel extends Model{

	//获取问卷下面试题
	public function getItems($survey_id){
		$map["survey_id"]=$survey_id;
		$count=$this->where($map)->count();
		$Page=new \Think\Page($count,10);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$show=$Page->show();
		$surveyItems = $this->where($map)->order("id asc")->limit($Page->firstRow.','.$Page->listRows)->select();
		return $assign=array(
						"surveyItems"=>$surveyItems,
						"show"=>$show
						);
	}


}