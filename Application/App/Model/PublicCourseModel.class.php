<?php
namespace App\Model;

use Think\Model;
/**
 * @首页公开课
 * @author lizhongjian
 *
 */
class PublicCourseModel extends CommonModel{

    protected $tablePrefix = 'think_';
    protected $tableName = 'Course';

    /**
     * app首页
     */
    public function index($get,$user_id){
        $get['course_name'] = $get['course_name'] ? $get['course_name'] : "";
        //接收搜索关键字解码
        $get['course_name'] = urldecode($get['course_name']);
        iconv( 'CP1252', 'UTF-8', $get['course_name']);
        //分页参数不传则默认为第一页
        $get['page'] = $get['page'] ? $get['page'] : 1;
        $get['pageNum'] =  15;
        $where["a.is_public"] = 1;
        $where["a.course_way"] = 0;
        $where["a.status"] = 1;
        if(!empty($get['course_name'])){

            $where['a.course_name']=array('like',"%".$get['course_name']."%");
        }
        $model = M('course_care');
        $publicCourses=M('Course as a')
            ->field("a.id,a.course_name,a.course_cover,a.click_count")
            ->where($where)
            ->limit(($get['page']-1) * $get['pageNum'] . ',' . $get['pageNum'])
            ->order('a.click_count DESC')
            ->select();
        foreach($publicCourses as $k=>$v){
            $publicCourses[$k]['care_total'] = $model->where(array('course_id'=> $v['id'],'care_status'=>1))->count('care_status');
            $uid_m = $model->where(array('course_id'=> $v['id'],'uid'=>$user_id,'care_status'=>1))->find();
            //查询是否加入过我的课程
            $is_joinMyCourse = M('Course_record')->where(array('uid'=>$user_id,'course_id'=>$v['id']))->find();
            if($is_joinMyCourse){
                $publicCourses[$k]['is_joinMyCourse'] = 1;//有加入过我的课程
            }else{
                $publicCourses[$k]['is_joinMyCourse'] = 0;//没有加入过我的课程
            }
            if($uid_m){
                $publicCourses[$k]['whether_concern'] = 1;
            }else{
                $publicCourses[$k]['whether_concern'] = 0;
            }
        }
        //公司banner图
        $img = M('Company_banner')->field('banner_img')->find();
        if(empty($img)){
            $img = '/Upload/20170418/58f5854200969.jpg';
        }
        //课程类别
        $courseCategory = M('CourseCategory')
            ->field("id,pid,cat_name,img")
            ->where(array('pid' => 0))->limit(6)->select();
        //自定义默认的六张课程分类图片
        $courseCategory_img = array(
             '/Upload/coursecategory/20170316/category1.png',
             '/Upload/coursecategory/20170316/category2.png',
             '/Upload/coursecategory/20170316/category3.png',
             '/Upload/coursecategory/20170316/category4.png',
             '/Upload/coursecategory/20170316/category5.png',
             '/Upload/coursecategory/20170316/category6.png'

        );
        foreach($courseCategory as $k => $v){
            $courseCategory[$k]['img'] = $courseCategory_img[$k];
        }
        $info = array(
            'img'              => $img,
            'courseCategory'  =>  $courseCategory,
            'publicCourses'    => $publicCourses,

        );

        if($info){
            return $this->success(1000,'获取数据成功',$info);
        }else{
            return $this->error(1030,'暂无数据返回');
        }
    }
    
    /**
     * app首页公开课列表课程分类
     */
    public function courseCategory(){
        //课程类别
        $courseCategory = M('CourseCategory')
            ->field("id,id as cid,pid,cat_name")
            ->where(array('pid' => 0))->select();
       /* $courseCategory = M('Course as a')
        ->field("a.id,b.id as cid,b.pid,b.cat_name")
        ->join('LEFT JOIN __COURSE_CATEGORY__ as b ON a.course_cat_id = b.id')
        ->where(array('a.is_public' => 1,'b.pid' => 0))
        ->select();*/
        return $courseCategory;
    }
    
