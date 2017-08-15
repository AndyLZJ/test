<?php

namespace Common\Model;

use Common\Model\BaseModel;

class CourseModel extends BaseModel
{
    public $_validate = [
        ['course_name', 'require', '课程名不能为空'],
        ['course_cat_id', 'require', '请选择分类'],
        ['course_time', 'require', '课程时长不能为空']
    ];

    /**
     *数据新增或者修改操作
     */
    public function update()
    {
        $data = $this->create();
        $data['user_id'] = $_SESSION['user']['id'];
        $data['course_cover'] = json_encode($data['course_cover']);
        if (empty($data)) {
            return false;
        }
        $detail = D('CourseDetail');
        //课程新增
        if (empty($data['id'])) {

            $id = $this->add($data);
            $detail = $detail->update($id);
            if (empty($detail)) {
                $this->delete($id);
            }
        } else {
            //课程修改
            $id = $data['id'];
            $this->save($data);
            $detail = $detail->update($id);

        }


        return true;
    }

    /**
     *获取详情页
     */
    public function detail($id)
    {
        $total = $this->find($id);

        if (!is_array($total)) {
            $this->error = '文章不存在';
            return false;
        }

        $info = M('course_detail');
        $info_detail = $info->find($id);
        $data = array_merge($total, $info_detail);
        return $data;
    }

    //获取章节
    public function getChapter($id)
    {
        if (empty($id)) {
            $this->error = '章节不存在';
            return false;
        }

        $chapter = M('course_chapter');
        $chapter_detail = $chapter->find($id);

        return $chapter;
    }

    /*公开课程*/

    public function PublicCourse($condition)
    {

        $map["is_public"] = 1;

        if (!empty($condition)) {

            $condition = "%" . $condition . "%";

            $map['course_name'] = array('like', $condition);
        }

        $count = $this->where($map)->count();

        $Page = new \Think\Page($count, 12);

        $Page->type = 1;

        $Page->setConfig('prev', '上一页');

        $Page->setConfig('next', '下一页');

        $show = $Page->show();

        $list = $this->where($map)->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $categorys = M("CourseCategory");

        $courseRecord = M("CourseRecord");

        $lecturer = M("Lecturer");

        for ($i = 0; $i < count($list); $i++) {

            $arr['id'] = $list[$i]['course_cat_id'];

            $airs["id"] = $list[$i]['lecturer'];

            $lecturers = $lecturer->where($airs)->find();

            $category = $categorys->where($arr)->find();

            $records = $courseRecord->where($maps)->find();

            $list[$i]["cat_name"] = $category["cat_name"];

            $list[$i]["percent"] = round(($records["recently_lookup_time"] / $list[$i]["course_time"]) * 100) . "%";

            $list[$i]["recently_lookup_time"] = $records["recently_lookup_time"];

            $list[$i]["lecturer"] = $lecturers["name"];

        }

        $assign = array(
            "show" => $show,
            "list" => $list
        );

        return $assign;

    }

