<?php 

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 审核管理控制器
 */
class AuditController extends AdminBaseController{


    /** 
     *审核设置列表
     */
     public function setList()
     {
       $list = D('Audit')->setList();
       $this->assign('list',$list);
       $this->display('auditset_list');

     }   

    // /** 
    //  *审核设置列表
    //  */
    //  public function aa()
    //  {
    //  $id = I('pid');
    //  $res = D('Audit')->projectResubmit($id,1);
    //  if($res){
    //    echo 'ok';
    //  }else{
    //    echo 'shibai';
    //  } 
    //  }
     /** 
     *审核的设置
     */
     public function set()
     { 
       
       $type = I('get.type');
      
       $detailData = D('Audit')->setDetail($type);
      //  DUMP($detailData);
       $roles = $this->roleOption();
       $conditions = $this->conditionOption($type);
      //  dump($roles);
       $this->assign('detailData',$detailData);
       $this->assign('conditions',$conditions);
       $this->assign('type',$type);
       $this->assign('roles',$roles);
       $this->display('auditset_detail');

     }

     /** 
     *审核设置的角色选择列表
     */
     public function roleOption()
     {
       
       $roles = M('auth_group')->where(array('id'=>array('neq',3)))->select();
       return $roles;
     }  

     /** 
     *审核设置的条件选择列表
     */
     public function conditionOption($type)
     {   
       $res = D('Audit')->conditionOption($type);
       return $res;
     }  

     /** 
     *审核设置的保存
     */
     public function save()
     {
       
       $type = I('type');
       $res = D('Audit')->saveSet();
       if(!$res){
         
         $this->error(D('Audit')->getError(),U("Admin/Audit/set/type/$type"));
       }else{
         $this->success('保存成功',U('Admin/Audit/setList'));
       }
     }

     /** 
     *项目审核列表
     */
     public function projectauditlist()
     {  

        $data = D('Audit')->threeallauditlist(1);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('project_list');
     }

    /** 
     *课程审核列表
     */
     public function courseauditlist()
     {  

        $data = D('Audit')->threeallauditlist(2);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('course_list');
     }

    /** 
     *试卷审核列表
     */
     public function examinationauditlist()
     {  

        $data = D('Audit')->threeallauditlist(3);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('exam_list');
     }

    /** 
     *问卷审核列表
     */
     public function questionauditlist()
     {  

        $data = D('Audit')->threeallauditlist(4);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('question_list');
     }


    /** 
     *话题审核列表
     */
     public function topicauditlist()
     {  

        $data = D('Audit')->threeallauditlist(5);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('topic_list');
     }

    /** 
     *调研审核列表
     */
     public function researchauditlist()
     {  

        $data = D('Audit')->threeallauditlist(6);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('research_list');
     }

    /** 
     *考试审核列表
     */
     public function testauditlist()
     {  

        $data = D('Audit')->threeallauditlist(7);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('test_list');
     }

    /** 
     *加分申请-积分审核列表
     */
     public function applyauditlist()
     {  

        $data = D('Audit')->threeallauditlist(8);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('apply_list');
     }

    /** 
     *用户注册审核列表
     */
     public function usersauditlist()
     {  

        $data = D('Audit')->threeallauditlist(9);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('users_list');
     }
   
     /** 
     *话题小组审核列表
     */
     public function topicgrouplist()
     {  

        $data = D('Audit')->threeallauditlist(10);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('topicgroup_list');
     }


