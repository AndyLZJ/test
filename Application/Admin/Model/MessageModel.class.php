<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 消息通知模型
 */
class MessageModel extends BaseModel{
    protected $trueTableName= 'think_admin_message';
  
  /**
   * 消息通知
   */
   public function messList($user_id){ //$user_id为登陆者的session
       //查看全部消息
      //  $where = array(
      //    'a.user_id'=>$user_id,
      //    'a.is_delete'=>0
          
      //  );
         $size = 15;
      //  if(I('tabType')==1){
         $p1 = $_GET['p1'] ?  $_GET['p1'] : 1 ;
      //  }elseif(I('tabType')==2){
         $p2 = $_GET['p2'] ?  $_GET['p2'] : 1 ;
         $p3 = $_GET['p3'] ?  $_GET['p3'] : 1 ;
      //  }
       /*查看全部消息*/
       $searchType = I('searchType');  //接收搜索类型： 1表示全部消息，2表示未读，2表示已读 
       $condition = I('table_search');    //接收get地址参数 table_search，搜索框值
       $condition1 = $searchType==1 ? $condition : '';  //判断是否是全部消息，真则搜索条件赋值“搜索框值”，假则赋空值
       
       $map = array(
         'a.title'=>array("like","%".$condition1."%"),
         'c.type_name'=>array('like',"%".$condition1."%"),
         '_logic'=>'or'

       );
       $maps['_complex'] = $map;
       $maps['a.user_id'] = array('eq',$user_id);
       $maps['a.is_delete'] = array('eq',0);
	   
	   	if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.title,c.type_name,a.status,b.username as fromname,to_char(a.newstime,'YYYY-MM-DD HH24:MI:SS') as newstime";
		}else{
			$field = "a.id,a.title,c.type_name,a.status,a.newstime,b.username as fromname";
		}
       $list[] =  $this->alias('a')
                       ->field($field)
                       ->join('left join __USERS__ b on a.from_id=b.id')
                       ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
                       ->where($maps)
                       ->order('a.newstime desc')
                       ->page($p1.','.$size)
                       ->select();
       $count1 = $this->alias('a')
                       ->field($field)
                       ->join('left join __USERS__ b on a.from_id=b.id')
                       ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
                       ->where($maps)
                       ->order('a.newstime desc')
                       ->count();
        // $par = "tabType=1";
       $i=1; //设置url的tabType=1
       $show1 = tabPage($count1,$size,'p1',$i);
      
                    // print_r($list);

          /*查看未读消息*/
         $condition = I('table_search'); //重新接收get地址参数 table_search，搜索框值
         $condition2 = $searchType==2 ? $condition : ''; 
        //   $where = array(
        //  'a.user_id'=>$user_id,
        //  'a.status'=>0 ,
        //  'a.is_delete'=>0
        //    );
         $map = array(
           'a.title'=>array('like',"%".$condition2."%"),
           'c.type_name'=>array('like',"%".$condition2."%"),
           '_logic'=>'or'
           );
         $maps['_complex'] = $map;
         $maps['a.user_id'] = array('eq',$user_id);
         $maps['a.status'] = array('eq',0);
         $maps['a.is_delete'] = array('eq',0);
		 
         $list[] =  $this->alias('a')
                     ->field($field)
                     ->join('left join __USERS__ b on a.from_id=b.id')
                     ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
                    //  ->where($where)
                     ->where($maps)
                     ->order('a.newstime desc')
                     ->page($p2.','.$size)
                     ->select();
            $count2 =  $this->alias('a')
                     ->field($field)
                     ->join('left join __USERS__ b on a.from_id=b.id')
                     ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
                    //  ->where($where)
                     ->where($maps)
                     ->order('a.newstime desc')
               
                     ->count();
          $i=2; //设置url的tabType=2
          $show2 = tabPage($count2,$size,'p2',$i);



           /*查看已读消息*/
          $condition = I('table_search');  //重新接收get地址参数 table_search，搜索框值
          $condition3 = $searchType==3 ? $condition : '';
        //   $where = array(
        //  'a.user_id'=>$user_id,
        //  'a.status'=>1 , //消息读取状态0表示未读，1表示已读
        //  'a.is_delete'=>0
        //    );
          $map = array(
           'a.title'=>array('like',"%".$condition3."%"),
           'c.type_name'=>array('like',"%".$condition3."%"),
           '_logic'=>'or'
            );
          $maps['_complex'] = $map;
          $maps['a.user_id'] = array('eq',$user_id);
          $maps['a.status'] = array('eq',1);
          $maps['a.is_delete'] = array('eq',0);
          $list[] =  $this->alias('a')
                     ->field($field)
                     ->join('left join __USERS__ b on a.from_id=b.id')
                     ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
                    //  ->where($where)
                     ->where($maps)
                     ->order('a.newstime desc')
                     ->page($p3.','.$size)
                     ->select();
         $count3 =  $this->alias('a')
                     ->field($field)
                     ->join('left join __USERS__ b on a.from_id=b.id')
                     ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
                    //  ->where($where)
                     ->where($maps)
                     ->order('a.newstime desc')
                     ->count();
            $i=3; //设置url的tabType=3
           $show3 = tabPage($count3,$size,'p3',$i);
      //  }
       
