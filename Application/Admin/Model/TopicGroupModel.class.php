<?php 

namespace Admin\Model;

use Common\Model\BaseModel;


/**
 * 话题小组模型
 */
class TopicGroupModel extends BaseModel{


     /** 
     *  部落页
     */
     public function getIndex()
     {   
        
         //我关注的小组list
         $loginId = $_SESSION['user']['id'];
        //我加入的小组
        $where["a.user_id"] = $loginId;
        $where["a.status"] = 1;
        $where["b.status"] = 1;
        $myJoin = M("topic_personnel a")->field('b.*')->join("join __TOPIC_GROUP__ b ON a.topic_group_id=b.id")->where($where)->select();
        //我创建的小组 think_topic_group
        $where2["user_id"] = $loginId;
        $where2["status"] = 1;
        $myCreate = M("topic_group")->where($where2)->select();
         $list = array_merge($myJoin,$myCreate);
        //  dump($list);
        $match = array();
        foreach($list as $k=>$v){
           $match[] = $v['id']; 
        }
        $match[] = ''; //登录者没有关注或创建任一部落的处理情况
        
         //我关注的小组详情-小组话题list
         $group_id = I('get.group_id') + 0; //选中的部落id
         $group_id = $group_id ? $group_id : $list[0]['id'];
         //接收的$group_id是否在我关注的小组list
         if(!in_array($group_id,$match)){
            $this->error = '非法输入group_id值';
            return false;
         }
         $topicdata = $this->getgroupTpoicList($group_id);
         $topiclist = $topicdata['list'];
        //  krsort($topiclist);
         
         $data = array(
           'list'=>$list,
           'topiclist'=>$topiclist,
           'group_id'=>$group_id,
           'page'=>$topicdata['page'],
           
         );
       return $data;

     }

     /** 
     *  部落广场页
     */
     public function getBuluoPark()
     {   
         $size = 15;
         $p = I('get.p') ? I('get.p') : 1;
          //小组列表状态   0待审核 1审核通过 2审核拒绝 3申请加入 4由我创建
         $loginId = $_SESSION['user']['id'];
         $list = M('topic_group')->where(array('status'=>1))->page($p.','.$size)->select();
         $count = M('topic_group')->where(array('status'=>1))->count();
         $page = $this->pageClass($count,$size);
         foreach($list as  $k=>$v){
              if($v['user_id'] == $loginId){
                  $list[$k]['describe'] = '由我创建';
                  $list[$k]['flag'] = 4;
              }else{
                $exist = M('topic_personnel')->where(array('topic_group_id'=>$v['id'],'user_id'=>$loginId))->find();
                if($exist){
                    if($exist['status'] == 0){
                     $list[$k]['describe'] = '审核中';
                     $list[$k]['flag'] = 0;
                    }else if($exist['status'] == 1){
                     $list[$k]['describe'] = '已加入该部落';
                     $list[$k]['flag'] = 1;
                    }else if($exist['status'] == 2){
                     $list[$k]['describe'] = '已拒绝';
                     $list[$k]['flag'] = 2;
                    }else if($exist['status'] == 3){
                     $list[$k]['describe'] = '申请加入';
                     $list[$k]['flag'] = 3;
                    }
                  
                }else{
                   $list[$k]['describe'] = '申请加入';
                   $list[$k]['flag'] = 3;
                }

              }
         }
       $data = array(
           'list'=>$list,
           'page'=>$page
       );
       return $data;

     }



   /**
    *申请加入
    */
    public function applyToJoin(){ 
       if(IS_AJAX){
         $apply_reason = I('apply_reason');
         $user_id = $_SESSION['user']['id'];
         $topicGroupId = I('topicGroupId');
         $pdata = array(
           'topic_group_id'=>$topicGroupId,
           'user_id'=>$user_id,
           'apply_reason'=>$apply_reason,
           'status'=>0,   
           );
         $map = array('topic_group_id'=>$topicGroupId,'user_id'=>$user_id);
         $exist = M('topic_personnel')->where($map)->find();
        
         if($exist){
             $ret = M('topic_personnel')->where($map)->save(array('apply_reason'=>$apply_reason,'status'=>0)); 
         }else{
             $ret = M('topic_personnel')->add($pdata); 
         }
        
         if(!$ret){
          $this->error = M('topic_personnel')->getLastSql();
          return false;
          }

         //向具体用户发送消息
          $dataOne = M('topic_group')->where(array('id'=>$topicGroupId))->find();

          $senduser_id = $dataOne['user_id'];
          $title = $dataOne['name'];
          $contents_time = date('Y-m-d H:i:s');
          $type_id = 16;
          $from_id = $_SESSION['user']['id'];
        //   $url = 'Admin/topic_group/topicList/flag/1/id/'.$topicGroupId;
          $url = 'Admin/topic_group/index';
          $res = D('Trigger')->messageTrigger($senduser_id,$title,$contents_time,$type_id,$from_id,$url);
          if(!$res){
          $this->error = '插入消息数据失败';
          return false;
          }
          //关联话题小组消息
          $messagedata = array(
              'message_id'=>$res,
              'type'=>1,
              'correlate_id'=>$topicGroupId
          );
          $res = M('admin_message_topic')->add($messagedata);
           if(!$res){
           $this->error = '插入消息数据失败';
           return false;
           }


        
          return $res;         
       }
     
      }