    /** 
     *加分申请-学分审核列表
     */
     public function creditsapplyauditlist()
     {  

        $data = D('Audit')->threeallauditlist(11);
        $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
         $this->assign('list1',$data['lists']);
         $this->assign('page1',$data['page']);
        }else if($tabType == 2){
         $this->assign('list2',$data['lists']);
         $this->assign('page2',$data['page']);
        }else if($tabType == 3){
         $this->assign('list3',$data['lists']);
         $this->assign('page3',$data['page']);
        }
        $this->assign('keyword',$data['keyword']);
        $this->display('credits_apply_list');
     }





    /** 
     *三期批量审核
     */
     public function threebatchAudit()
     {  
       
         $res = D('Audit')->threebatchAudit();
         $this->ajaxreturn($res);

     }




     /** 
     * 三期所有审核的详情展示
     */
     public function threeauditdetail()
     {
        
         $tablename =  I('get.tablename');
         $id =  I('get.id'); //接收各表的主键id  
         $audit_id =  I('get.audit_id'); //接收审核表的主键id  
         $audit_status =  I('get.audit_status'); //接收列表数据的审核状态       
         $data =  D('Audit')->threeallauditdetail(); 

        if($tablename == 'admin_project'){    
          // dump($_SESSION);
          $res1 = $data['res1'];
          $coursedesc = $data['coursedesc'];
          $examdesc = $data['examdesc'];
          $surveydesc = $data['surveydesc'];
          $this->assign('audit_id',$audit_id); //审核表的主键id 
          $this->assign('audit_status',$audit_status); //列表数据的审核状态 
          $this->assign('pdesc',$res1); //模板输出一维数组,详情头信息
          $this->assign('course',$coursedesc); //模板输出二维数组，详情课程
          $this->assign('exam',$examdesc); //模板输出二维数组，试卷课程
          $this->assign('survey',$surveydesc); //模板输出二维数组，问卷课程
          $this->assign('department',$data['department']);
          $this->display('threeproject_desc');

        }else if($tablename == 'course'){  
          $this->assign('cdesc',$data);
          $this->assign('audit_id',$audit_id); //审核表的主键id 
          $this->assign('audit_status',$audit_status); //列表数据的审核状态 
          $this->display('threecourse_desc');

        }else if($tablename == 'examination'){  
        $id = I('get.id');
        $_SESSION['exam_id'] = $id;
        $exam = D('ResourcesManage');
        $data = $exam->getExamDetail2($id);
        // dump($data);
        //详情
        $this->assign('xhr',$data['detail']);
        //单选
        $this->assign('singleChoice',$data['singleChoice']);
        $this->assign('singleChoiceSum',$data['singleChoiceSum']);
        $this->assign('singleChoiceTotalScore',$data['singleChoiceTotalScore']);
        //多选
        $this->assign('multipleChoice',$data['multipleChoice']);
        $this->assign('multipleChoiceSum',$data['multipleChoiceSum']);
        $this->assign('multipleChoiceTotalScore',$data['multipleChoiceTotalScore']);
        //判断
        $this->assign('descriPtive',$data['descriPtive']);
        $this->assign('descriPtiveChoiceSum',$data['descriPtiveChoiceSum']);
        $this->assign('descriPtiveChoiceTotalScore',$data['descriPtiveChoiceTotalScore']);

        //问答
        $this->assign('wd',$data['wd']);
        $this->assign('wdSum',$data['wdSum']);
        $this->assign('wdTotalScore',$data['wdTotalScore']);
        
        $this->assign('status',I('get.status'));
        
        $this->assign('audit_id',$audit_id); //审核表的主键id 
        $this->assign('audit_status',$audit_status); //列表数据的审核状态 
        $this->display('threeexam_desc');           
        }else if($tablename == 'survey'){  
         
            // $surveyitem = $data['surveyitem'];
            // // print_r($surveyitem);
            // $questionNaire = D('ResourcesManage');
            // $surveyData = $questionNaire->getQuestionNaireDetail($id);
        
            // $this->assign('base',$surveyData['base']);//问卷详情
            // $this->assign('list',$surveyData['list']);//问卷题目
            // $this->assign('surveyitem',$surveyitem);  //输出问卷题目
            // $this->assign('qdesc',$data['res']);  
            // $this->assign('audit_id',$audit_id); //审核表的主键id 
            // $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            // $this->display('threequestion_desc');

           
            /** 三期代码**/
            $get = I("get.");
            $suvId = (int)$get["id"];
            if($suvId < 1){
              exit("非法操作，缺少问卷id");
            }
            
            $detail = D("RsSurvey")->detail($suvId);
            $this->assign($detail);
            $this->assign('qdesc',$data['res']); 
            
            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            $this->display("threequestion_desc");

           /**  军强代码
            $id = I('get.id');
            $_SESSION['questionNaire_id'] = $id;
            $questionNaire = D('ResourcesManage');
            $data = $questionNaire->getQuestionNaireDetail($id);
            session('questionNaire_name',$data['detail']['survey_name']);
            
            $this->assign('base',$data['base']);//详情
            $this->assign('list',$data['list']);//题目
            $this->assign('refused',I('get.refused'));
            $this->assign('status',I('get.status'));
            $this->display('threequestion_desc');
           **/

        }else if($tablename == 'friends_circle'){
          // print_r($data);
            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            $this->assign('data',$data);  
            $this->display('threetopic_desc');
            
        }else if($tablename == 'research'){
            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
		      	$this->assign('title',$data['title']);
            $this->assign('detail',$data['detail']);
            // dump($data['detail']);
            $this->display('threeresearch_desc');

        }else if($tablename == 'test'){
            //发起考试头部信息 和指定人员
            $this->assign('title',$data['title']); 
            $this->assign('designee',$data['designee']);

            // //详情
            // $this->assign('xhr',$data['detail']);
            //单选
            $this->assign('singleChoice',$data['testdetail']['singleChoice']);
            $this->assign('singleChoiceSum',$data['testdetail']['singleChoiceSum']);
            $this->assign('singleChoiceTotalScore',$data['testdetail']['singleChoiceTotalScore']);
            //多选
            $this->assign('multipleChoice',$data['testdetail']['multipleChoice']);
            $this->assign('multipleChoiceSum',$data['testdetail']['multipleChoiceSum']);
            $this->assign('multipleChoiceTotalScore',$data['testdetail']['multipleChoiceTotalScore']);
            //判断
            $this->assign('descriPtive',$data['testdetail']['descriPtive']);
            $this->assign('descriPtiveChoiceSum',$data['testdetail']['descriPtiveChoiceSum']);
            $this->assign('descriPtiveChoiceTotalScore',$data['testdetail']['descriPtiveChoiceTotalScore']);

            //问答
            $this->assign('wd',$data['testdetail']['wd']);
            $this->assign('wdSum',$data['testdetail']['wdSum']);
            $this->assign('wdTotalScore',$data['testdetail']['wdTotalScore']);
            $this->assign('testdetail',$data['testdetail']);
            $this->assign('address',$data['title']['address']);

            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            $this->display('threetest_desc');
        }else if($tablename == 'integration_apply'){
          //  print_r($data);
            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            $this->assign('data',$data);  
            $this->display('threeapply_desc');
        }else if($tablename == 'users'){
            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            $this->assign('data',$data);  
            $this->display('threeusers_desc');
        }else if($tablename == 'topic_group'){
            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            $this->assign('data',$data);  
            $this->display('threetopicgroup_desc');
        }else if($tablename == 'credits_apply'){
          //  print_r($data);
            $this->assign('audit_id',$audit_id); //审核表的主键id 
            $this->assign('audit_status',$audit_status); //列表数据的审核状态 
            $this->assign('data',$data);  
            $this->display('threecredits_apply_desc');
        }
        
      
     }










   //------------------------  二期代码   -----------------------//


     /** 
     *所有审核列表展示   
     */
     public function auditlist()
     {
  /*
   *memcached的用法示例:
   S('test','memcache');$test = S('test'); echo $test;
   S('doogie', ['sex' => '男', 'age' => 26], 3600);  
   dump(S('doogie'));  
   */
   
        $data = D('Audit')->allauditlist();
        // dump($data['list']);
         $tabType = I('get.tabType') !== '' ? I('get.tabType') : '1';
        if($tabType == 1){
          $this->assign('list1',$data['list']);
          $this->assign('page1',$data['page']);		       
        }else if($tabType == 2){
          $this->assign('list2',$data['list']);
          $this->assign('page2',$data['page']);	
        }else if($tabType == 3){
          $this->assign('list3',$data['list']);
          $this->assign('page3',$data['page']);	
        }
          $this->assign('auditType',$data['auditType']);
          $this->assign('keyword',$data['keyword']);
          $this->assign('list',$data['list']);
          $this->assign('page1',$data['page']);
          $this->display('auditlist');

     }
     
     /**
     *全部类型的批量审核
     */
     public function batchAudit()
     {
         $res = D('Audit')->batchAudit();
         $this->ajaxreturn($res);
     }

     /** 
     *所有审核的详情展示
     */
     public function auditdetail()
     {
        
         $tablename =  I('get.tablename');
         $id =  I('get.id');       
         $data =  D('Audit')->allauditdetail(); 

        if($tablename == 'admin_project'){    
          $res1 = $data['res1'];
          $coursedesc = $data['coursedesc'];
          $examdesc = $data['examdesc'];
          $surveydesc = $data['surveydesc'];
          $this->assign('pdesc',$res1); //模板输出一维数组,详情头信息
          $this->assign('course',$coursedesc); //模板输出二维数组，详情课程
          $this->assign('exam',$examdesc); //模板输出二维数组，试卷课程
          $this->assign('survey',$surveydesc); //模板输出二维数组，问卷课程
          $this->assign('department',$data['department']);
          $this->display('project_desc');

        }else if($tablename == 'course'){  
          $this->assign('cdesc',$data);
          $this->display('course_desc');

        }else if($tablename == 'examination'){  
        $id = I('get.id');
        $_SESSION['exam_id'] = $id;
        $exam = D('ResourcesManage');
        $data = $exam->getExamDetail2($id);
        // dump($data);
        //详情
        $this->assign('xhr',$data['detail']);
        //单选
        $this->assign('singleChoice',$data['singleChoice']);
        $this->assign('singleChoiceSum',$data['singleChoiceSum']);
        $this->assign('singleChoiceTotalScore',$data['singleChoiceTotalScore']);
        //多选
        $this->assign('multipleChoice',$data['multipleChoice']);
        $this->assign('multipleChoiceSum',$data['multipleChoiceSum']);
        $this->assign('multipleChoiceTotalScore',$data['multipleChoiceTotalScore']);
        //判断
        $this->assign('descriPtive',$data['descriPtive']);
        $this->assign('descriPtiveChoiceSum',$data['descriPtiveChoiceSum']);
        $this->assign('descriPtiveChoiceTotalScore',$data['descriPtiveChoiceTotalScore']);

        //问答
        $this->assign('wd',$data['wd']);
        $this->assign('wdSum',$data['wdSum']);
        $this->assign('wdTotalScore',$data['wdTotalScore']);
        
        $this->assign('status',I('get.status'));
   
        $this->display('exam_desc');           
        }else if($tablename == 'survey'){  
            $surveyitem = $data['surveyitem'];
            // print_r($surveyitem);
            $this->assign('surveyitem',$surveyitem);  //输出问卷题目
            $this->assign('qdesc',$data['res']);  
            $this->display('question_desc');

        }else if($tablename == 'friends_circle'){
          // print_r($data);
            $this->assign('data',$data);  
            $this->display('topic_desc');
            
        }else if($tablename == 'research'){
		      	$this->assign('title',$data['title']);
            $this->assign('detail',$data['detail']);
            // dump($data['detail']);
            $this->display('research_desc');

        }else if($tablename == 'test'){
            //发起考试头部信息 和指定人员
            $this->assign('title',$data['title']); 
            $this->assign('designee',$data['designee']);

            // //详情
            // $this->assign('xhr',$data['detail']);
            //单选
            $this->assign('singleChoice',$data['testdetail']['singleChoice']);
            $this->assign('singleChoiceSum',$data['testdetail']['singleChoiceSum']);
            $this->assign('singleChoiceTotalScore',$data['testdetail']['singleChoiceTotalScore']);
            //多选
            $this->assign('multipleChoice',$data['testdetail']['multipleChoice']);
            $this->assign('multipleChoiceSum',$data['testdetail']['multipleChoiceSum']);
            $this->assign('multipleChoiceTotalScore',$data['testdetail']['multipleChoiceTotalScore']);
            //判断
            $this->assign('descriPtive',$data['testdetail']['descriPtive']);
            $this->assign('descriPtiveChoiceSum',$data['testdetail']['descriPtiveChoiceSum']);
            $this->assign('descriPtiveChoiceTotalScore',$data['testdetail']['descriPtiveChoiceTotalScore']);

            //问答
            $this->assign('wd',$data['testdetail']['wd']);
            $this->assign('wdSum',$data['testdetail']['wdSum']);
            $this->assign('wdTotalScore',$data['testdetail']['wdTotalScore']);
            $this->assign('testdetail',$data['testdetail']);
            $this->assign('address',$data['title']['address']);
            $this->display('test_desc');
        }else if($tablename == 'integration_apply'){
          //  print_r($data);
            $this->assign('data',$data);  
            $this->display('apply_desc');
        }else if($tablename == 'users'){
            $this->assign('data',$data);  
            $this->display('users_desc');
        }
        
      
     }
     

      /** 
      *网页下载文件
      */
      public function file_download()
      {

        $id  = I('get.id');
        $type = I('get.type');
        if($type == 11){
           $filepath = M('credits_apply')->where(array('id'=>$id))->getField('attachment');
        }else{
          $filepath = M('integration_apply')->where(array('id'=>$id))->getField('attachment');
        }
        
        $filepath = '.'.$filepath;   //thinkphp查找文件路径是以入口文件为基准
        if(!file_exists($filepath)){
            echo "下载文件不存在！";
            exit;
        }
        
          header('Content-Description: File Transfer'); 
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename='.basename($filepath));//获取带有文件扩展名的文件名
          header('Content-Transfer-Encoding: binary');
          header('Expires: 0');
          header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
          header('Pragma: public');
          header('Content-Length: ' . filesize($filepath));//获取文件大小
          readfile($filepath);//输出文件
      
      }








   //------------------------  一期代码   -----------------------//





     /** 
     *项目审核列表展示   
     */
     public function audit(){
       $data = D('Audit')->projectAuditList();
		   $list = $data[0];
       $show = $data[1];
       foreach($data[0] as $k=>$v){
         $k = $k + 1;
         $k = 'audit'.$k;
         $this->assign($k,$v);  //audit1代表待审核，audit2代表已审核，audit3代表已拒绝
       }
       foreach($data[1] as $k=>$v){
         $k = $k + 1;
         $k = 'page'.$k;
         $this->assign($k,$v); //page1,page2,page3
       }
        
        // $this->assign('page'.$k,$show);
        $this->display('audit_item');
        
       }

     /**     
    *项目的查看详情
    */
    public function pdesc(){
       $data =  D('Audit')->projectAuditdesc();
       $res1 = $data['res1'];
       $coursedesc = $data['coursedesc'];
       $examdesc = $data['examdesc'];
       $surveydesc = $data['surveydesc'];
       $this->assign('pdesc',$res1); //模板输出一维数组,详情头信息
       $this->assign('course',$coursedesc); //模板输出二维数组，详情课程
       $this->assign('exam',$examdesc); //模板输出二维数组，试卷课程
       $this->assign('survey',$surveydesc); //模板输出二维数组，问卷课程
       $this->display('project_desc');
      }
    
    
   
   /**     
    *项目的删除
    */
    public function del(){
      $audit = M('admin_project'); 
       if(IS_GET){
       $get  = I('id');
       $arr = array(
         'type'=>0
       );  
      
       $where['id'] = array('eq',$get);
       $audit = M('admin_project'); 
       $res = $audit->where($where)->delete($arr);
       

    if($res === false){
      $this->error('删除失败');
     } else {
      $this->success('删除成功',U('Admin/Audit/audit'));
       }
       }
       
    }    

    
    /**     
     *课程审核列表展示     
     */
     public function course(){
       $list = D('Audit');  
       $data = $list->courseAuditlist();
      //  print_r($data); 
       foreach($data[0] as $k=>$v){
         $k = 'list'.$k;
         $this->assign($k,$v); //list0:待审核,list1:已通过，list3：已拒绝
       }
       foreach($data[1] as $k=>$v){
         $k = $k + 1;
         $k = 'page'.$k;
         $this->assign($k,$v); //page1,page2,page3
       }

       $this->display('audit_course');
     }

    /**     
     *课程审核的查看详情  
     */
    public function cdesc(){
      $id = I('get.id');
      $course = D('Audit');
      $res = $course->coursedesc($id);
      // echo time();
      // print_r($res);
      $this->assign('cdesc',$res);
      $this->display('course_desc');
    }
   

    /**     
     *试卷审核列表展示     
     */
     public function exam(){
       $data = D('Audit');  
       $data = $data->examAuditlist();
       foreach($data[0] as $k=>$v){
         $k = 'list'.$k;
         $this->assign($k,$v); //list0:待审核,list1:已通过，list3：已拒绝
       }
       foreach($data[1] as $k=>$v){
         $k = $k + 1;
         $k = 'page'.$k;
         $this->assign($k,$v); //page1,page2,page3
       }
       $this->display('audit_exam');
     }

     
     /**
     * 试卷审核的查看详情
     */ 
    public function edesc(){
       $id = I('get.id');
       $exam = D('Audit');
       $res = $exam->examdesc($id);
       // echo time();
      //  print_r($res);exit;
      // clog($res);
       foreach($res as $k=>$v){
          if($k == 0){
            $this->assign('singleChoice',$v);
          }else if($k == 1){
            $this->assign('multipleChoice',$v);
          }else if($k == 2){
            $this->assign('judge',$v);
          }else if($k == 3){
            $this->assign('edesc',$v);
          }else if($k == 4){
            $this->assign('singleChoicestatistics',$v);
          }else if($k == 5){
            $this->assign('multipleChoicestatistics',$v);
          }else if($k == 6){
            $this->assign('judgestatistics',$v);
          }
          
       }
      //  $this->assign('edesc',$res);
       $this->display('exam_desc');


    }





    /**     
     *问卷审核列表展示     
     */
     public function question(){
       $data = D('Audit');  
       $data = $data->questionAuditlist();
      //  print_r($list); 
       foreach($data[0] as $k=>$v){
         $k = 'list'.$k;
         $this->assign($k,$v); //list0:待审核,list1:已通过，list3：已拒绝
       }
       foreach($data[1] as $k=>$v){
         $k = $k + 1;
         $k = 'page'.$k;
         $this->assign($k,$v); //page1,page2,page3
       }
       $this->display('audit_question');
       
     
     }

    /**
     * 问卷审核的查看详情
     */   
    public function qdesc(){
      
      $question = D('Audit');
      $data = $question->questiondesc();
        // echo time();
      $res = $data['res'];
      $surveyitem = $data['surveyitem'];
      // print_r($surveyitem);
      $this->assign('surveyitem',$surveyitem);  //输出问卷题目
      $this->assign('qdesc',$res);  
      $this->display('question_desc');
    }


  
  

   /**
    *所有的审核 审核通过
    */
  
    public function pass(){
      // $audit = M('admin_project'); 
      /*
       if(IS_GET){
       $get  = I('id');

       $where['id'] = array('eq',$get);
       if(I('auditType') == 'item'){ 
            $audit = M('admin_project'); 
            $arr = array(
            'type'=>4
              );
        }else if(I('auditType') == 'course'){
             $audit = M('course'); 
             $arr = array(
             'status'=>1
               );

        }else if(I('auditType') == 'exam'){
             $audit = M('examination'); 
             $arr = array(
             'status'=>1
               );
        }else if(I('auditType') == 'question'){
             $audit = M('survey'); 
             $arr = array(
             'status'=>1
               );
        }  
        $res = $audit->where($where)->save($arr);

    if($res === false){
      $this->error('审核失败');
     }else if(I('auditType') == 'item'){
      $this->success('通过审核',U('Admin/Audit/audit'));
     }else if(I('auditType') == 'course') {
      $this->success('通过审核',U('Admin/Audit/course'));
     }else if(I('auditType') == 'exam') {
      $this->success('通过审核',U('Admin/Audit/exam'));
     }else if(I('auditType') == 'question') {
      $this->success('通过审核',U('Admin/Audit/question'));
     }


       }
       */
       if(IS_AJAX){
          $get  = I('id');
        
       $where['id'] = array('eq',$get);
       if(I('auditType') == 'item'){ //项目审核
            $audit = M('admin_project'); 
            $arr = array(
            'type'=>0
              );
              $map = array(
             'id'=>$get,   
             'type'=>2
              );
        }else if(I('auditType') == 'course'){//课程审核
             $audit = M('course'); 
             $arr = array(
             'id'=>$get,  
             'status'=>1
               );
             $map = array(
             'id'=>$get,  
             'status'=>0
               );
        }else if(I('auditType') == 'exam'){//试卷审核
             $audit = M('examination'); 
             $arr = array(
             'status'=>1
               );
             $map = array(
             'id'=>$get,  
             'status'=>0
              );
        }else if(I('auditType') == 'question'){//问卷审核
             $audit = M('survey'); 
             $arr = array(
            'id'=>$get,  
             'status'=>1
               );
             $map = array(
             'id'=>$get,  
             'status'=>0
               );
        }  
        $res = $audit->where($map)->find(); //查找待审核记录
        
        if($res){

        $res = $audit->where($where)->save($arr);
        if($res === false){ //审核失败
         $data['status'] = 0;  
         $data['info'] = '审核失败';  
         $data['url'] = "";
        }else if(I('auditType') == 'item'){  //项目审核
         $data['status'] = 1;  
         $data['info'] = '审核成功';  
         $data['url'] = U('Audit/audit'); 
        }else if(I('auditType') == 'course') { //课程审核
        
         $data['status'] = 1;  
         $data['info'] = '审核成功';  
         $data['url'] = U('Audit/course');  
        }else if(I('auditType') == 'exam') { //试卷审核
         $data['status'] = 1;  
         $data['info'] = '审核成功';  
         $data['url'] = U('Audit/exam'); 
        }else if(I('auditType') == 'question') { //问卷审核
         $data['status'] = 1;  
         $data['info'] = '审核成功';  
         $data['url'] = U('Audit/question'); 
         }
        }else{   //重复审核
         $data['status'] = 0;  
         $data['info'] = '不能重复审核';  
         $data['url'] = '';

        }
         $this->ajaxReturn($data);
       }
       
    }  
     
     
   /**
    *所有的审核 拒绝审核
    */
    public function denid(){
      /*
       if(IS_GET){
        
        $get  = I('id'); //接受 点击“拒绝”按钮传值 id
        $where['id'] = array('eq',$get);
        if(I('auditType') == 'item'){ 
            $audit = M('admin_project'); 
            $arr = array(
            'type'=>3
              );
        }else if(I('auditType') == 'course'){
             $audit = M('course'); 
             $arr = array(
             'status'=>2
               );

        }else if(I('auditType') == 'exam'){
             $audit = M('examination'); 
             $arr = array(
             'status'=>3
               );
        }else if(I('auditType') == 'question'){
             $audit = M('survey'); 
             $arr = array(
             'status'=>3
               );
        }  
        $res = $audit->where($where)->save($arr);
        if($res === false){
     //错误页面的默认跳转页面是返回前一页，通常不需要设置
          $this->error('审核失败');
        } else if(I('auditType') == 'item') {
          //设置成功后跳转页面的地址，默认的返回页面是$_SERVER['HTTP_REFERER']
    
          $this->success('审核拒绝',U('Admin/Audit/audit'));
      //    echo '审核成功';
     //   $this->redirect("Audit/audit");
       }else if(I('auditType') == 'course') {
         $this->success('审核拒绝',U('Admin/Audit/course'));
       }else if(I('auditType') == 'exam') {
         $this->success('审核拒绝',U('Admin/Audit/exam'));
       }else if(I('auditType') == 'question') {
         $this->success('审核拒绝',U('Admin/Audit/question'));
       }
     }
     */
      if(IS_AJAX){
        
        $get  = I('id'); //接受 点击“拒绝”按钮传值 id
        $where['id'] = array('eq',$get);
        if(I('auditType') == 'item'){ 
            $audit = M('admin_project'); 
            $arr = array(
            'type'=>3
              );
           $map = array(
           'id'=>$get,   
           'type'=>2
           );
        }else if(I('auditType') == 'course'){
             $audit = M('course'); 
             $arr = array(
             'status'=>2
               );
              $map = array(
             'id'=>$get,   
            'status'=>0
              );
        }else if(I('auditType') == 'exam'){
             $audit = M('examination'); 
             $arr = array(
             'status'=>3
               );
             $map = array(
             'id'=>$get,   
            'status'=>0
              );
        }else if(I('auditType') == 'question'){
             $audit = M('survey'); 
             $arr = array(
             'status'=>3
               );
             $map = array(
             'id'=>$get,   
             'status'=>0
              );
        }  

        $res = $audit->where($map)->find();
        
        if($res){
        $res = $audit->where($where)->save($arr);
        if($res === false){
           $data['status'] = 0;  
           $data['info'] = '审核失败';  
           $data['url'] = "";
        } else if(I('auditType') == 'item') {  //项目审核
          $data['status'] = 1;  
          $data['info'] = '已拒绝该审核';  
          $data['url'] = U('Audit/audit'); 
       }else if(I('auditType') == 'course') {  //课程审核
          $data['status'] = 1;  
          $data['info'] = '已拒绝该审核';  
          $data['url'] = U('Audit/course'); 
       }else if(I('auditType') == 'exam') { //试卷审核
          $data['status'] = 1;  
          $data['info'] = '已拒绝该审核';  
          $data['url'] = U('Audit/exam'); 
       }else if(I('auditType') == 'question') { //问卷审核
          $data['status'] = 1;  
          $data['info'] = '已拒绝该审核';  
          $data['url'] = U('Audit/question'); 
       }
        }else{  //重复审核
         $data['status'] = 0;  
         $data['info'] = '不能重复审核';  
         $data['url'] = '';

        }
         $this->ajaxReturn($data);
     }

   }
    









