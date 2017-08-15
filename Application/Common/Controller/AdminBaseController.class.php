<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * admin 基类控制器
 */
class AdminBaseController extends BaseController{

	//分页数
	public $total_page = 10;
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		parent::_initialize();
		$auth=new \Think\Auth();
		$rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
		$result=$auth->check($rule_name,$_SESSION['user']['id']); 
		
		if(!$_SESSION['user']['id']){

			$url = U('home/index/index');

			echo '<script>top.location.href="'.$url.'"</script>';
			exit;

			//$this->error("未登录，跳转登录页", "javascript:parent.location.reload()");
		}
		
		if(!$result){
			$this->error('您没有权限访问');
		}
	}
}

