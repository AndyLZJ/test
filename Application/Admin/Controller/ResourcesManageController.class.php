<?php

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/*
 * 资源管理控制器
 * @Andy 
 */
class ResourcesManageController extends AdminBaseController{
    
    
    /*
     * 课程管理列表
     */
    public function courseManage(){
        
        $this->display();
    } 
    
    /*
     * 下载试卷模板
     */
    public function downloadExam(){
       	
		$uploadpath="./Upload/exam/";

		$saveName="exam.xls";//文件保存名

		$showName="exam.xlsx";//文件原名
        
		$filename=$uploadpath.$saveName;//完整文件名（路径加名字）

		file_download($filename);
    }
    
    /*
     * 下载试卷模板
     */
    public function downloadSurvey(){
       
        	
        $uploadpath="./Upload/survey/";
    
        $saveName="survey.xls";//文件保存名
    
        $showName="survey.xls";//文件原名
    
        $filename=$uploadpath.$saveName;//完整文件名（路径加名字）
    
        file_download($filename);
    }
 
       

    /*
     * 新增试卷分类
     */
    public function addExamclassify(){
        $results = D('ResourcesManage')->addTestClass();
        $data = array(
            "status"=> $results
        );
        $this->ajaxReturn($data,'json');
    }

    /**
     * 删除问卷
     */
    public function delTestCategory(){

        $results = D('ResourcesManage')->delTestCategory();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /*
     * 导入试卷
     */
    public function importExam(){
        if(IS_POST){
            $options = I('post.');

            /*if(!$options['importExam']){
                $this->error('请选择试卷分类!');
            }*/
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 300*1024*1024 ;// 设置附件上传大小
            $upload->exts = array('xls','xlsx');// 设置附件上传类型
            $upload->rootPath = './Upload/'; // 设置附件上传根目录
            $upload->savePath = 'excel/'; // 设置附件上传（子）目录
            $upload->autoSub = true;
            $upload->subName = '';
            // 上传文件
            $info = $upload->upload();
            
            if(!$info) {// 上传错误提示错误信息 
                $this->error($upload->getError());
            }else{// 上传成功
                foreach($info as $v){
                    $file_path1 = './Upload/'.$v['savepath'];
                    $file_path2 = $v['savename'];
                }
                $file = $file_path1.$file_path2;
                //dump($file);exit;
                if(file_exists($file)){
                    $list = D('ResourcesManage')->uploadExam($file);

                    if($list === false){
                        $this->error('试卷格式错误或有必填项为空,请重新填写!',U('passExam'));
                    }

                    $orderno =  D('Trigger')->orderNumber(3);

                    $arr['test_heir'] = $_SESSION['user']['username'];
                    $arr['test_upload_time'] = date("Y-m-d H:i:s",time());
                    $arr['test_cat_id'] = $options['importExam'];
                    $arr['test_name'] = $list['test_name'];
                    $arr['test_score'] = $list['totalscore'];
                    $arr['status'] = 0;
                    $arr['is_available'] = 1;
                    $arr['principal'] = $_SESSION['user']['username'];
                    $arr['type'] = 1;
                    $arr['orderno'] = $orderno;
					
					$arr['auth_user_id'] = session('user.id');
					
                    $ret = M('Examination')->add($arr);

                    if($ret){
                        foreach($list['a'] as $k => $v){
                            // $msg[$k]['examination_id'] = $ret;//试卷id
                            $msg['title'] = $v['title'];
                            $msg['optiona'] = $v['optiona'];
                            $msg['optionb'] = $v['optionb'];
                            $msg['optionc'] = $v['optionc'];
                            $msg['optiond'] = $v['optiond'];
                            $msg['optione'] = $v['optione'];
                            $msg['right_option'] = trim(str_replace('，',',',$v['right_option']));
                            $msg['classification'] = $v['classification'];
                            $msg['ctime'] = date("Y-m-d H:i:s",time());
                            $msg['creater_id'] = $_SESSION['user']['id'];
                            $msg['keywords'] = trim(str_replace('，',',',$v['keywords']));
                            $msg['analysis'] = $v['analysis'];
                            $msg['belongcourse'] = $options['importExam'];
                            $res = M('ExaminationItem')->add($msg);//试题id

                            //试卷试题关联表
                            if($res){
                                $info['examination_id'] = $ret;
                                $info['examination_item_id'] = $res;
                                $info['score'] = $v['item_score'];
                                $res2 = M('examination_item_rel')->add($info);
                                if(!$res2){
                                    $this->error('导入失败',U('passExam'));
                                }
                            }else{
                                $this->error('导入失败',U('passExam'));
                            }
                        }
                    }else{
                        $this->error('导入失败',U('passExam'));
                    }
                    $this->success('导入成功',U('passExam'));
                }
            }
    
        }else{
            $this->error('非法数据提交',U('passExam'));
        }
    
    }
    /*
     * 已通过的试卷列表
     */
    public function passExam(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('ResourcesManage')->passExamList($total_page);
		
        $getExamCateWhere["cat_name"] = array('neq', '');
        $cate = D('ResourcesManage')->getExamCate($getExamCateWhere);
        $this->assign('category',$cate);
        $this->assign('approved_list',$approved_data['list']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('data',$approved_data['xhr']);
        //搜索项
        $this->assign('keyword',$approved_data['keyword']);
        $this->assign('cate',$approved_data['cate']);
        $this->assign('heir',$approved_data['heir']);
        $this->display();
    }
    
    /*
     * 自定义删除试卷列表数据
     */
    public function del_all(){
        $post = (I('post.id'));
        $res = M('Examination')->where(array('id'=>array('in',$post)))->delete();
        if($res){
           $this->ajaxReturn(1); 
        }else{
           $this->ajaxReturn(0);
        }
    }
    /*
     * 待审核的试卷列表
     */
    public function auditExam(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('ResourcesManage')->auditExamList($total_page);

        $cate = D('ResourcesManage')->getExamCate(array('cat_name'=>array('neq','')));

        $this->assign('category',$cate);

        $this->assign('approved_list',$approved_data['list']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('data',$approved_data['xhr']);
        $this->assign('keyword',$approved_data['keyword']);
        $this->display();
    }
    
    /*
     * 已拒绝的试卷列表
     */
    public function refusedExam(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('ResourcesManage')->refusedExamList($total_page);

        $cate = D('ResourcesManage')->getExamCate(array('cat_name'=>array('neq','')));

        $this->assign('category',$cate);

        $this->assign('approved_list',$approved_data['list']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('data',$approved_data['xhr']);
        $this->assign('keyword',$approved_data['keyword']);
        $this->display();
    }
    
    /*
     * 禁用试卷
     */
    public function examForbidden(){
        if(IS_POST){
            $id = I('post.eid');
            $res = D('ResourcesManage')->examForbiddens($id);
            !$res and $this->ajaxReturn(0);
            $this->ajaxReturn(1);  
        }
    }
    
    /*
     * 试卷启用
     */
    public function examOpen(){
        if(IS_POST){
            $id = I('post.eid');
            $res = D('ResourcesManage')->examOpens($id);
            !$res and $this->ajaxReturn(0);
            $this->ajaxReturn(1);
        }
    }
    /*
     * 删除已拒绝审核的试卷
     */
    public function DeleteForbiddenExam(){
        if(IS_POST){
            $id = I('post.eid');
            $res = D('ResourcesManage')->examDelete($id);
            !$res and $this->ajaxReturn(0);
            $this->ajaxReturn(1);
        }
    }
    
    
    /**
     * 试卷详情
     */
    public function examDestail(){
        $id = I('get.id');
        $_SESSION['exam_id'] = $id;
        $exam = D('ResourcesManage');
        $data = $exam->getExamDetail2($id,session('user.id'));

        $_SESSION['exam_name'] = $data['detail']['test_name'];
        // dump($data);
        //详情
        $this->assign('xhr',$data['detail']);
        //单选
        $this->assign('singleChoice',$data['singleChoice']);
        $this->assign('singleChoiceSum',$data['singleChoiceSum']);
        $this->assign('singleChoiceTotalScore',$data['singleChoiceTotalScore']);
        //多选
        $this->assign('multipleChoice',$data['multipleChoice']);
        $this->assign('multipleChoiceSum',$data['multipleChoiceSum']);
        $this->assign('multipleChoiceTotalScore',$data['multipleChoiceTotalScore']);
        //判断
        $this->assign('descriPtive',$data['descriPtive']);
        $this->assign('descriPtiveChoiceSum',$data['descriPtiveChoiceSum']);
        $this->assign('descriPtiveChoiceTotalScore',$data['descriPtiveChoiceTotalScore']);

        //问答
        $this->assign('wd',$data['wd']);
        $this->assign('wdSum',$data['wdSum']);
        $this->assign('wdTotalScore',$data['wdTotalScore']);
        
        $this->assign('refused',I('get.refused'));
        $this->assign('status',I('get.status'));
        $this->display();
        
    }
    
    /*
     * 试卷统计
     */
    public function examCount(){
        $exam = D('ResourcesManage');
        $id = I('get.id');
        
        $data = $exam->getExamInfo($id);
        // dump($data);
        $status = $exam->getExaminationStatus($id);

        $this->assign('name',session('exam_name'));
        $this->assign('id',session('exam_id'));
        $this->assign('data',$data);
        $this->assign('status',$status);
        $this->display();
    }
    
    /*
     * 已通过的问卷列表
     */
    public function passQuestionNaire(){
        //每页显示条数
        $total_page = $this->total_page;
        $item = M("SurveyCategory")->select();
        $approved_data = D('ResourcesManage')->passQuestionNaireList($total_page);
        $this->assign('list',$item);
        $this->assign('approved_list',$approved_data['list']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('data',$approved_data['xhr']);
        $this->assign('keyword',$approved_data['keyword']);
        $this->display();
    }

    /**
     * 分类管理
     */
    public function classManagement(){

        $data = D('ResourcesManage')->classManagement();

        $this->assign("items",$data);
        $this->display();
    }

    /**
     * 试卷分类管理
     * @return [type] [description]
     */
    public function testClassManagement(){
        $data = D('ResourcesManage')->testClassManagement();

        $this->assign("items",$data);
        $this->display();
    }
    
    /*
     * 待审核的问卷列表
     */
    public function auditQuestionNaire(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('ResourcesManage')->auditQuestionNaireList($total_page);

        $this->assign('approved_list',$approved_data['list']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('data',$approved_data['xhr']);
        $this->assign('keyword',$approved_data['keyword']);
        $this->display();
    }
    
    /*
     * 已拒绝的问卷列表
     */
    public function refusedQuestionNaire(){
        //每页显示条数
        $total_page = $this->total_page;
        $approved_data = D('ResourcesManage')->refusedQuestionNaireList($total_page);
        $this->assign('approved_list',$approved_data['list']);
        $this->assign('approved_page',$approved_data['page']);
        $this->assign('data',$approved_data['xhr']);
        $this->assign('keyword',$approved_data['keyword']);
        $this->display();
    }
    /*
     * 问卷管理详情
     */
    public function questionNaireDetail(){
        $id = I('get.id');
        $_SESSION['questionNaire_id'] = $id;
        $questionNaire = D('ResourcesManage');
        $data = $questionNaire->getQuestionNaireDetail($id);
        session('questionNaire_name',$data['detail']['survey_name']);
        
        $this->assign('base',$data['base']);//详情
        $this->assign('list',$data['list']);//题目
        $this->assign('refused',I('get.refused'));
        $this->assign('status',I('get.status'));
        $this->display();
    }
    
    /*
     * 问卷统计
     */
    public function questionNaireCount(){
        $model = D('ResourcesManage');
        $id = I('get.id');

        $data = $model->getQUestionNaireInfo($id);
        $status = $model->getSurveyStatus($id);

        $this->assign('id',$_SESSION['questionNaire_id']);
        $this->assign('name',session('questionNaire_name'));
        $this->assign('data',$data);
        $this->assign('status',$status);
        $this->display();
    }
    /*
     * 问卷禁用
     */
    public function questionNaireForbidden(){
        if(IS_AJAX){
            $id = I('post.did');
            $res = M('Survey')->where(array('id'=>$id))->setField('is_available','0');
            !$res and $this->ajaxReturn(0);
            $this->ajaxReturn(1);
        }
    }
    
    
    /*
     * 问卷启用
     */
    public function questionNaireOpen(){
        if(IS_AJAX){
            $id = I('post.did');
            $res = M('Survey')->where(array('id'=>$id))->setField('is_available','1');
            !$res and $this->ajaxReturn(0);
            $this->ajaxReturn(1);
        }
    }
    
    /**
     * 单个删除问卷
     */
    public function delSurvey(){
    	$post = I('post.');
    	$post["survey_id"] += 0;
    	if(!is_int($post["survey_id"]) || $post["survey_id"] < 1){
    		exit(json_encode(array("code"=>1011, "message"=>"请提交问卷id")));
    	}
    	
    	$resp = D('ResourcesManage')->delSurvey($post["survey_id"]);
    	if($resp["code"] != 1000){
    		exit(json_encode(array("code"=>1012, "message"=>$resp["message"])));
    	}else{
    		exit(json_encode(array("code"=>1000, "message"=>"成功")));
    	}
    }
    
    /*
     * 批量删除问卷
     */
    public function del_alls(){
      
            $post = (I('post.id'));
            $res = M('Survey')->where(array('id'=>array('in',$post)))->delete();
            if($res){
                $this->ajaxReturn(1);
            }else{
                $this->ajaxReturn(0);
            }  
    }
    
    /*
     * 导入问卷
     */
    public function importQuestionNaire(){
    	//文件数据结构查看
    	/* 
    	$file = "Upload/excel/mytest12.xlsx";
    	$file = "Upload/excel/mytest.xls";
    	$list = D('ResourcesManage')->importExcelSurvey($file);
    	print_r($list);
    	exit;
    	 */
        if($_POST){
            $options = I('post.questionNaireType');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 300*1024*1024 ;// 设置附件上传大小
            $upload->exts = array('jpg', 'gif', 'png', 'jpeg','xls','xlsx');// 设置附件上传类型
            $upload->rootPath = './Upload/'; // 设置附件上传根目录
            $upload->savePath = 'excel/'; // 设置附件上传（子）目录
            $upload->autoSub = true;
            $upload->subName = '';
            // 上传文件
            $info = $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                //$this->error($upload->getError());
                exit(json_encode(array("code"=>1011, "message"=>$upload->getError())));
            }else{// 上传成功
                foreach($info as $v){
                    $file_path1 = './Upload/'.$v['savepath'];
                    $file_path2 = $v['savename'];
                }
                
                $file = $file_path1.$file_path2;
                if(file_exists($file)){
                    $list = D('ResourcesManage')->importExcelSurvey($file);
                    if($list["code"] != 1000){
                    	exit(json_encode($list));
                    }
					
                    $survey_name = $list["data"]["0"]["0"];
                    //判断是否已有标题相同的问卷
                    $hasWhere["survey_name"] = $survey_name;
                    $has = M("survey")->where($hasWhere)->limit(1)->select();
                    if($has){
                    	exit(json_encode(array("code"=>1011, "message"=>"存在标题相同的问卷，可能问卷已上传或者修改标题重新上传")));
                    }
                    
                    $survey_desc = "";
                    $survey_type = 0;
                    if(strstr($survey_name, "问卷") || strstr($survey_name, "调研")){
	                    $survey_type = 1;
                    	$survey_desc = $list["data"]["1"][0];
                    }elseif(strstr($survey_name, "回执")){
	                    $survey_type = 2;
                    }elseif(strstr($survey_name, "效果评估")){
	                    $survey_type = 3;
                    	exit(json_encode(array("code"=>1012, "message"=>"暂不支持培训效果评估类型的导入")));
                    }else{
                    	//其他统一当做常规问卷吧
                    	$survey_type = 1;
                    	$survey_desc = $list["data"]["1"][0];
                    }
                   	
                    $arr['survey_name'] = $survey_name;
                    $arr['survey_desc'] = $survey_desc;
                    $arr['survey_cat_id'] = $options;
                    $arr['survey_heir'] = $_SESSION['user']['id'];
                    $arr['survey_upload_time'] = date("Y-m-d H:i:s",time());
                    $arr['status'] = 0;//待审核
                    $orderno = D('Trigger')->orderNumber(4);
                    $arr['orderno'] = $orderno;//工单号
                    $survey_id = M('Survey')->add($arr);
                   	if(!$survey_id){
                   		exit(json_encode(array("code"=>1013, "message"=>"上传失败，请稍后重试")));
                   	}
                    //保存问卷题目
                    for ($i=0; $i<count($list["data"]); $i++){
                    	if($survey_type == 1){
                    		if($i < 2) continue;
                    		if($i % 2 == 1){
                    			$thisData = $list["data"][$i];
                    			$option0 = $thisData[0]."";
                    			$classification = 0;//问卷分类 1表示单选题 2表示多选题 3判断 4简答
                    			//奇数行保存数据（偶数行标题   奇数行选项）
                    			$msg['optionF'] = "";
                    			if($option0 == ""){
                    				//格式1
                    				$msg['title'] = $list["data"][$i-1][1];
                    				if($thisData[1] && $thisData[2] && $thisData[3]){
	                    				//有三项项存在，题目类型算作选择题
	                    				$classification = 2;
	                    				$msg['optiona'] = $thisData[1];
	                    				$msg['optionb'] = $thisData[2];
	                    				$msg['optionc'] = $thisData[3];
	                    				$msg['optiond'] = $thisData[4];
	                    				$msg['optione'] = $thisData[5];
	                    				$msg['optionF'] = $thisData[6];
	                    			}elseif($thisData[1] && $thisData[2] && !$thisData[3]){
	                    				//只有两项项存在，题目类型算作单选题
	                    				$classification = 1;
	                    				$msg['optiona'] = $thisData[1];
	                    				$msg['optionb'] = $thisData[2];
	                    			}else{
	                    				//简答题
	                    				$classification = 4;
	                    			}
                    			}else{
                    				//格式2
                    				$msg['title'] = $list["data"][$i-1][0];
                    				if($thisData[0] && $thisData[1] && $thisData[2]){
	                    				//有三项项存在，题目类型算作选择题
	                    				$classification = 2;
	                    				$msg['optiona'] = $thisData[0];
	                    				$msg['optionb'] = $thisData[1];
	                    				$msg['optionc'] = $thisData[2];
	                    				$msg['optiond'] = $thisData[3];
	                    				$msg['optione'] = $thisData[4];
	                    				$msg['optionF'] = $thisData[5];
	                    			}elseif($thisData[0] && $thisData[1] && !$thisData[2]){
	                    				//只有两项项存在，题目类型算作单选题
	                    				$classification = 1;
	                    				$msg['optiona'] = $thisData[0];
	                    				$msg['optionb'] = $thisData[1];
	                    			}else{
	                    				//简答题
	                    				$classification = 4;
	                    			}
                    			}
                    			
                    			if(!$msg['title'] || $msg['title'] == "") continue;
                    			$hasItem = M("survey_item")->where("survey_id=".$survey_id." and title='".$msg['title']."'")->find();
                    			if($hasItem){
                    				continue;
                    			}
                    			
                    			$msg['survey_id'] = $survey_id;
                    			$msg['classification'] = $classification;
                    			$msg['ctime'] = date("Y-m-d H:i:s",time());
                    			$res = M('survey_item')->add($msg);
                    		}
                    	}elseif($survey_type == 2){
                    		//回执表
                    		$thisData = $list["data"][$i];
                    		for($j=0; $j<count($thisData); $j++){
                    			if($thisData[$j]){
                    				if(!$thisData[$j] || $thisData[$j] == "") continue;
                    				$hasItem = M("survey_item")->where("survey_id=".$survey_id." and title='".$thisData[$j]."'")->find();
                    				if($hasItem){
                    					continue;
                    				}
                    				$msg['survey_id'] = $survey_id;
                    				$msg['title'] = $thisData[$j];
                    				$msg['classification'] = 4;
                    				$msg['ctime'] = date("Y-m-d H:i:s",time());
                    				$res = M('survey_item')->add($msg);
                    			}
                    		}	
                    	}
                    }
                    exit(json_encode(array("code"=>1000, "message"=>"导入成功")));
                }
            }
		}else{
			exit(json_encode(array("code"=>1001, "message"=>'非法数据提交')));
		}
    }
    
    /*
     * 添加问卷分类
     */
    public function questionNaireStyle(){

        $results = D('ResourcesManage')->addClass();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');
 
    }

    /**
     * 删除问卷
     */
    public function delCategory(){

        $results = D('ResourcesManage')->delCategory();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /*
    * 智能组卷页面展示
     */
    public function smartExam(){
        $model = D('ResourcesManage');
        $cate = $model->getExamCate();  //试卷分类
        $count = $model->getExamNum();

        //课程
        $where['auditing'] = 1;
        $where['status'] = 1;
        $course= D('Course')->getAllCourse($where);

        $this->assign('cate',$cate);
        $this->assign('count',$count);
        $this->assign('course',$course);
        $this->display();
    }
	
	/*
    * 智能组卷页面展示2
     */
    public function smartExam2(){
//  	M('examination_temp')->where(array('user_id'=>session('user.id')))->delete();
		$url = array(U('passexam','','',true),U('auditexam','','',true),U('refusedexam','','',true));
		if(in_array($_SERVER['HTTP_REFERER'],$url)){
			M('examination_temp')->where(array('user_id'=>session('user.id')))->delete();
		}
        $model = D('ResourcesManage');
        $cate = $model->getExamCate();  //试卷分类
        $count = $model->getExamNum();
		$data = $model->getTempExam();
		$scoreInfo = $model->getNumInfo();
		$baseinfo = $model->getBaseInfo();
		$bank = D('QuestionBank')->getAllBank();
		$examinationInfo = D('ResourcesManage')->getExamNum();
		
        //课程
        $where['auditing'] = 1;
        $where['status'] = 1;
        $course= D('Course')->getAllCourse($where);
		
		$this->assign('bank',$bank);
		$this->assign('examinationInfo',$examinationInfo);
		$this->assign('data',$data['data']);
		$this->assign('page',$data['page']);
		$this->assign('scoreInfo',$scoreInfo);
        $this->assign('cate',$cate);
        $this->assign('count',$count);
        $this->assign('course',$course);
        $this->assign('baseinfo',$baseinfo);
        $this->display();
    }
	
	public function add_examination_item(){
		$model = D('ResourcesManage');
		$count = $model->getExamNum();
        //课程
		$specifiedUser = D('IsolationData')->specifiedUser(false);
		$where['auth_user_id'] = array('in',$specifiedUser);
        $where['auditing'] = 1;
        $where['status'] = 1;
        $course= D('Course')->getAllCourse($where);
		
		//试题库
        $where2['auth_user_id'] = array('in',$specifiedUser);
		$question_bank = M('question_bank')->where($where2)->select();
		$this->assign('question_bank',$question_bank);
        $this->assign('course',$course);
        $this->assign('count',$count);
		$this->display();
	}
	
	/**
	 * 删除试题临时数据
	 */
	public function del_temp(){
		$data = D('ResourcesManage')->del_temp();
		$this->ajaxReturn($data);
	}
	
	
	public function getExamNumBy(){
		$res = D('ResourcesManage')->getExamNumBy();
		$this->ajaxReturn($res);
	}
	
	//智能组卷表单处理
	public function formHandle(){
		D('ResourcesManage')->formHandle();
	}
	
	/**
	 * 预览试卷-临时表
	 */
	public function preview_temp(){
		$data = D('ResourcesManage')->getTempData();

		//单选
        $this->assign('singleChoice',$data['dan-info']);
        $this->assign('singleChoiceSum',$data['dan-num']);
        $this->assign('singleChoiceScore',$data['dan-fen']);
        $this->assign('singleChoiceTotalScore',$data['dan-num'] * $data['dan-fen']);
        //多选
        $this->assign('multipleChoice',$data['duo-info']);
        $this->assign('multipleChoiceSum',$data['duo-num']);
        $this->assign('multipleChoiceScore',$data['duo-fen']);
        $this->assign('multipleChoiceTotalScore',$data['duo-num'] * $data['duo-fen']);
        //判断
        $this->assign('descriPtive',$data['pan-info']);
        $this->assign('descriPtiveChoiceSum',$data['pan-num']);
        $this->assign('descriPtiveChoiceScore',$data['pan-fen']);
        $this->assign('descriPtiveChoiceTotalScore',$data['pan-num'] * $data['pan-fen']);

        //问答
        $this->assign('wd',$data['jian-info']);
        $this->assign('wdSum',$data['jian-num']);
        $this->assign('wdScore',$data['jian-fen']);
        $this->assign('wdTotalScore',$data['jian-num'] * $data['jian-fen']);

//      $this->assign('examname',$data['examname']);
//      $this->assign('examcate',$cate[0]['cat_name']);
        $this->assign('totalScore',$data['totalScore']);
        $this->display('preview');
		
	}

	public function save_temp(){
		$data = D('ResourcesManage')->save_temp();
		$this->ajaxReturn($data);
	}
	
	public function del_temp_data(){
		M('examination_temp')->where(array('user_id'=>session('user.id')))->delete();
	}
    /**
     * 预览试卷
     * @return [type] [description]
     */
    public function preview(){
        $data = I('post.');
        $model = D('ResourcesManage');
        $exams = $model->randomExam($data);
        
        $exams = $model->randomExam($data);
        //预览试卷后自动存session,提交表单后就不再重复查询数据库
        //防止预览到的试题与实际提交的试题不一致 或 多次预览到的试题不一致
        session('dan',$exams['dan']['ids']);
        session('duo',$exams['duo']['ids']);
        session('pan',$exams['pan']['ids']);
        session('jian',$exams['jian']['ids']);
        session('exams',$exams);
        session('formdata',$data);
        

        $cate = $model->getExamCate(array('id'=>$data['examcate']));
        //单选
        $this->assign('singleChoice',$exams['dan']['info']);
        $this->assign('singleChoiceSum',$data['dan']);
        $this->assign('singleChoiceScore',$data['dan-fen']);
        $this->assign('singleChoiceTotalScore',$data['dan'] * $data['dan-fen']);
        //多选
        $this->assign('multipleChoice',$exams['duo']['info']);
        $this->assign('multipleChoiceSum',$data['duo']);
        $this->assign('multipleChoiceScore',$data['duo-fen']);
        $this->assign('multipleChoiceTotalScore',$data['duo'] * $data['duo-fen']);
        //判断
        $this->assign('descriPtive',$exams['pan']['info']);
        $this->assign('descriPtiveChoiceSum',$data['pan']);
        $this->assign('descriPtiveChoiceScore',$data['pan-fen']);
        $this->assign('descriPtiveChoiceTotalScore',$data['pan'] * $data['pan-fen']);

        //问答
        $this->assign('wd',$exams['jian']['info']);
        $this->assign('wdSum',$data['jian']);
        $this->assign('wdScore',$data['jian-fen']);
        $this->assign('wdTotalScore',$data['jian'] * $data['jian-fen']);

        $this->assign('examname',$data['examname']);
        $this->assign('examcate',$cate[0]['cat_name']);
        $this->assign('totalScore',$exams['totalScore']);
        $this->display();
    }

    /**
     * 智能组卷-表单处理
     * @return [type] [description]
     */
    public function examHandle(){
        $model = D('ResourcesManage');
        $data = session('formdata');session('formdata',null);
        
        $data['danids'] = session('dan');session('dan',null);
        $data['duoids'] = session('duo');session('duo',null);
        $data['panids'] = session('pan');session('pan',null);
        $data['jianids']= session('jian');session('jian',null);

        $info = $model->addExamination($data);
        
        if($info){
            $this->ajaxReturn(array('status'=>1));
        }else{
            $this->ajaxReturn(array('status'=>0));
        }
    }

    /**
     * 试题管理-列表
     * @return [type] [description]
     */
    public function examination(){
        $model = D('ResourcesManage');
        $data = $model->examList();
		
		//试题库
		$res = D('QuestionBank')->index();
        $where['auditing'] = 1;
        $where['status'] = 1;
        $specifiedUser = D('IsolationData')->specifiedUser();
        $where['auth_user_id'] = array('in',$specifiedUser);
        
        $allCourse= D('Course')->getAllCourse($where);
        $course_name = D('Course')->getAllCourse($where,'id,course_name');
        $search = $this->getcourse();

        $this->assign('search',$search);
        $this->assign('list',$data['list']);
        $this->assign('page',$data['page']);
        $this->assign('title',$data['title']);
        $this->assign('type',$data['type']);
        $this->assign('course',$data['course']);
        $this->assign('course_name',$course_name);
        $this->assign('allCourse',$allCourse);
		
		//试题库ID
		$this->assign('id',I('get.id'));
		$this->assign('res',$res);//试题库信息
        $this->display();
    }

    /**
     * 试题详情
     * @return [type] [description]
     */
    public function examDetails(){
        $id = I('post.id');
        $data = D('ResourcesManage')->examDetails($id);

        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 添加试题
     */
    public function addQuestion(){
        if(IS_POST){
            if(I('post.title') == ''){
                $this->ajaxReturn(array('status'=>0,'info'=>'请输入试题描述'));
            }
            if(I('post.analysis') == ''){
                $this->ajaxReturn(array('status'=>0,'info'=>'请输入试题解析'));
            }

            $data = $this->optionsHandle(I('post.'));
            $data['ctime'] = time();
            $data['creater_id'] = $_SESSION['user']['id'];
            $data['title'] = strip_tags($data['title']);
            $data['right_option'] = trim(str_replace('，',',',$data['right_option']));
            $data['keywords'] = trim(str_replace('，',',',$data['keywords']));
			$data['question_bank_id'] = I('get.id');
			
            $model = D('ResourcesManage');
			
			$isset = $model->issetExamination($data);
			if($isset){
				$this->ajaxReturn(array('status'=>0,'info'=>'该题目已存在'));
			}
			
			switch($data['classification']){
				case 1:
					if(!in_array($data['right_option'],array('A','B','C','D'))){
						$this->ajaxReturn(array('status'=>0,'info'=>'请输入A/B/C/D其中一项'));
					}
					break;
				case 2:
                    $arr = explode($data['right_option']);
                    foreach($arr as $k=>$v){
                        if(!in_array($v,array('A','B','C','D','E'))){
                            $this->ajaxReturn(array('status'=>0,'info'=>'请输入A/B/C/D/E其中一项或多项'));
                        }
                    }
                    break;
				case 3:
					$data['optiona'] = '对';
					$data['optionb'] = '错';
					if(!in_array($data['right_option'],array('A','B'))){
						$this->ajaxReturn(array('status'=>0,'info'=>'请输入A/B其中一项'));
					}
					break;
			}
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('examination_item');
				$data['belongcourse'] = $data['belongcourse'];
				$data['optiona'] = $data['optiona'];
				$data['optionb'] = $data['optionb'];
				$data['optionc'] = $data['optionc'];
				$data['optiond'] = $data['optiond'];
				$data['optione'] = $data['optione'];
			}
			
            $res = $model->addQuestion($data);
            if($res){
                $this->ajaxReturn(array('status'=>1,'url'=>U('examination',array('id'=>I('get.id')))));
            }else{
                $this->ajaxReturn(array('status'=>0));
            }
        }
        //课程
        $where['auditing'] = 1;
        $where['status'] = 1;
        $specifiedUser = D('IsolationData')->specifiedUser();
        $where['auth_user_id'] = array('in',$specifiedUser);
        
        $course= D('Course')->getAllCourse($where);
        $this->assign('course',$course);
        $this->display();
    }

    public function optionsHandle($data){
        foreach($data['optiona'] as $k=>$v){
            if($v != ''){
                $data['optiona'] = $v;
            }
        }
        foreach($data['optionb'] as $k=>$v){
            if($v != ''){
                $data['optionb'] = $v;
            }
        }
        foreach($data['optionc'] as $k=>$v){
            if($v != ''){
                $data['optionc'] = $v;
            }
        }
        foreach($data['optiond'] as $k=>$v){
            if($v != ''){
                $data['optiond'] = $v;
            }
        }
        foreach($data['right_option'] as $k=>$v){
            if($v != ''){
                $data['right_option'] = $v;
            }
        }
        return $data;
    }

    /*
     * 删除试题
     */
    public function delQuestion(){
        $post = (I('post.id'));
        $res = M('Examination_item')->where(array('id'=>array('in',$post)))->delete();
        if($res){
           $this->ajaxReturn(array('status'=>1));
        }else{
           $this->ajaxReturn(array('status'=>0));
        }
    }

    /*
     * 下载试题模板
     */
    public function downloadQuestion(){
        $uploadpath="./Upload/exam/";
        $saveName="question.xls";//文件保存名
        $showName="question.xlsx";//文件原名
        $filename=$uploadpath.$saveName;//完整文件名（路径加名字）
        file_download($filename);
    }

    /*
     * 导入试题
     */
    public function importQuestion(){
        if(IS_POST){
            $course = I('post.belongcourse');
			$question_bank_id = I('post.bank_id');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 300*1024*1024 ;// 设置附件上传大小
            $upload->exts = array('xls','xlsx');// 设置附件上传类型
            $upload->rootPath = './Upload/'; // 设置附件上传根目录
            $upload->savePath = 'excel/'; // 设置附件上传（子）目录
            $upload->autoSub = true;
            $upload->subName = '';
            // 上传文件
            $info = $upload->upload();

            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功
                foreach($info as $v){
                    $file_path1 = './Upload/'.$v['savepath'];
                    $file_path2 = $v['savename'];
                }
                $file = $file_path1.$file_path2;
                if(file_exists($file)){
                    $list = D('ResourcesManage')->uploadQuestion($file);
					if($list === false){
						$this->error('模板格式错误',U('examination',array('id'=>I('get.id'))));
					}
					
                    $count = count($list);
                    $errNum = 0;
                    foreach($list as $k => $v){
                        $isset = M('examination_item')
								->where(array('title'=>$v['title'],'question_bank_id'=>$question_bank_id))
								->find();
                        if($isset){//题目重复
                            $errNum += 1;
                            continue;
                        }
                        if($v['classification'] == 1){
                            if(empty($v['title'])||empty($v['optiona'])||empty($v['optionb'])||empty($v['optionc'])||empty($v['optiond'])||empty($v['right_option'])){
                                $errNum += 1;
                                continue;
                            }
                        }else if($v['classification'] == 2){
                            if(empty($v['title'])||empty($v['optiona'])||empty($v['optionb'])||empty($v['optionc'])||empty($v['optiond'])||empty($v['right_option'])){
                                $errNum += 1;
                                continue;
                            }
                        }else if($v['classification'] == 3){
                            if(empty($v['title'])||empty($v['optiona'])||empty($v['optionb'])||empty($v['right_option'])){
                                $errNum += 1;
                                continue;
                            }
                        }else if($v['classification'] == 4){
                            if(empty($v['title'])||empty($v['right_option'])){
                                $errNum += 1;
                                continue;
                            }
                        }
                        $msg['title'] = (string)$v['title'];
                        $msg['optiona'] = (string)$v['optiona'];
                        $msg['optionb'] = (string)$v['optionb'];
                        $msg['optionc'] = (string)$v['optionc'];
                        $msg['optiond'] = (string)$v['optiond'];
                        $msg['optione'] = (string)$v['optione'];
                        $msg['creater_id'] = $_SESSION['user']['id'];
                        $msg['right_option'] = (string)strtoupper($v['right_option']);
                        $msg['classification'] = $v['classification'];
                        $msg['keywords'] = (string)$v['keywords'];
                        $msg['ctime'] = time();
                        $msg['belongcourse'] = $course;
                        $msg['analysis'] = (string)$v['analysis'];
						$msg['question_bank_id'] = $question_bank_id;

                        $msg['right_option'] = trim(str_replace('，',',',$msg['right_option']));
                        $msg['keywords'] = trim(str_replace('，',',',$msg['keywords']));
						
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$msg['id'] = getNextId('examination_item');
						}
						
                        $res = M('ExaminationItem')->add($msg);
                        if(!$res){
                            $errNum += 1;
                        }
                    }
                    $this->success('导入成功,本次总共导入'.$count.'条,导入失败'.$errNum.'条',U('examination',array('id'=>$question_bank_id)));
                }
            }
        }else{
            $this->error('非法数据提交',U('examination'));
        }
    
    }

    /**
     * 模糊搜索所属课程
     * @return [type] [description]
     */
    public function getcourse(){
        $where['auditing'] = 1;
        $where['status'] = 1;
        $course= D('Course')->getAllCourse($where);
        foreach($course as $k=>$v){
            $str .= "'".$v['course_name']."',";
        }
        return '['.rtrim($str,',').']';
    }

    /**
     * 导入问卷
     */

    public function add_classify(){

        $item = M("SurveyCategory")->select();
        $this->assign('list',$item);

        $this->display();
    }
	
	/**
	 * 根据试题库ID获取各题型的数量
	 */
	public function getNumsByBankid(){
		$res = D('QuestionBank')->getNumsByBankid(I('post.id'));
		echo json_encode($res);
	}


}