// /*
//   ajax的无刷新page类

// */

//       public function atest(){
//           import("Org.Nx.AjaxPage");// 导入分页类  注意导入的是自己写的AjaxPage类
//           $audit = M('admin_project'); 
//            $value = 88; //调试用
//           $where1 = array(
//               'type' => 2,
//               'user_id'  => $value,      
//           );
//           $where2 = array(
//               'type' => 0,
//               'user_id'  => $value,      
//           );
//           $where3 = array(
//               'type' => 3,
//               'user_id'  => $value,      
//           );
//        $res1 = $audit->field('a.project_name,a.class_name,b.username')->alias('a')
//                   ->join(' LEFT JOIN think_users  as  b  on b.id = a.user_id')
//                   ->where($where1)
//                   ->select();
//           $res2 = $audit->field('a.project_name,a.class_name,b.username')->alias('a')
//                   ->join(' LEFT JOIN think_users  as  b  on b.id = a.user_id')
//                   ->where($where2)
//                   ->select();
//           $res3 = $audit->field('a.project_name,a.class_name,b.username')->alias('a')
//                   ->join(' LEFT JOIN think_users  as  b  on b.id = a.user_id')
//                   ->where($where3)
//                   ->select();          
//          $count1 = $audit->field('a.project_name,a.class_name,b.username')->alias('a')
//                   ->join(' LEFT JOIN think_users  as  b  on b.id = a.user_id')
//                   ->where($where1)
//                   ->count();
//         //   print_r($res); exit; 
//           $this->assign('audit1',$res1);
//           $this->assign('audit2',$res2);
//           $this->assign('audit3',$res3);
          
