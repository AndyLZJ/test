<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/4
 * Time: 13:38
 */

namespace App\Controller;

use Think\Controller;
use Think\Upload;

/*
 * 工作圈控制器
 * lizhongjian
 */
class FriendsCircleController extends CommonController
{
    /*
     * 初始化
     */
    public function __construct() {
        parent::__construct();
    }


    /*
     * 获取工作圈列表
     */
    public function friendsCircleList(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $get['page'] = I('get.page', 1 ,'int');
        $get['pageNum'] =  30;
        $info = D('FriendsCircle')->friendsCircleList($get,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }

    /**
     * 获取回复评论列表
     * $course_id  课程id
     */
    public function getChildComment(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $id = I('get.id','','int');
        $type = I('get.type','','int');
        //$get['pageNum'] =  30;
        $info = D('FriendsCircle')->getChildComment($id,$type,$userId);
        if($info['code'] == 1000){
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }
    /*
     * 发布工作圈
     * $content
     * $images
     */
    public function publicFriendsCircle(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataPost();
            $content = I('post.content','','trim');
            $image = I('post.image','','trim');
            $info = D('FriendsCircle')->checkData($content,$image,$userId);
            if($info['code'] == 1000){
                //发布工作圈触发获取积分
                $res = D('Trigger')->intergrationTrigger($userId,14);
                $this->success($info['code'],$info['message'],$info['data']);

            }else{
                $this->error($info['code'],$info['message']);
            }
    }

    /*
     * 发布工作圈上传图片
     */
    public function uploadImage(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
         $userId = $this->verifyUserDataPost();
            if (!empty($_FILES)) {
                //图片上传设置
                $config = array(
                    'maxSize' => 3145728,
                    'savePath' => '/Upload/friendsCircle/',
                    'rootPath' => './',
                    'saveName' => array('uniqid', ''),
                    'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                    'autoSub' => true,
                    'subName' => array('date', 'Ymd')
                );
                $xhr = $this->uploadImages($config);
                if($xhr){
                    return $this->success(1000,'上传成功',$xhr);
                }else{
                    return $this->error(1025,'上传失败');
                }
            }else{
                return $this->error(1025,'没有文件被上传');
            }
    }

    /*
     * 删除工作圈
     * $id 评论id
     */
    public function deleteFriendsCircle(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $get['id'] = I("get.id",'','int');
        $result  = D("FriendsCircle")->delComment($get,$userId);
        if ($result >= 0) {
            $this->success(1000, '操作成功');
        } else {
            $this->error(1030, '操作失败');
        }
    }

    /*
     * 工作圈评论和回复评论
     * $uid 评论者id
     * $id 内容id
     */
    public function friendsCircleComment(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataPost();
            $post = I('post.');
            $info = D('FriendsCircle')->addComment($post,$userId);
            if($info['code'] == 1000){
                //工作圈评论回复评论触发获取积分
                $res = D('Trigger')->intergrationTrigger($userId,15);
                $this->success($info['code'],$info['message'],$info['data']);
            }else{
                $this->error($info['code'],$info['message']);
            }
    }


    /*
     * 工作圈回复评论（无效）
     * 评论id
     * 回复者id
     */
    public function friendsCircleReplyComment(){
        if(IS_POST){
            $post = I('post.');
            $info = D('FriendsCircle')->replyComment($post);
            if(!empty($info['data'])){
                $this->success($info['code'],$info['message'],$info['data']);
            }else{
                $this->error($info['code'],$info['message']);
            }
        }else{
            $this->error(1013,'错误请求方式');
        }
    }
    
    /*
     * 工作圈点赞
     */
    public function praise(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $get = I('get.');
        $info = D('FriendsCircle')->doPraise($get,$userId);
        if($info['code'] == 1000){
            //工作圈点赞触发获取积分
            $res = D('Trigger')->intergrationTrigger($userId,15);
            $this->success($info['code'],$info['message'],$info['data']);
        }else{
            $this->error($info['code'],$info['message']);
        }
    }
}