<?php 

namespace Admin\Controller;

use Common\Controller\AdminBaseController;

class RsCourseController extends AdminBaseController{


	/**
	 * 资源管理模块 - 课程管理首页
	 */
	public function index()
	{

		$data =D('RsCourse')->index();


		$this->assign($data);

		$this->display();
	}

	/**
	 * 添加多媒体视频
	 */
	public function addVideo(){

		$upToken =D('RsCourse')->addVideo();

		$this->ajaxReturn($upToken,'json');
	}

	/**
	 * 多媒体页面
	 */
	public function video(){

		$setting=C('UPLOAD_SITEIMG_QINIU');
		$this->assign("domain",$setting['driverConfig']['domain']);
		$this->display();
	}

	/**
	 * 检查视频是否转码成功
	 */
	public function pfopStatus(){

		$url =D('RsCourse')->pfopStatus();

		echo $url;
		exit;
	}

	/**
	 * 资源管理模块 - 课程分类
	 */
	public function CourseClass(){

		$data =D('RsCourse')->CourseClass();

		$items = array("items"=>$data);

		$this->assign($items);

		$this->display();
	}

	/**
	 *增加分类
	 */
	public function addCategory()
	{
		$results = D('RsCourse')->addCategory();

		$data = array(
			"status"=> $results,
		);

		$this->ajaxReturn($data,'json');

	}

	/**
	 * 编辑分类
	 */
	public function editorCategory(){

		$results = D('RsCourse')->editorCategory();

		$this->ajaxReturn($results,'json');

	}

	/**
	 * 删除分类
	 */
	public function delCategory(){

		$results = D('RsCourse')->delCategory();

		$data = array(
			"status"=> $results,
		);

		$this->ajaxReturn($data,'json');
	}


	/**
	 * 添加课程
	 */
	public function addCourse(){

		$data = D('RsCourse')->addCourse();

		$this->assign($data);
		$this->display();

	}

	/**
	 * 编辑课程
	 */
	public function editorCourse(){

		$results = D('RsCourse')->editorCourse();

		if($results){
			$this->success('编辑成功',U('Admin/RsCourse/index'));
		}else{
			$this->error('编辑失败');
		}

	}

	/**
	 * 设置公开课
	 */
	public function setOpen(){

		$results = D('RsCourse')->setOpen();

		$data = array(
			"status"=> $results,
		);

		$this->ajaxReturn($data,'json');

	}

	/**
	 * 设置是否禁用
	 */
	public function setTrigger(){

		$results = D('RsCourse')->setTrigger();

		$data = array(
			"status"=> $results,
		);

		$this->ajaxReturn($data,'json');


	}

	/**
	 * 删除课程
	 */
	public function delCourse(){

		$results = D('RsCourse')->delCourse();

		$data = array(
			"status"=> $results,
		);

		$this->ajaxReturn($data,'json');

	}

	/**
	 * 历史版本
	 */
	public function HistoryVersion(){

		$total_page = $this->total_page;

		$data = D('RsCourse')->HistoryVersion($total_page);

		$this->assign($data);

		$this->display();
	}

	/**
	 * 查看历史版本
	 */
	public function checkcourse(){

		$data = D('RsCourse')->checkcourse();
		
		$this->assign($data);
		$this->display();

	}

	/**
	 * 选择用户标签
	 */
	public function TagList(){

		$data = D('RsCourse')->TagList();

		$this->assign("taglist",$data);

		$this->display();

	}


}