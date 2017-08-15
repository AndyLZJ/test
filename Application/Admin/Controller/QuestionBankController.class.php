<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 试题库控制器
 */
class QuestionBankController extends AdminBaseController{
	
	/**
	 * 首页
	 */
	public function index(){
		$model = D('QuestionBank');
		$res = D('QuestionBank')->index();
		$cate = D('ResourcesManage')->getExamCate();
		
		$this->assign('cate',json_encode($cate));
		$this->assign('data',$res['data']);
		$this->assign('page',$res['page']);
		$this->assign('name',$res['name']);
		$this->display();
	}

	/**
	 * 查找试题库
	 */
	public function search(){
		$res = D('QuestionBank')->search();
		$this->display('index');
	}
	
	/**
	 * 新增试题库
	 */
	public function add(){
		$res = D('QuestionBank')->add();
		$this->ajaxReturn($res);
	}
	
	public function addhtml(){
		$cate = D('ResourcesManage')->getExamCate();
		
		$this->assign('cate',$cate);
		$this->display('add');
	}
	/**
	 * 删除试题库
	 */
	public function del(){
		$res = D('QuestionBank')->del();
		$this->ajaxReturn($res);
	}
	
	/**
	 * 重命名试题库
	 */
	public function reName(){
		$res = D('QuestionBank')->reName();
		$this->ajaxReturn($res);
	}
	
	/**
     * 导出试题
     * @return [type] [description]
     */
    public function export(){
        $model = D('QuestionBank');
        $data = $model->export();
		
        $xlsName  = "试题-".date('Y-m-d-H:i:s');

        array_unshift($data,array('试题题目','选项A','选项B','选项C','选项D','选项E','正确答案','题目类型','得分关键字','所属课程','所属试题库'));
        create_xls($data,$xlsName);
    }

	//移动到--复制到
	public function move(){
		$res = D('QuestionBank')->index(I('get.id'));
		
		$this->assign('data',$res['data']);
		$this->assign('page',$res['page']);
		$this->assign('name',$res['name']);
		$this->display();
	}
	
	//移动试题
	public function moveto(){
		$res = D('QuestionBank')->moveto();
		$this->ajaxReturn($res);
	}
	
	//复制试题
	public function copyto(){
		$res = D('QuestionBank')->copyto();
		$this->ajaxReturn($res);
	}
}
