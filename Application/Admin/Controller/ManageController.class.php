<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;

/**
 * 培训管理控制器
 */
class ManageController extends AdminBaseController
{
    /**
     *培训项目
     */
    public function index()
    {
        $total_page = 12;

        //修改过期状态
        D('Manage')->upstate($total_page);

        $data = D('Manage')->getProjectlist($total_page);

        $this->assign($data);
        $this->display();
    }

    /*新建培训项目*/

    public function create(){
        $users = D('UserCompany')->getCompany($_SESSION['user']['id']);
        $participates = D("DesignatedPersonnel")->getDpersonnels($_SESSION['user']['id']);//获取可能指定参与的人员

        //清空项目快速缓存
        F('admin_project',NULL);
        F('examination',NULL);
        F('course',NULL);
        F('research',NULL);

        $this->assign("participates", $participates);
        $this->assign("users", $users);
        $this->display();
    }

    /**
     * 指定人员
     * @return [type] [description]
     */
    public function users(){
        $company = D('Manage')->getCompanys();      //所有分公司
        $job = D('Manage')->getJobs();              //所有岗位
        $tag = D('Manage')->getTags();              //所有标签

        $this->assign("company", $company['data']);
        $this->assign("job", $company['job']);
        $this->assign("tag", $company['tag']);
        $this->assign("jobs", $job);
        $this->assign("tags", $tag);
        $this->assign("all", $company['count_all']);
        $this->display();
    }

    /**
     * 返回项目
     */
    public function creatc_edit(){

        $project_id = I("get.project_id");

        $users = D('UserCompany')->getCompany($_SESSION['user']['id']);
        $participates = D("DesignatedPersonnel")->getDpersonnels($_SESSION['user']['id']);//获取可能指定参与的人员

        //获取缓存
        $admin_project = F('admin_project');
        
        $admin_project['project_covers'] = $admin_project['filename'];

        //获取编辑指定人员
        if(!empty($admin_project['user_id'])){
            $designee = D('Manage')->creatc_edit($admin_project['user_id'],1);
        }else{
            $designee = array();
        }

        //获取指定部门
        if(!empty($admin_project['tissue_id'])){
            $department = D('Manage')->creatc_edit($admin_project['tissue_id'],2);
        }else{
            $department = array();
        }
		
		$this->assign('user',count($admin_project['user_id']));
		$this->assign("name", $admin_project['box']);
		$this->assign("amount", $admin_project['amount']);
        $this->assign("participates", $participates);
        $this->assign("project", $admin_project);
        $this->assign("users", $users);
        $this->assign("designee", $designee);
        $this->assign("department", $department);
        $this->assign("project_id", $project_id);
        $this->display('create');
    }

    //项目提交
    public function submit(){

        $post = I("post.");

        //清空项目快速缓存
        F('admin_project',NULL);
        F('examination',NULL);
        F('course',NULL);
        F('research',NULL);

        //获取缓存
        F('admin_project',$post);

        //如果存在编辑ID,写入缓存
        if(!empty($post['project_id'])){
            D('Manage')->edit_item($post['project_id']);

        }

        if($post['typeid'] == 1){
            $project_data = F('admin_project');

            if(!empty($project_data['project_name'])){
                $this->form_save();
            }else{
                $this->error('数据异常,请重新提交');
            }
        }else{
            $this->redirect('/Admin/Manage/curriculum');

        }


    }

    /**
     * 提交 - 生成培训项目
     */
    public function form_save(){

        $model = C('URL_MODEL');

        $results = D("Manage")->form_save();

        if($results){

            if($model == 1){

                $jump = U('/Admin/Manage/index');

            }else{

                $jump = "/Admin/Manage/index";

            }

            $this->success('编辑成功！',$jump);

        }else{

            $this->error("提交失败");
        }

    }

    /**
     * 项目保存为 - 草稿箱
     */
    public function drafts(){

        $post = I("post.");

        //清空项目快速缓存
        F('admin_project',NULL);
        F('examination',NULL);
        F('course',NULL);
        F('research',NULL);

        //获取缓存
        F('admin_project',$post);

        $_POST['typeid'] = 1;

        $results = D("Manage")->form_save();

    }


