<?php
namespace Mobile\Model;

use Think\Model;

/**
 * @author Dujunqiang 20170308
 * 我的学员--我的调研
 */

class ContactsModel extends CommonModel {
    protected $tablePrefix = 'think_';
    protected $tableName = 'tissue_rule';

    //自动验证
    protected $_validate = array(
        array('username', 'empty', '用户名不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('username', '5,100', '用户名长度超出限制', Model::EXISTS_VALIDATE, 'length'),
        array('phone', '/1[34578]{1}\d{9}$/', '请输入正确的手机号格式', Model::EXISTS_VALIDATE, 'regex'),
        array('password', 'empty', '密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('confirm', 'empty', '确认密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('password', 'checkValid', '密码不能有中文', Model::EXISTS_VALIDATE, 'callback'),
        array('oldPassword', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'isPassword', '只允许输入6-20位由 数字、字母、下划线组成的密码', Model::EXISTS_VALIDATE, 'callback'),
        array('phone', 'empty', '手机号码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('phone', 'is_numeric', '手机号码必须为数字', Model::EXISTS_VALIDATE, 'function')
    );

	/**
	 * 通讯录首页
	 */
    public function index($uid){
        if(!$uid){
            return array("code"=>1021, "message"=>'提交失败，未获取到用户id');
        }
        $part = array();

        //一级
        $tissue1 = M("tissue_rule")->field("id,pid,name")->where("pid=0")->limit(1)->find();

        $part["id"] = $tissue1["id"];
        //$part["pid"] = $tissue1[0]["pid"];
        $part["name"] = $tissue1["name"];

       // foreach($tissue1 as $key=>$value){
            //二级（总公司部门 / 分公司）
            $tissue2 = M("tissue_rule")->field("id,pid,name")->where("pid=".$tissue1["id"])->select();
            $key2 = 0;
            $sub_list = array();
            foreach($tissue2 as $key2=>$value2){
                $sub_list[$key2]["id"] = $value2["id"];
                //$sub_list[$key2]["pid"] = $value2["pid"];
                $sub_list[$key2]["name"] = $value2["name"];
                $sub_list[$key2]["is_part"] = 0;

                //三级（分公司部门）
                $tissue3 = M("tissue_rule")->field("id,pid,name")->where("pid=".$value2["id"])->select();
                if($tissue3){
                    $key3 = 0;
                    $sub_list2 = array();
                    foreach($tissue3 as $key3=>$value3){
                        $sub_list2[$key3]["id"] = $value3["id"];
                        //$sub_list2[$key3]["pid"] = $value3["pid"];
                        $sub_list2[$key3]["name"] = $value3["name"];
                        $key3 ++;
                    }

                    $sub_list[$key2]["is_part"] = 1;
                    $sub_list[$key2]["sub_list"] = $sub_list2;
                }

                $key2 ++;
            }
       // }
        $part["part_list"] = $sub_list;
        /*
        //获取该公司下的所有管理员
        $admin = M("tissue_group_access as a")
            ->field("a.uid,b.username,b.avatar")
            ->join("JOIN __USERS__ as b ON a.uid=b.id")
            ->where("manage_id=1")->select();
        $key = count($part);
        foreach ($admin as $akey=>$value){
            $part[$key]["uid"] = $admin[$akey]["uid"];
            $part[$key]["username"] = $admin[$akey]["username"];
            $part[$key]["dataType"] = 2;
            $key ++;
        } */
        return array("code"=>1000, "message"=>'操作成功', "data"=>$part);
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
	
	//根据用户ID获取公司第一层级
	public function getRulePid($pid){

		if(!is_int($pid) && $pid < 0){
			return array("code"=>1031, "message"=>"未获取到组织id");
		}
        //如果pid为0,根据父级pid查询对应id
        if($pid == 0){
            $group = M("tissue_rule")->field("id,pid,name")->where("pid=".$pid)->find();
            return array("pid" => $group["id"]);
        }else{
            $group = M("tissue_rule")->field("id,pid,name")->where("id=".$pid)->find();
            return self::getRulePid($group["pid"]);
        }

		/*if(!$group){
			return array("pid" => $pid);
		}else{
			if(!strstr($group[0]["name"], "分公司") || strstr(!$group[0]["name"], "公司")){
				return self::getRulePid($group[0]["pid"]);
			}else{
				return array("pid" => $group[0]["id"]);
			}
		}*/
	}
	
	/**
	 * 分组通讯录列表
	 * id 部门id 必须
	 */
	public function groupList($param,$uid){
		if(!$param || !$uid){
			return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
		}
		
		$param["id"] += 0;
		if(!$param["id"]){
			return array("code"=>1022, "message"=>'缺少参数: id 部门id');
		}
		
		$list = M("tissue_group_access a")
			->field("a.tissue_id,a.job_id,b.username,b.id as uid,b.avatar,b.email,b.phone,b.job_number")
			->join("JOIN __USERS__ b ON a.uid=b.id")
			->where(array("a.tissue_id" => $param["id"],'b.status' => 1))->select();
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
	 * 联系人详细信息
	 * uid 联系人id
	 */
	public function detail($uid){

		$list = M("tissue_group_access a")
			->field("a.tissue_id,a.job_id,b.username,b.id as uid,b.avatar,b.email,b.phone,b.job_number")
			->join("JOIN __USERS__ b ON a.uid=b.id")
			->where("a.uid=".$uid)->limit(1)->select();
		if($list){
			$part = M("tissue_rule")->field("pid,name")->where("id=".$list[0]["tissue_id"])->limit(1)->select();
            //如果是部门则执行这里
            if($part[0]['pid'] != 1){
                $parts = M("tissue_rule")->where("id=".$part[0]['pid'])->limit(1)->select();
                //拼接公司部门
                $list[0]["part_name"] = $parts[0]['name']."-".$part[0]["name"];
            }else{
                //如果是分公司则执行这里
                $list[0]["part_name"] = $part[0]["name"];
            }
			$partg = M("jobs_manage")->field("name")->where("id=".$list[0]["job_id"])->limit(1)->select();
			$list[0]["job_name"] = $partg[0]["name"];
			return array("code"=>1000, "message"=>'操作成功', "data"=>$list);
		}else{
			return array("code"=>1023, "message"=>'没有获取到相应用户，请确认uid是否属于当前公司');
		}
	}
	
	/**
	 * 联系人查询
	 * keyword 搜索关键字 必须
	 */
	public function search($param,$uid){
		if(!$param || !$uid){
			return array("code"=>1021, "message"=>'提交失败，未获取到参数或用户数据');
		}
		
		if(!$param["keyword"]){
			return array("code"=>1022, "message"=>'缺少参数： keyword 搜索关键字');
		}
		
		$tissue = M("tissue_group_access")->where("uid=".$uid)->limit(1)->select();
		if(!$tissue){
			return array("code"=>1023, "message"=>'当前用户没有加入组织');
		}
		
		$tissue = M("tissue_group_access a")
			->field("b.id,b.pid,b.name")
			->join("JOIN __TISSUE_RULE__ b ON a.tissue_id=b.id")->where("uid=".$uid)->limit(1)->select();

		if(!$tissue){
			return array("code"=>1022, "message"=>'您还没有加入组织');
		}
		$pid = $tissue[0]["id"];
		$getPid = self::getRulePid($pid);
		if(!$getPid){
			return array("code"=>1022, "message"=>'您还没有加入组织');
		}
		$getPid = $getPid["pid"];
		$part = M("tissue_rule");
		$part = $part->field("id,pid,name")->where("pid=".$getPid)->select();

        foreach($part as $key=>$value){

            $partNum = M("tissue_group_access a")->join("JOIN __USERS__ b ON a.uid=b.id")->field("count(a.uid) as num")->where(array("a.tissue_id" => $value["id"],'b.status' => 1))->select();
			$part[$key]["part_num"] = $partNum[0]["num"];
			$part[$key]["dataType"] = 1;
		}
		
		//获取该pid下的组织id（三层）
		$tissueIds = "";
		$tissueList = M("tissue_rule")->field("id")->where("pid=".$getPid)->select();
		foreach ($tissueList as $tk=>$tv){
			$tissueIds .= $tv["id"].",";
			$subList = M("tissue_rule")->field("id")->where("pid=".$tv["id"])->select();
			foreach ($subList as $sv){
				$tissueIds .= $sv["id"].",";
			}
		}
		$tissueIds = substr($tissueIds,0,-1);
		if($tissueIds == ""){
			$tissueIds = $getPid;
		}
		
		//获取该公司下的所有名称
		$users = M("tissue_group_access as a")
			->field("a.tissue_id,a.job_id,b.username,b.id as uid,b.avatar,b.email,b.phone,b.job_number")
			->join("JOIN __USERS__ as b ON a.uid=b.id")
			->where("a.tissue_id in(".$tissueIds.") AND username LIKE '%".$param["keyword"]."%'")->select();
		if(!$users){
			return array("code"=>1030, "message"=>'没有搜索到用户');
		}

		foreach ($users as $key=>$value){

			$part = M("tissue_rule")->where("id=".$value["tissue_id"])->limit(1)->select();
             //如果是部门则执行这里
            if($part[0]['pid'] != 1){
                $parts = M("tissue_rule")->where("id=".$part[0]['pid'])->limit(1)->select();
                //拼接公司部门
			    $users[$key]["part_name"] = $parts[0]['name']."-".$part[0]["name"];
            }else{
                //如果是分公司则执行这里
                $users[$key]["part_name"] = $part[0]["name"];
            }
            //查询相应岗位
			$partg = M("jobs_manage")->field("name")->where("id=".$value["job_id"])->limit(1)->select();
			$users[$key]["job_name"] = $partg[0]["name"];

		}

		return array("code"=>1000, "message"=>'操作成功', "data"=>$users);
	}
}