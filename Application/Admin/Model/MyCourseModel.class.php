<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 学习任务_我的课程
 * @author Dujuqiang 20170320
 */
class MyCourseModel extends BaseModel{
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

		//获取所属标签
		$tag_list = M("users_tag")->order("id desc")->select();
		
		//我的必修课程
		if($param["type"] == 1){
			$where = array();
			$where['a.status'] = array("eq",1);
			$where['a.auditing'] = array("eq",1);
			if(!empty($param["keyword"])){
				$where["a.course_name"] = array("like", "%".$param["keyword"]."%");
			}

			if(!empty($param["arrangement_id"])){
				//所属层次
				$where["arrangement_id"] = array("eq",$param["arrangement_id"]);
			}
			
			//线上线下课程
			if($param["way"] == "up"){
				$where["course_way"] = 0;
			}
			if($param["way"] == "down"){
				$where["course_way"] = 1;
			}
			
			if(!empty($param["cid"])){
				//课程分类-获取子类
				$cids = self::getCourseChild($param["cid"], $param["cid"].",");
				$cids = substr($cids, 0, -1);
				$where["course_cat_id"] = array("in", $cids);
			}
			$where["d.user_id"] = $user_id;
			$where["e.type"] = array("in","0,4");
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$field = "a.id,a.course_name,a.course_code,a.course_time,a.course_cat_id,a.lecturer,a.course_way,a.media_src,";
				$field .= "a.maker,a.chapter,a.course_cover,a.auditing,a.status,a.is_public,a.click_count,a.location,a.restrictions,";
				$field .= "a.lecturer_name,a.user_id,a.is_trigger,a.score,a.arrangement_id,a.orderno,a.objection,a.tag_id,a.jobs_id,a.auth_user_id,";
				$field .= "to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
			}else{
				$field = "a.*";
			}
			$results = M("course a")
				->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
				->join("JOIN __PROJECT_COURSE__ c ON a.id=c.course_id")
				->join("JOIN __DESIGNATED_PERSONNEL__ d ON c.project_id = d.project_id")
				->join("JOIN __ADMIN_PROJECT__ e ON c.project_id = e.id")
				->field($field .",c.project_id,b.cat_name,c.course_id,c.credit,e.project_name")
				->where($where)->order("c.start_time desc")
				->limit($start, $param["pageLen"])
				->select();
			//if($_GET["printr"] == 1) echo M("course a")->_sql();
			foreach ($results as $key=>$value){
				if(!$value["lecturer_name"] && $value["lecturer"]){
					$lecturer = M("lecturer")->field("name")->where("id=".$value["lecturer"])->find();
					$results[$key]["lecturer_name"] = $lecturer["name"];
				}
			}
			
			$count = M("course a")
				->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
				->join("JOIN __PROJECT_COURSE__ c ON a.id=c.course_id")
				->join("JOIN __DESIGNATED_PERSONNEL__ d ON c.project_id = d.project_id")
				->join("JOIN __ADMIN_PROJECT__ e ON c.project_id = e.id")
				->field("a.id")->where($where)->select();
			
			//输出分页
			$pageNav = "";
			$count = count($count);
			if($count > $param["pageLen"]){
				$pageNav = $this->pageClass($count,$param["pageLen"]);
			}
			
