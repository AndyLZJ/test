<?php
namespace Mobile\Controller;
use Think\Controller;
/**
 * @author Dujunqiang 20170329
 * APP h5页面相关
 */
class HFiveController extends CommonController {
	/**
	 * 初始化
	 */
	function __construct(){
		parent::__construct();
	}
	
	/**
	 * 调研结果
	 * method：post
	 * survey_id 调研id
	 */
	public function surveyResult(){
		$post = I("post.");
		$post["survey_id"] += 0;
		if(!is_int($post["survey_id"]) || $post["survey_id"] < 1){
			$data["code"] = 1011;
			$data["message"] = "未获取到问卷id";
		} 
		
		$this->display("HFive/survey");
	}
	
	/**
	 * 日程提醒
	 * method:get
	 */
	public function calendar(){
		$uid = $this->verifyUserDataGet();
		
		$resp = D("HFive")->calendar($uid);
		if($_GET["printr"] == 1) print_r($resp);
		$jsonStr = "";
		$date1 = array();//已有日期-考试
		$date2 = array();//已有日期-调研
		if(is_array($resp)){
			foreach ($resp as $key=>$value){
				//不要连线 不要多任务
				$thisDate = date("Y-m-d", strtotime($value["start_time"]));
				if($value["type"] == 1){
					$singleColor = "ff0000";
					if(in_array($thisDate, $date1)){
						continue;
					}
					$date1[] = $thisDate;
				}else{
					$singleColor = "fff100";
					if(in_array($thisDate, $date2)){
						continue;
					}
					$date2[] = $thisDate;
				}
				
				$value["start_time"] = date("d-m-Y 12:01", strtotime($value["start_time"]));
				$value["end_time"] = date("d-m-Y 13:01", strtotime($value["start_time"]));
				
				$jsonStr .= '{"start": "' .$value["start_time"]. '",
                  "end": "' .$value["end_time"]. '",
                  "singleColor": "'. $singleColor .'"},';
			}
			$jsonStr = substr($jsonStr, 0, -1);
		}
		$data["data"] = $jsonStr;
		$this->assign($data);
		$this->display("HFive/calendar");
	}
	
	/**
	 * 资讯详细
	 * method:get
	 * new_id 资讯id
	 */
	public function news(){
		$get = I("get.");
		$get["new_id"] += 0;
		if(!is_int($get["new_id"]) || $get["new_id"] < 1){
			$data["code"] = 1011;
			$data["message"] = "未获取到资讯id";
		}else{
			$resp = M("news")->where("id=".$get["new_id"])->limit(1)->select();
			$data["code"] = 1000;
			$data["message"] = "成功";
			$data["data"] = $resp[0];
		}
		if($_GET["printr"] == 1) print_r($data);
		$this->assign($data);
		$this->display("HFive/news");
	}
	
	/**
	 * 积分规则
	 */
	public function score(){
		$resp = M("integration_rule")->select();
		$data["list"] = $resp;
		if($_GET["printr"] == 1) print_r($data);
		$uid = $this->verifyUserDataGet();
		@D('Trigger')->intergrationTrigger($uid, 4);
		$this->assign($data);
		$this->display("HFive/score");
	}
}