//         //   $credit = M('users');
          
//           $limitRows = 2; // 设置每页记录数
       
//           $p = new \AjaxPage($count1, $limitRows,"audit"); //第三个参数是你需要调用换页的ajax函数名
//           $limit_value = $p->firstRow . "," . $p->listRows;
        
//         //   $data = $credit->order('id desc')->limit($limit_value)->select(); // 查询数据
//           $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成

//           $this->assign('page1',$page);
//           $this->display('audit_item');

//      }



//          public function test2(){
//      	  import("Org.Nx.AjaxPage");// 导入分页类  注意导入的是自己写的AjaxPage类
//      	  $credit2 = M('admin_project');
//         $count = $credit2->count(); //计算记录数
//         $limitRows = 5; // 设置每页记录数
//         $p = new \AjaxPage($count, $limitRows,"user2"); //第三个参数是你需要调用换页的ajax函数名
//         $limit_value = $p->firstRow . "," . $p->listRows;
       
//         $data2 = $credit2->limit($limit_value)->select(); // 查询数据
//         // print_r($data2);
//         $page2 = $p->show(); // 产生分页信息，AJAX的连接在此处生成

//         $this->assign('list2',$data2);
//         $this->assign('page2',$page2);

//         $this->display('ajaxpagetest');
//      }
    
