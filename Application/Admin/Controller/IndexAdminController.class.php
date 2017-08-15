<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 后台首页控制器_管理员
 */
class IndexAdminController extends AdminBaseController{
	/**
	 * 首页
	 */
	public function index(){
		$undoParam["chooseDay"] = date("Y-m-d");
		$data["notice"] = D("IndexAdmin")->notice();//滚动通知
		$data["active"] = D("IndexAdmin")->active();//进行中项目
		$data["actived"] = D("IndexAdmin")->actived();//结束的项目
		$data["teach"] = D("IndexAdmin")->teach();//授课信息统计
		$data["news"] = D("IndexAdmin")->news();//新闻资讯
		$schedule = D("IndexAdmin")->schedule();//日程任务提醒（数据量太多，获取最近两个月的数据）
		$data["schedule"] = $schedule["schedule"];
		$data["undo"] = $schedule["undoTask"];//未审核任务数量
		
		$scheduleStr = "";
		if(is_array($data["schedule"])){
			foreach ($data["schedule"] as $key=>$value){
				$formatTime = date("d-m-Y", strtotime($value));
				$scheduleStr .= '{"start": "' .$formatTime. ' 00:00:00",
				"end": "' .$formatTime. ' 22:22:22",
				"singleColor": "ff0000"},';
			}
			$scheduleStr = substr($scheduleStr, 0, -1);
		}
		$data["scheduleStr"] = $scheduleStr;
		
		$this->assign($data);

		session('home','indexDirector');

	    $this->display("Index/indexadmin");
	}
	
	/**
	 * 获取当天日程数据
	 */
	public function dayTask(){
		$post = I("post.");
		if(!$post["chooseDay"]){
			$post["chooseDay"] = date("Y-m-d");
		}else{
			//chooseDay 前端传过来 cmvDay-5-2-2017  处理为 2017-02-05
			if(preg_match('/^cmvDay-[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/', $post["chooseDay"])){
				$chooseDay = explode("-", $post["chooseDay"]);
				$chooseDay[2] += 1;//国外月份从0开始算起   12月份为11
				$post["chooseDay"] = $chooseDay[3]."-".$chooseDay[2]."-".$chooseDay[1];
				$post["chooseDay"] = date("Y-m-d", strtotime($post["chooseDay"]));
			}else{
				$post["chooseDay"] = date("Y-m-d");
			}
		}
		
		$post["page"] += 0;
		if(!is_int($post["page"]) || $post["page"] < 1){
			$post["page"] = 1;
		}
		
		$resp = D("IndexAdmin")->dayTask($post);
		$return = array("code"=>1000, "message"=>"成功", "list"=>$resp["list"]);
		echo json_encode($return);
	}
	
	//id 存在查单条 不存在查全部
	public function notice(){
		$id = (int)I("get.id");
		if($id < 1){
			$id = null;
		}
		
		$resp = D("IndexAdmin")->notice($id);//滚动通知
		$return = array("code"=>1000, "message"=>"成功", "list"=>$resp);
		echo json_encode($return);
	}
	
	//标记为已读
	public function readNotice(){
		$user_id = $_SESSION["user"]["id"];
		$id = (int)I("get.id");
		if($id < 1){
			$id = null;
		}
		
		@D('Trigger')->intergrationTrigger($user_id, 2);
		$resp = D("IndexAdmin")->readNotice($id);//滚动通知
		$return = array("code"=>1000, "message"=>"成功");
		echo json_encode($return);
	}
}
