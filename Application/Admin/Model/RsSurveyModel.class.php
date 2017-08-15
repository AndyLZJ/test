<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 问卷导入
 * @author Dujunqiang 20170609
 */
class RsSurveyModel extends BaseModel{
	//初始化
	public function __construct(){}
	/**
	 * 问卷资源列表
	 */
	public function listPage($param){
		$where["status"] = $param["status"];
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 20;
		$start = ($param["page"] - 1) * $param["pageLen"];
		
		if($param["keyword"]){
			$param["keyword"] = addslashes($param["keyword"]);
			$where["survey_name"] = array("like", "%".$param["keyword"]."%");
		}

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();
		$where['auth_user_id'] = array("in",$specifiedUser);

		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$list = M("survey")
				->where($where)
				->limit($start, $param["pageLen"])
				->field("id,survey_name,survey_cat_id,survey_score,survey_heir,status,is_available,principal,survey_desc,orderno,objection,auth_user_id,to_char(survey_upload_time,'YYYY-MM-DD HH24:MI:SS') as survey_upload_time")
				->order("id desc")
				->select();
		}else{
			$list = M("survey")->where($where)->limit($start, $param["pageLen"])->order("id desc")->select();
		}
		
		$items = D('IsolationData')->isolationData($list);

		foreach($items as $i=>$list){

			if(!$items[$i]["orderno"]){
				$items[$i]["orderno"] = "--";
			}

			//问卷分类
			$cat_name = M("survey_category")->where("id=".$items[$i]["survey_cat_id"])->find();
			$items[$i]["cat_name"] = $cat_name["cat_name"];

			//上传人
			$cat_name = M("users")->where("id=".$items[$i]["survey_heir"])->find();
			$items[$i]["username"] = $cat_name["username"];

			//使用统计（项目调研  系统问卷）
			$project_survey = M("project_survey")->field("count(survey_id) as num")->where("survey_id=".$items[$i]["id"])->select();
			$research_survey = M("research")->field("count(survey_id) as num")->where("survey_id=".$items[$i]["id"])->select();
			$items[$i]["use_num"] = $project_survey[0]["num"] + $research_survey[0]["num"];

			//获取终审人
			if($param["status"] == 3){
				$auditWhere["type"] = 4;
				$auditWhere["correlate_id"] = $items[$i]["id"];
				$audit = M("audit")->where($auditWhere)->find();
				//状态：0:待审核 1:一审通过 2:二审通过 3:三审通过 4:一审拒绝 5:二审拒绝 6:三审拒绝',
				$audituser_id = 0;
				if($audit["audit_status"] == "4"){
					$audituser_id = $audit["levalone_man"];
				}else if($audit["audit_status"] == "5"){
					$audituser_id = $audit["levaltwo_man"];
				}else if($audit["audit_status"] == "6"){
					$audituser_id = $audit["levalthree_man"];
				}
				$audit_user = "";
				if($audituser_id > 0){
					$auditUser = M("users")->where("id=".$audituser_id)->find();
					$audit_user = $auditUser["username"];
				}
				$items[$i]["audit_user"] = $audit_user;
			}
		}
		
		//输出分页
		$count = M("survey")->where($where)->count();
		
		$pageNav = "";
		if($count > $param["pageLen"]){
			$pageNav = $this->pageClass($count[0]["num"], $param["pageLen"]);
		}
		
		$cat_list = M('survey_category')->field('id,cat_name')->select();
		
