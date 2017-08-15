<?php
namespace Admin\Model;
use Common\Model\BaseModel;

/**
 * 组织架构-用户的导入
 * @author Dujunqiang 20170511
 */
class UserImportModel extends BaseModel{
	protected $tablePrefix = 'aaaa_';
	protected $tableName= 'user_import';
	
	//初始化
	public function __construct(){}
	
	//添加用户
	public function addUser($param){
		if(!$param){
			return false;
		}
		$user_id = $_SESSION["user"]["id"];
		
		$status = 1;//数据是否有效 0无效 1有效
		$error_type = 0;//无效类型 1数据不完善 2系统中已存在 3文件中重复 4手机号格式有误 5邮箱格式有误 6导入异常
		if($param["email"] != ""){
			if(!filter_var($param["email"], FILTER_VALIDATE_EMAIL)){
				$status = 0;
				$error_type = 5;
			}else{
				//文件中重复
				$where["email"] = $param["email"];
				if($param["id"]){
					$where["id"] = array("neq", $param["id"]);
				}
				$hasFile = M("user_import")->field("email")->where($where)->find();
				if($hasFile){
					$status = 0;
					$error_type = 3;
				}
				
				//系统中已存在
				$sysWhere["email"] = $param["email"];
				$hasSys = M("users")->field("email")->where($sysWhere)->find();
				if($hasSys){
					$status = 0;
					$error_type = 2;
				}
			}
		}
		
		if($param["name"] == "" || $param["email"] == ""){
			$status = 0;
			$error_type = 1;
		}
		
		$param["user_id"] = $user_id;
		$param["status"] = $status;
		$param["error_type"] = $error_type;
		$param["add_time"] = date("Y-m-d H:i:s");
		if($param["id"]){

			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$param['add_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}

			M("user_import")->where("id=".$param["id"])->save($param);
		}else{

			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$param['id'] = getNextId('auth_group');
				$param['add_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}

			M("user_import")->add($param);
		}
		
		return array("code"=>1000, "message"=>"ok", "error_type"=>$error_type);
	}
	
	//获取导入结果
	public function importPage($param){
		$user_id = $_SESSION["user"]["id"];
		$totalNum = M("user_import")->field("count(id) as num")->where("user_id=".$user_id)->select();
		$successNum = M("user_import")->field("count(id) as num")->where("user_id=".$user_id." and status='1'")->select();
		$totalNum = $totalNum[0]["num"];
		$successNum = $successNum[0]["num"];
		
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 20;
		$start = ($param["page"] - 1) * $param["pageLen"];
		
		$where["user_id"] = $user_id;
		if($param["type"] > 0 && $param["type"] < 5){
			$where["status"] = 0;
			$where["error_type"] = $param["type"];
		}
		if($param["type"] == 10){
			$where["status"] = 1;
		}
		$list = M("user_import")->where($where)->limit($start, $param["pageLen"])->select();
		
		//输出分页
		$count = M("user_import")->field("count(id) as num")->where($where)->select();
		$pageNav = "";
		if($count[0]["num"] > $param["pageLen"]){
			$pageNav = $this->pageClass($count[0]["num"], $param["pageLen"]);
		}
		
		//获取部门组织架构
		//三级（分公司）
		$company_list = array();
		$center_id = $_COOKIE["center_id"];
		if(!$center_id){
			$data = array('code'=>1021, "message"=>"未获取到要导入的层级");
			return $data;
		}
		$tissue2 = M("tissue_rule")->field("id,pid,name")->where("pid=".$center_id)->select();
		foreach($tissue2 as $key2=>$value2){
			$company_list[$key2]["id"] = $value2["id"];
			$company_list[$key2]["name"] = $value2["name"];
			$company_list[$key2]["is_part"] = 0;
	
			//四级（分公司部门）
			$tissue3 = M("tissue_rule")->field("id,pid,name")->where("pid=".$value2["id"])->select();
			if($tissue3){
				$company_list[$key2]["is_part"] = 1;
			}
		}
		
		$data = array('pageNav' => $pageNav, 'list' => $list, "totalNum"=>$totalNum, "successNum"=>$successNum, "company_list"=>$company_list);
		return $data;
	}
	
	//移除临时表用户
	public function delUser($param){
		$user_id = $_SESSION["user"]["id"];
		
		$delNum = 0;
		foreach ($param as $key=>$value){
			$value += 0;
			if(is_int($value)){
				M("user_import")->where("id=".$value)->delete();
				$delNum ++;
			}
		}
		
		return array("code"=>1000, "message"=>"ok", "delNum"=>$delNum);
	}
	
	//编辑错误数据
	public function editData($param){
		$resp = $this->addUser($param);
		return $resp;
	}
	
	//保存有效结果
	public function saveValid(){
		$user_id = $_SESSION["user"]["id"];
		while (true){
			$userImport = M("user_import")->where("user_id=".$user_id." and status='1'")->select();
			if(!$userImport){
				break;
			}
			
			$center_id = $_COOKIE["center_id"];
			foreach ($userImport as $value){
				//验证分公司 部门(保存在两个中心下  稽核中心 共享中心)
				$tissueId = null;
				if($value["company"] && $value["part"]){
					$hasTissue = M("tissue_rule")->where("name='".$value["company"]."' and pid=".$center_id)->find();
					if(!$hasTissue){
						$tissueData["name"] = $value["company"];
						$tissueData["pid"] = $center_id;
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$tissueData['id'] = getNextId('auth_group');
						}
						$tissueId = M("tissue_rule")->add($tissueData);
						if(!$tissueId){
							M("user_import")->where("id=".$value["id"])->save(array("status"=>"0", "error_type"=>"6"));
							continue;
						}
					}else{
						$tissueId = $hasTissue["id"];
					}
					
					$hasPart = M("tissue_rule")->where("name='".$value["part"]."' and pid=".$tissueId)->find();
					if(!$hasPart){
						$tissueData["name"] = $value["part"];
						$tissueData["pid"] = $tissueId;
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$tissueData['id'] = getNextId('auth_group');
						}
						$tissueId = M("tissue_rule")->add($tissueData);
						if(!$tissueId){
							M("user_import")->where("id=".$value["id"])->save(array("status"=>"0", "error_type"=>"6"));
							continue;
						}
					}else{
						$tissueId = $hasPart["id"];
					}
				}
				
				$hasSys = M("users")->field("email")->where("email='".$value["email"]."'")->find();
				if($hasSys){
					M("user_import")->where("id=".$value["id"])->save(array("status"=>"0", "error_type"=>"2"));
					continue;
				}
				$userData["username"] = $value["name"];
				$userData["password"] = md5("123456");//初始密码
				$userData["avatar"] = "/Upload/avatar/default.png";
				$userData["email"] = $value["email"];
				$userData["phone"] = $value["phone"];
				$userData["status"] = 1;
				$userData["register_time"] = date("Y-m-d H:i:s");
				$userData["sequence"] = $value["sequence"];
				$userData["birthday"] = $value["birthday"];
				$userData["gender"] = $value["sex"];
				$userData["age"] = $value["age"];
				$userData["group_time"] = $value["group_time"];
				$userData["center_time"] = $value["center_time"];
				$userData["area"] = $value["area"];
				$userData["room"] = $value["room"];
				$userData["rank"] = $value["job_level"];
				$userData["education"] = $value["edu"];
				$userData["mobilephone"] = $value["phone"];
				$userData["tel"] = $value["office_phone"];
				$userData["ip_phone"] = $value["ip_phone"];

				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$userData['id'] = getNextId('auth_group');
					$userData["register_time"] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
					$userData["birthday"] = array('exp',"to_date('".$value["birthday"]."','yy-mm')");
					$userData["group_time"] = array('exp',"to_date('".$value["group_time"]."','yy-mm-dd')");
					$userData["center_time"] = array('exp',"to_date('".$value["center_time"]."','yy-mm-dd')");
				}

				$userId = M("users")->add($userData);

				if($userId){
					M("auth_group_access")->add(array("user_id"=>$userId, "group_id"=>3));
				}
				
				$job_id = 0;
				if($value["job_name"]){
					$jobWhere["name"] = $value["job_name"];
					$hasJob = M("jobs_manage")->where($jobWhere)->find();
					if($hasJob){
						$job_id = $hasJob["id"];
					}else{
						$jobData["name"] = $value["job_name"];
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$jobData['id'] = getNextId('auth_group');
						}
						$jobAdd = M("jobs_manage")->add($jobData);
						if($jobAdd){
							$job_id = $jobAdd;
						}
					}
				}
				
				if($userId && $tissueId){
					$tiruleData["user_id"] = $userId;
					$tiruleData["tissue_id"] = $tissueId;
					$tiruleData["manage_id"] = 0;
					$tiruleData["job_id"] = $job_id;
					$gid = M("tissue_group_access")->add($tiruleData);
				}
				
				if($userId && !$tissueId){
					$tiruleData["user_id"] = $userId;
					$tiruleData["tissue_id"] = 0;
					$tiruleData["manage_id"] = 0;
					$gid = M("tissue_group_access")->add($tiruleData);
				}

				M("user_import")->where("id=".$value["id"])->delete();
			}
		}
		return array("code"=>1000, "message"=>"ok");
	}
	
	//取消导入--分批删除
	public function cancelImport(){
		$user_id = $_SESSION["user"]["id"];
		while (true){
			$has = M("user_import")->where("user_id=".$user_id)->find();
			if(!$has){
				break;
			}
			
			M("user_import")->where("user_id=".$user_id)->delete();
		}
		
		return array("code"=>1000, "message"=>"ok");
	}
	
	//获取公司部门
	public function getPart($tid){
		$tissue3 = M("tissue_rule")->field("id,pid,name")->where("pid='".$tid."'")->select();
		if($tissue3){
			$part_list = array();
			foreach($tissue3 as $key3=>$value3){
				$part_list[$key3]["id"] = $value3["id"];
				$part_list[$key3]["name"] = $value3["name"];
			}
		}
		
		return array("code"=>1000, "message"=>"ok", "part_list"=>$part_list);
	}
}