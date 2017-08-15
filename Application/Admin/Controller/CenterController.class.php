<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 *
 * @Andy
 * dujunqiang 20170719 修改学习档案部分
 */
class CenterController extends AdminBaseController{
	
	/**
	 * 学习档案
	 * type 1全部 2在线培训 3线下培训
	 * startTime 开始时间
	 * endTime 查询结束时间
	 * p 页码
	 */
	public function study(){
		$get = I("get.");
		$oldGet = $get;
		$get["type"] = (int)$get["type"];
		if($get["type"] != 1 && $get["type"] != 2 && $get["type"] != 3){
			$get["type"] = 1;
		}
		
		//年月 转为 年月日时分秒
		if(!preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $get["startTime"])){
			$get["startTime"] = "";
		}
		
		if(!preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $get["endTime"])){
			$get["endTime"] = "";
		}
		
		$get["p"] = (int)$get["p"];
		if($get["p"] <= 0){
			$get["p"] = 1;
		}

		$data = D('Center')->study($get);
		$data["type"] = $get["type"];
		$data["startTime"] = $oldGet["startTime"];
		$data["endTime"] = $oldGet["endTime"];
		$data["circleType"] = $oldGet["circleType"];
		$this->assign($data);
		$this->display();
	}
	
	/**
	 * 学分统计筛选
	 * creditType 1年度 2季度 3月份
	 */
	public function getStudyCredit(){
		$creditType = I("get.creditType");
		if($creditType != 1 && $creditType != 2 && $creditType != 3){
			$creditType = 1;
		}
		
		$data = D("Center")->getStudyCredit($creditType);
		echo json_encode($data);
	}
	
	/**
	 * 学习档案-导出数据
	 * type 1全部 2在线培训 3线下培训
	 * startTime 开始时间
	 * endTime 查询结束时间
	 */
	public function studyExport(){
		$get = I("get.");
		$get["type"] = (int)$get["type"];
		if($get["type"] != 1 && $get["type"] != 2 && $get["type"] != 3){
			$get["type"] = 1;
		}
		
		//年月 转为 年月日时分秒
		if(!preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $get["startTime"])){
			$get["startTime"] = "";
		}
		
		if(!preg_match('/^[0-9]{4}-[0-9]{1,2}$/', $get["endTime"])){
			$get["endTime"] = "";
		}
		
		$get["p"] = (int)$get["p"];
		if($get["p"] <= 0){
			$get["p"] = 1;
		}
		
		Vendor('PHPExcel.PHPExcel');
		header("Content-Type: text/csv");
		if($get["type"] == 1){
			$fileName = urlencode("全部").date("YmdHi");//需要编码，否则IE下文件名称错误
			$title[] = "序号";
			$title[] = "姓名";
			$title[] = "所属部门";
			$title[] = "学习课程数";
			$title[] = "获得学分";
			$title[] = "内部培训班";
			$title[] = "外派培训班";
			$title[] = "外出授课";
			$title[] = "获得学分";
			$title[] = "学习时长";
			$title[] = "考试次数";
			$title[] = "问卷次数";
			$title[] = "笔记数";
		}elseif($get["type"] == 2){
			$fileName = urlencode("在线培训").date("YmdHi");//需要编码，否则IE下文件名称错误
			$title[] = "序号";
			$title[] = "完成学习时间";
			$title[] = "课程名称";
			$title[] = "课程时长";
			$title[] = "学分";
			$title[] = "课程类型";
		}elseif($get["type"] == 3){
			$fileName = urlencode("线下培训班").date("YmdHi");//需要编码，否则IE下文件名称错误
			$title[] = "序号";
			$title[] = "主办部门";
			$title[] = "培训项目名称";
			$title[] = "培训类别";
			$title[] = "培训开始时间";
			$title[] = "培训结束时间";
			$title[] = "培训学时";
			$title[] = "培训对象";
			$title[] = "培训组织人员";
			$title[] = "获得学分";
		}
		header("Content-Disposition:filename=".$fileName.".csv");
		
		foreach($title as $key=>$titleValue){
			$titleValue = iconv('utf-8','gb2312',$titleValue);
			$title[$key] = $titleValue;
		}
		$fp = fopen('php://output', $fileName);
		fputcsv($fp, $title);
		
		$page = 1;
		$pageLen = 50;
		$get["pageLen"] = $pageLen;
		$dataOrder = 1;
		while(true){
			$get["page"] = $page;
			$getDownload = D('Center')->study($get);
			$getDownload = $getDownload["list"];
			
			foreach($getDownload as $thisValue){
				//数据编码
				foreach($thisValue as $key => $val){
					$val = iconv('utf-8','gb2312',$val);
					if(preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}/', $val)){
						$row[$key] = $val."\r";
					}else{
						$row[$key] = $val;
					}
				}
				
				$newRow = array();
				if($get["type"] == 1){
					$newRow[] = 1;
					$newRow[] = $row["username"];
					$newRow[] = $row["part"];
					$newRow[] = $row["course_num"];
					$newRow[] = $row["upCredit"];
					$newRow[] = $row["project0"];
					$newRow[] = $row["project1"];
					$newRow[] = $row["project2"];
					$newRow[] = $row["downCredit"];
					$newRow[] = $row["hours"];
					$newRow[] = $row["exam_num"];
					$newRow[] = $row["survey_num"];
					$newRow[] = $row["note_num"];
				}elseif($get["type"] == 2){
					$newRow[] = $dataOrder;
					$newRow[] = $row["create_time"];
					$newRow[] = $row["course_name"];
					$newRow[] = $row["course_time"];
					$newRow[] = $row["course_credit"];
					$newRow[] = $row["course_type"];
				}elseif($get["type"] == 3){
					$newRow[] = $dataOrder;
					$newRow[] = $row["part"];
					$newRow[] = $row["project_name"];
					$newRow[] = $row["project_type"];
					$newRow[] = $row["start_time"];
					$newRow[] = $row["end_time"];
					$newRow[] = $row["total_time"];
					$newRow[] = $row["class_name"];
					$newRow[] = $row["manager"];
					$newRow[] = $row["credit"];
				}
				
				$dataOrder ++;
				
				fputcsv($fp, $newRow);
				unset($row);
			}
			
			//每隔 $pageLen行，刷新一下输出 buffer
			ob_flush();
			flush();
		
			$page ++;
			if(count($getDownload) < $pageLen){
				break;
			}
		}
	}
	
	/**
	 * 找人PK
	 */
	public function pk(){

		$items = D('Center')->pk();

		$this->assign($items);
		$this->display();
	}


	/**
	 * PK 成员
	 */
	public function pkMember(){

		$items = D('Center')->pkMember();

		$this->assign($items);

		$this->display();

	}

	/**
	 * PK 成员Ajax
	 */
	public function memberAjax(){

		$items = D('Center')->memberAjax();

		$this->ajaxReturn($items,'json');

	}

	/**
	 * PK 部门
	 */
	public function pkDepartment(){


		$data = D('Center')->pkDepartment();

		$this->assign($data);

		$this->display();

	}

	/**
	 * PK 部门Ajax
	 */
	public function departmentAjax(){

		$items = D('Center')->departmentAjax();

		$this->ajaxReturn($items,'json');

	}

	/**
	 * 学习目标
	 * @return [type] [description]
	 */
	public function goal(){
		$data = D('Center')->getStudyData();
		$this->assign('data',$data);
		$this->display();
	}

}
