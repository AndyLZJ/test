<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 费用统计分析
 * @author Dujuqiang 20170315
 */
class DataController extends AdminBaseController{
	//费用统计首页
	public function cost(){

		//获取组织架构
		$tool_tree = D("Tool")->tree(0);
		
		$data["startTime"] = date("Y-m", strtotime("-3 month"));
		$data["endTime"] = date("Y-m");
		$this->assign($data);
		$this->assign("tool_tree",$tool_tree);
    	$this->display();
    }

	/**
	 * 获取当前选择部门
	 * 所有计划名称
	 */
	public function getPlanName(){

		$results = D("Data")->getPlanName();

		$this->ajaxReturn($results,'json');

	}

	/**
	 * 获取费用统计 指定项目
	 */
	public function itemParameter(){

		$results = D("Data")->itemParameter();

		$this->ajaxReturn($results,'json');

	}
    
    /**
     * 获取数据列
     * startTime 开始时间
     * endTime 结束时间
     * keyword 搜索关键字
     * page 当前页码，不传默认为1
     * pageLen 每页数据条数，不传默认30
     */
    public function getData(){
    	header('Content-type:text/json');
    	$user_id = $_SESSION["user"]["id"];
    	if(!$user_id){
    		$return = array("code"=>1011, "message"=>"未获取到用户，请登录");
    		echo json_encode($return);
    		return;
    	}
    	
    	$post = I("post.");
   		$timeVerifi = self::timeVerifi($post["startTime"], $post["endTime"]);
    	if($timeVerifi["code"] != 1000){
    		$return = array("code"=>1012, "message"=>$timeVerifi["message"]);
    		echo json_encode($return);
    		return;
    	}
    	$post["startTime"] = $timeVerifi["startTime"];
    	$post["endTime"] = $timeVerifi["endTime"];
    	
    	$post["page"] += 0;
    	if(!is_int($post["page"]) || $post["page"] < 1){
    		$post["page"] = 1;
    	}
    	
    	$post["pageLen"] += 0;
    	if(!is_int($post["pageLen"]) || $post["pageLen"] < 1){
    		$post["pageLen"] = 20;
    	}
    	
    	$data = D('Data')->getData($post);
    	$return = array("code"=>1000, "message"=>"操作成功", "list"=> $data["list"], "page"=>$data["page"]);
    	echo json_encode($return);
    	return;
    }
    
    /**
     * 获取图表数据
     * startTime 开始时间
     * endTime 结束时间
     * keyword 搜索关键字
     */
    public function getEchart(){
    	$post = I("post.");
    	$timeVerifi = self::timeVerifi($post["startTime"], $post["endTime"]);
    	if($timeVerifi["code"] != 1000){
    		return false;
    	}
    	
    	$post["startTime"] = $timeVerifi["startTime"];
    	$post["endTime"] = $timeVerifi["endTime"];
    	$data = D('Data')->getEchart($post);
    	$return = array("code"=>1000, "message"=>"操作成功", "echart"=> $data["echart"]);
    	echo json_encode($return);
    	return;
    }
    
    /*
     * 时间验证
     * $startTime 开始时间 2016-06
     * $endTime 结束时间 2017-06
     * 返回开始结束时间 2016-06-01 00:00:00
     * 2017-06-31 00:00:00
     */
	public function timeVerifi($startTime, $endTime){
    	if(!preg_match("/^[0-9]{4}-[0-9]{2}$/", $startTime)){
    		return array("code"=>1012, "message"=>"开始时间格式不正确");
    	}else{
    		$timeArr = explode("-", $startTime);
    		if($timeArr[1] > 12 || $timeArr[1] < 1){
    			return array("code"=>1013, "message"=>"开始时间格式不正确");
    		}
    		$startTime .= "-01 00:00:00";
    	}
    	
    	if(!preg_match("/^[0-9]{4}-[0-9]{2}$/", $endTime)){
    		return array("code"=>1014, "message"=>"结束时间格式不正确");
    	}else{
    		$timeArr = explode("-", $endTime);
    		if($timeArr[1] > 12 || $timeArr[1] < 1){
    			return array("code"=>1015, "message"=>"结束时间格式不正确");
    		}
    	
    		//2017-01  2017-03  处理为  2017-01-01 00:00:00 2017-03-31 23:59:59
    		$endTime .= "-01 00:00:00";
    		if(strtotime($endTime) > time()){
    			$endTime = date("Y-m-01 00:00:00");
    		}
    		$endTime = date("Y-m-d 00:00:00",strtotime('+1 month',strtotime($endTime)));
    	}
    	
    	if(strtotime($startTime) > strtotime($endTime)){
    		return array("code"=>1016, "message"=>"结束时间必须大于开始时间");
    	}
    	
    	if((strtotime($endTime) - strtotime($startTime)) / 86400 > 365 * 3){
    		return array("code"=>1017, "message"=>"最多支持查询时长为三年");
    	}
    	
    	return array("code"=>1000, "message"=>"验证成功", "startTime"=>$startTime, "endTime"=>$endTime);
    }
    
    //导出当前条件下的结果集
    public function download(){
    	$get = I("get.");
    	$timeVerifi = self::timeVerifi($get["startTime"], $get["endTime"]);
    	if($timeVerifi["code"] != 1000){
    		echo "时间格式有误";
    		exit;
    	}
    	$get["startTime"] = $timeVerifi["startTime"];
    	$get["endTime"] = $timeVerifi["endTime"];
    	
    	/* $page = 1;
    	$pageLen = 100;
    	$get["pageLen"] = $pageLen;
    	$get["page"] = $page;
    	
    	$getDownload = D("data")->getDownload($get);
    	print_r($getDownload);exit; */
    	
    	Vendor('PHPExcel.PHPExcel');
    	header("Content-Type: text/csv");
    	$fileName = date("YmdHi").urlencode("费用统计");//需要编码，否则IE下文件名称错误
		header("Content-Disposition:filename=".$fileName.".csv");
    	
    	$title[] = "序号";
    	$title[] = "培训项目";
    	$title[] = "开始时间";
    	$title[] = "结束时间";
    	$title[] = "实际参与人数";
    	$title[] = "项目预算（元）";
    	$title[] = "实际费用（元）";
    	foreach($title as $key=>$titleValue){
    		$titleValue = iconv('utf-8','gb2312',$titleValue);
    		$title[$key] = $titleValue;
    	}
    	$fp = fopen('php://output', $fileName);
    	fputcsv($fp, $title);
    	
    	$page = 1;
    	$pageLen = 50;
    	$get["pageLen"] = $pageLen;
		while(true){
	    	$get["page"] = $page;
			$getDownload = D("data")->getDownload($get);
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
				fputcsv($fp, $row);
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
	 * 费用统计详细页
	 */
	public function article(){

		$data = D('Data')->article();

		$this->assign($data);

		$this->display();


	}



}