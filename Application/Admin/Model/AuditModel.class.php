<?php 

namespace Admin\Model;
/**
 * 审核管理模型
 */
use Think\Model;
/**
 * 审核管理模型
 */
class AuditModel extends Model{
       protected $tableName= 'course';





     /** 
     *审核设置的条件选择列表,根据类型查找相应的字段
     */
     public function conditionOption($type)
     {
       $res = M('audit_condition')->where(array('type'=>$type))->select();
       return $res;
     }

    /** 
     *审核设置列表
     */
     public function setList()
     {
       $data = M('audit_rule')->select();
       
       $setdata = array(
          1=>'培训项目',
          2=>'新建课程',
          3=>'新建试卷',
          4=>'新建问卷',
          5=>'新建互动',
          6=>'发起调研',
          7=>'发起考试',
          8=>'发起加分',
          9=>'用户注册',
          10=>'业务部落'
       );

       foreach($data as $k=>$v){
           $key = $k+1;
           $data[$k]['name'] = $setdata[$key];
       }
     return $data;
       
     }

     /** 
     *根据user_id获取用户名
     */
   public function uname($user_id){
      return M('users')->where(array('id'=>$user_id))->getField('username');
   }
 
     /** 
     *审核的设置
     */
     public function setDetail($type)
     { 
        
        $data = M('audit_rule')->alias('a')
                       ->join('left join __AUDIT_CONDITION__ b on b.id=a.condition_id')
                       ->where(array('a.type'=>$type))
                       ->field('a.*,b.name,b.conditiona,b.conditionb')
                       ->find();

        $data['oneaudit_username'] = $this->uname($data['oneaudit_user_id']);
        $data['twoaudit_username'] = $this->uname($data['twoaudit_user_id']);
        $data['threeaudit_username'] = $this->uname($data['threeaudit_user_id']);
            //  echo M('audit_rule')->_sql();
        return $data;
     }
     /** 
     *审核设置的保存
     */
     public function saveSet()
     {
        
       $data = I('post.');
      //  dump($data);exit;  
       $oneaudit_role = $data['oneaudit_role'];
       $twoaudit_role = $data['twoaudit_role'];
       $threeaudit_role = $data['threeaudit_role'];

       $one_level_type = $data['one_level_type'];
       $two_level_type = $data['two_level_type'];
       $three_level_type = $data['three_level_type'];
         
       //取得审核轮数
       $num = $this->auditNum($one_level_type,$two_level_type,$three_level_type);
       $data['num'] = $num; 
 		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('audit_rule');
		}

