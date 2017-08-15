<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台菜单管理
 */
class AuditUsersController extends AdminBaseController{

	/**
	 *  注册审核 - 待审核
	 */
	public function registration(){
		//显示页数
		$total_page = $this->total_page;
		$approved_data = D('AdminAuditUsers')->getMembersList($total_page);
		//获取组织架构信息
		$treeInfo = D('AdminTissue')->treeInfo();
		$this->assign('treeInfo',$treeInfo);
		$this->assign('approved_list',$approved_data['list']);
		$this->assign('approved_page',$approved_data['page']);
		$this->assign('type',$approved_data['type']);
		$this->display();
	}


	/**
	 *  注册审核 - 状态修改
	 */
	public function statusUpdate(){
		$results = D('AdminAuditUsers')->statusUpdate();
		$data = array(
			"status"=> $results,
		);
		$this->ajaxReturn($data,'json');

	}


	/**
	 *  岗位管理
	 */
	public function jobsManage(){
		//显示页数
		$total_page = $this->total_page;
		$gangwei_data = D('AdminAuditUsers')->jobsList($total_page);
		$this->assign('gangwei_list',$gangwei_data['list']);
		$this->assign('gangwei_page',$gangwei_data['page']);
		$this->display();
	}

	/**
	 *  添加 - 岗位管理
	 */
	public function addJobs(){
		$results = D('AdminAuditUsers')->addJobs();
		$data = array(
			"status"=> $results,
		);
		$this->ajaxReturn($data,'json');

	}

	/**
	 *  删除 - 岗位管理
	 */
	public function delJobs(){
		$results = D('AdminAuditUsers')->delJobs();
		$data = array(
			"status"=> $results,
		);
		$this->ajaxReturn($data,'json');


	}

	/**
	 *  编辑姓名 - 岗位管理
	 */
	public function editorJobs(){
		$results = D('AdminAuditUsers')->editorJobs();
		
		$this->ajaxReturn($results,'json');

	}
	
	/**
	 * 导入岗位 20170515
	 */
	public function import(){
		$user_id = $_SESSION["user"]["id"];
		$hasImport = M("jobs_import")->where("user_id=".$user_id)->find();
		if($hasImport){
			$this->emptyData();//清空之前导入的数据
		}
		
		Vendor("PHPExcel.PHPExcel");
		$user_id = $_SESSION["user"]["id"];
		if(!$_FILES["excelFile"]){
			$return = array("code" =>1010, "message"=>"请上传文件");
			exit(json_encode($return));
		}
		
		if($_FILES["excelFile"]["error"] > 0){
			$return = array("code" =>1011, "message"=>"上传文件出错");
			exit(json_encode($return));
		}
		
		if($_FILES["excelFile"]["size"] > 5242880){
			$return = array("code" =>1012, "message"=>"上传的文件不要超过5M");
			exit(json_encode($return));
		}
		
		if(!$_FILES["excelFile"]["tmp_name"]){
			$return = array("code" =>1013, "message"=>"请选择上传的文件");
			exit(json_encode($return));
		}
		
		$fileArr = explode(".",$_FILES["excelFile"]["name"]);
		$type = end($fileArr);
		if($type != 'csv' && $type != 'xls' && $type != 'xlsx'){
			$return = array("code" =>1014, "message"=>"文件格式必须为csv、xls或xlsx");
			exit(json_encode($return));
		}
		$file = $_FILES["excelFile"]["tmp_name"];
		
		$readType = \PHPExcel_IOFactory::identify($file);  //识别文件类型
		$excelReader = \PHPExcel_IOFactory::createReader($readType);
		if($excelReader instanceof \PHPExcel_Reader_CSV){
			$excelReader->setInputEncoding('GBK');
		}
		
		$PHPExcelObj = $excelReader->load($file);
		$currentSheet = $PHPExcelObj->getSheet(0);            //选取第一张表单(Sheet1)为当前操作的表单
		$excelRows = $currentSheet->getHighestRow();          //获取最大行
		$excelColumn = $currentSheet->getHighestColumn();     //获取最大列(获取到的是字母 ABCD......)
		$excelColumnIndex = \PHPExcel_Cell::columnIndexFromString($excelColumn);//字母转为数字
		
		if($excelRows <= 1){
			$return = array("code" =>1021, "message"=>"空文件，此文件没有有效数据");
			exit(json_encode($return));
		}
		if($excelRows > 5000){
			$return = array("code" =>1021, "message"=>"一次最多导入5000行数据");
			exit(json_encode($return));
		}
		if($excelColumn > 20){
			$return = array("code" =>1022, "message"=>"文件列数不能超过20列");
			exit(json_encode($return));
		}
		
		//没有0行
		$rowTop = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$excelContent = array();
		$noDataNum = 0;
		
		$tissue = M("tissue_group_access")->where("user_id=".$user_id)->find();
		
		for($i=1; $i<=$excelRows; $i++){
			$rowValue = array();
			$isNullRow = false;
			for($j=0; $j<$excelColumnIndex; $j++){
				$rowValue[$j] = $currentSheet->getCell($rowTop[$j].$i)->getValue();
				if($rowValue[$j]){
					$isNullRow = true;
				}
			}
			if(!$isNullRow){
				$noDataNum ++;
			}
			if($noDataNum > 10){
				break;
			}
		
			//第一行为标题，跳过
			if($i > 1){
				if(!$rowValue[0]){
					continue;
				}
				
				$data["name"] = $rowValue[0];
				D("AdminAuditUsers")->addUser($data);
			}
		}
		
		$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($return));
	}
	
	/**
	 * 导入结果页面
	 */
	public function importPage(){
		$get = I("get.");
		$get["page"] = $get["p"] + 0;
		if(!is_int($get["page"]) || $get["page"] < 0){
			$get["page"] = 1;
		}
		
		$data = D("AdminAuditUsers")->importPage($get);
		$this->assign($data);
		$this->display("AuditUsers/import_page");
	}
	
	//移除临时表数据
	public function delUser(){
		$post = I("post.");
		if($post["ids"] == ""){
			$return = array("code" =>1001, "message"=>"请选择要移除的岗位");
			exit(json_encode($return));
		}
		
		$delArr = explode(",", $post["ids"]);
		if(count($delArr) == 0){
			$return = array("code" =>1002, "message"=>"请选择要移除的岗位");
			exit(json_encode($return));
		}
		
		$resq = D("AdminAuditUsers")->delUser($delArr);
		$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($return));
	}
	
	//编辑错误数据
	public function editData(){
		$post = I("post.");
		$post["id"] += 0;
		if(!is_int($post["id"]) || $post["id"] < 0){
			$return = array("code" =>1011, "message"=>"用户id有误");
			exit(json_encode($return));
		}
		
		if($post["name"] == ""){
			$return = array("code" =>1014, "message"=>"请填写岗位");
			exit(json_encode($return));
		}
		
		if(mb_strlen($post["username"],'utf8') > 10){
			$return = array("code"=>1011, "message"=>"岗位名称最长10个字符");
			exit(json_encode($return));
		}
		
		$resp = D("AdminAuditUsers")->editData($post);
		exit(json_encode($resp));
	}
	
	//保存有效结果
	public function saveValid(){
		D("AdminAuditUsers")->saveValid();
		$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($return));
	}
	
	//取消导入
	public function cancelImport(){
		D("AdminAuditUsers")->cancelImport();
		$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($return));
	}
	
	//清空数据
	public function emptyData(){
		D("AdminAuditUsers")->cancelImport();
	}
}

