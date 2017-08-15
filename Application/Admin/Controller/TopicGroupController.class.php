<?php 

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 话题小组控制器
 */
class TopicGroupController extends AdminBaseController{



     /** 
     *话题小组lists
     */
     public function oldindex()
     {
       $list = D('TopicGroup')->getIndex();

    //    print_r($list);
       $this->assign('list',$list);
       $this->display('index');


     }



     /** 
     *  部落页
     */
     public function index()
     {
      
       $data = D('TopicGroup')->getIndex();
       if(!$data){
           $this->error(D('TopicGroup')->getError());
       }
    //    print_r($list);
       $this->assign('list',$data['list']);
       $this->assign('topiclist',$data['topiclist']);
       $this->assign('group_id',$data['group_id']);
       $this->assign('page',$data['page']);
    //    $this->display('index');
       $this->display('buluo');

     }
     
     /** 
     *部落广场页
     */
     public function buluoPark()
     {
       
       $data = D('TopicGroup')->getBuluoPark();

    //    print_r($list);
       $this->assign('list',$data['list']);
       $this->assign('page',$data['page']);
       $this->display('buluo_park');

     }




   /**
    *申请加入小组
    */
    public function applyToJoin(){ 
      $TopicGroup = D('TopicGroup');
  
      $user_id = $_SESSION['user']['id']; 
      $res =  $TopicGroup->applyToJoin();
     if (!$res) {
         
         $data['status'] = 0;  
         $data['info'] = D('TopicGroup')->getError();  
         $this->ajaxReturn($data);
      }else{
           
         $data['status'] = 1;  
         $data['info'] = '申请加入成功，请等待结果';  
         $data['url'] = U('Admin/TopicGroup/buluoPark'); 
    
         $this->ajaxReturn($data);
          } 
     
      }




    /** 
     *发布话题页面
     */
     public function addDetail()
     {


       $this->display('addgroup');
       

     }