//      /**
//       * find的where条件测试
//       */
//      public function test3(){
//        $get  = I('id');
//       //  $where['id']  = array('eq',$get);
//         $audit = M('admin_project'); 
//        $map = array(
//            'id'=>$get,   
//            'type'=>2
//            );
//        $res = $audit->where($map)->find();
//        print_r($res);
//        if($res){
//          echo '找到';
//        }else{
//          echo "未找到";
//        }
//      }
    
//     /**
//       * 网页下载文件测试
//       */
//     public function test4(){
//        $filepath = I('filepath'); //
//        if(!file_exists($filepath)){
//           echo "下载文件不存在！";
//           exit;

//        }
//        $filepath='./Upload/excel/simple.xls'; //调试用
//     header('Content-Description: File Transfer'); 
//     header('Content-Type: application/octet-stream');
//     header('Content-Disposition: attachment; filename='.basename($filepath));//获取带有文件扩展名的文件名
//     header('Content-Transfer-Encoding: binary');
//     header('Expires: 0');
//     header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
//     header('Pragma: public');
//     header('Content-Length: ' . filesize($filepath));//获取文件大小
//     readfile($filepath);//输出文件
    
//     }

//     public function test5(){

//     }
       
//     public function  qrcode(){

//         $course_id=I("get.id");

//         $url=json_encode(array("course_id"=>$course_id));

//         qrcode($url,14);

//     }

   


}

