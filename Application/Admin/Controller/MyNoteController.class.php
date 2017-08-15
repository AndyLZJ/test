<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 个人中心_我的笔记
 * @author Dujuqiang 20170317
 */
class MyNoteController extends AdminBaseController{
	/*
	 * 我的笔记首页
	 * 优化建议：增加课程名称搜索，时间段搜索
	 */
	public function notePage(){
		$user_id = $_SESSION["user"]["id"];
		if(!$user_id){
			echo "未获取到用户，请登录";
			exit;
		}
		
		$get = I("get.");
		$page = $get["p"] + 0;
		if(!is_int($page) || $page < 1){
			$page = 1;
		}
		$pageLen = 15;
		$param["page"] = $page;
		$param["pageLen"] = $pageLen;
		$data = D("MyNote")->getData($param);
		$this->assign($data);
    	$this->display();
    }
}