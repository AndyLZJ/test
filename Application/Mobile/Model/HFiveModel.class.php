<?php
namespace Mobile\Model;

use Think\Model;

/**
 * @author Dujunqiang 20170308
 * 我的学员--我的调研
 */

class HFiveModel extends CommonModel {
    protected $tablePrefix = 'think_';
    protected $tableName = 'admin_project';
    
    /**
     * 日程提醒
     */
    public function calendar($uid){
    	if(!$uid){
    		return false;
    	}
    	
    	//我的考试任务
    	$course = M("project_examination as a")->field("a.project_id,project_name,a.start_time,a.end_time")
    		->join("JOIN __ADMIN_PROJECT__ as b ON a.project_id=b.id")
    		->join("JOIN __DESIGNATED_PERSONNEL__ as c ON a.project_id=c.project_id")
    		->where("b.type=0 OR b.type=4 AND c.uid=".$uid)->order("a.start_time DESC")->limit(30)->select();
    	$data = array();
    	$key = 0;
    	foreach ($course as $value){
    		$data[$key]["type"] = 1;
    		$data[$key]["start_time"] = $value["start_time"];
    		$data[$key]["end_time"] = $value["end_time"];
    		$key ++;
    	}
    	
    	//我的调研任务
    	$survey = M("project_survey as a")->field("a.project_id,project_name,a.start_time,a.end_time")
    		->join("JOIN __ADMIN_PROJECT__ as b ON a.project_id=b.id")
    		->join("JOIN __DESIGNATED_PERSONNEL__ as c ON a.project_id=c.project_id")
    		->where("b.type=0 OR b.type=4 AND c.uid=".$uid)->order("a.start_time DESC")->limit(30)->select();
    	foreach ($survey as $value){
    		$data[$key]["type"] = 2;
    		$data[$key]["start_time"] = $value["start_time"];
    		$data[$key]["end_time"] = $value["end_time"];
    		$key ++;
    	}
    	
    	//系统调研任务
    	$research = M("research")->field("id,research_name,start_time,end_time")->where("audit_state=1")->order("start_time DESC")->limit(30)->select();
    	foreach ($research as $value){
    		$data[$key]["type"] = 2;
    		$data[$key]["start_time"] = $value["start_time"];
    		$data[$key]["end_time"] = $value["end_time"];
    		$key ++;
    	}
    	
    	return $data;
    }
}
