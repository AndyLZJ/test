<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class PjCourseController extends AdminBaseController{

//******************权限***********************
    /**
     * 培训项目-考试
     */
    public function index(){

        $total_page = $this->total_page;

        $data=D('PjCourse')->index($total_page);

        $this->assign($data);

        $this->display();
    }

    /**
     * 培训项目 - 课程
     */
    public function course(){

        $total_page = $this->total_page;

        $data=D('PjCourse')->course($total_page);

        $this->assign($data);

        $this->display();

    }

    /**
     * 培训项目 - 调研
     */
    public function research(){

        $typeid = I('get.typeid') ? I('get.typeid') : 0;

        $total_page = $this->total_page;

        $data=D('PjCourse')->research($total_page);

        $this->assign($data);
        $this->assign("typeid",$typeid);
        $this->display();


    }




}


