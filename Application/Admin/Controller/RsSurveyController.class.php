<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 问卷导入
 * author DuJunqiang 20170608
 */
class RsSurveyController extends AdminBaseController{
	/**
	 * 问卷列表
	 * type 1已发布 5未发布 0待审核 3已拒绝
	 * title 问卷标题
	 * p 页码
	 */
	public function listPage(){
		$get = I("get.");
		if(!$get["type"]){
			$get["type"] = 1;
		}
		$get["type"] = (int)$get["type"];
		//0表示待审核，1表示已通过，3表示已拒绝,4已删除 5草稿（未发布）',
		if($get["type"] == 1) $get["status"] = 1;
		if($get["type"] == 2) $get["status"] = 5;
		if($get["type"] == 3) $get["status"] = 0;
		if($get["type"] == 4) $get["status"] = 3;
		$get["page"] = $get["p"];
		$data = D("RsSurvey")->listPage($get);
		$data["type"] = $get["type"];
		$this->assign($data);
		$this->display("list_page");
	}
	
	/**
	 * 禁用启用操作
	 * is_available 0表示禁用，1表示启用 
	 * survey_id 
	 */
	public function setAvailable(){
		$get = I("get.");
		if($get["is_available"] != 1){
			$get["is_available"] = 0;
		}
		
		$get["survey_id"] = (int)$get["survey_id"];
		if($get["survey_id"] < 1){
			$return = array("code" =>1011, "message"=>"缺少问卷id");
			exit(json_encode($return));
		}
		
		$resp = D("RsSurvey")->setAvailable($get);
		$return = array("code" =>1000, "message"=>"ok");
		exit(json_encode($return));
	}
	