    //新建培训课程
    public function curriculum()
    {
        //获取编辑类型
        $classid = I("get.classid");

        //获取项目缓存
        $admin_project = F('admin_project');

        //获取当前项目考试快速缓存
        $examination = F('examination');

        //获取负责人
        $responsible = D("Manage")->getOrganization();

        //获取当前项目课程缓存
        $course = F('course');

        //获取当前项目调研缓存
        $research = F('research');

        $this->assign("research",$research);
        $this->assign("course",$course);
        $this->assign("examination",$examination);
        $this->assign("responsible",$responsible);
        $this->assign("project_start_time",$admin_project['start_time']);
        $this->assign("project_end_time",$admin_project['end_time']);
        $this->assign("project_id",$admin_project['project_id']);
        $this->assign("classid",$classid);
        $this->display("curriculum");

    }

    public function search()
    {
        $data = $_POST ? $_POST : $_GET;
        $_GET["p"] = $data["p"];
        if ($data["type"] == 1) {
            $courses = D("Course")->searchCourse($data);
            $array = array("code" => 1, "data" => $courses);
            $this->ajaxReturn($array);
        } elseif ($data["type"] == 2) {
            $examinations = D("Examination")->searchExamination($data);
            $array = array("code" => 1, "data" => $examinations);
            $this->ajaxReturn($array);
        } elseif ($data["type"] == 3) {
            $surveys = D("survey")->searchSurvey($data);
            $array = array("code" => 1, "data" => $surveys);
            $this->ajaxReturn($array);
        }


    }

    /***
     *
     * 添加课程刷新
     **/

    public function commit()
    {
        $id = I("get.id");
        $_SESSION["course_id"] = $id;
        $courses = D("Course")->getCourses(explode(",", $id));

        $data = array("project_id" => $_SESSION["project"]["id"], "course_id" => $id);
        foreach ($courses as $k => $v) {
            $data["specific_information"][$v['id']] = $v;
        }
        if (!empty($id)) {
            D("ProjectCourse")->adds($data);
            $this->curriculum();
        }
    }

