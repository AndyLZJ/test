<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 个人中心_我的笔记
 * @author Dujuqiang 20170317
 */
class MyNoteModel extends BaseModel{
	//初始化
	public function __construct(){}
	
    /**
     * 获取图表数据及数据列
     * page 当前页码，不传默认为1
     * pageLen 每页数据条数，不传默认15
     */
	public function getData($param){
		$user_id = $_SESSION["user"]["id"];
		$start = ($param["page"] - 1) * $param["pageLen"];
		$where["user_id"] = $user_id;
		
		$note = M("course_note")
			->field("course_id,project_id,min(id) idn")
			->where($where)
			->group("course_id,project_id")
			->order("idn desc")
			->page($param["page"],$param["pageLen"])
			->select();
		foreach ($note as $key=>$value){
			$noteWhere["user_id"] = $user_id;
			$noteWhere["course_id"] = $value["course_id"];
			$noteWhere["project_id"] = $value["project_id"];
			//数据库版本区分
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$noteInfo = M("course_note")->field("to_char(time,'YYYY-MM-DD HH24:MI:SS') as time,note_content")->where($noteWhere)->order("id desc")->find();
			}else{
				$noteInfo = M("course_note")->where($noteWhere)->order("id desc")->find();
			}
			
			$note[$key]["time"] = $noteInfo["time"];
			$note[$key]["note_content"] = $noteInfo["note_content"];
			
			$thisCourse = M("course")->field("course_name")->where("id=".$value["course_id"])->limit(1)->select();
			if($value["project_id"] == 0 || !$value["project_id"]){
				$note[$key]["project_id"] = "true";
				$thisCourse[0]["course_name"] .= " [选修]";
			}

			//统计笔记总数
			$map['project_id'] = array("eq",$value['project_id']);
			$map['course_id'] = array("eq",$value['course_id']);

			$note[$key]["note_num"] = M("course_note")->field("course_name")->where($map)->count();
			$note[$key]["course_name"] = $thisCourse[0]["course_name"];
			$note[$key]['url'] = U('Admin/MyCourse/detail#note',array('type'=>3,'project_id'=>$value['project_id'],'course_id'=>$value['course_id'],'typeid'=>1));
		}
		
		$total = M("course_note")
				->field("course_id,project_id")
				->where($where)
				->group("course_id,project_id")
				->select();
		$totalNum = count($total);
		
		$page = "";
		if($totalNum > $param["pageLen"]){
			$page = $this->pageClass($totalNum, $param["pageLen"]);
		}
		return array("pageNav"=>$page, "noteList"=>$note);
	}
	
	//制造假数据
	public function makeTest(){
		$user_id = $_SESSION["user"]["id"];
		$where["status"] = 1;
		//$where["course_way"] = 1;
		$where["auditing"] = 1;
		$list = M("course")->where($where)->select();
		foreach ($list as $key=>$value){
			$randNum = rand(1, 5);
			for($i=0; $i<$randNum; $i++){
				$data3["user_id"] = $user_id;
				$data3["course_id"] = $value["id"];
				$data3["note_content"] = uniqid()."我的笔记我的笔记我的笔记我的笔记我的笔记我的笔记";
				$data3["time"] = date("Y-m-d H:i:s",time() - 3*86400 + $key * 3600 + $i * 3600);
				$data3["is_public"] = 1;
				M("course_note")->add($data3);
			}
		}
		print_r($list);exit;
		
		foreach ($list as $value){
			/* $data["project_id"] = $value["project_id"];
			$data["user_id"] = $user_id;
			M("designated_personnel")->add($data); */
			
			/* 
			$data2["user_id"] = $user_id;
			$data2["course_id"] = $value["course_id"];
			$data2["recently_lookup_time"] = 66;
			$data2["start_time"] = date("Y-m-d H:i:s",time() - 86400);
			$data2["end_time"] = date("Y-m-d H:i:s",time() - 6400);
			M("course_record")->add($data2); */
			
			$randNum = rand(1, 20);
			for($i=0; $i<$randNum; $i++){
				$data3["user_id"] = $user_id;
				$data3["course_id"] = $value["course_id"];
				$data3["note_content"] = uniqid()."我的笔记我的笔记我的笔记我的笔记我的笔记我的笔记";
				$data3["time"] = date("Y-m-d H:i:s",time() - rand(50, 9999));
				$data3["is_public"] = 1;
				M("course_note")->add($data3);
			}
		}
		print_r($list);
	}
}