      //  $status = array(0,1); //消息读取状态0表示未读，1表示已读
      //   foreach($status as $v){
      //     $where = array(
      //    'a.user_id'=>$user_id,
      //    'a.status'=>$v ,
      //    'a.is_delete'=>0
      //      );
      //     $list[] =  $this->alias('a')
      //                ->field($field)
      //                ->join('left join __USERS__ b on a.from_id=b.id')
      //                ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
      //                ->where($where)
      //                ->order('a.newstime desc')
      //                ->select();

      //   }              
       array_push($list,$condition1); //把搜索框值塞入$list数组
       array_push($list,$condition2);
       array_push($list,$condition3);
       array_push($list,$show1);
       array_push($list,$show2);
       array_push($list,$show3);
      //  print_r($list);
       return $list; 

          }

    /**
    *消息单个删除
    */
     public function deleteOne($user_id){
       if (IS_GET) {
       $mid = I('id');
       $where = array(
         'user_id'=>$user_id,
         'id'=>$mid,
         'is_delete'=>1 
       );
       return $res = $this->save($where);
      
     }
     }

    /**
    *消息批量删除
    */
     public function batchDelete($user_id){
     if (IS_POST) {
      //  print_r(I('post.checkbox')); exit;
               foreach (I('id') as $k => $v) {
               $where = array(
                'user_id'=>$user_id,
                'id'=>$v,
                'is_delete'=>1 
               ); 
               $res = $this->save($where);
            }
             return $res;
        }
           
     }

   /**
    *消息查看内容后变为已读
    */
     public function messageRead($user_id){  
         if (IS_AJAX) { 
         $mid = I('id');
         $where = array(
         'user_id'=>$user_id,
         'id'=>$mid,
         'status'=>1 
       );


       return $res = $this->save($where);
       
      }

     }
  
   /**
    *消息查看内容详情
    */
 	public function messageDetail($user_id){ 
        if (IS_AJAX) { 
	        $mid = I('id');
	        $where = array(
		        'a.user_id'=>$user_id,
		        'a.id'=>$mid,
	       	);
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$field = "to_char(a.contents_time,'YYYY-MM-DD HH24:MI:SS') as contents_time";
			}else{
				$field = "a.contents_time";
			}
	       	return $res = $this->alias('a')
		        ->field('a.id,a.title,c.type_name,c.cat_detail,'.$field)
		        ->join('left join __USERS__ b on a.from_id=b.id')
		        ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
		        ->where($where)
		        ->order('a.newstime desc')
		        ->find();
       }
    }


   /**
    *消息查看内容详情--话题小组
    */
     public function topicmessageDetail($user_id){ 
        if (IS_AJAX) { 
         $mid = I('id');
         $where = array(
         'a.user_id'=>$user_id,
         'a.id'=>$mid,
       );
	   	if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "to_char(a.contents_time,'YYYY-MM-DD HH24:MI:SS') as contents_time";
		}else{
			$field = "a.contents_time";
		}
        $data = $this->alias('a')
            ->field('a.id,a.title,a.user_id,a.from_id,c.type_name,c.cat_detail,d.type,d.correlate_id,e.theme,'.$field)
            ->join('left join __USERS__ b on a.from_id=b.id')
            ->join('left join __ADMIN_MESSAGE_TYPE__ c on a.type_id=c.id')
            ->join('left join __ADMIN_MESSAGE_TOPIC__ d on a.id=d.message_id')
            ->join('left join __TOPIC_GROUP__ e on e.id=d.correlate_id')
            ->where($where)
            ->order('a.newstime desc')
            ->find();  
         
        if($data['type'] == 0){
           $data['topicmessagetitle'] = '你有一个小组邀请，是否加入？';
           $data['topicuser_id'] = $data['user_id'];
        }else{
           $apply_username = M('users')->where(array('id'=>$data['from_id']))->getField('username');
           $data['topicmessagetitle'] = $apply_username.' 申请加入 '.$data['title'];
           $data['topicuser_id'] = $data['from_id'];
        }
        //判断think_topic_personnel表的status的状态是否变更
         $condition = array(
           'topic_group_id'=>$data['correlate_id'],
           'user_id'=> $data['topicuser_id'],
           'status'=>0
         );
         $exist = M('topic_personnel')->where($condition)->find();
         if($exist){
           $data['showStatus'] = 1;
         }else{
           $data['showStatus'] = 0;
         }

        return $data;
       }
     }

   /**
    *话题小组－确认加入/取消加入
    */
    public function topicaudit(){ 
       if (IS_AJAX) { 
         $topicuser_id = I('topicuser_id');
         $correlate_id = I('correlate_id');
         $status = I('status');
         $where = array(
           'user_id'=>$topicuser_id,
           'topic_group_id'=>$correlate_id,
           
         );
         $data = array(
           'status'=>$status,
           'add_time'=>date('Y-m-d H:i:s')
           );
         $res = M('topic_personnel')->where($where)->save($data);
         if($res){
           if($status == 1){
            $info = '同意加入该小组';
           }else if($status == 2){
            $info = '拒绝加入该小组';
           }
         }else{
           $this->error = '系统错误';
           return false;
         }
       
        return $info;
       }



    }



}