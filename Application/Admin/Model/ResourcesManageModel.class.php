<?php

namespace Admin\Model;

use Common\Model\BaseModel;

/**
 * ------------------------------------------------------------------------------
 * Description  问卷类别操作模型
 * @filename ResourcesManageModel.class.php
 * @author Andy
 * @datetime 2016-12-30 11:37:29
 * -------------------------------------------------------------------------------
 */
class ResourcesManageModel extends BaseModel {

    function __construct(){
    	
    }
    //定义表前缀表名，过滤表单字段
    protected $tablePrefix = 'think_';
    protected $trueTableName = 'think_survey_category';
    protected $insertFields = array('id','cat_name');
    protected $pk = 'id';
    //定义验证规则
    protected $_validate = array(
        array('cat_name','require','分类类别名称不能为空',1),
    );
    //接收用逗号分隔开的字符串数据再转化为数组
     function _before_insert(&$data) {
        $data = explode(',',$data);
        $arr = array();
       	foreach($data as $k => $v){
       		if($v){
	           $arr[$k]['cat_name'] = $v;
	           $arr[$k]['pid'] = 0;
	           $arr[$k]['sort'] = 0;
           }
        }
           $data = $arr;
           return $data;
	}
	
	
	/*
	 * 导入试卷处理方法
	 * @pamar  $file  导入的文件路径
	 * @return $data  导入后经过处理的文件数组
	 */
	function uploadExam($file,$name){
	    // 拆分数组拼装对应数据表的数组结构
	    $list = import_excel($file);

	    $tempArr = array('题型','题目描述','A','B','C','D','E','标准答案','得分关键字','分值','试题解析',null,null,null);

	    if($list[2] != $tempArr){
	    	return false;
	    }
	    $data = array();
	    $totalscore = 0;
	    foreach($list as $k=>$v){
	        if($k > 2 && !empty($v['0'])){


				if(empty($v['1']) || empty($v['2']) || empty($v['3']) || empty($v['7']) || empty($v['9']) || empty($v['0']) || !in_array($v['0'],array('1','2','3','4'))){
					return false;
				}

	            $data[$k-3]['title'] = trim($v['1']);
	            $data[$k-3]['optiona'] = $v['2'];
	            $data[$k-3]['optionb'] = $v['3'];
	            $data[$k-3]['optionc'] = $v['4'];
	            $data[$k-3]['optiond'] = $v['5'];
	            $data[$k-3]['optione'] = $v['6'];
	            $data[$k-3]['right_option'] = trim(str_replace("，",",",$v['7']));
	            $data[$k-3]['keywords'] = trim(str_replace("，",",",$v['8']));
	            $data[$k-3]['item_score'] = $v['9'];
	            $data[$k-3]['classification'] = $v['0'];
	            $data[$k-3]['analysis'] = trim($v['10']);

	            $totalscore += $v['9']; 
	        }
	    }
	    $list = array(
	        'a'=> $data,
	        'test_name'=> $list['1']['0'],
	        'totalscore'=>$totalscore
	    );
	    return $list;
	}
	
	/*
	 * 导入excel处理方法
	 * @pamar  $file  导入的文件路径 
	 * @return $data  导入后经过处理的文件数组
	 */
	function uploadExcel($file){
	    // 拆分数组拼装对应数据表的数组结构
	    $list = import_excel($file);
	    
	    $tempArr = array('题型','题目描述','A','B','C','D','E',null,null,null);

	    if($list[2] != $tempArr){
	    	return false;
	    }

     	$data = array();
     	$list['1']['0'] = $list['1']['0'];
	    foreach($list as $k=>$items){ 
	        if($k > 2 && !empty($items['0'])){
	            $data[$k-3]['title'] = $items['1'];
	            $data[$k-3]['optiona'] = $items['2'];
	            $data[$k-3]['optionb'] = $items['3'];
	            $data[$k-3]['optionc'] = $items['4'];
	            $data[$k-3]['optiond'] = $items['5'];
	            $data[$k-3]['optione'] = $items['6'];
	            $data[$k-3]['classification'] = $items['0'];
	            
	        }    
	    } 
	    $list = array(
	        'a'=> $data,
	        'b'=> $list['1']['0']
	    );
	    return $list;
	}
	
	/**
	 * 导入问卷处理
	 * $file 有效的excel文件地址
	 */
	public function importExcelSurvey($file){
		Vendor("PHPExcel.PHPExcel");
		if(!$file){
			$return = array("code" =>1020, "message"=>"请上传文件");
			return $return;
		}
		$readType = \PHPExcel_IOFactory::identify($file);  //识别文件类型
		$excelReader = \PHPExcel_IOFactory::createReader($readType);
		if($excelReader instanceof \PHPExcel_Reader_CSV){
			$excelReader->setInputEncoding('GBK');
		}
		
		$PHPExcelObj = $excelReader->load($file);
		$currentSheet = $PHPExcelObj->getSheet(0);            //选取第一张表单(Sheet1)为当前操作的表单
		$excelRows = $currentSheet->getHighestRow();          //获取最大行
		$excelColumn = $currentSheet->getHighestColumn();     //获取最大列(获取到的是字母 ABCD......)
		$excelColumnIndex = \PHPExcel_Cell::columnIndexFromString($excelColumn);//字母转为数字
		
		if($excelRows <= 1){
			$return = array("code" =>1021, "message"=>"空文件");
			return $return;
		}
		if($excelRows > 500){
			$return = array("code" =>1021, "message"=>"一次最多导入500行数据");
			return $return;
		}
		if($excelColumn > 20){
			$return = array("code" =>1022, "message"=>"文件列数不能超过20列");
			return $return;
		}
		
		//没有0行
		$rowTop = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
		$excelContent = array();
		$noDataNum = 0;
		for($i=1; $i<=$excelRows; $i++){
			$rowValue = array();
			$isNullRow = false;
			for($j=0; $j<$excelColumnIndex; $j++){
				$rowValue[$j] = $currentSheet->getCell($rowTop[$j].$i)->getValue();
				if($rowValue[$j]){
					$isNullRow = true;
				}
			}
			if(!$isNullRow){
				$noDataNum ++;
			}
			if($noDataNum > 10){
				break;
			}
			array_push($excelContent, $rowValue);
		}
		
		$return = array("code" =>1000, "message"=>"成功", "data"=>$excelContent);
		return $return;
	}
	
	/*
	 * 已通过问卷列表
	 */

