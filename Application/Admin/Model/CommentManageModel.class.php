<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 评论管理
 * @author Dujuqiang 20170327
 */
class CommentManageModel extends BaseModel{
	//初始化
	public function __construct(){}
	
    /**
     * 获取图表数据及数据列
     * page 当前页码，不传默认为1
     * pageLen 每页数据条数，不传默认15
     */
	public function index($param){
		$user_id = $_SESSION["user"]["id"];
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 15;
		$start = ($param["page"] - 1) * $param["pageLen"];
		
		//获取课程分类(三级分类，剩下的作为平级)
		$courseCat = M("course_category")->where("pid=0")->select();
		foreach ($courseCat as $k1=>$v1){
			$subCat1 = M("course_category")->where("pid=".$v1["id"])->select();
			foreach ($subCat1 as $k2=>$v2){
				$subCids = self::getCourseChild($v2["id"], $v2["id"].",");
				$subCids = substr($subCids, 0, -1);
				$subCat2 = M("course_category")->where("pid IN (".$subCids.")")->select();
		
				$subCat1[$k2]["sub_cat"] = $subCat2;
			}
			$courseCat[$k1]["sub_cat"] = $subCat1;
		}
		$where = array();
		//$where['a.user_id'] = $user_id;
		$where['b.status'] = array("eq",1);
		$where['b.auditing'] = array("eq",1);
		$where['a.comment_content'] = array("neq", '');
		if(!empty($param["keyword"])){
			$where["b.course_name"] = array("like", "%".$param["keyword"]."%");
		}
		
		if(!empty($param["cid"])){
			//课程分类-获取子类
			$cids = self::getCourseChild($param["cid"], $param["cid"].",");
			$cids = substr($cids, 0, -1);
			$where["course_cat_id"] = array("in", $cids);
		}
		

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

		$where['b.auth_user_id'] = array("in",$specifiedUser);

		$list = M("colligate_comment a")->field("DISTINCT(a.course_id),b.*,c.cat_name")
			->join("JOIN __COURSE__ b ON a.course_id=b.id")
			->join("LEFT JOIN __COURSE_CATEGORY__ c ON b.course_cat_id = c.id")
			->where($where)->limit($start, $param["pageLen"])->select();
		
		$count = M("colligate_comment a")->field("DISTINCT(a.course_id),b.*")
			->join("JOIN __COURSE__ b ON a.course_id=b.id")
			->join("LEFT JOIN __COURSE_CATEGORY__ c ON b.course_cat_id = c.id")
			->where($where)->count();
		
		//隔离数据过滤
        $list = D('IsolationData')->isolationData($list);

		//输出分页
		$pageNav = $this->pageClass($count, $param["pageLen"]);
		
		$data = array('pageNav' => $pageNav, 'list' => $list, "courseCat"=>$courseCat);
		return $data;
	}
	
	//获取课程分类子类
	public function getCourseChild($cid, $cidStr){
		$cid += 0;
		if(!is_int($cid) || $cid < 0){
			return false;
		}
		
		$cat = M("course_category")->where("pid=".$cid)->select();
		if($cat){
			foreach ($cat as $key=>$v){
				$cidStr .= $v["id"] . ",";
				$cidStr = self::getCourseChild($v["id"], $cidStr);
			}
		}
		return $cidStr;
	}
}