<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 后台考试管理控制器
 */
class TestManageController extends AdminBaseController{

    /**
     * 首页-考试列表
     * @return [type] [description]
     */
    public function index(){
        $total_page = $this->total_page;
        $model = D('TestManage');
        
        $list = $model->getTestList();



        foreach($list['res'] as $k=>$item){

            $pass_num = 0;

            $res = $model->getTestuser_ids($item['id']);

            foreach($res as $v){

                if($item['pass_line'] <= $v['total_score']){
                    $pass_num++;
                }

            }

            $pass_rate = ($pass_num/count($res)) * 100;

            $list['res'][$k] = $item;
            $list['res'][$k]['pass_rate'] = $pass_rate;
        }


        $list['type'] = $list['type']=='' ? '' : $list['type'] + 1;
        $list['status'] = $list['status']=='' ? '' : $list['status'] + 1;

        $this->assign('list',$list['res']);
        $this->assign('page',$list['page']);
        $this->assign('test_name',$list['test_name']);
        $this->assign('type',$list['type']);
        $this->assign('status',$list['status']);
        $this->assign('audit_status',$list['audit_status']);
        $this->display();
    }

    /**
     * 添加考试
     */
    public function addTest(){
        $model = D('TestManage');

        $total_page = $this->total_page;

        if(IS_POST){
            $data = I('post.');

            $res = $model->addTest($data);
            if ($res) {
                $this->success('提交成功',U('index'));
            } else {
                $this->error('提交失败');
            }
        }else{
            $staff=D('AdminTissue')->index($total_page);
			$testInfo = $model->getTestInfoById(I('get.id'));
            $designee = $model->getUinfo(I('get.id'));
            $examinationInfo = $model->getEinfo(I('get.id'));
			
			
            $this->assign('staff',$staff);
            $this->assign('id',I('get.id'));
            $this->assign('designee',$designee);
            $this->assign('examinationInfo',$examinationInfo);
            $this->assign('testInfo',$testInfo);
            $this->display();
        }

    }

    /**
     * 指定部门/人员
     */
    public function tissue(){
        
        $data = D('TestManage')->getCompanys();      //所有分公司

        /*
        $job = D('Manage')->getJobs();              //所有岗位
        $tag = D('Manage')->getTags();              //所有标签

        $this->assign("company", $company['data']);
        $this->assign("job", $company['job']);
        $this->assign("tag", $company['tag']);
        $this->assign("jobs", $job);
        $this->assign("tags", $tag);
        $this->assign("all", $company['count_all']);
        */

        $this->assign($data);
        $this->display();
    }

    /**
     * 添加试卷
     */
    public function addExamination(){
        $model = D('TestManage');
        $examination = $model->getAllExmination();
        // dump($examination);
        $cate = $model->getAllExminationCate();
        // dump($cate);
        $this->assign('examination',$examination['res']);
        $this->assign('test_name',$examination['test_name']);
        $this->assign('test_cate',$examination['test_cate']);
        $this->assign('cate',$cate);
        $this->display();
    }

    /**
     * 预览考试(试卷)
     * @return [type] [description]
     */
    //线上--查看试卷，线下--链接考试结果页面
    public function preview(){
        $get = I('get.');//'id'=>$v['id'],'type'=>$v['type'],'name'=>$v['name'])

        if($get['type'] == 1){//线下
            $res = D('TestManage')->getTestuser_ids($get['id']);
            $examination = D('TestManage')->getTestInfo($get['id']);
            $scoreInfo = D('TestManage')->getScoreInfo($get['id']);
            $attendance = D('ExamReport')->getNumByTid($get['id']);
            $attendance['que'] = $attendance['should'] - $attendance['real'];
            foreach($res as $k=>$v){
                $totalScore += $v['total_score'];
            }
            $avgScore = $totalScore / count($res);
            $this->assign('attendance',$attendance);
            $this->assign('avgScore',$avgScore);

            // dump($examination);
            $this->assign('examination',$examination);
            $this->assign('scoreInfo',$scoreInfo);
            $this->assign('data',$res);
            $this->assign('backUrl',U('index',array('audit_status'=>$get['audit_status'])));
            $this->display('lookresultoffline');
        }else{
            $examination = D('TestManage')->getTestInfo($get['id']);
            if($examination){
                $data = D('TestManage')->getExamDetail($examination['id']);
            }

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

            $this->assign('source','1');    //标识来源
            $this->assign('test_status',$get['status']);    //考试状态
            $this->assign('test_name',$get['name']);    //考试名称
            $this->assign('backUrl',U('index',array('audit_status'=>$get['audit_status'])));
            $this->display('lookexamination');

        }

    }

    /**
     * 查看考试结果
     * @return [type] [description]
     */
    public function lookResult(){
        $get = I('get.');
        $model = D('TestManage');
        $res = $model->getTestuser_ids($get['id']);
        $examination = $model->getTestInfo($get['id']);
        $scoreInfo = $model->getScoreInfo($get['id']);

        $attendance = D('ExamReport')->getNumByTid($get['id']);
        $attendance['que'] = $attendance['should'] - $attendance['real'];
        foreach($res as $k=>$v){
            $totalScore += $v['total_score'];
        }
        $avgScore = $totalScore / count($res);
        // dump($res);
        $this->assign('examination',$examination);
        $this->assign('attendance',$attendance);
        $this->assign('avgScore',$avgScore);
        $this->assign('scoreInfo',$scoreInfo);
        $this->assign('data',$res);
        $this->assign('backUrl',U('index',array('audit_status'=>$get['audit_status'])));
        if($get['type'] == 1){
            $this->display('lookresultoffline');
        }else{
            $this->display('lookresultonline');
        }
    }

