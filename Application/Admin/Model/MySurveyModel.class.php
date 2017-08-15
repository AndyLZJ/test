<?php

namespace Common\Model;

use Common\Model\BaseModel;

/**
 * ------------------------------------------------------------------------------
 * Description  我的调研操作模型
 * @filename MySurveyModel.class.php
 * @author Andy
 * @datetime 2016-1-5 11:37:29
 * -------------------------------------------------------------------------------
 */
class MySurveyModel extends BaseModel{
    /*
     * 初始化
     */
    function __construct(){
         
    }
    
    /*
     * 我的调研（待参与,已考试,已逾期）
     */
    public function getWaitSurvey($total_page = 10){

        $start_page = I("get.p",0,'int');
        $keyword=I("get.keyword")?I("get.keyword"):"";
        $typeid = I("get.typeid",0,'int');

        //获取已经过审核的项目
        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);
        $where['a.status'] = array("neq",2);

        if(!empty($keyword)){

            $where['_string']="(b.project_name like '%".$keyword."%')  OR (d.survey_name like '%".$keyword."%')";

            $results =  M("survey_attendance a")->field("a.id,a.project_id,a.survey_id,a.status,b.project_name,c.start_time,c.end_time,c.survey_names,d.survey_name,e.cat_name")->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id JOIN __PROJECT_SURVEY__ c ON b.id = c.project_id and c.survey_id = a.survey_id LEFT JOIN __SURVEY__ d ON c.survey_id = d.id LEFT JOIN __SURVEY_CATEGORY__ e ON d.survey_cat_id = e.id")->order("a.status asc,c.start_time asc,c.end_time asc")->where($where)->select();

        }else{

            $results =  M("survey_attendance a")->field("a.id,a.project_id,a.survey_id,a.status,b.project_name,c.start_time,c.end_time,c.survey_names,d.survey_name,e.cat_name")->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id JOIN __PROJECT_SURVEY__ c ON b.id = c.project_id and c.survey_id = a.survey_id LEFT JOIN __SURVEY__ d ON c.survey_id = d.id LEFT JOIN __SURVEY_CATEGORY__ e ON d.survey_cat_id = e.id")->order("a.status asc,c.start_time asc,c.end_time asc")->page($start_page,$total_page)->where($where)->select();

        }

        $count = M("survey_attendance a")->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id JOIN __PROJECT_SURVEY__ c ON b.id = c.project_id and c.survey_id = a.survey_id LEFT JOIN __SURVEY__ d ON c.survey_id = d.id")->where($where)->count();


        //输出分页
        $show = $this->pageClass($count,$total_page);

        $data = array(
            'typeid'=>$typeid,
            'page' => $show,
            'map' => $results,
            'keyword'=>$keyword
        );

