<?php
namespace App\Controller;

use Think\Controller;
/**
 * @首页公开课
 * @author lizhongjian
 *
 */
class PublicCourseController extends CommonController{

    public function __construct() {
        parent::__construct();
    }

    /**
     * app首页公开课
     */
    public function index(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $get = I('get.');
        $info=D("PublicCourse")->index($get,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }
    
    /**
     * app首页公开课列表课程分类
     */
    public function courseCategory(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $get=I("get.");
            $info = D("PublicCourse")->courseCategory($get,$userId);
            if(!empty($info[0])){
                $this->success(1000,'获取数据成功',$info);
            }else{
                $this->error(1030,'获取数据失败');
            }
    }

    /**
     * app首页公开课列表
     */
    public function publicCourse(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $get['cid'] = I("get.cid",'','int');
            $get['page'] = I("get.page",1,'int');
            $get['course_name'] = I("get.course_name",'','trim');
            $get['type'] = I("get.type",'','int');
            $info = D("PublicCourse")->publicCourse($get,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    //浏览次数
    public function readNum(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $course_id = I('post.course_id','','int');
        if($course_id > 0){
            M('Course')->where(array('id'=>$course_id))->setInc('click_count', 1);
            $click_count = M('Course')->where(array('id'=>$course_id))->getField('click_count');
            $click_count = $click_count ? $click_count : 0;
            $this->success(1023,'获取成功',array('click_count'=>$click_count));
        }else{
            $this->error(1023,'课程id参数有误');
        }
    }
    /*
     * 公开课-课程详情-简介
     * @param $course_id 课程id
     */
    public function courseDetails(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $course_id = I('get.id','','int');
        $info = D('PublicCourse')->getCourseDetails($course_id,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }
    
    /**
     * 公开课-课程详情-目录
     * @param course_id 课程id
     */
    public function courseDetailsList(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $course_id = I('get.id','','int');
        $info = D('PublicCourse')->getCourseDetailsList($course_id,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }


    /**
     * 公开课-课程详情-获取评论列表
     * $course_id  课程id
     */
    public function getCommentList(){
        //判断用户是否存在，提交方式是否为GET
        $userId = $this->verifyUserDataGet();
        $course_id = I('get.id');
        $page = I('get.page','','int');
        $info = D('PublicCourse')->getCourseComment($course_id,$userId,$page);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }



    /**
     * 公开课-课程详情-评论/回复评论
     * $userId 评论者id
     * $id 课程id
     * $course_id 课程course_id
     */
    public function contentComment(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $post['id'] = I('post.id','','int');//评论/回复评论id
        $post['course_id'] = I('post.course_id','','int');//评论/回复评论id
        $post['comment_content'] = I('post.content','','trim');//评论内容
        $info = D('PublicCourse')->addComment($post,$userId);
        if($info['code'] === 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }



    /**
     *公开课-课程详情-回复评论
     * $uid 回复者id
     * $id 评论id
     * (此方法暂无用)
     *
     */
    public function replyComment(){
        if(IS_POST){
            $post = I('post.');
            $user_id = $this->getUserId();
            if(empty($user_id)){
                $this->error(1024,'用户不存在');die();
            }
            $info = D('PublicCourse')->doReplyComment($post,$user_id);
            if(!empty($info['data'])){
                $this->success($info['code'],$info['message'],$info['data']);
            }else{
                $this->error($info['code'],$info['message']);
            }
        }else{
            $this->error(1013,'不合法数据请求');
        }
    }


    /*
    * 我的课程-课程详情-删除评论
    */
    public function deleteComment(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $post["course_id"] = I("post.course_id",'','int');;
        $post['id'] = I("post.id",'','int');
        $result  = D("PublicCourse")->delComment($post,$userId);
        if ($result['code'] == 1000) {
            $this->success($result['code'], $result['message'], $result['data']);
        } else {
            $this->error($result['code'], $result['message']);
        }
    }
    /**
     *公开课-课程详情-点赞/取消点赞
     * $id 发布评论id
     * type 1点赞 0取消点赞
     *
     */
    public function praise(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $get = I('get.');
        $info = D('PublicCourse')->doPraise($get,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }
    /*
     * 公开课-笔记
     */
    public function note(){

        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $course_id = I('get.id');
        $info = D('PublicCourse')->getNote($course_id,$userId);
        if(!empty($info['data'])){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }


   /*
     * 公开课-详情-综合评价
     * $course_id 课程id
     *
    public function colligateComment(){
        if(IS_GET){
            $course_id = I('get.course_id',1);
            $info = D('PublicCourse')->colligateComment($course_id);
            if(!empty($info['data'])){
                $this->success($info['code'],$info['message'],$info['data']);
            }else{
                $this->error($info['code'],$info['message']);
            }
        }else{
            $this->error(1013,'错误请求方式');
        }
    }*/
}