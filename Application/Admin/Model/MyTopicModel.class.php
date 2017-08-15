<?php
namespace Admin\Model;
use Common\Model\BaseModel;

/**
 * 我的话题小组
 * author DuJunqiang 20170627
 */
class MyTopicModel extends BaseModel{
	/*
	 * 初始化
	*/
	function __construct(){}
	
	/**
	 * 我的小组首页
	 */
	public function index($param){
		$user_id = $_SESSION["user"]["id"];
		
		if($param["title"]){
			$where["b.name"] = array("like", "%".$param["title"]."%");
			$where2["name"] = array("like", "%".$param["title"]."%");
		}
		
		//我加入的小组
		$where["a.user_id"] = $user_id;
		$where["a.status"] = 1;
		$where["b.status"] = 1;
		$myJoin = M("topic_personnel a")->field('b.*')->join("join __TOPIC_GROUP__ b ON a.topic_group_id=b.id")->where($where)->select();
		
		//我创建的小组 think_topic_group
		$where2["user_id"] = $user_id;
		$where2["status"] = 1;
		$myCreate = M("topic_group")->where($where2)->select();
		
		return array("code"=>1000, "message"=>"ok", "join_list"=>$myJoin, "create_list"=>$myCreate);
	}
	
	/**
	 * 退出小组
	 */
	public function quit($group_id){
		$user_id = $_SESSION["user"]["id"];
		$where = array(
			'user_id'=>$user_id,
			'topic_group_id'=>$group_id,
		);
        $return = M("topic_personnel")->where($where)->save(array('status'=>3)); //更改为删除状态

		//触发小组系统消息
       
		@$res = D('Trigger')->sendTopicMessage($user_id,'',$group_id,0,0,2);
        return $return;
       


	}
	
	/**
	 * 操作我的小组, 参数$id是小组（部落）id
	 */
	public function my($table_search,$id){
        $condition = array(
			'c.email'=>array('like',"%$table_search%")
		);
		$user_id = $_SESSION["user"]["id"];
        $size = 15;

		$p = I("get.p",1,'int');
		//小组（部落）的所有成员操作展示
         $data = M('topic_group a')->field('a.name,b.topic_group_id,b.*')
									->join('join __TOPIC_PERSONNEL__ b on a.id=b.topic_group_id')
									->join('join __USERS__ c on b.user_id=c.id')
									->where($condition)
									->where(array('a.id'=>$id,'a.status'=>1,'b.status'=>1))
									->page($p.','.$size)
									->select();
		 $count = M('topic_group a')->field('a.name,a.topic_group_id,b.*')
									->join('join __TOPIC_PERSONNEL__ b on a.id=b.topic_group_id')
									->join('join __USERS__ c on b.user_id=c.id')
									->where($condition)
									->where(array('a.id'=>$id,'a.status'=>1,'b.status'=>1))
									->count();

        $show = $this->pageClass($count,$size);

         foreach($data as $k=>$v){
				$user = M('users')->where(array('id'=>$v['user_id']))->find();
                $data[$k]['username'] = $user['username'];
				$data[$k]['email'] = $user['email'];
				$data[$k]['gender'] = $user['gender'] == 1 ? '男' : '女';
				if($user['gender'] == 0){
					$data[$k]['gender'] =  '';
				}else if($user['gender'] == 1){
					$data[$k]['gender'] = '男';
				}else if($user['gender'] == 2){
					$data[$k]['gender'] = '女';
				}
				$data[$k]['tissuename'] = $this->gusertissue($v['user_id']);
				$data[$k]['lastTime'] = $this->getlastSayTime($v['user_id']);
                  

			}


		//小组（部落）的所有话题展示
        $alltopic = M('group_topic')->where(array('topic_group_id'=>$id))->select();
		foreach($alltopic as $k=>$v){
              $alltopic[$k]['username'] = M('users')->where(array('id'=>$v['user_id']))->getField('username');
			   $alltopic[$k]['describe'] = strip_tags($v['describe']);
		}
        

        $topicname = M('topic_group')->where(array('id'=>$id))->getField('name');



		$res = array(
			'data'=>$data,
			'count'=>$count,
			'alltopic'=>$alltopic,
			'topicname'=>$topicname,
			'show'=>$show
		);

        return $res;


	}
	


	/**
	 * 获取用户所在组织名
	 */
	public function gusertissue($user_id)
	{
      //
	  	if(!empty($user_id)){

			  	$tissue = M('tissue_group_access a')->field("b.name")->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")->where(array("a.user_id"=>array("eq",$user_id)))->find();
		  }else{

			  $tissue = '';
		  }
		
	 	return $tissue['name'];
	}