     /** 
     *发布话题小组
     */
     public function addGroup()
     {
       $fdata = I('post.');
    //    dump($fdata);die;
       //字段规则控制
       if(empty($fdata['name'])){
          $this->error = '部落名称必填';
          return false;
       }else if(mb_strlen($fdata['name']) > 300){
          $this->error = '部落名称超过最大长度限制';
          return false;
       }else if(empty($fdata['theme'])){
          $this->error = '部落主题必填';
          return false;
       }else if(in_array($_SESSION['user']['id'],$fdata['user_id'])){
          $this->error = '邀请人员不能包含自己';
          return false;
       }

   
       $orderno = D('Trigger')->orderNumber(10);
    //    echo $orderno;   die;
       $gdata = array(
           'user_id'=>$_SESSION['user']['id'],
           'name'=>$fdata['name'],
           'theme'=>$fdata['theme'],
           'publish_time'=>date('Y-m-d H:i:s'),
           'status'=>0,
           'orderno'=>$orderno,
           'type'=>1,
           'group_covers'=>$fdata['group_covers'],
       );

       $res = M('topic_group')->add($gdata);
       $topicGroupId = $res;
    // dump($res); die;
       if(!$res){
          $this->error = '插入数据失败';
          return false;
       }

       //往话题邀请人员关联表 插入小组人员关联数据
       foreach($fdata['user_id'] as $k=>$v){
           $pdata = array(
           'topic_group_id'=>$topicGroupId,
           'user_id'=>$v,
           'status'=>0,   
           );
         $ret = M('topic_personnel')->add($pdata); 
         if(!$ret){
          $this->error = '插入数据失败';
          return false;
          }

         //向具体用户发送消息
          $user_id = $v;
          $title = $fdata['name'];
          $contents_time = date('Y-m-d H:i:s');
          $type_id = 16;
          $from_id = $_SESSION['user']['id'];
          $url = 'Admin/topic_group/topicList/flag/1/id/'.$topicGroupId;
          $res = D('Trigger')->messageTrigger($user_id,$title,$contents_time,$type_id,$from_id,$url);
          if(!$res){
          $this->error = '插入消息数据失败1';
          return false;
          }
          //关联话题小组消息
          $messagedata = array(
              'message_id'=>$res,
              'type'=>0,
              'correlate_id'=>$topicGroupId
          );
          $res = M('admin_message_topic')->add($messagedata);
           if(!$res){
           $this->error = '插入消息数据失败2';
           return false;
           }


        }  
        return $res;                       
       
     }


     /** 
     *编辑话题小组
     */
     public function updata()
     {



     }


     /** 
     * 小组内话题列表
     */
     public function getgroupTpoicList($group_id)
     {

       $size = 15;
	   $p = I("get.p",0,'int');
       $group_id = $group_id ? $group_id : '';
       $list = M('group_topic')->alias('a')
                ->join('left join __USERS__ b on a.user_id=b.id')
                ->field('a.*,b.username')
                ->where(array('topic_group_id'=>$group_id))
                ->page($p.','.$size)
                ->order('a.publish_time desc')
                ->select();
       $count = M('group_topic')->alias('a')
                ->join('left join __USERS__ b on a.user_id=b.id')
                ->field('a.*,b.username')
                ->where(array('topic_group_id'=>$group_id))
                ->count();


       foreach($list as $k=>$v){
           $comment_num =  M('topic_interaction')->where(array('topic_id'=>$v['id']))->count();
           $list[$k]['comment_num'] = $comment_num;
           $list[$k]['describe'] = strip_tags($v['describe']);
       }


       $group_name = M('topic_group')->where(array('id'=>$group_id))->getField('name');

       $show = $this->pageClass($count,$size);
       $data = array(
           'list'=>$list,
           'group_name'=>$group_name,
           'page'=>$show
       );

       return $data; 
     }


