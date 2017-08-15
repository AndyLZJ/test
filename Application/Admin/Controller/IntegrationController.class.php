<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 积分管理控制器
 */
class IntegrationController extends AdminBaseController
{

   /**
     福利管理模块
    **/ 

   /**
    *福利列表
    */  
   public function welfarelist(){
       $condition = I('table_search');
       $data = D('Integration')->welfareList();
    //    print_r($data);
       $this->assign('list',$data['list']);
       $this->assign('condition',$condition);
       $this->assign('page1',$data['page1']);
       $this->display('welfarelist');
   }

 
    /**
    *新增福利,已进行字段自动验证
    */  
   public function addWelfare(){
       if(IS_POST){
        $data['name'] = I('post.name');
        $data['welfare_covers'] = I('post.welfare_covers');
        $data['need_score'] = I('post.need_score');
        $data['is_public'] = I('post.is_public')?'1': '0';
        $id = I('post.id');
        $Form = D("Integration");
		
		if(C('DB_TYPE') == 'Oracle'){
			$data['id'] = getNextId('welfare');
		}
		
        if(empty($id)){
         $res = D("Integration")->addWelfare();
         if($res){
              $this->success('数据添加成功！',U('Admin/Integration/welfarelist'));
         }else{
              $this->error(D("Integration")->getError());
         }
     
        // if ($Form->create($data, 1)) {
        //     if ($Form->add()) {
        //         $this->success('数据添加成功！',U('Admin/Integration/welfarelist'));
        //     } else {
        //         $this->error('数据写入错误');
        //     }
        // } else {
        //     // 字段验证错误
        //     $this->error($Form->getError());
        //  }

        }else{
          //编辑时保存请求、
          $res = D("Integration")->editWelfaredata();
          
          if($res){
            $this->success('数据修改成功！',U('Admin/Integration/welfarelist'));  
          }else {
            $this->error(D("Integration")->getError());
          }
        //   if ($Form->create($data, 1)) {
        //   $res = $Form->editWelfaredata();
        //   if($res){
        //     $this->success('数据修改成功！',U('Admin/Integration/welfarelist'));  
        //   }else {
        //         $this->error('数据写入错误');
        //     }
        //     } else {
        //     // 字段验证错误
        //     $this->error($Form->getError());
        //  }
        }
        exit;
       }
      
      $this->display('addwelfare'); 
   }
   
    /**
    *福利列表数据的删除
    */  
   public function delone(){
    //   echo $id = I('id');die;
      $id = I('id') ? I('id') : 0;
      $data['is_delete'] = 1;
      $data['auth_user_id'] = $_SESSION['user']['id'];

      $res = M('welfare')->where(array('id'=>$id))->save($data);
      if (!$res) {
            $this->error('数据异常');
            }else{
            $this->success('删除成功',U('Admin/Integration/welfarelist'));
        }
   }
	/**
     * 福利数据的编辑详情展示
     */
     public function editWelfare()
	 {
       $id = I('id');
       $data = D('Integration')->editWelfare();
       $this->assign('data',$data);
       $this->assign('type','edit');
       $this->display('addwelfare');
     }
	/**
     * 福利兑换历史
     */
     public function welfareChangehistory()
	 {
       $data = D('Integration')->welfareChangehistory();
       $this->assign('historylist',$data['list2']);
       $this->assign('page2',$data['page2']);
       $this->display('welfarelist');
     }


   /**
     积分管理模块
    **/ 

	/**
     * 积分规则列表
     */
     public function integrationlist()
	 {

      $list1 = D('Integration')->integrationlist();
      $this->assign('list1',$list1);
      $this->display();
     }
	/**
     * 积分规则列表的编辑
     */
     public function editIntegrationlist()
	 {
           
      if(IS_AJAX){
      $res = D('Integration')->editIntegrationList();
      if($res){
          $data = array(
              'status'=>1,
              'info'=>'编辑成功！'
          );
          $this->ajaxReturn($data);
       }else{
          $data = array(
              'status'=>0,
              'info'=>'编辑失败！'
          );
          $this->ajaxReturn($data);
       }
      }
      
      
     }
	/**
     * 积分流水列表
     */
     public function integrationHistoryList()
	 {
    
      $data = D('Integration')->integrationHistoryList();
      $list2 = $data['list2'];
      $page2 = $data['page2'];
      $this->assign('list2',$list2);
      $this->assign('page2',$page2);
      $this->display('integrationlist');
     }

     /**
     我的积分模块
    **/ 

	/**
     * 我的积分列表
     */
     public function myintegration()
	 {
       
      $data = D('Integration')->myintegration();
       if(IS_AJAX){
        $this_month_score = $data['this_month_score'];
            if($this_month_score !== false){
                $data =array('status'=>1,'info'=>$data);
                $this->ajaxreturn($data);
            }else{
                $data =array('status'=>0,'info'=>'系统错误');
                $this->ajaxreturn($data);
            }
        }else {  
        $this->assign('yeardata',$data['yeardata']);
        $this->assign('all_score',$data['all_score']);
        $this->assign('available_score',$data['available_score']);
        $this->assign('this_month_score',$data['this_month_score']);
        $this->assign('list',$data['list']);
        $this->display();
      }
     }    
  
	/**
     * 兑换积分-福利社
     */
     public function myintegrationExchange()
	 {
      $res = D('Integration')->integrationExchange();
      $this->ajaxreturn($res);
     }  


  	/**
     * 我的积分列表页的积分规则
     */
     public function myintegrationRule()
	 {
      $list1 = D('Integration')->integrationlist();
      $this->assign('list1',$list1);
      $this->display('integral_rules');
      
      //调用公共Model里的方法,查看积分规则 积分触发
      $res = D('Trigger')->intergrationTrigger($_SESSION['user']['id'],4);


     }
  	/**
     * 我的积分列表页的申请加分
     */
     public function myintegrationApply()
	 {

        if(IS_POST){
        //  echo aa;    
         $model = D('Integration');
         $res = $model->myintegrationApply();
         if(!$res){
          $this->error($model->getError());
         }else{
          $this->success('申请成功,请等待审核结果！');
         }
        }else{
         $this->display('integral_apply');  
        }
        
     }
  	/**
     * 申请加分申请记录
     */
     public function myintegrationApplyhistory()
	 {
       $data = D('Integration')->myintegrationApplyhistory();

    //    print_r($data);exit;
       $this->assign('list2',$data['list2']);
       $this->assign('page2',$data['page2']);
       $this->display('integral_apply');  
     }
  	/**
     * 我的积分列表页的积分流水——全部,获取，使用列表
     */
     public function myintegrationwater()
	 {
       $data = D('Integration')->myintegrationwater();
    
       $this->assign('list1',$data['list1']);
       $this->assign('page1',$data['page1']);
       $this->assign('list2',$data['list2']);
       $this->assign('page2',$data['page2']);
       $this->assign('list3',$data['list3']);
       $this->assign('page3',$data['page3']);      
       $this->display('integral_water');  
         
     }
  





}