		$data = array('pageNav' => $pageNav, 'list' => $items, "cat_list"=>$cat_list);
		return $data;
	}
	
	/**
	 * 
	 */
	public function setAvailable($param){
		$where["id"] = $param["survey_id"];
		$data["is_available"] = $param["is_available"];
		M("survey")->where($where)->save($data);
		$data = array('code' => 1000, "message"=>"ok");
		return $data;
	}	
	/**
	 * 
	 */
	public function import($excelCon){
		$user_id = $_SESSION["user"]["id"];
		$itemNum = count($excelCon) - 4;
		if($itemNum < 1){
			$return = array("code" =>1028, "message"=>"请填写问卷题目");
			return $return;
		}
		
		$catName = trim($excelCon[2][0]);
		if($catName == "" || $catName == "分类填写位置"){
			$return = array("code" =>1029, "message"=>"请填写问卷分类");
			return $return;
		}
		$surveyName = trim($excelCon[2][1]);
		if($surveyName == "" || $surveyName == "名称填写位置"){
			$return = array("code" =>1029, "message"=>"请填写问卷名称");
			return $return;
		}
		$surveyDesc = trim($excelCon[2][2]);
		if($surveyDesc == "简介填写位置"){
			$surveyDesc = "";
		}
		
		$catWhere["cat_name"] = $catName;
		$hasCat = M("survey_category")->where($catWhere)->find();
		if($hasCat){
			$survey_cat_id = $hasCat["id"];
		}else{
			$catData["cat_name"] = $catName;
			$survey_cat_id = M("survey_category")->add($catData);
			if(!$survey_cat_id){
				$return = array("code" =>1030, "message"=>"问卷分类保存失败，请稍后重试");
				return $return;
			}
		}
		
		$surWhere["auth_user_id"] = array("eq", $user_id);
		$surWhere["survey_name"] = $surveyName;
		$hasSurvey = M("survey")->where($surWhere)->find();
		if($hasSurvey){
			$return = array("code" =>1033, "message"=>"问卷名称已存在，请修改文件中问卷名称");
			return $return;
		}
		
		$surData["survey_name"] = $surveyName;
		$surData["survey_cat_id"] = $survey_cat_id;
		$surData["survey_heir"] = $user_id;
		$surData["survey_upload_time"] = date("Y-m-d H:i:s");
		$surData["status"] = 5;
		$surData["survey_desc"] = $surveyDesc;
		$orderno = D('Trigger')->orderNumber(4);
		$surData["orderno"] = $orderno;
		$surData["auth_user_id"] = $user_id;
		$survey_id = M("survey")->add($surData);
		if(!$survey_id){
			$return = array("code" =>1031, "message"=>"问卷名称保存失败，请稍后重试");
			return $return;
		}
		
		for ($i=4; $i<$itemNum; $i++){
			$thisItem = $excelCon[$i];
			$hasVal = trim(implode("", $thisItem));
			if(!$hasVal){
				continue;
			}
		
			$thisItem[0] = $thisItem[0] + 0;
			if($thisItem[0] != 1 && $thisItem[0] != 2 && $thisItem[0] != 3){
				continue;
			}
		
			$thisItem[1] = trim($thisItem[1]);
		
			$thisItem[2] += 0;
			if($thisItem[2] != 2){
				$thisItem[2] = 1;
			}
		
			$thisItem[3] += 0;
			if($thisItem[3] != 2){
				$thisItem[3] = 1;
			}
		
			$thisItem[4] += 0;
			if($thisItem[4] < 0 && $thisItem[4] > 8){
				$thisItem[4] = 0;
			}
		
			$itemData["survey_id"] = $survey_id;
			$itemData["title"] = $thisItem[1];
			$itemData["is_must"] = $thisItem[2];
			$itemData["item_type"] = $thisItem[3];
			$itemData["verify_type"] = $thisItem[4];
			$itemData["classification"] = $thisItem[0];
			$itemData["ctime"] = date("Ymd");
			$item_id = M("survey_item")->add($itemData);
			//单选 多选保存选项
			if($item_id && $thisItem[0] != 3){
				$optNum = count($thisItem) - 5;
				$orders = 0;
				for ($j=5; $j<$optNum; $j++){
					$optStr = trim($thisItem[$j]);
					if($optStr){
						$optData["item_id"] = $item_id;
						$optLetter = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
						$optData["letter"] = $optLetter[$orders];
						$optData["option"] = $optStr;
						$optData["orders"] = $orders;
						M("survey_item_opt")->add($optData);
						$orders ++;
					}
				}
			}
		}
		
		$return = array("code" =>1000, "message"=>"导入成功");
		return $return;
	}
	
	/**
	 * 查看问卷详情
	 */
	public function detail($id){
		$survey = M("survey")->where("id=".$id)->find();
		if($survey){
			$surveyItem = M("survey_item")->where("survey_id=".$id)->order("orders asc")->select();
			foreach ($surveyItem as $key=>$value){
				if($value["classification"] == 1 || $value["classification"] == 2){
					//单选 多选获取选项
					$itemOpt = M("survey_item_opt")->where("item_id=".$value["id"])->order("orders asc")->select();
					$surveyItem[$key]["option"] = $itemOpt;
				}
			}
			
			//获取问卷分类
			$cats = M("survey_category")->where("id=".$survey["survey_cat_id"])->find();
			$survey["cat_name"] = $cats["cat_name"];
		}
		
		return array("survey"=>$survey, "item"=>$surveyItem);
	}
	
	/**
	 * 新建问卷页面
	 */
	public function createPage(){
		//获取问卷分类
		$cats = M("survey_category")->select();
		return array("cats"=>$cats);
	}
	
	/**
	 * 新建问卷
	 */
	public function createSurvey($param){
		$user_id = $_SESSION["user"]["id"];
		$where["survey_name"] = $param["survey_name"];
		$where["auth_user_id"] = array("eq", $user_id);
		if($param["survey_id"]){
			//修改
			$where["id"] = array("neq", $param["survey_id"]);
			$has = M("survey")->where($where)->find();
			if($has){
				return array("code"=>1021, "message"=>"问卷名称已存在，请更换");
			}
			$data["survey_heir"] = $user_id;
			$data["survey_upload_time"] = date("Y-m-d H:i:s");
			$data["survey_name"] = $param["survey_name"];
			$data["survey_desc"] = $param["survey_desc"];
			$data["survey_cat_id"] = $param["survey_cat_id"];
			$data["status"] = $param["status"];
			$data["status"] = $param["status"];
			$data['auth_user_id'] = $_SESSION['user']['id'];
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$param["survey_upload_time"] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}
			M("survey")->where("id=".$param["survey_id"])->save($data);
			return array("code"=>1000, "message"=>"ok", "lastId"=>$param["survey_id"]);
		}else{
			//新建
			$has = M("survey")->where($where)->find();
			if($has){
				return array("code"=>1021, "message"=>"问卷名称已存在，请更换");
			}
			$param["survey_heir"] = $user_id;
			$param["survey_upload_time"] = date("Y-m-d H:i:s");
			$orderno = D('Trigger')->orderNumber(4);
			$param["orderno"] = $orderno;
			$param['auth_user_id'] = $_SESSION['user']['id'];
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$param['id'] = getNextId('survey');
				$param["survey_upload_time"] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}
			$lastId = M("survey")->add($param);
			if($lastId){
				return array("code"=>1000, "message"=>"ok", "lastId"=>$lastId);
			}else{
				return array("code"=>1022, "message"=>"system error,请稍后重试");
			}
		}
		
	}
	
	/**
	 * 编辑问卷页面
	 */
	public function editPage($id){
		//获取问卷分类
		$cats = M("survey_category")->select();
		
		$survey = M("survey")->where("id=".$id)->find();
		if($survey){
			$surveyItem = M("survey_item")->where("survey_id=".$id)->order("orders asc")->select();
			foreach ($surveyItem as $key=>$value){
				if($value["classification"] == 1 || $value["classification"] == 2){
					//单选 多选获取选项
					$itemOpt = M("survey_item_opt")->where("item_id=".$value["id"])->order("order asc")->select();
					$surveyItem[$key]["option"] = $itemOpt;
				}
			}
			$thisCats = M("survey_category")->where("id=".$survey["survey_cat_id"])->find();
			$survey["cat_name"] = $thisCats["cat_name"];
		}
		
		return array("survey"=>$survey, "item"=>$surveyItem, "cats"=>$cats);
	}
	
	/**
	 * 保存题目
	 */
	public function createItem($param){
		$item_id = (int)$param["item_id"];
		$itemData["survey_id"] = $param["survey_id"];
		$itemData["title"] = $param["title"];
		$itemData["img"] = $param["img"];
		$itemData["is_must"] = $param["is_must"];
		$itemData["item_type"] = $param["item_type"];
		$itemData["verify_type"] = $param["verify_type"];
		$itemData["classification"] = $param["classification"];
		$itemData["orders"] = $param["orders"];
		$itemData["ctime"] = time();
		if($item_id > 0){
			M("survey_item")->where("id=".$item_id)->save($itemData);
			
			M("survey_item_opt")->where("item_id=".$item_id)->delete();
		}else{
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$itemData['id'] = getNextId('survey_item');
			}
			
			$item_id = M("survey_item")->add($itemData);
			if(!$item_id){
				return array("code"=>1021, "message"=>"问卷题目创建失败");
			}
		}
		
		if($param["classification"] == 1 || $param["classification"] == 2){
			$optNum = count($param["option"]);
			for ($i=0; $i<$optNum; $i++){
				$option = $param["option"][$i];
				$opt_img = $param["opt_img"][$i];
				$optLetter = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
				$optData["item_id"] = $item_id;
				$optData["letter"] = $optLetter[$i];
				$optData["option"] = $option;
				$optData["opt_img"] = $opt_img;
				$optData["order"] = $i;
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$optData['id'] = getNextId('survey_item_opt');
				}
				$insertId = M("survey_item_opt")->add($optData);
			}
		}
	}
	
	/*
	 * 删除题目
	 */
	public function delItem($itemId){
		$delNum = M("survey_item_opt")->where("item_id=".$itemId)->delete();
		if($delNum > 0){
			M("survey_item")->where("id=".$itemId)->delete();
		}
		return array("code"=>1000, "message"=>"ok");
	}
}