<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 11:19
 */

namespace Mobile\Controller;
use Think\Controller;
/**
 * @资讯控制器
 * @2017-06-15 11:36:50
 * @author lizhongjian
 *
 */
class NewsController extends CommonController{
   public function __construct(){
       parent::__construct();
   }


    /**
     * @获取资讯列表
     * @
     */
    public function getNewsList(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        //接收资讯模板参数
        $type = I('get.type',1,'trim,htmlspecialchars,int');
        //接收分页参数
        $page = I('get.page',1,'trim,htmlspecialchars,int');
        //设置每页显示数据条数
        $total = 15;
        if($page < 1){
            $this->error(1030,'分页参数有误');
        }
        $page = $page-1;
        $result = D('News')->getNewsList($type,$page,$total,$userId);
        if($result['code'] == 1000){
            $this->success(1000,$result['message'],$result['data']);
        }else{
            $this->success(1030,$result['message']);
        }

    }
    /**
     * 资讯管理
     * 创建资讯
     */
    public function createUpdateNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $method = I('post.method','','trim,htmlspecialchars,int');
        $data['template'] = I('post.template','','trim,htmlspecialchars,int');
        $data['type'] = I('post.type','','trim,htmlspecialchars,int');
        $data['title'] = I('post.title','','trim,htmlspecialchars');
        $data['content'] = I('post.content','','trim,htmlspecialchars');
        $data['img'] = I('post.image','','trim,htmlspecialchars');
        $News = D('News');
        if(!$News->create($data,1)){
            //数据验证不通过则抛出错误
            $this->error(1030,$News->getError());
        }else{
            //验证通过，则进行数据操作
            $result = $News->createUpdateNews($data,$method,$userId);
           if($result['code'] == 1000){
               $this->success(1000,$result['message'],$result['data']);
           }else{
               $this->success(1030,$result['message']);
           }
        }
    }


    /**
     * 获取编辑资讯信息
     */
    public function getNewsInfo(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $id = I('get.id','','trim,htmlspecialchars,int');
        if($id == "" || $id < 1){
            $this->error(1030,'编辑id参数有误');
        }else{
            $result = M('News')->field('id,template,type,title,content,img')->where(array('id'=>$id))->find();
            if($result){
                $this->success(1000,'操作成功',$result);
            }else{
                $this->error(1030,'操作失败');
            }
        }
    }

    /**
     * @资讯删除(支持单条或批量删除)
     * @$arrayId 数组id
     */
    public function deleteNews(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        $strId = I('post.id');
        $info = D('News')->deleteNews($strId);
        if($info['code'] == 1000){
             $this->success(1000,$info['message']);
        }else{
             $this->error(1030,$info['message']);
        }
    }

    /**
     * 资讯上传图片
     */
    public function uploadFile(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataPost();
        if(empty($_FILES["file"]["name"])){
            $this->error(1030,'没有文件被上传');
        }else{
            //图片上传设置
            $config = array(
                'maxSize' => 3145728,
                'savePath' => '/Upload/news/',//保存子目录
                'rootPath' => './',//保存根目录
                'saveName' => array('uniqid', ''),
                'exts' => array('jpg', 'gif', 'png', 'jpeg'),
                'autoSub' => true,
                'subName' => array('date', 'Ymd')
            );
            $msg = $this->uploadImages($config);
            //保存图片路径
            if($msg){
                $this->success(1000,'上传成功',$msg);
            }else{
                $this->error(1030,'上传失败');
            }
        }
    }
}