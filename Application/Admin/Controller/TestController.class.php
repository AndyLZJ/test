<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;

/**
 * 培训管理控制器
 */
class TestController extends AdminBaseController
{
    /**
     *培训项目
     */
    public function index()
    {

        $this->display();
    }

    /*新建培训项目*/

    public function create()
    {
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
     * 返回项目
     */
    public function creatc_edit(){

        $users = D('UserCompany')->getCompany($_SESSION['user']['id']);
        $participates = D("DesignatedPersonnel")->getDpersonnels($_SESSION['user']['id']);//获取可能指定参与的人员

        //获取缓存
        $admin_project = F('admin_project');
        $admin_project['project_covers'] = $admin_project['filename'];

        //获取编辑指定人员
        if(!empty($admin_project['user_id'])){
            $designee = D('Manage')->creatc_edit($admin_project['user_id']);
        }else{
            $designee = array();
        }

        $this->assign("participates", $participates);
        $this->assign("project", $admin_project);
        $this->assign("users", $users);
        $this->assign("designee", $designee);
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

            $this->form_save();

        }else{
            $this->redirect('/Admin/Manage/curriculum');

        }


    }

    /**
     * 提交 - 生成培训项目
     */
    public function form_save(){

        $results = D("Manage")->form_save();

        if($results){

            $this->success('编辑成功！',"/Admin/Manage/index");

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
        $responsible = D("PjCourse")->getOrganization();

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
        $this->assign('typeid',$typeid);
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


        $this->assign("project_id",$id);
        $this->assign("participates", $participates);
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



}
