<?php
namespace Admin\Controller;
use Html2Text\mb_strlen;

use Common\Controller\AdminBaseController;

/**
 * 用户批量导入
 * author DuJunqiang 20170511
 */
class UserImportController extends AdminBaseController{
	/**
	 * 文件导入，导入成功跳转导入结果页面
	 */
	public function import(){
		Vendor("PHPExcel.PHPExcel");
		$user_id = $_SESSION["user"]["id"];
		
		$hasImport = M("user_import")->where("user_id=".$user_id)->find();
		if($hasImport){
			if(time() - strtotime($hasImport["add_time"]) < 5*60){
				$return = array("code" =>1010, "message"=>"5分钟内请勿频繁提交文件");
				exit(json_encode($return));
			}
			$this->emptyData();//清空之前导入的数据
		}
		
		if(!$_FILES["userFile"]){
			$return = array("code" =>1010, "message"=>"请上传文件");
			exit(json_encode($return));
		}
		
		if($_FILES["userFile"]["error"] > 0){
			$return = array("code" =>1011, "message"=>"上传文件出错");
			exit(json_encode($return));
		}
		
		if($_FILES["userFile"]["size"] > 5242880){
			$return = array("code" =>1012, "message"=>"上传的文件不要超过5M");
			exit(json_encode($return));
		}
		
		if(!$_FILES["userFile"]["tmp_name"]){
			$return = array("code" =>1013, "message"=>"请选择上传的文件");
			exit(json_encode($return));
		}
		
		$fileArr = explode(".",$_FILES["userFile"]["name"]);
		$type = end($fileArr);
		if($type != 'csv' && $type != 'xls' && $type != 'xlsx'){
			$return = array("code" =>1014, "message"=>"文件格式必须为csv、xls或xlsx");
			exit(json_encode($return));
		}
		
		$file = $_FILES["userFile"]["tmp_name"];
		//$file = "Upload/excelTmp/user_import_test2.xlsx";
		
		$center_id = $_POST["center_id"];
		$center_id = (int)$center_id;
		if($center_id < 1){
			$return = array("code" =>1021, "message"=>"请选择导入范围");
			exit(json_encode($return));
		}
		
		cookie("center_id", $center_id, 86400);
		
		$excelCon = self::importExcel($file);
		$excelCon = $excelCon["data"];
		//验证文件合法性
		$rowTop = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$titleOne = array("区域","分公司","部门","室","姓名","性别","生日","年龄","邮箱","序列类型","职务/岗位","职级","用户组级别","学历","入集团日期","入中心日期","手机","办公电话","IP电话");				
		for ($k=0; $k<count($titleOne); $k++){
			$rowValue1 = trim($excelCon[1][$k]);
			if($rowValue1 != $titleOne[$k]){
				$return = array("code" =>1023, "message"=>$rowTop[$k]."列必须为".$titleOne[$k]);
				exit(json_encode($return));
			}
		}
		
		$lineNum = count($excelCon);
		for($i=0; $i<$lineNum; $i++){
			//第二行开始正式内容
			if($i > 1){
				$thisItem = $excelCon[$i];
				$hasVal = trim(implode("", $thisItem));
				if(!$hasVal){
					continue;
				}
				
				//必填选项 邮箱 姓名 手机号
				$area = $thisItem[0];
				$area = mb_substr($area, 0, 10);
				$param["area"] = $area;
				
				$company = $thisItem[1];
				$company = mb_substr($company, 0, 10);
				$param["company"] = $company;
				
				$part = $thisItem[2];
				$part = mb_substr($part, 0, 10);
				$param["part"] = $part;
				
				$room = $thisItem[3];
				$room = htmlspecialchars($room);
				$param["room"] = $room;
				
				$name = $thisItem[4];
				$name = mb_substr($name, 0, 10);
				$param["name"] = $name;
				
				$sexType = 0;
				if($thisItem[5] == "男"){
					$sexType = "1";
				}
				if($thisItem[5] == "女"){
					$sexType = "2";
				}
				$param["sex"] = $sexType;
				
				$birthday = $thisItem[6];
				if(!preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $birthday)){
					$birthday = "";
				}
				$param["birthday"] = $birthday;
				
				$age = (int)$thisItem[7];
				if($age < 1){
					$age = "";
				}
				$param["age"] = $age;
				
				$email = $thisItem[8];
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					$email = "";
				}
				$param["email"] = $email;
				
				$sequence = $thisItem[9];
				$sequence = htmlspecialchars($sequence);
				$param["sequence"] = $sequence;
				
				$job_name = $thisItem[10];
				$job_name = htmlspecialchars($job_name);
				$param["job_name"] = $job_name;
				
				$job_level = $thisItem[11];
				$job_level = htmlspecialchars($job_level);
				$param["job_level"] = $job_level;
				
				$user_level = $thisItem[12];
				$user_level = htmlspecialchars($user_level);
				$param["user_level"] = $user_level;
				
				//1:博士研究生 2:硕士研究生 3:本科 4:专科 5:专科以下'
				$edu = $thisItem[13];
				$eduType = 0;
				if($edu){
					if(strstr($edu, "博士")){
						$eduType = 1;
					}elseif(strstr($edu, "硕士")){
						$eduType = 2;
					}elseif(strstr($edu, "本科")){
						$eduType = 3;
					}elseif(strstr($edu, "专科")){
						$eduType = 4;
					}else{
						$eduType = 5;
					}
				}
				$param["edu"] = $eduType;
				
				$group_time = $thisItem[14];
				if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $group_time)){
					$group_time = "";
				}
				$param["group_time"] = $group_time;
				
				$center_time = $thisItem[15];
				if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $center_time)){
					$center_time = "";
				}
				$param["center_time"] = $center_time;
				
				$phone = $thisItem[16];
				if(!preg_match("/^1[0-9]{10}$/", $phone)){
					$phone = "";
				}
				$param["phone"] = $phone;
				
				$office_phone = $thisItem[17];
				if(!preg_match("/^([0-9]|-){5,20}$/", $office_phone)){
					$office_phone = "";
				}
				$param["office_phone"] = $office_phone;
				
				$ip_phone = $thisItem[18];
				$ip_phone = htmlspecialchars($ip_phone);
				$param["ip_phone"] = $ip_phone;
				
				//print_r($param);
				D("UserImport")->addUser($param);
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
		
		$get["type"] += 0;
		if(!is_int($get["type"]) || $get["type"] < 0){
			$get["type"] = 0;
		}
		
		$data = D("UserImport")->importPage($get);
		$data["type"] = $get["type"];
		$this->assign($data);
		$this->display("Tissue/user_import");
	}
	
	//移除临时表用户
	public function delUser(){
		$post = I("post.");
		if($post["ids"] == ""){
			$return = array("code" =>1001, "message"=>"请选择要移除的用户");
			exit(json_encode($return));
		}
		
		$delArr = explode(",", $post["ids"]);
		if(count($delArr) == 0){
			$return = array("code" =>1002, "message"=>"请选择要移除的用户");
			exit(json_encode($return));
		}
		
		$resq = D("UserImport")->delUser($delArr);
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
		
		if($post["phone"] == ""){
			$return = array("code" =>1012, "message"=>"请填写手机号");
			exit(json_encode($return));
		}
		
		if(!preg_match("/^1[0-9]{10}$/", $post["phone"])){
			$return = array("code" =>1013, "message"=>"手机号格式有误");
			exit(json_encode($return));
		}
		
		if($post["name"] == ""){
			$return = array("code" =>1014, "message"=>"请填写姓名");
			exit(json_encode($return));
		}
		
		if(mb_strlen($post["username"],'utf8') > 10){
			$return = array("code"=>1011, "message"=>"用户名称最长10个字符");
			exit(json_encode($return));
		}
		
		if($post["email"] == ""){
			$return = array("code" =>1014, "message"=>"请填写邮箱");
			exit(json_encode($return));
		}
		
		if(!filter_var($post["email"], FILTER_VALIDATE_EMAIL)){
			$return = array("code"=>1011, "message"=>"邮箱格式有误");
			exit(json_encode($return));
		}
		
		$resp = D("UserImport")->editData($post);
		//$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($resp));
	}
	
	//保存有效结果
	public function saveValid(){
		D("UserImport")->saveValid();
		$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($return));
	}
	
	//取消导入
	public function cancelImport(){
		D("UserImport")->cancelImport();
		$return = array("code" =>1000, "message"=>"成功");
		exit(json_encode($return));
	}
	
	//清空数据
	public function emptyData(){
		D("UserImport")->cancelImport();
	}
	
	//获取公司下部门
	public function getPart(){
		$get = I("get.");
		$tid = $get["tid"];
		$resq = D("UserImport")->getPart($tid);
		exit(json_encode($resq));
	}
	
	/**
	 * 编辑导入用户页面
	 */
	public function editPage(){
		$get = I("get.");
		$id = (int)$get["id"];
		if($id > 0){
			$data = M("user_import")->where("id=".$id)->find();
		}
		$this->assign($data);
		$this->display("Tissue/user_import_edit");
	}
	
	/**
	 * 导入Excel处理
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
			//空行大于5行了，终止
			if($noDataNum > 5){
				break;
			}
			array_push($excelContent, $rowValue);
		}
	
		$return = array("code" =>1000, "message"=>"成功", "data"=>$excelContent);
		return $return;
	}
}
