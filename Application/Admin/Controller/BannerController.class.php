<?php 

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 审核管理控制器
 */
class BannerController extends AdminBaseController{

     /** 
     *banner管理的展示
     */
     public function detail()
     {

       $data = D('Banner')->getDetail();
       
       $this->assign('banner_img',$data['banner_img']);
       $this->display('banner');

     }


     /** 
     *banner管理的上传，目前需求只上传一张banner图，banner管理模块设计：表没存在一条数据则增加该数据，存在则修改该数据
     */
     public function updata()
     {
       $filepath = I('post.filepath');
       if(empty($filepath)){
          $this->ajaxreturn(false); 
          exit;
       }

      //  echo aa ;
       $res = D('Banner')->upload($filepath);
        if($res === false){
           $this->ajaxreturn(false); 
           
        }else{
           $this->ajaxreturn(true);
        }


     }




    //-------------------------------------3.2期----------------------------------------//

    /** 
     * uploadifyDemo 测试
     */
     public function uploadifyDemo()
     {
       
       $this->display('uploadify/demo');
     }
    /** 
     *banner的列表 
     */
     public function index()
     {
       $lists = D('Banner')->bannerList();
       
       
       $this->assign('lists',$lists);
       $this->display('index');
     }


    /** 
     *banner的增加/编辑，上限5条 
     */
     public function add()
     {  
        
        $res = D('Banner')->add();
        //  $this->ajaxreturn(D('Banner')->getError()); exit;
        if($res === false){
           $data = array(
             'status'=>0,
             'info'=>D('Banner')->getError(),
             'url'=>''
           );
           
           $this->ajaxreturn($data); 
           
        }else{
           $data = array(
             'status'=>1,
             'info'=>'保存成功',
             'url'=>''
           );
           
           $this->ajaxreturn($data); 
        }
        // $this->display('add');
     }

    /** 
     *banner条数的删除
     */
     public function del()
     {  
        $id = I('get.id') ? I('get.id') : 0;
        $res = D('Banner')->del($id);
        if($res){
          $this->success('删除成功',U('Admin/Banner/index'));
        }else{
          $this->success('删除失败');
        }
          
        

     }

    /** 
     *banner的详情
     */
     public function bannerDetail()
     {
        $id = I('get.id') ? I('get.id') : '';
       
        $edit = I('get.edit');
        $data = D('Banner')->bannerDetail($id);
        // print_r($data);
        $this->assign('edit',$edit);
        $this->assign('data',$data);
        $this->display('add');
     }

    /** 
     *banner轮播速度设置 
     */
     public function speedSet()
     {
        $res = D('Banner')->speedSet();

         if($res === false){
           $data = array(
             'status'=>0,
             'info'=>D('Banner')->getError(),
             'url'=>''
           );
           $this->ajaxreturn($data);  
        }else{
           $data = array(
             'status'=>1,
             'info'=>'设置成功',
             'url'=>''
           );
           $this->ajaxreturn($data); 
        }

     }

    /** 
     *banner的排序
     */
     public function orderNumber()
     {
        $res = D('Banner')->orderNumber();
        if($res){
          // $this->success('成功',U('Admin/Banner/index'));
          $this->redirect('Admin/Banner/index');
        }else{
          $this->error(D('Banner')->getError());
        }
     }

    /** 
     *banner详情页的资讯/课程/调研管理列表获取 
     */
     public function getItem()
     {
         $type = I('type');
         $data = D('Banner')->getItem($type);
         $this->assign('lists',$data['list']);
         $this->assign('page',$data['show']);
         $this->display('zhiding');
     }



}