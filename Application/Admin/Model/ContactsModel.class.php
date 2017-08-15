<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 通讯录
 * @author Dujuqiang 20170327
 */
class ContactsModel extends BaseModel{
	//初始化
	public function __construct(){}
	
	/**
	 * 获取通讯录数据
	 */
	public function getData($param){
		$user_id = $_SESSION["user"]["id"];
		$tissue = M("tissue_group_access a")->field("b.id,b.pid,b.name")
			->join("JOIN __TISSUE_RULE__ b ON a.tissue_id=b.id")
			->where("user_id=".$user_id)->limit(1)->select();
		if(!$tissue){
			return array(
				"code"=>1000,
				"data"=>"", 
				"unset"=>0, 
				"adminList"=>"", 
				"root_name"=>"", 
				"root_id"=>""
			);
		}else{
			if($tissue[0]["id"] == 1){
				$getPid = "1";
			}else{
				$getPid = self::getRulePid($tissue[0]["id"]);
				$getPid = $getPid["pid"];
			}
		}
		
		$root = M("tissue_rule")->field("id,pid,name")->where("id=".$getPid)->select();
		$rootName = $root[0]["name"];
		$rootId = $root[0]["id"];
		
		$tissue =  M("tissue_rule")->field("id,pid,name")->where("pid=".$rootId)->select();
		$part = array();
		foreach($tissue as $key=>$value){
			$ids = self::getCourseChild($value["id"], $value["id"].",");
			$ids = substr($ids, 0, -1);
			
			$partNum1 = M("tissue_group_access a")
						->field("count(user_id) as num")
						->join("JOIN __USERS__ b ON a.user_id=b.id")
						->where("b.status='1' and tissue_id IN (" .$ids. ")")
						->select();
			$part[$key]["part_num"] = $partNum1[0]["num"];
			$part[$key]["id"] = $value["id"];
			$part[$key]["pid"] = $value["pid"];
			$part[$key]["name"] = $value["name"];
			
			//三级分类，剩下的作为平级
			$subPart = array();
			$tissue1 =  M("tissue_rule")->field("id,pid,name")->where("pid=".$value["id"])->select();
			foreach ($tissue1 as $k1=>$v2){
				$ids1 = self::getCourseChild($v2["id"], $v2["id"].",");
				$ids1 = substr($ids1, 0, -1);
				
				$partNum2 = M("tissue_group_access a")
							->field("count(user_id) as num")
							->join("JOIN __USERS__ b ON a.user_id=b.id")
							->where("b.status='1' and tissue_id IN (" .$ids1. ")")
							->select();
				$subPart[$k1]["part_num"] = $partNum2[0]["num"];
				$subPart[$k1]["id"] = $v2["id"];
				$subPart[$k1]["pid"] = $v2["pid"];
				$subPart[$k1]["name"] = $v2["name"];

				$tissue2 =  M("tissue_rule")->field("id,pid,name")->where("pid=".$v2["id"])->select();

				$sub_items = array();

				foreach($tissue2 as $k2=>$v3){

					$ids2 = self::getCourseChild($v3["id"], $v3["id"].",");

					$ids2 = substr($ids2, 0, -1);

					$partNum3 = M("tissue_group_access a")
								->field("count(user_id) as num")
								->join("JOIN __USERS__ b ON a.user_id=b.id")
								->where("b.status='1' and tissue_id IN (" .$ids2. ")")
								->select();

					$sub_items[$k2]["part_num"] = $partNum3[0]["num"];
					$sub_items[$k2]["id"] = $v3["id"];
					$sub_items[$k2]["pid"] = $v3["pid"];
					$sub_items[$k2]["name"] = $v3["name"];

				}

				$subPart[$k1]['sub_items'] = $sub_items;

			}
			
			$part[$key]["sub_list"] = $subPart;
		}
		
		//获取该公司下的所有管理员
		$ids2 = self::getCourseChild($getPid, $getPid.",");
		$ids2 = substr($ids2, 0, -1);
		$adminList = M("tissue_group_access a")
			->field("a.user_id,b.username,b.avatar,a.manage_id")
			->join("JOIN __USERS__ b ON a.user_id=b.id")
			->where("tissue_id IN (". $ids2 .") AND manage_id!=0")->select();
		
		//未分配人员
		$unset = M("users a")
				->field("count(a.id) as num")
				->join("LEFT JOIN __TISSUE_GROUP_ACCESS__ b ON a.id=b.user_id")
				->where("(b.user_id is NULL or b.tissue_id='0') AND a.status=1")
				->select();
		return array(
			"code"=>1000,
			"data"=>$part, 
			"unset"=>$unset[0]["num"], 
			"adminList"=>$adminList, 
			"root_name"=>$rootName, 
			"root_id"=>$rootId
		);
	}
	