    /**
     * 发布考试结果
     * @return [type] [description]
     */
    public function publish(){
        $data = I('post.');
        
        $model = D('TestManage');
        $res = $model->publish($data);
        if($res){
            $this->ajaxReturn(array('status'=>1));
        }else{
            $this->ajaxReturn(array('status'=>0));
        }
    }

    /**
     * 保存考试成绩
     * @return [type] [description]
     */
    public function saveScore(){
        $data = I('post.');
        $data['save'] = 1;
        
        $model = D('TestManage');
        $res = $model->saveScore($data);
        
        if($res){
            $this->success('保存成功','index');
        }else{
            $this->error('保存失败','index');
        }
    }

    /**
     * 查看考试结果
     * @return [type] [description]
     */
    public function checkExaminationAnswer(){
        $model = D('TestManage');
        $data = I('get.');//getExamDetail2  test_id examination_id id
        $res = D('ResourcesManage')->getExamDetail2($data['examination_id']);

        $uinfo = $model->getUserInfo($data['test_id'],$data['id']);
        $answerInfo = $model->getAnswerInfo($data['test_id'],$data['id'],$data['examination_id']);
        $wdscore = $model->getWdScore($data['test_id'],$data['id']);

        if(!$wdscore[0]['wdscore']){
            $wdscore = '';
        }
        
        //详情
        $this->assign('xhr',$res['detail']);
        $this->assign('uInfo',$uinfo);
        $this->assign('answerInfo',$answerInfo);
        $this->assign('test_id',$data['test_id']);  //考试id
        $this->assign('examination_id',$data['examination_id']);//试卷id
        $this->assign('user_id',$data['id']);
        $this->assign('wdscore',$wdscore);
        //单选
        $this->assign('singleChoice',$res['singleChoice']);
        $this->assign('singleChoiceSum',$res['singleChoiceSum']);
        $this->assign('singleChoiceTotalScore',$res['singleChoiceTotalScore']);
        //多选
        $this->assign('multipleChoice',$res['multipleChoice']);
        $this->assign('multipleChoiceSum',$res['multipleChoiceSum']);
        $this->assign('multipleChoiceTotalScore',$res['multipleChoiceTotalScore']);
        //判断
        $this->assign('descriPtive',$res['descriPtive']);
        $this->assign('descriPtiveChoiceSum',$res['descriPtiveChoiceSum']);
        $this->assign('descriPtiveChoiceTotalScore',$res['descriPtiveChoiceTotalScore']);

        //问答
        $this->assign('wd',$res['wd']);
        $this->assign('wdSum',$res['wdSum']);
        $this->assign('wdTotalScore',$res['wdTotalScore']);        
        $this->display();
    }

    /**
     * 下载分数导入模板
     * @return [type] [description]
     */
    public function downloadQuestion(){
        $uploadpath="./Upload/exam/";
        $saveName="score.xls";//文件保存名
        $showName="score.xlsx";//文件原名
        $filename=$uploadpath.$saveName;//完整文件名（路径加名字）
        file_download($filename);
    }

    /*
     * 导入成绩
     */
    public function importScore(){
        if(IS_POST){
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
                    $list = D('TestManage')->uploadScore($file);
                    
                    $count = count($list);
                    $num = 0;
                    foreach($list as $k => $v){
                        //名字、手机号匹配
                        $user_id = D('TestManage')->getuser_idByPhone($v['phone']);
                        
                        if(!$user_id || ($user_id['username'] != $v['name'])){
                            $num += 1;
                            continue;
                        }

                        $user_id = $user_id['id'];

                        //think_test_user_rel 考试-员工关联表
                        $relTableData[$k]['user_id'] = $user_id;
                        $relTableData[$k]['test_id'] = I('post.test_id');

                        //think_exam_score考试-分数表
                        $examScoreData[$k]['user_id'] = $user_id;
                        $examScoreData[$k]['total_score'] = $v['score'];
                        $examScoreData[$k]['exam_id'] = I('post.examination_id');
                        $examScoreData[$k]['test_id'] = I('post.test_id');

                        //think_examination_attendance考勤表
                        $examAttendanceData[$k]['user_id'] = $user_id;
                        $examAttendanceData[$k]['test_id'] = I('post.examination_id');
                        $examAttendanceData[$k]['status'] = 1;
                        $examAttendanceData[$k]['examination_id'] = I('post.test_id');

                    }
                    $res1 = M('test_user_rel')->addall($relTableData);
                    $res2 = M('exam_score')->addall($examScoreData);
                    $res3 = M('examination_attendance')->addall($examAttendanceData);

                    if($res1 && $res2 && $res3){
                        $this->success('导入成功,本次导入'.$count.'条,失败'.$num.'条');
                    }else{
                        $this->error('导入失败,本次导入'.$count.'条,失败'.$num.'条');
                    }
                }
            }
        }else{
            $this->error('非法数据提交');
        }
    
    }

    /**
     * 保存人工阅卷得分
     * @return [type] [description]
     */
    public function saveAudit(){
        $model = D('TestManage');
        $res = $model->saveAudit();
        if($res){
            $this->ajaxReturn(array('status'=>1));
        }else{
            $this->ajaxReturn(array('status'=>0));
        }
    }

    /**
     * 导出考试成绩
     * @return [type] [description]
     */
    public function export(){
        $model = D('TestManage');
        $data = $model->all();
        $xlsName  = $data['name'] . "考试结果-".date('Y-m-d H:i:s');

        array_unshift($data['data'],array('用户名称','手机号码','公司名称','岗位名称','得分'));
        create_xls($data['data'],$xlsName);
    }
}
