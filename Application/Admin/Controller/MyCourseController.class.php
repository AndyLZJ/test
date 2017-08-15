<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 学习任务_我的课程
 * @author Dujuqiang 20170320
 * 
 * 更新内容
 * 20170605 课件播放进度
 */
class MyCourseController extends AdminBaseController{
	/*
	 * 我的课程
	 * type 课程类型 1我的课程 2公开课 默认1
	 * p 页码
	 * cid 课程分类， 默认为空
	 * keyword 搜索关键字， 默认为空
	 */
	public function index(){
		$user_id = $_SESSION["user"]["id"];
		if(!$user_id){
			echo "未获取到用户，请登录";
			exit;
		}
		
		$get = I("get.");
		$get["page"] = $get["p"] + 0;
		if(!is_int($get["page"]) || $get["page"] < 1){
			$get["page"] = 1;
		}
		
		$get["type"] += 0;
		if($get["type"] != 2){
			$get["type"] = 1;
		}
		
		$get["cid"] += 0;
		if(!is_int($get["cid"]) || $get["cid"] < 0){
			$get["cid"] = "";
		}

		$get["arrangement_id"] += 0;
		if(!is_int($get["arrangement_id"]) || $get["arrangement_id"] < 0){
			$get["arrangement_id"] = "";
		}

		$get["tag_id"] += 0;
		if(!is_int($get["tag_id"]) || $get["tag_id"] < 0){
			$get["tag_id"] = "";
		}
		
		if($get["way"] != "up" && $get["way"] != "down"){
			$get["way"] = "all";
		}
		
		$pageLen = 10;
		$get["pageLen"] = $pageLen;
		$data = D("MyCourse")->index($get);
		$data["type"] = $get["type"];
		$data["way"] = $get["way"];
		$data["cid"] = $get["cid"];
		$data["tag_id"] = $get["tag_id"];
		$data["keyword"] = $get["keyword"];
		$data["arrangement_id"] = $get["arrangement_id"];
		//print_r($data);
		$this->assign($data);
    	$this->display();
    }
    
    /**
     * 获取课程详情
     * type 课程类型 1我的课程 2公开课
     * course_id 课程ID
     * project_id 项目id
     * type 页面类型 1介绍  2评价 3笔记
     * 
     */
    public function detail(){
    	
		$typeid = I('get.typeid');
		
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		echo "未获取到用户，请登录";
    		exit;
    	}
    	$get = I("get.");
    	if($get["type"] != 2 && $get["type"] != 3 && $get["type"] != 4){
    		$get["type"] = 1;
    	}
    	
    	$get["course_id"] += 0;
    	if(!is_int($get["course_id"]) || $get["course_id"] < 1){
    		echo "非法操作，缺少课程id";
    		exit;
    	}
    	
    	if($get["project_id"] != "true" && $get["project_id"] != "false"){
	    	$get["project_id"] += 0;
	    	if(!is_int($get["project_id"]) || $get["project_id"] < 1){
	    		echo "非法操作，缺少项目id";
	    		exit;
	    	}
    	}
    	
    	$base = D("MyCourse")->base($get);
    	$data["base"] = $base["data"];
		$data["course_bak"] = $base["course_bak"];
    	$data["course_id"] = $get["course_id"];
    	$data["project_id"] = $get["project_id"];
    	$data["type"] = $get["type"];
    	$data["manager"] = $get["manager"];
    	
    	$data["user_id"] = $user_id;
    	$data["avatar"] = $_SESSION["user"]["avatar"];
    	$data["username"] = $_SESSION["user"]["username"];
    	
    	$data["whetherteach"] = 0;
    	if($get["whetherteach"] == "1"){
	    	$data["whetherteach"] = 1;
	    	$stampStart = strtotime($base["data"]["start_time"]);
	    	$endStart = strtotime($base["data"]["end_time"]);
	    	$data["kaoqin"] = "attendance/index/id/".$get["course_id"]."/pid/".$get["project_id"]."/type/".$get["type"]."/course_name/".$base["data"]["course_name"]."/start_time/$stampStart/end_time/$endStart";
    	}
    	$data["ogtype"] = $get["ogtype"];//是否能从我的授课过来
    	