     /** 
     *话题小组内的话题发布
     */
     public function addTopic()
     {
       $fdata = I('post.');
    
    //    $orderno = D('Trigger')->orderNumber(10);
  
       $gdata = array(
           'topic_group_id'=>$fdata['topic_group_id'],
           'user_id'=>$_SESSION['user']['id'],
           'name'=>$fdata['name'],
           'describe'=>$fdata['content'],
           'publish_time'=>date('Y-m-d H:i:s'),
           'status'=>1,
           'topic_covers'=>$fdata['topic_covers'],
       );

       $res = M('group_topic')->add($gdata);
        if(!$res){
           $this->error = '插入消息数据失败';
           return false;
           }
       return $res;                       
       


     }

 
 

//----------------------话题详情全部逻辑-------------------------------


	/**
     *话题详情
     */
     public function getlist()
	 {
       //接收话题的id
       $topic_id = I('get.id') + 0;
       
       $arr = M('topic_interaction')
       		->alias('a')
		->join('left join __USERS__ b  on b.id=a.user_id')
		->where(array('a.topic_id'=>$topic_id))
		->field('b.username,b.avatar,a.*')
		->select();
       
       foreach($arr as $k=>$v){
        //   M('topic_interaction')->where($v['pid'])->getField('user_id') 
        //每条数据加上“对谁回复”的栈toname
       $usernameArr = M('topic_interaction')
       			->alias('a')
			->join('left join __USERS__ b  on b.id=a.user_id')
			->where(array('a.id'=>$v['pid']))
			->field('b.username,b.id')
			->find();
       $username = $usernameArr['username'];
       $tempuser_id = $usernameArr['id'];
       $arr[$k]['toname'] = $username;
       $arr[$k]['touser_id'] = $tempuser_id;
       //每条数据加上点赞的栈topic_praise
       $praise = M('topic_praise')->where(array('cid'=>$v['id']))->sum('praise');
       $arr[$k]['praise'] = $praise;

       $whetherPraise = M('topic_praise')->where(array('cid'=>$v['id'],'user_id'=>$_SESSION['user']['id']))->getField('praise');
       $arr[$k]['whetherPraise'] = $whetherPraise;
       }

       $list = $this->getTreeDatas($arr,'tree','','id','id','pid'); //调用basemodel的的getTreeDatas，取得tree
   
       //重新组装二维数组
       static $key  = 1;
       foreach ($list as $k => $v) {
        if($v['pid'] == 0){
            $key = $k;
            $list[$key]=$v;
        }else{
            
            $list[$key]['subReply'][]=$v;
            unset($list[$k]);
        }  
     }

      //   array_multisort($list,SORT_DESC); 
       krsort($list); //对数组进行降序排序 
     //对每条话题加入评论数
        foreach ($list as $k => $v) {
          $list[$k]['subComments'] =  count($v['subReply']);

        }
       //数组分页
        $size = 15;
        $pageData = $this->arrayPage($list,$size);
        $list = $pageData['list'];
        $show = $pageData['show'];

        $data = array(
            'list'=>$list,
            'page'=>$show
        );
        
      return $data;
     }


	/**
    *新增互动/话题
    */
     public function add()
	 {
       $topic_id = I('post.topic_id');
       if(I('post.type') == 'public'){
           $orderno = D('Trigger')->orderNumber(5);
           $data = array(
           'orderno'=>$orderno,
           'topic_id'=>$topic_id,
           'user_id'=>$_SESSION['user']['id'],
           'content'=>I('post.content'),
           'publish_time'=>date('Y-m-d H:i:s'),
           'status'=>1,
           'pid'=>0
          );
       }else{
           $pid = I('post.pid');
           $content = I('post.content');
           $data = array(
           'topic_id'=>$topic_id,
           'user_id'=>$_SESSION['user']['id'],
           'content'=> $content,
           'publish_time'=>date('Y-m-d H:i:s'),
           'status'=>1,
           'pid'=> $pid
          );

          //@$this->hudongMessage($pid,'互动评论');  
       }
       $res = M('topic_interaction')->add($data);
       return $res;

         
     }

