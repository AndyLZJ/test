<?php 

namespace Admin\Model;
use Common\Model\BaseModel;
 /**
 * 
 */
/**
 * 项目互动模型
 * @author lijinbao 20170512
 */
class ItemInteractionModel extends BaseModel{



    /** 
     *项目互动列表
     */
     public function getItemList()
     {

       $size = 15;
        
       $p =  I("get.p",0,'int');


        $sessionuser_id = $_SESSION['user']['id'];

        if($sessionuser_id == 1){
        //超级管理员可看到所有项目
         $map = array('a.id'=>array('gt',0));
        }else{
         $map = "b.user_id=$sessionuser_id or a.user_id=$sessionuser_id";
        }
        
        $keyword = I('get.table_search') !== '' ? I('get.table_search') : '';
        $condition  = array(
                'a.project_name'=>array("like","%$keyword%"),    
                'a.type'=> array("in",'0,4'),      
            );
        // $map['a.project_name'] = array("like","%$keyword%");
        // $map['a.type'] = array("in",'0,4');
       //隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

         $condition['a.auth_user_id'] = array("in",$specifiedUser);


       $list =  M('admin_project')->alias('a')
               ->distinct(true)
               ->field('a.*')
               ->join('left join __DESIGNATED_PERSONNEL__ b on b.project_id=a.id')
               ->where($map)
               ->where($condition)
               ->page($p.','.$size)
               ->select();
 
         $count =  M('admin_project')->alias('a')
			   ->distinct(true)
               ->field('a.*')
               ->join('left join __DESIGNATED_PERSONNEL__ b on b.project_id=a.id')
               ->where($map)
               ->where($condition)
               ->select();

		$count = count($count);
		// print_R($list);
		//exit;
		
		//隔离数据过滤
		$list = D('IsolationData')->isolationData($list);

            //    echo M('admin_project')->_sql();
   
        foreach($list as $k=>$v){
           //每个项目的指定人数
             $designated_num =  M('designated_personnel')->where(array('project_id'=>$v['id']))->count();
             $list[$k]['designated_num'] = $designated_num;

           //每个项目的互动(包括评论及回复)总数
             $interaction_num =  M('item_interaction')->where(array('project_id'=>$v['id']))->count();
             $list[$k]['interaction_num'] = $interaction_num;

        }
        $page = $this->pageClass($count,$size);
        
        $data = array(
            'list'=>$list,
            'page'=>$page,
            'keyword'=>$keyword
        );
        return $data;
     }  


    /** 
     *项目互动管理列表
     */
     public function getItemManageList()
     {

       $size = 15;
        
       $p = I('p') ? I('p') : 1 ;

        
        $keyword = I('get.table_search') !== '' ? I('get.table_search') : '';
        $condition  = array(
                'a.project_name'=>array("like","%$keyword%"),    
                'a.type'=> array("in",'0,4'),      
            );

       //解决数据量过大查询慢 - 方法1
       $specifiedUser = D('IsolationData')->specifiedUser();

      $condition['a.auth_user_id'] = array("in",$specifiedUser);

       $list =  M('admin_project')->alias('a')
               ->distinct(true)
               ->field('a.*')
               ->join('left join __DESIGNATED_PERSONNEL__ b on b.project_id=a.id')
               ->where($condition)
               ->page($p.','.$size)
               ->select();

         $count =  M('admin_project')->alias('a')
               ->distinct(true)
               ->field('a.*')
               ->join('left join __DESIGNATED_PERSONNEL__ b on b.project_id=a.id')
               ->where($condition)
               ->count();
            //    echo M('admin_project')->_sql();

          //隔离数据过滤
         //$list = D('IsolationData')->isolationData($list);


        foreach($list as $k=>$v){
           //每个项目的指定人数
             $designated_num =  M('designated_personnel')->where(array('project_id'=>$v['id']))->count();
             $list[$k]['designated_num'] = $designated_num;

           //每个项目的互动(包括评论及回复)总数
             $interaction_num =  M('item_interaction')->where(array('project_id'=>$v['id']))->count();
             $list[$k]['interaction_num'] = $interaction_num;

        }
        $page = $this->pageClass($count,$size);
        // dump($list);
        $data = array(
            'list'=>$list,
            'page'=>$page,
            'keyword'=>$keyword
        );
        return $data;
     }