	public function passQuestionNaireList($total_page = 10){
	    
	        $start_page = I("get.p",0,'int');
	    
	        if(IS_GET){
	    
		        $keyword = I("get.keyword");
		    
		        $conditions['b.survey_name'] = array("like","%$keyword%");
		        $conditions['b.status'] = 1;
		        $ret = M('SurveyCategory')->select();
		        
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "a.id,a.pid,a.sort,a.cat_name,b.survey_name,b.survey_cat_id,b.survey_heir,b.status,b.is_available,b.principal,b.survey_desc,b.orderno,b.objection,b.auth_user_id,to_char(b.survey_upload_time,'YYYY-MM-DD HH24:MI:SS') as survey_upload_time,c.username";	
				}else{
					$field = "a.*,b.*,c.username";
				}
				$result = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->join('left join __USERS__ c on b.survey_heir = c.id')
			        ->where($conditions)
			        ->page($start_page,$total_page)
			        ->field($field)
			        ->order('b.survey_upload_time desc')
			        ->select();
		        $count = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->where($conditions)->count();
	        }else{
	   
		        $ret = M('Survey_Category')->select();
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$field = "a.id,a.pid,a.sort,a.cat_name,b.survey_name,b.survey_cat_id,b.survey_heir,b.status,b.is_available,b.principal,b.survey_desc,b.orderno,b.objection,b.auth_user_id,to_char(b.survey_upload_time,'YYYY-MM-DD HH24:MI:SS') as survey_upload_time,c.username";	
				}else{
					$field = "a.*,b.*,c.username";
				}
				$result = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->join('left join __USERS__ c on b.survey_heir = c.id')
			        ->where(array('b.status'=>1))
			        ->page($start_page,$total_page)
			        ->field($field)
			        ->order('b.survey_upload_time desc')
			        ->select();
		        $count = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->where(array('b.status'=>1))->count();
	        }
	        
	        for($i=0; $i<count($result); $i++){
	        	//使用统计（项目调研  系统问卷）
	        	$project_survey = M("project_survey")->field("count(survey_id) as num")->where("survey_id=".$result[$i]["id"])->select();
	        	$research_survey = M("research")->field("count(survey_id) as num")->where("survey_id=".$result[$i]["id"])->select();
	        	$result[$i]["use_num"] = $project_survey[0]["num"] + $research_survey[0]["num"];
	        }
	        $show = $this->pageClass($count,$total_page);
		
		    $data = array(
			   "list" =>$result,
			   "page"=>$show,
		       'xhr' => $ret,
		       'keyword' => $keyword
		    );
		    return $data;
	}
	
	/*
	 * 待审核问卷列表
	 */
	
	public function auditQuestionNaireList($total_page = 10){
	     
	    $start_page = I("get.p",0,'int');
	     
	    if(IS_GET){
	         
	        $keyword = I("get.keyword");
	         
	        $conditions['a.survey_name'] = array("like","%$keyword%");
	        $conditions['a.status'] = 0;
	        $ret = M('SurveyCategory')->select();
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$field = "b.cat_name,a.survey_name,a.survey_cat_id,a.survey_heir,a.status,a.is_available,a.principal,a.survey_desc,a.orderno,a.objection,a.auth_user_id,to_char(a.survey_upload_time,'YYYY-MM-DD HH24:MI:SS') as survey_upload_time,c.username";	
			}else{
				$field = "a.*,b.cat_name,c.username";
			}
	        $result = M('survey')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY_CATEGORY__ b ON b.id = a.survey_cat_id")
			        ->join('left join __USERS__ c on a.survey_heir = c.id')
			        ->where($conditions)
			        ->page($start_page,$total_page)
			        ->field($field)
			        ->order('a.survey_upload_time desc')
			        ->select();

	        $count = M('survey')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY_CATEGORY__ b ON b.id = a.survey_cat_id")
			        ->where($conditions)->count();
	         
	    }else{
	        $ret = M('Survey_Category')->select();
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$field = "b.cat_name,a.survey_name,a.survey_cat_id,a.survey_heir,a.status,a.is_available,a.principal,a.survey_desc,a.orderno,a.objection,a.auth_user_id,to_char(a.survey_upload_time,'YYYY-MM-DD HH24:MI:SS') as survey_upload_time,c.username";	
			}else{
				$field = "a.*,b.cat_name,c.username";
			}
	        $result = M('survey')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY_CATEGORY__ b ON b.id = a.survey_cat_id")
			        ->join('left join __USERS__ c on a.survey_heir = c.id')
			        ->where(array('a.status'=>0))
			        ->page($start_page,$total_page)
			        ->field($field)
			        ->order('a.survey_upload_time desc')
			        ->select();
	        $count = M('survey')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY_CATEGORY__ b ON b.id = a.survey_cat_id")
			        ->where(array('b.status'=>0))->count();
	    }
	    
	    $show = $this->pageClass($count,$total_page);
	
	    $data = array(
	        "list" =>$result,
	        "page"=>$show,
	        'xhr' => $ret,
	        'keyword' => $keyword
	    );
	    return $data;
	}
	
	/*
	 * 已拒绝的问卷列表
	 */
	
	public function refusedQuestionNaireList($total_page = 10){
	
	    $start_page = I("get.p",0,'int');
	
	    if(IS_GET){
	
	        $keyword = I("get.keyword");
	
	        $conditions['b.survey_name'] = array("like","%$keyword%");
	        $conditions['b.status'] = 3;
	        $ret = M('SurveyCategory')->select();
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$field = "a.cat_name,b.survey_name,b.survey_cat_id,b.survey_heir,b.status,b.is_available,b.principal,b.survey_desc,b.orderno,b.objection,b.auth_user_id,to_char(b.survey_upload_time,'YYYY-MM-DD HH24:MI:SS') as survey_upload_time,c.username";	
			}else{
				$field = "a.*,b.*,c.username";
			}
	        $result = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->join('left join __USERS__ c on b.survey_heir = c.id')
			        ->where($conditions)
			        ->page($start_page,$total_page)
			        ->field($field)
			        ->order('b.survey_upload_time desc')
			        ->select();
	        $count = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->where($conditions)->count();
	
	    }else{
	
	        $ret = M('Survey_Category')->select();
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$field = "a.cat_name,b.survey_name,b.survey_cat_id,b.survey_heir,b.status,b.is_available,b.principal,b.survey_desc,b.orderno,b.objection,b.auth_user_id,to_char(b.survey_upload_time,'YYYY-MM-DD HH24:MI:SS') as survey_upload_time,c.username";	
			}else{
				$field = "a.*,b.*,c.username";
			}
	        $result = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->join('left join __USERS__ c on b.survey_heir = c.id')
			        ->where(array('b.status'=>3))
			        ->page($start_page,$total_page)
			        ->field($field)
			        ->order('b.survey_upload_time desc')
			        ->select();
	        $count = M('SurveyCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __SURVEY__ b ON a.id = b.survey_cat_id")
			        ->where(array('b.status'=>3))->count();
	    }
	    
	    $show = $this->pageClass($count,$total_page);
	
	    $data = array(
	        "list" =>$result,
	        "page"=>$show,
	        'xhr' => $ret,
	        'keyword' => $keyword
	    );
	    return $data;
	}

	/*
	 * 问卷详情
	 */
	public function getQuestionNaireDetail($id){
	    //试卷详情
	    $questionNaire = M('Survey');
	    $ret = $questionNaire
	    		->alias('a')
	    		->join('LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id')
	    		->join('left join __USERS__ c on a.survey_heir=c.id')
	    		->where("a.id = $id")
	    		->field("a.*,b.cat_name,c.username")
	    		->find();

	    $list = M("survey_item")->where("survey_id=".$id)->order("id asc")->select();
	    return array("base"=>$ret, "list"=>$list);
	    
	    /*
	    //试卷单选题题目
	    $singleChoice = M("Survey_item")->alias('a')->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")->where("a.classification=1 AND b.id =$id")->field("a.*")->select();
	    $singleChoiceTotalScore = M('Survey_item')->alias('a')->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")->where("a.classification=1 AND b.id =$id")->field("a.*")->sum('survey_score');
	    $singleChoiceSum = count($singleChoice);
	    
	    //试卷多选题题目
	    $multipleChoice = M('Survey_item')->alias('a')->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")->where("a.classification=2 AND b.id =$id")->field("a.*")->select();
	    $multipleChoiceTotalScore = M('Survey_item')->alias('a')->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")->where("a.classification=2 AND b.id =$id")->field("a.*")->sum('survey_score');
	    $multipleChoiceSum = count($multipleChoice);
	   
	    //试卷判断题题目
	    $descriPtive = M('Survey_item')->alias('a')->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")->where("a.classification=3 AND b.id =$id")->field("a.*")->select();
	    $descriPtiveChoiceTotalScore = M('Survey_item')->alias('a')->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")->where("a.classification=3 AND b.id =$id")->field("a.*")->sum('survey_score');
	    $descriPtiveChoiceSum = count($descriPtive);
	    return $data = array(
	        //详情
	        "detail" => $ret,
	        //单选
	        "singleChoice" => $singleChoice,
	        "singleChoiceSum" => $singleChoiceSum,
	        "singleChoiceTotalScore" => $singleChoiceTotalScore,
	        //多选
	        "multipleChoice" => $multipleChoice,
	        "multipleChoiceSum" => $multipleChoiceSum,
	        "multipleChoiceTotalScore" => $multipleChoiceTotalScore,
	        //判断
	        "descriPtive" => $descriPtive,
	        "descriPtiveChoiceSum" => $descriPtiveChoiceSum,
	        "descriPtiveChoiceTotalScore" => $descriPtiveChoiceTotalScore
	    );
	    */
	 }
	     

	
	/*
	 * 问卷单选题目详情
	 */
	public function getSingleChoice($id){
	    $data = M('Survey')->alias('a')->join('LEFT JOIN think_survey_item b ON a.id = b.survey_id AND b.classification = 1 AND b.survey_id ='.$id)->where('b.id>0') ->field('b.*')->select();
	    return $data;
	    
	}
	
	/*
	 * 问卷多选题目详情
	 */
	public function getMultipleChoice($id){
	    
	    $list = M('Survey')->alias('a')->join('LEFT JOIN think_survey_item b ON a.id = b.survey_id AND b.classification = 2 AND b.survey_id ='.$id) ->where('b.id>0')->field('b.*')->select();
	    return $list; 
	}
	
	/*
	 * 问卷描述题目详情
	 */
	public function getDescriptive($id){
	    $res = M('SurveyItem')->alias('a')->join('LEFT JOIN think_survey_item b ON a.id = b.survey_id AND b.classification = 3 AND b.survey_id ='.$id) ->where('b.id>0')->field('b.*')->select();
	     return $res;
	}
	
	/*
	 * 已经通过的试卷列表
	 */
	public function passExamList($total_page = 10){
	    
        $start_page = I("get.p",0,'int');
		$total_page = 10;
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.test_name,a.test_cat_id,a.test_score,a.status,a.is_available,a.test_mode,a.type,a.auth_user_id,b.id as cateid,b.cat_name,to_char(a.test_upload_time,'YYYY-MM-DD HH24:MI:SS') as test_upload_time";
		}else{
			$field = "a.id,a.test_name,a.test_cat_id,a.test_score,a.status,a.is_available,a.test_mode,a.type,a.auth_user_id,b.id as cateid,b.cat_name,a.test_upload_time";
		}
        if(IS_GET){
    
	        $keyword = I("get.keyword");
	    	$cate = I('get.test_cate');
	    	$heir = I('get.test_heir');
	    	if($cate != -1 && $cate != ''){
	        	$conditions['test_cat_id'] = $cate;
	    	}
	    	if($keyword){
	        	$conditions['a.test_name'] = array("like","%$keyword%");
	        }
	        if($heir){
	        	$conditions['a.test_heir'] = array("like","%$heir%");
	        }

		        $conditions['a.status'] = 1;
		        $ret = M('examination')->select();
				
				$specifiedUser = D('IsolationData')->specifiedUser();
				$conditions['a.auth_user_id'] = array('in',$specifiedUser);
				
				if(!empty($keyword)){
	        		$result = M('examination')
				        ->alias('a')
				        ->join("LEFT JOIN think_examination_category b ON b.id = a.test_cat_id")
				        ->where($conditions)
				        ->field($field)
				        ->order('a.test_upload_time desc')
				        ->select();
        	}else{
        		$result = M('examination')
			        ->alias('a')
			        ->join("LEFT JOIN think_examination_category b ON b.id = a.test_cat_id")
			        ->where($conditions)
			        ->field($field)
			        ->page($start_page,$total_page)
			        ->order('a.test_upload_time desc')
			        ->select();
        	}
	        
	        $count = M('examination')
			        ->alias('a')
			        ->join("LEFT JOIN think_examination_category b ON b.id = a.test_cat_id")
			        ->where($conditions)
			        ->count();
        }else{
   
	        $ret = M('ExaminationCategory')->select();
			
			$specifiedUser = D('IsolationData')->specifiedUser();
			
			if(!empty($keyword)){
        		$result = M('examination')
			        ->alias('a')
			        ->join("LEFT JOIN think_examination_category b ON b.id = a.test_cat_id")
			        ->where(array('a.status'=>1,'a.auth_user_id'=>array('in',$specifiedUser)))
			        ->field($field)
			        ->order('a.test_upload_time desc')
			        ->select();
        	}else{
        		$result = M('examination')
			        ->alias('a')
			        ->join("LEFT JOIN think_examination_category b ON b.id = a.test_cat_id")
			        ->where(array('a.status'=>1,'a.auth_user_id'=>array('in',$specifiedUser)))
			        ->field($field)
			        ->page($start_page,$total_page)
			        ->order('a.test_upload_time desc')
			        ->select();
        	}
	        
			
	        $count = M('examination')
			        ->alias('a')
			        ->join("LEFT JOIN think_examination_category b ON b.id = a.test_cat_id")
			        ->where(array('a.status'=>1,'a.auth_user_id'=>array('in',$specifiedUser)))
			        ->count();
        }
        
		$result = D('IsolationData')->isolationData($result);