        return $data;
   
    }

    /**
     * @return array
     * 处理试卷过期
     */
    public function overdue(){

        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);
        $where['b.type'] = array("eq",0);
        $where['c.project_id'] = array("gt",0);
        $where['c.survey_id'] = array("gt",0);

        //查询过期没有提交的试卷
        $overdue_data =M("designated_personnel a")
			->join("LEFT JOIN __ADMIN_PROJECT__ b ON a.project_id = b.id LEFT JOIN __PROJECT_SURVEY__ c ON b.id = c.project_id")
			->where($where)
			->field('c.project_id,c.survey_id,c.start_time,c.end_time')
			->select();

        foreach($overdue_data as $k=>$data) {

            $map['user_id'] = array("eq",$_SESSION['user']['id']);
            $map['project_id'] = array("eq",$data['project_id']);
            $map['survey_id'] = array("eq",$data['survey_id']);

            $is_survey = M("survey_attendance")->where($map)->find();

            if(empty($is_survey)){

                try {

                    $add = array(
                        "user_id" => $_SESSION['user']['id'],
                        "survey_id" => $data['survey_id'],
                        "project_id" => $data['project_id'],
                        "status" =>0,
                        "mobile_scanning"=>0
                    );

                    if(strtotime($data['end_time']) < time()){
                        $add['status'] = 3;
                    }

                    $DB = M('survey_attendance');

                    $DB->startTrans();

                    $DB->data($add)->add();

                    $DB->commit();


                } catch ( Exception $e ) {

                    $DB->rollback();

                }
            }else{

                if((strtotime($data['end_time'])) < time() and ($is_survey['status'] == 0)){
                    M('survey_attendance')-> where('id='.$is_survey['id'])->setField('status',3);
                }

            }


        }
    }

    /**
     * @return array
     * 试卷状态添加
     */
    public function researchAdd(){

        //查询所属组织
        $userGroup = M('tissue_group_access')->field("tissue_id")->where("user_id=".$_SESSION['user']['id'])->find();

        $where['a.tissue_id'] = array("eq",$userGroup['tissue_id']);
        $where['b.audit_state'] = array("eq",1);

        $items = M("research_tissueid a")
		->field("a.research_id,b.survey_id,b.end_time")
		->join("LEFT JOIN __RESEARCH__ b ON a.research_id = b.id")
		->where($where)
		->select();

        foreach($items as $item){

            $condition['survey_id'] = array("eq",$item['survey_id']);
            $condition['research_id'] = array("eq",$item['research_id']);
            $condition['user_id'] = array("eq",$_SESSION['user']['id']);

            //判断是否存在试卷
            $is_research = M('research_attendance')->where($condition)->find();

            if(empty($is_research)){

                $add = array(
                    "user_id" => $_SESSION['user']['id'],
                    "survey_id" => $item['survey_id'],
                    "research_id" => $item['research_id'],
                    "state" => 0
                );

                if(strtotime($item['end_time']) < time()){
                    $add['state'] = 3;
                }

                M('research_attendance')->data($add)->add();
                
            }else{

                if(strtotime($item['end_time']) < time() and ($is_research['state'] == 0)){
                    M('research_attendance')->where('id='.$is_research['id'])->setField('state',3);
                }

            }

        }

    }
    
    
    /*
     * 获取参加调研的题目
     */
    public function getSurveyInfo(){
        //调研类型
        $typeid = I('get.typeid');

        //调研id
        $survey_id = I('get.survey_id');

        if($typeid == 0){

            //关联项目id
            $project_id = I('get.project_id');

            //关联项目
            $admin_project = M('admin_project')
            				->field("project_name")
            				->where(array("id"=>$project_id))
            				->find();

            //调研状态
            $survey_attendance = M('survey_attendance')
            					->field("status")
            					->where(array("survey_id"=>$survey_id,"project_id"=>$project_id,"user_id"=>$_SESSION['user']['id']))
            					->find();

        }else{

            $research_id = I('get.research_id');

            $condition['survey_id'] = array("eq",$survey_id);
            $condition['research_id'] = array("eq",$research_id);
            $condition['user_id'] = array("eq",$_SESSION['user']['id']);

            $survey_attendance = M('research_attendance')->field("state as status")->where($condition)->find();

        }


        //调研类别
        $survey = M('survey a')
		->field("a.*,b.cat_name")
		->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")
		->where(array("a.id"=>$survey_id))
		->find();

		$surveyItem = M("survey_item")->where("survey_id=".$survey_id)->order("order asc")->select();
		foreach ($surveyItem as $key=>$value){
			if($value["classification"] == 1 || $value["classification"] == 2){
				//单选 多选获取选项
				$itemOpt = M("survey_item_opt")->where("item_id=".$value["id"])->order("order asc")->select();
				$surveyItem[$key]["option"] = $itemOpt;
			}
		}
		
        $items = array(
            "project_name"=>$admin_project['project_name'],
            "status"=>$survey_attendance['status'],
            "cat_name"=>$survey['cat_name'],
            "survey_id"=>$survey_id,
            "project_id"=>$project_id,
            "research_id"=>$research_id,
            "typeid"=>$typeid,
			"survey"=>$survey,
            "surveyItem"=>$surveyItem,
        );

        return $items;
    }
    
    /*
     * 处理参加调研提交的结果题目
     */
   
    public function handelJoinSurveyResult(){

        //接收提交考试的答案
        $data = I('post.');

        try {

            //提交调研 - 用户添加积分
            if($data['typeid'] == 0){

                $info = array(
                    "survey_id"=>$data['survey_id'],
                    "project_id"=>$data['project_id'],
                );

                $survey = M('project_survey')->where(array("project_id"=>array("eq",$data['project_id']),"survey_id"=>array("eq",$data['survey_id'])))->field('credit')->find();

                $this->creditAdd(1,$survey['credit'],$data['survey_id'],$data['project_id']);

            }else{

                $info = array(
                    "survey_id"=>$data['survey_id'],
                    "research_id"=>$data['research_id'],
                );

                $research = M('research')->where(array("id"=>array("eq",$data['research_id']),"survey_id"=>array("eq",$data['survey_id'])))->field('credits')->find();

                $this->creditAdd(3,$research['credits'],$data['survey_id'],$data['research_id']);

            }


            //单选题
            foreach($data['radio_list'] as $k=>$item){
                $this->is_survey($k,$item,1,$info,$data['typeid']);
            }

            $i = 0;

            //多选题
            foreach($data['multiselect_list'] as $k=>$item){

                if(!empty($item['other'])){
                    $info['other']  = $item['other'][$i];
                }else{
                    $info['other']  = "";
                }

                $str_tag = implode(",",$item['list']);
                $this->is_survey($k,$str_tag,2,$info,$data['typeid']);

                $i++;
            }

            //描述题
            foreach($data['describe_list'] as $k=>$item){
                $this->is_survey($k,$item,3,$info,$data['typeid']);
            }

            $commit_time = date("Y-m-d H:i:s",time());

            if($data['typeid'] == 0){

                $conditions['user_id'] = array("eq",$_SESSION['user']['id']);
                $conditions['survey_id'] = array("eq",$info['survey_id']);
                $conditions['project_id'] = array("eq",$info['project_id']);

                M('survey_attendance')->where($conditions)->setField(array('status'=>1,'commit_time'=>"{$commit_time}"));

            }else{

                $conditions['user_id'] = array("eq",$_SESSION['user']['id']);
                $conditions['survey_id'] = array("eq",$info['survey_id']);
                $conditions['research_id'] = array("eq",$info['research_id']);

                M('research_attendance')->where($conditions)->setField(array('state'=>1,'commit_time'=>"{$commit_time}"));
            }

            $results = array("state"=>true,"typeid"=>$data['typeid'],);

        } catch ( Exception $e ) {

            $results = array("state"=>false,"typeid"=>$data['typeid']);
        }

        return $results;
    
    }
    
    
    /**
     * 写入调研答案
     */
    public function is_survey($k,$item,$typeid,$info,$type)
    {
        $itea = array();
        if($typeid == 3){
            $itea['survey_answer'] = '';
            $itea['describe'] = $item;
        }else if($typeid == 2){
            $itea['survey_answer'] = $item;//题目答案
            if(!empty($info['other'])){
                $itea['describe'] = $info['other'];
            }
        }else{
            $itea['survey_answer'] = $item;//题目答案
        }

        if($type == 0){

            $itea['u_survey_id'] = $_SESSION['user']['id'];//考试人id
            $itea['survey_id'] = $info['survey_id'];//试卷id
            $itea['project_id'] = $info['project_id'];//关联项目id
            $itea['classification'] = $typeid;//题目类型 1单选 2多选 3判断
            $itea['question_number'] = $k;//题目序号

            M('Survey_answer')->data($itea)->add();

        }else{

            $itea['user_id'] = $_SESSION['user']['id'];//考试人id
            $itea['survey_id'] = $info['survey_id'];//试卷id
            $itea['research_id'] = $info['research_id'];//关联项目id
            $itea['classification'] = $typeid;//题目类型 1单选 2多选 3判断
            $itea['question_number'] = $k;//题目序号

            M('research_answer')->data($itea)->add();

        }

    }
    
    /*
     * 获取调研结果并作统计
     */
    public function getSurveyResult(){
    	$user_id = $_SESSION["user"]["id"];
        $typeid = I('get.typeid');

        $survey_id = I('get.survey_id');
        if($typeid == 0){
            $project_id = I('get.project_id');
            //关联项目
            $admin_project = M('admin_project')->field("project_name,end_time")->where(array("id"=>$project_id))->find();
            $info = array(
                "id"=>$project_id,
                "survey_id"=>$survey_id
            );

        }else{
            $research_id = I('get.research_id');
            $where['id'] = array("eq",$research_id);
            $admin_project = M('research')->field("end_time")->where($where)->find();
            $info = array(
                "id"=>$research_id,
                "survey_id"=>$survey_id
            );
        }
        
        if(strtotime($admin_project['end_time']) > time()){
            $survey_attendance['status'] = 0;
        }else{
            $survey_attendance['status'] = 1;
        }

        $survey = M('survey a')->field("a.*,b.cat_name")->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")->where(array("a.id"=>$survey_id))->find();
        $surveyItem = M('survey_item')->where("survey_id = ".$survey_id)->order("order asc")->select();
		foreach ($surveyItem as $key=>$value){
        	if($typeid == 0){
        		$answer = M("survey_answer");
        		$aWhere["project_id"] = $project_id;
        		$aWhere["survey_id"] = $survey_id;
        		$aWhere["question_number"] = $value["id"];
        	}else{
        		$answer = M("research_answer");
        		$aWhere["research_id"] = $research_id;
        		$aWhere["survey_id"] = $survey_id;
        		$aWhere["question_number"] = $value["id"];
        	}
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
        			if($typeid == 0){
        				$ptWhere["u_survey_id"] = $user_id;
        			}else{
	        			$ptWhere["user_id"] = $user_id;
        			}
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
        		if($typeid == 0){
        			$fillWhere["u_survey_id"] = $user_id;
        		}else{
        			$fillWhere["user_id"] = $user_id;
        		}
        		$itemAnswer = $answer->where($fillWhere)->find();
        		$surveyItem[$key]["survey_answer"] = $itemAnswer["describe"];
        	}
        }

        $items = array(
            "status"=>$survey_attendance['status'],
            "cat_name"=>$survey['cat_name'],
            "survey_id"=>$survey_id,
            "typeid"=>$typeid,
        	"survey"=>$survey,
        	"survetItem"=>$surveyItem,
        );

        if($typeid == 0){
            $items['project_name'] = $admin_project['project_name'];
            $items['project_id'] = $project_id;

        }else{
            $items['research_id'] = $research_id;
        }
        return $items;
    }

    /**
     * 获取统计进度条
     */
    public function getProgress($id,$typeid,$type,$info){

        $where = array();

        if($type == 0){

            $where['question_number'] = array("eq",$id);
            $where['project_id'] = array("eq",$info['id']);
            $where['survey_id'] = array("eq",$info['survey_id']);

            //获取调研总票数
            $total = M('survey_answer ')->where($where)->count();

            //获取单选试题条数
            $survey_answer_list = M('survey_answer ')->field("survey_answer")->where($where)->select();

        }else{

            $where['question_number'] = array("eq",$id);
            $where['research_id'] = array("eq",$info['id']);
            $where['survey_id'] = array("eq",$info['survey_id']);

            //获取调研总票数
            $total = M('research_answer ')->where("question_number = ".$id)->count();

            //获取单选试题条数
            $survey_answer_list = M('research_answer ')->field("survey_answer")->where("question_number = ".$id)->select();

        }

        $option = array(
            "a"=>0,
            "b"=>0,
            "c"=>0,
            "d"=>0,
            "e"=>0,
            "f"=>0,
        );

        if($typeid == 1){

            //统计单选票数
            foreach($survey_answer_list as $list){
                if($list['survey_answer'] == "A"){
                    $option['a']++;
                }elseif($list['survey_answer'] == "B"){
                    $option['b']++;
                }elseif($list['survey_answer'] == "C"){
                    $option['c']++;
                }elseif($list['survey_answer'] == "D"){
                    $option['d']++;
                }elseif($list['survey_answer'] == "E"){
                    $option['e']++;
                }else{
                    $option['f']++;
                }
            }

        }else{

            //统计单选票数
            foreach($survey_answer_list as $list){

                if(preg_match("/A/",$list['survey_answer'])){
                    $option['a']++;
                }
                if(preg_match("/B/",$list['survey_answer'])){
                    $option['b']++;
                }
                if(preg_match("/C/",$list['survey_answer'])){
                    $option['c']++;
                }
                if(preg_match("/D/",$list['survey_answer'])){
                    $option['d']++;
                }
                if(preg_match("/E/",$list['survey_answer'])){
                    $option['e']++;
                }else{
                    $option['f']++;
                }
            }

            $total = $option['a'] + $option['b'] + $option['c'] + $option['d'] + $option['e'];

        }


        //计算百份比
        $a = (int)round(($option['a']/$total)*100);
        $b = (int)round(($option['b']/$total)*100);
        $c = (int)round(($option['c']/$total)*100);
        $d = (int)round(($option['d']/$total)*100);
        $e = (int)round(($option['e']/$total)*100);



        //统计
        $statistics = array();
        $statistics['a'] = array("votes"=>$option['a'],"percentage"=>$a);
        $statistics['b'] = array("votes"=>$option['b'],"percentage"=>$b);
        $statistics['c'] = array("votes"=>$option['c'],"percentage"=>$c);
        $statistics['d'] = array("votes"=>$option['d'],"percentage"=>$d);
        $statistics['e'] = array("votes"=>$option['e'],"percentage"=>$e);

        return $statistics;

    }

    /**
     * 删除调研
     */
    public function delsurvey(){

        $typeid = I('get.typeid');

        if($typeid == 0){

            $survey_id = I('get.survey_id');
            //关联项目id
            $project_id = I('get.project_id');

            $where['user_id'] = array("eq",$_SESSION['user']['id']);
            $where['survey_id'] = array("eq",$survey_id);
            $where['project_id'] = array("eq",$project_id);

            M('survey_attendance')->where($where)->setField('status',2);

        }else{

            $survey_id = I('get.survey_id');
            //关联项目id
            $research_id = I('get.research_id');

            $where['user_id'] = array("eq",$_SESSION['user']['id']);
            $where['survey_id'] = array("eq",$survey_id);
            $where['research_id'] = array("eq",$research_id);

            M('research_attendance')->where($where)->setField('state',2);

        }

        return true;

    }


    /**
     * 调研评估
     */
    public function researchList($total_page){

        $start_page = I("get.p",0,'int');
        $keyword=I("get.keyword")?I("get.keyword"):"";
        $typeid = I("get.typeid",0,'int');

        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);
        $where['a.state'] = array("in","0,1,3");
        $where['b.audit_state'] = array("neq",0);

        if(!empty($keyword)){
            $where['_string']="(b.research_name like '%".$keyword."%')";
        }

        $results = M("research_attendance a")->join("LEFT JOIN __RESEARCH__ b ON a.survey_id = b.survey_id and a.research_id = b.id LEFT JOIN __SURVEY__ c ON a.survey_id = c.id LEFT JOIN __SURVEY_CATEGORY__ d ON c.survey_cat_id = d.id")->field("a.state as status,b.*,d.cat_name")->page($start_page,$total_page)->order("a.state asc,b.start_time asc,b.end_time asc")->where($where)->select();

        $count = M("research_attendance a")->join("LEFT JOIN __RESEARCH__ b ON a.survey_id = b.survey_id and a.research_id = b.id LEFT JOIN __SURVEY__ c ON a.survey_id = c.id LEFT JOIN __SURVEY_CATEGORY__ d ON c.survey_cat_id = d.id")->where($where)->count();

        //输出分页
        $show = $this->pageClass($count,$total_page);

        $data = array(
            'typeid'=>$typeid,
            'page' => $show,
            'map' => $results,
            'keyword'=>$keyword
        );

        return $data;
    }


}