       if($data['is_condition'] == 0){ //无条件
         $data['is_condition'] = 0;
         $data['condition_id'] = 0;
         
         $exist = M('audit_rule')->where(array('type'=>$data['type']))->find();

         if($exist){
           
           $res = M('audit_rule')->where(array('type'=>$data['type']))->save($data);

         }else{
           if($data['type'] == ''){
             $res =  false;
            }
           $res = M('audit_rule')->add($data);
         }
         if($res === false){
  
             $this->error = '修改失败！';
             return false;
           }
         return true;
       }else{  //有条件
           //把条件值修改
           $condition['conditiona'] = $data['conditiona'];
           $condition['conditionb'] = $data['conditionb'];
          
           $res = M('audit_condition')->where(array('id'=>$data['condition_id']))->save($condition);
          //  echo M('audit_condition')->_sql();
           if($res === false){
            
             $this->error = '修改失败！';
             return false;
           }
             
           unset($data['conditiona']);
           unset($data['conditionb']);
           $exist = M('audit_rule')->where(array('type'=>$data['type']))->find();
           
         if($exist){
           
           $res = M('audit_rule')->where(array('type'=>$data['type']))->save($data);
           
         }else{
           $res = M('audit_rule')->add($data);
         }

         if($res === false){
         
             $this->error = '修改失败！';
             return false;
           }
         
          return true;
       }
       
      
     }

     /** 
     *审核级数-old
     */
     public function auditNum_bak($oneaudit_role,$twoaudit_role,$threeaudit_role)
     {
       if($twoaudit_role == '' && $threeaudit_role == ''){
          $num = 1;
       }else if($threeaudit_role == ''){
          $num = 2;
       }else{
          $num = 3;
       }
         return $num; 

     }

     /** 
     *审核级数
     */
     public function auditNum($one_level_type,$two_level_type,$three_level_type)
     {
       if($two_level_type == 0 && $three_level_type == 0){
          $num = 1;
       }else if($three_level_type == 0){
          $num = 2;
       }else{
          $num = 3;
       }
         return $num; 

     }



    /** 
     *三期批量审核时写入审核人
     */
     public function threeAuditMan($id,$auditstatus)
     { 
       if($auditstatus == 0){
            M('audit')->where(array('id'=>$id))->save(array('levalone_man'=>$_SESSION['user']['id']));
       }else if($auditstatus == 1){
            M('audit')->where(array('id'=>$id))->save(array('levaltwo_man'=>$_SESSION['user']['id']));
       }else if($auditstatus == 2){
            M('audit')->where(array('id'=>$id))->save(array('levalthree_man'=>$_SESSION['user']['id']));
       }
       
     }


    /** 
     *判断是否已经审核过，若审核过则提示“该条数据已审核，请勿重复审核”
     *@param array $ids
     *@param array $audit_status
     *@return bool 
     */
     public function repetitiveAudit($ids,$audit_statuses)
     { 
         if(count($ids) == 1){
           $exist  = M('audit')->where(array('id'=>$ids[0],'audit_status'=>$audit_statuses[0]))->find();
           if(!$exist){
             $ret = array(
                     'status'=>0,
                     'info'=>'该条数据已审核，请勿重复审核',
					           'url'=>''
                      );
             return $ret;
           }
         }else{
            foreach($ids as $k=>$v){
               $exist  = M('audit')->where(array('id'=>$v,'audit_status'=>$audit_statuses[$k]))->find();
               if(!$exist){
                $ret = array(
                        'status'=>0,
                        'info'=>'批量审核中包含已审核数据，请勿重复审核',
                        'url'=>''
                          );
                return $ret;
             }
            }
         }
               $ret = array('status'=>1);
               return $ret;
     }


    /** 
     *三期批量审核
     */
     public function threebatchAudit()
     { 
         $ids = I('post.ids'); //接受的$ids为审核表的ids
        //  dump($ids);die;
         
         $audit_statuses = I('post.audit_statuses');  //接收页面当时该条数据的审核状态
         $type = I('post.type'); 
         $auditstyle =I('post.auditstyle'); //用来区别“pass”，“denied”审核请求
         $objection = I('post.objection');  //拒绝理由

         //判断是否存在重复审核
         $res = $this->repetitiveAudit($ids,$audit_statuses);
         if($res['status'] == 0){
           return $res;
         }
		//  print_r($tablenames);
		//   print_r($ids); exit;
         if($auditstyle == 'denied'){ //批量拒绝部分
            if($type == 1){ //项目审核-拒绝
             foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>3,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
               
               
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/projectauditlist')
                      );
                return $ret;
            }else if($type == 2){ //课程审核-拒绝
               foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/courseauditlist')
                      );
                return $ret;  
            }else if($type == 3){ //新建试卷审核-拒绝
                foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>3,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/examinationauditlist')
                      );
                return $ret;  
            }else if($type == 4){ //新建问卷审核-拒绝
               foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>3,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/questionauditlist')
                      );
                return $ret; 
            }else if($type == 5){ //新建互动审核-拒绝
               foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/topicauditlist')
                      );
                return $ret; 
            }else if($type == 6){ //发起调研审核-拒绝
               foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>2,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/researchauditlist')
                      );
                return $ret; 
            }else if($type == 7){ //发起考试审核-拒绝
               foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>2,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/testauditlist')
                      );
                return $ret; 
            }else if($type == 8){ //加分申请审核-拒绝
               foreach($ids as $k=>$v){
                $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/applyauditlist')
                      );
                return $ret; 
            }else if($type == 9){ //用户注册审核-拒绝
               foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>0,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/usersauditlist')
                      );
                return $ret; 
            }else if($type == 10){ //话题小组审核-拒绝
               foreach($ids as $k=>$v){
               $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('topic_group')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/topicgrouplist')
                      );
                return $ret; 
            }else if($type == 11){ //加学分申请审核-拒绝
               foreach($ids as $k=>$v){
                $dataone = M('audit')->where(array('id'=>$v))->find();
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>4));
                 
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>5)); 
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>6)); 
               }
                $this->threeAuditMan($v,$dataone['audit_status']);
                $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>2,'objection'=>$objection));
                $res = M('audit')->where(array('id'=>$v))->save(array('objection'=>$objection));
             }
  
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核拒绝成功',
					           'url'=>U('Admin/Audit/creditsapplyauditlist')
                      );
                return $ret; 
            }

         }else if($auditstyle == 'pass'){ //批量通过部分
            if($type == 1){ //项目审核-通过
             foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
               }

               }else{ //有条件
        
                  $tianjian = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->find();
                  $project_time = $this->diffBetweenTwoDays($tianjian['start_time'],$tianjian['end_time']);
                 //项目指定人员（人数）
                 $designeeNum = M('admin_project')->alias('a')
                              ->join('left join __DESIGNATED_PERSONNEL__ b on b.project_id=a.id')
                              ->where(array('a.id'=>$dataone['correlate_id']))
                              ->count();
                 
                  if($dataone['audit_status'] == 0){
                      $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                    if($dataone['num'] == 1){
                      $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                    }else{
                      if($dataone['condition_id'] == 1){
                         //预留项目时长
                         if($project_time < $dataone['conditiona']){
                         $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0)); 
                        }
                     }else if($dataone['condition_id'] == 2){
                        if($tianjian['project_budget'] < $dataone['conditiona']){
                         $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0)); 
                        }  
                     }else if($dataone['condition_id'] == 3){
                        if($designeeNum < $dataone['conditiona']){
                          $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0)); 
                        }
                     }
                    }

                    
                  }else if($dataone['audit_status'] == 1){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                    if($dataone['num'] == 2){
                      $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                    }else{
                      if($dataone['condition_id'] == 1){
                         //预留项目时长
                        if($project_time < $dataone['conditiona']){
                         $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0)); 
                        }
                     }else if($dataone['condition_id'] == 2){
                        if($tianjian['project_budget'] < $dataone['conditionb']){
                         $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0)); 
                        }  
                     }else if($dataone['condition_id'] == 3){
                        if($designeeNum < $dataone['conditionb']){
                          $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0)); 
                        }
                     }
                    }
                  }else if($dataone['audit_status'] == 2){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                    $res = M('admin_project')->where(array('id'=>$dataone['correlate_id']))->save(array('type'=>0));
                  }
               }
                   $this->threeAuditMan($v,$dataone['audit_status']);
             }
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/projectauditlist')
                      );
             return $ret;
            }else if($type == 2){ //新建课程审核-通过
              foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
               }

               }else{ //有条件
        
                  $tianjian = M('course')->where(array('id'=>$dataone['correlate_id']))->find();

                  if($dataone['audit_status'] == 0){
                      $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                    if($dataone['num'] == 1){
                      $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                    }else{
                      if($dataone['condition_id'] == 4){
                         //授课时长
                        if($tianjian['course_time'] < $dataone['conditiona']){
                         $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                        }  
                     }else if($dataone['condition_id'] == 5){
                        if($tianjian['credit'] < $dataone['conditiona']){
                          $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1)); 
                        }
                    
                    }

                    }
                  }else if($dataone['audit_status'] == 1){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                    if($dataone['num'] == 2){
                      $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1)); 
                    }else{
                      if($dataone['condition_id'] == 4){
                        if($tianjian['course_time'] < $dataone['conditionb']){
                         $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1)); 
                        }  
                     }else if($dataone['condition_id'] == 5){
                        if($tianjian['credit'] < $dataone['conditionb']){
                          $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1)); 
                        }
                     }
                    }
                  }else if($dataone['audit_status'] == 2){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                    $res = M('course')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1)); 
                  }
               }
                   $this->threeAuditMan($v,$dataone['audit_status']);
             }
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/courseauditlist')
                      );
             return $ret;
            }else if($type == 3){ //新建试卷审核-通过
               foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('examination')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
               }

               }
              $this->threeAuditMan($v,$dataone['audit_status']);
             }
              $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/examinationauditlist')
                      );
              return $ret;
            }else if($type == 4){ //新建问卷审核-通过
                foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('survey')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
               }

               }
              $this->threeAuditMan($v,$dataone['audit_status']);
             }
              $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/questionauditlist')
                      );
              return $ret;             
            }else if($type == 5){ //新建互动审核-通过
                foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('friends_circle')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
               }

               }
               $this->threeAuditMan($v,$dataone['audit_status']);
             }
              $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/topicauditlist')
                      );
              return $ret;
            }else if($type == 6){ //发起调研审核-通过
               foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
               }

               }else{ //有条件
        
                  $tianjian = M('research')->where(array('id'=>$dataone['correlate_id']))->find();

                  if($dataone['audit_status'] == 0){
                      $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                    if($dataone['num'] == 1){
                      $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                    }else{
                      if($dataone['condition_id'] == 6){
                         //调研时长
                         $survey_time = $this->diffBetweenTwoDays($tianjian['start_time'], $tianjian['end_time']);
                        if($survey_time < $dataone['conditiona']){
                         $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1));
                        }  
                     }else if($dataone['condition_id'] == 7){
                        if($tianjian['credits'] < $dataone['conditiona']){
                          $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1)); 
                        }
                    
                    }

                    }
                  }else if($dataone['audit_status'] == 1){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                    if($dataone['num'] == 2){
                      $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1)); 
                    }else{
                      if($dataone['condition_id'] == 6){
                        //调研时长
                         $survey_time = $this->diffBetweenTwoDays($tianjian['start_time'], $tianjian['end_time']);
                        if($survey_time < $dataone['conditionb']){
                         $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1)); 
                        }  
                     }else if($dataone['condition_id'] == 7){
                        if($tianjian['credits'] < $dataone['conditionb']){
                          $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1)); 
                        }
                     }
                    }
                  }else if($dataone['audit_status'] == 2){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                    $res = M('research')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_state'=>1)); 
                  }
               }
                   $this->threeAuditMan($v,$dataone['audit_status']);
             }
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/researchauditlist')
                      );
             return $ret;
            }else if($type == 7){ //发起考试审核-通过
               foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
               }

               }else{ //有条件
        
                  $tianjian = M('test')->where(array('id'=>$dataone['correlate_id']))->find();
                  $test_time = $this->diffBetweenTwoMinutes($tianjian['start_time'], $tianjian['end_time']);
                  $testerNum = M('test')->alias('a')
                              ->join('left join __TEST_USER_REL__ b on b.test_id=a.id')
                              ->where(array('a.id'=>$dataone['correlate_id']))
                              ->count();

                  if($dataone['audit_status'] == 0){
                      $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                    if($dataone['num'] == 1){
                      $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                    }else{
                      if($dataone['condition_id'] == 8){
                         //考试时长（分钟）
                         
                        if($test_time < $dataone['conditiona']){
                         $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                        }  
                     }else if($dataone['condition_id'] == 9){ //学分（分）
                        if($tianjian['score'] < $dataone['conditiona']){
                          $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0)); 
                        }
                    
                     }else if($dataone['condition_id'] == 10){ //指定人员（人数）
                        if($testerNum < $dataone['conditiona']){
                          $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0)); 
                        }
                    
                     }

                    }
                  }else if($dataone['audit_status'] == 1){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                    if($dataone['num'] == 2){
                     $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                    }else{
                      if($dataone['condition_id'] == 8){
                        //调研时长
                        if($test_time < $dataone['conditionb']){
                         $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                        }  
                     }else if($dataone['condition_id'] == 9){
                        if($tianjian['score'] < $dataone['conditionb']){
                         $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                        }
                     }else if($dataone['condition_id'] == 10){
                        if($testerNum < $dataone['conditionb']){
                         $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0));
                        }
                     }
                    }
                  }else if($dataone['audit_status'] == 2){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                    $res = M('test')->where(array('id'=>$dataone['correlate_id']))->save(array('audit_status'=>0)); 
                  }
               }
                   $this->threeAuditMan($v,$dataone['audit_status']);
             }
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/testauditlist')
                      );
             return $ret;
            }else if($type == 8){ //加分申请审核-通过
              foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                
                if($dataone['num'] == 1){
                  $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                  $rest = $this->applyIntegration($dataone['correlate_id']);
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                  $rest = $this->applyIntegration($dataone['correlate_id']);
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                $rest = $this->applyIntegration($dataone['correlate_id']);
               }

               }else{ //有条件
        
                  $tianjian = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->find();
 
                  if($dataone['audit_status'] == 0){
                      $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                    if($dataone['num'] == 1){
                      $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                      $rest = $this->applyIntegration($dataone['correlate_id']);
                    }else{
                      if($dataone['condition_id'] == 11){
                         //积分分值 
                        if($tianjian['add_score'] < $dataone['conditiona']){
                         $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                         $rest = $this->applyIntegration($dataone['correlate_id']);
                        }  
                      }
                     } 
                  }else if($dataone['audit_status'] == 1){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                    if($dataone['num'] == 2){
                     $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                     $rest = $this->applyIntegration($dataone['correlate_id']);
                    }else{
                      if($dataone['condition_id'] == 11){
                        //调研时长
                        if($tianjian['add_score'] < $dataone['conditionb']){
                         $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                         $rest = $this->applyIntegration($dataone['correlate_id']);
                        }  
                     }
                    }
                  }else if($dataone['audit_status'] == 2){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                    $res = M('integration_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                    $rest = $this->applyIntegration($dataone['correlate_id']);
                  }
               }
                   $this->threeAuditMan($v,$dataone['audit_status']);
             }
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/applyauditlist')
                      );
             return $ret;
            }else if($type == 9){ //用户注册审核-通过
              foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('users')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
               }

               }
               $this->threeAuditMan($v,$dataone['audit_status']);
             }
              $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/usersauditlist')
                      );
             return $ret;
            }else if($type == 10){ //话题小组审核-通过
              foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                if($dataone['num'] == 1){
                  $res = M('topic_group')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('topic_group')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('topic_group')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
               }

               }
               $this->threeAuditMan($v,$dataone['audit_status']);
             }
              $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/topicgrouplist')
                      );
             return $ret;
            }else if($type == 11){ //加学分申请审核-通过
              foreach($ids as $k=>$v){
               
               $dataone = M('audit')->where(array('id'=>$v))->find();
               if($dataone['is_condition'] == 0){ //无条件
                 if($dataone['audit_status'] == 0){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                
                if($dataone['num'] == 1){
                  $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                  $rest = $this->applyCredits($dataone['correlate_id']);
                }
               }else if($dataone['audit_status'] == 1){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                if($dataone['num'] == 2){
                  $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                  $rest = $this->applyCredits($dataone['correlate_id']);
                }
               }else if($dataone['audit_status'] == 2){
                $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                $rest = $this->applyCredits($dataone['correlate_id']);
               }

               }else{ //有条件
        
                  $tianjian = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->find();
 
                  if($dataone['audit_status'] == 0){
                      $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>1));
                    if($dataone['num'] == 1){
                      $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                      $rest = $this->applyCredits($dataone['correlate_id']);
                    }else{
                      if($dataone['condition_id'] == 11){
                         //积分分值 
                        if($tianjian['add_score'] < $dataone['conditiona']){
                         $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                         $rest = $this->applyCredits($dataone['correlate_id']);
                        }  
                      }
                     } 
                  }else if($dataone['audit_status'] == 1){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>2)); 
                    if($dataone['num'] == 2){
                     $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                     $rest = $this->applyCredits($dataone['correlate_id']);
                    }else{
                      if($dataone['condition_id'] == 11){
                        //调研时长
                        if($tianjian['add_score'] < $dataone['conditionb']){
                         $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                         $rest = $this->applyCredits($dataone['correlate_id']);
                        }  
                     }
                    }
                  }else if($dataone['audit_status'] == 2){
                    $res = M('audit')->where(array('id'=>$v))->save(array('audit_status'=>3)); 
                    $res = M('credits_apply')->where(array('id'=>$dataone['correlate_id']))->save(array('status'=>1));
                    $rest = $this->applyCredits($dataone['correlate_id']);
                  }
               }
                   $this->threeAuditMan($v,$dataone['audit_status']);
             }
               $ret = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					           'url'=>U('Admin/Audit/creditsapplyauditlist')
                      );
             return $ret;
            }
         }
     }


      /**
      * 审核通过后，申请加分记录插入积分记录表
      */
      public function applyIntegration($id)
      {

      
                     $dataone = M('integration_apply')->where(array('id'=>$id))->find();
                     $arr = array(
                         'time'=>time(),
                         'user_id'=>$dataone['user_id'],
                         'score'=>$dataone['add_score'],
                         'type'=>'申请加分',
                         'describe'=>'申请加分-'.$dataone['apply_title'],
                         'apply_id'=>$dataone['id'],
                     );
					
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$arr['id'] = getNextId('integration_record');
					}
					
                     $ret1 = M('integration_record')->add($arr);
                         if ($ret1 === false) {
                        $this->rollback();
                        $res = array(
                        'status'=>0,
                        'info'=>'系统发生错误！'
                        );
                        return $res;
                        }
      }

      /**
      * 审核通过后，申请加学分记录插入学分统计表think_center_study
      */
      public function applyCredits($id)
      {

      
                     $dataone = M('credits_apply')->where(array('id'=>$id))->find();
                     $arr = array(
                         'create_time'=>date('Y-m-d H:i:s',$dataone['add_time']),
                         'typeid'=>6,
                         'credit'=>$dataone['add_score'],
                         'user_id'=>$dataone['user_id'],
                     );
                     $ret1 = M('center_study')->add($arr);
                       if ($ret1 === false) {
                          $this->rollback();
                          $res = array(
                          'status'=>0,
                          'info'=>'系统发生错误！'
                          );
                          return $res;
                        }
      }


      /**
      * 求两个日期之间相差的天数
      * (针对1970年1月1日之后，求之前可以采用泰勒公式)
      * @param string $day1
      * @param string $day2
      * @return number
      */
      public function diffBetweenTwoDays ($day1, $day2)
      {
        $second1 = strtotime($day1);
        $second2 = strtotime($day2);
          
        if ($second1 < $second2) {
          $tmp = $second2;
          $second2 = $second1;
          $second1 = $tmp;
        }
        return ($second1 - $second2) / 86400;
      }

      /**
      * 求两个日期之间相差的分钟数
      * (针对1970年1月1日之后，求之前可以采用泰勒公式)
      * @param string $time1
      * @param string $time2
      * @return number
      */
      public function diffBetweenTwoMinutes ($time1, $time2)
      {
        $second1 = strtotime($time1);
        $second2 = strtotime($time2);
          
        if ($second1 < $second2) {
          $tmp = $second2;
          $second2 = $second1;
          $second1 = $tmp;
        }
        return ($second1 - $second2) / 60;
      }

     

     /** 
     *点击待审核时触发生成关联表数据，并返回登录者的角色id
     *$type:  1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册，10：业务部落，11：申请加学分
     */
     public function clickTrigger($type){
       if($type == 1){ 

        $map = array(
              'type'=>2,
              
              );
        $lists = M('admin_project')
                ->where($map)
                ->field('*')
                ->select();
        
      // echo M('admin_project')->_sql();
       }else if($type == 2){
        $map = array(
              'status'=>0,
              
              );
        $lists = M('course')
                ->where($map)
                ->field('*')
                ->select();
        // echo M('course')->_sql(); die;
       }else if($type == 3){
        $map = array(
              'status'=>0,
              
              );
        $lists = M('examination')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 4){
        $map = array(
              'status'=>0,
              
              );
        $lists = M('survey')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 5){
        $map = array(
              'status'=>0,
              
              );
        $lists = M('friends_circle')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 6){
        $map = array(
              'audit_state'=>0,
              
              );
        $lists = M('research')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 7){
        $map = array(
              'audit_status'=>1,
              
              );
        $lists = M('test')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 8){
        $map = array(
              'status'=>0,
              
              );
        $lists = M('integration_apply')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 9){
        $map = array(
              'status'=>2,
              
              );
        $lists = M('users')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 10){
        $map = array(
              'status'=>0,
              
              );
        $lists = M('topic_group')
                ->where($map)
                ->field('*')
                ->select();
       }else if($type == 11){
        $map = array(
              'status'=>0,
              
              );
        $lists = M('credits_apply')
                ->where($map)
                ->field('*')
                ->select();
       }
      //获取当前审核配置表的数据
      // $type = 1;
      $dataSet = $this->AuditSetData($type);
      // dump($dataSet); exit;
      foreach($lists as $k=>$v){
            // if($v['type'] === null || $v['type'] !== 2 ){
              //判断为待审，把当前审核配置表配置 往审核表里生成关联数据，
              // 1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册
                if($type == 1){
                  $create_user_id = $v['user_id'];
                }else if($type == 2){
                  $create_user_id = $v['user_id'];
                }else if($type == 3){
                  $create_user_id = $v['test_heir'];
                }else if($type == 4){
                  $create_user_id = $v['survey_heir'];
                }else if($type == 5){
                  $create_user_id = $v['user_id'];
                }else if($type == 6){
                  $create_user_id = $v['create_user'];
                }else if($type == 7){
                  $create_user_id = $v['create_user'];
                }else if($type == 8){
                  $create_user_id = $v['user_id'];
                }else if($type == 9){
                  $create_user_id = $v['id'];
                }
               $res = $this->auditDataExist($v['id'],$type);
              //  echo $v['id'];

               $tissues = $this->getTissueid($create_user_id,$dataSet['one_level_type'],$dataSet['two_level_type'],$dataSet['three_level_type']);
                if($tissues['num'] != ''){
                  $dataSet['num'] = $tissues['num'];
                }


               if(!$res){
                //  echo dd;die;
                $auditData = array();
                $auditData = array(
                       'type'=> $type,
                       'correlate_id'=> $v['id'],
                       'oneaudit_role'=> $dataSet['oneaudit_role'],
                       'twoaudit_role'=> $dataSet['twoaudit_role'],
                       'threeaudit_role'=> $dataSet['threeaudit_role'],
                       'is_condition'=> $dataSet['is_condition'],
                       'condition_id'=> $dataSet['condition_id'],
                       'conditiona'=> $dataSet['conditiona'],
                       'conditionb'=> $dataSet['conditionb'],
                       'num'=> $dataSet['num'],
                       'one_level_type'=> $dataSet['one_level_type'],
                       'two_level_type'=> $dataSet['two_level_type'],
                       'three_level_type'=> $dataSet['three_level_type'],
                       'oneaudit_user_id'=> $dataSet['oneaudit_user_id'],
                       'twoaudit_user_id'=> $dataSet['twoaudit_user_id'],
                       'threeaudit_user_id'=> $dataSet['threeaudit_user_id'],

                       'one_leader_tissueid'=> $tissues['tissue1'],
                       'two_leader_tissueid'=> $tissues['tissue2'],
                       'three_leader_tissueid'=>$tissues['tissue3'],
                       
                 );
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$auditData['id'] = getNextId('audit');
				}

                 $res =  M('audit')->add($auditData);
               }
                
                //  dump($res);
            // }else{
            //   //非初审，项目表

            // }
      }
     
     //根据登录者获取登录者的所属角色，可多重角色
      $lander =$_SESSION;
      $gruop_id_arr = M('auth_group_access')
                 ->where(array('user_id'=>$lander['user']['id']))
                 ->field('group_id')
                 ->select();

      $temp = array();
      foreach($gruop_id_arr as $k=>$v){
           $temp[] = $v['group_id'];
      }
      $gruop_id = implode(',',$temp);
      return $gruop_id;
     }

     /** 
     * 各表的未审核数据是否在审核表中存在
     */
     public function auditDataExist($id,$type){
              
              $res = M('audit')->where(array('type'=>$type,'correlate_id'=>$id))->find();
             
               if($res){
                 return true;
               }else{
                 return false;
               }


     }


     /** 
     * 返回创建者所在组织tissue1-上级tissue2-上上级组织id tissue3 ，对应审核轮数num
     *
     */
     public function getTissueid($create_user_id,$one_level_type,$two_level_type,$three_level_type){
             
             
             if($one_level_type == 3 && $two_level_type == 3 && $three_level_type == 3 ){
                  $temp1 = M('tissue_group_access')->where(array('user_id'=>$create_user_id))->getField('tissue_id');
                  $temp2 = M('tissue_rule')->where(array('id'=>$temp1))->getField('pid') ;
                  $temp2 = $temp2 ? $temp2 : 0;
                  $temp3 = M('tissue_rule')->where(array('id'=>$temp2))->getField('pid');
                  $temp3 = $temp3 ? $temp3 : 0;
                  if($temp3 == 0){
                    $num = 2;
                  }else if($temp2 == 0){
                    $num = 1;
                  }
             }else if(($one_level_type == 3 && $two_level_type == 3) || ($one_level_type == 3 && $three_level_type == 3)|| ($one_level_type == 3 && $three_level_type == 3) ){
                  $temp1 = M('tissue_group_access')->where(array('user_id'=>$create_user_id))->getField('tissue_id');
                  $temp2 = M('tissue_rule')->where(array('id'=>$temp1))->getField('pid') ;
                  $temp2 = $temp2 ? $temp2 : 0;
                  $temp3 = M('tissue_rule')->where(array('id'=>$temp2))->getField('pid');
                  $temp3 = $temp3 ? $temp3 : 0;
                  if($temp2 == 0){
                    $num = 1;
                  }
             }
             
            $data = array(
                'tissue1' => $temp1,
                'tissue2' => $temp2,
                'tissue3' => $temp3,
                'num' => $num,
            ) ; 
            return $data;

     }








     /** 
     * 审核列表的显示转换处理 --上级审批人、等待审批人、下级审批人 
     *$type:  1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册，10：业务部落，11：申请加学分
     */
     public function listTransform($lists,$type){

        foreach($lists as $k=>$v){
              $uname = $this->getUserName($v['user_id']);
              $levalone_man_name = $this->getUserName($v['levalone_man']);
              $levaltwo_man_name = $this->getUserName($v['levaltwo_man']);
              $levalthree_man_name = $this->getUserName($v['levalthree_man']);
              $oneaudit_role_name = $this->getGroupName($v['oneaudit_role']);
              $twoaudit_role_name = $this->getGroupName($v['twoaudit_role']);
              $threeaudit_role_name = $this->getGroupName($v['threeaudit_role']);
              
              if($type == 1){
                $population = $this->getpopulation($v['id']);
                $project_time = $this->diffBetweenTwoDays($v['start_time'],$v['end_time']);
                $lists[$k]['project_time'] = round($project_time,2);
                $lists[$k]['population'] = $population;
              }else if($type == 7 || $type == 6){
                $uname = $this->getUserName($v['create_user']); 
              }else if($type == 4){
                $uname = $this->getUserName($v['survey_heir']); 
              }else if($type == 2 || $type == 11){
                $uname = $this->getUserName($v['user_id']); 
              }
              
              $lists[$k]['uname'] = $uname;
              $lists[$k]['levalone_man_name'] = $levalone_man_name;
              $lists[$k]['levaltwo_man_name'] = $levaltwo_man_name;
              $lists[$k]['levalthree_man_name'] = $levalthree_man_name;

              if($v['one_level_type'] == 1){
               $oneaudit_role_name = $this->getUserName($v['oneaudit_user_id']);
              }else if($v['one_level_type'] == 2){
               $lists[$k]['oneaudit_role_name'] = $oneaudit_role_name;
              }else if($v['one_level_type'] == 3){
               $oneaudit_role_name = '负责人';
              }

              if($v['two_level_type'] == 1){
                // echo aa;
               $twoaudit_role_name = $this->getUserName($v['twoaudit_user_id']);
              }else if($v['two_level_type'] == 2){
               $lists[$k]['twoaudit_role_name'] = $twoaudit_role_name;
              }else if($v['two_level_type'] == 3){
               $twoaudit_role_name = '负责人';
              } 

              if($v['three_level_type'] == 1){
               $threeaudit_role_name = $this->getUserName($v['threeaudit_user_id']);
              }else if($v['three_level_type'] == 2){
               $lists[$k]['threeaudit_role_name'] = $threeaudit_role_name;
              }else if($v['three_level_type'] == 3){
               $threeaudit_role_name = '负责人';
              }  


              // $lists[$k]['oneaudit_role_name'] = $oneaudit_role_name;
              // $lists[$k]['twoaudit_role_name'] = $twoaudit_role_name;
              // $lists[$k]['threeaudit_role_name'] = $threeaudit_role_name;

              
             //待审核列表的审批人  
              if($v['audit_status'] == 0 ){
                $lists[$k]['preauditman'] = '--';
                if($v['num'] == 3 ){
                  $lists[$k]['currentauditman'] = $oneaudit_role_name;
                  $lists[$k]['laterauditman'] = $twoaudit_role_name.'>'.$threeaudit_role_name;
                }else if($v['num'] == 2){
                  $lists[$k]['currentauditman'] = $oneaudit_role_name;
                  $lists[$k]['laterauditman'] = $twoaudit_role_name;
                }else if($v['num'] == 1){
                  $lists[$k]['currentauditman'] = $oneaudit_role_name;
                  $lists[$k]['laterauditman'] = '--';
                }
              }else if($v['audit_status'] == 1){
                  $lists[$k]['preauditman'] = $levalone_man_name;
                  if($v['num'] == 3 ){
                  $lists[$k]['currentauditman'] = $twoaudit_role_name;
                  $lists[$k]['laterauditman'] = $threeaudit_role_name;
                }else if($v['num'] == 2){
                  $lists[$k]['currentauditman'] = $twoaudit_role_name;
                  $lists[$k]['laterauditman'] = '--';
                }
              }else if($v['audit_status'] == 2){
                 $lists[$k]['preauditman'] =  $levalone_man_name.'>'.$levaltwo_man_name;
                 $lists[$k]['currentauditman'] = $threeaudit_role_name;
                 $lists[$k]['laterauditman'] = '--';
              }
             
             //已通过或已拒绝审核列表的审批人 auditor
              if($v['audit_status'] == 1){
                  $lists[$k]['auditor'] = $levalone_man_name;
                  
              }else if($v['audit_status'] == 2){
                 $lists[$k]['auditor'] = $levalone_man_name.'>'.$levaltwo_man_name;
              }else if($v['audit_status'] == 3){
                $lists[$k]['auditor'] = $levalone_man_name.'>'.$levaltwo_man_name.'>'.$levalthree_man_name;
              }else if($v['audit_status'] == 4){
                  $lists[$k]['auditor'] = $levalone_man_name;
                  
              }else if($v['audit_status'] == 5){
                 $lists[$k]['auditor'] = $levalone_man_name.'>'.$levaltwo_man_name;
              }else if($v['audit_status'] == 6){
                $lists[$k]['auditor'] = $levalone_man_name.'>'.$levaltwo_man_name.'>'.$levalthree_man_name;
              }

         }
         return $lists;

     }

     /** 
     *根据登陆者是否为负责人--并返回所在组织的tissue_id
     *
     */
     public function getPrincipalTissueId()
     {  
        $user_id = $_SESSION['user']['id'];
        $tissue_id = M('tissue_group_access')->where(array('user_id'=>$user_id,'manage_id'=>2))->getField('tissue_id');
        return $tissue_id = $tissue_id ? $tissue_id : '-1';
     }
     /** 
     *所有审核列表-模型
     *$type:  1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册，10：业务部落，11：申请加学分
     */
     public function threeallauditlist($type)
     {  


        $size = 15;
        
        $p1 = I('p1') ? I('p1') : 1 ;
        $p2 = I('p2') ? I('p2') : 1 ;
        $p3 = I('p3') ? I('p3') : 1 ;
	      $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        $keyword = I('get.table_search') !== '' ? I('get.table_search') : '';
        //判别audit表中是否存在相关type值条件
        $typeCondition = array("b.type"=>$type);
        // echo $keyword;
        if(is_numeric($keyword)){
            $condition  = array(
              'a.orderno'=>array("like","%$keyword%"),           
          );
        }else{
               switch ($type) {
                  case "1":
                    $condition  = array( 'a.project_name'=>array("like","%$keyword%"));
                    break;
                  case "2":
                    $condition  = array( 'a.course_name'=>array("like","%$keyword%"));
                    break;
                  case "3":
                    $condition  = array( 'a.test_name'=>array("like","%$keyword%"));
                    break;
                  case "4":
                    $condition  = array( 'a.survey_name'=>array("like","%$keyword%"));
                    break;
                  case "5":
                    $condition  = array( 'a.content'=>array("like","%$keyword%"));
                    break;
                  case "6":
                    $condition  = array( 'a.research_name'=>array("like","%$keyword%"));
                    break;
                  case "7":
                    $condition  = array( 'a.name'=>array("like","%$keyword%"));
                    break;
                  case "8":
                    $condition  = array( 'a.apply_title'=>array("like","%$keyword%"));
                    break;
                  case "9":
                    $condition  = array( 'a.username'=>array("like","%$keyword%"));
                    break;
                  case "10":
                    $condition  = array( 'a.name'=>array("like","%$keyword%"));
                    break;
                  case "11":
                    $condition  = array( 'a.apply_title'=>array("like","%$keyword%"));
                    break;
                  default:
                    $condition  =  array('a.orderno'=>1);
                }
            

        }

       if($tabType == 1){
      
       //待审核列表的触发关联审核表think_audit插入数据的处理
        $gruop_id = $this->clickTrigger($type);
      //  echo  $gruop_id;die;
       $Login_id = $_SESSION['user']['id'];

       $tissue_id = $this->getPrincipalTissueId();
      //  echo $tissue_id;
       $scopeMap = "     ( b.one_level_type = 2 AND b.audit_status = 0 AND b.oneaudit_role in ($gruop_id) ) 
                      OR ( b.two_level_type = 2 AND b.audit_status = 1 AND b.twoaudit_role in ($gruop_id) ) 
                      OR ( b.three_level_type = 2 AND b.audit_status = 2 AND b.threeaudit_role in ($gruop_id))

                      OR ( b.one_level_type = 1 AND b.audit_status = 0 AND b.oneaudit_user_id = $Login_id ) 
                      OR ( b.two_level_type = 1 AND b.audit_status = 1 AND b.twoaudit_user_id = $Login_id ) 
                      OR ( b.three_level_type = 1 AND b.audit_status = 2 AND b.threeaudit_user_id = $Login_id )
                         
                      OR ( b.one_level_type = 3 AND b.audit_status = 0 AND b.one_leader_tissueid = $tissue_id ) 
                      OR ( b.two_level_type = 3 AND b.audit_status = 1 AND b.two_leader_tissueid = $tissue_id) 
                      OR ( b.three_level_type = 3 AND b.audit_status = 2 AND b.three_leader_tissueid = $tissue_id)
                      
                      " ;
       
      
       if($type == 1){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>2))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.project_name,to_char(a.start_time,\'YYYY-MM-DD HH24:MI:SS\') as start_time,to_char(a.end_time,\'YYYY-MM-DD HH24:MI:SS\') as end_time,a.project_budget,a.population,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               //   echo haha;
               // echo M('admin_project')->getLastSql();
               // echo '<hr />';
               $count = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>2))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();
           }else{

               $lists = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>2))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               //   echo haha;
               // echo M('admin_project')->getLastSql();
               // echo '<hr />';
               $count = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>2))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();


           }
        



       }else if($type == 2){


           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.course_time,a.course_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               // echo  M('course')->_sql();
               $count = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }else{

               $lists = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               // echo  M('course')->_sql();
               $count = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }

       }else if($type == 3){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.test_heir,a.test_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }else{

               $lists = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }

       }else if($type == 4){


           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.survey_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }else{

               $lists = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }
        




       }else if($type == 5){


           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0,'a.pid'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               //  echo  M('friends_circle')->_sql();die;
               foreach($lists as $k=>$v){
                   $content_result =  re_substr(strip_tags($v['content']), 0, 15, true, "utf-8"); //截取字符串15位
                   if($content_result == ''){
                       $lists[$k]['content_result'] = '--';
                   }else{
                       $lists[$k]['content_result'] = $content_result;
                   }

               }
               $count = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0,'a.pid'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();



           }else{


               $lists = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0,'a.pid'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               //  echo  M('friends_circle')->_sql();die;
               foreach($lists as $k=>$v){
                   $content_result =  re_substr(strip_tags($v['content']), 0, 15, true, "utf-8"); //截取字符串15位
                   if($content_result == ''){
                       $lists[$k]['content_result'] = '--';
                   }else{
                       $lists[$k]['content_result'] = $content_result;
                   }

               }
               $count = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0,'a.pid'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();


           }


       }else if($type == 6){


           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.orderno,a.research_name,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }else{

               $lists = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }

       

       }else if($type == 7){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>1))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.orderno,a.name,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }else{

               $lists = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>1))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();


           }
        

       }else if($type == 8){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.add_score,a.apply_title,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();


           }else{

               $lists = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }

       }else if($type == 9){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->field("a.id,a.phone,a.username,a.orderno,b.id as audit_id,b.audit_status,b.objection")
                   ->where(array('a.status'=>2))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->select();


               $count = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>2))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();


           }else{

               $lists = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>2))
                   ->where($scopeMap)
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               // echo  M('users')->getLastsql();
               // dump($lists);
               $count = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>2))
                   ->where($condition)
                   ->where($typeCondition)
                   ->where($scopeMap)
                   ->count();

           }


       }else if($type == 10){
       
        $lists = M('topic_group')->alias('a')
                  ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                  ->where(array('a.status'=>0))
                  ->where($scopeMap)
                  ->where($condition)
                  ->where($typeCondition)
                  ->page($p1.','.$size)
                  ->order('a.id desc')
                  ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                  ->select(); 
        // echo  M('users')->getLastsql();    
        // dump($lists);           
        $count = M('topic_group')->alias('a')
                  ->join('left join __AUDIT__ as b on b.correlate_id = a.id') 
                  ->where(array('a.status'=>0))
                  ->where($condition)
                  ->where($typeCondition)
                  ->where($scopeMap)
                  ->count();
       }else if($type == 11){
      
        $lists = M('credits_apply')->alias('a')
                  ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                  ->where(array('a.status'=>0))
                  ->where($scopeMap)
                  ->where($condition)
                  ->where($typeCondition)
                  ->page($p1.','.$size)
                  ->order('a.id desc')
                  ->field('a.*,b.oneaudit_user_id,b.twoaudit_user_id,b.threeaudit_user_id,b.one_level_type,b.two_level_type,b.three_level_type,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                  ->select();                
        $count = M('credits_apply')->alias('a')
                  ->join('left join __AUDIT__ b on b.correlate_id = a.id') 
                  ->where(array('a.status'=>0))
                  ->where($condition)
                  ->where($typeCondition)
                  ->where($scopeMap)
                  ->count();
       }
       
       $show = tabPage($count,$size,'p1',1); 
       //  echo M('admin_project')->_sql();
       $lists = $this->listTransform($lists,$type);
   

    		 
      }else if($tabType == 2){
      
         if($_SESSION['user']['id'] == 1){ 
          //  echo aa;
       if($type == 1){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>array('in','0,4')))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p2.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.project_name,to_char(a.start_time,\'YYYY-MM-DD HH24:MI:SS\') as start_time,to_char(a.end_time,\'YYYY-MM-DD HH24:MI:SS\') as end_time,a.project_budget,a.population,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>array('in','0,4')))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }else{

               $lists = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>array('in','0,4')))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p2.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('admin_project')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.type'=>array('in','0,4')))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }




       }else if($type == 2){


           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.course_time,a.course_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }else{

               $lists = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('course')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }




       }else if($type == 3){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.test_heir,a.test_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }else{

               $lists = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('examination')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();


           }

       }else if($type == 4){


           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.survey_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }else{

               $lists = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('survey')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();


           }

       }else if($type == 5){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1,'a.pid'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.orderno,b.id as audit_id,b.audit_status')
                   ->select();
               foreach($lists as $k=>$v){
                   $content_result =  re_substr(strip_tags($v['content']), 0, 15, true, "utf-8"); //截取字符串15位
                   if($content_result == ''){
                       $lists[$k]['content_result'] = '--';
                   }else{
                       $lists[$k]['content_result'] = $content_result;
                   }

               }
               $count = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1,'a.pid'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }else{

               $lists = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1,'a.pid'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               foreach($lists as $k=>$v){
                   $content_result =  re_substr(strip_tags($v['content']), 0, 15, true, "utf-8"); //截取字符串15位
                   if($content_result == ''){
                       $lists[$k]['content_result'] = '--';
                   }else{
                       $lists[$k]['content_result'] = $content_result;
                   }

               }
               $count = M('friends_circle')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1,'a.pid'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }





       }else if($type == 6){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.orderno,a.research_name,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>1))
                   ->where($condition)
                   ->count();

           }else{

               $lists = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('research')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_state'=>1))
                   ->where($condition)
                   ->count();

           }


       }else if($type == 7){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.orderno,a.name,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }else{

               $lists = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('test')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.audit_status'=>0))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }

       }else if($type == 8){

           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.add_score,a.apply_title,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }else{

               $lists = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('integration_apply')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }

       }else if($type == 9){


           if(strtolower(C('DB_TYPE')) == 'oracle'){

               $lists = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.id,a.phone,a.username,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                   ->select();
               $count = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();


           }else{

               $lists = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->page($p1.','.$size)
                   ->order('a.id desc')
                   ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                   ->select();
               $count = M('users')->alias('a')
                   ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                   ->where(array('a.status'=>1))
                   ->where($condition)
                   ->where($typeCondition)
                   ->count();

           }


       }else if($type == 10){

        $lists = M('topic_group')->alias('a')
                  ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                  ->where(array('a.status'=>1))
                  ->where($condition)
                  ->where($typeCondition)
                  ->page($p1.','.$size)
                  ->order('a.id desc')
                  ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                  ->select(); 
        // echo  M('topic_group')->getLastsql();    
        // dump($lists);           
        $count = M('topic_group')->alias('a')
                  ->join('left join __AUDIT__ as b on b.correlate_id = a.id') 
                  ->where(array('a.status'=>1))
                  ->where($condition)
                  ->where($typeCondition)
                  ->where($scopeMap)
                  ->count();
       }else if($type == 11){
        $lists = M('credits_apply')->alias('a')
                  ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                  ->where(array('a.status'=>1))
                  ->where($condition)
                  ->where($typeCondition)
                  ->page($p1.','.$size)
                  ->order('a.id desc')
                  ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                  ->select();                
        $count = M('credits_apply')->alias('a')
                  ->join('left join __AUDIT__ b on b.correlate_id = a.id') 
                  ->where(array('a.status'=>1))
                  ->where($condition)
                  ->where($typeCondition)
                  ->count();
       }

      //  dump($lists);
       $lists = $this->listTransform($lists,$type); //列表显示转换

      //  dump($lists);
      //  dump($lists);
       $show = tabPage($count,$size,'p2',2); 


         }  
      }else if($tabType == 3){
        if($_SESSION['user']['id'] == 1){
           
        if($type == 1){

            if(strtolower(C('DB_TYPE')) == 'oracle'){

                $lists = M('admin_project')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.type'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p3.','.$size)
                    ->field('a.id,a.project_name,to_char(a.start_time,\'YYYY-MM-DD HH24:MI:SS\') as start_time,to_char(a.end_time,\'YYYY-MM-DD HH24:MI:SS\') as end_time,a.project_budget,a.population,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                    ->select();
                // dump($lists);
                $count = M('admin_project')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.type'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }else{

                $lists = M('admin_project')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.type'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p3.','.$size)
                    ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();
                // dump($lists);
                $count = M('admin_project')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.type'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();


            }




        }else if($type == 2){

            if(strtolower(C('DB_TYPE')) == 'oracle'){

                $lists = M('course')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.id,a.course_time,a.course_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                    ->select();
                $count = M('course')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }else{

                $lists = M('course')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();
                $count = M('course')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }



       }else if($type == 3){


            if(strtolower(C('DB_TYPE')) == 'oracle'){

                $lists = M('examination')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.id,a.test_heir,a.test_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                    ->select();
                $count = M('examination')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }else{

                $lists = M('examination')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();
                $count = M('examination')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }





       }else if($type == 4){

            if(strtolower(C('DB_TYPE')) == 'oracle'){

                $lists = M('survey')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.survey_name,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                    ->select();
                $count = M('survey')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }else{

                $lists = M('survey')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();
                $count = M('survey')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>3))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }
     

       }else if($type == 5){

            if(strtolower(C('DB_TYPE')) == 'oracle'){

                $lists = M('friends_circle')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2,'a.pid'=>0))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.orderno,b.id as audit_id,b.audit_status')
                    ->select();
                foreach($lists as $k=>$v){
                    $content_result =  re_substr(strip_tags($v['content']), 0, 15, true, "utf-8"); //截取字符串15位
                    if($content_result == ''){
                        $lists[$k]['content_result'] = '--';
                    }else{
                        $lists[$k]['content_result'] = $content_result;
                    }

                }
                $count = M('friends_circle')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2,'a.pid'=>0))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }else{

                $lists = M('friends_circle')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2,'a.pid'=>0))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();
                foreach($lists as $k=>$v){
                    $content_result =  re_substr(strip_tags($v['content']), 0, 15, true, "utf-8"); //截取字符串15位
                    if($content_result == ''){
                        $lists[$k]['content_result'] = '--';
                    }else{
                        $lists[$k]['content_result'] = $content_result;
                    }

                }
                $count = M('friends_circle')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.status'=>2,'a.pid'=>0))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }

       }else if($type == 6){

            if(strtolower(C('DB_TYPE')) == 'oracle'){

                $lists = M('research')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.audit_state'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.id,a.orderno,a.research_name,b.id as audit_id,b.audit_status,b.objection')
                    ->select();
                $count = M('research')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.audit_state'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }else{

                $lists = M('research')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.audit_state'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->page($p1.','.$size)
                    ->order('a.id desc')
                    ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                    ->select();
                $count = M('research')->alias('a')
                    ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                    ->where(array('a.audit_state'=>2))
                    ->where($condition)
                    ->where($typeCondition)
                    ->count();

            }





       }else if($type == 7){

        if(strtolower(C('DB_TYPE')) == 'oracle'){

            $lists = M('test')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.audit_status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->page($p1.','.$size)
                ->order('a.id desc')
                ->field('a.id,a.orderno,a.name,b.id as audit_id,b.audit_status,b.objection')
                ->select();
            $count = M('test')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.audit_status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->count();

        }else{

            $lists = M('test')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.audit_status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->page($p1.','.$size)
                ->order('a.id desc')
                ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();
            $count = M('test')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.audit_status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->count();

        }

       }else if($type == 8){

        if(strtolower(C('DB_TYPE')) == 'oracle'){

            $lists = M('integration_apply')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->page($p1.','.$size)
                ->order('a.id desc')
                ->field('a.id,a.add_score,a.apply_title,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                ->select();
            $count = M('integration_apply')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->count();

        }else{

            $lists = M('integration_apply')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->page($p1.','.$size)
                ->order('a.id desc')
                ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();
            $count = M('integration_apply')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>2))
                ->where($condition)
                ->where($typeCondition)
                ->count();

        }

       }else if($type == 9){


        if(strtolower(C('DB_TYPE')) == 'oracle'){

            $lists = M('users')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>0))
                ->where($condition)
                ->where($typeCondition)
                ->page($p1.','.$size)
                ->order('a.id desc')
                ->field('a.id,a.phone,a.username,a.orderno,b.id as audit_id,b.audit_status,b.objection')
                ->select();
            $count = M('users')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>0))
                ->where($condition)
                ->where($typeCondition)
                ->count();

        }else{

            $lists = M('users')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>0))
                ->where($condition)
                ->where($typeCondition)
                ->page($p1.','.$size)
                ->order('a.id desc')
                ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                ->select();
            $count = M('users')->alias('a')
                ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                ->where(array('a.status'=>0))
                ->where($condition)
                ->where($typeCondition)
                ->count();

        }


       }else if($type == 10){
       
        $lists = M('topic_group')->alias('a')
                  ->join('left join __AUDIT__ as b on b.correlate_id = a.id')
                  ->where(array('a.status'=>2))
                  ->where($condition)
                  ->where($typeCondition)
                  ->page($p1.','.$size)
                  ->order('a.id desc')
                  ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                  ->select(); 
        // echo  M('users')->getLastsql();    
        // dump($lists);           
        $count = M('topic_group')->alias('a')
                  ->join('left join __AUDIT__ as b on b.correlate_id = a.id') 
                  ->where(array('a.status'=>2))
                  ->where($condition)
                  ->where($typeCondition)
                  ->where($scopeMap)
                  ->count();
       }else if($type == 11){
        $lists = M('credits_apply')->alias('a')
                  ->join('left join __AUDIT__ b on b.correlate_id = a.id')
                  ->where(array('a.status'=>2))
                  ->where($condition)
                  ->where($typeCondition)
                  ->page($p1.','.$size)
                  ->order('a.id desc')
                  ->field('a.*,b.id as audit_id,b.levalone_man,b.levaltwo_man,b.levaltwo_man,b.levalthree_man,b.audit_status,b.objection,b.oneaudit_role,b.twoaudit_role,b.threeaudit_role,b.is_condition,b.condition_id,b.conditiona,b.conditionb,b.num')
                  ->select();                
        $count = M('credits_apply')->alias('a')
                  ->join('left join __AUDIT__ b on b.correlate_id = a.id') 
                  ->where(array('a.status'=>2))
                  ->where($condition)
                  ->where($typeCondition)
                  ->count();
       }
   
       $show = tabPage($count,$size,'p3',3);  
       $lists = $this->listTransform($lists,$type); //列表显示转换
      //  dump($lists);

         }
      }

      $data = array(
				  'keyword'=>$keyword,
			    'lists'=>$lists,
			    'page'=>$show
		       );
		  return $data;
     }




     /** 
     *审核设置的数据
     */
     public function AuditSetData($type)
     {  
       if($type == 11) $type = 8;  //申请加学分取申请加分的审核设置
        $map = array(
          'a.type'=>$type
        );
        $dataSet = M('audit_rule')->alias('a')
                ->field('a.*,b.name,conditiona,conditionb')
                ->join('left join __AUDIT_CONDITION__ b on b.id = a.condition_id')
                ->where($map)
                ->find();

        return $dataSet;

     }

     /** 
     *获取用户名
     */
     public function getUserName($user_id)
     {  
      $username = M('users')->where(array('id'=>$user_id))->getField('username');
       return $username;

     }


     /** 
     *获取角色名
     */
     public function getGroupName($id)
     {  
      $title = M('auth_group')->where(array('id'=>$id))->getField('title');
       return $title;

     }

     /** 
     *获取角色名 ,$id为项目id
     */
     public function getpopulation($id)
     {  
      $population = M('designated_personnel')->field('count(*) as population')->where(array('project_id'=>$id))->select();
      // echo M('designated_personnel')->_sql();
       return $population[0]['population'];

     }

     /** 
     *三期所有审核的详情展示
     */
     public function threeallauditdetail()
     {
        $tablename =  I('get.tablename');
        $id =  I('get.id');        
        if($tablename == 'admin_project'){
              $audit = M('admin_project');
                if(IS_GET){
                $get  = $id;

              $res1 = $audit->field('a.id as aid,a.*,b.*')->alias('a')
                            ->join(' LEFT JOIN __USERS__ b  on b.id = a.user_id')
                            ->where("a.id = $get")
                            ->find();
             //项目指定人员
              $persons =  $audit->field('c.username')->alias('a')
                            ->join('LEFT JOIN __DESIGNATED_PERSONNEL__ b on a.id = b.project_id')
                    ->join('LEFT JOIN __USERS__ as c on b.user_id = c.id')
                    ->where(array('a.id'=>$get))
                    ->select();
              $res1['uname'] = $persons;         

              //项目下的课程详情
                $res2 = $audit->field('b.*')->alias('a')
                      ->field('c.course_name,c.course_way,c.lecturer,c.lecturer_name,d.name,b.start_time,b.end_time,b.credit,b.location')
                          ->join(' LEFT JOIN __PROJECT_COURSE__  b  on a.id = b.project_id ')
                  ->join(' LEFT JOIN __COURSE__  c  on b.course_id = c.id ')
                  ->join(' LEFT JOIN __LECTURER__  d  on c.lecturer = d.id ')
                          ->where("a.id = $get")
                          ->select();

                $coursedesc = $res2;

            //项目下的考试详情
                $res2 = $audit->alias('a')
                          ->field('b.cacheid,b.test_names,c.test_mode,d.username,b.start_time,b.end_time,b.credits,b.test_length as test_length')
                          ->join(' LEFT JOIN __PROJECT_EXAMINATION__  b  on a.id = b.project_id ')
                          ->join(' LEFT JOIN __EXAMINATION__  c  on b.test_id = c.id ')
                          ->join(' LEFT JOIN __USERS__  d  on b.manager_id = d.id ')
                          ->where("a.id = $get")
                          ->select();
              $examdesc = $res2;

              //项目下的调研详情
              $res2 = $audit->alias('a')
                      ->field('c.survey_name,d.username,b.start_time,b.end_time,b.credit')
                          ->join(' LEFT JOIN __PROJECT_SURVEY__  b  on a.id = b.project_id ')
                  ->join(' LEFT JOIN __SURVEY__  c  on b.survey_id = c.id ')
                  ->join(' LEFT JOIN __USERS__  d  on b.manager_id = d.id ')
                          ->where("a.id = $get")
                          ->select();
            $surveydesc = $res2;



                    //指定部门
                    if(!empty($res1['tissue_id'])){

                        $tissue_id = unserialize($res1['tissue_id']);

                        if(!empty($tissue_id)){

                            $where['id'] = array("in",$tissue_id);

                            $department = M('tissue_rule')->field('name')->where($where)->select();

                            foreach($department as $item){

                                $str_name[] = $item['name'];

                            }

                            if(!empty($str_name)){
                                $department = implode(",",$str_name);
                            }else{
                                $department = '';
                            }

                        }else{
                            $department = "";
                        }

                    }else{
                        $department = "";
                    }



                $data = array(
              'res1'=>$res1,
              'coursedesc'=>$coursedesc,
              'examdesc'=>$examdesc,
              'surveydesc'=>$surveydesc,
               'department'=>$department

                );
            
                }
              return $data; 
        }else if($tablename == 'course'){
              $coursem = M('course');
              $where = array(
                    'a.id' => $id,        
                );
              
              /* 课程详情逻辑 */
              $res = $coursem->field('a.id,a.chapter,a.arrangement_id,a.jobs_id,a.tag_id,a.course_name,d.name,a.course_cover,c.cat_name,a.status,a.course_time,a.course_way,a.maker,a.lecturer_name,a.lecturer,b.username,a.create_time,e.course_aim,e.course_outline')
                            ->alias('a')
                            ->join('left join __USERS__ b on a.user_id=b.id')
                            ->join('left join __COURSE_CATEGORY__ c on a.course_cat_id=c.id')
                    ->join('left join __LECTURER__ d on a.lecturer=d.id')
                    ->join('left join __COURSE_DETAIL__ e on a.id=e.id')
                    ->where($where)
                    ->find();
              $res['chapter'] = json_decode($res['chapter'],true);

             //面授课，讲师为线上讲师（内部讲师，或外部讲师），关联讲师表取出讲师姓名
            
              if($res['course_way'] == 1 || $res['lecturer'] != 0 ){
              $lecturer = $coursem->field('b.name,b.user_id,b.type')
                                  ->alias('a')
                                  ->join('left join __LECTURER__ b on a.lecturer=b.id')
                                  ->where($where)
                                  ->find();
                if($lecturer['type'] == 1){
                          $lecturername = $lecturer['name']; 
                }elseif($lecturer['type'] == 0){
                          $lecturername = M('users')->where(array('id'=>$lecturer['user_id']))->getField('username');
                }
              $res['lecturer_name'] =  $lecturername;
              }
              
              //所属层次，标签，岗位
              //所属层次(1-基层,2-中间层,3-核心层,4-专业层)
              switch ($res['arrangement_id'])
                {
                case 1:
                  $res['arrangement_name'] = '基层';
                  break;
                case 2:
                  $res['arrangement_name'] = '中间层';
                  break;
                case 3:
                  $res['arrangement_name'] = '核心层';
                  break;
                case 4:
                  $res['arrangement_name'] = '专业层';
                  break;       
                }
               
               $res['jobs_name'] = M('jobs_manage')->where(array('id'=>$res['jobs_id']))->getField('name');
               
               $res['tag_ids'] = explode(',',$res['tag_id']); //array
               foreach($res['tag_ids'] as $k=>$v){
                 $res['tag_names'][] = M('users_tag')->where(array('id'=>$v))->getField('tag_title');
               }
                $res['tag_name'] = implode('，',$res['tag_names']);
                //  print_r($res['chapter']);
                return $res;
        }else if($tablename == 'examination'){
                   
        }else if($tablename == 'survey'){
                $coursem = M('survey');
                    $where = array(
                          'a.id' => $id,        
                      );
                $res = $coursem->field('a.id,a.status,a.survey_name,a.survey_score,c.username as survey_heir,a.survey_upload_time,b.cat_name')
                                ->alias('a')
                                ->join('left join __SURVEY_CATEGORY__ b on a.survey_cat_id=b.id')
                                ->join('left join __USERS__ c on a.survey_heir=c.id ')
                                ->where($where)
                                ->find();
                  /* 问卷详情逻辑,取问卷item */
                  $question = M('survey_item'); 
                    $where = array(
                      'b.id'=>$id
                    );

                  $surveyitem = $question->field('a.*')
                                ->alias('a')
                                ->join('left join __SURVEY__ b on a.survey_id=b.id')
                                ->where($where)
                                ->order('classification asc,id asc')
                                ->select();
                  $data = array(
                  'res'=>$res,
                  'surveyitem'=>$surveyitem
                    );
                  return $data;
        }else if($tablename == 'friends_circle'){
                //话题审核详情
                $data = M('friends_circle')->alias('a')->field('a.*,b.username,"话题审核" as auditTitle')
                        ->join('left join __USERS__ b on b.id=a.user_id')
                        ->where(array('a.id'=>$id))
                        ->find();
                return $data;               
        }else if($tablename == 'research'){
                  //获取发起考试详情的头部信息
                $title = M('research')
                      ->alias('a')
                      ->join('left join __USERS__ b on b.id=a.create_user')
                      ->join('left join __RESEARCH_TISSUEID__ c on c.research_id=a.id')
                      ->join('left join __TISSUE_RULE__ d on d.id=c.tissue_id')
                      ->where(array('a.id'=>$id))
                      ->field('a.id,a.research_name,a.survey_id,a.credits,b.username,a.create_time,a.start_time,a.end_time,a.audit_state,d.name')
                      ->select();
                  //  print_r($title); 
    
                  $survey_id = $title[0]['survey_id'];
                  //调研类别
                  $cat_name = M('survey a')->field("b.cat_name")->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")->where(array("a.id"=>$survey_id))->find();

                  //调研题题目
                  $survey_item = M('survey_item ')->where("survey_id = ".$survey_id)->select();

                  //单选题,多选题，描述题
                  $detail = array();

                  foreach($survey_item as $item){
                      if($item['classification'] == 1){
                          $detail['radio_list'][] = $item;
                      }elseif($item['classification'] == 2){
                          $detail['multiselect_list'][] = $item;
                      }else{
                          $detail['describe_list'][] = $item;
                      }
                  }

                $data = array(
                  'title'=>$title,
                  'detail'=>$detail
                );
                return  $data;
          }else if($tablename == 'test'){
          //获取发起考试详情的头部信息
          $title = M('test')
                      ->alias('a')
                      ->join('left join __USERS__ b on b.id=a.create_user')
                      ->join('left join __EXAMINATION__ c on c.id=a.examination_id')
                      ->where(array('a.id'=>$id))
                      ->field('a.id,a.name,a.examination_id,a.type,a.score,c.test_score,b.username,a.create_time,a.start_time,a.end_time,a.audit_status,a.address')
                      ->find();
          //获取发起考试详情的的指定人员
          $designee = M('test')
                      ->alias('a')
                      ->join('left join __TEST_USER_REL__ b on b.test_id=a.id')
                      ->join('left join __USERS__ c on c.id=b.user_id')
                      ->where(array('a.id'=>$id))
                      ->field('c.username as designee')
                      ->select();
            // foreach($designees as $k=>$v){
            //   $designee = $designee.'  '.$v['designee'];
            // }
            //试卷展示 
          //  print_r($res); 
          //  print_r($designee); 
            if($title['type'] == 0){
              //判断为线上考试： 获取试卷详情
              $testdetail = D('TestManage')->getExamDetail($id);
            }else{
              $testdetail = '此发起考试为线下考试';
            }
            // print_r($data); 
            $data = array(
                'title'=>$title,
                'designee'=>$designee,
                'testdetail'=>$testdetail
            );
            return $data;
        }else if($tablename == 'integration_apply'){
             $data = M('integration_apply')->alias('a')->field('a.*,b.username')
                    ->join('left join __USERS__ b on b.id=a.user_id')
                    ->where(array('a.id'=>$id))
                    ->find();
              if(end(explode('.', $data['attachment'])) == 'doc' || end(explode('.', $data['attachment'])) == 'docx' ){
                $data['type'] = 2;
                $data['docmentName'] = end(explode('/', $data['attachment']));
              }else{
                $data['type'] = 1;
              }
             return $data;
        }else if($tablename == 'users'){
              $data = M('users')->where(array('id'=>$id))->find();
              return $data;             
        }else if($tablename == 'topic_group'){
              $data = M('topic_group')->alias('a')
                       ->join('left join __USERS__  b on b.id=a.user_id')
                       ->where(array('a.id'=>$id))
                       ->field('a.*,b.username')
                       ->find();
              return $data;             
        }else if($tablename == 'credits_apply'){
             $data = M('credits_apply')->alias('a')->field('a.*,b.username')
                    ->join('left join __USERS__ b on b.id=a.user_id')
                    ->where(array('a.id'=>$id))
                    ->find();
              if(end(explode('.', $data['attachment'])) == 'doc' || end(explode('.', $data['attachment'])) == 'docx' ){
                $data['type'] = 2;
                $data['docmentName'] = end(explode('/', $data['attachment']));
              }else{
                $data['type'] = 1;
              }
             return $data;
        }

     }


     /** 
      *项目重新（即将表数据的状态变为待审核）提交接口
      * @param $id 项目id
      * @param $type 类型(1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册,10:话题小组,11:申请加学分) 
      * @return $res, 返回结果 bool
      *调用方法 实例：$res = D('Audit')->projectResubmit($id,1);
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

    






    //------------------------  二期代码   -----------------------//    
	
      /** 
     *所有审核列表展示   
     */
     public function allauditlist()
     {

        $size = 15;
        
        $p1 = I('p1') ? I('p1') : 1 ;
        $p2 = I('p2') ? I('p2') : 1 ;
        $p3 = I('p3') ? I('p3') : 1 ;
	    $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        $auditType = I('get.auditType') !== '' ? I('get.auditType') : '0'; //搜索下拉框传来的值
      	$keyword = trim(I('get.table_search')) !== '' ? trim(I('get.table_search')) : '';
        
		$condition = array('like',"%$keyword%");
        

        if($tabType == 1){
          $p = $p1;
          $auditStatus = array(
            '1'=>2,
            '2'=>0,
            '3'=>0,
            '4'=>0,
            '5'=>0,
            '6'=>0,
            '7'=>1,
            '8'=>0,
            '9'=>2,
          );
        }else if($tabType == 2){
          $p = $p2; 
          $auditStatus = array(
            '1'=>array('in','0,4'),
            '2'=>1,
            '3'=>1,
            '4'=>1,
            '5'=>1,
            '6'=>1,
            '7'=>0,
            '8'=>1,
            '9'=>1,
          );
        }else if($tabType == 3){
          $p = $p3; 
          $auditStatus = array(
            '1'=>3,
            '2'=>2,
            '3'=>3,
            '4'=>3,
            '5'=>2,
            '6'=>2,
            '7'=>2,
            '8'=>2,
            '9'=>0,
          );
        }
      //审核列表展示  全部审核类型
	  if($auditType == 0){
         if(is_array($auditStatus[1])){
           $auditStatus[1] = $auditStatus[1][1];
         }else{
           $auditStatus[1] = $auditStatus[1];
         }


        //计算记录的偏移量
        $offset = $size * ($p - 1);
         
	    $sql = "select audit_time,'admin_project' as tableName,id,project_name as auditTitle ,'培训项目' as type,start_time,end_time,add_time from __ADMIN_PROJECT__ where type in ($auditStatus[1])  having auditTitle like '%$keyword%' 
		    union select audit_time,'course' as tableName,id,course_name as auditTitle,'新建课程' as type, '-' start_time, '-' end_time,FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%i:%s' ) as add_time FROM __COURSE__ where status=$auditStatus[2] having auditTitle like '%$keyword%' 	
				union select audit_time,'examination' as tableName,id,test_name as auditTitle,'新建试卷' as type,'-' as start_time,'-' as end_time,test_upload_time as add_time FROM __EXAMINATION__ where status=$auditStatus[3]  having auditTitle like '%$keyword%' 
				union select audit_time,'survey' as tableName,id,survey_name as auditTitle,'新建问卷' as type,'-' as start_time,'-' as end_time,survey_upload_time as add_time FROM __SURVEY__ where status=$auditStatus[4]  having auditTitle like '%$keyword%' 
        union select audit_time,'friends_circle' as tableName,id,'话题审核' as auditTitle,'新建话题' as type,'-' as start_time,'-' as end_time,publish_time as add_time FROM __FRIENDS_CIRCLE__ where status=$auditStatus[5] and pid=0 having auditTitle like '%$keyword%'
				union select audit_time,'research' as tableName,id,research_name as auditTitle,'发起调研' as type,start_time,end_time,create_time as add_time FROM __RESEARCH__ where audit_state=$auditStatus[6] having auditTitle like '%$keyword%' 
				union select audit_time,'test' as tableName,id,name as auditTitle,'发起考试' as type,start_time,end_time,create_time as add_time FROM __TEST__ where audit_status=$auditStatus[7]  having auditTitle like '%$keyword%' 
				union select audit_time,'integration_apply' as tableName,id,apply_title as auditTitle,'加分申请' as type,'-' as start_time, '-' as end_time,FROM_UNIXTIME(add_time, '%Y-%m-%d %H:%i:%s' ) as add_time FROM __INTEGRATION_APPLY__ where status=$auditStatus[8] having auditTitle like '%$keyword%'
				union select audit_time,'users' as tableName,id,username as auditTitle,'用户注册' as type,'-' as start_time, '-' as end_time,register_time as add_time FROM __USERS__ where status=$auditStatus[9]  having auditTitle like '%$keyword%'
				order by  add_time desc LIMIT $offset,$size
				";
        $sqlcount = "select 'admin_project' as tableName,id,project_name as auditTitle ,'培训项目' as type,start_time,end_time,add_time from __ADMIN_PROJECT__ where type in ($auditStatus[1])  having auditTitle like '%$keyword%' 
		    union select 'course' as tableName,id,course_name as auditTitle,'新建课程' as type, '-' start_time, '-' end_time,FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%i:%s' ) as add_time FROM __COURSE__ where status=$auditStatus[2] having auditTitle like '%$keyword%' 	
				union select 'examination' as tableName,id,test_name as auditTitle,'新建试卷' as type,'-' as start_time,'-' as end_time,test_upload_time as add_time FROM __EXAMINATION__ where status=$auditStatus[3]  having auditTitle like '%$keyword%' 
				union select 'survey' as tableName,id,survey_name as auditTitle,'新建问卷' as type,'-' as start_time,'-' as end_time,survey_upload_time as add_time FROM __SURVEY__ where status=$auditStatus[4]  having auditTitle like '%$keyword%' 
			  union select 'friends_circle' as tableName,id,'话题审核' as auditTitle,'新建话题' as type,'-' as start_time,'-' as end_time,publish_time as add_time FROM __FRIENDS_CIRCLE__ where status=$auditStatus[5] and pid=0 having auditTitle like '%$keyword%' 
      	union select 'research' as tableName,id,research_name as auditTitle,'发起调研' as type,start_time,end_time,create_time as add_time FROM __RESEARCH__ where audit_state=$auditStatus[6] having auditTitle like '%$keyword%' 
				union select 'test' as tableName,id,name as auditTitle,'发起考试' as type,start_time,end_time,create_time as add_time FROM __TEST__ where audit_status=$auditStatus[7]  having auditTitle like '%$keyword%' 
				union select 'integration_apply' as tableName,id,apply_title as auditTitle,'加分申请' as type,'-' as start_time, '-' as end_time,FROM_UNIXTIME(add_time, '%Y-%m-%d %H:%i:%s' ) as add_time FROM __INTEGRATION_APPLY__ where status=$auditStatus[8] having auditTitle like '%$keyword%'
				union select 'users' as tableName,id,username as auditTitle,'用户注册' as type,'-' as start_time, '-' as end_time,register_time as add_time FROM __USERS__ where status=$auditStatus[9]  having auditTitle like '%$keyword%'
				order by  add_time desc 
				";
        $list = M()->query($sql);
        $listcount = M()->query($sqlcount);
        $count = count($listcount);
	//    $data = M('admin_project')->alias('a')->field('tname')
    // //   ->table('think_user_0')
    //   ->union('SELECT username FROM __USERS__ AS b')
	//   ->union('SELECT survey_name FROM __SURVEY__ AS c')
    //   ->select();
       }else if($auditType == 1){
       //审核列表展示  项目审核类型
	   $audit = M('admin_project'); 
       $list = $audit->field("'admin_project' as tableName,id,project_name as auditTitle ,'培训项目' as type,start_time,end_time,add_time,audit_time")
				  ->order('id desc')
                  ->where(array('type'=>$auditStatus[1],'project_name'=>$condition))
                  ->page($p.','.$size)
                  ->select();    
       $count = $audit->where(array('type'=>$auditStatus[1],'project_name'=>$condition))->count(); 
       }else if($auditType == 2){
       //审核列表展示  新建课程类型
       $list = M('course')->field("'course' as tableName,id,course_name as auditTitle,'新建课程' as type, '-' start_time, '-' end_time,FROM_UNIXTIME(create_time, '%Y-%m-%d %H:%i:%s' ) as add_time,audit_time")
				  ->order('id desc')
                  ->where(array('status'=>$auditStatus[2],'course_name'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
       $count = M('course')->where(array('status'=>$auditStatus[2],'course_name'=>$condition))->count(); 
	   }else if($auditType == 3){
        //审核列表展示  新建试卷类型
       $list = M('examination')->field("'examination' as tableName,id,test_name as auditTitle,'新建试卷' as type,start_time,end_time,test_upload_time as add_time,audit_time")
				  ->order('id desc')
                  ->where(array('status'=>$auditStatus[3],'test_name'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
       $count = M('examination')->where(array('status'=>$auditStatus[3],'test_name'=>$condition))->count();      
	   }else if($auditType == 4){
        //审核列表展示  新建问卷类型
       $list = M('survey')->field("'survey' as tableName,id,survey_name as auditTitle,'新建问卷' as type,start_time,end_time,survey_upload_time as add_time,audit_time")
				  ->order('id desc')
                  ->where(array('status'=>$auditStatus[4],'survey_name'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
       $count =M('survey')->where(array('status'=>$auditStatus[4],'survey_name'=>$condition))->count();      
	   }else if($auditType == 5){
        //审核列表展示  新建话题类型  
       $list = M('friends_circle')->field("'friends_circle' as tableName,id,'话题审核' as auditTitle,'新建话题' as type,'-' as start_time, '-' as end_time,publish_time as add_time,audit_time")
				          ->order('id desc')
                  ->where(array('status'=>$auditStatus[5],'pid'=>0,'content'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
       $count =M('friends_circle')->where(array('status'=>$auditStatus[5],'content'=>$condition))->count();   
	   }else if($auditType == 6){
        //审核列表展示  发起调研类型
       $list = M('research')->field("'research' as tableName,id,research_name as auditTitle,'发起调研' as type,start_time,end_time,create_time as add_time,audit_time")
				          ->order('id desc')
                  ->where(array('audit_state'=>$auditStatus[6],'research_name'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
       $count = M('research')->where(array('audit_state'=>$auditStatus[6],'research_name'=>$condition))->count();      

	   }else if($auditType == 7){
       //审核列表展示  发起考试类型
       $list = M('test')->field("'test' as tableName,id,name as auditTitle,'发起考试' as type,start_time,end_time,create_time as add_time,audit_time")
			        	  ->order('id desc')
                  ->where(array('audit_status'=>$auditStatus[7],'name'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
       $count = M('test')->where(array('audit_status'=>$auditStatus[7],'name'=>$condition))->count(); 

	   
	   }else if($auditType == 8){
        //审核列表展示  加分申请类型
       $list = M('integration_apply')->field("'integration_apply' as tableName,id,apply_title as auditTitle,'加分申请' as type,'-' as start_time, '-' as end_time,FROM_UNIXTIME(add_time, '%Y-%m-%d %H:%i:%s' ) as add_time,audit_time")
				          ->order('id desc')
                  ->where(array('status'=>$auditStatus[8],'apply_title'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
      //  dump($list);           
       $count = M('integration_apply')->where(array('status'=>$auditStatus[8],'apply_title'=>$condition))->count();     
	   }else if($auditType == 9){
        //审核列表展示  用户注册类型
       $list = M('users')->field("'users' as tableName,id,username as auditTitle,'用户注册' as type,'-' as start_time, '-' as end_time,register_time as add_time,audit_time")
				          ->order('id desc')
                  ->where(array('status'=>$auditStatus[9],'username'=>$condition)) 
                  ->page($p.','.$size)
                  ->select();  
       $count =  M('users')->where(array('status'=>$auditStatus[9],'username'=>$condition))->count();     
	   }
  

       if($tabType == 1){
       $show = tabPage($count,$size,'p1',1);   
       }else if($tabType == 2){
       $show = tabPage($count,$size,'p2',2);   
       }else if($tabType == 3){
       $show = tabPage($count,$size,'p3',3);   
       }
        
		  $data = array(
			    'auditType'=>$auditType,
				  'keyword'=>$keyword,
			    'list'=>$list,
			    'page'=>$show
		       );
		  return $data;
     
     }
     
     
	/**
	 *全部类型的批量审核
	 */
   public function batchAudit(){
         $ids = I('post.ids');
         $tablenames = I('post.tablenames'); 
		     $auditType =I('post.auditType'); // 用来定位审核下拉框选项的值
         $auditstyle =I('post.auditstyle'); //用来区别“pass”，“denied”审核请求
		//  print_r($tablenames);
		//   print_r($ids); exit;
         if($auditstyle == 'pass'){ //批量通过部分
			  // 自动启动事务支持
                 $this->startTrans();
			foreach($tablenames as $k=>$v){
				//  echo aa;
                if($v == 'admin_project'){
					 $ret = M('admin_project')->where(array('id'=>$ids[$k]))->save(array('type'=>0,'audit_time'=>time()));
                     // 发生错误自动回滚事务
                     if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！',
					
                      );
					  return $res;
					 }
				}else if($v == 'course'){
					 $ret = M('course')->where(array('id'=>$ids[$k]))->save(array('status'=>1,'audit_time'=>time()));
                     if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }
				}else if($v == 'examination'){
					// echo aa;
					 $ret = M('examination')->where(array('id'=>$ids[$k]))->save(array('status'=>1,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'survey'){
					 $ret = M('survey')->where(array('id'=>$ids[$k]))->save(array('status'=>1,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'friends_circle'){
					 $ret = M('friends_circle')->where(array('id'=>$ids[$k]))->save(array('status'=>1,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'research'){
					 $ret = M('research')->where(array('id'=>$ids[$k]))->save(array('audit_state'=>1,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'test'){
					 $ret = M('test')->where(array('id'=>$ids[$k]))->save(array('audit_status'=>0,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }      
				}else if($v == 'integration_apply'){
					 $ret = M('integration_apply')->where(array('id'=>$ids[$k]))->save(array('status'=>1,'audit_time'=>time()));
                         if ($ret === false) {
                        $this->rollback();
                        $res = array(
                        'status'=>0,
                        'info'=>'系统发生错误！'
                        );
                        return $res;
                        }  
                     //审核通过，申请加分记录插入积分记录表
                     $dataone = M('integration_apply')->where(array('id'=>$ids[$k]))->find();
                     $arr = array(
                         'time'=>time(),
                         'user_id'=>$dataone['user_id'],
                         'score'=>$dataone['add_score'],
                         'type'=>'申请加分',
                         'describe'=>'申请加分-'.$dataone['apply_title'],
                         'apply_id'=>$dataone['id'],
                     );
					 
					 if(strtolower(C('DB_TYPE')) == 'oracle'){
						$arr['id'] = getNextId('integration_record');
					}
					 
                     $ret1 = M('integration_record')->add($arr);
                         if ($ret1 === false) {
                        $this->rollback();
                        $res = array(
                        'status'=>0,
                        'info'=>'系统发生错误！'
                        );
                        return $res;
                        }  

				}else if($v == 'users'){
					 $ret = M('users')->where(array('id'=>$ids[$k]))->save(array('status'=>1,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }      
				}
				   
            }
			// 提交事务
            $this->commit();
            $res = array(
                     'status'=>1,
                     'info'=>'批量审核通过成功',
					  'url'=>U('Admin/Audit/auditlist',array('auditType'=>$auditType,'auditForword'=>1))
                      );
             return $res;

		 }else{
            //批量拒绝部分
			  // 自动启动事务支持
                 $this->startTrans();
			foreach($tablenames as $k=>$v){
				//  echo aa;
                if($v == 'admin_project'){
					 $ret = M('admin_project')->where(array('id'=>$ids[$k]))->save(array('type'=>3,'audit_time'=>time()));
                     // 发生错误自动回滚事务
                     if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！',
					
                      );
					  return $res;
					 }
				}else if($v == 'course'){
					 $ret = M('course')->where(array('id'=>$ids[$k]))->save(array('status'=>2,'audit_time'=>time()));
                     if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }
				}else if($v == 'examination'){
					// echo aa;
					 $ret = M('examination')->where(array('id'=>$ids[$k]))->save(array('status'=>3,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'survey'){
					 $ret = M('survey')->where(array('id'=>$ids[$k]))->save(array('status'=>3,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'friends_circle'){
					 $ret = M('friends_circle')->where(array('id'=>$ids[$k]))->save(array('status'=>2,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'research'){
					 $ret = M('research')->where(array('id'=>$ids[$k]))->save(array('audit_state'=>2,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }                                    
				}else if($v == 'test'){
					 $ret = M('test')->where(array('id'=>$ids[$k]))->save(array('audit_status'=>2,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }      
				}else if($v == 'integration_apply'){
					 $ret = M('integration_apply')->where(array('id'=>$ids[$k]))->save(array('status'=>2,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }      
				}else if($v == 'users'){
					 $ret = M('users')->where(array('id'=>$ids[$k]))->save(array('status'=>0,'audit_time'=>time()));
                      if ($ret === false) {
					 $this->rollback();
                     $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
					  return $res;
					 }      
				}
				   
            }
			// 提交事务
            $this->commit();
            $res = array(
                     'status'=>1,
                     'info'=>'批量拒绝成功',
					  'url'=>U('Admin/Audit/auditlist',array('auditType'=>$auditType,'auditForword'=>1))
                      );
             return $res;


		 }
      


   }

     /** 
     *所有审核的详情展示
     */
     public function allauditdetail()
     {
        $tablename =  I('get.tablename');
        $id =  I('get.id');        
        if($tablename == 'admin_project'){
              $audit = M('admin_project');
                if(IS_GET){
                $get  = $id;

              $res1 = $audit->field('a.id as aid,a.*,b.*')->alias('a')
                            ->join(' LEFT JOIN __USERS__ b  on b.id = a.user_id')
                            ->where("a.id = $get")
                            ->find();
             //项目指定人员
              $persons =  $audit->field('c.username')->alias('a')
                            ->join('LEFT JOIN __DESIGNATED_PERSONNEL__ b on a.id = b.project_id')
                    ->join('LEFT JOIN __USERS__ as c on b.user_id = c.id')
                    ->where(array('a.id'=>$get))
                    ->select();
              $res1['uname'] = $persons;         

              //项目下的课程详情
                $res2 = $audit->field('b.*')->alias('a')
                      ->field('c.course_name,c.course_way,c.lecturer,c.lecturer_name,d.name,b.start_time,b.end_time,b.credit,b.location')
                          ->join(' LEFT JOIN __PROJECT_COURSE__ b  on a.id = b.project_id ')
                  ->join(' LEFT JOIN __COURSE__ c  on b.course_id = c.id ')
                  ->join(' LEFT JOIN __LECTURER__ d  on c.lecturer = d.id ')
                          ->where("a.id = $get")
                          ->select();

                $coursedesc = $res2;

            //项目下的考试详情
                $res2 = $audit->alias('a')
                      ->field('b.cacheid,b.test_names,c.test_mode,d.username,b.start_time,b.end_time,b.credits,b.test_length%1440 as test_length')
                          ->join(' LEFT JOIN __PROJECT_EXAMINATION__ b  on a.id = b.project_id ')
                  ->join(' LEFT JOIN __EXAMINATION__ c  on b.test_id = c.id ')
                  ->join(' LEFT JOIN __USERS__ d  on b.manager_id = d.id ')
                          ->where("a.id = $get")
                          ->select();
              $examdesc = $res2;

              //项目下的调研详情
              $res2 = $audit->alias('a')
                      ->field('c.survey_name,d.username,b.start_time,b.end_time,b.credit')
                          ->join(' LEFT JOIN __PROJECT_SURVEY__ b  on a.id = b.project_id ')
                  ->join(' LEFT JOIN __SURVEY__ c  on b.survey_id = c.id ')
                  ->join(' LEFT JOIN __USERS__ d  on b.manager_id = d.id ')
                          ->where("a.id = $get")
                          ->select();
            $surveydesc = $res2;



                    //指定部门
                    if(!empty($res1['tissue_id'])){

                        $tissue_id = unserialize($res1['tissue_id']);

                        if(!empty($tissue_id)){

                            $where['id'] = array("in",$tissue_id);

                            $department = M('tissue_rule')->field('name')->where($where)->select();

                            foreach($department as $item){

                                $str_name[] = $item['name'];

                            }

                            if(!empty($str_name)){
                                $department = implode(",",$str_name);
                            }else{
                                $department = '';
                            }

                        }else{
                            $department = "";
                        }

                    }else{
                        $department = "";
                    }



                $data = array(
              'res1'=>$res1,
              'coursedesc'=>$coursedesc,
              'examdesc'=>$examdesc,
              'surveydesc'=>$surveydesc,
               'department'=>$department

                );
            
                }
              return $data; 
        }else if($tablename == 'course'){
              $coursem = M('course');
              $where = array(
                    'a.id' => $id,        
                );
              
              /* 课程详情逻辑 */
              $res = $coursem->field('a.id,a.chapter,a.course_name,d.name,a.course_cover,c.cat_name,a.status,a.course_time,a.course_way,a.maker,a.lecturer_name,b.username,a.create_time,e.course_aim,e.course_outline')
                            ->alias('a')
                            ->join('left join __USERS__ b on a.user_id=b.id')
                            ->join('left join __COURSE_CATEGORY__c on a.course_cat_id=c.id')
                    ->join('left join __LECTURER_ d on a.lecturer=d.id')
                    ->join('left join __COURSE_DETAIL__ e on a.id=e.id')
                    ->where($where)
                    ->find();
              $res['chapter'] = json_decode($res['chapter'],true);

             //面授课，讲师为线上讲师（内部讲师，或外部讲师），关联讲师表取出讲师姓名
            
              if($res['course_way'] == 1){
              $lecturer = $coursem->field('b.name,b.user_id,b.type')
                              ->alias('a')
                                ->join('left join __LECTURER__ b on a.lecturer=b.id')
                      ->where($where)
                          ->find();
                if($lecturer['type'] == 1){
                          $lecturername = $lecturer['name']; 
                }elseif($lecturer['type'] == 0){
                  $lecturername = M('users')->where(array('id'=>$lecturer['user_id']))->getField('username');
                }
              $res['lecturer_name'] =  $lecturername;
              }
              
                //  print_r($res['chapter']);
                return $res;
        }else if($tablename == 'examination'){
                   
        }else if($tablename == 'survey'){
                $coursem = M('survey');
                    $where = array(
                          'a.id' => $id,        
                      );
                $res = $coursem->field('a.id,a.status,a.survey_name,a.survey_score,c.username as survey_heir,a.survey_upload_time,b.cat_name')
                                ->alias('a')
                                ->join('left join __SURVEY_CATEGORY__ b on a.survey_cat_id=b.id')
                                ->join('left join __USERS__ c on a.survey_heir=c.id ')
                                ->where($where)
                                ->find();
                  /* 问卷详情逻辑,取问卷item */
                  $question = M('survey_item'); 
                    $where = array(
                      'b.id'=>$id
                    );

                  $surveyitem = $question->field('a.*')
                                ->alias('a')
                                ->join('left join __SURVEY__ b on a.survey_id=b.id')
                                ->where($where)
                                ->order('classification asc,id asc')
                                ->select();
                  $data = array(
                  'res'=>$res,
                  'surveyitem'=>$surveyitem
                    );
                  return $data;
        }else if($tablename == 'friends_circle'){
                //话题审核详情
                $data = M('friends_circle')
			->alias('a')
			->field('a.*,b.username,"话题审核" as auditTitle')
                        ->join('left join __USERS__ b on b.id=a.user_id')
                        ->where(array('a.id'=>$id))
                        ->find();
                return $data;               
        }else if($tablename == 'research'){
                  //获取发起考试详情的头部信息
                $title = M('research')
                      ->alias('a')
                      ->join('left join __USERS__ b on b.id=a.create_user')
                      ->join('left join __RESEARCH_TISSUEID__ c on c.research_id=a.id')
                      ->join('left join __TISSUE_RULE__ d on d.id=c.tissue_id')
                      ->where(array('a.id'=>$id))
                      ->field('a.id,a.research_name,a.survey_id,a.credits,b.username,a.create_time,a.start_time,a.end_time,a.audit_state,d.name')
                      ->select();
                  //  print_r($title); 
    
                  $survey_id = $title[0]['survey_id'];
                  //调研类别
                  $cat_name = M('survey a')->field("b.cat_name")->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")->where(array("a.id"=>$survey_id))->find();

                  //调研题题目
                  $survey_item = M('survey_item ')->where("survey_id = ".$survey_id)->select();

                  //单选题,多选题，描述题
                  $detail = array();

                  foreach($survey_item as $item){
                      if($item['classification'] == 1){
                          $detail['radio_list'][] = $item;
                      }elseif($item['classification'] == 2){
                          $detail['multiselect_list'][] = $item;
                      }else{
                          $detail['describe_list'][] = $item;
                      }
                  }

                $data = array(
                  'title'=>$title,
                  'detail'=>$detail
                );
                return  $data;
          }else if($tablename == 'test'){
          //获取发起考试详情的头部信息
          $title = M('test')
                      ->alias('a')
                      ->join('left join __USERS__ b on b.id=a.create_user')
                      ->join('left join __EXAMINATION__ c on c.id=a.examination_id')
                      ->where(array('a.id'=>$id))
                      ->field('a.id,a.name,a.examination_id,a.type,a.score,c.test_score,b.username,a.create_time,a.start_time,a.end_time,a.audit_status,a.address')
                      ->find();
          //获取发起考试详情的的指定人员
          $designee = M('test')
                      ->alias('a')
                      ->join('left join __TEST_USER_REL__ b on b.test_id=a.id')
                      ->join('left join __USERS__ c on c.id=b.user_id')
                      ->where(array('a.id'=>$id))
                      ->field('c.username as designee')
                      ->select();
            // foreach($designees as $k=>$v){
            //   $designee = $designee.'  '.$v['designee'];
            // }
            //试卷展示 
          //  print_r($res); 
          //  print_r($designee); 
            if($title['type'] == 0){
              //判断为线上考试： 获取试卷详情
              $testdetail = D('TestManage')->getExamDetail($id);
            }else{
              $testdetail = '此发起考试为线下考试';
            }
            // print_r($data); 
            $data = array(
                'title'=>$title,
                'designee'=>$designee,
                'testdetail'=>$testdetail
            );
            return $data;
        }else if($tablename == 'integration_apply'){
             $data = M('integration_apply')
	            ->alias('a')
		    ->field('a.*,b.username')
                    ->join('left join __USERS__ b on b.id=a.user_id')
                    ->where(array('a.id'=>$id))
                    ->find();
              if(end(explode('.', $data['attachment'])) == 'doc' || end(explode('.', $data['attachment'])) == 'docx' ){
                $data['type'] = 2;
                $data['docmentName'] = end(explode('/', $data['attachment']));
              }else{
                $data['type'] = 1;
              }
             return $data;
        }else if($tablename == 'users'){
              $data = M('users')->where(array('id'=>$id))->find();
              return $data;             
        }

     }


















  //------------------------  一期代码   -----------------------//





	  /**
     * 计算考试时长
     */
    public function getExamTimeLong($start_time,$end_time){
    
        //把格式化日期转化为UINX时间戳
        $start = strtotime($start_time);//开始时间
        $end = strtotime($end_time);//结束时间
        //计算时间长（分钟）
        $timeLong = floor(($end - $start)%86400/60);
        return $timeLong;
    }




	/**
	 *项目审核列表
	 */
   public function projectAuditList1(){
        $s = $_SESSION['user'];
          // session(null);
          // print_r($s);exit;
          $size = 15;
        
         $p1 = $_GET['p1'] ?  $_GET['p1'] : 1 ;
         $p2 = $_GET['p2'] ?  $_GET['p2'] : 1 ;
         $p3 = $_GET['p3'] ?  $_GET['p3'] : 1 ;
          
          $audit = M('admin_project'); 
        //   $value = session('user_id');  $_SESSION['user']['id'] //接收用户登录的session
          $value = 88; //调试用
          $type = array(2,0,3); //0表示进行中，1表示草稿，2表示待审核，3表示拒绝，4表示已完成项目
          foreach($type as $k=>$v){
              $k = $k + 1;
              $where = array(
              'type' => $v,
            //   'user_id'  => $value,
              );
     
		      if($k == 1){ 
                 $p = $p1;
	          	}else if($k == 2){
                 $p = $p2;
		       }else if($k == 3){
                 $p = $p3;
	        	}
          $list[] = $audit->field('a.id,a.project_name,a.class_name,a.add_time,b.username')->alias('a')
                  ->join(' LEFT JOIN __USERS__ b  on b.id = a.user_id')
				  ->order('id desc')
                  ->where($where)
                  ->page($p.','.$size)
                  ->select();  
        
          $count = $audit->field('a.id,a.project_name,a.class_name,a.add_time,b.username')->alias('a')
                  ->join(' LEFT JOIN __USERS__ b  on b.id = a.user_id')
                  ->where($where)
                  ->count(); 
            $show[] = tabPage($count,$size,'p'.$k,$k);
   
          } 
		  $data = array(
			    '0'=>$list,
			    '1'=>$show
		       );
		  return $data;
   }
	/**
	 *项目审核详情
	 */
   public function projectAuditdesc(){
	   $audit = M('admin_project');
        if(IS_GET){
       $get  = I('id') ? I('id') : 0; 
    //    $where['id'] = $get;
    //    $res = $audit->where($where)->select();
       $res1 = $audit->field('a.id as aid,a.*,b.*')->alias('a')
                     ->join(' LEFT JOIN __USERS__ b  on b.id = a.user_id')
                     ->where("a.id = $get")
                     ->find();
		//项目指定人员
	   $persons =  $audit->field('c.username')->alias('a')
		                 ->join('LEFT JOIN __DESIGNATED_PERSONNEL__ b on a.id = b.project_id')
						 ->join('LEFT JOIN __USERS__ c on b.user_id = c.id')
						 ->where(array('a.id'=>$get))
						 ->select();
	  	$res1['uname'] = $persons;         
    //    print_r($res1); 
        //   print_r($persons);
      //项目下的课程详情
         $res2 = $audit->field('b.*')->alias('a')
		          ->field('c.course_name,c.course_way,c.lecturer,c.lecturer_name,d.name,b.start_time,b.end_time,b.credit,b.location')
                  ->join(' LEFT JOIN __PROJECT_COURSE__ b  on a.id = b.project_id ')
				  ->join(' LEFT JOIN __COURSE__ c  on b.course_id = c.id ')
				  ->join(' LEFT JOIN __LECTURER__ d  on c.lecturer = d.id ')
                  ->where("a.id = $get")
                  ->select();

         $coursedesc = $res2;

       /*
       
        $res2 = $audit->field('b.*')->alias('a')
                  ->join(' LEFT JOIN __PROJECT_COURSE__ b  on a.id = b.project_id ')
                  ->where("a.id = $get")
                  ->find();
       if(!empty($res2['course_id'])){
          $course_ids  = explode(",",$res2['course_id']);  //课程ids array
          $specific_informations = $res2['specific_information']; 
          $specific_informations = json_decode($specific_informations, true); //课程ids对应的描述数组
        $course = M('course');
       foreach($course_ids as $k=>$v){
             $tmp1 = $course->alias('a')
                            ->join(' LEFT JOIN __LECTURER__ b  on a.lecturer = b.id ')
                            ->where("a.id = $v")
                            ->field('*')
                            ->find();    

             if(array_key_exists($v, $specific_informations)){
               $tmp2 =  $specific_informations[$v] ;
             }else{
               $tmp2 = array();
             }
            $coursedesc[] = array_merge($tmp1,$tmp2);
        }
       }
	   */
        // print_r($coursedesc);

	  //项目下的考试详情
        $res2 = $audit->alias('a')
		          ->field('b.test_names,c.test_mode,d.username,b.start_time,b.end_time,b.credits,b.test_length%1440 as test_length')
                  ->join(' LEFT JOIN __PROJECT_EXAMINATION__ b  on a.id = b.project_id ')
				  ->join(' LEFT JOIN __EXAMINATION__ c  on b.test_id = c.id ')
				  ->join(' LEFT JOIN __USERS__ d  on b.manager_id = d.id ')
                  ->where("a.id = $get")
                  ->select();
		$examdesc = $res2;

	  /*
        $res2 = $audit->field('b.*')->alias('a')
                  ->join(' LEFT JOIN __PROJECT_EXAMINATION__ b  on a.id = b.project_id ')
                  ->where("a.id = $get")
                  ->find();
        if(!empty($res2['test_id'])){
           $test_ids  = explode(",",$res2['test_id']);  //试卷ids array
           $specific_informations = $res2['specific_information']; 
           $specific_informations = json_decode($specific_informations, true); //试卷ids对应的描述数组
        $exam = M('examination');
       foreach($test_ids  as $k=>$v){
        $tmp1 = $exam->where("id = $v")->find();    

        if(array_key_exists($v, $specific_informations)){
            $tmp2 =  $specific_informations[$v] ;
			$start_time =  $tmp2['start_time'];
			$end_time =  $tmp2['end_time'];
            $timeLong = $this->getExamTimeLong($start_time,$end_time);
			$tmp2['timeLong'] = $timeLong;
         }else{
            $tmp2 = array();
         }
        $examdesc[] = array_merge($tmp1,$tmp2);
        }
      }
	  */
    //    print_r($examdesc);

      //项目下的调研详情
	    $res2 = $audit->alias('a')
		          ->field('c.survey_name,d.username,b.start_time,b.end_time,b.credit')
                  ->join(' LEFT JOIN __PROJECT_SURVEY__ b  on a.id = b.project_id ')
				  ->join(' LEFT JOIN __SURVEY__ c  on b.survey_id = c.id ')
				  ->join(' LEFT JOIN __USERS__ d  on b.manager_id = d.id ')
                  ->where("a.id = $get")
                  ->select();
		$surveydesc = $res2;
	  /*
        $res2 = $audit->field('b.*')->alias('a')
                  ->join(' LEFT JOIN __PROJECT_SURVEY__ b  on a.id = b.project_id ')
                  ->where("a.id = $get")
                  ->find();
        if(!empty($res2['survey_id'])){
          $survey_ids  = explode(",",$res2['survey_id']);  //问卷ids array
          $specific_informations = $res2['specific_information']; 
          $specific_informations = json_decode($specific_informations, true); //问卷ids对应的描述数组
        $survey = M('survey');
       foreach($survey_ids as $k=>$v){
          $tmp1 = $survey->where("id = $v")->find();    
        // print_r($tmp1);
        if(array_key_exists($v, $specific_informations)){
          $tmp2 =  $specific_informations[$v] ;
        }else{
          $tmp2 = array();
        }
        $surveydesc[] = array_merge($tmp1,$tmp2);
        // print_r(array_merge($tmp1,$tmp2));  
          }
        }
		*/

        $data = array(
			'res1'=>$res1,
			'coursedesc'=>$coursedesc,
			'examdesc'=>$examdesc,
			'surveydesc'=>$surveydesc
	    	);
		
 	}
	 return $data;
   } 
       
	    
    /**
	 *课程审核列表
	 */
	public function courseAuditlist(){
		 $size = 15;
         $p1 = $_GET['p1'] ?  $_GET['p1'] : 1 ;
         $p2 = $_GET['p2'] ?  $_GET['p2'] : 1 ;
         $p3 = $_GET['p3'] ?  $_GET['p3'] : 1 ;

       $status = array(0,1,2);  //0表示待审核，1表示已通过，2表示已拒绝
       foreach($status as $k=>$v){
         $where = array(
              'a.status' => $v,        
          );
		$k = $k + 1;

		   if($k == 1){ 
              $p = $p1;
	       	}else if($k == 2){
              $p = $p2;
		    }else if($k == 3){
              $p = $p3;
	     	}
          $list[] = $this->field('a.id,a.course_name,c.cat_name,a.course_time,a.is_public,a.course_way,b.username,a.create_time')
		                ->alias('a')
		                ->join('left join __USERS__ b on a.user_id=b.id')
		                ->join('left join __COURSE_CATEGORY__ c on a.course_cat_id=c.id')
						->where($where)
						->order('id desc')
						->page($p.','.$size)
						->select();
		
       $count = $this->field('a.id,a.course_name,c.cat_name,a.course_time,a.is_public,a.course_way,b.username,a.create_time')
		                ->alias('a')
		                ->join('left join __USERS__ b on a.maker=b.id')
		                ->join('left join __COURSE_CATEGORY__ c on a.course_cat_id=c.id')
						->where($where)
						->count();
      $show[] = tabPage($count,$size,'p'.$k,$k);


	   }	
		$data = array(
			'0'=>$list,
			'1'=>$show
		);	
		return $data;

	}


	/**
	 *课程审核详情
	 */
	public function coursedesc($id){

	    $coursem = M('course');
        $where = array(
              'a.id' => $id,        
          );
		  
		  /* 课程详情逻辑 */
		$res = $coursem->field('a.id,a.chapter,a.course_name,d.name,a.course_cover,c.cat_name,a.status,a.course_time,a.course_way,a.maker,a.lecturer_name,b.username,a.create_time,e.course_aim,e.course_outline')
		                ->alias('a')
		                ->join('left join __USERS__ b on a.user_id=b.id')
		                ->join('left join __COURSE_CATEGORY__ c on a.course_cat_id=c.id')
						->join('left join __LECTURER__ d on a.lecturer=d.id')
						->join('left join __COURSE_DETAIL__ e on a.id=e.id')
						->where($where)
						->find();
	     $res['chapter'] = json_decode($res['chapter'],true);

		 //面授课，讲师为线上讲师（内部讲师，或外部讲师），关联讲师表取出讲师姓名
		
		if($res['course_way'] == 1){
		 $lecturer = $coursem->field('b.name,b.user_id,b.type')
			                 ->alias('a')
		                     ->join('left join __LECTURER__ b on a.lecturer=b.id')
							 ->where($where)
					         ->find();
				if($lecturer['type'] == 1){
                   $lecturername = $lecturer['name']; 
				}elseif($lecturer['type'] == 0){
				   $lecturername = M('users')->where(array('id'=>$lecturer['user_id']))->getField('username');
				}
			$res['lecturer_name'] =  $lecturername;
		  }
		  
        //  print_r($res['chapter']);
         return $res;
	}


      /**
	 *试卷审核列表
	 */
	public function examAuditlist(){
		 $size = 15;
         $p1 = $_GET['p1'] ?  $_GET['p1'] : 1 ;
         $p2 = $_GET['p2'] ?  $_GET['p2'] : 1 ;
         $p3 = $_GET['p3'] ?  $_GET['p3'] : 1 ;

       $status = array(0,1,3);  //0表示待审核，1表示已通过，3表示已拒绝
	   $exam = M('examination');
       foreach($status as $k=>$v){
		 $k = $k + 1;
         $where = array(
              'a.status' => $v,        
          );
        // $p = $p.$k;
		if($k == 1){ 
           $p = $p1;
		}else if($k == 2){
           $p = $p2;
		}else if($k == 3){
           $p = $p3;
		}
		$list[] = $exam->field('a.id,a.test_name,b.cat_name,a.test_score,a.test_heir,a.test_upload_time')
		                ->alias('a')
		                // ->join('left join users as b on a.maker=b.id')
		                ->join('left join __EXAMINATION_CATEGORY__ b on a.test_cat_id=b.id')
						->where($where)
						->order('id desc')
						->page($p.','.$size)
						->select();
		 $count = $exam->field('a.id,a.test_name,b.cat_name,a.test_score,a.test_heir,a.test_upload_time')
		                ->alias('a')
		                // ->join('left join users as b on a.maker=b.id')
		                ->join('left join __EXAMINATION_CATEGORY__ b on a.test_cat_id=b.id')
						->where($where)
						->count();
		$show[] = tabPage($count,$size,'p'.$k,$k);
	   }

       $data = array(
			'0'=>$list,
			'1'=>$show
		);	
		return $data;
	}

	 /**
	 *试卷审核详情
	 */
	public function examdesc($id){
       
	    $exam = M('examination');
		 /* 试卷详情逻辑 */

         /*各种试题的题目*/ 
		$type = array(1,2,3); //试题分类 1表示单选择题 2表示多选 3判断题
		foreach($type as $v){
            $where = array(
               'a.id' => $id,   
			   'b.classification' => $v     
            );
			$res[] = $exam->field('a.id,b.*')
		                ->alias('a')
		                // ->join('left join users as b on a.maker=b.id')
		                ->join('left join __EXAMINATION_ITEM__ b on a.id=b.examination_id')
						->where($where)
						->select();
	         	}

			
           	/*试卷的头部信息*/         
		   $where = array(
               'a.id' => $id,        
            );
		  $res[] = $exam->field('a.id,a.status,a.test_name,b.cat_name,a.test_score,a.test_heir,a.test_upload_time')
		                ->alias('a')
		                // ->join('left join users as b on a.maker=b.id')
		                ->join('left join __EXAMINATION_CATEGORY__ b on a.test_cat_id=b.id')
						->where($where)
						->find();		
        
			/*试卷的分数及题目数统计信息*/   
		    $type = array(1,2,3); //试题分类 1表示单选择题 2表示多选 3判断题
	    	foreach($type as $v){
            $where = array(
               'a.id' => $id,   
			   'b.classification' => $v     
            );
		    $res[] = $exam->field('count(*) as totalitem,sum(item_score) as totalscore')
		                ->alias('a')
		                // ->join('left join users as b on a.maker=b.id')
		                ->join('left join __EXAMINATION_ITEM__ b on a.id=b.examination_id')
						->where($where)
						->select();
		}
	    
         return $res;
	}

	
	/**
	 *问卷审核列表
	 */
	public function questionAuditlist(){
		 $size = 15;
         $p1 = $_GET['p1'] ?  $_GET['p1'] : 1 ;
         $p2 = $_GET['p2'] ?  $_GET['p2'] : 1 ;
         $p3 = $_GET['p3'] ?  $_GET['p3'] : 1 ;
       $status = array(0,1,3);  //0表示待审核，1表示已通过，3表示已拒绝
	   
	   $exam = M('survey');
       foreach($status as $k=>$v){
		   $k = $k + 1;
         $where = array(
              'a.status' => $v,        
          );
		/* 分页逻辑 */
		if($k == 1){
           $p = $p1;
		}else if($k == 2){
           $p = $p2;
		}else if($k == 3){
           $p = $p3;
		}
		$list[] = $exam->field('a.id,a.survey_name,b.cat_name,c.username as survey_heir,a.survey_upload_time')
		                ->alias('a')
		                // ->join('left join users as b on a.maker=b.id')
		                ->join('left join __SURVEY_CATEGORY__ b on a.survey_cat_id=b.id')
						->join('left join __USERS__ c on a.survey_heir=c.id ')
						->where($where)
						->order('id desc')
						->page($p.','.$size)
						->select();
		$count = $exam->field('a.id,a.survey_name,b.cat_name,a.username,a.survey_upload_time')
		                ->alias('a')
		                ->join('left join __SURVEY_CATEGORY__ b on a.survey_cat_id=b.id')
						->join('left join __USERS__ c on a.survey_heir=c.id ')
						->where($where)
						->count();
		$show[] = tabPage($count,$size,'p'.$k,$k); //调用分页函数
	   }

      $data = array(
			'0'=>$list,
			'1'=>$show
		);	
		return $data;
	}

	 /**
	  *问卷审核详情
	  */
    public function questiondesc(){
		$id = I('get.id');
		$coursem = M('survey');
        $where = array(
              'a.id' => $id,        
          );
		$res = $coursem->field('a.id,a.status,a.survey_name,a.survey_score,c.username as survey_heir,a.survey_upload_time,b.cat_name')
		                ->alias('a')
		                ->join('left join __SURVEY_CATEGORY__ b on a.survey_cat_id=b.id')
						->join('left join __USERS__ c on a.survey_heir=c.id ')
						->where($where)
						->find();
	     /* 问卷详情逻辑,取问卷item */
	    $question = M('survey_item'); 
        $where = array(
          'b.id'=>$id
         );

        $surveyitem = $question->field('a.*')
		                ->alias('a')
		                ->join('left join __SURVEY__ b on a.survey_id=b.id')
					    ->where($where)
                        ->order('classification asc,id asc')
					    ->select();
      $data = array(
			'res'=>$res,
			'surveyitem'=>$surveyitem
		);
      return $data;
	}


}