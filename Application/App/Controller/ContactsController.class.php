<?php
namespace App\Controller;
use Think\Controller;
   /**
    * @author Dujunqiang 20170310
    * 我的通讯录
    *
    */
class ContactsController extends CommonController {
	/**
	 * 初始化
	 */
	public function __construct(){
		parent::__construct();
	}
	
	//验证是否获取到用户，是否提交数据
	public function currVerifi(){
		$userId = $this->getUserId();
		if(empty($userId)){
			$this->error(1024,'用户不存在');
		}
	
		if(!IS_POST || !$_POST){
			$this->error(1025,'不合法数据请求');
		}
	
		return $userId;
	}
	
	/**
	 * 通讯录首页
	 */
	public function index(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
		$return = D("Contacts")->index($userId);
		if($return["code"] == 1000){
			$this->success(1000,'获取数据成功',$return["data"]);
		}else{
			$this->error($return["code"],$return["message"]);
		}
	}
	
	/**
	 * 分组通讯录列表
	 * 
	 */
	public function groupList(){
		$userId = $this->currVerifi();
		$_POST["id"] += 0;
		if(!is_int($_POST["id"]) || $_POST["id"] < 1){
			$this->error(1011, "缺少参数： id 部门id");
		}
		$return = D("Contacts")->groupList($_POST,$userId);
		if($return["code"] == 1000){
			$this->success(1000,'获取数据成功',$return["data"]);
		}else{
			$this->error($return["code"],$return["message"]);
		}
	}
	
	/**
	 * 联系人详细信息
	 * uid 联系人id
	 */
	public function detail(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
		$uid = I('post.uid','','int');
        if(!isset($uid)){
            $this->error(1011, "缺少参数：uid 联系人id ");
        }
		if($uid < 1){
			$this->error(1011, "用户id需为大于0的正整数");
		}
		$return = D("Contacts")->detail($uid);
		if($return["code"] == 1000){
			$this->success(1000,'获取数据成功',$return["data"]);
		}else{
			$this->error($return["code"],$return["message"]);
		}
	}
	
	/**
	 * 联系人查询
	 */
	public function search(){
		$userId = $this->currVerifi();
		$post = I("post.");
		if(!$post["keyword"] || $post["keyword"] == ""){
			$this->error(1011, "缺少参数：keyword 搜索关键字");
		}
		$return = D("Contacts")->search($post,$userId);
		if($return["code"] == 1000){
			$this->success(1000,'获取数据成功',$return["data"]);
		}else{
			$this->error($return["code"],$return["message"]);
		}
	}
}