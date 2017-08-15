<?php
namespace Mobile\Controller;

use Think\Controller;
   /**
    * @导航栏(我的入口)
    * @author lizhongjian
    *
    */
class MineController extends CommonController {
    
    /**
     * 初始化
     */
    function __construct(){
        parent::__construct();
    }
    

  

  
  /**
   * 我的课程
   * @param typeId 1必修   2选修  默认为1必修
   */
    public function myCourse(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $page = I('get.page', 1, 'int');//分页参数
        $typeId = I("get.typeId",1 ,'int');
        $info = D('Mine')->myCourse($typeId,$page,$userId);
        if($info['list']){
            $this->success(1000,'获取数据成功',$info);
        }else{
            $this->error(1030,'暂无数据返回');
        }
    }

    /*
     * 我的-导航页面
     */
    public function mine(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $info = D('Mine')->mine($userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }
    /**
     * 我的课程-课程详情-简介
     * @$course_id  课程id
     * @$pid  课程关联项目id
     */
    public function briefIntroduction(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        //如果typeId为1就是必修课程 否则就为选修课程 默认为必修
        $typeId = I('get.typeId','','int');
        $course_id = I('get.id','','int');
        $pid = I('get.project_id','','int');
        if($typeId == ''){
            return $this->error(1023,'参数有误');
        }
        if($typeId == 1){

            $info = D('Mine')->getCourseDetails($course_id,$pid,$userId);

        }else if($typeId == 2){

            $info = D('Mine')->getBriefIntroduction($course_id,$userId);
        }
        if($info['code'] == 1000){

            $this->success($info['code'],$info['message'],$info['data']);

        }else{

            $this->error($info['code'],$info['message']);
        }
    }

    /**
     * 我的课程-课程详情-目录
     * @$course_id  课程id
     */
    public function courseList(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $course_id = I('get.id','','int');
            $project_id = I('get.pid','','int');
            $info = D('Mine')->getCourseList($course_id,$project_id,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }


    /**
     * 我的课程-课程详情-获取评论列表
     * $course_id  课程id
     */
    public function getCommentList(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        //接收课程id
        $course_id = I('get.id','','int');
        $page = I('get.page', 1 ,'int');
        $info = D('Mine')->getCourseComment($course_id,$userId,$page);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    /**
     * 获取课程回复评论列表
     * $course_id  课程id
     */
    public function getChildComment(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        //评论回复评论id
        $id = I('get.id','','int');
        //课程id
        //$course_id = I('get.course_id','','int');
        //类型1 为点击主评论  其他则是点击子评论或回复评论
        $type = I('get.type','','int');
        $info = D('Mine')->getChildComment($id,$type,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }


    /**
     * 我的课程-课程详情-评论/回复评论
     * $uid 发布评论者id
     * $id 内容id
     */
    public function contentComment(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $post['id'] = I('post.id','','int');//评论/回复评论id
        $post['course_id'] = I('post.course_id','','int');//评论/回复评论id
        $post['comment_content'] = I('post.content','','trim');//评论内容
        $info = D('Mine')->addComment($post,$userId);
        if($info['code'] === 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }



    /**
     * 我的课程-课程详情-对课程，讲师评价(发布评论)
     * $course_id 课程id
     */
    public function comment(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $is_mark = I('post.is_mark','','int');
        $post['course_id'] = I('post.id','','int');
        $post['lecturer_score'] = I('post.lecturer_comment',0,'int');//讲师评价等级1,2,3,4,5,0
        $post['course_score'] = I('post.course_comment',0,'int');////课程评价等级1,2,3,4,5,0
        $post['comment_content'] = I('post.comment_content','','htmlspecialchars');

        if($is_mark != ''){

            if($is_mark != 0 && $is_mark != 1){
                $this->error(1030, 'is_mark参数有误');
            }

            if(empty($post['course_id']) || empty($post['comment_content'])){
                $this->error(1030, '课程id或评价内容参数有误');
            }

            if($post['course_id'] < 1){
                $this->error(1030, '课程id参数有误');
            }
            $result = D('Mine')->doComment($post,$userId,$is_mark);
            if ($result) {
                $this->success(1000,'操作成功');
            } else {
                $this->error(1030, '您对该课程已经评过星级了');
            }
        }else{

            if(empty($post['course_id']) || empty($post['comment_content'])){
                $this->error(1030, '课程id或评价内容参数有误');
            }

            if($post['course_id'] < 1){
                $this->error(1030, '课程id参数有误');
            }

            $result = D('Mine')->doComment($post,$userId);
            if ($result) {
                $this->success(1000,'操作成功');
            } else {
                $this->error(1030, '您对该课程已经评过星级了');
            }
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
        $result  = D("Mine")->delComment($post,$userId);
        if ($result['code'] == 1000) {
            $this->success($result['code'], $result['message'], $result['data']);
        } else {
            $this->error($result['code'], $result['message']);
        }
    }

    /**
     *我的课程-课程详情-点赞/取消点赞
     * $uid 用户id
     * $course_id 课程id
     * type 1点赞 0取消点赞
     *
     */
    public function praise(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $get = I('get.');
            $info = D('Mine')->doPraise($get,$userId);
            if(empty($info['data'])){
                $this->error($info['code'],$info['message']);
            }else{
                $this->success($info['code'],$info['message'],$info['data']);
            }
    }

    /**
     * 课程详情-添加至我的课程
     * @param course_id 课程id
     */
    public function addMyCourse(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $course_id = I('get.id','','int');
            $info = D('Mine')->addMyCourse($course_id,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    /**
     * 我的课程-课程详情-新建笔记
     *$uid  当前登录用户
     * course_id 课程id
     * content 笔记内容
     */
    public function createNote(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $post = I('post.');
        $post['id'] = I('post.id','','int');
        $post['content'] = I('post.content');
        $post['is_public'] = I('post.is_public','','int');
            $result = D('Mine')->executeCreateNote($post,$userId);
            if($result['code'] == 1000){
                $this->success($result['code'],$result['message'],$result['data']);
            }else{
                $this->error($result['code'],$result['message']);
            }
    }

    /*
     * 笔记
     *
     */
    public function note(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $course_id = I('get.id','','int');
        $info = D('Mine')->note($course_id,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }



    /**
     * 资讯
     */
   public function news(){
       //判断用户是否存在,获取用户id,判断提交方式是否合法
       $userId = $this->verifyUserDataGet();
       $news = M('News');
       $res_news = $news->order('id DESC')->select();
       foreach($res_news as $k => $v){
           $res_news[$k]['content'] =  str_replace('&nbsp;','',strip_tags($v['content']));
       }
       if($res_news){
           $this->success(1000,'获取成功',$res_news);
       }else{
           $this->error(1030,'暂无数据返回');
       }
   }


    /**$id 资讯id
     * 资讯详情(已做了h5)
     */
    public function newsDetail(){
        $user_id = $this->getUserId();
        if(empty($user_id)){
            $this->error(1024,'用户不存在');die();
        }
        $news_id = I('request.id','','intval');
        if(!empty($news_id)){
            $news = M('News');
            $result = $news->where(array('id'=>$news_id))->find();
            $this->success(1000,'获取成功',$result);
        }else{
            $this->error(1023,'缺少必要参数');
        }

    }

    /**
     * 我的关注
     * $id 课程id
     * $is_public  是否公开 0不公开 1公开
     *$care_status 1关注 2取消关注
     */
    public function  concern(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $data['course_id'] = I('request.id','','intval');
        $data['care_status'] = I('request.status','','intval');
        $info = D('Mine')->checkConcernData($data,$userId);

        if($info['code'] == 1000){
            $this->error($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }


    /**
     * 我的关注
     * $id 课程id
     *
     */
    public function myConcern(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        //根据uid
        $info = D('Mine')->getMyConcern($userId);
        $this->success(1000,'获取成功',$info);
    }

    /*
     * 资讯列表
     */
    public function newsList(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $info = M('News')->order('id DESC')->select();
        foreach($info as $key => $val){
            $info[$key]['content'] = str_replace('&nbsp;','',strip_tags($val['content']));;
        }
        if($info){
            $this->success(1000,'获取成功',$info);
        }else{
            $this->error(1030,'无数据返回');
        }
    }
    /**
     * 新建资讯
     */
    public function createNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
       //接收表单字段
        $post = I('post.');
        $res = D('Mine')->checkData($post,$userId,1);
        if(empty($res['data'])){
            $this->error($res['code'],$res['message']);
        }else{
            $this->error($res['code'],$res['message'],$res['data']);
        }
    }


    /**
     * 编辑资讯
     */
    public function editNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
       //接收表单字段
        $post = I('post.');
        $res = D('Mine')->checkData($post,$userId,2);
        if(empty($res['data'])){
            $this->error($res['code'],$res['message']);
        }else{
            $this->error($res['code'],$res['message'],$res['data']);
        }
    }

    /**
     * 资讯管理上传图片
     */
    public function uploadImg(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataPost();
            $user_id = $this->getUserId();
            if(empty($user_id)){
                $this->error(1024,'用户不存在');die();
            }
            if (!empty($_FILES["file"]["name"])) {
                    //图片上传设置
                $config = array(
                        'maxSize' => 3145728,
                        'savePath' => '/Upload/news/',//保存子目录
                        'rootPath' => './',//保存根目录
                        'saveName' => array('uniqid', ''),
                        'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                        'autoSub' => false,
                        'subName' => array('date', 'Ymd')
                    );
                        $msg = $this->uploadImages($config);
                        //保存图片路径
                        if($msg){
                            $this->success(1000,'上传成功',$msg);
                        }else{
                            $this->error(1025,'上传失败');
                        }
                }else{
                    $this->error(1025,'没有文件被上传');
                }
    }


    /*
     * 删除资讯
     * (可单条删除可批量删除)
     */
    public function deleteNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $id = I('get.id');
            $info = D('Mine')->delete($id);
            if($info){
                return $this->success(1000,'操作成功');
            }else{
                return $this->error(1030,'操作失败');
            }
    }

    /*
     * 消息-获取系统消息
     * user_type 用户类型
     */
    public function systemNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $page = I('get.page',1,'int');
        $info = D('Mine')->getSystemNews($userId,$page);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    /**
     * 消息-更改系统消息状态
     * $id 消息id
     */
    public function changeSystemNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        $info = D('Mine')->changeSystemNews($id,$status);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }


    /**
     * 消息-已读系统消息详情
     * $id 消息id
     */
    public function SystemNewsDetail(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $id = I('get.id','','int');
        $info = D('Mine')->getSystemNewsDetail($id);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    /**
     * 学习目标
     * typeId 类别(0-必修,1-选修,2-修读)
     */
   public function learningTarget(){
       $userId = $this->verifyUserDataGet();
       $typeId = I('get.typeId',1,'int');
       $page = I('get.page',1,'int');
       $token = I('get.token');
       $secret_key = I('get.secret_key');
       $info = D('Mine')->learningTarget($typeId,$page,$userId);
       //dump($info);exit;
       $info['token'] = $token;
       $info['secret_key'] = $secret_key;

       if($typeId == 1){

           $this->assign('items',$info);
           $this->display('objectiveone');
       }

       if($typeId == 2){

           $this->assign('items',$info);
           $this->display('objectivetwo');
       }

       if($typeId == 3){
           $this->assign('items',$info);
           $this->display('objectivethree');
       }
   }
    /*
     * 消息-互动消息
     */
    public function interactNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $model = M('Friends_circle');
        $page = I('get.page',1,'int');
        $info = D('Mine')->interactNews($userId,$model,$page);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    /**
     * 消息-更改互动消息状态
     * $id 消息id
     */
    public function changeInteractNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $id = I('get.id','','int');
        $status = I('get.status','','int');
        $type = I('get.type','','int');//1工作圈消息 2课程消息
        $info = D('Mine')->changeInteractNews($id,$status,$type);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }


    /**
     * 消息-已读互动消息详情
     * $id 消息id
     */
    public function interactNewsDetail(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $id = I('get.id','','int');
        $info = D('Mine')->getInteractNewsDetail($id,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    /*
     * 我的学分
     */
    public function  credit(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $model = M('Center_study');
        $info = D('Mine')->credit($userId,$model);
        if($info){
            $this->success(1000,'获取数据成功',$info);
        }else{
            $this->error(1030,'无数据返回');
        }
    }


    /*
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
    public function recordVideoTimeLong(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $course_id = I("post.id",'','int');//课程id
        $pid = I("post.pid",'','int');//课程关联项目id，如果有就传
        $fileName = I("post.name",'','trim');//课程章节名称
        $path = I('post.src','','trim');//课程章节链接
        $type = I("post.type",'','int');//课程章节类型 1视频 2ppt 3文档
        $timeLen = I("post.timeLen",'','int');//学习时长
        $time_percent = I("post.time_percent",'','int');//学习进度(按照百分比计算)

        $course_id += 0;
        $type += 0;
        if(!is_int($course_id) || $course_id < 1){

            return $this->error(1012,"课程id有误");
        }

        $pid += 0;
        if(!is_int($pid)){

            return $this->error(1012, "项目id有误");

        }

        if($fileName == ''){

            return $this->error(1013, "请提交章节名称");

        }

        if($timeLen == ''){

            $time_percent = 0;

        }

        if($time_percent == ''){

            $time_percent += 0;//是数值类型

        }
        $resp = D("Mine")->recordVideoTimeLong($userId,$course_id,$pid,$fileName,$path,$type,$timeLen,$time_percent);
        if(!$resp){
            return  $this->error(1030,'操作失败');
        }else{
            return  $this->success(1000,'操作成功');
        }
    }


    /*
     * 记录视频，文档下载的数据
     * uid 用户id
     * course_id 课程id
     * project_id 项目id(如果有就传，否则不用传)
     * name 章节名称
     * src 附件地址
     * type 附件类型
     *
     */
    public function recordData(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $uid = I('get.uid','','int');
        $course_id = I('get.course_id','','int');
        $chapter_id = I('get.chapter_id',0,'int');
        $project_id = I('get.project_id',0,'int');
        if($project_id < 0){
            $project_id = 0;
        }
        $name = I('get.name','','trim');
        $path = I('get.src','','trim');
        $type = I('get.type','','int');
        $style = I('get.style','','trim');
        if($uid == ''){
            $this->error(1030,'未知用户');
        }
        if($course_id == ''){
            $this->error(1030,'缺少课程id');
        }
        if($name == ''){
            $this->error(1030,'缺少课程章节名称');
        }
        if($path == ''){
            $this->error(1030,'缺少课程章节链接');
        }
        if($type == ''){
            $this->error(1030,'缺少课程章节类型');
        }
        $info = D('Mine')->recordData($uid,$course_id,$chapter_id,$project_id,$name,$path,$type,$style);
        if($info == 1){
            $this->success(1000,'下载成功');
        }else if($info == 2){
            $this->error(1030,'已经下载过');
        }
    }


    /*
     * 获取缓存文件
     */
    public function getRecordData(){

        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $info = D('Mine')->getRecordData($userId);
        if($info){
            $this->success(1000,'获取成功',$info);
        }else{
            $this->error(1030,'暂无数据返回');
        }
    }

    /*
     * 删除缓存(单条或多条删除)
     */
    public function delRecordData(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        //该参数是一个用英文逗号分隔开的字符串
        $delId = I('post.id','','trim');
        $delId_arr = explode(',',$delId);
        if(is_array($delId_arr)){
            $where['id'] = array('in',$delId_arr);
            $info = M('File_download')->where($where)->delete();
            if($info){
                $this->success(1000,'操作成功');
            }else{
                $this->error(1030,'操作失败');
            }
        }
    }
}