<?php

namespace Common\Model;

use Common\Model\BaseModel;

class SurveyModel extends BaseModel
{

	/*
	 * 初始化
	 */
    function __construct(){}
	
    /**
     * 获取指定负责人下的所有调研信息
     */
    public function getProjectSurvey($total_page = 10){
        
        $start_page = I("get.p",0,'int');
        $keyword=I("get.keyword")?I("get.keyword"):"";
        $user_id=$_SESSION["user"]["id"];
       //如果存在搜索条件则执行
       if(!empty($keyword)){
        $where['c.survey_name']= array( "like", "%".$keyword."%");
       }
        $where['a.manager_id'] =  array('eq',$user_id);
        $where['b.type'] =  array('in',array('0','4'));
        
        $list = M('Project_survey a')
        ->join('LEFT JOIN __ADMIN_PROJECT__ b ON b.id=a.project_id LEFT JOIN __SURVEY__ c ON c.id=a.survey_id LEFT JOIN __SURVEY_CATEGORY__ d ON c.survey_cat_id=d.id')
        ->where($where)
        ->order('a.start_time desc')
        ->field('a.start_time,a.end_time,a.survey_names,a.survey_id,a.project_id,b.project_name,c.survey_name,d.cat_name')
        ->page($start_page,$total_page)
        ->select();
    
    
        //遍历开始时间和结束时间计算状态
        foreach($list as $k => $v){
           $s = strtotime($v['start_time']) - time();//调研开始时间与当前时间的差
           $e = strtotime($v['end_time']) - time();//调研结束时间和当前时间的差
           $p = strtotime($v['end_time']) - time();//调研结束时间和当前时间的差

           if($s < 0 && $e > 0){
              $items[$k]['_status'] = 0;//进行中 
           }else if($s > 0){
              $items[$k]['_status'] = 1;//未开始
           }else if($e < 0){
              $items[$k]['_status'] = 2;//已结束
           }
          
            $items[$k]['survey_id'] = $v['survey_id'];//调研id
            $items[$k]['cat_name'] = $v['cat_name'];//调研类别名称
            $items[$k]['project_id'] = $v['project_id'];//关联项目id
            $items[$k]['project_name'] = $v['project_name'];//关联项目名称
            $items[$k]['survey_name'] = $v['survey_name'];//调研名称
            $items[$k]['start_time'] = $v['start_time'];//开始时间
            $items[$k]['end_time'] = $v['end_time'];//结束时间
            
         
      }

      $count = count($items);
      //输出分页
      $show=$this->pageClass($count,$total_page);
      return $data = array(
          'page' => $show,
          'list' => $items,
          'keyword'=>$keyword
      );
      
    }
    
    /*
     * 获取预览调研题目信息
     */
	public function getPreviewSurveyInfo(){
		$user_id = $_SESSION["user"]["id"];
        //调研id
        $id = I('get.id');
        $t_name = I('get.t_name');
        $p_name = I('get.p_name');
        $s = I('get.s');
        //调研详情
        $survey = M('survey a')->field("a.*,b.cat_name")
       		->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")
        	->where(array("a.id"=>$id))->find();
        $survey["p_name"] = $p_name;
        $survey["t_name"] = $t_name;
        
        $surveyItem = M("survey_item")->where("survey_id=".$id)->order("order asc")->select();
        foreach ($surveyItem as $key=>$value){
        	if($value["classification"] == 1 || $value["classification"] == 2){
        		//单选 多选获取选项
        		$itemOpt = M("survey_item_opt")->where("item_id=".$value["id"])->order("order asc")->select();
        		$surveyItem[$key]["option"] = $itemOpt;
        	}
        }
        
        return $data = array(
            "survey" => $survey,//详情
        	"surveyItem" => $surveyItem,//题目
        );
    }
       /**
        ** 获取预览调研题目信息
        */
	public function getSurveyResultInfo(){
		$user_id = $_SESSION["user"]["id"];
        //调研id
        $id = I('get.id');
        $t_name = I('get.t_name');
        $p_name = I('get.p_name');
        $s = I('get.s');
        $pid = I('get.pid');
        //调研详情
        $survey = M('survey a')->field("a.*,b.cat_name")
       		->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")
        	->where(array("a.id"=>$id))->find();
        $survey["p_name"] = $p_name;
        $survey["t_name"] = $t_name;
        
        $surveyItem = M('survey_item')->where("survey_id = ".$id)->order("order asc")->select();
        foreach ($surveyItem as $key=>$value){
        	$answer = M("survey_answer");
        	$aWhere["project_id"] = $pid;
        	$aWhere["survey_id"] = $id;
        	$aWhere["question_number"] = $value["id"];
        	//题目类型判断
        	if($value["classification"] == 1 || $value["classification"] == 2){
        		//单选 多选
        		$itemOpt = M("survey_item_opt")->where("item_id=".$value["id"])->order("order asc")->select();
        		if($value["item_type"] == 1){
        			//投票（展示百分比）
        			$total = $answer->field("count(id) as num")->where($aWhere)->select();
        			$total = $total[0]["num"];
        			if($total > 0){
        				foreach ($itemOpt as $subKey=>$subValue){
        					$itemOpt[$subKey]["num"] = 0;//选项人数
        					$itemOpt[$subKey]["rate"] = "0%";//选项百分比
        					$optWhere = $aWhere;
        					$optWhere["survey_answer"] = array("like", "%".$subValue["letter"]."%");
        					$optNum = $answer->field("count(id) as num,survey_answer")->where($optWhere)->select();
        					if($optNum > 0){
        						$optNum = $optNum[0]["num"];
        						$optRate = $optNum / $total * 100;
        						$optRate = (round($optRate, 2))."%";
        						$itemOpt[$subKey]["rate"] = $optRate;
        						$itemOpt[$subKey]["num"] = $optNum;
        					}
        				}
        			}else{
        				foreach ($itemOpt as $subKey=>$subValue){
        					$itemOpt[$subKey]["num"] = 0;//选项人数
        					$itemOpt[$subKey]["rate"] = "0%";//选项百分比
        				}
        			}
        		}else{
        			//普通（展示自己的选项）
        			$ptWhere = $aWhere;
        			$ptWhere["u_survey_id"] = $user_id;
        			$itemAnswer = $answer->where($ptWhere)->find();
        			$itemOpt[$subKey]["survey_answer"] = $itemAnswer["survey_answer"];
        			foreach ($itemOpt as $subKey=>$subValue){
        				$ptWhere["survey_answer"] = array("like", "%".$subValue["letter"]."%");
        				$optNum = $answer->field("survey_answer")->where($ptWhere)->select();
        				$itemOpt[$subKey]["ischeck"] = 0;
        				if($optNum){
        					$itemOpt[$subKey]["ischeck"] = 1;
        				}
        			}
        		}
        		$surveyItem[$key]["option"] = $itemOpt;
        	}else{
        		$fillWhere = $aWhere;
        		$fillWhere["u_survey_id"] = $user_id;
        		$itemAnswer = $answer->where($fillWhere)->find();
        		$surveyItem[$key]["survey_answer"] = $itemAnswer["describe"];
        	}
        }
        
        $data = array(
        		"survey"=>$survey,
        		"survetItem"=>$surveyItem,
        );
        return $data;
	}
    
    
	/* //获取所有审核通过的调研
	public function getAll(){
		import("Org.Nx.AjaxPage");
		$limitRows = 4;
		$map["status"]=1;
		$map["is_available"]=1;
		$SurveyCategoryTable=$this->tablePrefix . "survey_category";
		$count=$this->where($map)->count();
		$p = new \AjaxPage($count, $limitRows,"survey");
		$p->setConfig('theme','%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$sql="select a.id,a.survey_name,a.survey_cat_id,a.survey_mode,b.cat_name from " . $this->trueTableName . " a left join " . $SurveyCategoryTable . " b on a.survey_cat_id=b.id where a.status=1 and a.is_available=1 limit " . $p->firstRow . " , " . $p->listRows;
		$surveys=$this->query($sql);
		$show = $p->show();
		$assign=array(
			"list"=>$surveys,
			"show"=>$show
		);
		return $assign;
	} */