    /**
     * 项目 - 添加考试
     */
    public function testCommit()
    {

        $id = I("post.id");

        $examination = D("Examination")->getExamination(explode(",", $id));

        //合并新添加试卷
        $results = D("Manage")->mergeTest($examination,2);

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 项目 - 添加线下考试
     */
    public function testCommitline(){

        $examination_data = F('examination');

        $i = 1;

        foreach($examination_data as $data){
            if(empty($data['id'])){
                $i++;
            }
        }

        $examination = array(
                array(
                "id"=>0,
                "test_name"=>"",
                "test_score"=>"",
                "start_time"=>date("Y-m-d H:i:s",time()),
                "end_time"=>date("Y-m-d H:i:s",time()),
                "test_mode"=>"",
                "cacheid"=>$i,
            )
        );

        $results = D("Manage")->mergeTest($examination,2);

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 项目 - 添加课程
     */
    public function courseCommit()
    {
        $id = I("post.id");
        $Course_data = D("Manage")->getCourse(explode(",", $id));
        $results = D("Manage")->mergeTest($Course_data,1);
        $data = array(
            "status"=> $results,
        );
        $this->ajaxReturn($data,'json');

    }

    /**
     * 项目 - 添加调研
     */
    public function researchCommit(){
        $id = I("post.id");
        $Research_data = D("Manage")->getResearch(explode(",", $id));
        $results = D("Manage")->mergeTest($Research_data,3);
        $data = array(
            "status"=> $results,
        );
        $this->ajaxReturn($data,'json');
    }

    /**
     * 修改项目考试信息
     */
    public function updateTest(){
        $results =D('Manage')->updateTest();
        $data = array(
            "status"=> $results,
        );
        $this->ajaxReturn($data,'json');
    }

    /**
     * 删除项目考试
     */
    public function DeleteTest(){

        $results =D('Manage')->DeleteTest();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 删除创建项目
     */
    public function delItems(){

        $results =D('Manage')->delItems();

        if($results){

            $this->success('删除成功！',"/Admin/Manage/index");

        }else{

            $this->error("提交失败");
        }

    }


    /***
     * 添加问卷刷新
     */
    public function surveyCommit()
    {
        $id = I("get.id");
        $_SESSION["survey_id"] = $id;
        $examination = D("Survey")->getSurvey(explode(",", $id));
        $data = array("project_id" => $_SESSION["project"]["id"], "survey_id" => $id);
        foreach ($examination as $k => $v) {
            $data["specific_information"][$v['id']] = $v;
        }

        if (!empty($id)) {
            D("ProjectSurvey")->adds($data);
            $this->curriculum($id);
        }
    }

    /***
     * 删除项目下面的课程
     */
    public function deleteCourse()
    {
        $id[] = I("get.id");

        D("ProjectCourse")->deleteCourse($id);
        $this->curriculum();
    }

    /***
     * 删除项目下面的考试
     */
    public function deleteExamination()
    {
        $id[] = I("get.id");

        D("ProjectExamination")->deleteExam($id);
        $this->curriculum();
    }

    /***
     * 删除项目下面的调研
     */
    public function deleteSurvey()
    {
        $id[] = I("get.id");
        D("ProjectSurvey")->delete($id);
        $this->curriculum();
    }

    /***
     * 培训项目详情
     */

    public function detail()
    {
        $typeid = I("get.typeid");
        $items = D("Manage")->detail();

        $this->assign($items);

        if($items['project']['is_public'] == 0){
            $department_name = array("不公开");
        }else{
            foreach($items['department'] as $item){
                $department_name[] = $item['name'];
            }
        }

        $department = implode(",",$department_name);

        $this->assign('typeid',$typeid);
        $this->assign('department',$department);
        $this->display();

    }

    /***
     * 删除培训项目
     */
    public function delete()
    {
        $id = I("get.id");
        $res = D("AdminProject")->deleteData($id);
        $CourseResult = D("ProjectCourse")->deleteData($id);
        $ExaminationResult = D("ProjectExamination")->deleteData($id);
		
		//培训项目预算选项
		$budget = M('project_budget')->where(array('project_id'=>$id))->delete();
        if ($res) {
            $this->redirect('Manage/index', array(), 2, '成功删除，页面跳转中...');
        } else {
            $this->error('删除失败');
        }
    }

    /***
     * 显示编辑项目
     */
    public function edit()
    {
        $id = I("get.id");

        $items = D("Manage")->detail();
        $participates = D("DesignatedPersonnel")->getDpersonnels($_SESSION['user']['id']);//获取可能指定参与的人员

        $designee_total = count($items['designee']) ? count($items['designee']) : 0;

        $this->assign("project_id",$id);
        $this->assign("participates", $participates);
        $this->assign("designee_total", $designee_total);
		// $this->assign("budget", $items['budget']);	//项目预算

        foreach($items['budget'] as $k=>$v){
            $name[] = $v['option_name'];
            $amount[] = $v['amount'];
        }
        
        $this->assign('name',$name);
        $this->assign('amount',$amount);
        $this->assign($items);

        $this->display("create");
    }

    /***
     * 处理编辑项目数据
     */
    public function refresh()
    {
        $data = I("post.");
        $arr = array("project_name", "project_id", "class_name", "start_time", "end_time", "project_description", "project_covers", "project_budget", "is_public", "population");
        $dpersonnels = D("DesignatedPersonnel");
        $map = array();
        foreach ($data as $k => $v) {
            if (!in_array($k, $arr)) {
                $res = $dpersonnels->updateDpersonnels($data["project_id"], $v);
            } else {
                $map[$k] = $v;
            }
        }
        $result = D("AdminProject")->update($map);
        if (!empty($_FILES["project_covers"]["name"])) {
            //图片上传设置
            $config = array(
                'maxSize' => 3145728,
                'savePath' => '/',
                'rootPath' => './Upload/photo',
                'saveName' => array('uniqid', ''),
                'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub' => true,
                'subName' => array('date', 'Ymd')
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            $images = $upload->upload();
            if (!$images) {
                $this->error($upload->getError());
            } else {
                $project_covers = "/Upload/photo" . $images['project_covers']['savepath'] . $images['project_covers']['savename'];
                $photo = "./Upload/photo" . $images['project_covers']['savepath'] . $images['project_covers']['savename'];
                //保存图片路径
                $photosPath = "./Upload/photo/" . $images['project_covers']['savename'];
                $image = new \Think\Image();
                $image->open("$photo");
                $image->thumb(271, 188)->save("$photosPath");
                $maps["project_covers"] = substr_replace($photosPath, "", 0, 1);
                $maps["project_id"] = $data["project_id"];
                $result = D("AdminProject")->update($maps);
            }
        }
        $_SESSION["project"]["id"] = $data["project_id"];
        $this->edit();
    }

    /*课程，考试，调研详情编辑*/
    public function detailEdit()
    {
        $project_id = I("get.id");

        //获取编辑类型
        $classid = I("get.classid");

        $items = D("Manage")->detail();

        if(!empty($project_id)){

            $items['project']['project_id'] = $project_id;
            $items['project']['filename'] = $items['project']['project_covers'];

            //重新写入缓存
            F('admin_project',$items['project']);
            F('examination',$items['examination']);
            F('course',$items['course']);
            F('research',$items['survey']);

            D('Manage')->edit_item($project_id);
        }

        $this->redirect('admin/Manage/curriculum',array('classid'=>$classid));
    }

    /***
     * 培训项目存为草稿
     */
    public function draught()
    {
        $map["project_id"] = I("get.id");
        $map["type"] = 1;
        $result = D("AdminProject")->update($map);
        if ($result) {
            $_SESSION["project"]["id"] = $map["project_id"];
            $this->index();
        }
    }

    /*保存课程，考试，调研信息*/
    public function conserve()
    {

        $arr = I("post.");

        $map["project_id"] = $_SESSION['project']['id'];
        if ($arr["type"] == "course") {
            $projectCourse = D("ProjectCourse")->getOne($map["project_id"]);
            $projectCourse["specific_information"] = json_decode($projectCourse["specific_information"], true);
            $specificInformation = array("id" => $arr["course_id"], "course_name" => $arr["course_name"], "course_way" => $arr["course_way"], "name" => $arr["lecture"], "start_time" => $arr["start_time"], "end_time" => $arr["end_time"], "credit" => $arr["credit"], "location" => $arr["location"]);
            $projectCourse['specific_information'][$arr['course_id']] = $specificInformation;
            $projectCourse["specific_information"] = json_encode($projectCourse['specific_information']);
            $res = D("ProjectCourse")->saveData($projectCourse);
            if ($res) {
                $this->success("课程信息保存成功");
            } else {
                $this->error("课程信息保存失败");
            }
        } elseif ($arr["type"] == "exam") {

            $projectExamination = D("ProjectExamination")->getOne($map["project_id"]);
            $projectExamination["specific_information"] = json_decode($projectExamination["specific_information"], true);


            $specificInformation = array("id" => $arr["test_id"], "test_name" => $arr["test_name"], "test_mode" => $arr["test_mode"], "principal" => $arr["principal"], "start_time" => $arr["start_time"], "end_time" => $arr["end_time"], "test_score" => $arr["test_score"]);
            $projectExamination['specific_information'][$arr['test_id']] = $specificInformation;

            $rows = json_encode($projectExamination["specific_information"]);

            $where['project_id'] = array("eq",$map["project_id"]);

            $res = D("ProjectExamination")->where($where)->setField("specific_information",$rows);

            if ($res) {
                $this->success("考试信息保存成功");
            } else {
                $this->error("考试信息保存失败");
            }
        } elseif ($arr["type"] == "survey") {
            $projectSurvey = D("ProjectSurvey")->getOne($map["project_id"]);
            $projectSurvey["specific_information"] = json_decode($projectSurvey["specific_information"], true);
            $specificInformation = array("id" => $arr["survey_id"], "survey_name" => $arr["survey_name"], "principal" => $arr["principal"], "start_time" => $arr["start_time"], "end_time" => $arr["end_time"], "survey_score" => $arr["survey_score"]);
            $projectSurvey['specific_information'][$arr['survey_id']] = $specificInformation;
            $projectSurvey["specific_information"] = json_encode($projectSurvey["specific_information"]);
            $res = D("ProjectSurvey")->saveData($projectSurvey);
            if ($res) {
                $this->success("调研信息保存成功");
            } else {
                $this->error("调研信息保存失败");
            }

        }
    }

    /**
     * 查看项目总结
     */
    public function viewitem(){

        $setting=C('UPLOAD_SITEIMG_QINIU');

        $items = D("Manage")->detail();

        $number = count($items['designee']);

        $survey_list = $examination_list = $course_list = array();

        //查看课程考勤情况
        foreach($items['course'] as $k=>$course){
            $course_list[$k] = $course;
            $course_list[$k]['attendance'] = D("Manage")->project_course($course['project_id'],$course['course_id']);
        }


        //查看考试情况
        foreach($items['examination'] as $k=>$examination){
            $examination_list[$k] = $examination;
            $examination_list[$k]['attendance'] = D("Manage")->project_examination($examination['project_id'],$examination['test_id']);

            $qualifiedrate = D("Manage")->getQualifiedrate($examination['project_id'],$examination['test_id'],$examination['pass_line']);

            $examination_list[$k]['pass_rate'] = $qualifiedrate['pass_rate'];

            $examination_list[$k]['average'] = $qualifiedrate['average'];
        }

        //查看问卷情况
        foreach($items['survey'] as $k=>$survey){

            $survey_list[$k] = $survey;
            $survey_list[$k]['attendance'] = D("Manage")->project_survey($survey['project_id'],$survey['survey_id']);

        }

        //查看项目总结描述
        $summary_list = D("Manage")->project_summary();

        $enclosure_list = unserialize($summary_list['project_summary']['enclosure']);

        //预算费用列表
        $project_budget = $summary_list['project_budget'];

        $enclosure = array();

        foreach($enclosure_list as $k=>$url){
            $enclosure[$k]['name'] = urldecode(substr($url,strrpos($url,"/")+1));
            $enclosure[$k]['url'] = $url;
        }

        $this->assign("course_list",$course_list);
        $this->assign("examination_list",$examination_list);
        $this->assign("survey_list",$survey_list);
        $this->assign("summary_list",$summary_list['project_summary']);
        $this->assign("enclosure",$enclosure);
        $this->assign("number",$number);
        $this->assign("project_budget",$project_budget);
        $this->assign($items);
        $this->assign("domain",$setting['driverConfig']['domain']);
        $this->display();
    }

    /**
     * 项目总结编辑
     */
    public function viewedit(){

        $data = D("Manage")->viewedit();

        if ($data) {
            $this->success("操作成功");
        } else {
            $this->error("操作失败");
        }

    }

    /**
     * 查看项目总结 - 上传文档
     */
    public function upload(){

        $upToken =D('Manage')->upload();

        $this->ajaxReturn($upToken,'json');

    }

    /**
     * 检查视频是否转码成功
     */
    public function pfopStatus(){

        $url =D('RsCourse')->pfopStatus();

        echo $url;
        exit;
    }

    /**
     * 项目总结 - 导出Word
     */
    public function exportWord(){

        D('Manage')->exportWord();

    }

    /**
     * 项目总结 - 导出PDF
     */
    public function exportPdf(){

        D('Manage')->exportPdf();

    }

    /**
     * 培训项目 - 指定人员
     */
    public function personnel(){

        //获取组织架构信息
        $treeInfo =D('Manage')->personnel();

        $this->assign($treeInfo);

        $this->display();

    }

    /**
     * 培训项目 - 指定部门
     */
    public function department(){



        $treeInfo =D('Manage')->department();



        $this->assign("tree_items",$treeInfo);

        $this->display();

    }

    /**
     * 删除培训项目
     */

    public function del_project(){

        $results = D('Manage')->del_project();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }


}
