<?php

namespace Admin\Controller;

use Common\Controller\AdminBaseController;

class CourseController extends AdminBaseController
{
    /**
     *课程新增展示页
     */
    public function courseAdd()
    {
        $category = $this->getCategory();
        $lecturer = $this->getLecturer();
        $this->assign('category', $category);
        $this->assign('lecturer', $lecturer);
        $this->display('addcourse');
    }

    /**
     *课程编辑页面
     */
    public function courseEdit()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('页面不存在');
        }
        $course = D('Course');
        $detail = $course->detail($id);
        $category = $this->getCategory();
        $lecturer = $this->getLecturer();
        $this->assign('category', $category);
        $this->assign('lecturer', $lecturer);
        $this->assign('detail', $detail);
        $this->display('course/addcourse');
    }

    /**
     *课程新增或修改
     */
    public function update()
    {
        if (IS_POST) {
            $course = D('Course');
            $res = $course->update();
            if ($res) {
                $this->success('成功');
            }

        }

    }

    /**
     *获取课程分类数据
     */
    public function getCategory()
    {
        $category = M('course_category');
        $result = $category->select();
        return $result;
    }

    /**
     *获取讲师列表
     */
    public function getLecturer()
    {
        $lecturer = M('lecturer');
        $result = $lecturer->select();
        return $result;
    }

    /**
     *添加章节
     */
    public function addChapter()
    {
        $name = I('post.name');
        $src = I('post.src');
        $chapter = M('course_chapter');
        $data = [
            'name' => $name,
            'src' => $src
        ];
        $id = $chapter->add($data);
        $new = [
            'id' => $id,
            'name' => $name,
            'src' => $src
        ];
        $this->ajaxReturn($new, 'json');
    }

    /**
     *删除章节
     */
    public function delChapter()
    {
        if (IS_AJAX) {
            $id = I('post.id');
            $chapter = M('course_chapter');
            $res = $chapter->delete($id);
            if ($res) {
                echo '200';
            } else {
                echo '300';
            }
        }
    }


    /**
     *课程分类详情页
     */
    public function courseCategory()
    {
        $list = M('course_category');
        $category = $list->select();

        $catage = tree($category);
        $new = [];
        for ($i = 0; $i < count($catage) / 2; $i++) {
            $new[$i]['cat_name'] = $catage[2 * $i];
            $new[$i]['id'] = $catage[2 * $i + 1]['id'];
        }

        $categoryDefault = [];
        foreach ($category as $k => $v) {
            $categoryDefault[$v['id']] = $v['pid'];
        }
        $this->assign('categoryDefault', $categoryDefault);
        $this->assign('catage', $new);
        $this->display('category');
    }

    /**
     *课程分类编辑
     */
    public function tree()
    {
        if (IS_AJAX) {
            echo '1';
        }
        if (IS_GET) {
            $id = I('get.id');
        }
        $list = M('course_category');
        $category = $list->select();
        $pid = ' ';
        $name = ' ';
        foreach ($category as $k => $v) {
            if ($v['id'] == $id) {
                $pid = $v['pid'];
                $name = $v['cat_name'];
            }
        }
        $catage = tree($category);
        $new = [];
        for ($i = 0; $i < count($catage) / 2; $i++) {
            $new[$i]['cat_name'] = $catage[2 * $i];
            $new[$i]['id'] = $catage[2 * $i + 1]['id'];
        }
        $this->assign('pid', $pid);
        $this->assign('name', $name);
        $this->assign('catage', $new);
        $this->display('editcategory');

    }

    /**
     *删除分类
     */
    public function delCategory()
    {
        if (IS_GET) {
            $id = I('get.id');
            $category = M('course_category');
            if ($category->delete($id)) {
                $this->success('删除成功');
            } else {
                $this->error('分类不存在');
            }
        }
    }

    /**
     *增加分类
     */
    public function addCategory()
    {
        if (IS_AJAX) {
            $pid = I('post.pid');
            $name = I('post.name');
            $data = [
                'pid' => $pid,
                'cat_name' => $name
            ];
            $category = M('course_category');
            if ($category->add($data)) {
                echo '成功';
                exit;
            }
        } else {
            echo "请求错误";
        }
    }

    /*我的课程*/

    public function index()
    {

        $total_page = $this->total_page;

        $data = D("Course")->index($total_page);

        $this->assign($data);
        $this->display();

    }

    /*查看课程详情*/

    public function courseDetail()
    {
        $course_id = I("get.id") ? I("get.id") : C("config.course_id");
        $course_way = I('get.course_way') ? I('get.course_way') : C("config.course_way");
        $judge = D("CourseRecord")->judge($course_id);
        if (!$judge) {//如果我的课程记录表没有该条数据则是显示‘加入我的课程’按钮
            $link = "<a href='#' onclick='add_Mycourse();'  id='aMyCourse'   type='button' class='btn btn-primary btn-sm pull-right'> <i class='fa fa-tags mr5' aria-hidden='true'></i>加入我的课程</a>";
        } else {
            $link = "<a href='#' onclick=''  id='aMyCourse'   type='button' class='btn btn-primary btn-sm pull-right'> <i class='fa fa-tags mr5' aria-hidden='true'></i>学习中</a>";
        }
        if ($course_way == "1") { //1代表面授课，0代表线上课程
            $courseDetail = D("CourseDetail")->detail($course_id);
            $articles = D("CourseArticle")->getArticles($course_id);
            $studentComment = D("CourseComment")->comment($_SESSION['user']['id'], $course_id);
            $lecturerComment = D("LecturerComment")->comment($_SESSION['user']['id'], $courseDetail['lecturer_id']);
            $this->assign("link", $link);
            $this->assign("success", C("config.success"));
            $this->assign("currentTime", date("Y-m-d H:i:s", time()));
            $this->assign("lecturerComment", $lecturerComment);
            $this->assign("studentComment", $studentComment);
            $this->assign("articles", $articles["list"]);
            $this->assign("courseDetail", $courseDetail);
            $this->display("courseDetail");

        } else {
            $courseDetail = D("CourseDetail")->detail($course_id);
            $courseDetail = D("CourseDetail")->detail($course_id);
            $articles = D("CourseArticle")->getArticles($course_id);
            $video = D("CourseArticle")->isVideo($course_id);
            $courseCategory = D("courseCategory")->getCategory($courseDetail['course_cat_id']);
            $studentComment = D("CourseComment")->comment($_SESSION['user']['id'], $course_id);
            $this->assign("link", $link);
            $this->assign("success", C("config.success"));
            $this->assign("show", $articles['show']);
            $this->assign("video", $video);
            $this->assign("articles", $articles['list']);
            $this->assign("studentComment", $studentComment);
            $this->assign("courseCategory", $courseCategory);
            $this->assign("courseDetail", $courseDetail);
            $this->display("onlineCourse");
        }

    }

    /*查看课程详情*/

    public function teachCourseDetail()
    {
        $course_id = I("get.id") ? I("get.id") : C("config.course_id");
        $course_way = I('get.course_way') ? I('get.course_way') : C("config.course_way");
        $course_id = I("id") ? I("id") : '';
        $course_name = I("course_name");
        $project_id = I('pid');
        $start_time = I("start_time");
        // $start_time = date('Y-m-d H:i:s', $start_time);
        $end_time = I("end_time");
        // echo $end_time;die;
        // echo  $start_time;die;

        //    print_r($config);exit;

        $courseDetail = D("CourseDetail")->teachDetail($course_id, $project_id);  //获取课授课课程详情的内容信息，介绍目标大纲，，，
        $articles = D("CourseArticle")->getArticles($course_id);  //获取课授课课程对应课程文章章节
        // $studentComment = D("CourseComment")->comment($_SESSION['user']['id'], $course_id);  //课程评价
        $lecturerComment = D("LecturerComment")->comment($_SESSION['user']['id'], $courseDetail['lecturer_id']); //讲师评价
        //    $this->assign("link",$link);
        //传递授课列表页的参数，让点击“考勤统计”时作为传递参数调用
        $this->assign("id", $course_id);
        $this->assign("pid", $project_id);
        $this->assign("start_time", $start_time);
        $this->assign("end_time", $end_time);
        $this->assign("course_name", $course_name);
        //一些课程详情的模板输出
        $this->assign("success", C("config.success"));
        $this->assign("success", C("config.success"));
        $this->assign("currentTime", date("Y-m-d H:i:s", time()));
        $this->assign("lecturerComment", $lecturerComment);
        $this->assign("studentComment", $studentComment);
        $this->assign("articles", $articles["list"]);
        $this->assign("courseDetail", $courseDetail);
        $this->display("courseDetail");

    }

    /***
     * 加入我的课程
     */
    public function join()
    {
        $data = I("get.");
        $data["user_id"] = $_SESSION["user"]["id"];
        $res = D("CourseRecord")->accretion($data);
        
        $this->ajaxReturn($res,'json');
    }


    /**
     * 我的课程
     */
	public function myCourse(){

        $data = D("Course")->myCourse();

        $this->assign($data);
        $this->display();

    }

    /**
     * 我的课程 - 公开课
     */
    public function openCourse(){

        $data = D("Course")->myCourse();

        $this->assign($data);
        $this->display();

    }
}