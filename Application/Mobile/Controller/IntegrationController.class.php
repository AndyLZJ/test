<?php
/**
 * Created by PhpStorm.
 * User: lizhongjian
 * Date: 2017/3/9
 * Time: 16:38
 * 我的积分控制器
 */

namespace Mobile\Controller;


class IntegrationController extends CommonController
{


    /**
     * 积分规则列表
     */
    public function integrationList(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $info = D('Integration')->integrationlist($userId);
            if($info){
                $this->success(1000,'获取成功',$info);
            }else{
                $this->error(1030,'获取失败');
            }
    }


    /**
     * 申请加分
     */
    public function applyAddStore(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataPost();
            $postData = I('post.');
            $model = M('Integration_apply');
            $info = D('Integration')->applyAddStore($postData,$model,$userId);
            if(!empty($info['data'])){
                $this->success($info['code'],$info['message'],$info['data']);
            }else{
                $this->error($info['code'],$info['message']);
            }
    }


    /*
     * 申请加分上传附件
     */
    public function uploadImg(){
        if(IS_POST){
            $user_id = $this->getUserId();
            if(empty($user_id)){
                $this->error(1024,'用户不存在');die();
            }
            if (!empty($_FILES["file"]["name"])) {
                //图片上传设置
                $config = array(
                    'maxSize' => 3145728,
                    'savePath' => '/Upload/integration/',//保存子目录
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
                    $this->error(1025,'上传失败');
                }
            }else{
                $this->error(1025,'没有文件被上传');
            }
        }else{
            $this->error(1013,'不合法数据请求');
        }
    }


    /*
     * 申请加分记录
     */
    public function applyAddStoreRecord(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $get = I('get.');
        $get['page'] = $get['page'] ? $get['page'] : 1;
        $get['pageNum'] =  15;
        $data = M('integration_apply')
            ->where(array('uid'=>$userId))
            ->limit(($get['page']-1) * $get['pageNum'] . ',' . $get['pageNum'])->order('add_time DESC')->select();
        foreach($data as $k=>$v){
            $data[$k]['add_time'] = date('Y-m-d H:i:s',$v['add_time']);
        }
        if($data){
            $this->success(1000,'获取成功',$data);
        }else{
            $this->error(1030,'暂无数据返回');
        }
    }
    /**
     * 我的积分列表
     */
    public function myIntegration(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $page = I('get.page');
            $data = D('Integration')->myIntegration($page,$userId);
            if($data){
                $this->success(1000,'获取成功',$data);
            }else{
               $this->error(1030,'获取失败');
            }
    }

    /**
     * 兑换积分-福利社
     */
    public function myIntegrationExchange(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
            $userId = $this->verifyUserDataGet();
            $get = I('get.');
            $res = D('Integration')->integrationExchange($get,$userId);
            if($res){
                $this->success($res['code'],$res['message']);
            }else{
                $this->error($res['code'],$res['message']);
            }
    }

    /*
     * 积分记录
     * type 1全部  2获取 3使用
     */
    public function myIntegrationWater()
    {
        if (IS_GET) {
            $get = I('get.');
            $user_id = $this->getUserId();
            if (empty($user_id)) {
                $this->error(1024, '用户不存在');
                die();
            }
            $info = D('Integration')->myIntegrationWater($get, $user_id);

            if (!empty($info)) {
                $this->success(1000, '获取数据成功', $info);
            } else {
                $this->error(1030, '暂无数据返回');
            }
        } else {
            $this->error(1013, '错误请求方式');
        }
    }
}