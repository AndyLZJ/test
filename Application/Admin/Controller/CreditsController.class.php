<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 学分搜索统计控制器
 */
class CreditsController extends AdminBaseController
{

    /**
     * 学分搜索统计列表
     */
   public function creditsList(){
       $data = D('Credits')->creditsList();
    //    print_r($res);
       $this->assign('res',$data[0]);
       $this->assign('page',$data[1]);
       $this->assign('user',$data[2]);
       $this->display('creditslist');
   }








}