	/**
    *话题/评论的 删除
    */
     public function del()
	 {
        $id = I('post.id','','intval') ; 
        
        $arr = M('topic_interaction')->where(array('status'=>1))->select();
        
        $list = $this->getOneTree($arr,$id,1);  //调用basemodel的getOneTree公共方法

        $list[]=array( 'id'=>$id );
        // dump($list); 
        // 自动启动事务支持
        $this->startTrans();
        try {  
            foreach($list as $v) {  
             $res = M('topic_interaction')->where(array('id'=>$v['id']))->delete();
              if (false === $res) {
                    // 发生错误自动回滚事务
                    $this->rollback();
                    return false;
                } 
            }  
         // 提交事务
            $this->commit();
           
             return true;
        } catch (ThinkException $e) {
            $this->rollback();
        }
 
     }

	/**
    *话题的 点赞
    */
     public function praise()
	 {
       $cid = I('post.id',0,'intval') ;
       $type =  I('post.type') ;
       $map = array(
              'cid'=>$cid,
              'user_id'=>$_SESSION['user']['id']
              );
       if($type == "praise"){ //点赞操作  
       $ret = M('topic_praise')->where($map)->find(); //判断登陆者是否对该条话题进行点赞过
       if(!$ret){//初次点赞
           $arr = array(
               'cid'=> $cid,
               'praise'=>1,
               'praise_time'=>date('Y-m-d H:i:s'),
               'user_id'=>$_SESSION['user']['id']
           );

           $res = M('topic_praise')->add($arr);
            if($res){
                //@$this->hudongMessage($cid,'互动点赞');
                return true;
            }else{
                return false;
            }
       }else{ //点赞后的二次操作，点赞操作
          $arr = array(
               'praise'=>1,      
           );
          $res = M('topic_praise')->where($map)->save($arr);
           if($res){
                return true;
            }else{
                return false;
            }
       }

       }else if($type == "cancel"){ //取消点赞操作
          $arr = array(
               'praise'=>0,      
           );
          
        //   echo $cid;
          $res = M('topic_praise')->where($map)->save($arr);
        //   print_r($res);
            if($res){
                return true;
            }else{
                return false;
            }       
       }
    
     }
	/**
    *互动-消息触发
    */
     public function hudongMessage($cid,$title)
	 {
        $user_id = M('topic_interaction')->where(array('id'=>$cid))->getField('user_id');

        $contents_time = date('Y-m-d H:i:s');
        $type_id = 14;
        $from_id = $_SESSION['user']['id'];
        $url = 'Admin/FriendsCircle/friendsCircleList#c'.$cid;
        D('Trigger')->messageTrigger($user_id,$title,$contents_time,$type_id,$from_id,$url);
     }





    /*
     *工作圈列表-判断该话题是否已点赞
     * list
     */
    public function whetherPraise()
    {  
         $id = I('id');
         $mytagid = $_SESSION['user']['id'];
         $map = array('cid'=>$id,'user_id'=>$mytagid);
		  $num = M('topic_praise')->where($map)->getField('praise');
          if($num == 1){
              return 'aaaaa';
          }
    }


	/**
    *获取话题名称
    */
     public function getTopicName($id)
	 {
        $topic_group_id = M('group_topic')->where(array('id'=>$id))->getField('topic_group_id');
        $topic_name = M('topic_group')->where(array('id'=>$topic_group_id))->getField('name');

        return $topic_name; 
     }


	/**
    *获取话题详情
    */
     public function getTheTopic($topic_id)
	 {
        $data = M('group_topic')->alias('a')
                 ->join('left join __USERS__ b on a.user_id=b.id')
                 ->field('a.*,b.username,b.avatar')
                 ->where(array('a.id'=>$topic_id))
                 ->find();

        return $data; 
     }





    public function circle($list){
         
         foreach($list as $k=>$v){
             if($v['_data']){
                //  echo '2'.'<br/>';
               foreach($v['_data'] as $k1=>$v1){ 
               $list[$k]['_data'][$k1]['toname'] = $v['username'];
        
               $this->circle($v1);
               
               }     
             }       
        }
        return $list;    
      }

	/**
    *判断是否为小组组长或创建者 是则返回1
    */
    public function getLeader($id){
         $login_id = $_SESSION['user']['id'];
         $group_id = M('group_topic')->where(array('id'=>$id))->getField('topic_group_id');
         //登录者是否为创建者
         $exist1 = M('topic_group')->where(array('id'=>$group_id,'user_id'=>$login_id))->find();
         //登录者是否为组长
         $exist2 = M('topic_personnel')->where(array('topic_group_id'=>$group_id,'user_id'=>$login_id,'group_leader'=>1))->find();
         if($exist1 || $exist2){
            $res = 1;
         }else{
            $res = 0;
         }
         return $res;
        }



}