	/**
	 * 文件导入，导入成功跳转导入结果页面
	 */
	public function import(){
		Vendor("PHPExcel.PHPExcel");
		$user_id = $_SESSION["user"]["id"];
		if(!$_FILES["importFile"]){
			$return = array("code" =>1010, "message"=>"请上传文件");
			exit(json_encode($return));
		}
		
		if($_FILES["importFile"]["error"] > 0){
			$return = array("code" =>1011, "message"=>"上传文件出错");
			exit(json_encode($return));
		}
		
		if($_FILES["importFile"]["size"] > 5242880){
			$return = array("code" =>1012, "message"=>"上传的文件不要超过5M");
			exit(json_encode($return));
		}
		
		if(!$_FILES["importFile"]["tmp_name"]){
			$return = array("code" =>1013, "message"=>"请选择上传的文件");
			exit(json_encode($return));
		}
		
		$fileArr = explode(".",$_FILES["importFile"]["name"]);
		$type = end($fileArr);
		if($type != 'csv' && $type != 'xls' && $type != 'xlsx'){
			$return = array("code" =>1014, "message"=>"文件格式必须为csv、xls或xlsx");
			exit(json_encode($return));
		}
		$file = $_FILES["importFile"]["tmp_name"];
		//$file = "Upload/excel/mytest0609.xlsx";
		
		$excelCon = self::importExcel($file);
		if($excelCon["code"] != 1000){
			exit(json_encode($excelCon));
		}
		
		/*
		 * 略过部分
		 * 第一行：文档说明
		 * 第二行：问卷信息列标题
		 * 第四行：问卷题目列标题
		 */
		
		$excelCon = $excelCon["data"];
		
		//验证文档合法性
		if($excelCon[1][0] != "问卷分类"){
			$return = array("code" =>1021, "message"=>"第2行A列必须为【问卷分类】");
			exit(json_encode($return));
		}
		if($excelCon[1][1] != "问卷名称"){
			$return = array("code" =>1022, "message"=>"第2行B列必须为【问卷名称】");
			exit(json_encode($return));
		}
		if($excelCon[1][2] != "问卷简介"){
			$return = array("code" =>1023, "message"=>"第2行C列必须为【问卷简介】");
			exit(json_encode($return));
		}
		if($excelCon[3][0] != "题目类型"){
			$return = array("code" =>1024, "message"=>"第4行A列必须为【题目类型】");
			exit(json_encode($return));
		}
		if($excelCon[3][1] != "题目"){
			$return = array("code" =>1025, "message"=>"第4行B列必须为【题目】");
			exit(json_encode($return));
		}
		if($excelCon[3][2] != "是否必填"){
			$return = array("code" =>1025, "message"=>"第4行C列必须为【是否必填】");
			exit(json_encode($return));
		}
		if($excelCon[3][3] != "投票/普通"){
			$return = array("code" =>1026, "message"=>"第4行D列必须为【投票/普通】");
			exit(json_encode($return));
		}
		if($excelCon[3][4] != "验证类型"){
			$return = array("code" =>1027, "message"=>"第4行E列必须为【验证类型】");
			exit(json_encode($return));
		}

		$importReturn = D("RsSurvey")->import($excelCon);
		if($importReturn["code"] != 1000){
			exit(json_encode($importReturn));
		}
		
		$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($return));
	}
	
	/**
	 * 导入问卷处理
	 * $file 有效的excel文件地址
	 */
	public function importExcel($file){
		Vendor("PHPExcel.PHPExcel");
		if(!$file){
			$return = array("code" =>1020, "message"=>"请上传文件");
			return $return;
		}
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
			$return = array("code" =>1021, "message"=>"空文件");
			return $return;
		}
		if($excelRows > 500){
			$return = array("code" =>1021, "message"=>"一次最多导入500行数据");
			return $return;
		}
		if($excelColumn > 20){
			$return = array("code" =>1022, "message"=>"文件列数不能超过20列");
			return $return;
		}
	
		//没有0行
		$rowTop = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$excelContent = array();
		$noDataNum = 0;
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
			array_push($excelContent, $rowValue);
		}
	
		$return = array("code" =>1000, "message"=>"成功", "data"=>$excelContent);
		return $return;
	}
	
	/**
	 * 查看问卷
	 */
	public function detail(){
		$get = I("get.");
		$suvId = (int)$get["id"];
		if($suvId < 1){
			exit("非法操作，缺少问卷id");
		}
		
		$detail = D("RsSurvey")->detail($suvId);
		$this->assign($detail);
		$this->display("detail");
	}
	
	/**
	 * 编辑问卷
	 */
	public function editPage(){
		$get = I("get.");
		$id = (int)$get["id"];
		if($id < 1){
			exit("非法操作，缺少问卷id");
		}
		$detail = D("RsSurvey")->editPage($id);
		$this->assign($detail);
		$this->display("edit_page");
	}
	
	/**
	 * 新建问卷页面
	 */
	public function createPage(){
		$detail = D("RsSurvey")->createPage();
		$this->assign($detail);
		$this->display("create_page");
	}
	
	/**
	 * 新建问卷
	 */
	public function createSurvey(){
		$post = I("post.");
		$survey_name = addslashes(trim($post["survey_name"]));
		$survey_desc = addslashes(trim($post["survey_desc"]));
		$survey_cat_id = (int)$post["survey_cat_id"];
		$status = (int)$post["status"];
		
		if(!$survey_name){
			$return = array("code" =>1011, "message"=>"请填写问卷名称");
			exit(json_encode($return));
		}
		if($survey_cat_id < 1){
			$return = array("code" =>1022, "message"=>"请选择问卷分类");
			exit(json_encode($return));
		}
		if($status != 0 && $status != 5){
			$return = array("code" =>1023, "message"=>"保存类型有误");
			exit(json_encode($return));
		}
		
		$post["survey_id"] = (int)$post["survey_id"];//修改用到
		if($post["survey_id"] > 0){
			$data["survey_id"] = $post["survey_id"];
		}
		
		$data["survey_name"] = $survey_name;
		$data["survey_desc"] = $survey_desc;
		$data["survey_cat_id"] = $survey_cat_id;
		$data["status"] = $status;
		
		$resp = D("RsSurvey")->createSurvey($data);
		if($resp["code"] != 1000){
			exit(json_encode($resp));
		}else{
			exit(json_encode($resp));
		}
	}
	
	/**
	 * 保存试题
	 */
	public function createItem(){
		//print_r($_POST);
		//print_r($_FILES);
		$post = I("post.");
		$post["title"] = addslashes(trim($post["title"]));
		if(!$post["title"]){
			$return = array("code" =>1011, "message"=>"题目必填");
			exit(json_encode($return));
		}
		$post["survey_id"] = (int)$post["survey_id"];
		if($post["survey_id"] < 1){
			$return = array("code" =>1011, "message"=>"缺少survey_id");
			exit(json_encode($return));
		}
		$post["orders"] = (int)$post["orders"];
		
		if($post["is_must"] != 1){
			$post["is_must"] = 2;
		}
		if($post["item_type"] != 1){
			$post["item_type"] = 2;
		}
		
		$post["verify_type"] = (int)$post["verify_type"];
		if($post["verify_type"] < 1){
			$post["verify_type"] = 0;
		}
		
		if($post["classification"] != 1 && $post["classification"] != 2 && $post["classification"] != 3){
			if(count($post["option"]) > 1){
				$post["classification"] = 1;
			}else{
				$return = array("code" =>1011, "message"=>"题目类型有误");
				exit(json_encode($return));
			}
		}
		
		//选择题选项验证
		if($post["classification"] == 1 || $post["classification"] == 2){
			$optNum = count($post["option"]);
			for($i=0; $i<$optNum; $i++){
				$post["opt_img"][$i] = "";
				if($_FILES["opt_img"]["error"][$i] == 0){
					$fileType = true;
					if($_FILES["opt_img"]["size"][$i] > 2097152){
						$fileType = false;
					}
					$fileArr = explode(".",$_FILES["opt_img"]["name"][$i]);
					$type = end($fileArr);
					if($type != 'png' && $type != 'jpg' && $type != 'jpeg' && $type != 'gif' && $type != 'bmp'){
						$fileType = false;
					}
					
					if($fileType){
						$imgPath = "./Upload/survey/".uniqid("opt").mt_rand(1, 9999).".".$type;
						if(move_uploaded_file($_FILES['opt_img']['tmp_name'][$i], $imgPath)){
							$imgPath = str_replace("./Upload", "/Upload", $imgPath);
							$post["opt_img"][$i] = $imgPath;
						}
					}
				}else{
					if($post["opt_img_old"][$i]){
						$post["opt_img"][$i] = $post["opt_img_old"][$i];
					}
				}
				
				$option = $post["option"][$i];
				$opt_img = $post["opt_img"][$i];
				if(!$option && !$opt_img){
					$return = array("code" =>1012, "message"=>"选项文本、选项插图至少填写一个");
					exit(json_encode($return));
				}
			}
		}
		
		//题目插图验证
		$post["img"] = "";
		if($_FILES["img"]["error"] == 0){
			if($_FILES["img"]["size"] > 2097152){
				unset($_FILES["img"]);
			}
			$fileArr = explode(".",$_FILES["img"]["name"]);
			$type = end($fileArr);
			if($type != 'png' && $type != 'jpg' && $type != 'jpeg' && $type != 'gif' && $type != 'bmp'){
				unset($_FILES["img"]);
			}
			
			$imgPath = "./Upload/survey/".uniqid("item").mt_rand(1, 9999).".".$type;
			if(move_uploaded_file($_FILES['img']['tmp_name'], $imgPath)){
				$imgPath = str_replace("./Upload", "/Upload", $imgPath);
				$post["img"] = $imgPath;
			}
		}else{
			if($post["img_old"]){
				$post["img"] = $post["img_old"];
			}
		}
		
		D("RsSurvey")->createItem($post);
		
		$return = array("code" =>1000, "message"=>"ok");
		exit(json_encode($return));
	}
	
	//删除问卷
	public function delSurvey(){
		$survey_id = I("get.survey_id");
		$survey_id = (int)$survey_id;
		if($survey_id < 1){
			$return = array("code" =>1001, "message"=>"未获取到survey_id");
			exit(json_encode($return));
		}
		
		$data["status"] = 4;
		M("survey")->where("id=".$survey_id)->save($data);
		
		$return = array("code" =>1000, "message"=>"ok");
		exit(json_encode($return));
	}
	
	//删除问卷题目
	public function delItem(){
		$post = I("post.");
		$itemId = (int)$post["itemId"];
		if($itemId < 1){
			$return = array("code" =>1011, "message"=>"未获取到题目id");
			exit(json_encode($return));
		}
		
		$resp = D("RsSurvey")->delItem($itemId);
		$return = array("code" =>1000, "message"=>"ok");
		exit(json_encode($return));
	}
	
	//发布问卷
	public function yesPub(){
		$post = I("post.");
		$ids = $post["survey_ids"];
		if($ids){
			$idArr = explode(",", $ids);
			foreach ($idArr as $value){
				$survey_id = (int)$value;
				if($survey_id > 0){
					$data["status"] = 0;
					M("survey")->where("id=".$survey_id)->save($data);
				}
			}
			
			$return = array("code" =>1000, "message"=>"ok");
			exit(json_encode($return));
		}else{
			$return = array("code" =>1001, "message"=>"未获取到问卷id");
			exit(json_encode($return));
		}
	}
	
	//取消发布
	public function cancelPub(){
		$post = I("post.");
		$ids = $post["survey_ids"];
		if($ids){
			$idArr = explode(",", $ids);
			foreach ($idArr as $value){
				$survey_id = (int)$value;
				if($survey_id > 0){
					$data["status"] = 5;
					M("survey")->where("id=".$survey_id)->limit(1)->save($data);
					
					/*
					重新（即将表数据的状态变为待审核）提交接口 $res = D('Trigger')->projectResubmit($id,1);
					@param $type 类型(1:培训项目,2:新建课程,3:新建试卷审,4:新建问卷,5:新建互动,6:发起调研7:发起考试,8:发起加分,9:用户注册)
					 */
					D('Trigger')->projectResubmit($survey_id, 4);
				}
			}
	
			$return = array("code" =>1000, "message"=>"ok");
			exit(json_encode($return));
		}else{
			$return = array("code" =>1001, "message"=>"未获取到问卷id");
			exit(json_encode($return));
		}
	}
}