    /**
     * app首页公开课列表
     */
    public function publicCourse($get,$user_id){
        if($get['cid'] != ''){
            $info = $this->getAllChildId($get['cid']);
            $info[] = $get['cid'];
            $where['a.course_cat_id'] = array('in',$info);//course_cat_id在这个数组中，
            $get['course_name'] = $get['course_name'] ? $get['course_name'] : "";

        }

        $get['page'] = $get['page'] ? $get['page'] : 1;
        $get['pageNum'] =  15;
        $start = ($get['page'] - 1)*$get['pageNum'];
        $end = $get['pageNum'];
        $where["a.is_public"] = 1;
        $where["a.course_way"] = 0;
        $where["a.status"] = 1;
        if(!empty($get['course_name'])){
            $where['a.course_name']=array('like',"%".$get['course_name']."%");
        }

            if($get['type'] == 1){//按照热门排序
                $publicCourses=M('Course as a')
                    ->field("a.id as course_id,a.course_name,a.course_cover,a.click_count")
                    ->join('LEFT JOIN __COURSE_CATEGORY__ as b ON a.course_cat_id = b.id')
                    ->where($where)
                    ->limit($start,$end)->order('click_count desc')
                    ->select();
            }

            if($get['type'] == 2){//按照好评数排序
           $DB_PREFIX = strtolower(C('DB_PREFIX').'course_score');
		   $results = M("course as a")
		         ->join("LEFT JOIN __COURSE_CATEGORY__ as b ON a.course_cat_id = b.id")
		         ->field("a.id as course_id,a.course_name,a.course_cover,a.click_count,b.cat_name,(select sum(c.course_score) from $DB_PREFIX as c where a.id = c.course_id) as coursenum")
               ->where($where)->limit($start,$end)->order('a.id DESC')->select();

                $publicCourses = array();
                foreach($results as $k=>$items){
                    $publicCourses[$k] = $items;
                    if(empty($items['coursenum'])){
                        $publicCourses[$k]['coursenum'] = 0;
                    }
                }
                array_multisort($this->i_array_column($publicCourses,'coursenum'),SORT_DESC,$results);
            }

            if($get['type'] == 3){//按照最新添加时间排序
                $publicCourses=M('Course as a')
                    ->field("a.id as course_id,a.course_name,a.create_time,a.course_cover,a.click_count")
                    ->join('LEFT JOIN __COURSE_CATEGORY__ as b ON a.course_cat_id = b.id')
                    ->where($where)
                    ->limit($start,$end)->order('create_time desc')
                    ->select();
            }
            if($get['type'] == ""){//无条件排序
            $publicCourses=M('Course as a')
                ->field("a.id as course_id,a.course_name,a.create_time,a.course_cover,a.click_count")
                ->join('LEFT JOIN __COURSE_CATEGORY__ as b ON a.course_cat_id = b.id')
                ->where($where)
                ->limit($start,$end)
                ->select();
        }

        $model = M('course_care');
        foreach($publicCourses as $k=>$v){
            $publicCourses[$k]['care_total'] = $model->where(array('course_id'=> $v['course_id'],'care_status'=>1))->count('care_status');
            $uid_m = $model->where(array('course_id'=> $v['course_id'],'uid'=>$user_id,'care_status'=>1))->find();
            //查询是否加入过我的课程
            $is_joinMyCourse = M('Course_record')->where(array('uid'=>$user_id,'course_id'=>$v['course_id']))->find();
            if($is_joinMyCourse){
                $publicCourses[$k]['is_joinMyCourse'] = 1;//有加入过我的课程
            }else{
                $publicCourses[$k]['is_joinMyCourse'] = 0;//没有加入过我的课程
            }
            if($uid_m){
                $publicCourses[$k]['whether_concern'] = 1;//加入过我的关注
            }else{
                $publicCourses[$k]['whether_concern'] = 0;//没有加入过我的关注
            }
        }
        if($publicCourses){
            return $this->success(1000,'获取数据成功',$publicCourses);
        }else{
            return $this->error(1030,'暂无数据返回');
        }
    }


    /*
     * @根据课程类别id查询所有子类的id
     * @param $cid 类别id
     */
    public function getAllChildId($cid){
        //实例化类别表模型
        $info = M('CourseCategory')->where(array('pid' => $cid))->select();
        static $list = array();
        foreach($info as $k=>$v){
            $list[] = $v['id'];
            $this->getAllChildId($v['id']);
        }
            return $list; 
    }

