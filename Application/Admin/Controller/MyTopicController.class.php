<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 我的话题小组
 * author DuJunqiang 20170627
 */
class MyTopicController extends AdminBaseController{
	/**
	 * 我的小组首页
	 * 创建的小组 加入的小组不同表，暂不做分页
	 * title 搜索内容
	 */
	public function index(){
		$title = I("get.table_search");
		$title = addslashes(trim($title));
		$param["title"] = $title;
		$return = D("MyTopic")->index($param);
		
		// print_r($return);
		$this->assign('table_search',$title);
		$this->assign('join_list',$return['join_list']);
		$this->assign('create_list',$return['create_list']);
		$this->display();
	}
	
	/**
	 * 退出小组
	 */
	public function quit(){
		$group_id = I('get.id');
		$return = D("MyTopic")->quit($group_id);
		if($return !== false){
			$this->success("退出小组成功",U('Admin/MyTopic/index'));
		}else{
			$this->error("退出小组失败");
		}
	}
	
	/**
	 * 操作我的小组
	 */
	public function my(){
        $id = I('get.id');
	    $table_search = I('get.table_search');
		$res = D("MyTopic")->my($table_search,$id);
        // dump($res);
        $this->assign('id',$id); //小组id
		$this->assign('table_search',$table_search);
        $this->assign('data',$res['data']);
		$this->assign('count',$res['count']);
		$this->assign('alltopic',$res['alltopic']);
		$this->assign('topicname',$res['topicname']);
		$this->assign('page',$res['show']);
		$this->display();
	}
	
	/**
	 * 设置组长
	 * status 1设置为组长 2取消组长
	 * user_id 用户id
	 */
	public function setLeader(){
        $groupid = I('get.groupid') + 0; 
	    $id = I('get.id') + 0;
		$user_id = M('topic_personnel')->where(array('id'=>$id))->getField('user_id');
		$type = I('get.type') + 0;
        $return = D("MyTopic")->setLeader($id,$type);
        if($return !== false){
            if($type == 1){
			//触发小组系统消息
		    @$res = D('Trigger')->sendTopicMessage($_SESSION["user"]["id"],'',$groupid,0,0,3);
			$this->success("设置成功",U('Admin/MyTopic/my',array('id'=>$groupid)));
			}else{
			$this->success("取消成功",U('Admin/MyTopic/my',array('id'=>$groupid)));
			}
		}else{
			$this->error("系统错误 设置失败");
		}


	}
	
	/**
	 * 删除成员
	 * user_id 用户id
	 */
	public function del(){

		$groupid = I('get.groupid') + 0; 
	    $id = I('get.id') + 0; //话题小组邀请人员关联表主键id
		
        $return = D("MyTopic")->del($id);
        if($return !== false){
           
			$this->success("删除成功",U('Admin/MyTopic/my',array('id'=>$groupid)));
			
		}else{
			$this->error("系统错误");
		}
		
	}
	
	/**
	 * 解散小组
	 */
	public function disband(){
		$id = I('get.id') + 0; 
        $code = I('get.code');
		$res = $this->check_verify($code,'topic');
        if(!$res){
			$this->error("输入验证码错误");
		}


		$return = D('MyTopic')->disband($id);
		if($return !== false){
           
			$this->success("小组解散成功",U('Admin/MyTopic/index'));
			
		}else{
			$this->error("系统错误");
		}
	}
	
	/*
	 * 与我有关
	 * type 1回复我的 2跟帖发言 3系统消息
	 */
	public function aboutMe(){
		$type = I('get.type');
		if($type!=1 && $type!=2 && $type!=3){
		   $type = 1;	
		}
        

		$return = D("MyTopic")->aboutMe($type);
		
		$this->assign('type',$type);
		if($type == 1){
          $this->assign('data1',$return['data']);
		  $this->assign('page1',$return['page']);
		}else if($type == 2){
          $this->assign('data2',$return['data']);
		  $this->assign('page2',$return['page']);
		}else if($type == 3){
          $this->assign('data3',$return['data']);
		  $this->assign('page3',$return['page']);
		}
		
		$this->display("about_me");
	}


	/*
	 * 回复消息
	 */
	public function reply(){

		$this->display();

	}	


	/*
	 * 测试验证码验证
	 */
	public function testverfy(){
		// dump($_SESSION);
		$code = "kcjj";
		$id = "topic";
		$verify = new \Think\Verify();
        $res = $verify->check($code,$id);
		dump($res);
		if ($res) {
			echo '输入验证码正确';
			# code...
		}else{
			echo '输入验证码错误';
		}
		
	}
	
	//显示验证码
	public function verify(){
		$config =    array(
				'fontSize'    =>    30,    	// 验证码字体大小
				'length'      =>    4,     	// 验证码位数
				'useNoise'    =>    true,	// 关闭验证码杂点
				'useCurve'    =>    false, 	// 关闭验证码杂点
		);
		$Verify = new \Think\Verify($config);
		$Verify->entry('topic');
	}
	
	//验证验证码
	public function check_verify($code, $id){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}
}
