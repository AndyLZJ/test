<?php
namespace Common\Controller;
use Think\Controller;
/**
 * Base基类控制器
 */
class BaseController extends Controller{
    /**
     * 初始化方法
     */
    public function _initialize(){
        
    }

    
protected function check()
{
	$auth=new \Think\Auth();
	$rule_name=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
	$result=$auth->check($rule_name,$_SESSION['user']['id']);
}

}
