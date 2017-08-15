<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * @author Dujunqiang 20170327
 * 我的通讯录
 */
class ContactsController extends AdminBaseController{
	/**
	 * 通讯录首页
	 */
	public function index(){
		$user_id = $_SESSION["user"]["id"];
		if(!$user_id){
			echo "未获取到用户，请登录";
			exit;
		}
		
		$get = I("get.");
		$data = D("Contacts")->getData($get);
		
		$this->assign($data);
		$this->display();
	}
	
	/**
	 * 获取部门通讯录数据
	 */
	public function getPart(){
		$post = I("post.");
		$post["keyword"] = trim($post["keyword"]);
		$data = D("Contacts")->getPart($post);
		$return = array("code"=>1000, "message"=>"成功", "data"=>$data);
		echo json_encode($return);
		return;
	}
	
	/**
	 * 用户详情页
	 */
	public function details(){
		$data = D("Contacts")->details();
		$this->assign('data',$data);
		$this->display();
	}
}