	//组合条件搜索问卷

	public function searchSurvey($data){
		import("Org.Nx.AjaxPage");
		$limitRows = 4;
		$map=array();
		$where=" a.status=1 and a.is_available=1";
		if($data["survey_category"]!=0){
			$map["survey_cat_id"]=$data["survey_category"];
			$where.=" and a.survey_cat_id= " . $data["survey_category"];
		}
		if($data["survey_name"]!=""){
			$datas["survey_name"]="%" . $data["survey_name"] . "%";
			$map['survey_name']=array('like',$datas["survey_name"]);
			$where.=" and a.survey_name like " . "'%" . $data["survey_name"] . "%'";
		}
		$map["status"]=1;
		$map["is_available"]=1;
		$surveyCategoryTable=$this->tablePrefix . "survey_category";
		$count=$this->where($map)->count();
		$p = new \AjaxPage($count, $limitRows,"survey");
		$p->setConfig('theme','%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$sql="select a.id,a.survey_name,a.survey_cat_id,b.cat_name from " . $this->trueTableName . " a left join " . $surveyCategoryTable . " b on a.survey_cat_id=b.id where " . $where . " limit " . $p->firstRow . " , " . $p->listRows;
		$surveys=$this->query($sql);
		$show = $p->show();
		$assign=array(
			"list"=>$surveys,
			"show"=>$show
		);
		return $assign;

	}

/***
获取已经选定的调研	
**/

public function getSurveySelect(){
	$arr["project_id"]=$_SESSION['project']['id'];
	$projectSurvey=D("projectSurvey")->where($arr)->find();
	$id=array_unique(explode(",",$projectSurvey["survey_id"]));
	$map["id"]=array("in",$id);
	$projectSurvey=D("Survey")->where($map)->select();
	return $projectSurvey;
}

/***
获取指定项目的调研信息	
**/

public function getSurveyOneSelect($project_id){
	$arr["project_id"]=$project_id;
	$projectSurvey=D("projectSurvey")->where($arr)->find();
	$id=array_unique(explode(",",$projectSurvey["survey_id"]));
	$map["id"]=array("in",$id);
	$projectSurvey=D("Survey")->where($map)->select();
	return $projectSurvey;
}

/***
获取资源库对应调研的调研信息
*/
public function getOne($id){

	$map["a.id"]=$id;

	$survey=$this
			->alias("a")
			->field("a.*,b.cat_name")
			->join("LEFT JOIN __SURVEY_CATEGORY__ b on b.id=a.survey_cat_id")
			->where($map)
			->find();
	return $survey;
}

 /*获取资源库课程信息*/
    public function getSurvey($arr){
        $map["id"]=array("in",$arr);
        $surveys=$this->where($map)->order("id")->select();
       	return $surveys;
    }

}