	/**
     *项目互动详情-互动列表/话题
     */
     public function getlist()
	 {
       //接收项目的id
       $project_id = I('get.project_id') + 0;
       
	   	if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "b.username,b.avatar,a.id,a.project_id,a.user_id,a.content,a.images,a.status,a.pid,a.orderno,";
			$field .= "to_char(a.publish_time,'YYYY-MM-DD HH24:MI:SS') as publish_time";
		}else{
			$field = "b.username,b.avatar,a.*";
		}
       $arr = M('item_interaction')
   			->alias('a')
   			->join('left join __USERS__ b  on b.id=a.user_id')
   			->where(array('a.project_id'=>$project_id))
   			->field($field)
   			->select();
       
       foreach($arr as $k=>$v){
        //   M('item_interaction')->where($v['pid'])->getField('user_id') 
        //每条数据加上“对谁回复”的栈toname
	       $usernameArr = M('item_interaction')->alias('a')->join('left join __USERS__ b on b.id=a.user_id')->where(array('a.id'=>$v['pid']))->field('b.username,b.id')->find();
	       $username = $usernameArr['username'];
	       $tempuser_id = $usernameArr['id'];
	       $arr[$k]['toname'] = $username;
	       $arr[$k]['touser_id'] = $tempuser_id;
	       //每条数据加上点赞的栈item_praise
	       $praise = M('item_praise')->where(array('cid'=>$v['id']))->sum('praise');
	       $arr[$k]['praise'] = $praise;
	
	       $whetherPraise = M('item_praise')->where(array('cid'=>$v['id'],'user_id'=>$_SESSION['user']['id']))->getField('praise');
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
       $project_id = I('post.project_id');
       if(I('post.type') == 'public'){
           $orderno = D('Trigger')->orderNumber(5);
           $data = array(
           'orderno'=>$orderno,
           'project_id'=>$project_id,
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
           'project_id'=>$project_id,
           'user_id'=>$_SESSION['user']['id'],
           'content'=> $content,
           'publish_time'=>date('Y-m-d H:i:s'),
           'status'=>1,
           'pid'=> $pid
          );

          //@$this->hudongMessage($pid,'互动评论');  
       }
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('item_interaction');
			$data['publish_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}
       	$res = M('item_interaction')->add($data);
       	return $res;
    }

	/**
    *话题/评论的 删除
    */
     public function del()
	 {
        $id = I('post.id','','intval') ; 
        
        $arr = M('item_interaction')->where(array('status'=>1))->select();
        
        $list = $this->getOneTree($arr,$id,1);  //调用basemodel的getOneTree公共方法

        $list[]=array( 'id'=>$id );
        // dump($list); 
        // 自动启动事务支持
        $this->startTrans();
        try {  
            foreach($list as $v) {  
             $res = M('item_interaction')->where(array('id'=>$v['id']))->delete();
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
       $ret = M('item_praise')->where($map)->find(); //判断登陆者是否对该条话题进行点赞过
       if(!$ret){//初次点赞
           $arr = array(
               'cid'=> $cid,
               'praise'=>1,
               'praise_time'=>date('Y-m-d H:i:s'),
               'user_id'=>$_SESSION['user']['id']
           );
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$arr['praise_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");;
				$arr['id'] = getNextId('item_praise');
			}
           $res = M('item_praise')->add($arr);
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
          $res = M('item_praise')->where($map)->save($arr);
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
          $res = M('item_praise')->where($map)->save($arr);
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
        $user_id = M('item_interaction')->where(array('id'=>$cid))->getField('user_id');

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
		  $num = M('item_praise')->where($map)->getField('praise');
          if($num == 1){
              return 'aaaaa';
          }
    }


	/**
    *获取项目名称
    */
     public function getItemName($id)
	 {
        $project_name = M('admin_project')->where(array('id'=>$id))->getField('project_name');
        return $project_name; 
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
      





}


