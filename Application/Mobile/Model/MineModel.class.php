<?php
namespace Mobile\Model;

use Think\Model;

class MineModel extends CommonModel {
   
    protected $tablePrefix = 'think_';
    protected $tableName = 'designated_personnel';
    static  $sum;

    protected $_validate = array(
        array('title', 'empty', '标题不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('title', '5,100', '用户名长度超出限制', Model::EXISTS_VALIDATE, 'length'),
        array('content', 'empty', '内容不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('content', '5,1000', '内容长度超出限制', Model::EXISTS_VALIDATE, 'length'),
        array('phone', '/1[34578]{1}\d{9}$/', '请输入正确的手机号格式', Model::EXISTS_VALIDATE, 'regex'),
        array('password', 'empty', '密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('confirm', 'empty', '确认密码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('password', '6,20', '密码长度为6-20位', Model::EXISTS_VALIDATE, 'length'),
        array('password', 'isPassword', '只允许输入6-20位由 数字、字母、下划线组成的密码', Model::EXISTS_VALIDATE, 'callback'),
        array('phone', 'empty', '手机号码不能为空', Model::EXISTS_VALIDATE, 'function'),
        array('phone', 'is_numeric', '手机号码必须为数字', Model::EXISTS_VALIDATE, 'function')
    );

    /**
     * 初始化
     */
    function __construct(){
        parent::__construct();
    }


        /*
        * 我的课程
        * @param typeId 1必修   2选修  默认为1必修
        */

    public function myCourse($typeId,$page,$uid){
            $pageNum = 15;
            //查询该用户所在的组织
            $item = M('Tissue_group_access')->field('tissue_id,job_id')->where(array('uid'=>$uid))->find();
            $tissue_id = $item['tissue_id'];
            $job_id = $item['job_id'];
            $tiaoJian = array(
                'tissue_id' => $tissue_id,
                'job_id' => $job_id,
                'year' => date('Y')
            );

        //获取已经过审核的项目,指定人学习的课程
        if($typeId == 1){//必修
            $where['a.uid'] = array("eq",$uid);
            $where['b.id'] = array("gt",0);
        $ret = M("designated_personnel as a")
            ->join('LEFT JOIN __PROJECT_COURSE__ as b ON a.project_id = b.project_id LEFT JOIN __COURSE__ as c ON b.course_id = c.id LEFT JOIN __COURSE_DETAIL__ as d ON c.id = d.id')
            ->where($where)
            ->field('b.project_id,b.course_id,b.start_time,b.end_time,c.course_name,c.course_cover,d.course_intro')
            ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();
            //计算当年所有月份的必修总学时
            $tiaoJian['typeid'] = 0;
            $rets = M('Tool_learning')->field('january,february,march,april,may,june,july,august,september,october,november,december')->where($tiaoJian)->find();
            $_total = 0;
            foreach($rets as $value){
             $_total += $value;
           }

            //计算当年必修的总学时
            $con['create_time'] = array('like','%'.date('Y').'%');
            $con['user_id'] = $uid;
            $con['typeid'] = 4;
            $sum = M('Center_study')->where($con)->sum('hours');
            if(!$sum){
                $sum = 0;
            }
            $info = array();

            foreach($ret as $i=>$v){

                $start_time = strtotime($v['start_time']) - time();//大于0表示待考试
                $end_time = strtotime($v['end_time']) - time();//小于0表示逾期（已结束）

                    if($start_time > 0){
                        $info[$i]['course_id'] = $v['course_id'];
                        $info[$i]['project_id'] = $v['project_id'];
                        $info[$i]['course_name'] = $v['course_name'];
                        $info[$i]['course_cover'] = $v['course_cover'];
                        $info[$i]['course_intro'] = $v['course_intro'];
                        $info[$i]['start_time'] = $v['start_time'];
                        $info[$i]['end_time'] = $v['end_time'];
                        $info[$i]['_status'] = 1;//未开始
                        $info[$i]['typeId'] = $typeId;
                    }

                    if($end_time > 0  && $start_time < 0){
                        $info[$i]['course_id'] = $v['course_id'];
                        $info[$i]['project_id'] = $v['project_id'];
                        $info[$i]['course_name'] = $v['course_name'];
                        $info[$i]['course_cover'] = $v['course_cover'];
                        $info[$i]['course_intro'] = $v['course_intro'];
                        $info[$i]['start_time'] = $v['start_time'];
                        $info[$i]['end_time'] = $v['end_time'];
                        $info[$i]['_status'] = 2;//学习中
                        $info[$i]['typeId'] = $typeId;
                    }

                    if($end_time < 0){
                        $info[$i]['course_id'] = $v['course_id'];
                        $info[$i]['project_id'] = $v['project_id'];
                        $info[$i]['course_name'] = $v['course_name'];
                        $info[$i]['course_cover'] = $v['course_cover'];
                        $info[$i]['course_intro'] = $v['course_intro'];
                        $info[$i]['start_time'] = $v['start_time'];
                        $info[$i]['end_time'] = $v['end_time'];
                        $info[$i]['_status'] = 3;//已结束
                        $info[$i]['typeId'] = $typeId;

                    }

                }
            $data['_total'] = $_total = $_total ? $_total : 0;
            $data['total'] = $sum = $sum ? $sum : 0;
            $data['list'] = $info;

        }else if($typeId == 2){//选修

            //获取在公开课列表加入到我的课程的课程列表
            $condition['b.id'] = array("gt",0);
            $condition['a.uid'] = $uid;
            //$condition['a.type'] = 2;
            /*$res = M("Course_record as d")
                ->join("LEFT JOIN __ATTENDANCE__ a ON d.uid = a.uid AND d.course_id = a.course_id LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __COURSE_DETAIL__ c ON b.id = c.id ")
                ->where($condition)
                ->field('a.status,a.state,b.id,b.course_name,b.course_cover,c.course_intro')
                ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();*/
            //查询课程的学习状态
            $res = M("Course_record as a")
                ->join("LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __COURSE_DETAIL__ c ON b.id = c.id ")
                ->where($condition)
                ->field('b.chapter,b.id,b.course_name,b.course_cover,c.course_intro')
                ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();

            //计算当年所有月份的选修总学时
            $tiaoJian['typeid'] = 1;
            $rets = M('Tool_learning')->field('january,february,march,april,may,june,july,august,september,october,november,december')->where($tiaoJian)->find();
            $_total = 0;
            foreach($rets as $value){
                $_total += $value;
            }

            //计算当年必修的总学时
            $con['create_time'] = array('like','%'.date('Y').'%');
            $con['user_id'] = $uid;
            $con['typeid'] = 5;
            $sum = M('Center_study')->where($con)->sum('hours');
            $info = array();
            foreach($res as $k=>$v){
                $chapter = json_decode($v["chapter"], true);//总章节
                $where['project_id'] = 0;
                $where['course_id'] = $v['id'];
                $where['uid'] = $uid;
                //查询已学习的章节
                $isStudy = M("Course_chapter")->field("id")->where($where)->select();
               if(!$isStudy){//未开始
                   $info[$k]['course_id'] = $v['id'];
                   $info[$k]['course_name'] = $v['course_name'];
                   $info[$k]['course_cover'] = $v['course_cover'];
                   $info[$k]['course_intro'] = $v['course_intro'];
                   $info[$k]['_status'] = 1;
                   $info[$k]['typeId'] = $typeId;
               }else if(count($isStudy) == count($chapter)){//已结束
                   $info[$k]['course_id'] = $v['id'];
                   $info[$k]['course_name'] = $v['course_name'];
                   $info[$k]['course_cover'] = $v['course_cover'];
                   $info[$k]['course_intro'] = $v['course_intro'];
                   $info[$k]['_status'] = 3;
                   $info[$k]['typeId'] = $typeId;
               }else if(count($isStudy) < count($chapter)){//学习中
                   $info[$k]['course_id'] = $v['id'];
                   $info[$k]['course_name'] = $v['course_name'];
                   $info[$k]['course_cover'] = $v['course_cover'];
                   $info[$k]['course_intro'] = $v['course_intro'];
                   $info[$k]['_status'] = 2;
                   $info[$k]['typeId'] = $typeId;
               }
            }

            $data['_total'] = $_total = $_total ? $_total : 0;
            $data['total'] = $sum;
            $data['list'] = $info;
        }
        return $data;
    }


    /*
     * 我的-导航页面
     */
    public function mine($userId){
        //查询用户名，头像，简介，总积分，总学时
        $userInfo = M('Users')->field('id,username,avatar,personalized_signature')->where(array('id'=>$userId))->find();
        $useJiFen = M('Integration_record')->where(array('uid'=>$userId))->sum('score');
        $useJiFen = $useJiFen ? $useJiFen : 0;
        $userStudyTime = M('Center_study')->where(array('user_id'=>$userId,'typeid'=>array('in','4,5')))->sum('hours');

        $userStudyTime = $userStudyTime ? $userStudyTime : 0;
        //把学时分钟转化为小时
        $userInfo['userStudyTime'] = round(($userStudyTime/60),1)."小时";
        $userInfo['useJiFen'] = $useJiFen;
        if($userInfo){
            return $this->success(1000,'获取数据成功',$userInfo);
        }else{
            return $this->error(1030,'暂无数据返回');
        }
    }
    /**
     * 我的课程-课程详情-简介(选修)
     * @$course_id  课程id
     *
     */
    public function getBriefIntroduction($course_id,$uid){
        if($course_id == ''){
            return $this->error(1023,'缺少必要参数');die();
        }
        $condition['a.course_id'] = $course_id;
        $condition['b.status'] = 1;//通过审核的课程
       // $condition['c.type'] = 0;//内部讲师
        $condition['a.uid'] = $uid;
        $info = M('CourseRecord a')
            ->field('a.course_id,b.course_name,b.course_way,b.media_src,b.click_count,b.lecturer as lecturer_id,b.lecturer_name,c.name,c.desc,e.course_intro')
            ->join('LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __LECTURER__ c ON b.lecturer = c.id  LEFT JOIN __COURSE_DETAIL__ e ON a.course_id = e.id')
            ->where($condition)->find();
           //计算该课程对应的讲师的综合评价等级
        $where['lecturer_evaluation'] = array('EGT',0);
        $where['lecturer_id'] = $info['lecturer_id'];
        $where['course_id'] = $info['course_id'];
        $colligateComment = M('Colligate_comment')->where($where)->AVG('lecturer_evaluation');
        if(!empty($info['media_src'])){
            //判断是否是课件
            if(strstr($info['media_src'],'.html')){
                $info['srcType'] = 1;//课件
            }else{
                $info['srcType'] = 2;//普通视频
            }

        }else{
            $http = I("server.HTTP_HOST");
            $info['srcImage'] = 'http://'.$http.'/Upload/20170411/58ec391c147ed.png';
        }



            $info['lecturerColligateComment'] = ceil($colligateComment);

        if($info){
            return $this->success(1000,'获取数据成功',$info);
        }else{
            return $this->error(1030,'暂无数据返回');die();
        }
    }


    /**
     * 我的课程-课程详情-简介(必修)
     * @$course_id  课程id
     * @$pid  课程关联项目id
     */
    public function getCourseDetails($course_id,$pid,$uid){
        if($course_id == '' || $pid == ''){
            return $this->error(1023,'缺少必要参数');die();
        }
        $condition['a.project_id'] = $pid;
        $condition['a.course_id'] = $course_id;
        $condition['b.status'] = 1;//通过审核的课程
        //$condition['c.type'] = 0;//内部讲师

        $info = M('Project_course a')
            ->field('a.course_id,b.course_name,b.course_way,b.media_src,b.click_count,b.lecturer as lecturer_id,b.lecturer_name,c.name,c.desc,e.course_intro')
            ->join('LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __LECTURER__ c ON b.lecturer = c.id  LEFT JOIN __COURSE_DETAIL__ e ON a.course_id = e.id')
            ->where($condition)->find();

        //计算该课程对应的讲师的综合评价等级
        $where['lecturer_evaluation'] = array('EGT',0);
        $where['lecturer_id'] = $info['lecturer_id'];
        $where['course_id'] = $info['course_id'];
        $colligateComment = M('Colligate_comment')
            ->where($where)
            ->AVG('lecturer_evaluation');
        $info['lecturerColligateComment'] = ceil($colligateComment);
        if($info){
            return $this->success(1000,'获取数据成功',$info);
        }else{
            return $this->error(1030,'暂无数据返回');die();
        }
    }

    /**
     * 获取我的课程-课程详情-目录
     * $course_id  课程id
     */
    public function getCourseList($course_id,$project_id,$uid){
        $project_id  =  $project_id ? $project_id : 0;
        if($course_id != ''){
            $where['b.status'] = 1;
            $where['a.uid'] = $uid;
            $where['a.course_id'] = $course_id;
            $info = M('CourseRecord a')->field('b.id,b.chapter')->join('LEFT JOIN __COURSE__ b ON a.course_id = b.id')->where($where)->find();

            //$info['chapter'] = json_decode($info['chapter']);
            $info['chapter'] = json_decode($info['chapter'],true);
            // dump($info['chapter']);exit;
            foreach ($info['chapter'] as $key=>$value){
                $fileType = substr($value["src"], -5, 5);
                if(strstr($fileType, ".mp4") || strstr($fileType, ".avi") || strstr($fileType, ".flv") || strstr($fileType, ".mp3") || strstr($fileType, ".wmv")){

                    $info['chapter'][$key]['type'] = 1;//视频，音频
                    if(strstr($fileType, ".mp4")){
                        $info['chapter'][$key]['style'] = 'mp4';//mp4
                    }
                    if(strstr($fileType, ".avi")){
                        $info['chapter'][$key]['style'] = 'avi';//avi
                    }
                    if(strstr($fileType, ".flv")){
                        $info['chapter'][$key]['style'] = 'flv';//flv
                    }
                    if(strstr($fileType, ".mp3")){
                        $info['chapter'][$key]['style'] = 'mp3';//mp3
                    }
                    if(strstr($fileType, ".wmv")){
                        $info['chapter'][$key]['style'] = 'wmv';//wmv
                    }

                }elseif(strstr($fileType, ".ppt") || strstr($fileType, ".pptx") || strstr($fileType, ".png") || strstr($fileType, ".jpeg") || strstr($fileType, ".jpg")){

                    $info['chapter'][$key]['type'] = 2;//ppt
                    if(strstr($fileType, ".ppt")){
                        $info['chapter'][$key]['style'] = 'ppt';//ppt
                    }
                    if(strstr($fileType, ".pptx")){
                        $info['chapter'][$key]['style'] = 'pptx';//pptx
                    }

                    if(strstr($fileType, ".png")){
                        $info['chapter'][$key]['style'] = 'png';//png
                    }
                    if(strstr($fileType, ".jpeg")){
                        $info['chapter'][$key]['style'] = 'jpeg';//jpeg
                    }
                    if(strstr($fileType, ".jpg")){
                        $info['chapter'][$key]['style'] = 'jpg';//jpeg
                    }

                }elseif(strstr($fileType, ".doc") || strstr($fileType, ".docx") || strstr($fileType, ".xls") || strstr($fileType, ".xlsx") || strstr($fileType, ".pdf")){

                    $info['chapter'][$key]['type'] = 2;//文档类型
                    if(strstr($fileType, ".doc")){
                        $info['chapter'][$key]['style'] = 'doc';//doc
                    }
                    if(strstr($fileType, ".docx")){
                        $info['chapter'][$key]['style'] = 'docx';//docx
                    }
                    if(strstr($fileType, ".xls")){
                        $info['chapter'][$key]['style'] = 'xls';//xls
                    }
                    if(strstr($fileType, ".xlsx")){
                        $info['chapter'][$key]['style'] = 'xlsx';//xlsx
                    }
                    if(strstr($fileType, ".pdf")){
                        $info['chapter'][$key]['style'] = 'pdf';//pdf
                    }

                }else{//mdzz
                    $info['chapter'][$key]['type'] = 3;//课件
                    $info['chapter'][$key]['style'] = 'mp4';//课件
                }

                //进度
                $info['chapter'][$key]['id'] = $course_id;//课程id
                $info['chapter'][$key]['pid'] = $project_id;//项目id
                $info['chapter'][$key]['chapter_id'] = $key + 1;//章节id
                $info['chapter'][$key]['uid'] = $uid;//用户id
               //获取文件大小
                $info['chapter'][$key]['src'] = $value['src'];//课件地址
                $header_array = get_headers($value['src'], true);
                $size = $header_array['Content-Length'];
                $info['chapter'][$key]['size'] = $size;//章节视频大小
                $info['chapter'][$key]['name'] = $value['name'];//章节名称
                //$info['chapter'][$key]["time_percent"] = 0;
                $cwhere["uid"] = $uid;
                $cwhere["course_id"] = $course_id;
                $cwhere["project_id"] = $project_id = $project_id ? $project_id : 0;
                $cwhere["name"] = $value["name"];
                $chapter = M("course_chapter")->where($cwhere)->limit(1)->find();
                if($chapter){
                    if($info['chapter'][$key]['type'] == 1){
                        //百分比进度
                        $info['chapter'][$key]["time_percent"] = $chapter["time_percent"];
                        //时长
                        $info['chapter'][$key]["timeLen"] = $chapter["timelen"];
                    }else if($info['chapter'][$key]['type'] == 2){

                        $info['chapter'][$key]["time_percent"] = 100;
                        $info['chapter'][$key]["timeLen"] = 100;

                    }else if($info['chapter'][$key]['type'] == 3){
                        $info['chapter'][$key]["time_percent"] = 0;
                        $info['chapter'][$key]["timeLen"] = 0;
                    }
                }else{
                    if($info['chapter'][$key]['type'] == 1){
                        //百分比进度
                        $info['chapter'][$key]["time_percent"] = 0;
                        //时长
                        $info['chapter'][$key]["timeLen"] = 0;
                    }else if($info['chapter'][$key]['type'] == 2){

                        $info['chapter'][$key]["time_percent"] = 0;
                        $info['chapter'][$key]["timeLen"] = 0;

                    }else if($info['chapter'][$key]['type'] == 3){
                        $info['chapter'][$key]["time_percent"] = 0;
                        $info['chapter'][$key]["timeLen"] = 0;
                    }
                }
            }
        }else{
            $where['b.status'] = 1;
            $where['a.uid'] = $uid;
            $where['a.project_id'] = $project_id;
            $info = M('CourseRecord a')->field('b.id,b.chapter')->join('LEFT JOIN __COURSE__ b ON a.course_id = b.id')->where($where)->find();

        }
        if($info['chapter']){
           return $this->success(1000,'获取数据成功',$info['chapter']);
       }else{
           return $this->error(1000,'暂无数据返回');
       }
    }


    /**
     * 我的课程-课程详情-获取评论列表
     * @$course_id  课程id
     * @$userId   用户id
     * @$page    分页参数
     */
    public function getCourseComment($course_id,$userId,$page){

        $pageNum = 15;
        $start = ($page - 1) * $pageNum;
        $end = $pageNum;
        $comList = M("colligate_comment")
            ->where("course_id=".$course_id." AND pid=0 AND comment_content!=''")
            ->order("comment_time DESC")->limit($start, $end)->select();
        //dump($comList);exit;
        //获取课程综合评价和数量
        $condition['course_score'] = array('EGT',0);
        $condition['course_id'] = $course_id;
        $where['course_id'] = $course_id;
        $where['pid'] = array('eq',0);
        $where['comment_content'] = array('neq','');
        //课程的综合评分（平均值）
        $course_evaluation = M("Course_score")->where($condition)->AVG('course_score');
        //课程的评价数量
        $courseEvaluationCount = M("Colligate_comment")->where($where)->count('id');
        $course_evaluation = ceil($course_evaluation);
        //获取子评论/回复
        foreach ($comList as $key=>$value){
            $user = M("users")->field("username,avatar")->where("id=".$value["uid"])->limit(1)->select();
            $comList[$key]["username"] = $user[0]["username"];
            $comList[$key]["avatar"] = $user[0]["avatar"];
            $comList[$key]["comment_content"] = str_replace('&nbsp;','',strip_tags($value["comment_content"]));
            $zan = M("course_praise")->where("praise=1 AND id=".$value["id"])->count();
            $comList[$key]["zan"] = $zan;//总赞
            $zanStatus = M("course_praise")->where("id=".$value["id"]." AND uid=".$userId)->getField('praise');
            if($zanStatus == 0){
                $comList[$key]["zan_status"] = 0;//我是否赞过  1已赞 0未赞
            }else{
                $zanStatus = $zanStatus[0]["praise"] + 0;
                $comList[$key]["zan_status"] = 1;//我是否赞过  1已赞 0未赞
            }

            //是否可删除

            if($userId == $value["uid"]){

                $comList[$key]["del_status"] = 1;

            }else{
                $comList[$key]["del_status"] = 0;
            }

            $subList = array();
            $pids = self::getCommentChild($value["id"], $value["id"].",");
            $pids = substr($pids, 0, -1);
            if($pids){
                $sonCon = M("colligate_comment")
                    ->where("course_id=".$course_id." AND pid in (".$pids.")")->select();
                if($sonCon){
                    $userCache = array();
                    foreach ($sonCon as $sk=>$sv){
                        $subUser = M("users")->field("username,avatar")->where("id=".$sv["uid"])->limit(1)->select();
                        $sonCon[$sk]["username"] = $subUser[0]["username"];
                        $sonCon[$sk]["avatar"] = $subUser[0]["avatar"];
                        $sonCon[$sk]["comment_content"] = str_replace('&nbsp;','',strip_tags($sv["comment_content"]));
                        $userCache[$sv["id"]] = $subUser[0]["username"];

                        //是否可删除
                        $sonCon[$sk]["del_status"] = 0;
                        if($userId == $sv["uid"]){
                            $sonCon[$sk]["del_status"] = 1;
                        }

                        if($sv["pid"] != $value["id"]){
                            $sonCon[$sk]["reply_user"] = $userCache[$sv["pid"]];
                        }
                    }
                    $comList[$key]["sub_list"] = $sonCon;
                }
                //统计所有子评论条数
                $comList[$key]["sub_total"] = count($sonCon);
            }
        }
        //查询该用户是否对该课程评分过，有is_mark=1,否则is_mark=0
        $mark = M('Course_score')->where(array('uid'=>$userId,'course_id'=>$course_id))->find();
        if($mark){
            $data['is_mark'] = 1;
        }else{
            $data['is_mark'] = 0;
        }
        $data['courseColligateEvaluation'] = $course_evaluation;
        $data['courseEvaluationCount'] = $courseEvaluationCount;
        $data['comment'] = $comList;
        if($comList){
            return $this->success(1000,'获取数据成功',$data);
        }else{
            return $this->error(1030,'暂无数据返回');
        }
    }

    /*
     * 获取回复评论（页面用于数据实时刷新）(互动消息课程)
     *
     */
    public function getChildComment($id,$type,$userId){

        if($id === ''){
            return $this->error(1023,'缺少回复评论id');
        }
        if($type == 1){
            //主评论
            //点击工作圈列表评论，传主评论id
            $data = M("Colligate_comment")->where(array("id" => $id))->limit(1)->find();
            //获取发布评论人的头像和用户名
            $user = M("Users")->field("username,avatar")->where(array('id' => $data["uid"]))->limit(1)->find();
            $comList["id"] = $data["id"];
            $comList["uid"] = $data["uid"];
            $comList["course_id"] = $data["course_id"];
            $comList["comment_content"] = str_replace('&nbsp;','',strip_tags($data["comment_content"]));
            $comList["comment_time"] = $data["comment_time"];
            $comList["pid"] = $data["pid"];
            $comList["username"] = $user["username"];
            $comList["avatar"] = $user["avatar"];
            $zan = M("Course_praise")->where("praise=1 AND id=".$data["id"])->count();
            $comList["zan"] = $zan;//总赞
            $zanStatus = M("Course_praise")->where("id=".$data["id"]." AND uid=".$data["uid"])->limit(1)->select();
            if(!$zanStatus){
                $zanStatus = 0;
            }else{
                $zanStatus = $zanStatus["praise"] + 0;
            }
            $comList["zan_status"] = $zanStatus;//我是否赞过  1已赞 0未赞

            //是否可删除
            $comList["del_status"] = 0;
            if($userId == $data["uid"]){
                $comList["del_status"] = 1;
            }

            $subList = array();
            $pids = self::getCommentChild($data["id"], $data["id"].",");
            $pids = substr($pids, 0, -1);

        }else{
            //接收子评论id，递归查找父级id
            $pid = M("Colligate_comment")->where(array("id" => $id))->limit(1)->getField('pid');

            //根据子类id查询第一级id
            $infoId = $this->findCourseParentId($pid);
            $info = M("Colligate_comment")->where(array("id" => $infoId))->limit(1)->find();
            if($info == null){
                return $this->error(1023,'评论id参数有误');
            }
            //获取子评论/回复
            //获取该子评论相对应的主评论人的头像和用户名
            $user = M("Users")->field("username,avatar")->where(array('id' => $info["uid"]))->limit(1)->find();
            $comList["id"] = $info["id"];
            $comList["uid"] = $info["uid"];
            $comList["comment_content"] = str_replace('&nbsp;','',strip_tags($info["comment_content"]));
            $comList["comment_time"] = $info["comment_time"];
            $comList["pid"] = $info["pid"];
            $comList["username"] = $user["username"];
            $comList["avatar"] = $user["avatar"];
            $zan = M("Course_praise")->where("praise=1 AND id=".$info["id"])->count();
            $comList["zan"] = $zan;//总赞

            $zanStatus = M("Course_praise")->where("id=".$info["id"]." AND uid=".$info["uid"])->limit(1)->select();
            if(!$zanStatus){
                $zanStatus = "0";
            }else{
                $zanStatus = $zanStatus["praise"] + 0;
            }
            $comList["zan_status"] = $zanStatus;//我是否赞过  1已赞 0未赞

            //是否可删除
            $comList["del_status"] = 0;
            if($userId == $info["uid"]){
                $comList["del_status"] = 1;
            }

            $subList = array();
            $pids = self::getCommentChild($info["id"], $info["id"].",");
            $pids = substr($pids, 0, -1);
        }
        if($pids){
            $sonCon = M("Colligate_comment")->where("pid in (".$pids.")")->select();
            if($sonCon){
                $userCache = array();
                foreach ($sonCon as $sk=>$sv){
                    //上一条评论的用户的头像和用户名
                    $subUser = M("users")->field("username,avatar")->where("id=".$sv["uid"])->limit(1)->select();
                    //获取子评论上一条评论的用户名
                    $sonCon[$sk]["username"] = $subUser[0]["username"];
                    $sonCon[$sk]["avatar"] = $subUser[0]["avatar"];
                    $userCache[$sv["id"]] = $subUser[0]["username"];;

                    //是否可是我的工作圈显示删除按钮
                    $sonCon[$sk]["del_status"] = 0;
                    if($userId == $sv["uid"]){
                        $sonCon[$sk]["del_status"] = 1;
                    }
                    if($sv["pid"] != $info["id"]){
                        $sonCon[$sk]["reply_user"] = $userCache[$sv["pid"]];;
                    }
                }
                $comList["child"] = $sonCon;

            }
            //统计所有子评论条数
            $comList["sum"] = count($sonCon);
        }
        if($comList){
            return $this->success(1000,'获取数据成功',$comList);
        }else{
            return $this->error(1030,'赞无数据返回');
        }
    }

    /**
     * 对讲师、课程的评价
     * $course_id 课程id
     */
     public function doComment($post,$uid,$is_mark){
             //查询课程指定的讲师
             $lecturer_id = M('Course')->where(array('id'=>$post['course_id']))->getField('lecturer');
             if($lecturer_id){
                 $post['lecturer_id'] = $lecturer_id;
             }else{
                 $post['lecturer_id'] = '';
             }
         if(isset($is_mark)) {
             if ($is_mark == 0) {
                 //没有评过星级的
                 $cdata['uid'] = $uid;
                 $cdata['course_id'] = $post['course_id'];
                 $cdata['comment_content'] = $post['comment_content'];
                 $cdata['comment_time'] = date('Y-m-d H:i:s', time());
                 $cdata['pid'] = 0;
                 $cdata['state'] = 0;
                 $array['uid'] = $uid;
                 $array['course_id'] = $post['course_id'];
                 $array['lecturer_id'] = $post['lecturer_id'];
                 $array['lecturer_score'] = $post['lecturer_score'];
                 $array['course_score'] = $post['course_score'];
                 $array['score_time'] = date('Y-m-d H:i:s', time());;
                 $where['uid'] = $uid;
                 $where['course_id'] = $array['course_id'];
                 $result = M('Course_score')->where($where)->find();
                 if ($result) {
                     return false;
                 } else {
                     $info = M('ColligateComment')->data($cdata)->add();
                     $infop = M('Course_score')->data($array)->add();
                     if($info && $infop){
                         return true;
                     }else{
                         return false;
                     }
                 }
             } else if ($is_mark == 1) {
                 //评过星级的
                 $data['uid'] = $uid;
                 $data['course_id'] = $post['course_id'];
                 $data['comment_content'] = $post['comment_content'];
                 $data['comment_time'] = date('Y-m-d H:i:s', time());
                 $data['pid'] = 0;
                 $data['state'] = 0;
                 $info = M('ColligateComment')->data($data)->add();
                 return $info;
             }
         }else{
             $cdata['uid'] = $uid;
             $cdata['course_id'] = $post['course_id'];
             $cdata['comment_content'] = $post['comment_content'];
             $cdata['comment_time'] = date('Y-m-d H:i:s',time());
             $cdata['pid'] = 0;
             $cdata['state'] = 0;
             $array['uid'] = $uid;
             $array['course_id'] = $post['course_id'];
             $array['lecturer_id'] = $post['lecturer_id'];
             $array['lecturer_score'] = $post['lecturer_score'];
             $array['course_score'] = $post['course_score'];
             $array['score_time'] = date('Y-m-d H:i:s',time());
             $where['uid'] = $uid;
             $where['course_id'] = $array['course_id'];
             $result = M('Course_score')->where($where)->find();
             if($result){
                 return false;
             }else{
                 $info = M('ColligateComment')->data($cdata)->add();
                 $infop = M('Course_score')->data($array)->add();
                 if($info && $infop){
                     return true;
                 }else{
                     return false;
                 }
             }
         }
     }


    /*
     * 评论/回复评论
     */
    public function addComment($post,$userId){
        $data['pid'] = $post['id'];
        $data['uid'] = $userId;
        $data['course_id'] = $post['course_id'];
        $data['comment_content'] = $post['comment_content'];
        if($data['pid']== '' || $data['course_id'] == ''){
            $this->error(1023,'缺少必要参数');die();
        }
        if(empty($data['comment_content'])){
            return $this->error(1030,'请输入内容');
        }
        $data['comment_time'] = date('Y-m-d H:i:s',time());
        $id = M('ColligateComment')->data($data)->add();
        if($id){
            $info = M('ColligateComment')->where(array('id'=>$id))->find();
            return $this->success(1000,'操作成功',$info);
        }else{
            return $this->error(1030,'操作失败');
        }
    }

    /**
     * 删除评价
     * comment_id 评价id
     */
    public function delComment($post,$userId){

        if($post['id'] == '' || $post['course_id'] == ''){
            return $this->error(1023,'评论id或课程id不能为空');
        }
        $resp = M("colligate_comment")->where(array("id" => $post['id'],'course_id' => $post['course_id']))->limit(1)->delete();
        if($resp) {
            //删除关联的回复评论
            $pids = self::getCommentChild($post['id'], $post['id'] . ",");
            $pidp = substr($pids, 0, -1);
            if ($pidp) {
                $where['pid'] = array('in',$pidp);
                $res = M("Colligate_comment")->where($where)->delete();
                if ($res) {
                    return $this->success(1000, "操作成功");
                } else {
                    return $this->error(1030, "操作失败");
                }
            }
        }
    }




    /**
     *我的课程-课程详情-点赞/取消点赞
     * $uid 评论者id
     * $course_id 课程course_id
     *
     */
    public function doPraise($get,$uid){
        $CourseComment = M('CoursePraise');
        $data['uid'] = $uid;
        $data['id'] = $get['id'];
        $type =  $get['type'];
        if(isset($type) && ($type == 0 || $type == 1)){
            if($type == 0){//取消点赞
                $praise = $CourseComment->where($data)->getField('praise');
                if($praise == 0){
                    return $this->error(1031,'不能重'.$uid.'复操作');
                }else{
                    $data['praise'] = 0;
                    $data['praise_time'] = date('Y-m-d H:i:s',time());
                    $CourseComment->where(array('uid'=>$uid,'id'=>$data['id']))->save($data);
                    $praise_total = $CourseComment->where(array('id'=>$data['id']))->sum('praise');
                    $data['praise_total'] = $praise_total;
                    return $this->success(1000,'操作成功',$data);
                }
            }else{//点赞
                $res = $CourseComment->field('praise')->where($data)->find();
                if($res['praise'] == null){
                    $data['praise'] = 1;
                    $data['praise_time'] = date('Y-m-d H:i:s',time());
                    $CourseComment->data($data)->add();
                    //统计点赞总数
                    $praise_total = $CourseComment->where(array('id'=>$data['id']))->sum('praise');
                    $data['praise_total'] = $praise_total;
                    return $this->success(1000,'操作成功',$data);
                }elseif($res['praise'] == 0){
                    $data['praise'] = 1;
                    $data['praise_time'] = date('Y-m-d H:i:s',time());
                    $CourseComment->where(array('uid'=>$uid,'id'=>$data['id']))->save($data);
                    //统计点赞总数
                    $praise_total = $CourseComment->where(array('id'=>$data['id']))->sum('praise');
                    $data['praise_total'] = $praise_total;
                    return $this->success(1000,'操作成功',$data);
                }else{
                    return $this->error(1031,'不能重复操作');
                }
            }
        }else{
            return $this->error(1023,'参数有误');die();
        }
    }

    /**
     * 加入我的课程
     * @ $course_id 课程id
     */
    public function addMyCourse($course_id,$uid){
       if($course_id == ''){
           return $this->error(1023,'参数有误');
       }else{
           $res = M('CourseRecord')->where(array('uid'=>$uid,'course_id'=>$course_id))->find();
           if(!$res){
               $info = M('CourseRecord')->data(array('uid'=>$uid,'course_id'=>$course_id))->add();
               //type为2是移动端的数据
               $infop = M('Attendance')->data(array('uid'=>$uid,'course_id'=>$course_id,'type'=>2))->add();
               if($info && $infop){
                   return $this->success(1000,'添加成功',array('uid'=>$uid,'course_id'=>$course_id));
               }else{
                   return $this->error(1030,'操作失败');
               }
           }else{
               return $this->error(1030,'已经添加过了');//已经加入过我的课程了
           }
       }
    }
   

    /**执行操作
     * 我的课程-课程详情-新建笔记
     * 
     */
    public function executeCreateNote($post,$uid){

        if(!empty($post['id'])){
            $data['course_id'] = $post['id'];
            $data['uid'] = $uid;
            $data['note_content'] = $post['content'];
            if(empty($data['note_content'])){
                return $this->error(1023,'缺少必要参数');
            }
            if(empty($post['is_public'])){
                $data['is_public'] = 1;
            }else{
                $data['is_public'] = $post['is_public'];
            }
            $data['time'] = date('Y:m:d H:i:s',time());
            $info = M('CourseNote')->data($data)->add();
            if($info){
                //新建笔记触发获取积分
                $res = D('Trigger')->intergrationTrigger($uid,10);
                return $this->success(1000,'操作成功',array('id'=>$info));

            }else{
                return $this->error(1030,'操作失败');
            }
        }else{
            return $this->error(1023,'缺少必要参数');
        }
    }


    /*
     * 获取我关注的课程
     * $uid 用户id
     * @return $info
     */
    public function getMyConcern($uid){
        $where = array(
            'b.uid' => $uid,
            'b.care_status' => 1,
        );
        $info = M('Course a')->field('a.id as course_id,a.click_count,a.course_cover,a.course_name,b.care_status')->join('LEFT JOIN __COURSE_CARE__ b ON a.id = b.course_id')->where($where)->select();
       foreach($info as $k => $v){
           //计算课程关注总数
           $caerNum = M('Course_care')->where(array('course_id'=>$v['course_id'],'care_status'=>1))->sum('care_status');
           $info[$k]['care_num'] = $caerNum;
       }
       return $info;
    }


    /*
     * 加入我的关注
     * 检验关注输入参数合法性
     */
    public function checkConcernData($data,$userId){
      if(empty($data['course_id']) || empty($data['care_status'])){
          return $this->error(1023,'缺少必要参数');
      }
        switch($data['care_status']){
            case 2;//取消关注
                $care_status = M('CourseCare')->where(array('uid'=>$userId,'course_id'=>$data['course_id']))->getField('care_status');
                if($care_status == 2){
                    return $this->error(1030,'已取消过，不能重复操作');die();
                }else{
                    M('CourseCare')->where(array('uid'=>$userId,'course_id'=>$data['course_id']))->setField('care_status',2);
                    return $this->success(1000,'操作成功',array('care_status'=>2));
                }
                break;

            case 1;//加入关注
                $care_status = M('CourseCare')->where(array('uid'=>$userId,'course_id'=>$data['course_id']))->getField('care_status');
                if($care_status == 1){
                    return $this->error(1023,'已关注过了');die();
                }elseif($care_status == 2){
                    M('CourseCare')->where(array('uid'=>$userId,'course_id'=>$data['course_id']))->setField('care_status',1);
                    //加入我的关注触发获取积分
                    $res = D('Trigger')->intergrationTrigger($userId,7);
                    return  $this->success(1000,'操作成功',array('care_status'=>1));
                }else{
                    $data['uid'] = $userId;
                    M('CourseCare')->data($data)->add();
                    //加入我的关注触发获取积分
                    $res = D('Trigger')->intergrationTrigger($userId,7);
                    return  $this->success(1000,'操作成功',array('care_status'=>1));
                }
                break;
         }
    }

    /*
     * 检验资讯表单数据合法性
     * @param  $post
     */
    public function checkData($post,$user_id,$type){
        $data['title'] = $post['heading'];
        $data['content'] = $post['substance'];
        $data['img'] = $post['image'];
        $news = M('News');
        if(empty($data['title'])){
           return  $this->error(1013,'标题不能为空');die();
        }
        if(empty($data['content'])){
            return  $this->error(1013,'内容不能为空');die();
        }
        if(empty($data['img'])){
            $data['img'] = "";
        }
        $data['create_time'] = date('Y-m-d H:i:s',time());
        $data['uid'] = $user_id;
        if($type == 1){

            $msg = $news->data($data)->add();
        }
        if($type == 2){
            $data['id'] = $post['news_id'];
            if(empty($data['id'])){return $this->error(1023,'缺少必要参数');die();}
            if(empty($data['title'])){
                return  $this->error(1013,'标题不能为空');die();
            }
            if(empty($data['content'])){
                return  $this->error(1013,'内容不能为空');die();
            }
            if(empty($data['img'])){
                $data['img'] = "";
            }
            $msg = $news->data($data)->save();
        }
            if($msg){
                $info['id'] = $msg;
                return $this->success(1000,'提交成功',$info);
            }else{
                return $this->error(1030,'操作失败');
            }
        }

    /*
     * 笔记
     * $course_id 课程id
     * $typeId 1我的笔记  2分享笔记
     */
    public function note($course_id,$userId){
        if(empty($course_id)){
            return $this->error(1023,'缺少必要参数');
        }else{
               $info = M('CourseNote')->where(array('course_id'=>$course_id,'uid'=>$userId))->order('id DESC')->select();

               $data = M('CourseNote a')->join('LEFT JOIN __USERS__ b ON a.uid = b.id')->where(array('a.course_id'=>$course_id,'a.is_public'=>1))->field('a.*,b.username,b.avatar')->order('id DESC')->select();
           }
        $res = array(
            'myNote' => $info,
            'shareNote' => $data,
        );
            if($res){
                return $this->success(1000,'操作成功',$res);
            }else{
                return $this->error(1040,'暂无数据返回');
            }
    }


    /*
     * 资讯删除
     */
    public function delete($id){
        if(!empty($id)){
            //将字符串拼装成数组
            $arr_id = explode(',',$id);
            $where['id'] = array('in',$arr_id);
            return $info = M('News')->where($where)->delete();
        }else{
            return $this->error(1023,'缺少必要参数');
        }
    }
    /*
     * 获取用户的系统消息
     * @param  $uid 用户id
     * @param  $user_type 用户id
     */
    public function getSystemNews($uid,$page){
        if($page < 0){
            return $this->error(1023,'分页参数输入有误');
        }
        $pageNum = 15;
        $start = ($page - 1)*$pageNum;
        $end = $pageNum;
        $where['uid'] = array('eq',$uid);
        $where['type_id'] = array('in',array(7,10,11,12));
        $info = M('AdminMessage a')->field('a.id,a.uid,a.title,a.contents_time,a.type_id,a.status')->where($where)->limit($start,$end)->order('contents_time DESC')->select();
      // dump($info);exit;
        foreach($info as $k => $value){
           /*if($value['type_id'] == 1){
               $info[$k]['newsType'] = '课程制作';
           }
           if($value['type_id'] == 2){
               $info[$k]['newsType'] = '试卷制作';
           }
           if($value['type_id'] == 3){
               $info[$k]['newsType'] = '问卷制作';
           }
           if($value['type_id'] == 4){
               $info[$k]['newsType'] = '授课任务';
           }
           if($value['type_id'] == 5){
               $info[$k]['newsType'] = '成绩发布';
           }
           if($value['type_id'] == 6){
               $info[$k]['newsType'] = '调研结果';
           }*/
           if($value['type_id'] == 7){
               $info[$k]['newsType'] = '审批任务';
           }
           /*if($value['type_id'] == 8){
               $info[$k]['newsType'] = '统计调研';
           }
           if($value['type_id'] == 9){
               $info[$k]['newsType'] = '签到提醒';
           }*/
           if($value['type_id'] == 10){
               $info[$k]['newsType'] = '课程学习';
           }
           if($value['type_id'] == 11){
               $info[$k]['newsType'] = '参加考试';
           }
           if($value['type_id'] == 12){
               $info[$k]['newsType'] = '参与调研';
           }
           /*if($value['type_id'] == 13){
               $info[$k]['newsType'] = '计划总结';
           }*/
       }
        if($info){
            //查看系统消息触发获取积分
            $res = D('Trigger')->intergrationTrigger($uid,2);
            return $this->success(1000,'获取数据成功',$info);
        }else{
            return $this->error(1030,'暂无数据返回');
        }
    }

   /*
    *更改消息未读状态
    */
    public function changeSystemNews($id,$status){

        if($id === '' || $id < 0){
            return $this->error(1023,'未获取到消息id');
        }

        if($status === '' || $status < 0){
            return $this->error(1023,'消息状态参数必须且为0');
        }

        if($status == 0){
            $res = M('AdminMessage')->where(array('id'=>$id))->setField('status',1);
            if($res){
                return $this->success(1000,'操作成功',array('status'=>1));
            }else{
                return $this->error(1041,'不能重复操作');
            }
        }else{
            return $this->error(1023,'参数有误');
        }
    }

    /**
     * 获取系统消息消息详情
     * $id 消息id
     *
     */
    public function getSystemNewsDetail($id){
        if($id == '' || $id < 0){
            return $this->error(1023,'未获取到消息id');
        }
        $info = M('AdminMessage a')->field('a.id,a.uid,a.title,a.contents_time,a.type_id,a.status')->where(array('id' => $id,'status'=>1))->find();

            if($info['type_id'] == 1){
                $info['newsType'] = '课程制作';
            }
            if($info['type_id'] == 2){
                $info['newsType'] = '试卷制作';
            }
            if($info['type_id'] == 3){
                $info['newsType'] = '问卷制作';
            }
            if($info['type_id'] == 4){
                $info['newsType'] = '授课任务';
            }
            if($info['type_id'] == 5){
                $info['newsType'] = '成绩发布';
            }
            if($info['type_id'] == 6){
                $info['newsType'] = '调研结果';
            }
            if($info['type_id'] == 7){
                $info['newsType'] = '审批任务';
            }
            if($info['type_id'] == 8){
                $info['newsType'] = '统计调研';
            }
            if($info['type_id'] == 9){
                $info['newsType'] = '签到提醒';
            }
            if($info['type_id'] == 10){
                $info['newsType'] = '课程学习';
            }
            if($info['type_id'] == 11){
                $info['newsType'] = '参加考试';
            }
            if($info['type_id'] == 12){
                $info['newsType'] = '参与调研';
            }
            if($info['type_id'] == 13){
                $info['newsType'] = '计划总结';
            }
        if($info){
            return $this->success(1000,'获取数据成功',$info);
        }else{
            return $this->error(1030,'暂无数据返回');
        }

    }

    /*
     * 互动消息
     */
    public function interactNews($userId,$model,$page){

        if($page < 0){
            return $this->error(1023,'分页参数输入有误');
        }
        $pageNum = 15;
        $start = ($page - 1)*$pageNum;
        $end = $pageNum;
        //工作圈评论
        //查询主评论数据
        $wheres['pid'] = array('eq',0);
        $info = $model->where($wheres)
            ->field('id,content,publish_time,state')->limit($start,$end)->order('publish_time DESC')->select();

        //循环第一次取出第一条主评论的所有子评论
        if(!empty($info)){
            foreach($info as $k => $v){
                $where['cid'] =  $v['id'];
                $where['praise'] =  array('gt',0);
                $pids = self::getFriendCommentChild($v["id"], $v["id"].",");
                $pids = substr($pids, 0, -1);
                $tiaojian['pid'] = array('in',$pids);
                $msg = $model->where($tiaojian)
                    ->field('id,content,publish_time,state,pid,uid')->limit($start,$end)->order('publish_time DESC')->select();
                //查询主评论点赞总数
                foreach($msg as $i => $j){
                    $ret[$i]['praiseTotal'] = M('Friends_praise')->where($where)->sum('praise');
                    $ret[$i]['praiseTotal'] = $ret[$i]['praiseTotal'] ? $ret[$i]['praiseTotal'] : 0;
                    $msg[$i]['type'] = 1;//表示工作圈
                    $msg[$i]["touid"] =  M("FriendsCircle")->where(array("id" => $j["pid"]))->getField('uid');
                    $msg[$i]['praiseTotal'] = $ret[$i]['praiseTotal'];
                }
                foreach($msg as $m => $n){
                    if($n['touid'] != $userId){
                        unset($msg[$m]);
                    }
                }
                $mseeage[$k]['sub'] = $msg;
            }

            //把三维数组循环拼装成二维数组
            foreach($mseeage as $key => $val){
                foreach($val['sub'] as $x => $y){
                    $new[] = $y;
                }
            }
        }else{
            $new = array();
        }
        //课程评论动态
        //查询主评论数据
        $condition['pid'] = array('eq',0);
        $infof = M('Colligate_comment')->where($condition)
            ->field('id,comment_content as content,comment_time as publish_time,state')->limit($start,$end)->order('publish_time DESC')->select();
        //循环第一次取出第一条主评论的所有子评论

        if(!empty($infof)){
            foreach($infof as $ks => $vs){
                $conditions['id'] =  $vs['id'];
                $conditions['praise'] =  array('gt',0);
                $pidsc = $this->getCommentChild($vs["id"], $vs["id"].",");
                $pidsc = substr($pidsc, 0, -1);
                $tiaojians['pid'] = array('in',$pidsc);
                $msgs = M('Colligate_comment')->where($tiaojians)
                    ->field('id,comment_content as content,comment_time as publish_time,state,uid,pid')->limit($start,$end)->order('publish_time DESC')->select();
                //查询主评论点赞总数
                foreach($msgs as $is => $js){
                    $rets[$is]['praiseTotal'] = M('Course_praise')->where($conditions)->sum('praise');
                    $rets[$is]['praiseTotal'] = $rets[$is]['praiseTotal'] ? $rets[$is]['praiseTotal'] : 0;
                    $msgs[$is]['type'] = 2;//表示课程
                    $msgs[$is]["touid"] =  M("Colligate_comment")->where(array("id" => $js["pid"]))->getField('uid');
                    $msgs[$is]['praiseTotal'] = $rets[$is]['praiseTotal'];
                }
                foreach($msgs as $ms => $ns){
                    if($ns['touid'] != $userId){
                        unset($msgs[$ms]);
                    }
                }
                $mseeages[$ks]['sub'] = $msgs;

            }
            //把三维数组循环拼装成二维数组
            foreach($mseeages as $vals){
                foreach($vals['sub'] as $xs => $ys){
                    $news[] = $ys;
                }
            }
        }else{
            $news = array();
        }

        if(empty($new)){
            $data = $news;
        }
        if(empty($news)){
            $data = $new;
        }
        if(!empty($new) && !empty($news)){
            $data = array_merge_recursive($new,$news);
        }

        $sort = array(
            'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
            'field'     => 'publish_time',       //排序字段
        );
        $arrSort = array();
        foreach($data AS $uniqid => $row){
            foreach($row AS $key=>$value){
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if($sort['direction']){
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);
        }
        if($data){
            return $this->success(1000,'获取数据成功',$data);
        }else{
            return $this->error(1030,'暂无数据返回');
        }
    }

    /*
    *更改互动消息未读状态为已读
    */
    public function changeInteractNews($id,$state,$type){

        if($id === '' || $id < 0){
            return $this->error(1023,'未获取到消息id');
        }
        if($state === '' || $state < 0){
            return $this->error(1023,'消息状态不能为空');
        }
        if($state == 0){
            if($type == 1){
                $res = M('Friends_circle')->where(array('id'=>$id))->setField('state',1);
                if($res){
                    return $this->success(1000,'操作成功',array('state'=>1));
                }else{
                    return $this->error(1041,'不能重复操作');
                }
            }elseif($type ==2){
                $res = M('Colligate_comment')->where(array('id'=>$id))->setField('state',1);
                if($res){
                    return $this->success(1000,'操作成功',array('state'=>1));
                }else{
                    return $this->error(1041,'不能重复操作');
                }
            }
        }else{
            return $this->error(1023,'参数有误');
        }
    }


    /*
     * 互动消息详情
     */
    public function getInteractNewsDetail($id){
        if($id < 0 || $id === ''){
            return $this->error(1023,'缺少消息id');
        }
       $res =  M('Friends_circle')->where(array('id' => $id))->find();
        if($res){
            return $this->success(1000,'获取数据成功',$res);
        }else{
            return $this->success(1030,'赞无数据返回');
        }
    }

   /*
   * 我的学分
   */
    public function  credit($userId,$model){

       //统计总学分
        $total_credit =$model->where(array('user_id'=>$userId))->sum('credit');
        $total_credit = $total_credit ? $total_credit : 0;
        //查询学分来源
        $info = $model->where(array('user_id'=>$userId))
            ->field('id,create_time as add_time,typeid as type,credit as score,source_id as course_id,user_id as uid ')->select();
        foreach($info as $k=>$v){
            $info[$k]['source'] = $this->centerStudy($v['type'],$v['course_id']);
            $info[$k]['add_time'] = $v['add_time'];
            $info[$k]['score'] = $v['score'] ? $v['score'] : 0;
        }

        return $data = array(
            'total_score' => $total_credit,
            'data'    =>   $info
        );
    }


    /*
    * 学习目标
    */
    public function learningTarget($typeId,$page,$userId){

        if($typeId == ''){
            return $this->error(1024,'不合法参数');
        }
        $data = $this->getLearningTarget($typeId,$page,$userId);

        if($data){
            //查看学习目标触发获取积分
            $res = D('Trigger')->intergrationTrigger($userId,5);
            return $this->success(1000,'获取成功',$data);
        }else{
            return $this->error(1040,'暂无数据返回');
        }
    }


    public function getLearningTarget($typeId,$page,$uid){
        $pageNum = 15;
        //查询该用户所在的组织
        $item = M('Tissue_group_access')->field('tissue_id,job_id')->where(array('uid'=>$uid))->find();
        $tissue_id = $item['tissue_id'];
        $job_id = $item['job_id'];
        $tiaoJian = array(
            'tissue_id' => $tissue_id,
            'job_id' => $job_id,
            'year' => date('Y')
        );
        //查询学员所定的学习目标 type

        //获取已经过审核的项目,指定人学习的课程
        if($typeId == 1){//必修
            $where['a.uid'] = array("eq",$uid);
            $where['b.id'] = array("gt",0);
            $ret = M("designated_personnel as a")
                ->join('LEFT JOIN __PROJECT_COURSE__ as b ON a.project_id = b.project_id LEFT JOIN __COURSE__ as c ON b.course_id = c.id LEFT JOIN __COURSE_DETAIL__ as d ON c.id = d.id')
                ->where($where)
                ->field('b.project_id,b.course_id,b.start_time,b.end_time,c.course_name,c.course_cover,d.course_intro')
                ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();

            //计算当年所有月份的必修总学时
            $tiaoJian['typeid'] = 0;
            $rets = M('Tool_learning')->field('january,february,march,april,may,june,july,august,september,october,november,december')->where($tiaoJian)->find();
            $_total = 0;
            foreach($rets as $value){
                $_total += $value;
            }

            //计算当年必修的总学时
            $con['create_time'] = array('like','%'.date('Y').'%');
            $con['user_id'] = $uid;
            $con['typeid'] = 4;
            $sum = M('Center_study')->where($con)->sum('hours');
            if(!$sum){
                $sum = 0;
            }
            $info = array();

            foreach($ret as $i=>$v){

                $start_time = strtotime($v['start_time']) - time();//大于0表示待考试
                $end_time = strtotime($v['end_time']) - time();//小于0表示逾期（已结束）

                if($start_time > 0){
                    $info[$i]['course_id'] = $v['course_id'];
                    $info[$i]['project_id'] = $v['project_id'];
                    $info[$i]['course_name'] = $v['course_name'];
                    $info[$i]['course_cover'] = $v['course_cover'];
                    $info[$i]['course_intro'] = $v['course_intro'];
                    $info[$i]['start_time'] = $v['start_time'];
                    $info[$i]['end_time'] = $v['end_time'];
                    $info[$i]['_status'] = 1;//未开始
                    $info[$i]['typeId'] = $typeId;
                }

                if($end_time > 0  && $start_time < 0){
                    $info[$i]['course_id'] = $v['course_id'];
                    $info[$i]['project_id'] = $v['project_id'];
                    $info[$i]['course_name'] = $v['course_name'];
                    $info[$i]['course_cover'] = $v['course_cover'];
                    $info[$i]['course_intro'] = $v['course_intro'];
                    $info[$i]['start_time'] = $v['start_time'];
                    $info[$i]['end_time'] = $v['end_time'];
                    $info[$i]['_status'] = 2;//学习中
                    $info[$i]['typeId'] = $typeId;
                }

                if($end_time < 0){
                    $info[$i]['course_id'] = $v['course_id'];
                    $info[$i]['project_id'] = $v['project_id'];
                    $info[$i]['course_name'] = $v['course_name'];
                    $info[$i]['course_cover'] = $v['course_cover'];
                    $info[$i]['course_intro'] = $v['course_intro'];
                    $info[$i]['start_time'] = $v['start_time'];
                    $info[$i]['end_time'] = $v['end_time'];
                    $info[$i]['_status'] = 3;//已结束
                    $info[$i]['typeId'] = $typeId;

                }

            }
            $data['_total'] = $_total = $_total ? $_total: 0;
            $data['total'] = $sums = $sum ? $sum : 0;
            $data['list'] = $info;
        }else if($typeId == 2){//选修

            //获取在公开课列表加入到我的课程的课程列表
            //$condition['b.is_public'] = 1;
            $condition['a.uid'] = $uid;
            /*$res = M("Course_record as d")
                ->join("LEFT JOIN __ATTENDANCE__ a ON d.uid = a.uid AND d.course_id = a.course_id LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __COURSE_DETAIL__ c ON b.id = c.id ")
                ->where($condition)
                ->field('a.status,a.state,b.id,b.course_name,b.course_cover,c.course_intro')
                ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();*/

            $res = M("Course_record as a")
                ->join("LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __COURSE_DETAIL__ c ON b.id = c.id ")
                ->where($condition)
                ->field('b.chapter,b.id,b.course_name,b.course_cover,c.course_intro')
                ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();
            //计算当年所有月份的选修总学时
            $tiaoJian['typeid'] = 1;
            $rets = M('Tool_learning')->field('january,february,march,april,may,june,july,august,september,october,november,december')->where($tiaoJian)->find();

            $_total = 0;
            foreach($rets as $value){
                $_total += $value;
            }
            //计算当年必修的总学时
            $con['create_time'] = array('like','%'.date('Y').'%');
            $con['user_id'] = $uid;
            $con['typeid'] = 5;
            $sum = M('Center_study')->where($con)->sum('hours');
            $sum = $sum ? $sum : 0;
            $info = array();
            foreach($res as $k=>$v){
                $chapter = json_decode($v["chapter"], true);//总章节
                $where['project_id'] = 0;
                $where['course_id'] = $v['id'];
                $where['uid'] = $uid;
                //查询已学习的章节
                $isStudy = M("Course_chapter")->field("id")->where($where)->select();
                if(!$isStudy){//未开始
                    $info[$k]['course_id'] = $v['id'];
                    $info[$k]['course_name'] = $v['course_name'];
                    $info[$k]['course_cover'] = $v['course_cover'];
                    $info[$k]['course_intro'] = $v['course_intro'];
                    $info[$k]['_status'] = 1;
                    $info[$k]['typeId'] = $typeId;
                }else if(count($isStudy) == count($chapter)){//已结束
                    $info[$k]['course_id'] = $v['id'];
                    $info[$k]['course_name'] = $v['course_name'];
                    $info[$k]['course_cover'] = $v['course_cover'];
                    $info[$k]['course_intro'] = $v['course_intro'];
                    $info[$k]['_status'] = 3;
                    $info[$k]['typeId'] = $typeId;
                }else if(count($isStudy) < count($chapter)){//学习中
                    $info[$k]['course_id'] = $v['id'];
                    $info[$k]['course_name'] = $v['course_name'];
                    $info[$k]['course_cover'] = $v['course_cover'];
                    $info[$k]['course_intro'] = $v['course_intro'];
                    $info[$k]['_status'] = 2;
                    $info[$k]['typeId'] = $typeId;
                }
            }
            $data['_total'] = $_total = $_total ? $_total: 0;
            $data['total'] = $sums = $sum ? $sum : 0;
            $data['list'] = $info;
        } else if($typeId == 3){//修读课程（必修和选修）
            //计算当年所有月份的修读课程数量(门)
            $tiaoJian['typeid'] = 2;
            $rets = M('Tool_learning')->field('january,february,march,april,may,june,july,august,september,october,november,december')->where($tiaoJian)->find();
            $_total = 0;
            foreach($rets as $value){
                $_total += $value;
            }
            $_total = $_total ? $_total: 0;
            /*$where['a.uid'] = array("eq",$uid);
            $where['b.id'] = array("gt",0);

            //计算当年所有月份的修读总学时
            $tiaoJian['typeid'] = 2;
            $rets = M('Tool_learning')->field('january,february,march,april,may,june,july,august,september,october,november,december')->where($tiaoJian)->find();
            $_total = 0;
            foreach($rets as $value){
                $_total += $value;
            }
            $ret = M("designated_personnel as a")
                ->join('LEFT JOIN __PROJECT_COURSE__ as b ON a.project_id = b.project_id LEFT JOIN __COURSE__ as c ON b.course_id = c.id LEFT JOIN __COURSE_DETAIL__ as d ON c.id = d.id')
                ->where($where)
                ->field('b.project_id,b.course_id,b.start_time,b.end_time,c.course_name,c.course_cover,d.course_intro')
                ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();
            //获取在公开课列表加入到我的课程的课程列表
            $condition['b.id'] = array("gt",0);
            $condition['b.is_public'] = 1;
            $condition['a.uid'] = $uid;
            $res = M("Attendance as a")
                ->join("LEFT JOIN __COURSE__ b ON a.course_id = b.id LEFT JOIN __COURSE_DETAIL__ c ON b.id = c.id ")
                ->where($condition)
                ->field('a.status,a.state,b.id,b.course_name,b.course_cover,c.course_intro')
                ->limit(($page-1) * $pageNum . ',' . $pageNum)->select();

            $info1 = array();
            $sum1 = 0;
            foreach($res as $j=>$v1){
                if($v1['status'] == 0){//未开始
                    $info1[$j]['_status'] = 1;
                }else if($v1['status'] == 1){//已结束
                    $info1[$j]['_status'] = 3;
                    $sum1++;
                }else if($v1['state'] == 1){//学习中
                    $info1[$j]['_status'] = 2;
                }
                $info1[$j]['course_id'] = $v1['id'];
                $info1[$j]['course_name'] = $v1['course_name'];
                $info1[$j]['course_cover'] = $v1['course_cover'];
                $info1[$j]['course_intro'] = $v1['course_intro'];
                $info1[$j]['typeId'] = $typeId;
            }


            $info2 = array();
            $sum = 0;
            foreach($ret as $k=>$v){
                $start_time = strtotime($v['start_time']) - time();//大于0表示待考试
                $end_time = strtotime($v['end_time']) - time();//小于0表示逾期（已结束）
                if($start_time > 0){
                    $info2[$k]['_status'] = 1;//未开始
                }

                if($end_time > 0  && $start_time < 0){
                    $info2[$k]['_status'] = 2;//学习中

                }

                if($end_time < 0){
                    $info2[$k]['_status'] = 3;//已结束
                    $sum++;
                }
                $info2[$k]['course_id'] = $v['course_id'];
                $info2[$k]['project_id'] = $v['project_id'];
                $info2[$k]['course_name'] = $v['course_name'];
                $info2[$k]['course_cover'] = $v['course_cover'];
                $info2[$k]['course_intro'] = $v['course_intro'];
                $info2[$k]['typeId'] = $typeId;
            }*/

            $course = $this->getCourse($uid);

            //公开课课程
            $where2['a.uid'] = $uid;
            $where2['a.project_id'] = 0;
            $where2['a.create_time'] = array('like','%'.date('Y').'%');

            $data2= M('Course_chapter a')
                ->join('left join __COURSE__ b on a.course_id=b.id LEFT JOIN __COURSE_DETAIL__ d ON b.id=d.id')
                ->field('a.project_id,a.time_percent as per,b.id,b.course_name,b.course_cover,d.course_intro')
                ->where($where2)
                ->group('b.id')->select();
           /* foreach($data2 as $k=>$v){
                $per = $this->getCoursePer($v['uid'],$v['course_id']);
                $data2[$k]['per'] = $per;
            }*/


            $courses = array_merge($course,$data2);
            $finishedCourseNum = $unFinishedCourseNum = 0;
            foreach($courses as $k=>$v){
                if($v['per'] == 100){
                    $finishedCourseNum += 1;    //已完成的课程数量
                    $courses[$k]['_status'] = 3;
                }else if($v['per'] == 0){
                    $courses[$k]['_status'] = 1;
                }else{
                    $courses[$k]['_status'] = 2;
                }
                $courses[$k]['project_id'] = $v['project_id'] == 0 ? 'true' : $v['project_id'];
            }

            $finishedCourseNum = $finishedCourseNum ? $finishedCourseNum : 0;
            $data['_total'] = $_total;
            $data['total'] = $finishedCourseNum;
            $data['list'] = $courses;
            
        }
		$goalRate = 0.1;
		if($data['_total'] > 0 && $data['total'] > 0){
			$goalRate = $data['total'] / $data['_total'] * 100;
			$goalRate = round($goalRate, 2);
		}
		
		//时间转为小时
		if($typeId == 1 || $typeId == 2){
			$data['total'] = round($data['total'] / 60, 1);
			$data['_total'] = round($data['_total'] / 60, 1);
		}
		$data['goalRate'] = $goalRate;
        return $data;
    }

    /*
     * 获取学员某门课程的学习进度
     * @param $uid 用户id
     * @param $cid 课程id
     * @param $pid 项目id
     */
    public function getCoursePer($uid,$cid,$pid=false){
        if($pid){
            $count = M('course_chapter')->where(array('uid'=>$uid,'course_id'=>$cid,'project_id'=>$pid))->count();
            $finishedNum = M('course_chapter')
                ->where(array('uid'=>$uid,'course_id'=>$cid,'project_id'=>$pid,'status'=>3))
                ->count();
            $per = floor( ($finishedNum / $count) * 100 );
            return $per;
        }else{
            $count = M('course_chapter')->where(array('uid'=>$uid,'course_id'=>$cid))->count();
            $finishedNum = M('course_chapter')
                ->where(array('uid'=>$uid,'course_id'=>$cid,'status'=>3))
                ->count();
            $per = floor( ($finishedNum / $count) * 100 );
            return $per;
        }
    }

    /**
     * 获取课程信息
     */
    public function getCourse($uid,$day=false){
        $where['b.uid'] = $uid;
        $where['c.status'] = 1;
        $where['c.auditing'] = 1;
        $where['c.course_name'] = array('neq','');
        if($day){
            $where['a.start_time'] = array('egt',$day['start_time']);
            $where['a.end_time'] = array('elt',$day['end_time']);
        }

        $data = M('project_course')
            ->alias('a')
            ->join('left join __DESIGNATED_PERSONNEL__ b on a.project_id=b.project_id')
            ->join('left join __COURSE__ c on a.course_id=c.id')
            ->join('left join __ADMIN_PROJECT__ d on a.project_id=d.id')
            ->join('left join __COURSE_DETAIL__ e on c.id=e.id')
            ->field('a.project_id,a.course_id,a.start_time,a.end_time,c.course_name,c.course_cover,e.course_intro')
            ->where($where)
            ->select();


        foreach($data  as $k => $v){
            //根据课程id获取本课程学习进度
            $per = $this->getCoursePer($v['user_id'],$v['course_id'],$v['project_id']);
            $data[$k]['per'] = $per;
        }

        return $data;
    }

    /**
     * 记录视频播放时长
     * project_id
     * course_id
     * fileName
     * fileSrc
     * fileType
     * status
     * timeLen
     * time_percent
     */
    public function recordVideoTimeLong($userId,$course_id,$pid,$fileName,$path,$type,$timeLen,$time_percent){
        //项目id
        $pid = $pid ? $pid : 0;
        $cwhere["uid"] = $userId;
        $cwhere["course_id"] = $course_id;
        $cwhere["project_id"] = $pid;
        $cwhere["name"] = $fileName;
        //查询有没有学习过
        //echo $userId,$course_id,$pid,$fileName,$type,$path,$timeLen,$time_percent;exit;
        $chapter = M("course_chapter")->where($cwhere)->limit(1)->find();

        if($chapter){
            if($type == 1){//说明是视频
                if($time_percent == 100){
                    $data["status"] = 3;
                }else{
                    $data["status"] = 2;
                }
                $data["uid"] = $userId;
                $data["course_id"] = $course_id;
                $data["project_id"] = $pid;
                $data["name"] = $fileName;
                $data["type"] = $type;
                $data["timeLen"] = $timeLen;
                $data["path"] = $path;
                $data["time_percent"] = $time_percent;
                $data["create_time"] = date("Y-m-d H:i:s");
                $res = M("course_chapter")->where($cwhere)->limit(1)->save($data);
                return $res;
            }
        }else if($type == 2 || $type == 3) {//ppt //文档
            $data["uid"] = $userId;
            $data["course_id"] = $course_id;
            $data["project_id"] = $pid;
            $data["name"] = $fileName;
            $data["path"] = $path;
            $data["type"] = $type;
            $data["status"] = 3;
            $data["timeLen"] = 100;
            $data["time_percent"] = 100;
            $data["create_time"] = date("Y-m-d H:i:s");
            $res = M("course_chapter")->add($data);
            return $res;
        }else if($type == 4){//文档
            $data["uid"] = $userId;
            $data["course_id"] = $course_id;
            $data["project_id"] = $pid;
            $data["name"] = $fileName;
            $data["path"] = $path;
            $data["type"] = $type;
            $data["status"] = 3;
            $data["timeLen"] = 100;
            $data["time_percent"] = 100;
            $data["create_time"] = date("Y-m-d H:i:s");
            $res = M("course_chapter")->add($data);
            return $res;
        }
    }


    /*
     * 记录文件下载
     */
    public function recordData($uid,$course_id,$chapter_id,$project_id,$name,$path,$type,$style){
        $data['uid'] = $uid;
        $data['course_id'] = $course_id;
        $data['project_id'] = $project_id;
        $data['chapter_id'] = $chapter_id;
        $data['name'] = $name;
        $data['path'] = $path;
        $data['type'] = $type;
        $data['style'] = $style;

        //如果存在该条数据就返回提示存在
        /* $where['uid'] = $uid;
         $where['course_id'] = $course_id;
         $where['project_id'] = $project_id;
         $where['name'] = $name;
         $where['path'] = $path;
         $where['type'] = $type;
         $where['style'] = $style;*/
         $info = M('File_download')->where($data)->find();
         if($info){
             $result = 2;
         }else{
             $data['create_time'] = date('Y-m-d H:i:s',time());
             $results = M('File_download')->data($data)->add();
             $result = 1;
         }
       // $result = M('File_download')->data($data)->add();
        return $result;
    }

    /*
     * 获取文件缓存
     */
    public function getRecordData($uid){

        $result = M('File_download')->where(array('uid'=>$uid))->select();

        foreach($result as $k => $v){
            $img = M('Course')->where(array('id'=>$v['course_id']))->getField('course_cover');
            $result[$k]['course_cover'] = $img;
        }
        return $result;
    }


}