	/**
	 * 获取用户最后一次发言时间
	 */
	public function getlastSayTime($user_id)
	{
        $lastTime = M('topic_interaction')->where(array('user_id'=>$user_id))->order('id desc')->limit(1)->getField('publish_time');
		
	 	return $lastTime;
	}
 
    



	/**
	 * 设置组长
	 * status 1设置为组长 0取消组长
	 * user_id 用户id
	 */
	public function setLeader($id,$type){
		$user_id = $_SESSION["user"]["id"];

		$where = array('id'=>$id);

        if($type == 1){
          $data = array('group_leader'=>1);
		}else{
		  $data = array('group_leader'=>0);
		}
		
        $return = M("topic_personnel")->where($where)->save($data); //更改状态
        return $return;

	}
	
	/**
	 * 删除成员
	 * user_id 用户id
	 */
	public function del($id){
		$where = array('id'=>$id);
        $data = array('status'=>3);

        $return = M("topic_personnel")->where($where)->save($data); //更改为删除状态
        return $return;
	}
	
	/**
	 * 解散小组
	 */
	public function disband($id){
		$where = array('id'=>$id);
		$data = array('status'=>3);
		$return = M("topic_group")->where($where)->save($data); //更改为删除状态
        return $return;
	}
	
	/*
	 * 与我有关
	 * type 1回复我的 2跟帖发言 3系统消息
	 */
	public function aboutMe($type){
		$user_id = $_SESSION["user"]["id"];
		$size = 30;
       
		$p = I("get.p",1,'int');
		if($type == 1){
			$myids = M('topic_interaction')->field('id')->where(array('user_id'=>$user_id))->select();
			$myid = '';
			foreach($myids as $k=>$v){
				if($k==0){
				  $myid = $v['id'];
				}else{
                  $myid = $myid.','.$v['id'];
				}				
			}
			//登录者没有任何回复的处理情况
			if(empty($myid)){
              $data = '';
              $count = 0;
			}else{
              $data = M('topic_interaction')->where(array('pid'=>array('in',$myid)))->order('id desc')->page($p.','.$size)->select();
			  $count = M('topic_interaction')->where(array('pid'=>array('in',$myid)))->order('id desc')->count();
			}

			foreach($data as $k=>$v){
				$data[$k]['uname'] = M('users')->where(array('id'=>$v['user_id']))->getField('username'); //获取姓名
				$data[$k]['myname'] = $_SESSION["user"]["username"]; 
				$data[$k]['topicname'] = M('group_topic')->where(array('id'=>$v['topic_id']))->getField('name'); //获取关联话题名
			}
		}else if($type == 2){
            $data = M('group_topic a')->field('a.name,b.topic_id,b.user_id,b.content,b.publish_time')
									->join('join __TOPIC_INTERACTION__ b on a.id=b.topic_id')
									->order('b.publish_time desc')
									->where(array('a.user_id'=>$user_id,'a.status'=>1))
									->page($p.','.$size)
									->select();
            $count = M('group_topic a')->field('a.name,b.topic_id,b.user_id,b.content,b.publish_time')
									->join('join __TOPIC_INTERACTION__ b on a.id=b.topic_id')
									->order('b.publish_time desc')
									->where(array('a.user_id'=>$user_id,'a.status'=>1))
									->count();

			foreach($data as $k=>$v){
				$data[$k]['uname'] = M('users')->where(array('id'=>$v['user_id']))->getField('username'); //获取姓名	
			}

		}else if($type == 3){
              $arr = M('topic_group')->where(array('user_id'=>$_SESSION['user']['id']))->select();
			  $garr = array();
			  foreach($arr as $k=>$v){
                $garr[]= $v['id'];
			  }
			  $groupstr = implode(',',$garr);
              $mymessageArr = M('topic_message')->where(array('topic_group_id'=>array('in',$groupstr)))->order('id desc')->page($p.','.$size)->select();
			  $count = M('topic_message')->where(array('topic_group_id'=>array('in',$groupstr)))->order('id desc')->count();
              foreach($mymessageArr as $k=>$v){
				   
                   $mymessageArr[$k]['username'] = M('users')->where(array('id'=>$v['user_id']))->getField('username');
                   $audit_username = M('users')->where(array('id'=>$v['audit_user_id']))->getField('username');
				   $mymessageArr[$k]['audit_username'] = $audit_username;
				   if($v['type'] == 1){
					 $mymessageArr[$k]['content'] = $v['content'].$audit_username;   
				   }
			  }
             $data = $mymessageArr;
		}
        
   
          $show = $this->pageClass($count,$size);
    //    dump($data);
	      $return = array(
			  'data'=>$data,
			  'page'=>$show
		  );
	   return $return;

	}
	
	/*
	 * 回复消息
	 */
	public function reply(){
		$user_id = $_SESSION["user"]["id"];
	}
}