//			dump($result);
	        $show = $this->pageClass($count,$total_page);
		
		    $data = array(
			   "list" =>$result,
			   "page"=>$show,
		       'xhr' => $ret,
		       'keyword' => $keyword,
		       'cate'=>$cate,
		       'heir'=>$heir
		    );
		    return $data;
	}
	
	/*
	 * 待审核的试卷列表
	 */
	public function auditExamList($total_page = 10){
	     
	    $start_page = I("get.p",0,'int');
	    if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "b.id,b.test_name,b.test_cat_id,b.test_score,b.status,b.is_available,b.test_mode,b.type,b.auth_user_id,a.id as cateid,a.cat_name,to_char(b.test_upload_time,'YYYY-MM-DD HH24:MI:SS') as test_upload_time";
		}else{
			$field = "b.id,b.test_name,b.test_cat_id,b.test_score,b.status,b.is_available,b.test_mode,b.type,b.auth_user_id,a.id as cateid,a.cat_name,b.test_upload_time";
		}
	    if(IS_GET){
	         
	        $keyword = I("get.keyword");
	         
	        $conditions['test_name'] = array("like","%$keyword%");
	        $conditions['status'] = 0;
	        $ret = M('ExaminationCategory')->select();
			
			$specifiedUser = D('IsolationData')->specifiedUser();
			$conditions['b.auth_user_id'] = array('in',$specifiedUser);
        	
        	if(!empty($keyword)){
        		$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where($conditions)
					->field($field)
			        ->order('b.test_upload_time desc')
			        ->select();
        	}else{
        		$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where($conditions)
					->field($field)
			        ->order('b.test_upload_time desc')
			        ->page($start_page,$total_page)
			        ->select();
        	}
	        
	        $count = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where($conditions)
			        ->count();
	    }else{
	
	        $ret = M('ExaminationCategory')->select();
			
			$specifiedUser = D('IsolationData')->specifiedUser();
			
			if(!empty($keyword)){
				$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where(array('status'=>0,'b.auth_user_id'=>array('in',$specifiedUser)))
					->field($field)
			        ->order('b.test_upload_time desc')
			        ->select();
			}else{
				$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where(array('status'=>0,'b.auth_user_id'=>array('in',$specifiedUser)))
					->field($field)
			        ->order('b.test_upload_time desc')
			        ->page($start_page,$total_page)
			        ->select();
			}
	        
	        $count = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where(array('status'=>0,'b.auth_user_id'=>array('in',$specifiedUser)))
			        ->count();
	    }
			
		$result = D('IsolationData')->isolationData($result);
		/*if(strtolower(C('DB_TYPE')) == 'oracle'){
			foreach($result as $k=>$v){
				$result[$k]['test_upload_time'] = date('Y-m-d H:i:s',strtotime($v['test_upload_time']));
			}
		}*/
		
	    $show = $this->pageClass($count,$total_page);
	
	    $data = array(
	        "list" =>$result,
	        "page"=>$show,
	        'xhr' => $ret,
	        'keyword' => $keyword
	    );
	    return $data;
	}
	
	
	/*
	 * 已拒绝的试卷列表
	 */
	public function refusedExamList($total_page = 10){
	
	    $start_page = I("get.p",0,'int');
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "b.id,b.test_name,b.test_cat_id,b.test_score,b.status,b.is_available,b.test_mode,b.type,b.auth_user_id,a.id as cateid,a.cat_name,to_char(b.test_upload_time,'YYYY-MM-DD HH24:MI:SS') as test_upload_time";
		}else{
			$field = "b.id,b.test_name,b.test_cat_id,b.test_score,b.status,b.is_available,b.test_mode,b.type,b.auth_user_id,a.id as cateid,a.cat_name,b.test_upload_time";
		}
	    if(IS_GET){
	
	        $keyword = I("get.keyword");
	
	        $conditions['test_name'] = array("like","%$keyword%");
	        $conditions['status'] = 3;
	        $ret = M('ExaminationCategory')->select();
			
			$specifiedUser = D('IsolationData')->specifiedUser();
			$conditions['b.auth_user_id'] = array('in',$specifiedUser);
			
			if(!empty($keyword)){
				$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
					->field($field)
			        ->where($conditions)
			        ->select();
			}else{
				$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
					->field($field)
			        ->where($conditions)
			        ->page($start_page,$total_page)
			        ->select();
			}
	        
	        $count = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where($conditions)
			        ->count();
	    }else{
	
	        $ret = M('ExaminationCategory')->select();
			
			$specifiedUser = D('IsolationData')->specifiedUser();
			
			if(!empty($keyword)){
				$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where(array('status'=>3,'b.auth_user_id'=>array('in',$specifiedUser)))
					->field($field)
			        ->select();
			}else{
				$result = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where(array('status'=>3,'b.auth_user_id'=>array('in',$specifiedUser)))
					->field($field)
			        ->page($start_page,$total_page)
			        ->select();
			}
	        
	        $count = M('ExaminationCategory')
			        ->alias('a')
			        ->join("LEFT JOIN __EXAMINATION__ b ON a.id = b.test_cat_id")
			        ->where(array('status'=>3,'b.auth_user_id'=>array('in',$specifiedUser)))
			        ->count();
	    }
			
		$result = D('IsolationData')->isolationData($result);
		
	    $show = $this->pageClass($count,$total_page);
	
	    $data = array(
	        "list" =>$result,
	        "page"=>$show,
	        'xhr' => $ret,
	        'keyword' => $keyword
	    );
	    return $data;
	}

	/**
	 * 试卷详情-2期
	 * @param $id  试卷id
	 * @param $user_id 用户ID
	 * @param $test_id 考试ID
	 * @param $project_id 项目ID
	 * date:20170222
	 */
	public function getExamDetail2($id,$user_id,$test_id=false,$project_id=false){
		$rightNum = 0;
		$exam = M('Examination');
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "a.id,a.test_name,a.test_cat_id,a.test_score,a.status,a.is_available,a.test_mode,a.type,a.auth_user_id,to_char(a.test_upload_time,'YYYY-MM-DD HH24:MI:SS') as test_upload_time";
		}else{
			$field = "a.*";
		}
		$ret = $exam
			->alias('a')
			->join('LEFT JOIN __EXAMINATION_CATEGORY__ b ON a.test_cat_id = b.id WHERE a.id ='.$id) 
			->field($field.',b.cat_name')
			->find();
		$type = M('examination')->where(array('id'=>$id))->getField('s_type');
		
		$where['a.examination_id'] = $id;
		if($type == 1){
			$where['a.user_id'] = $user_id;
			if($test_id){
				$where['a.test_id'] = $test_id;
			}else if($project_id){
				$where['a.project_id'] = $project_id;
			}
		}
		
		//单选
		$singleChoice = M('Examination_item_rel')->alias('a')
				->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
				->where(array_merge($where,array('b.classification'=>1)))
				->select();
	   	if($singleChoice){
	   		$singleChoiceSum = count($singleChoice);
	   		$singleChoiceTotalScore = $singleChoiceSum * $singleChoice[0]['score'];
	   	}else{
	   		$singleChoiceSum = $singleChoiceTotalScore = 0;
	   	}
		
		//多选题
		$multipleChoice = M('Examination_item_rel')->alias('a')
				->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
				->where(array_merge($where,array('b.classification'=>2)))
				->select();
	   	if($multipleChoice){
	   		$multipleChoiceSum = count($multipleChoice);
	   		$multipleChoiceTotalScore = $multipleChoiceSum * $multipleChoice[0]['score'];
	   	}else{
	   		$multipleChoiceSum = $multipleChoiceTotalScore = 0;
	   	}
	   	
		//判断
		$descriPtive = M('Examination_item_rel')->alias('a')
	   					->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
	   					->where(array_merge($where,array('b.classification'=>3)))
	   					->select();
	   	if($descriPtive){
	   		$descriPtiveChoiceSum = count($descriPtive);
	   		$descriPtiveChoiceTotalScore = $descriPtiveChoiceSum * $descriPtive[0]['score'];
	   	}else{
	   		$descriPtiveChoiceSum = $descriPtiveChoiceTotalScore = 0;
	   	}

	   	//问答
	   	$wd = M('Examination_item_rel')->alias('a')
	   					->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_item_id = b.id')
	   					->where(array_merge($where,array('b.classification'=>4)))
	   					->select();
	   	if($wd){
	   		$wdSum = count($wd);
	   		$wdTotalScore = $wdSum * $wd[0]['score'];
	   	}else{
	   		$wdSum = $wdTotalScore = 0;
	   	}

	   	return $data = array(
	       //详情
	       "detail" => $ret,
	       //单选
	       "singleChoice" => $singleChoice,
	       "singleChoiceSum" => $singleChoiceSum,
	       "singleChoiceTotalScore" => $singleChoiceTotalScore,
	       //多选
	       "multipleChoice" => $multipleChoice,
	       "multipleChoiceSum" => $multipleChoiceSum,
	       "multipleChoiceTotalScore" => $multipleChoiceTotalScore,
	       //判断
	       "descriPtive" => $descriPtive,
	       "descriPtiveChoiceSum" => $descriPtiveChoiceSum,
	       "descriPtiveChoiceTotalScore" => $descriPtiveChoiceTotalScore,
	       //问答
	       'wd'=>$wd,
	       'wdTotalScore'=>$wdTotalScore,
	       'wdSum'=>$wdSum
	   );
	}

	/**
	 * 获取考试分类
	 * @return [type] [description]
	 */
	public function getExamCate($where){
		return M('examination_category')->where($where)->select();
	}

	/**
	 * 获取试题库各类型试题数量
	 * @param  [type] $type [试题类型//单选 多选 判断 问答]
	 * @return [type]       [description]
	 */
	public function getExamNum($type){
		$specifiedUser = D('IsolationData')->specifiedUser();
		$conditions['auth_user_id'] = array('in',$specifiedUser);
		
		$question_bank = M("question_bank")->where($conditions)->getField("id",true);
		
		$db = M('examination_item');
		$where["question_bank_id"] = array("in", $question_bank);
		if($type){
			$where["classification"] = $type;
			$res = $db->where($where)->count();
		}else{
			for($i = 1;$i<=4;$i++){
				$where["classification"] = $i;
				$res[] = $db->where($where)->count();
			}
		}
		return $res;
	}


	/**
	 * 预览试卷-随机获取题库中的考题
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function randomExam($data){
		$db = M('examination_item');
		if($data['dan']){
			$finalDanIds = $this->randIds($data['dan'],1);
		}
		if($data['duo']){
			$finalDuoIds = $this->randIds($data['duo'],2);
		}
		if($data['pan']){
			$finalPanIds = $this->randIds($data['pan'],3);
		}
		if($data['jian']){
			$finalJianIds = $this->randIds($data['jian'],4);
		}

		return array(
			//试题列表
			'dan'=>$finalDanIds,
			'duo'=>$finalDuoIds,
			'pan'=>$finalPanIds,
			'jian'=>$finalJianIds,
			//试卷总分-总题数目
			'totalExam'=>$data['totalExam'],
			'totalScore'=>$data['totalScore']
		);
	}

	/**
	 * 获取试题处理
	 * @param  [type] $num  [要获取的试题数目]
	 * @param  [type] $type [要获取的试题类型]
	 * @return [type]       [试题+试题id集合]
	 */
	public function randIds($num,$type){
		$db = M('examination_item');
		$ids = $db->where(array('classification'=>$type))->select();
		foreach($ids as $k=>$v){
			$s['info'][] = $v;
			$s['ids'][] = $v['id'];
		}
		$ss = array_rand($s['info'],$num);
		if(is_array($ss)){
			foreach($ss as $k=>$v){
				$final['info'][] = $ids[$v];
				$final['ids'][] = $ids[$v]['id'];
			}
		}else{
			//只取出来一个
			$final['info'][] = $ids[$ss];
			$final['ids'][] = $ids[$ss]['id'];
		}
		return $final;
	}

	/**
	 * 考试表和关联表添加数据
	 * @param [type] $data [description]
	 */
	public function addExamination($data){
		$db1 = M('examination');
		$db2 = M('examination_item_rel');
		$orderno =  D('Trigger')->orderNumber(3);

		$insertData['test_name'] = $data['examname'];
		$insertData['test_heir'] = session('user.username');
		$insertData['test_cat_id'] = $data['examcate'];
		$insertData['test_score'] = $data['totalScore'];
		$insertData['test_upload_time'] = date('Y-m-d H:i:s');
		$insertData['status'] = 0;	//待审核
		$insertData['is_available'] = 1;	//待审核
		$insertData['principal'] = $_SESSION['user']['id'];		//负责人
		$insertData['orderno'] = $orderno;		//工单号
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$insertData['id'] = getNextId('examination');
			$insertData['test_upload_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}
		
		$id = $db1->add($insertData);
		if($id){//关联表插入数据
			if($data['danids']){
				$relTableData = array('examination_id'=>$id,'score'=>$data['dan-fen']);
				$r1 = $this->testRelTableHandle($data['danids'],$relTableData);
				if(!$r1){
					return false;
				}
			}
			if($data['duoids']){
				$relTableData = array('examination_id'=>$id,'score'=>$data['duo-fen']);
				$r2 = $this->testRelTableHandle($data['duoids'],$relTableData);
				if(!$r2){
					return false;
				}
			}
			if($data['panids']){
				$relTableData = array('examination_id'=>$id,'score'=>$data['pan-fen']);
				$r3 = $this->testRelTableHandle($data['panids'],$relTableData);
				if(!$r3){
					return false;
				}
			}
			if($data['jianids']){
				$relTableData = array('examination_id'=>$id,'score'=>$data['jian-fen']);
				$r4 = $this->testRelTableHandle($data['jianids'],$relTableData);
				if(!$r4){
					return false;
				}
			}
			return true;
		}else{
			return false;
		}
	}

	/**
	 * [testRelTableHandle description]
	 * @param  [type] $data       [循环数组]
	 * @param  [type] $insertData [要插入的表数据]
	 * @return [type]             [description]
	 */
	public function testRelTableHandle($data,$insertData){
		$db = M('examination_item_rel');
		foreach($data as $k=>$v){
			$insertData['examination_item_id'] = $v;
			$res = $db->add($insertData);
			if(!$res){
				return false;
			}
		}
		return true;
	}

	/**
	 * 试题列表
	 * @param  integer $total_page [description]
	 * @return [type]              [description]
	 */
	public function examList(){
		//试题库ID
		$id = I('get.id');
		$total_page = 10;
		
		$start_page = I("get.p",0,'int');
	    
        $course = I("get.course");
    	$title = I('get.title');
    	$type = I('get.type');
    	if($type){
        	$cond['a.classification'] = $type;
    	}

    	if($course){
        	$cond['b.course_name'] = array("like","%$course%");
        }
        if($title){
        	$cond['a.title'] = array("like","%$title%");
        }
		
		$cond['a.question_bank_id'] = $id;
        $res = M('examination_item')
        		->alias('a')
        		->join('LEFT JOIN __COURSE__ b on a.belongcourse = b.id')
				->join('left join __QUESTION_BANK__ c on a.question_bank_id=c.id')
        		->field('b.course_name,a.*,c.name,c.auth_user_id')
        		->page($start_page,$total_page)
        		->order('a.ctime desc')
        		->where($cond)
        		->select();
        $count = M('examination_item')
        		->alias('a')
        		->join('LEFT JOIN __COURSE__ b on a.belongcourse = b.id')
				->join('left join __QUESTION_BANK__ c on a.question_bank_id=c.id')
        		->where($cond)
        		->count();
        $res = D('IsolationData')->isolationData($res);
        $show = $this->pageClass($count,$total_page);
		
	    $data = array(
		   "list" =>$res,
		   "page"=>$show,
	       'title' => $title,
	       'type'=>$type,
	       'course'=>$course
	    );
	    return $data;
	}

	/**
	 * 获取试题信息
	 * @param  [type] $id [description]
	 * @return [type]     [description]
	 */
	public function examDetails($id){
		return M('examination_item')
				->alias('a')
				->join('LEFT JOIN think_course b on a.belongcourse = b.id')
				->where(array('a.id'=>$id))
				->field('a.*,b.course_name')
				->find();
	}

	/**
	 * 添加试题
	 * @param [type] $data [description]
	 */
	public function addQuestion($data){
		return M('examination_item')->add($data);
	}
	
	//试题是否存在
	public function issetExamination($data){
		return M('examination_item')->where(array('title'=>$data['title']))->find();
	}

	/*
	 * 导入试题处理方法
	 * @pamar  $file  导入的文件路径
	 * @return $data  导入后经过处理的文件数组
	 */
	function uploadQuestion($file){
	    // 拆分数组拼装对应数据表的数组结构
	    $list = import_excel($file);
		
		if(strpos($list[1][0],'请按照字段逐一填写各项内容') === false){
			return false;
		}

	    $data = array();
	    foreach($list as $k=>$v){
	        if($k > 2 && !empty($v['0'])){
	            $data[$k-3]['title'] = $v['1'];
	            $data[$k-3]['optiona'] = $v['2'];
	            $data[$k-3]['optionb'] = $v['3'];
	            $data[$k-3]['optionc'] = $v['4'];
	            $data[$k-3]['optiond'] = $v['5'];
	            $data[$k-3]['optione'] = $v['6'];
	            $data[$k-3]['right_option'] = trim(str_replace("，",",",$v['7']));
	            $data[$k-3]['keywords'] = trim(str_replace("，",",",$v['8']));
	            $data[$k-3]['classification'] = $v['0'];
	            $data[$k-3]['analysis'] = $v['9'];
	        }
	    }
	    return $data;
	}

	/**
	 * 获取试卷详细信息
	 */
	public function getExamInfo($id,$total_page=10){
		$start_page = I("get.p",1,'int');
		$tissue_name = I('get.tissue_name');
		$username = I('get.username');
		
		if($tissue_name){
			$where['g.name'] = array('like',"%$tissue_name%");
		}
		if($username){
			$where['e.username'] = array('like',"%$username%");
		}

		$where['a.test_id'] = $id;

		//项目考试表
		$data = M('project_examination')
				->alias('a')
				->join('left join __EXAMINATION__ b on a.test_id=b.id')
				->join('left join __ADMIN_PROJECT__ c on a.project_id=c.id')
				->join('left join __DESIGNATED_PERSONNEL__ d on a.project_id=d.project_id')
				->join('left join __USERS__ e on d.user_id=e.id')
				->join('left join __TISSUE_GROUP_ACCESS__ f on e.id=f.user_id')
				->join('left join __TISSUE_RULE__ g on f.tissue_id=g.id')
				->join('left join __EXAM_SCORE__ h on h.project_id=a.project_id and h.exam_id=a.test_id and d.user_id=h.user_id')
				->where($where)
				->distinct(true)
				// ->page($start_page.','.$total_page)
				->field('a.test_names,b.test_name,b.test_score,b.status,b.passing_score,b.id as eid,c.project_name,c.id as pid,e.username,e.id as user_id,g.name,h.total_score')
				->select();
		
		//考试表
		$data2= M('test_user_rel a')
				->join('left join __TEST__ b on a.test_id=b.id')
				->join('left join __USERS__ c on a.user_id=c.id')
				->join('left join __TISSUE_GROUP_ACCESS__ d on c.id=d.user_id')
				->join('left join __TISSUE_RULE__ e on d.tissue_id=e.id')
				->join('left join __EXAM_SCORE__ f on f.user_id=a.user_id and f.exam_id=b.examination_id and f.test_id=b.id')
				->join('left join __EXAMINATION__ h on b.examination_id=h.id')
				->where(array('b.examination_id'=>$id))
				->distinct(true)
				->field('b.id as tid,c.username,c.id as user_id,e.name,f.total_score,h.test_score,h.passing_score,h.id as eid')
				->select();

		$newArray = array_merge($data,$data2);

		$searchArr = array();
		
		foreach($newArray as $k=>$v){
			if(stripos($v['username'],$username) !== false){
				$search[] = $newArray[$k];
			}
			if(stripos($v['name'],$tissue_name) !== false){
				$search[] = $newArray[$k];
			}
		}

		$counts = count($newArray);
		foreach($newArray as $k=>$v){
			if( (int)$v['total_score'] >= (int)$v['passing_score'] ){
				$num++;
			}
		}
		$passingRate = (number_format($num / $counts ,2)) * 100;
		
		$show = $this->pageClass($counts,$total_page);
		if(empty($search)){
			$list = array_slice($newArray,($start_page - 1) * $total_page,$total_page);
		}else{
			$list = array_slice($search,($start_page - 1) * $total_page,$total_page);
		}
		
		$num = 0;	//及格次数
		foreach($list as $k=>$v){
			if( (int)$v['total_score'] >= (int)$v['passing_score'] ){
				$list[$k]['ispass'] = 1;
				$num++;
			}
			$list[$k]['data_tiem']=$this->getTestTime($v['user_id'],$v['eid'],$v['pid'],$v['tid']);
		}

		return array(
			'list'=>$list,
			'page'=>$show,
			'num'=>$counts,
			'passingRate'=>$passingRate,
			'tissue_name'=>$tissue_name,
			'username'=>$username
		);
	}

	/**
	 * 获取考试完成时间
	 */
	public function getTestTime($user_id,$eid,$pid=false,$tid=false){
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$field = "to_char(data_tiem,'YYYY-MM-DD HH24:MI:SS') as data_tiem";
		}else{
			$field = "data_tiem";
		}
		if($pid){
			return M('exam_answer')
					->where(array('exam_id'=>$eid,'project_id'=>$pid,'u_exam_id'=>$user_id))
					->order('data_tiem desc')
					->limit(1)
					->getField($field);
		}else{
			return M('exam_answer')
					->where(array('test_id'=>$tid,'u_exam_id'=>$user_id,'exam_id'=>$eid))
					->order('data_tiem desc')
					->limit(1)
					->getField($field);
		}
	}

	/**
	 * 获取问卷详细信息
	 */
	public function getQUestionNaireInfo($id,$total_page=10){
		$start_page = I("get.p",1,'int');
		$tissue_name = I('get.tissue_name');
		$username = I('get.username');
		if($tissue_name){
			$where['g.name'] = array('like',"%$tissue_name%");
		}
		if($username){
			$where['e.username'] = array('like',"%$username%");
		}

		$where['a.survey_id'] = $id;
		//项目调研
		$data = M('project_survey')
				->alias('a')
				->join('left join __SURVEY__ b on a.survey_id=b.id')
				->join('left join __ADMIN_PROJECT__ c on a.project_id=c.id')
				->join('left join __DESIGNATED_PERSONNEL__ d on a.project_id=d.project_id')
				->join('left join __USERS__ e on d.user_id=e.id')
				->join('left join __TISSUE_GROUP_ACCESS__ f on e.id=f.user_id')
				->join('left join __TISSUE_RULE__ g on f.tissue_id=g.id')
				->join('left join __SURVEY_ATTENDANCE__ h on h.user_id=d.user_id and h.project_id=a.project_id and h.survey_id=a.survey_id')
				->where($where)
				// ->page($start_page.','.$total_page)
				->field('a.survey_names,b.survey_name,b.status,c.project_name,d.user_id,e.username,g.name,h.commit_time')
				->select();
		foreach($data as $k=>$v){
			if(!$v['username']){
				unset($data[$k]);
			}
		}
		$ids= M('research a')
				->join('left join __SURVEY__ b on a.survey_id=b.id')
				->where(array('a.survey_id'=>$id))
				->field('a.id,a.survey_id')
				->select();

		$data2 = array();
		foreach($ids as $k=>$v){
			$users = $this->getUsersByTissueId($v['id']);
			if($users){
				foreach($users as $kk=>$vv){
					$userinfo = M('users a')
								->join('left join __TISSUE_GROUP_ACCESS__ b on a.id=b.user_id')
								->join('left join __TISSUE_RULE__ c on b.tissue_id=c.id')
								->where(array('a.id'=>$vv['user_id']))
								->field('a.username,a.id,c.name')
								->find();
					if(!$userinfo['id']){
						continue;
					}
					$temp['username'] = $userinfo['username'];
					$temp['name'] = $userinfo['name'];
					$temp['survey_id'] = $v['survey_id'];
					$temp['user_id'] = $userinfo['id'];

					$data2[] = $temp;
				}
			}
		}
		foreach($data2 as $k=>$v){
			$end_time = M('research_attendance')
						->where(array('research_id'=>$v['survey_id'],'user_id'=>$v['user_id']))
						->getField('commit_time');
			$data2[$k]['commit_time'] = $end_time;
		}

		$newArray = array_merge($data,$data2);
		$searchArr = array();
		
		foreach($newArray as $k=>$v){
			if(stripos($v['username'],$username) !== false){
				$search[] = $newArray[$k];
			}
			if(stripos($v['name'],$tissue_name) !== false){
				$search[] = $newArray[$k];
			}
		}

		$counts = count($newArray);

		$show = $this->pageClass($counts,$total_page);
		if(empty($search)){
			$list = array_slice($newArray,($start_page - 1) * $total_page,$total_page);
		}else{
			$list = array_slice($search,($start_page - 1) * $total_page,$total_page);
		}
	
		return array(
			'list'=>$list,
			'page'=>$show,
			'num'=>$counts,
			'tissue_name'=>$tissue_name,
			'username'=>$username
		);
	}

	/**
	 * 获取试卷状态
	 */
	public function getExaminationStatus($id){
		return M('examination')->where(array('id'=>$id))->getField('status');
	}

	public function getSurveyStatus($id){
		return M('survey')->where(array('id'=>$id))->getField('status');
	}

	/**
	 * 获取某个组织下的所有员工
	 */
	public function getUsersByTissueId($tissue_id){
		return M('tissue_group_access')->where(array('tissue_id'=>$tissue_id))->field('user_id')->select();
	}

	/**
	 * 分类管理
	 */
	public function classManagement(){

		$survey_category_all = M('survey_category')->field('id,cat_name')->select();

		return $survey_category_all;
	}

	/**
	 * 试卷分类管理
	 */
	public function testClassManagement(){

		$data = M('examination_category')->field('id,cat_name')->select();

		return $data;
	}

	/**
	 * 新增问卷分类
	 */
	public function addClass(){

		$list = I('post.list');

		try {

			$DB = M('survey_category');

			foreach($list as $val){

				if(empty($val[0])){
					$data = array(
						"cat_name"=>$val[1]
					);
					if(!empty($val[1])){
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$data['id'] = getNextId('survey_category');
						}
						$increment_id = $DB->data($data)->add();
					}
				}else{
					$increment_id = $DB->where('id='.$val[0])->setField("cat_name",$val[1]);
				}
			}

			if($increment_id){
				$DB->commit();
			}

		} catch ( Exception $e ) {

			$DB->rollback();

		}

		return $increment_id;
	}

	/**
	 * 删除问卷
	 */
	public function delCategory(){

		$id = I('post.id');

		$is_category = M('survey')->where("survey_cat_id=".$id)->find();

		if(empty($is_category)){

			try {

				$DB = M('survey_category');

				$increment_id = $DB->where("id=".$id)->delete();

				if($increment_id){
					$DB->commit();
				}

			} catch ( Exception $e ) {

				$DB->rollback();

			}

		}else{

			$increment_id = false;

		}



		return $increment_id;

	}

	//单个删除问卷
	public function delSurvey($survey_id){
		M("survey")->where("id=".$survey_id)->limit(1)->delete();
		M("survey_item")->where("survey_id=".$survey_id)->delete();
		return array("code"=>1000, "message"=>"成功");
	}

	/**
	 * 新增试卷分类
	 */
	public function addTestClass(){

		$list = I('post.classify');

		try {
			$DB = M('examination_category');
			foreach($list as $val){
				$v = trim($val[1]);
				if(empty($val[0])){
					$data = array(
						"cat_name"=>$v
					);
					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$data['id'] = getNextId('examination_category');
					}
					if(!empty($v)){
						$increment_id = $DB->data($data)->add();
					}
				}else{
					$increment_id = $DB->where('id='.$val[0])->setField("cat_name",$v);
				}
			}

			if($increment_id){
				$DB->commit();
			}

		} catch ( Exception $e ) {
			$DB->rollback();
		}
	}

	/**
	 * 删除试卷分类
	 */
	public function delTestCategory(){
		$id = I('post.id');
		$is_category = M('examination')->where("test_cat_id=".$id)->find();
		if(empty($is_category)){
			try {
				$DB = M('examination_category');
				$increment_id = $DB->where("id=".$id)->delete();
				if($increment_id){
					$DB->commit();
				}
			} catch ( Exception $e ) {
				$DB->rollback();
			}
		}else{
			$increment_id = false;
		}
		return $increment_id;
	}
	
	//根据课程分类ID获取试题数量
	public function getExamNumBy(){
		$course = I('post.course');
		$tiku = I('post.tiku');
		
		if($course != '' && $course != '-1'){
			$where['belongcourse'] = $course;
		}
		if($tiku != '' && $tiku != '-1'){
			$where['question_bank_id'] = $tiku;
		}
		for($i=1;$i<=4;$i++){
			$where['classification'] = $i;
			$data[] = M('examination_item')->where($where)->count();
		}
		return $data;
	}
	
	//智能组卷表单处理
	public function formHandle(){
		$data = I('post.');
		
		$user_id = session('user.id');
		if($data['suoshu'] != '-1'){
			$where['belongcourse'] = $data['suoshu'];
		}
		if($data['tiku'] != '-1'){
			$where['question_bank_id'] = $data['tiku'];
		}

//		M('examination_temp')->where(array('user_id'=>$user_id))->delete();
		
		if($data['dan']){
			$this->random_examinations($data['dan'],1,$data['dan-fen'],$where);
		}
		if($data['duo']){
			$this->random_examinations($data['duo'],2,$data['duo-fen'],$where);
		}
		if($data['pan']){
			$this->random_examinations($data['pan'],3,$data['pan-fen'],$where);
		}
		if($data['jian']){
			$this->random_examinations($data['jian'],4,$data['jian-fen'],$where);
		}
		
		$max = M('examination_temp')->where(array('user_id'=>session('user.id')))->max('id');
		$num = M('examination_temp')->where(array('user_id'=>session('user.id')))->sum('score');
//		$data['line1'] = ceil($data['per1'] * $num / 100);
		$msg = array(
			'name1'=>$data['name1'],
			'cate1'=>$data['cate1'],
			'line1'=>$data['line1']
		);
		M('examination_temp')->where(array('id'=>$max))->save($msg);
	}
	
	/**
	 * $num 要获取的试题数量
	 * $type 要获取的试题类型
	 * $score 试题分数
	 * $where 查询条件
	 */
	public function random_examinations($num,$type,$score,$where){
		$where['classification'] = $type;
		$data = M('Examination_item')
				->field('id as examination_id,title,classification')
				->where($where)
				->select();
		
		$res = array_rand($data,$num);
		$res = is_array($res) ? $res : (array)$res;
		
		foreach($res as $k=>$v){
			//添加的该试题是否已经存在
			$exists = M('examination_temp')
					->where(array('user_id'=>session('user.id'),'examination_id'=>$data[$v]['examination_id']))
					->find();
			if(!$exists){
				$info = M('examination_item')->where(array('id'=>$data[$v]['examination_id']))->find();
				
				$insertData['examination_id'] = $data[$v]['examination_id'];
				$insertData['title'] = $data[$v]['title'];
				$insertData['classification'] = $data[$v]['classification'];
				$insertData['course_name'] = M('course')->where(array('id'=>$info['belongcourse']))->getField('course_name');
				$insertData['question_bank'] = M('question_bank')->where(array('id'=>$info['question_bank_id']))->getField('name');
				$insertData['user_id'] = session('user.id');
				$insertData['score'] = $score;
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$insertData['id'] = getNextId('examination_temp');
				}
				$i = M('examination_temp')->add($insertData);
				if($i === false){
					return false;
				}
			}
		}
	}
	
	/**
	 * 获取临时表试题信息
	 */
	public function getTempExam(){
		$start_page = I("get.p",1,'int');
		$total_page = 10;		
		
		$res = M('examination_temp')
				->where(array('user_id'=>session('user.id')))
				->page($start_page,$total_page)
				->order('classification asc')
				->select();
		$count = M('examination_temp')
				->where(array('user_id'=>session('user.id')))
				->order('classification asc')
				->count();
		$show = $this->pageClass($count,$total_page);
		return array('data'=>$res,'page'=>$show);
	}
	
	/**
	 * 获取临时表分数、数量信息
	 */
	public function getNumInfo(){
		return M('examination_temp')
				->where(array('user_id'=>session('user.id')))
				->order('classification asc')
				->field('sum(score) as score,count(1) as num')
				->find();
	}
	
	/**
	 * 删除试题临时信息
	 */
	public function del_temp(){
		$id = I('post.');
		if(!is_array($id)){
			$res = M('examination_temp')
					->where(array('user_id'=>session('user.id'),'id'=>$id))
					->delete();
		}else{
			$res = M('examination_temp')
					->where(array('user_id'=>session('user.id'),'id'=>array('in',$id['id'])))
					->delete();
		}
		if($res === false){
			return array('status'=>0,'info'=>'删除失败');
		}else{
			return array('status'=>1,'info'=>'删除成功');
		}
	}
	
	/**
	 * 获取试题临时数据
	 */
	public function getTempData(){
		$user_id = session('user.id');
		$data = M('examination_temp a')
				->join('LEFT JOIN __EXAMINATION_ITEM__ b on a.examination_id=b.id')
				->where(array('a.user_id'=>$user_id))
				->select();

		$res = array();
		foreach($data as $k=>$v){
			switch ($v['classification']) {
				case '1':
					$res['dan-fen'] = $v['score'];
					$res['dan-info'][] = $v;
					break;
				case '2':
					$res['duo-fen'] = $v['score'];
					$res['duo-info'][] = $v;
					break;
				case '3':
					$res['pan-fen'] = $v['score'];
					$res['pan-info'][] = $v;
					break;
				case '4':
					$res['jian-fen'] = $v['score'];
					$res['jian-info'][] = $v;
					break;
			}
		}
		$res['dan-num'] = count($res['dan-info']);
		$res['duo-num'] = count($res['duo-info']);
		$res['pan-num'] = count($res['pan-info']);
		$res['jian-num'] = count($res['jian-info']);
		return $res;
	}
	
	/**
	 * 保存临时表试卷
	 */
	public function save_temp(){
		$post = I('post.');
		$tb1 = M('examination');
		$tb2 = M('examination_item_rel');
		
		$data1['test_name'] = $post['examname'];
		$data1['test_cat_id'] = $post['examcate'];
		$data1['test_score'] = $post['totalScore'];
		$data1['test_heir'] = session('user.username');
		$data1['test_upload_time'] = date('Y-m-d H:i:s');
		$data1['status'] = 0;
		$data1['principal'] = session('user.id');
		$data1['start_time'] = 1;
		$data1['end_time'] = 1;
		$data1['orderno'] = D('Trigger')->orderNumber(3);
		$data1['passing_score'] = $post['jige'];
		$data1['s_type'] = $post['type'];//组卷方式0-固定，1-随机
		
		$n1 = !$post['number_1'] ? '0' : $post['number_1'];
		$n2 = !$post['number_2'] ? '0' : $post['number_2'];
		$n3 = !$post['number_3'] ? '0' : $post['number_3'];
		$n4 = !$post['number_4'] ? '0' : $post['number_4'];
		
		$s1 = !$post['score_1'] ? '0' : $post['score_1'];
		$s2 = !$post['score_2'] ? '0' : $post['score_2'];
		$s3 = !$post['score_3'] ? '0' : $post['score_3'];
		$s4 = !$post['score_4'] ? '0' : $post['score_4'];
		
		$data1['number_score'] = $n1.','.$s1.','.$n2.','.$s2.','.$n3.','.$s3.','.$n4.','.$s4;
		$data1['auth_user_id'] = session('user.id');
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data1['id'] = getNextId('examination');
			$data1['test_upload_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			$data1['start_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			$data1['end_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}
		
		$id = $tb1->add($data1);
		
		if($id !== false){
			$temp_data = M('examination_temp')
						->where(array('user_id'=>session('user.id')))
						->field('examination_id,score')
						->select();
			if($post['type'] == 0){
				foreach($temp_data as $k=>$v){
					$data2['examination_id'] = $id;
					$data2['examination_item_id'] = $v['examination_id'];
					$data2['score'] = $v['score'];
					$res = $tb2->add($data2);
					if($res === false){
						return array('status'=>0);
					}
				}
			}
			
			M('examination_temp')->where(array('user_id'=>session('user.id')))->delete();
			return array('status'=>1);
		}else{
			return array('status'=>0);
		}
	}
	
	/**
	 * 试卷基本信息temp
	 */
	public function getBaseInfo(){
		$max = M('examination_temp')->where(array('user_id'=>session('user.id')))->max('id');
		return M('examination_temp')
				->where(array('id'=>$max))
				->field('name1,cate1,line1')
				->find();
	}
}