    /**
     * 获取我的课程-课程详情-目录
     * $course_id  课程id
     */
    public function getCourseDetailsList($course_id,$uid){
        if($course_id == ''){
            return $this->error(1023,'缺少课程id');
        }
        $where['a.status'] = 1;
        $where['a.id'] = $course_id;
        $info = M('Course a')->field('a.id,a.chapter')->where($where)->find();
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

            }elseif(strstr($fileType, ".ppt") || strstr($fileType, ".pptx") || strstr($fileType, ".png") || strstr($fileType, ".jpeg")){

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
                $info['chapter'][$key]['style'] = 3;//课件

            }

            //进度
            $info['chapter'][$key]['id'] = $course_id;//课程id
            $info['chapter'][$key]['uid'] = $uid;//用户id
            $info['chapter'][$key]['src'] = $value['src'];//课件地址
            $info['chapter'][$key]['name'] = $value['name'];//章节名称
            //$info['chapter'][$key]["time_percent"] = 0;
            $cwhere["uid"] = $uid;
            $cwhere["course_id"] = $course_id;
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
            }else {
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
        if($info['chapter']){
            return $this->success(1000,'获取数据成功',$info['chapter']);
        }else{
            return $this->error(1000,'暂无数据返回');
        }
    }

    /**
     * 获取公开课课程详情-简介
     * @ $course_id 课程id
     */
    public function getCourseDetails($course_id,$userId){
        if(empty($course_id)){
            return $this->error(1030,'缺少必要参数');
        }else{
            $condition['a.id'] = $course_id;
            $condition['a.status'] = 1;//通过审核的课程
            $info = M('Course a')
                ->field('a.id,a.course_name,a.media_src,a.click_count,a.lecturer as lecturer_id,a.lecturer_name,b.name,b.desc,c.lecturer_evaluation,d.course_intro')
                ->join('LEFT JOIN __LECTURER__ b ON b.id = a.lecturer LEFT JOIN __LECTURER_COMMENT__ as c ON b.id = c.lecturer_id LEFT JOIN __COURSE_DETAIL__ d ON a.id = d.id')
                ->where($condition)->find();
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
            if($info['lecturer_id'] == 0){
                $info['lecturer_name'] = $info['lecturer_name'];
            }else{
                $info['lecturer_name'] = $info['name'];
            }
            //查询该用户是否有加入过我的课程
            $ret = M('CourseRecord')->where(array('uid'=>$userId,'course_id'=>$course_id))->limit(1)->find();
            if($ret){
                $info['is_joinMyCourse'] = 1;//有加入过我的课程了
            }else{
                $info['is_joinMyCourse'] = 0;//没有加入过我的课程
            }
            if($info){
                return $this->success(1000,'获取数据成功',$info);
            }else{
                return $this->error(1030,'暂无数据返回');
            }
        }
    }

    /**
     * 我的课程-课程详情-获取评论列表
     * @ $course_id  课程id
     */
    public function getCourseComment($course_id,$userId,$page){
        $page = $page ? $page : 1;
        $pageNum = 20;
        $start = ($page - 1) * $pageNum;
        $end = $pageNum;
        $comList = M("colligate_comment")
            ->where("course_id=".$course_id." AND pid=0 AND comment_content!=''")
            ->order("comment_time DESC")->limit($start, $end)->select();

        //获取课程综合评价和数量
        $condition['course_score'] = array('EGT',0);
        $condition['course_id'] = $course_id;
        $where['course_id'] = $course_id;
        $where['pid'] = array('EQ',0);
        $where['comment_content'] = array('NEQ','');
       /* $course_evaluation = M("colligate_comment")->where($where)->AVG('course_evaluation');
        $courseEvaluationCount = M("colligate_comment")->where($where)->count('course_evaluation');
        $course_evaluation = ceil($course_evaluation);*/
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

            $zanStatus = M("course_praise")->where("id=".$value["id"]." AND uid=".$userId)->limit(1)->select();
            if(!$zanStatus){
                $zanStatus = "0";
            }else{
                $zanStatus = $zanStatus[0]["praise"] + 0;
            }
            $comList[$key]["zan_status"] = $zanStatus;//我是否赞过  1已赞 0未赞

            //是否可删除
            $comList[$key]["del_status"] = 0;
            if($userId == $value["uid"]){
                $comList[$key]["del_status"] = 1;
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
                        $sonConsd = M("Colligate_comment a")->join('LEFT JOIN __USERS__ b ON a.uid = b.id')->where(array("a.id" => $sv['pid']))->find();
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
                            $sonCon[$sk]["reply_user"] = $userCache[$sv["id"]];
                        }
                        $sonCon[$sk]["reply_user"] = $sonConsd["username"];
                    }

                    $comList[$key]["sub_list"] = $sonCon;

                }
                //统计所有子评论条数
                $comList[$key]["sub_total"] = count($sonCon);
            }
        }

        $data['courseColligateEvaluation'] = $course_evaluation;
        $data['courseEvaluationCount'] = $courseEvaluationCount;
        $data['comment'] = $comList;
        if($comList){
            return $this->success(1000,'获取数据成功',$data);
        }else{
            return $this->error(1030,'赞无数据返回');
        }
    }
   /* public function getCourseComment($course_id,$uid){
        $condition['a.course_id'] = $course_id;
        if(empty($course_id)){
            return $this->error(1030,'缺少必要参数');
        }else{
            //$condition['a.uid'] = $uid;
            $info = M('Colligate_comment a')
                ->field('a.id as content_id,a.course_id,a.lecturer_evaluation,a.course_evaluation,a.comment_content,a.comment_time,b.id,b.username,b.avatar')
                ->join('LEFT JOIN __USERS__ b ON a.uid = b.id')
                ->where($condition)->select();
            if($info){
                return $this->success(1000,'获取数据成功',$info);
            }else{
                return $this->error(1030,'暂无数据返回');
            }
        }
    }*/


    /*
     * 评论或回复评论
     * 执行评论插入操作
     */
    public function addComment($post,$uid){

        $data['pid'] = $post['id'];//评论id
        $data['course_id'] = $post['course_id'];//课程id
        $data['uid'] = $uid;
        $data['comment_content'] = $post['comment_content'];
        if($data['pid'] == '' ||  $data['course_id'] == ''){
            $this->error(1023,'缺少课程id或评论id');die();
        }
        if($data['comment_content'] == ''){
            return $this->error(1030,'请输入内容');
        }
        $data['comment_time'] = date('Y-m-d H:i:s',time());
        $id = M('colligate_comment')->data($data)->add();
        if($id){
            $info = M('colligate_comment')->where(array('id'=>$id))->find();
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
     *回复评论
     * $uid 评论者id
     *
     *
     */
    public function doReplyComment($post,$uid){
        $data['id'] = $post['id'];
        $data['author_id'] = $uid;
        $data['reply_comment_content'] = $post['content'];
        if(empty($data['id'])){
            return $this->error(1023,'缺少必要参数');die();
        }
        if(empty($data['reply_comment_content'])){
            return $this->error(1023,'缺少必要参数');die();
        }
        $data['reply_comment_time'] = date('Y:m:d H:i:s',time());
        $info = M('CourseReplycomment')->data($data)->add();
        if($info){
            return $this->success(1000,'操作成功',array('id'=>$data['id'],'author_id'=>$uid,'reply_comment_content'=>$data['reply_comment_content'],'reply_comment_time'=>$data['reply_comment_time']));die();
        }else{
            return $this->error(1030,'操作失败');die();
        }
    }



    /**
     *公开课程-课程详情-点赞/取消点赞
     * $uid 点赞者id
     * $id 评论内容id
     */
    public function doPraise($get,$uid){
        $CourseComment = M('CoursePraise');
        $data['uid'] = $uid;
        $data['id'] = $get['id'];//评论id
        settype($get['type'], "string");
        if($get['type'] == '0' || $get['type'] == '1'){
            if($get['type'] == '0'){//取消点赞
                $praise = $CourseComment->where($data)->getField('praise');
                if($praise == 0){
                    return $this->error(1031,'不能重复操作');
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
            return $this->error(1023,'缺少必要参数');die();
        }
    }
    /*
     * 公开课-笔记
     */
    public function getNote($course_id,$uid){
        if(!empty($course_id)){
            $this->error(1030,'缺少必要参数');
        }
        $info = M('CourseNote as a')->field('a.*,b.username,b.avatar')->join('LEFT JOIN __USERS__ b ON a.uid = b.id')->where(array('a.course_id'=>$course_id,'is_public'=>1))->select();
        if($info){
            return $this->success(1000,'获取数据成功',$info);
        }else{
            return $this->error(1030,'赞无数据返回');
        }
    }




}