	/**
	 * 获取部门数据
	 * id 
	 * page
	 */
	public function getPart($param){
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 15;
		$start = ($param["page"] - 1) * $param["pageLen"];
		
		if($param["keyword"]){
			$where["a.username"] = array("like", "%".$param["keyword"]."%");
		}
		
		if($param["id"] == "unset"){
			$where["_string"] = "(b.user_id is NULL or b.tissue_id='0') AND a.status=1";
		}else{
			$param["id"] += 0;
			if($param["id"] < 1 || !is_int($param["id"])){
				$where["_string"] = "a.id!=''";
			}else{
				$ids = self::getCourseChild($param["id"], $param["id"].",");
				$ids = substr($ids, 0, -1);
				$where["_string"] = "b.tissue_id IN (". $ids .")";
			}
			$where['a.status'] = array("eq",1);
		}
		
		$list = M("users a")
			->field("a.username,a.id as user_id,a.avatar,a.email,a.phone,a.mobilephone,a.job_number,b.tissue_id,b.job_id,a.tel")
			->join("JOIN __TISSUE_GROUP_ACCESS__ b ON a.id=b.user_id")
			->where($where)
			->order("a.id")
			->limit($start, $param["pageLen"])
			->select();
//		dump($list);
		foreach ($list as $key=>$value){
			if(!$list[$key]["tel"]){
				$list[$key]["tel"] = "--";
			}
			
			$list[$key]["details"] = U('details',array('user_id'=>$value['user_id']));
			if($value["tissue_id"]){
				$part = M("tissue_rule")->field("name")->where("id=".$value["tissue_id"])->limit(1)->select();
				$list[$key]["part_name"] = $part[0]["name"];
			}else{
				$list[$key]["part_name"] = "--";
			}
			
			if($value["job_id"]){
				$part = M("jobs_manage")->field("name")->where("id=".$value["job_id"])->limit(1)->select();
				$list[$key]["job_name"] = $part[0]["name"] ? $part[0]["name"] : "--";
			}else{
				$list[$key]["job_name"] = "--";
			}
			
			if(!$list[$key]["job_id"]){
				$list[$key]["job_id"] = "--";
			}
			
			if(!$list[$key]["email"]){
				$list[$key]["email"] = "--";
			}
			if($list[$key]["mobilephone"]){
				$list[$key]["phone"] = $list[$key]["mobilephone"];
			}
			
			if($list[$key]["phone"] == 0){
				$list[$key]["phone"] = "--";
			}
		}
		
		$count = M("users a")->field("a.id")
			->join("LEFT JOIN __TISSUE_GROUP_ACCESS__ b ON a.id=b.user_id")
			->where($where)
			->select();
		
		$pageNav = "";
		$count = count($count);
		if($count >= $param["pageLen"]){
			$pageNav = $this->pageClass($count,$param["pageLen"]);
		}
		$return = array("code"=>1000, "list"=>$list, "pageNav"=>$pageNav);
		return $return;
	}
	
	//根据用户组织ID获取所在中心
	public function getRulePid($pid){
		$pid += 0;
		if(!is_int($pid)){
			return array("code"=>1031, "message"=>"未获取到组织id");
		}
		$group = M("tissue_rule")->field("id,pid,name")->where("id=".$pid)->select();
		if(!$group){
			return array("pid" => $pid);
		}else{
			if($group[0]["pid"] != 1){
				return self::getRulePid($group[0]["pid"]);
			}else{
				return array("pid" => $group[0]["id"]);
			}
		}
	}
	
	//获取子类
	public function getCourseChild($cid, $cidStr){
		$cid += 0;
		if(!is_int($cid) || $cid < 0){
			return false;
		}
	
		$cat = M("tissue_rule")->where("pid=".$cid)->select();
		if($cat){
			foreach ($cat as $key=>$v){
				$cidStr .= $v["id"] . ",";
				$cidStr = self::getCourseChild($v["id"], $cidStr);
			}
		}
		return $cidStr;
	}
	
	/**
	 * 分组通讯录列表
	 * id 部门id 必须,未分配时id=unset,全部id不传
	 */
	public function groupList($param,$user_id){
		if(!$param || !$user_id){
			return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
		}
	
		$param["id"] += 0;
		if(!$param["id"]){
			return array("code"=>1022, "message"=>'缺少参数: id 部门id');
		}
	
		$list = M("tissue_group_access a")
		->field("a.tissue_id,a.job_id,b.username,b.id as user_id,b.avatar,b.email,b.phone,b.job_number")
		->join("JOIN __USERS__ b ON a.user_id=b.id")
		->where("a.tissue_id=".$param["id"])->select();
		if($list){
			foreach ($list as $key=>$value){
				$part = M("tissue_rule")->field("name")->where("id=".$value["tissue_id"])->limit(1)->select();
				$list[$key]["part_name"] = $part[0]["name"];
	
				$part = M("jobs_manage")->field("name")->where("id=".$value["job_id"])->limit(1)->select();
				$list[$key]["job_name"] = $part[0]["name"];
			}
			return array("code"=>1000, "message"=>'操作成功', "data"=>$list);
		}else{
			return array("code"=>1023, "message"=>'当前部门没有成员');
		}
	}
	
	/**
	 * 个人详情页
	 */
	public function details(){
		$data = M('users')
				->alias('a')
				->join('left join __TISSUE_GROUP_ACCESS__ b on a.id=b.user_id')
				->join('left join __JOBS_MANAGE__ c on b.tissue_id=c.tissue_id')
				->where(array('a.id'=>I('get.user_id')))
				->find();
		return $data;
	}
}