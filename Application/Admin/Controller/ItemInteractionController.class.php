<?php 

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 项目互动控制器
 */
class ItemInteractionController extends AdminBaseController{


    /** 
     *项目互动列表
     */
     public function index()
     {
       $data = D('ItemInteraction')->getItemList();
       $this->assign('list',$data['list']);
       $this->assign('page',$data['page']);
       $this->assign('keyword',$data['keyword']);
       $this->display();

     }  

    /** 
     *项目互动管理列表
     */
     public function manageindex()
     {
       $data = D('ItemInteraction')->getItemManageList();
       $this->assign('list',$data['list']);
       $this->assign('page',$data['page']);
       $this->assign('keyword',$data['keyword']);
       $this->display('manageindex');

     }  



    /*
     *项目互动详情
     * list
     */
    public function friendsCircleList()
    {   
        //接收项目的id
        $project_id = I('get.project_id') + 0;
        
        // dump($_SESSION['user']);
        $data = D('ItemInteraction')->getlist();
        // dump($list);
        $project_name = D('ItemInteraction')->getItemName($project_id);
        
        $this->assign('project_id',$project_id);
        $this->assign('project_name',$project_name);
        $this->assign('mytagid',$_SESSION['user']['id']);
        $this->assign('mytagavatar',$_SESSION['user']['avatar']);
        $this->assign('list',$data['list']);
        $this->assign('page',$data['page']);
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

        $list = D('ItemInteraction')->getlist();

        $project_name = D('ItemInteraction')->getItemName($project_id);
        
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
        	$res = D('ItemInteraction')->add();
        //  echo $res;
         if($res){
            $publish_time = M('item_interaction')
            	->where(array('id'=>$res))
            	->field("to_char(publish_time,'YYYY-MM-DD HH24:MI:SS') as publish_time")
				->find();
			$publish_time = $publish_time['publish_time'];
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

       $res = D('ItemInteraction')->del();
       $this->ajaxreturn($res); 
     }

	/**
    *话题的 点赞
    */
     public function praiseFriendsCircle()
	 {

       $res = D('ItemInteraction')->praise();
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
         $res = D('ItemInteraction')->add();
        
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
       return $list = D('ItemInteraction')->whetherPraise();
    }

}