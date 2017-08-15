<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 工作圈控制器
 */
class FriendsCircleController extends AdminBaseController
{

    /*
     * 发布工作圈
     * $content
     */
    public function publicFriendsCircle()
    {   
        
        if(IS_POST){
         $res = D('FriendsCircle')->add();
        //  echo $res;
         if($res){
            $publish_time = M('friends_circle')->where(array('id'=>$res))->getField('publish_time');
            $data = array(
                'id'=>$res,
                'status'=>1,
                'user_id'=>$_SESSION['user']['id'],
                'username'=>$_SESSION['user']['username'],
                'avatar'=>$_SESSION['user']['avatar'],
                'publish_time'=>$publish_time
            ); 
            //调用公共Model里的方法,发布工作圈状态 积分触发
             @D('Trigger')->intergrationTrigger($_SESSION['user']['id'],14);           
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

       $res = D('FriendsCircle')->del();
       $this->ajaxreturn($res); 
     }

	/**
    *话题的 点赞
    */
     public function praiseFriendsCircle()
	 {

       $res = D('FriendsCircle')->praise();
       //调用公共Model里的方法,对他人学课的问题回复／评论 积分触发
       @D('Trigger')->intergrationTrigger($_SESSION['user']['id'],15);      
       $this->ajaxreturn($res); 
     }

    /*
     *回复评论-个人互动
     * 
     */
    public function replyFriendsCircle()
    { 
     if(IS_POST){
         $res = D('FriendsCircle')->add();
        
         if($res){
             //调用公共Model里的方法,对他人学课的问题回复／评论 积分触发
             @D('Trigger')->intergrationTrigger($_SESSION['user']['id'],15);      
             
             $this->ajaxreturn($res); //为真则返回增加数据的id
   
         }else{
             $this->ajaxreturn(false);
         }
         
        }



    }
    /*
     *工作圈列表-个人互动
     * list
     */
    public function friendsCircleList()
    {   
        // dump($_SESSION['user']);
        $data = D('FriendsCircle')->getlist();
        // dump($data['list']);
        $this->assign('mytagid',$_SESSION['user']['id']);
        $this->assign('mytagavatar',$_SESSION['user']['avatar']);
        $this->assign('list',$data['list']);
        $this->assign('page',$data['page']);
        $this->display('friendscirclelist');
    }





    /*
     *工作圈列表-管理员互动列表
     * list
     */
    public function manageList()
    {  
        $data = D('FriendsCircle')->getlist();
        // dump($list);
        $this->assign('mytagid',$_SESSION['user']['id']);
        $this->assign('mytagavatar',$_SESSION['user']['avatar']);
        $this->assign('list',$data['list']);
        $this->assign('page',$data['page']);
        $this->display('friendsmanage');
    }


    /*
     *工作圈列表-判断该话题是否已点赞
     * list
     */
    public function whetherPraise()
    {  
        //  $map = array('cid'=>$v['id'],'user_id'=>$mytagid);
		//  $num = M('friends_praise')->where($map)->getField('praise');
       return $list = D('FriendsCircle')->whetherPraise();
    }



}