			$data = array('pageNav' => $pageNav, 'list' => $results, "courseCat"=>$courseCat,"tag_list"=>$tag_list);
			return $data;
		}else{
			//公开课选修课程
			//获取已经过审核的项目
			$where['a.is_public'] = array("eq",1);
			$where['a.status'] = array("eq",1);
			$where['a.auditing'] = array("eq",1);
		
			if(!empty($param["keyword"])){
				$where["a.course_name"] = array("like", "%".$param["keyword"]."%");
			}

			if(!empty($param["arrangement_id"])){
				//所属层次
				$where["arrangement_id"] = array("eq",$param["arrangement_id"]);
			}

			if(!empty($param["tag_id"])){
				//所属层次
				$where["tag_id"] = array("eq",$param["tag_id"]);
			}
			
			//线上线下课程
			if($param["way"] == "up"){
				$where["course_way"] = 0;
			}
			if($param["way"] == "down"){
				$where["course_way"] = 1;
			}
			
			if(!empty($param["cid"])){
				//课程分类-获取子类
				$cids = self::getCourseChild($param["cid"], $param["cid"].",");
				$cids = substr($cids, 0, -1);
				$where["course_cat_id"] = array("in", $cids);
			}
			
			$where['c.user_id'] = array("eq",$user_id);
			$results = M("course a")
				->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
				->join("JOIN __COURSE_RECORD__ c ON a.id = c.course_id")
				->field("a.*,b.cat_name")->where($where)->limit($start, $param["pageLen"])->select();
			
			foreach ($results as $key=>$value){
				$results[$key]["project_id"] = "true";
				
				if(!$value["lecturer_name"] && $value["lecturer"]){
					$lecturer = M("lecturer")->field("name")->where("id=".$value["lecturer"])->find();
					$results[$key]["lecturer_name"] = $lecturer["name"];
				}
			}
			
			$count = M("course a")
				->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")
				->join("LEFT JOIN __COURSE_RECORD__ c ON a.id = c.course_id")
				->field("a.id")->where($where)->select();
			
			//输出分页
			$pageNav = "";
			$count = count($count);
			if($count > $param["pageLen"]){
				$pageNav = $this->pageClass($count,$param["pageLen"]);
			}
			
			$data = array('pageNav' => $pageNav, 'list' => $results, "courseCat"=>$courseCat,"tag_list"=>$tag_list);
			return $data;
		}
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
	
	/**
	 * 获取课程详情
	 * type 课程类型 1我的课程 2公开课 
	 * course_id 课程ID
	 */
	public function base($param){
		$user_id = $_SESSION["user"]["id"];
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "id,course_name,course_code,course_time,course_cat_id,lecturer,course_way,media_src,";
			$field .= "maker,chapter,course_cover,credit,auditing,status,is_public,click_count,location,restrictions,";
			$field .= "lecturer_name,user_id,is_trigger,score,arrangement_id,orderno,objection,tag_id,jobs_id,auth_user_id,";
			$field .= "to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
		}else{
			$field = "*";
		}
		$course = M("course")->where("id=".$param["course_id"])->field($field)->find();
		//查看历史版本
		$course_bak = M("course_bak")->where("course_id=".$param["course_id"])->find();

		if($course){
			$cat = M("course_category")->field("cat_name")->where("id=".$course["course_cat_id"])->find();
			$course["cat_name"] = $cat["cat_name"];
			
			$course['chapter'] = json_decode($course['chapter'],true);
			foreach ($course['chapter'] as $key=>$value){
				$course['chapter'][$key]["show_doc"] = "";//预览office
				$fileType = substr($value["src"], -5, 5);
				$fileType = strtolower($fileType);
				if(strstr($fileType, ".mp4") || strstr($fileType, ".flv") || strstr($fileType, ".ogv") || strstr($fileType, ".webm")){
					$fileType = "video";
				}elseif(strstr($fileType, ".mp3")){
					$fileType = "audio";
				}elseif(strstr($fileType, ".ppt") || strstr($fileType, ".pptx")){
					$fileType = "ppt";
				}elseif(strstr($fileType, ".doc") || strstr($fileType, ".docx")){
					$fileType = "doc";
				}elseif(strstr($fileType, ".xls") || strstr($fileType, ".xlsx")){
					$fileType = "xls";
				}elseif(strstr($fileType, ".pdf")){
					$fileType = "pdf";
				}elseif(strstr($value["src"], ".html") || strstr($value["src"], "Course/course")){//mdzz
					$scooutid = $param["project_id"]."_".$param["course_id"];//外部id
					if(strstr($value["src"], "?")){
						$value["src"] .= "&student_id=".$user_id."&outid=".$scooutid;
					}else{
						$value["src"] .= "?student_id=".$user_id."&outid=".$scooutid;
					}
					$fileType = "kejian";
				}else{
					$fileType = "image";
				}
				$course['chapter'][$key]["src"] = $value["src"];
				$course['chapter'][$key]["fileType"] = $fileType;
				//取回预览数据
				$showWhere["course_id"] = $param["course_id"];
				$showWhere["chapter_id"] = $key;
				$showDoc = M('course_chapter_file')->where($showWhere)->find();
				if($showDoc){
					$course['chapter'][$key]["show_doc"] = "true";
				}
				//进度
				$course['chapter'][$key]["jindu"] = 0;
				$cwhere["user_id"] = $user_id;
				$cwhere["course_id"] = $param["course_id"];
				$cwhere["project_id"] = $param["project_id"];
				$cwhere["name"] = $value["name"];
				$chapter = M("course_chapter")->where($cwhere)->find();
				if($chapter){
					
					if($fileType == "video" || $fileType == "audio"){
						$course['chapter'][$key]["jindu"] = $chapter["time_percent"];
						$course['chapter'][$key]["timelen"] = $chapter["timelen"];
					}elseif($fileType == "kejian"){
						$course['chapter'][$key]["jindu"] = $chapter["time_percent"];
					}else{
						$course['chapter'][$key]["jindu"] = 100;
					}
				}
			}
			
			//是否我的课程joinStatus
			$course['joinStatus'] = 0;
			if($param["project_id"] == "true"){
				$course['joinStatus'] = 1;//没有项目id,从公开课添加
			}elseif($param["project_id"] == "false"){
				$hasJoinRecord = M("course_record")->field("id")->where("user_id=".$user_id." AND course_id=".$param["course_id"])->find();
				if($hasJoinRecord){
					$course['joinStatus'] = 1;
				}
			}else{
				$hasJoinPerson = M("designated_personnel")->field("id")->where("user_id=".$user_id." AND project_id=".$param["project_id"])->find();
				if($hasJoinPerson){
					$course['joinStatus'] = 1;
				}
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "to_char(start_time,'YYYY-MM-DD HH24:MI:SS') as start_time";
					$field .= ",to_char(end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
				}else{
					$field = "start_time,end_time,location";
				}
				$pro_course = M("project_course")->field($field)
					->where("project_id=".$param["project_id"]." AND course_id=".$param["course_id"])->find();
				
				$course['start_time'] = $pro_course["start_time"];
				$course['end_time'] = $pro_course["end_time"];
				$course['location'] = $pro_course["location"];
			}
			
			if(!$course["lecturer_name"] && $course["lecturer"]){
				$lecturer = M("lecturer")->field("name")->where("id=".$course["lecturer"])->find();
				$course["lecturer_name"] = $lecturer["name"];
			}
			
			//课程评价
			$comment = M("course_score")->field("avg(course_score) as course_score")->where("course_id=".$param["course_id"]." AND course_score>0")->select();
			if(!$comment["course_score"]) $comment["course_score"] = 0;
			$course['course_score'] = ceil($comment["course_score"]);
			
			//讲师评价
			$comment = M("course_score")->field("avg(lecturer_score) as lecturer_score")->where("course_id=".$param["course_id"]." AND lecturer_score>0")->select();
			if(!$comment["lecturer_score"]) $comment["lecturer_score"] = 0;
			$course['lecturer_score'] = ceil($comment["lecturer_score"]);
			
			//默认媒体地址类型
			$mediaFileType = substr($course[0]['media_src'], -5, 5);
			if(strstr($mediaFileType, ".mp4") || strstr($mediaFileType, ".flv") || strstr($mediaFileType, ".ogv") || strstr($mediaFileType, ".webm")){
				$mediaFileType = "video";
			}elseif(strstr($course['media_src'], ".html") || strstr($course['media_src'], "Course/course")){//mdzz
				$mediaFileType = "kejian";
			}else{
				$mediaFileType = "img";
			}
			$course['media_type'] = $mediaFileType;
		}
		return array("data"=>$course,"course_bak"=>$course_bak);
	}
	
	/**
	 * 获取课程介绍
	 */
	public function getDail($param){
		$user_id = $_SESSION["user"]["id"];
		
		$data = M("course_detail")->where("id=".$param["course_id"])->find();
		return array("data"=>$data);
	}
	
	/**
	 * 获取评价
     * course_id 课程id
     * page 页码，默认1
     * pageLen 默认15
	 */
	public function getComment($param){
		$user_id = $_SESSION["user"]["id"];
		
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 15;
		$start = ($param["page"] - 1) * $param["pageLen"];
		
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$comList = M("colligate_comment")
			->where("course_id=".$param["course_id"]." AND pid=0")
			->order("comment_time DESC")
			->field("id,lecturer_id,user_id,lecturer_evaluation,course_id,course_evaluation,comment_content,pid,state,to_char(comment_time,'YYYY-MM-DD HH24:MI:SS') as comment_time")
			->limit($start, $param["pageLen"])
			->select();
		}else{
			$comList = M("colligate_comment")
			->where("course_id=".$param["course_id"]." AND pid=0 AND comment_content!=''")
			->order("comment_time DESC")->limit($start, $param["pageLen"])->select();
		}
		//获取子评论/回复
		foreach ($comList as $key=>$value){
			$user = M("users")->field("username,avatar")->where("id=".$value["user_id"])->find();
			$comList[$key]["username"] = $user["username"];
			$comList[$key]["avatar"] = $user["avatar"];
			
			$zan = M("course_praise")->where("praise=1 AND id=".$value["id"])->count();
			$comList[$key]["zan"] = $zan;//总赞
			
			$zanStatus = M("course_praise")->where("id=".$value["id"]." AND user_id=".$user_id)->find();
			if(!$zanStatus){
				$zanStatus = "0";
			}else{
				$zanStatus = $zanStatus["praise"] + 0;
			}
			$comList[$key]["zan_status"] = $zanStatus;//我是否赞过  1已赞 0未赞
			
			//是否可删除
			$comList[$key]["del_status"] = 0;
			if($user_id == $value["user_id"]){
				$comList[$key]["del_status"] = 1;
			}
			
			$subList = array();
			$pids = self::getCommentChild($value["id"], $value["id"].",");
			$pids = substr($pids, 0, -1);
			if($pids){
				$sonCon = M("colligate_comment")
					->where("course_id=".$param["course_id"]." AND pid in (".$pids.")")->select();
				if($sonCon){
					$userCache = array();
					foreach ($sonCon as $sk=>$sv){
						$subUser = M("users")->field("username,avatar")->where("id=".$sv["user_id"])->find();
						$sonCon[$sk]["username"] = $subUser["username"];
						$sonCon[$sk]["avatar"] = $subUser["avatar"];
						
						$userCache[$sv["id"]] = $subUser["username"];
						
						//是否可删除
						$sonCon[$sk]["del_status"] = 0;
						if($user_id == $sv["user_id"]){
							$sonCon[$sk]["del_status"] = 1;
						}
						
						if($sv["pid"] != $value["id"]){
							$sonCon[$sk]["reply_user"] = $userCache[$sv["pid"]];
						}
					}
					$comList[$key]["sub_list"] = $sonCon;
					$comList[$key]["sub_num"] = count($sonCon);
				}
			}
		}
		
		$count = M("colligate_comment")->field("id")
			->where("course_id=".$param["course_id"]." AND pid=0")->select();
		$count = count($count);
		
		$pageNav = "";
		if($count > $param["pageLen"]){
			$pageNav = $this->pageClass($count,$param["pageLen"]);
		}
		$return["pageNav"] = $pageNav;
		$return["list"] = $comList;
		
		return array("data"=>$return);
	}
	
	//获取子评论pid
	public function getCommentChild($cid, $cidStr){
		$cid += 0;
		if(!is_int($cid) || $cid < 0){
			return false;
		}
	
		$cat = M("colligate_comment")->where("pid=".$cid)->select();
		if($cat){
			foreach ($cat as $key=>$v){
				$cidStr .= $v["id"] . ",";
				$cidStr = self::getCommentChild($v["id"], $cidStr);
			}
		}
	
		return $cidStr;
	}
	
	/**
	 * 添加评价 每人只可评价一次 XXXXX更改为可多次
	 * course_id 课程id
	 * content 评价内容
	 */
	public function addComment($param){
		$user_id = $_SESSION["user"]["id"];
		//判断笔记是否存在
		$course = M("course")->field("id")->where("id=".$param["course_id"])->limit(1)->select();
		if(!$course){
			return array("code"=>1021, "message"=>"当前course_id未获取到数据");
		}
		
		$lecturer = M("course")->where("id=".$param["course_id"])->find();
		$data["user_id"] = $user_id;
		$data["course_id"] = $param["course_id"];
		$data["comment_content"] = $param["content"];
		$data["lecturer_id"] = $lecturer["lecturer"];//???手机端选择课程时可以额外指定讲师
		$data["pid"] = 0;
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('colligate_comment');
			$data['comment_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}else{
			$data["comment_time"] = date("Y-m-d H:i:s");
		}
		$return = M("colligate_comment")->add($data);
		if($return){
			return array("code"=>1000, "message"=>"添加成功", "last_id"=>$return);
		}else{
			return array("code"=>1022, "message"=>"创建失败，请稍后重试");
		}
	}
	
	/**
	 * 评价点赞
	 * comment_id 评价id
	 * praise_status 点赞状态 1点赞 0取消赞
	 */
	public function makePraise($param){
		$user_id = $_SESSION["user"]["id"];
		$has = M("course_praise")->where("user_id=".$user_id." AND id=".$param["comment_id"])->find();
		if($has){
			$data["praise"] = $param["praise_status"];
			$data["praise_time"] = date("Y-m-d H:i:s");
			$resp = M("course_praise")->where("user_id=".$user_id." AND id=".$param["comment_id"])->limit(1)->save($data);
			return array("code"=>1000, "message"=>"成功");
		}else{
			$data["id"] = $param["comment_id"];
			$data["user_id"] = $user_id;
			$data["praise"] = $param["praise_status"];
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('course_praise');
				$data['praise_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}else{
				$data["praise_time"] = date("Y-m-d H:i:s");
			}
			$resp = M("course_praise")->add($data);
			return array("code"=>1000, "message"=>"成功");
		}
	}
	
	/**
	 * 评价回复
	 * comment_id 评价id
	 * content 回复内容
	 */
	public function reply($param){
		$user_id = $_SESSION["user"]["id"];
		$comment = M("colligate_comment")->where("id=".$param["comment_id"])->limit(1)->select();
		if(!$comment){
			return array("code"=>1021, "message"=>"未获取到评价id");
		}
		
		$data["lecturer_id"] = $comment[0]["lecturer_id"];
		$data["user_id"] = $user_id;
		$data["pid"] = $comment[0]["id"];
		$data["lecturer_score"] = 0;
		$data["course_id"] = $comment[0]["course_id"];
		$data["course_score"] = 0;
		$data["comment_content"] = $param["content"];
		$data["comment_time"] = date("Y-m-d H:i:s");
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('colligate_comment');
			$data["comment_time"] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}else{
			$data["comment_time"] = date("Y-m-d H:i:s");
		}
		$resp = M("colligate_comment")->add($data);
		if($resp){
			return array("code"=>1000, "message"=>"成功", "last_id"=>$resp);
		}else{
			return array("code"=>1021, "message"=>"操作失败，请稍后重试");
		}
	}
	
	/**
	 * 删除评价
	 * comment_id 评价id
	 */
	public function delComment($param){
		$user_id = $_SESSION["user"]["id"];
		
		$resp = M("colligate_comment")->where("id=".$param["comment_id"])->limit(1)->delete();
		if($resp){
			//删除关联的回复评论 
			$pids = self::getCommentChild($param["comment_id"], $param["comment_id"].",");
			$pids = substr($pids, 0, -1);
			if($pids){
				M("colligate_comment")->where("pid IN (".$pids.")")->delete();
			}
			
			return array("code"=>1000, "message"=>"成功");
		}else{
			return array("code"=>1021, "message"=>"操作失败，请稍后重试");
		}
	}
	
    /**
     * 获取当前课程下的笔记
     * course_id 课程id
     * page 页码，默认1
     * pageLen 默认15
     */
	public function getNote($param){
		$user_id = $_SESSION["user"]["id"];
		
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 15;
		$start = ($param["page"] - 1) * $param["pageLen"];
		if($param["project_id"] == "true") $param["project_id"] = 0;
		$table2 = 'SELECT id,user_id,note_content,time FROM __COURSE_NOTE__ WHERE course_id='.$param["course_id"].' AND project_id='.$param["project_id"].' AND (is_public=1 AND user_id!='.$user_id.') OR (user_id='.$user_id.')';
//		$table1 = 'SELECT id,user_id,note_content,time FROM __COURSE_NOTE__ WHERE course_id='.$param["course_id"].' AND project_id='.$param["project_id"].' AND user_id='.$user_id;
//		$sql = $table1 .' UNION '.$table2.' ORDER BY time DESC LIMIT '.$start.','.$param["pageLen"];
		$sql = $table2.' ORDER BY time DESC';
		$data = M()->query($sql);
		
		$data = array_slice($data,$start,$param["pageLen"]);
		
		foreach($data as $key=>$value){
			$user = M("users")->field("username,avatar")->where("id=".$value["user_id"])->find();
			$data[$key]["username"] = $user[0]["username"];
			$data[$key]["avatar"] = $user[0]["avatar"];
			//是否可删除
			$data[$key]["del_status"] = 0;
			if($user_id == $value["user_id"]){
				$data[$key]["del_status"] = 1;
			}
		}
		
		$sql = $table2;
		$count = M()->query($sql);
		$count = count($count);
		
		$pageNav = "";
		if($count > $param["pageLen"]){
			$pageNav = $this->pageClass($count,$param["pageLen"]);
		}
		$return["pageNav"] = $pageNav;
		$return["note"] = $data;
		return array("data"=>$return);
	}
	
	/**
	 * 添加笔记
	 * course_id 课程id
     * content 笔记内容
     * is_public 是否公开 1公开  2不公开
	 */
	public function addNote($param){
		$user_id = $_SESSION["user"]["id"];
		
		//判断笔记是否存在
		$course = M("course")->field("id")->where("id=".$param["course_id"])->limit(1)->select();
		if(!$course){
			return array("code"=>1021, "message"=>"当前course_id未获取到数据");
		}
		
		$data["user_id"] = $user_id;
		$data["course_id"] = $param["course_id"];
		$data["project_id"] = $param["project_id"];
		$data["note_content"] = $param["content"];
		$data["is_public"] = $param["is_public"];
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('course_note');
			$data['time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}else{
			$data["time"] = date("Y-m-d H:i:s");
		}
		$return = M("course_note")->add($data);
		if($return){
			return array("code"=>1000, "message"=>"添加成功", "last_id"=>$return);
		}else{
			return array("code"=>1022, "message"=>"创建失败，请稍后重试");
		}
	}
	
	/**
	 * 删除笔记
	 * note_id 笔记ID
	 */
	public function delNote($param){
		M("course_note")->where("id=".$param["note_id"])->limit(1)->delete();
		return array("code"=>1000, "message"=>"成功");
	}
	
	/**
	 * 课程 && 讲师评分
	 * course_id 课程id
	 * score 分数，满分五分
	 * type 打分类型 1课程 2讲师
	 */
	public function giveScore($param){
		$user_id = $_SESSION["user"]["id"];
		//判断笔记是否存在
		$course = M("course")->field("id")->where("id=".$param["course_id"])->limit(1)->select();
		if(!$course){
			return array("code"=>1021, "message"=>"当前course_id未获取到数据");
		}
		
		$has = M("course_score")->where("user_id=".$user_id." AND course_id=".$param["course_id"])->limit(1)->select();
		if($has){
			if($param["type"] == 1){
				$data["course_score"] = $param["score"];
			}else{
				$data["lecturer_score"] = $param["score"];
			}
			if($param["type"] == 1 && $has[0]["course_score"] > 0){
				return array("code"=>1022, "message"=>"课程已评价过");
			}
			
			if($param["type"] == 2 && $has[0]["lecturer_score"] > 0){
				return array("code"=>1023, "message"=>"讲师已评价过");
			}
			$return = M("course_score")->where("user_id=".$user_id." AND course_id=".$param["course_id"])->limit(1)->save($data);
			if($param["type"] == 1){
				//课程评价
				$comment = M("course_score")->field("avg(course_score) as course_score")->where("course_id=".$param["course_id"]." AND course_score>0")->select();
				if(!$comment[0]["course_score"]) $comment[0]["course_score"] = 0;
				$avgScore = ceil($comment[0]["course_score"]);
				M("course")->where("id=".$param["course_id"])->limit(1)->save(array("score"=>$avgScore));
			}else{
				//讲师评价
				$comment = M("course_score")->field("avg(lecturer_score) as lecturer_score")->where("lecturer_id=".$has[0]["lecturer_id"]." AND lecturer_score>0")->select();
				if(!$comment[0]["lecturer_score"]) $comment[0]["lecturer_score"] = 0;
				$avgScore = ceil($comment[0]["lecturer_score"]);
				M("lecturer")->where("id=".$has[0]["lecturer_id"])->limit(1)->save(array("score"=>$avgScore));
			}
			return array("code"=>1000, "message"=>"打分成功", "avgScore"=>$avgScore);
		}else{
			$lecturer = M("course")->where("id=".$param["course_id"])->limit(1)->select();
			if($param["type"] == 1){
				$data["course_score"] = $param["score"];
				$data["lecturer_score"] = 0;
			}else{
				$data["course_score"] = 0;
				$data["lecturer_score"] = $param["score"];
			}
			$data["user_id"] = $user_id;
			$data["course_id"] = $param["course_id"];
			$data["comment_content"] = "";
			$data["lecturer_id"] = $lecturer[0]["lecturer"];
			$data["score_time"] = date("Y-m-d H:i:s");
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$map['id'] = getNextId('course_score');
			}
			$return = M("course_score")->add($data);
			if($return){
				if($param["type"] == 1){
					//课程评价
					$comment = M("course_score")->field("avg(course_score) as course_score")->where("course_id=".$param["course_id"]." AND course_score>0")->select();
					if(!$comment[0]["course_score"]) $comment[0]["course_score"] = 0;
					$avgScore = ceil($comment[0]["course_score"]);
					M("course")->where("id=".$param["course_id"])->limit(1)->save(array("score"=>$avgScore));
				}else{
					//讲师评价
					$comment = M("course_score")->field("avg(lecturer_score) as lecturer_score")->where("lecturer_id=".$lecturer[0]["lecturer"]." AND lecturer_score>0")->select();
					if(!$comment[0]["lecturer_score"]) $comment[0]["lecturer_score"] = 0;
					$avgScore = ceil($comment[0]["lecturer_score"]);
					M("lecturer")->where("id=".$lecturer[0]["lecturer_id"])->limit(1)->save(array("score"=>$avgScore));
				}
				
				return array("code"=>1000, "message"=>"打分成功", "avgScore"=>$avgScore);
			}else{
				return array("code"=>1023, "message"=>"创建失败，请稍后重试");
			}
		}
	}
	
	/**
	 * 加入我的课程
	 * course_id 课程id
	 */
	public function addCourse($param){
		$user_id = $_SESSION["user"]["id"];
		if(!$user_id){
			return array("code"=>1021, "message"=>"未获取到用户，请登录");
		}
		
		$has = M("course_record")->where("course_id=".$param["course_id"]." AND user_id=".$user_id)->select();
		if($has){
			return array("code"=>1023, "message"=>"此课程已添加过");
		}
		$data["user_id"] = $user_id;
		$data["course_id"] = $param["course_id"];
		$data["recently_lookup_time"] = 0;
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$map['id'] = getNextId('course_record');
		}
		$return = M("course_record")->add($data);
		if($return){
			return array("code"=>1000, "message"=>"成功");
		}else{
			return array("code"=>1022, "message"=>"添加失败，请稍后重试");
		}
	}
	
	/**
	 * 章节进展
     * project_id
     * course_id
     * fileName
     * fileSrc
     * fileType
     * status
     * timeLen
     * time_percent
	 */
	public function chapter($param){
		$user_id = $_SESSION["user"]["id"];
		if(!$user_id){
			return array("code"=>1021, "message"=>"未获取到用户，请登录");
		}
		
		$cwhere["user_id"] = $user_id;
		$cwhere["course_id"] = $param["course_id"];
		$cwhere["project_id"] = $param["project_id"];
		$cwhere["name"] = $param["fileName"];
		$chapter = M("course_chapter")->where($cwhere)->limit(1)->select();
		
		if($chapter){
			if($param["fileType"] == "video" || $param["fileType"] == "audio" || $param["fileType"] == "kejian"){
				$data["status"] = $param["status"];
				$data["timeLen"] = $param["timeLen"];
				$data["time_percent"] = $param["time_percent"];
				
				M("course_chapter")->where($cwhere)->save($data);
			}
		}else{
			$data["user_id"] = $user_id;
			$data["course_id"] = $param["course_id"];
			$data["project_id"] = $param["project_id"];
			$data["name"] = $param["fileName"];
			$data["path"] = $param["fileSrc"];
			$type = 0;//附件类型 1视频video 2音频audio 3文档doc
			if($param["fileType"] == "video"){
				$type = 1;
			}elseif ($param["fileType"] == "audio"){
				$type = 2;
			}else{
				$type = 3;
			}
			$data["type"] = $type;
			$data["status"] = $param["status"];
			$data["timeLen"] = $param["timeLen"];
			$data["time_percent"] = $param["time_percent"];
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('course_chapter');
				$data["create_time"] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}else{
				$data["create_time"] = date("Y-m-d H:i:s");
			}
			
			M("course_chapter")->add($data);
		}
		
		//已完成判断是否学完章节，计算学时
		if($param["status"] == 3){
			$course = M("course")->where("id=".$param["course_id"])->limit(1)->select();
			$chapter = json_decode($course[0]['chapter'],true);
			$where["user_id"] = $user_id;
			$where["course_id"] = $param["course_id"];
			$where["project_id"] = $param["project_id"];
			$where["status"] = 3;
			$isStudy = M("course_chapter")->field("id")->where($where)->select();
			if(count($isStudy) == count($chapter)){
				//是否已算学时
				$where1["user_id"] = $user_id;
				$where1["source_id"] = $param["course_id"];
				$where1["project_id"] = $param["project_id"];
				$hasStudy = M("center_study")->where($where1)->limit(1)->select();
				if(!$hasStudy){
					$data1["create_time"] = date("Y-m-d H:i:s");
					if($param["project_id"] > 0){
						$data1["typeid"] = 4;
						$pro_course = M("project_course")
									->where("project_id=".$param["project_id"]." AND course_id=".$param["course_id"])
									->limit(1)
									->select();
						$data1["credit"] = $pro_course[0]["credit"];
						
						/* $diffTime = strtotime($pro_course[0]["end_time"]) - strtotime($pro_course[0]["start_time"]);
						$hours = ceil($diffTime / 60); */
						
						$hours = $course[0]["course_time"];
					}else{
						@D('Trigger')->intergrationTrigger($user_id, 19);
						
						$data1["typeid"] = 5;
						$data1["credit"] = $course[0]["credit"];
						$hours = $course[0]["course_time"];
					}
					
					$data1["source_id"] = $param["course_id"];
					$data1["project_id"] = $param["project_id"];
					$data1["user_id"] = $user_id;
					$data1["hours"] = $hours;
					
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$data1['create_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
						$data1['id'] = getNextId('center_study');
					}
					
					M("center_study")->add($data1);
				}
			}
		}
		return array("code"=>1000, "message"=>"成功");
	}
	
	/**
	 * 公开课
	 */
	public function publicCourse($param){
		$user_id = $_SESSION["user"]["id"];
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 15;
		$start = ($param["page"] - 1) * $param["pageLen"];

		if($param["arrangement_id"] <= 0){
			$param["arrangement_id"] = 0;
		}
	
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
	
		//公开课获取已经过审核的项目
		$where['a.is_public'] = array("eq",1);
		$where['a.status'] = array("eq",1);
		$where['a.auditing'] = array("eq",1);
		if(!empty($param["keyword"])){
			$where["a.course_name"] = array("like", "%".$param["keyword"]."%");
		}

		if(!empty($param["cid"])){
			//课程分类-获取子类
			$cids = self::getCourseChild($param["cid"], $param["cid"].",");
			$cids = substr($cids, 0, -1);
			$where["course_cat_id"] = array("in", $cids);
		}

		if(!empty($param["arrangement_id"])){
			$where['a.arrangement_id'] = array("eq",$param["arrangement_id"]);
		}
	
		$classid = I("get.classid");
	
		if($classid == 'new'){
			$order = "a.id desc";
		}else if($classid == 'hot'){
			$order = "a.click_count desc";
		}else{
			$order = "a.score desc";
		}
	
		$DB_PREFIX = strtolower(C('DB_PREFIX').'course_score');

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

		$where['a.auth_user_id'] = array("in",$specifiedUser);
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.course_name,a.course_code,a.course_time,a.course_cat_id,a.lecturer,a.course_way,a.media_src,";
			$field .= "a.maker,a.chapter,a.course_cover,a.credit,a.auditing,a.status,a.is_public,a.click_count,a.location,a.restrictions,";
			$field .= "a.lecturer_name,a.user_id,a.is_trigger,a.score,a.arrangement_id,a.orderno,a.objection,a.tag_id,a.jobs_id,a.auth_user_id,";
			$field .= "to_char(a.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(a.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time";
		}else{
			$field = "a.*";
		}
		$field .= ",b.cat_name,(select sum(c.course_score) from $DB_PREFIX c where a.id = c.course_id) as coursenum,d.course_intro as course_description";
		$results = M("course a")
				->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id LEFT JOIN __COURSE_DETAIL__ d ON d.id = a.id")
				->field($field)
				->where($where)
				->order($order)
				->select();
		
		//好评排序
		/* if($classid == 'praise'){
			$rows = array();
			foreach($results as $k=>$items){
				$rows[$k] = $items;

				if(empty($items['coursenum'])){
					$rows[$k]['coursenum'] = 0;
				}
			}
			array_multisort($this->i_array_column($rows,'coursenum'),SORT_DESC,$rows);
			$results = $rows;
		} */

		$itmes = array();
		$tag_list = array();

		//查询当前用户所属标签
		$users_tag = M("users_tag_relation")
					->field("tag_id")
					->where("user_id=".$_SESSION["user"]["id"])
					->select();

		//查询当前用户所属岗位
		$user_jobid = M("tissue_group_access")
					->field("job_id")
					->where("user_id=".$_SESSION["user"]["id"])
					->find();

		$results = $this->items_screen($results,$users_tag,$user_jobid);

		//隔离数据过滤
		$results = D('IsolationData')->isolationData($results);

		//输出分页
		$pageNav = "";

		$count = count($results);

		$page = ($param["page"]-1)*$param["pageLen"];

		$results = array_slice($results,$page,$param["pageLen"]);

		if($count > $param["pageLen"]){
			$pageNav = $this->pageClass($count,$param["pageLen"]);
		}
		
		$data = array('pageNav' => $pageNav, 'list' => $results, "courseCat"=>$courseCat);
		return $data;
	}

	/**
	 * @param $input
	 * @param $columnKey
	 * @param null $indexKey
	 * @return array
	 * 公开课筛选用户标签
	 */
	public function items_screen($results,$users_tag,$user_jobid){

		foreach($results as $list) {

			//判断用户是否存在该标签
			if (!empty($list['tag_id'])) {

				$tag_id = explode(",", $list['tag_id']);

				foreach ($users_tag as $tag) {

					if (in_array($tag['tag_id'], $tag_id)) {
						$tag_jump = true;
						break;
					} else {
						$tag_jump = false;
					}

				}

			}

			//判断用户是否存在该岗位
			if (!empty($list['jobs_id'])) {

				if ($user_jobid['job_id'] == $list['jobs_id']) {
					$job_jump = true;
				} else {
					$job_jump = false;
				}

			}


			if ((empty($list['tag_id']) and empty($list['jobs_id'])) OR ($tag_jump OR $job_jump)) {
				$itmes[] = $list;
			}
		}

		return $itmes;

	}

	function i_array_column($input, $columnKey, $indexKey=null){
		if(!function_exists('array_column')){
			$columnKeyIsNumber  = (is_numeric($columnKey))?true:false;
			$indexKeyIsNull            = (is_null($indexKey))?true :false;
			$indexKeyIsNumber     = (is_numeric($indexKey))?true:false;
			$result                         = array();
			foreach((array)$input as $key=>$row){
				if($columnKeyIsNumber){
					$tmp= array_slice($row, $columnKey, 1);
					$tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null;
				}else{
					$tmp= isset($row[$columnKey])?$row[$columnKey]:null;
				}
				if(!$indexKeyIsNull){
					if($indexKeyIsNumber){
						$key = array_slice($row, $indexKey, 1);
						$key = (is_array($key) && !empty($key))?current($key):null;
						$key = is_null($key)?0:$key;
					}else{
						$key = isset($row[$indexKey])?$row[$indexKey]:0;
					}
				}
				$result[$key] = $tmp;
			}
			return $result;
		}else{
			return array_column($input, $columnKey, $indexKey);
		}
	}
	
	/**
	 * 获取问题数据
	 */
	public function getQues($param){
		$user_id = $_SESSION["user"]["id"];
		$qwhere["course_id"] = $param["course_id"];
		$qwhere["project_id"] = $param["project_id"];
		if($param["chapIndex"] != 0){
			$qwhere["chapter"] = $param["chapIndex"];
		}
		$param["page"] = $param["p"];
		if(!$param["page"]) $param["page"] = 1;
		if(!$param["pageLen"]) $param["pageLen"] = 15;
		$start = ($param["page"] - 1) * $param["pageLen"];
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$ques = M("course_ques")
					->where($qwhere)
					->order("id desc")
					->field("id,user_id,course_id,project_id,chapter,title,content,to_char(add_time,'YYYY-MM-DD HH24:MI:SS') as add_time")
					->limit($start, $param["pageLen"])
					->select();
		}else{
			$ques = M("course_ques")->where($qwhere)->order("id desc")->limit($start, $param["pageLen"])->select();
		}
		foreach ($ques as $key=>$value){
			$quesUser = M("users")->field("username")->where("id=".$value["user_id"])->find();
			$ques[$key]["username"] = $quesUser["username"];
			
			$answerNum = M("course_answer")->field("count(id) as num")->where("ques_id=".$value["id"])->select();
			$ques[$key]["answer_num"] = $answerNum[0]["num"];
		}
		
		$count = M("course_ques")->field("count(id) as num")->where($qwhere)->select();
		$count = $count[0]["num"];
		$pageNav = "";
		if($count > $param["pageLen"]){
			$pageNav = $this->pageClass($count,$param["pageLen"]);
		}
		
		$return["pageNav"] = $pageNav;
		$return["quesNum"] = $count;
		$return["ques"] = $ques;
		return array("data"=>$return);
	}
	
	/*
	* 课程问答-新的问题
	* title 标题
	* content 内容
	* course_id 课程id
	* project_id 项目id，选修课此项为0
	* chapter 课程章节 0全部
	*/
	public function newQues($param){
		$user_id = $_SESSION["user"]["id"];
		$data["user_id"] = $user_id;
		$data["course_id"] = $param["course_id"];
		$data["project_id"] = $param["project_id"];
		$data["chapter"] = $param["chapIndex"];
		$data["title"] = $param["title"];
		$data["content"] = $param["content"];
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('course_ques');
			$data['add_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}else{
			$data["add_time"] = date("Y-m-d H:i:s");
		}
		$last_id = M("course_ques")->add($data);
		return $last_id;
	}
	
	/*
     * 课程问答-回答页面
     * ques_id 问题id
     */
    public function answerPage($ques_id, $p){
    	$user_id = $_SESSION["user"]["id"];
    	
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$ques = M("course_ques")
					->where("id=".$ques_id)
					->field("id,user_id,course_id,project_id,chapter,title,content,to_char(add_time,'YYYY-MM-DD HH24:MI:SS') as add_time")
					->find();
		}else{
			$ques = M("course_ques")->where("id=".$ques_id)->find();
		}
    	if($ques){
	    	$user = M("users")->field("username")->where("id=".$ques["user_id"])->find();
	    	$ques["username"] = $user["username"];
    	}
    	
    	//答案
    	$param["page"] = $p;
    	if(!$param["page"]) $param["page"] = 1;
    	if(!$param["pageLen"]) $param["pageLen"] = 15;
    	$start = ($param["page"] - 1) * $param["pageLen"];
    	
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$answer = M("course_answer")
					->where("ques_id=".$ques_id)
					->order("id desc")
					->field("id,ques_id,user_id,content,to_char(add_time,'YYYY-MM-DD HH24:MI:SS') as add_time")
					->limit($start, $param["pageLen"])
					->select();
		}else{
			$answer = M("course_answer")->where("ques_id=".$ques_id)->order("id desc")->limit($start, $param["pageLen"])->select();
		}
    	foreach ($answer as $key=>$value){
    		$answerUser = M("users")->field("username")->where("id=".$value["user_id"])->find();
    		$answer[$key]["username"] = $answerUser["username"];
    	}
    	
    	$answerNum = M("course_answer")->field("count(id) as num")->where("ques_id=".$ques_id)->select();
    	$count = $answerNum[0]["num"];
    	$answerNum = $answerNum[0]["num"];
    	$pageNav = "";
    	if($count > $param["pageLen"]){
    		$pageNav = $this->pageClass($count,$param["pageLen"]);
    	}
    	
    	$return = array("ques"=>$ques, "answer"=>$answer, "answerNum"=>$answerNum, "ques_id"=>$ques_id, "pageNav"=>$pageNav);
    	return $return;
    }
	
	/*
	 * 课程问答-提交答案
	* ques_id 问题id
	* content 答案内容
	*/
	public function answer($param){
		$user_id = $_SESSION["user"]["id"];
		
		$ques = M("course_ques")->where("id=".$param["ques_id"])->find();
		if(!$ques){
			return array("code" => 1022, "message"=>"问题不存在或者已删除");
		}
		
		$data["ques_id"] = $param["ques_id"];
		$data["user_id"] = $user_id;
		$data["content"] = $param["content"];
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('course_answer');
			$data["add_time"] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}else{
			$data["add_time"] = date("Y-m-d H:i:s");
		}
		
		$add = M("course_answer")->add($data);
		if($add > 0){
			//消息提醒
			$messageUrl = "admin/my_course/answerPage/ques_id/".$param["ques_id"];
			//D('Trigger')->messageTrigger("消息接收人", "通知标题", "创建时间", "消息类型", "消息发起人", "消息查看地址");
			D('Trigger')->messageTrigger($ques["user_id"], "你的问答有新消息，请查看：", date("Y-m-d H:i:s"), 15, $user_id, $messageUrl);
			
			return array("code" => 1000, "message"=>"", "last_id"=>$add);
		}else{
			return array("code" => 1021, "message"=>"提交失败，请稍后重试");
		}
	}
}