     /** 
     *发布话题小组
     */
     public function add()
     {
        $res = D('TopicGroup')->addGroup();
         
        if($res){
          $this->success('发布成功，请等待审核结果',U('Admin/TopicGroup/index'));
        }else{
          $this->error(D('TopicGroup')->getError());
        }
 
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
     public function groupTpoicList()
     {
       $group_id = I('get.group_id');
       $data = D('TopicGroup')->getgroupTpoicList($group_id);
       
    //    print_r($data);
       $this->assign('list',$data['list']);
       $this->assign('group_id',$group_id);
       $this->assign('group_name',$data['group_name']);
       $this->assign('page',$data['page']);
       $this->display('group_topic');

     }


     /** 
     *话题小组内的话题发布
     */
     public function addTopicShow()
     {
       //接收部落的id
       $group_id = I('get.group_id') + 0;
       //接收部落页的id
       $flag = I('get.flag') + 0;
       $this->assign('group_id',$group_id);
       $this->assign('flag',$flag);
       $this->display('add_group_topic');

     }


     /** 
     *话题小组内的话题发布
     */
     public function addTopic()
     {
        $topic_group_id = I('post.topic_group_id') + 0; 
        $flag = I('post.flag') + 0; 
        $res = D('TopicGroup')->addTopic();
         
        if($res){
          //触发小组系统消息
		  @$res = D('Trigger')->sendTopicMessage($_SESSION["user"]["id"],'',$topic_group_id,$res,0,4);
          if($flag == 1){
           $this->success('发布成功',U('Admin/TopicGroup/index',array('group_id'=>$topic_group_id)));
          }else{
           $this->success('发布成功',U('Admin/TopicGroup/groupTpoicList',array('group_id'=>$topic_group_id)));
          }
          
        }else{
          $this->error(D('TopicGroup')->getError());
        }
      

     }


//----------------------话题详情-------------------------------



    /*
     *项目互动详情
     * list
     */
    public function friendsCircleList()
    {   
        //接收话题的id
        $topic_id = I('get.id') + 0;
        
        //判断登录者是否是组长或部落创建者，若是则具有删帖的权限
        $whether_leader = D('TopicGroup')->getLeader($topic_id);

        // dump($_SESSION['user']);
        $data = D('TopicGroup')->getlist();
        // dump($list);
        $topic_name = D('TopicGroup')->getTopicName($topic_id);
        $theTopic = D('TopicGroup')->getTheTopic($topic_id);

        $this->assign('theTopic',$theTopic);
        $this->assign('topic_id',$topic_id);
        $this->assign('topic_name',$topic_name);
        $this->assign('mytagid',$_SESSION['user']['id']);
        $this->assign('mytagavatar',$_SESSION['user']['avatar']);
        $this->assign('list',$data['list']);
        $this->assign('page',$data['page']);
        $this->assign('whether_leader',$whether_leader);
        $this->display('friendscirclelist');
    }


    /*
     * 项目互动管理详情
     * list
     */
    public function manageList()
    {   
        //接收项目的id
        $project_id = I('get.project_id') + 0;

        $data = D('TopicGroup')->getlist();

        $project_name = D('TopicGroup')->getItemName($project_id);
        
        $this->assign('project_id',$project_id);
        $this->assign('project_name',$project_name);
        $this->assign('mytagid',$_SESSION['user']['id']);
        $this->assign('mytagavatar',$_SESSION['user']['avatar']);
        $this->assign('list',$data['list']);
        $this->assign('page',$data['page']);
        $this->display('friendsmanage');
    }


    /*
     * 发布工作圈
     * $content
     */
    public function publicFriendsCircle()
    {   
        
        if(IS_POST){
         $res = D('TopicGroup')->add();
        //  echo $res;
         if($res){
            $publish_time = M('topic_interaction')->where(array('id'=>$res))->getField('publish_time');
            $data = array(
                'id'=>$res,
                'status'=>1,
                'user_id'=>$_SESSION['user']['id'],
                'username'=>$_SESSION['user']['username'],
                'avatar'=>$_SESSION['user']['avatar'],
                'publish_time'=>$publish_time
            ); 
            //调用公共Model里的方法,发布工作圈状态 积分触发
             //@D('Trigger')->intergrationTrigger($_SESSION['user']['id'],14);           
         }else{
            $data = array(
                'status'=>0,
                'username'=>$_SESSION['user']['username']
            );
         }
         $this->ajaxreturn($data);
        }


    }
	/**
    *话题/评论的 删除
    */
     public function delFriendsCircle()
	 {

       $res = D('TopicGroup')->del();
       $this->ajaxreturn($res); 
     }

	/**
    *话题的 点赞
    */
     public function praiseFriendsCircle()
	 {

       $res = D('TopicGroup')->praise();
       //调用公共Model里的方法,对他人学课的问题回复／评论 积分触发
       //@D('Trigger')->intergrationTrigger($_SESSION['user']['id'],15);      
       $this->ajaxreturn($res); 
     }

    /*
     *回复评论-个人互动
     * 
     */
    public function replyFriendsCircle()
    { 
     if(IS_POST){
         $res = D('TopicGroup')->add();
        
         if($res){
             //调用公共Model里的方法,对他人学课的问题回复／评论 积分触发
             //@D('Trigger')->intergrationTrigger($_SESSION['user']['id'],15);      
             
             $this->ajaxreturn($res); //为真则返回增加数据的id
   
         }else{
             $this->ajaxreturn(false);
         }
         
        }



    }



    /*
     *工作圈列表-判断该话题是否已点赞
     * list
     */
    public function whetherPraise()
    {  
        //  $map = array('cid'=>$v['id'],'user_id'=>$mytagid);
		//  $num = M('friends_praise')->where($map)->getField('praise');
       return $list = D('TopicGroup')->whetherPraise();
    }

}





