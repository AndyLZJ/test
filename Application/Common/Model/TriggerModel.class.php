<?php
namespace Common\Model;
use Think\Model;
/**
 * 触发器公共模型
 */
class TriggerModel extends Model{
     protected $tableName = 'users';

    /**
     *  积分事件触发公共模型
     */
    public function intergrationTrigger($user_id,$i){
        
        //  $user_id =  $_SESSION['user']['id'];        
       
         $data = $this->rule($i);
        
         $arr = array(
             'time'=>time(),
             'user_id'=>$user_id,
             'score'=>$data['score'],
             'type'=>$data['type'],
             'describe'=>$data['name'],
         );
         $res = $this->intergrationrule($user_id,$i);
		
        // 当天或当月所得积分未封顶则插入记录
         if($res){
         	
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$arr['id'] = getNextId('integration_record');
			}
             $ret = M('integration_record')->add($arr);
             if($ret){
                 $info = $data['name'].','.'恭喜你获得'.$data['score'].'积分！';
                 return $info;
             }else{
                 $info = '数据库发生错误！';
                 return $info;
             }
         }else{
           $info = '\''.$data['name'].'\''.'规则'.'已达到封顶积分！';
           return $info;  
         }
       

    }



    /**
     *  积分事件触发的封顶限制
     */
    public function intergrationrule($user_id,$i){
        
        $data = $this->rule($i);
        

        if($i == 3 || $i == 4){

        if(C('DB_TYPE') == "mysqli") {
            $where = array(
              'user_id'=>$user_id,
              'describe'=>$data['name'],
              '_logic'=>'and'
            );
            //获取当月的登录系统的积分记录之和
            $months = date("Ym");
            $this_month_score = M('integration_record')
                              ->field("user_id,sum(score) as sumscore,FROM_UNIXTIME(time,'%Y%m') months")
                              ->where($where)->group('months')
                              ->having('months='.$months)
                                ->select();
            $this_month_score = $this_month_score[0]['sumscore'];
        }else {
          //oracle-获取当月的登录系统的积分记录之和
           $months_start_time = strtotime(date("Y-m-1 00:00:00")); //本月第一天时间戳
           $months_end_time = strtotime(date("Y-m-t 23:59:59")); //本月第一天时间戳
           $where['time'] = array('between',array($months_start_time,$months_end_time));
           $where['user_id'] = $user_id;

           $this_month_score = M('integration_record')->where($where)->sum('score');

        }

        $this_month_score = $this_month_score<0 || $this_month_score =='' ? 0 : $this_month_score;
        //获取登录系统的积分规则-封顶积分-当月
        $month_score = explode('/',$data['oneday_score']);
        $month_score = $month_score[0];

            if($this_month_score < $month_score){
                return true;
            }else{
                return false;
            }
        }


        if(C('DB_TYPE') == "mysqli") {

        //获取当天的登录系统的积分记录之和
        $where = array(
              'user_id'=>$user_id,
              'describe'=>$data['name'],
              '_logic'=>'and'
            );
        $day = date("Ymd");
        $this_day_score = M('integration_record')
                          ->field("user_id,sum(score) as sumscore,FROM_UNIXTIME(time,'%Y%m%d') months")
                          ->where($where)
                          ->group('months')
                          ->having('months='.$day)
                          ->select();
        $this_day_score = $this_day_score[0]['sumscore'];

        }else{
          //oracle-获取当天的登录系统的积分记录之和
           $day_start_time = strtotime(date("Y-m-d 00:00:00")); //本月第一天时间戳
           $day_end_time = strtotime(date("Y-m-d 23:59:59")); //本月第一天时间戳
           $where['time'] = array('between',array($months_start_time,$months_end_time));
           $where['user_id'] = $user_id;
           $this_month_score = M('integration_record')->where($where)->sum('score');
        }
        
        $this_day_score = $this_day_score<0 || $this_day_score =='' ? 0 : $this_day_score;
        //获取登录系统的积分规则-封顶积分-当天
        $oneday_score = $data['oneday_score']==0 || $data['oneday_score']=='' ? 99999999 : $data['oneday_score'];
       
            if($this_day_score < $oneday_score){
                return true;
            }else{
                return false;
            }
       
    }



    /**
    *  积分事件触发的rules
    */
     public function rule($i){
         $data = M('integration_rule')->where(array('id'=>$i))->find();  
         return $data;
    }



    /**
     *  消息通知触发公共模型
     */
    public function messageTrigger($user_id,$title,$contents_time,$type_id,$from_id,$url){
         $data = array(
             'user_id'=>$user_id,
             'title'=>$title,
             'contents_time'=>$contents_time,
             'newstime'=>date('Y-m-d H:i:s'),
             'type_id'=>$type_id,
             'from_id'=>$from_id,
             'status'=>0,
             'is_delete'=>0,
             'url'=>$url
         );

    if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('admin_message');
			$data['newstime'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			$data['contents_time'] = array('exp',"to_date('".$contents_time."','yy-mm-dd hh24:mi:ss')");
		}else{
			$data['newstime'] = date('Y-m-d H:i:s');
			$data['contents_time'] = $contents_time;
		}
        $res = M('admin_message')->add($data);
        // ECHO M('admin_message')->getLastSql();die;
        if($type_id == 10){//只发课程学习邮件
        //user_id即为收信人，根据user_id查找email
        $address = M('users')->where(array('id'=>$user_id))->getField('email');

        if($res){
          
           if($address){
            
            $this->sys_send_email($user_id,$res,$type_id,$title,$contents_time,$url,$address);
           }
        }          
      }
       return $res;
      
    }




    /**
     *  消息通知详情"点击前往"跳转公共模型
     */
    public function messageEntryTrigger($i){
       
       $data = M('admin_message')->where(array('id'=>$i))->find();
       $this->redirect($data['url']);

    }


   /**
    *邮箱推送
    */
    public function sys_send_email($user_id,$message_id,$type_id,$title,$contents_time,$url,$address){
      
      //根据$type_id联表think_admin_message_type查找emailtitle
       $emailtitle = M('admin_message_type')
                    ->where(array('id'=>$type_id))
                    ->getField('cat_detail');

      //消息跳转url
       $os = $this->os();
       if($os == "Linux"){
         $url = U($url,$vars='',$suffix=true,$domain=true);
         $url = str_replace('/index.php','',$url);
       }else{
         $url = U($url,$vars='',$suffix=true,$domain=true);
       }
       

  
       $emailcontent = "名称：".$title
                     ."<br />"
                     ."时间：".$contents_time
                     ."<br /><br /><br />"
                     ."点击前往：".$url
                     ."<br /><br />"
                     ."此邮件由培训平台系统自动发出,请勿回复！";

        $res = send_email($address,$emailtitle,$emailcontent);
        if($res['error'] == 0){
          $retinfo = "发送成功";
        }else{
          $retinfo =  "发送失败";
          // dump($res);
        }
        return $retinfo; 
    }



    /**    
     *    工单号公共模型
     *    $i参数1-9分别代表：（1）培训项目审核；（2）新建课程审核；（3）新建试卷审核；（4）新建问卷审核；
     *   （5）新建互动审核；（6）发起调研审核；（7）发起考试审核；（8）发起加分审核；（9）用户注册审核；
     */
    public function orderNumber($i){
        switch($i)
        {
              case 1:
                $no =  '01'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 2:
                $no =  '02'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 3:
                $no =  '03'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 4:
                $no =  '04'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 5:
                $no =  '05'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 6:
                $no =  '06'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 7:
                $no =  '07'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 8:
                $no =  '08'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
              case 9:
                $no =  '09'.substr(time(),6).rand(1000,9999);
                return $no;
                break;
             
              default:
                $no =  $i.substr(time(),6).rand(1000,9999);
                return $no;
        }
 
    }


    /** 
     *审核设置的数据
     */
     public function AuditSetData($type)
     {  
        $map = array(
          'a.type'=>$type
        );
        $dataSet = M('audit_rule')->alias('a')
                ->field('a.*,b.name,conditiona,conditionb')
                ->join('left join __AUDIT_CONDITION__ as b on b.id = a.condition_id')
                ->where($map)
                ->find();

        return $dataSet;

     }
     
     /** 
      *项目重新（即将表数据的状态变为待审核）提交接口
      * @param $id 项目id
      * @param $type 类型(1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册) 
      * @return $res, 返回结果 bool
      *调用方法 实例：$res = D('Trigger')->projectResubmit($id,1);
      */
     public function projectResubmit($id,$type=1)
     {
       $exist = M('audit')->where(array('type'=>$type,'correlate_id'=>$id))->find();
       //读取当前审核配置表的配置
       $dataSet = $this->AuditSetData($type);
       // dump($dataSet); exit;
       if($exist){
           //变更该条数据值
            $auditData = array();
            $auditData = array(
                    'type'=> $type,
                    'correlate_id'=> $id,
                    'levalone_man'=> 0,
                    'levaltwo_man'=> 0,
                    'levalthree_man'=> 0,
                    'audit_status'=> 0,
                    'objection'=>'',
                    'oneaudit_role'=> $dataSet['oneaudit_role'],
                    'twoaudit_role'=> $dataSet['twoaudit_role'],
                    'threeaudit_role'=> $dataSet['threeaudit_role'],
                    'is_condition'=> $dataSet['is_condition'],
                    'condition_id'=> $dataSet['condition_id'],
                    'conditiona'=> $dataSet['conditiona'],
                    'conditionb'=> $dataSet['conditionb'],
                    'num'=> $dataSet['num']      
              );
              $res =  M('audit')->where(array('type'=>$type,'correlate_id'=>$id))->save($auditData);
              
       }else{
            
            $auditData = array();
            $auditData = array(
                    'type'=> $type,
                    'correlate_id'=> $id,
                    'oneaudit_role'=> $dataSet['oneaudit_role'],
                    'twoaudit_role'=> $dataSet['twoaudit_role'],
                    'threeaudit_role'=> $dataSet['threeaudit_role'],
                    'is_condition'=> $dataSet['is_condition'],
                    'condition_id'=> $dataSet['condition_id'],
                    'conditiona'=> $dataSet['conditiona'],
                    'conditionb'=> $dataSet['conditionb'],
                    'num'=> $dataSet['num']
                    
              );
			  	if(strtolower(C('DB_TYPE')) == 'oracle'){
					$auditData['id'] = getNextId('audit');
				}
              $res =  M('audit')->add($auditData);


       }
        return $res;
       
     }




   /***
    *扫码考勤接口
        MOBILE接口调用实例,参数：$project_id,$course_id,$user_id
        public function  test1(){
		 $data =  D('Trigger')->qrcodeAttendance(157,85,312);
        }
    */

   public function  qrcodeAttendance($project_id,$course_id,$user_id){
       $map = array(
         'pid'=>$project_id,
         'user_id'=>$user_id,
         'course_id'=>$course_id,
       );
       $where = array(
         'project_id'=>$project_id,
         'course_id'=>$course_id,
       );
      $res = M('attendance')->where($map)->find();
      // echo 11;exit;
      if(!$res){
         $data = array(
          "code" => 1023,
          "message" => "用户不在考勤范围!",
         );
         return $data;
      }
      
      if($res['mobile_scanning'] == 1 || $res['status'] != ''){
         $data = array(
          "code" => 1024,
          "message" => "你已考勤，请勿重复考勤!",
         );
         return $data;
      }

      
      $res = M('project_course')->where($where)->find();
      if(time() <= strtotime($res['start_time'])){
         $svaeStatus = array('status'=>1,'mobile_scanning'=>1,'attendance_time'=>date('Y-m-d H:i:s'));
         $res = M('attendance')->where($map)->save($svaeStatus);
         $data = array(
          "code" => 1000,
          "message" => "考勤-按时",
         );
         return $data;
      }else if(strtotime($res['end_time']) > time() && time() > strtotime($res['start_time'])){
         $svaeStatus = array('status'=>2,'mobile_scanning'=>1,'attendance_time'=>date('Y-m-d H:i:s'));
         $res = M('attendance')->where($map)->save($svaeStatus);
          $data = array(
          "code" => 1025,
          "message" => "考勤-迟到",
         );
         return $data;
      }else if(strtotime($res['end_time']) < time()){
         $svaeStatus = array('status'=>0,'mobile_scanning'=>1,'attendance_time'=>date('Y-m-d H:i:s'));
         $res = M('attendance')->where($map)->save($svaeStatus);
          $data = array(
          "code" => 1026,
          "message" => "考勤-缺勤",
         );
         return $data;
      }
      
         
   }


   /***
    *检测当前的操作系统  Windows/Linux
    */

   public function os(){
        $os_name=php_uname();
        if(strpos($os_name,"Linux")!==false){
            $os_str="Linux";
        }else if(strpos($os_name,"Windows")!==false){
            $os_str="Windows";
        }
        return  $os_str;
         
   }



   /***
    *部落系统消息触发  $type  1加入小组 2退出小组 3设置管理员 4创建话题
    *调用： $res = D('Trigger')->sendTopicMessage($user_id,$content,$topic_group_id=0,$topic_id=0,$audit_user_id=0,$type);
    */

   public function sendTopicMessage($user_id,$content,$topic_group_id=0,$topic_id=0,$audit_user_id=0,$type){
        $data = array(
          'user_id'=>$user_id,
          'topic_group_id'=>$topic_group_id,
          'topic_id'=>$topic_id,
          'audit_user_id'=>$audit_user_id,
          'time'=>date('Y-m-d H:i:s'),
          'type'=>$type
        );
        if($type == 1){
           $data['content'] = "加入部落，审核人：";
        }else if($type == 2){
           $data['content'] = "退出部落";
        }else if($type == 3){
           $data['content'] = "被创建者设置为管理员";
        }else if($type == 4){
           $groupName = M('topic_group')->where(array('id'=>$topic_group_id))->getField('name');
           $topicName = M('group_topic')->where(array('id'=>$topic_id))->getField('name');
            
           $data['content'] = '在 '.$groupName.'部落 中创建话题 "'.$topicName.'"';
        }else{
           $data['content'] = $content;
        }

        $res = M('topic_message')->add($data);
        return $res;
   }







}