    	if($get["printr"] == 1) print_r($data);
    	$this->assign($data);
    	$this->assign('typeid',$typeid);
		$this->assign('course_id',$data["course_id"]);
    	$this->display();
    }
    
    /**
     * 详情下tab内容
     */
    public function detailTab(){
    	$user_id = $_SESSION["user"]["id"];
    	$get = I("get.");
    	
    	$get["course_id"] += 0;
    	if(!is_int($get["course_id"]) || $get["course_id"] < 1){
    		echo "非法操作，缺少课程id";
    		exit;
    	}
    	
    	if($get["project_id"] != "true" && $get["project_id"] != "false"){
    		$get["project_id"] += 0;
    		if(!is_int($get["project_id"]) || $get["project_id"] < 1){
    			echo "非法操作，缺少项目id";
    			exit;
    		}
    	}
    	
    	$base = D("MyCourse")->base($get);
    	$data["base"] = $base["data"];
    	$data["course_bak"] = $base["course_bak"];
    	$data["course_id"] = $get["course_id"];
    	$data["project_id"] = $get["project_id"];
    	$data["user_id"] = $user_id;
    	$data["avatar"] = $_SESSION["user"]["avatar"];
    	$data["username"] = $_SESSION["user"]["username"];
    	
    	//print_r($data);
    	//评论  笔记有分页
    	$get["page"] = $get["p"] + 0;
    	if(!is_int($get["page"]) || $get["page"] < 1){
    		$get["page"] = 1;
    	}
    	
    	if($get["tab"] == 1){
    		//课程介绍
    		$datail = D("MyCourse")->getDail($get);
    		$data["detail"] = $datail["data"];
    		$this->assign($data);
	    	$this->display("detail_tab1");
    	}elseif($get["tab"] == 2){
    		//章节目录
    		//直接页面使用，不再使用新页面
    	}elseif($get["tab"] == 3){
    		//课程评价
    		$comment = D("MyCourse")->getComment($get);
    		$data["commentList"] = $comment["data"];
    		$this->assign($data);
	    	$this->display("detail_tab3");
    	}elseif($get["tab"] == 4){
    		//课程笔记
    		$note = D("MyCourse")->getNote($get);
    		$data["note"] = $note["data"]["note"];
    		$data["pageNav"] = $note["data"]["pageNav"];
    		$this->assign($data);
	    	$this->display("detail_tab4");
    	}elseif($get["tab"] == 5){
    		//课程问答
    		$ques = D("MyCourse")->getQues($get);
    		$data["ques"] = $ques["data"]["ques"];
    		$data["pageNav"] = $ques["data"]["pageNav"];
    		$data["quesNum"] = $ques["data"]["quesNum"];
    		if(!$get["chapIndex"]) $get["chapIndex"] = 0;
    		$data["chapIndex"] = $get["chapIndex"];
    		$this->assign($data);
	    	$this->display("detail_tab5");
    	}
    }
    
    /**
     * 添加评价 每人只可评价一次
     * course_id 课程id
     * content 评价内容
     */
    public function addComment(){
    	$post = I("post.");
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		$return = array("code"=>1011, "message"=>"未获取到用户，请登录");
	    	echo json_encode($return);
	    	return;
    	}
    	
    	$post = I("post.");
    	$post["course_id"] = $post["course_id"] + 0;
    	if(!is_int($post["course_id"]) || $post["course_id"] < 1){
    		$return = array("code"=>1012, "message"=>"请提交课程id");
    		echo json_encode($return);
    		return;
    	}
    	
    	if(!$post["content"]){
    		$return = array("code"=>1013, "message"=>"请填写笔记内容");
    		echo json_encode($return);
    		return;
    	}
    	
    	$return = D("MyCourse")->addComment($post);
    	if($return["code"] != 1000){
    		$return = array("code"=>1014, "message"=>$return["message"]);
    		echo json_encode($return);
    		return;
    	}
    	@D('Trigger')->intergrationTrigger($user_id, 13);
    	$return = array("code"=>1000, "message"=>"添加成功", "last_id"=>$return["last_id"]);
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 评价点赞
     * comment_id 评价id
     * praise_status 点赞状态 1点赞 0取消赞
     */
    public function makePraise(){
    	$post = I("post.");
    	
   		$post["comment_id"] += 0;
    	if(!is_int($post["comment_id"]) || $post["comment_id"] < 1){
    		$return = array("code"=>1012, "message"=>"请提交评价id");
    		echo json_encode($return);
    		return;
    	}
    	
    	if($post["praise_status"] != 0){
    		$post["praise_status"] = 1;
    	}
    	
    	$return  = D("myCourse")->makePraise($post);
    	if($return["code"] != 1000){
	    	$return = array("code"=>1013, "message"=>$return["message"]);
	    	echo json_encode($return);
	    	return;
    	}
    	
    	$return = array("code"=>1000, "message"=>"成功");
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 评价回复
     * comment_id 评价id
     * content 回复内容
     */
    public function reply(){
    	$post = I("post.");
    	$user_id = $_SESSION["user"]["id"];
    	$post["comment_id"] += 0;
    	if(!is_int($post["comment_id"]) || $post["comment_id"] < 1){
    		$return = array("code"=>1012, "message"=>"请提交评价id");
    		echo json_encode($return);
    		return;
    	}
    	
    	if(!$post["content"]){
    		$return = array("code"=>1013, "message"=>"请填写回复内容");
    		echo json_encode($return);
    		return;
    	}
    	
    	$resp  = D("myCourse")->reply($post);
    	if($resp["code"] != 1000){
    		$return = array("code"=>1013, "message"=>$resp["message"]);
    		echo json_encode($return);
    		return;
    	}
    	
    	@D('Trigger')->intergrationTrigger($user_id, 13);
    	$return = array("code"=>1000, "message"=>"成功", "last_id"=>$resp["last_id"]);
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 删除评价
     * comment_id 评价id
     */
    public function delComment(){
    	$post = I("post.");
    	$post["course_id"] += 0;
    	
    	$post["comment_id"] += 0;
    	if(!is_int($post["comment_id"]) || $post["comment_id"] < 1){
    		$return = array("code"=>1012, "message"=>"请提交评价id");
    		echo json_encode($return);
    		return;
    	}
    	
    	$return  = D("myCourse")->delComment($post);
    	if($return["code"] != 1000){
    		$return = array("code"=>1013, "message"=>$return["message"]);
    		echo json_encode($return);
    		return;
    	}
    	
    	$return = array("code"=>1000, "message"=>"成功");
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 提交笔记
     * course_id 课程id
     * project_id 项目id
     * content 笔记内容
     * is_public 是否公开 1公开  2不公开
     */
    public function addNote(){
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
	    	$return = array("code"=>1011, "message"=>"未获取到用户，请登录");
	    	echo json_encode($return);
	    	return;
    	}
    	
    	$post = I("post.");
    	$post["course_id"] = $post["course_id"] + 0;
    	if(!is_int($post["course_id"]) || $post["course_id"] < 1){
	    	$return = array("code"=>1012, "message"=>"请提交课程id");
	    	echo json_encode($return);
	    	return;
    	}
    	
    	$post["project_id"] = $post["project_id"] + 0;
    	if(!is_int($post["project_id"])){
	    	$return = array("code"=>1012, "message"=>"请提交项目id");
	    	echo json_encode($return);
	    	return;
    	}
    	
    	if(!$post["content"]){
	    	$return = array("code"=>1013, "message"=>"请填写笔记内容");
	    	echo json_encode($return);
	    	return;
    	}
    	
    	//1公开 2不公开
    	$post["is_public"] += 0;
    	if($post["is_public"] != 2){
    		$post["is_public"] = 1;
    	}
    	
    	$return = D("MyCourse")->addNote($post);
    	if($return["code"] != 1000){
	    	$return = array("code"=>1011, "message"=>$return["message"]);
	    	echo json_encode($return);
	    	return;
    	}
    	
    	@D('Trigger')->intergrationTrigger($user_id, 10);
    	
    	$return = array("code"=>1000, "message"=>"添加成功", "last_id"=>$return["last_id"]);
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 删除笔记
     * note_id 笔记ID
     */
    public function delNote(){
    	$post = I("post.");
    	$post["note_id"] = $post["note_id"] + 0;
    	if(!is_int($post["note_id"]) || $post["note_id"] < 1){
    		$return = array("code"=>1012, "message"=>"请提交笔记id");
    		echo json_encode($return);
    		return;
    	}
    	
    	D("MyCourse")->delNote($post);
    	$return = array("code"=>1000, "message"=>"成功");
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 课程 && 讲师评分
     * course_id 课程id
     * score 分数，满分五分
     * type 打分类型 1课程 2讲师
     */
    public function giveScore(){
    	$post = I("post.");
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		$return = array("code"=>1011, "message"=>"未获取到用户，请登录");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post = I("post.");
    	$post["course_id"] = $post["course_id"] + 0;
    	if(!is_int($post["course_id"]) || $post["course_id"] < 1){
    		$return = array("code"=>1012, "message"=>"请提交课程id");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post["score"] = $post["score"] + 0;
    	if($post["score"] > 5 || $post["score"] < 0){
    		$return = array("code"=>1013, "message"=>"分数必须在0-5之间");
    		echo json_encode($return);
    		return;
    	}
    	
    	if($post["type"] != 1){
    		$post["type"] = 2;
    	}
    	
    	$resp = D("MyCourse")->giveScore($post);
    	if($resp["code"] != 1000){
	    	$return = array("code"=>$resp["code"], "message"=>$resp["message"]);
	    	echo json_encode($return);
	    	return;
    	}
    	
    	@D('Trigger')->intergrationTrigger($user_id, 8);
    	
    	$return = array("code"=>1000, "message"=>"添加成功", "avgScore"=>$resp["avgScore"]);
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 加入我的课程
     * course_id 课程id
     */
    public function addCourse(){
    	$post = I("post.");
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		$return = array("code"=>1011, "message"=>"未获取到用户，请登录");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post["course_id"] += 0;
    	if(!is_int($post["course_id"]) || $post["course_id"] < 1){
    		$return = array("code"=>1012, "message"=>"课程id有误");
    		echo json_encode($return);
    		return;
    	}
    	
    	$resp = D("MyCourse")->addCourse($post);
    	if($resp["code"] != 1000){
	    	$return = array("code"=>$resp["code"], "message"=>$resp["message"]);
	    	echo json_encode($return);
	    	return;
    	}
    	
    	@D('Trigger')->intergrationTrigger($user_id, 7);
    	
    	$return = array("code"=>1000, "message"=>"添加成功");
    	echo json_encode($return);
    	return;
    }
    
    /*
     * 章节进度
     * project_id
     * course_id
     * fileName
     * fileSrc
     * fileType
     * status
     * timeLen
     * time_percent
     */
    public function chapter(){
    	$post = I("post.");
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		$return = array("code"=>1011, "message"=>"未获取到用户，请登录");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post["course_id"] += 0;
    	if(!is_int($post["course_id"]) || $post["course_id"] < 1){
    		$return = array("code"=>1012, "message"=>"课程id有误");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post["project_id"] += 0;
    	if(!is_int($post["project_id"])){
    		$return = array("code"=>1012, "message"=>"项目id有误");
    		echo json_encode($return);
    		return;
    	}
    	
    	if(!$post["fileName"]){
    		$return = array("code"=>1013, "message"=>"请提交章节名称");
    		echo json_encode($return);
    		return;
    	}
    	
    	if(!$post["fileSrc"]){
    		$return = array("code"=>1013, "message"=>"请提交章节附件地址");
    		echo json_encode($return);
    		return;
    	}
    	
    	if(!$post["fileType"]){
    		$return = array("code"=>1013, "message"=>"请提交章节附件类型");
    		echo json_encode($return);
    		return;
    	}
    	
    	if($post["fileType"] == "video" || $post["fileType"] == "audio"){
	    	if($post["time_percent"] == "100"){
	    		$post["status"] = 3;
	    	}else{
	    		$post["status"] = 2;
	    	}
    	}else{
    		$post["status"] = 3;
    		$post["time_percent"] = 100;
    	}
    	
    	/**
    	 * 获取课件章节进度
    	 * 步骤 1获取章节数量
    	 * 步骤 2获取章节学习情况
    	 */
    	$scoPercent = 0;//课件学习百分比
    	if($post["fileType"] == "kejian" && strstr($post["fileSrc"], "scorm.occupationedu.com")){
    		$post["status"] = 2;//学习中
    		$post["timeLen"] = 0;
    		$post["time_percent"] = 0;
    		
    		$apiName = "apiGetCourse";//api名称
    		$apiParam["scoUrl"] = $post["fileSrc"];//课件地址
    		$getSco = self::apiReq($apiName, $apiParam);
    		if($getSco["code"] == 1000){
    			if(isset($getSco["scoChap"])){
    				$scoChapNum = count($getSco["scoChap"]);
    				$scoPassed = 0;
    				foreach ($getSco["scoChap"] as $chapValue){
    					$apiStudyName = "apiGetStudy";//api名称
    					$apiStudyParam["scoUrl"] = $post["fileSrc"];//课件地址
    					$apiStudyParam["outid"] = $post["project_id"]."_".$post["course_id"];//外部id
    					$apiStudyParam["entry"] = $chapValue["id"];//章节id
    					$getStudy = self::apiReq($apiStudyName, $apiStudyParam);
    					if($getStudy["code"] == 1000){
    						if($getStudy["data"]["lesson_status"] == "passed"){
    							$scoPassed ++;
    						}
    					}
    				}
    				
    				if($scoPassed > 0){
    					$scoPercent = $scoPassed / $scoChapNum;
    					$scoPercent = round($scoPercent, 2) * 100;
    				}
    				
    				if($scoPassed == $scoChapNum){
	    				$post["status"] = 3;//学习完成
    				}
    				$post["timeLen"] = "0";
    				$post["time_percent"] = $scoPercent;
    			}
    		}
    	}
    	
    	if(!$post["timeLen"]){
    		$post["timeLen"] = 0;
    	}
    	
    	$post["time_percent"] += 0;
    	
    	$resp = D("MyCourse")->chapter($post);
    	$return = array("code"=>1000, "message"=>"成功", "percent"=>$scoPercent);
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 
     */
    public function publicCourse(){
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		echo "未获取到用户，请登录";
    		exit;
    	}
    
    	$get = I("get.");
    	$get["page"] = $get["p"] + 0;
    	if(!is_int($get["page"]) || $get["page"] < 1){
    		$get["page"] = 1;
    	}
    
    	$get["type"] += 0;
    	if($get["type"] != 2){
    		$get["type"] = 1;
    	}
    
    	$get["cid"] += 0;
    	if(!is_int($get["cid"]) || $get["cid"] < 0){
    		$get["cid"] = "";
    	}

    	$get["pageLen"] = 16;
    	$data = D("MyCourse")->publicCourse($get);
    	$data["type"] = $get["type"];
    	$data["cid"] = $get["cid"];
    	$data["keyword"] = $get["keyword"];
    	$data["classid"] = $get["classid"];
		$data["arrangement_id"] = $get["arrangement_id"];
    	//print_r($data);
    	$this->assign($data);
    	$this->display("public");
    }
    
    //浏览次数
    public function seeNum(){
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		return false;
    	}
    	
    	$course_id = $_POST["course_id"] + 0;
    	if(is_int($course_id) && $course_id > 0){
    		$sql = "UPDATE __COURSE__ SET `click_count`=`click_count`+'1' WHERE id='$course_id' LIMIT 1";
    		M()->execute($sql);
    	}
    }
    
    //下载文件
    public function download(){
    	$file = $_GET['file'];
    	$folderArr = explode("/", $file);
    	$filename = end($folderArr);
    	header("Content-type:octet/stream");
    	header("Content-disposition:attachment;filename=".$filename.";");
    	header("Content-Length".filesize($file));
    	readfile($file);
    }
    
    /*
     * 课程问答-新的问题
     * title 标题
     * content 内容
     * course_id 课程id
     * project_id 项目id，选修课此项为0
     * chapter 课程章节 0全部
     */
    public function newQues(){
    	$post = I("post.");
    	$post["course_id"] += 0;
    	$post["project_id"] += 0;
    	$post["chapter"] += 0;
    	if(!is_int($post["course_id"]) || $post["course_id"] < 1){
    		$return = array("code"=>1011, "message"=>"缺少课程id");
	    	echo json_encode($return);
	    	return;
    	}
    	if(!is_int($post["project_id"])){
    		$return = array("code"=>1012, "message"=>"缺少项目id");
	    	echo json_encode($return);
	    	return;
    	}
    	
    	if(!is_int($post["chapter"])){
    		$post["chapter"] = 0;
    	}
    	$post["title"] = str_replace(" ","",$post["title"]);
    	$post["title"] = str_replace("&nbsp;","",$post["title"]);
    	if(!$post["title"] || $post["title"] == ""){
    		$return = array("code"=>1013, "message"=>"请填写标题");
    		echo json_encode($return);
    		return;
    	}
    	$post["content"] = str_replace(" ","",$post["content"]);
    	$post["content"] = str_replace("&nbsp;","",$post["content"]);
    	if(!$post["content"] || $post["content"] == ""){
    		$return = array("code"=>1014, "message"=>"请填写提问内容");
    		echo json_encode($return);
    		return;
    	}
    	
    	if(mb_strlen($post["title"], 'utf8') > 140){
    		$return = array("code"=>1015, "message"=>"标题长度最长140个字符");
    		echo json_encode($return);
    		return;
    	}
    	
    	$last_id = D("MyCourse")->newQues($post);
    	$return = array("code"=>1000, "message"=>"成功", "last_id"=>$last_id, "curr_time"=>date("Y-m-d H:i:s"));
    	echo json_encode($return);
    	return;
    }
    
    /*
     * 课程问答-回答页面
     * ques_id 问题id
     */
    public function answerPage(){
    	$get = I("get.");
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		echo "未获取到用户，请登录";
    		exit;
    	}
    	
    	$get["ques_id"] += 0;
    	if(!is_int($get["ques_id"]) || $get["ques_id"] < 1){
    		echo "非法操作，未获取到问题id";
    		exit;
    	}
    	
    	$resp = D("MyCourse")->answerPage($get["ques_id"], $get["p"]);
    	$this->assign($resp);
    	$this->display("answer_page");
    }
    
    /*
     * 课程问答-提交答案
     * ques_id 问题id
     * content 答案内容
     */
    public function answer(){
    	$post = I("post.");
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		$return = array("code"=>1011, "message"=>"未获取到用户");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post["ques_id"] += 0;
    	if(!is_int($post["ques_id"]) || $post["ques_id"] < 1){
    		$return = array("code"=>1012, "message"=>"请提交问题id");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post["content"] = str_replace(" ","",$post["content"]);
    	$post["content"] = str_replace("&nbsp;","",$post["content"]);
    	if($post["content"] == ""){
    		$return = array("code"=>1013, "message"=>"答案不能为空");
    		echo json_encode($return);
    		return;
    	}
    	if(mb_strlen($post["content"]) > 1000){
    		$return = array("code"=>1013, "message"=>"答案限1000字以内");
    		echo json_encode($return);
    		return;
    	}
    	
    	$resp = D("MyCourse")->answer($post);
    	if($resp["code"] != 1000){
    		$return = array("code"=>1014, "message"=>$resp["message"]);
    		echo json_encode($return);
    		return;
    	}
    	
    	$return = array("code"=>1000, "message"=>"成功");
    	echo json_encode($return);
    	return;
    }
    
    /**
     * API请求
     * $apiName 请求api名称
     * $param 提交参数，一维数组
     */
    private function apiReq($apiName, $param){
    	//post方式
    	$requestUrl = 'apiGetCourse';
    	$mainHost = "http://scorm.occupationedu.com/Home/course/";
    	$requestUrl = $mainHost.$apiName;
    
    	if(!$apiName){
    		return array("code"=>1001, "message"=>"api name is invalid");
    	}
    
    	if(!$param || !is_array($param)){
    		return array("code"=>1002, "message"=>"param is not array");
    	}
    	
    	foreach ($param as $pvalue){
    		if(is_array($pvalue)){
    			return array("code"=>1003, "message"=>"param is not a valid parameter");
    		}
    	}
    	
    	$urlEx = explode("?", $param["scoUrl"]);
    	$urlEx = explode("&", $urlEx[1]);
    	foreach ($urlEx as $key=>$value){
    		$urlParam = explode("=", $value);
    		if(count($urlParam) == 2){
	    		$param[$urlParam[0]] = $urlParam[1];
    		}
    	}
    	
    	$options = array(
    			CURLOPT_RETURNTRANSFER => true,
    			CURLOPT_HEADER         => false,
    			CURLOPT_POST           => true,
    			CURLOPT_POSTFIELDS     => $param
    	);
    	$ch = curl_init($requestUrl);
    	curl_setopt_array($ch,$options);
    	$result = curl_exec($ch);
    	curl_close($ch);
    
    	$result = json_decode($result);
    	$result = self::objToArray($result);
    	return $result;
    }
    
    //处理xmlObject为数组
    private function objToArray($obj){
    	if(is_object($obj)){
    		$obj = get_object_vars($obj);
    	}
    	if(is_array($obj)){
    		if(count($obj) == 0){
    			$obj = "";
    		}else{
    			foreach ($obj as $k=>$v){
    				$obj[$k] = self::objToArray($v);
    			}
    		}
    	}
    	return $obj;
    }
}