    //获取所有公开课程
    public function getAll()
    {

        import("Org.Nx.AjaxPage");

        $limitRows = 4;

        $map["status"] = 1;

        $map["auditing"] = 1;

        $courseCategoryTable = $this->tablePrefix . "course_category";

        $count = $this->where($map)->count();

        $p = new \AjaxPage($count, $limitRows, "curriculum");

        $p->setConfig('theme', '%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

        $sql = "select a.id,a.course_name,a.course_way,a.course_cat_id,a.is_public,b.cat_name from " . $this->trueTableName . " a left join " . $courseCategoryTable . " b on a.course_cat_id=b.id where a.status=1 and a.auditing=1 limit " . $p->firstRow . " , " . $p->listRows;

        $courses = $this->query($sql);

        $show = $p->show();

        $assign = array(
            "list" => $courses,
            "show" => $show
        );

        return $assign;
    }

    //组合条件搜索课程

    public function searchCourse($data)
    {

        import("Org.Nx.AjaxPage");

        $limitRows = 4;

        $map = array();

        $where = " a.status=1 and a.auditing=1";

        if ($data["course_way"] != 3) {

            $map["course_way"] = $data["course_way"];

            $where .= " and a.course_way= " . $data["course_way"];
        }

        if ($data["course_category"] != 0) {

            $map["course_cat_id"] = $data["course_category"];

            $where .= " and a.course_cat_id= " . $data["course_category"];


        }

        if ($data["course_name"] != "") {

            $datas["course_name"] = "%" . $data["course_name"] . "%";

            $map['course_name'] = array('like', $datas["course_name"]);

            $where .= " and a.course_name like " . "'%" . $data["course_name"] . "%'";
        }

        $map["status"] = 1;

        $map["auditing"] = 1;

        $courseCategoryTable = $this->tablePrefix . "course_category";

        $count = $this->where($map)->count();


        $p = new \AjaxPage($count, $limitRows, "curriculum");

        $p->setConfig('theme', '%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');


        $sql = "select a.id,a.course_name,a.course_way,a.course_cat_id,a.is_public,b.cat_name from " . $this->trueTableName . " a left join " . $courseCategoryTable . " b on a.course_cat_id=b.id where " . $where . " limit " . $p->firstRow . " , " . $p->listRows;

        $courses = $this->query($sql);

        $show = $p->show();

        $assign = array(
            "list" => $courses,
            "show" => $show
        );

        return $assign;

    }

    /***
     * 获取已经选定的课程
     **/

    public function getCourseSelect()
    {

        $arr["project_id"] = $_SESSION['project']['id'];

        $projectCourse = D("projectCourse")->where($arr)->find();

        $id = array_unique(explode(",", $projectCourse["course_id"]));

        $map["a.id"] = array("in", $id);

        $projectCourses = $this->field('a.id,a.course_name,a.course_way,a.credit,b.name,a.location')->alias('a')
            ->join(' LEFT JOIN __LECTURER__  as  b  on a.lecturer = b.id')
            ->where($map)
            ->select();
        return $projectCourses;

    }

    /***
     * 判断当前课程是否是我的课程
     **/

    public function isMy($lecturer, $courseId)
    {

        $map["id"] = $courseId;

        $map["course_way"] = 1;

        $course = $this->where($map)->find();

        if ($course["lecturer"] == $lecturer["id"]) {

            return true;
        }

        return false;

    }

    /***
     * 获取某项课程信息
     **/

    public function getOne($id)
    {

        $map["id"] = $id;
        $course = $this->where($map)->find();

        return $course;
    }

    /***
     * 获取指定项目的课程信息
     **/

    public function getCourseOneSelect($project_id)
    {

        $arr["project_id"] = $project_id;

        $projectCourse = D("projectCourse")->where($arr)->find();

        $id = array_unique(explode(",", $projectCourse["course_id"]));

        $map["a.id"] = array("in", $id);

        $projectCourses = $this->field('a.id,a.course_name,a.course_way,a.credit,b.name,a.location')->alias('a')
            ->join(' LEFT JOIN __LECTURER__  as  b  on a.lecturer = b.id')
            ->where($map)
            ->select();
        return $projectCourses;

    }


    /*获取资源库课程信息*/
    public function getCourses($arr)
    {
        $map["a.id"] = array("in", $arr);
        $courses = $this
            ->alias("a")
            ->field("a.*,b.name")
            ->join("left join __LECTURER__ b on a.lecturer=b.id")
            ->where($map)->order("a.id asc")->select();

        return $courses;
    }

    /**
     * 获取我的课程数据
     */
    public function index($total_page){

        $typeid = I('get.typeid') ? I('get.typeid') : 1;
        $start_page = I("get.p",0,'int');
        $keyword=I("get.keyword")?I("get.keyword"):"";

        //获取我的课程
        if($typeid == 1){

            $courseid = array();
            //获取指定人课程
            $conditions['b.user_id'] = array("eq",$_SESSION['user']['id']);

            $project_course = M("project_course a")->join("LEFT JOIN __DESIGNATED_PERSONNEL__ b ON a.project_id = b.project_id")->field("a.course_id")->where($conditions)->select();

            $my_course = M("course_record")->field("course_id")->where("user_id=".$_SESSION['user']['id'])->select();

            foreach($my_course as $course){
                $courseid[] = $course['course_id'];
            }

            foreach($project_course as $course){
                $courseid[] = $course['course_id'];
            }

            $where = array();
            $where['a.id'] = array("in",implode(",",$courseid));
            $where['a.status'] = array("eq",1);
            $where['a.auditing'] = array("eq",1);
            if(!empty($keyword)){
                $where['_string']="(a.course_name like '%".$keyword."%')  OR (b.cat_name like '%".$keyword."%')";
            }
        }else{

            //获取已经过审核的项目
            $where['a.is_public'] = array("eq",1);
            $where['a.status'] = array("eq",1);
            $where['a.auditing'] = array("eq",1);

            if(!empty($keyword)){
                $where['_string']="(a.course_name like '%".$keyword."%')  OR (b.cat_name like '%".$keyword."%')";
            }

        }

        $results = M("course a")->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")->field("a.*,b.cat_name")->where($where)->page($start_page,$total_page)->select();

        $count = M("course a")->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id")->field("a.*,b.cat_name")->where($where)->count();

        //输出分页
        $show=$this->pageClass($count,$total_page);


        $data = array(
            'typeid'=>$typeid,
            'page' => $show,
            'list' => $results,
            'keyword'=>$keyword
        );

        return $data;


    }

    /**
     * 我的课程
     */
    public function myCourse(){

        $courseid = I('get.courseid') ? I('get.courseid') : 1;

        $where['a.id'] = array("eq",$courseid);
            
        //获取课程数据
        $results = M("course a")->join("LEFT JOIN __COURSE_CATEGORY__ b ON a.course_cat_id = b.id LEFT JOIN __COURSE_DETAIL__ c ON a.id = c.id")->field("a.*,b.cat_name,c.*")->where($where)->find();

        preg_match("/<p>(.*)?<\/p>/i",'<p>天天向上</p> ',$course_aim);

        $results['chapter'] = json_decode($results['chapter'],true);

        $results['course_aim'] = $course_aim[0];

        return $results;
    }

    /**
     * 获取课程信息
     * @return [type] [description]
     */
    public function getAllCourse($where,$field=''){
        return $this->where($where)->field($field)->order('create_time desc')->select();
    }
    

}