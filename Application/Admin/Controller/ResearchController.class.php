<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 调研管理模块
 * @Andy
 */
class ResearchController extends AdminBaseController{

	/**
	 * 调研管理 - 首页
	 */
	public function index(){
		$type = I("get.typeid",1,'int');
		$total_page = $this->total_page;

		$results = D('Research')->index($total_page);
		
		$this->assign('type',$type);
		$this->assign($results);
		$this->display();
	}

	/**
	 * 新增加调研
	 */

	public function editor(){

		if(IS_POST){

			$results = D('Research')->editor();

			if ($results) {
				$this->success('提交成功',U('admin/Research/index'));
			} else {
				$this->error('提交失败');
			}
		}else{

			//获取编辑资料
			$items = D('Research')->getEditor();
			
			$ids = $items['tissue_id'];
			$names = $items['tissue_name'];
			
			$this->assign('id',$items['id']);
			$this->assign('ids',$ids);
			$this->assign('names',$names);
			$this->assign($items);
			$this->display();
		}

	}


	/**
	 * 指定部门
	 */
	public function tissue(){

		//获取组织架构信息
		$treeInfo = D('AdminTissue')->treeInfo();

		$this->assign("tree_items",$treeInfo);
		$this->display();
	}

	/**
	 * 查看详情
	 */
	public function view(){
		$data = D('Research')->view();
		if($data['typeid'] == 2){
			$this->assign($data);
			$this->display('questionnaire');
		}else{
			$items = D('MySurvey')->getSurveyResult();
			$this->assign($items);
			$this->display('MySurvey/checksurveyresult');
		}
	}

	/**
	 * 删除调研
	 */
	public function delete(){

		$results = D('Research')->delete();

		$data = array(
			"status"=> $results,
		);

		$this->ajaxReturn($data,'json');
	}

}
