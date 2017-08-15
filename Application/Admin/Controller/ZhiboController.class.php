<?php
namespace Admin\Controller;
use Html2Text\mb_strlen;

use Common\Controller\AdminBaseController;

/**
 * 直播演示
 * author DuJunqiang 20170629
 */
class ZhiboController extends AdminBaseController{
	//直播首页
	public function index(){
		$this->display();
	}
	
	//创建直播
	public function createPage(){
		$this->display("create_page");
	}
	
	//观看直播
	public function livePage(){
		if(I("get.status") == 'end'){
			$this->display("end_page");
		}else{
			$this->display("live_page");
		}
	}
}