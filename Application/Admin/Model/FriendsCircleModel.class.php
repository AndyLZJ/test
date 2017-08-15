<?php 

namespace Admin\Model;

use Common\Model\BaseModel;
/**
 * 工作圈模型
 */
class FriendsCircleModel extends BaseModel{	




	/**
    *新增互动/话题
    */
     public function add()
	 {

       $auth_center_id = $this->getCenterId();
       if(I('post.type') == 'public'){
           $orderno = D('Trigger')->orderNumber(5);
           //把编辑器的html代码的图片上传路径截取出来
           $content = I('post.content');
           preg_match_all ("/<img src=\"(.*)\"/U", $content, $pat_array);
        //    dump($pat_array);  exit;
           $images = implode(",",$pat_array[1]);

           $data = array(
	           'orderno'=>$orderno,
	           'user_id'=>$_SESSION['user']['id'],
	           'content'=>I('post.content'),
	           'images'=>$images,
	           'publish_time'=>date('Y-m-d H:i:s'),
	           'status'=>0,
	           'type'=>1,
	           'pid'=>0,
	           'auth_center_id'=>$auth_center_id
          );
       }else{
           $pid = I('post.pid');
           $content = I('post.content');
           $data = array(
	           'user_id'=>$_SESSION['user']['id'],
	           'content'=> $content,
	           'publish_time'=>date('Y-m-d H:i:s'),
	           'status'=>1,
	           'type'=>1,
	           'pid'=> $pid,
	           'auth_center_id'=>$auth_center_id
          );

          @$this->hudongMessage($pid,'互动评论');  
       }
	   
	    if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('friends_circle');
			$data['publish_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}
		
       $res = M('friends_circle')->add($data);
       return $res;

       
     }



	/**
    *获取登录者所在顶级组织的二级组织
    */
     public function getCenterId()
	 {  

        //获取登录者当前所在组织id
		$login_id = $_SESSION['user']['id'];
		$tissue = M('tissue_group_access')->where(array('user_id'=>$login_id))->find();
        $tissue_id = $tissue['tissue_id'];
       
        $arr = M('tissue_rule')->select();
        $data = $this->gettree($arr,$tissue_id,'','id','pid'); //调用basemodel的 gettree 方法
    //   dump($data[1]['id']);
        return $data[1]['id'];
        
     }
	/**
    *话题/评论的 删除
    */
     public function del()
	 {
        $id = I('post.id','','intval') ; 
        
        $arr = M('friends_circle')->where(array('status'=>1))->select();
        
        $list = $this->getOneTree($arr,$id,1);  //调用basemodel的getOneTree公共方法

        $list[]=array( 'id'=>$id );
        // dump($list); 
        // 自动启动事务支持
        $this->startTrans();
        try {  
            foreach($list as $v) {  
             $res = M('friends_circle')->where(array('id'=>$v['id']))->delete();
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
       $ret = M('friends_praise')->where($map)->find(); //判断登陆者是否对该条话题进行点赞过
       if(!$ret){//初次点赞
           $arr = array(
               'cid'=> $cid,
               'praise'=>1,
               'praise_time'=>date('Y-m-d H:i:s'),
               'user_id'=>$_SESSION['user']['id']
           );
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$arr['id'] = getNextId('friends_praise');
				$arr['praise_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}
           $res = M('friends_praise')->add($arr);
            if($res){
                @$this->hudongMessage($cid,'互动点赞');
                return true;
            }else{
                return false;
            }
       }else{ //点赞后的二次操作，点赞操作
          $arr = array(
               'praise'=>1,      
           );
          $res = M('friends_praise')->where($map)->save($arr);
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
          $res = M('friends_praise')->where($map)->save($arr);
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
        $user_id = M('friends_circle')->where(array('id'=>$cid))->getField('user_id');

        $contents_time = date('Y-m-d H:i:s');
        $type_id = 14;
        $from_id = $_SESSION['user']['id'];
        $url = 'Admin/FriendsCircle/friendsCircleList#c'.$cid;
        D('Trigger')->messageTrigger($user_id,$title,$contents_time,$type_id,$from_id,$url);
     }

	/**
    *互动列表/话题
    */
     public function getlist()
	 {
       $auth_center_id = $this->getCenterId();
       //admin具有查看所有工作圈的权限
       if($_SESSION['user']['id'] == 1){
           $where = array('a.status'=>1);
       }else{
           $where = array('a.status'=>1,'auth_center_id'=>$auth_center_id);
       }
       
	   	if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "b.username,b.avatar,a.id,a.user_id,a.content,a.images,a.status,a.pid,a.state,a.orderno,a.objection";
			$field .= ",a.type,a.auth_center_id,to_char(a.publish_time,'YYYY-MM-DD HH24:MI:SS') as publish_time";
		}else{
			$field = "b.username,b.avatar,a.*";
		}
       $arr = M('friends_circle')
       		->alias('a')
       		->join('left join __USERS__ b on b.id=a.user_id')
       		->where($where)
       		->field($field)
       		->select();
       
       foreach($arr as $k=>$v){
            if($v['type'] == 0 && $v['pid'] == 0){
                $images = explode(',',$v['images']);
                $tempstr = '';
            foreach($images as $ko=>$vo){
                
                $tempstr .= "<img src='".$vo."' width='250' height='240'/>";
            }


                $arr[$k]['content'] = $v['content']."<p>".$tempstr."</p>";
            }

        
            //每条数据加上“对谁回复”的栈toname
        $usernameArr = M('friends_circle')->alias('a')->join('left join __USERS__ b  on b.id=a.user_id')->where(array('a.id'=>$v['pid']))->field('b.username,b.id')->find();
        $username = $usernameArr['username'];
        $tempuser_id = $usernameArr['id'];
        $arr[$k]['toname'] = $username;
        $arr[$k]['touser_id'] = $tempuser_id;
        //每条数据加上点赞的栈friends_praise
        $praise = M('friends_praise')->where(array('cid'=>$v['id']))->sum('praise');
        $arr[$k]['praise'] = $praise;

        $whetherPraise = M('friends_praise')->where(array('cid'=>$v['id'],'user_id'=>$_SESSION['user']['id']))->getField('praise');
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
    //    dump($list);
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



    /*
     *工作圈列表-判断该话题是否已点赞
     * list
     */
    public function whetherPraise()
    {  
         $id = I('id');
         $mytagid = $_SESSION['user']['id'];
         $map = array('cid'=>$id,'user_id'=>$mytagid);
		  $num = M('friends_praise')->where($map)->getField('praise');
          if($num == 1){
              return 'aaaaa';
          }
    }


    public function circle($list){
         
         foreach($list as $k=>$v){
             if($v['_data']){
                //  echo '2'.'<br/>';
               foreach($v['_data'] as $k1=>$v1){ 
               $list[$k]['_data'][$k1]['toname'] = $v['username'];
                // print_r($list);
               $this->circle($v1);

            //    echo $v['username']; 
                //  self::circle(&$v1);                   
               }
            //   dump($v['_data']) ;
             }
            
        }
        return $list;
        
      }
      




}