<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 评论管理
 * @author Dujuqiang 20170327
 */
class CommentManageController extends AdminBaseController{
	/*
	 * 课程评论首页
	 * p 页码
	 * cid 课程分类， 默认为空
	 * keyword 搜索关键字， 默认为空
	 */
	public function index(){
		$user_id = $_SESSION["user"]["id"];
		if(!$user_id){
			echo "未获取到用户，请登录";
			exit;
		}
		
		$get = I("get.");
		$get["page"] = $get["p"] + 0;
		if(!is_int($get["page"]) || $get["page"] < 1){
			$get["page"] = 1;
		}
		
		$get["cid"] += 0;
		if(!is_int($get["cid"]) || $get["cid"] < 0){
			$get["cid"] = "";
		}
		
		$pageLen = 15;
		$get["pageLen"] = $pageLen;
		$data = D("CommentManage")->index($get);
		$data["cid"] = $get["cid"];
		$data["keyword"] = $get["keyword"];
		//print_r($data["list"]);
		$this->assign($data);
    	$this->display();
    }
}