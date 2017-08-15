<?php
namespace Mobile\Controller;
use Think\Controller;
use Think\Upload;
class BannerController extends CommonController {

    public function __construct() {
        parent::__construct();
    }


    /**
     * 获取首页banner轮播图
     */
    public function getCarouselFigure(){
        //判断用户是否存在,获取用户id,判断提交方式是否合法
        $userId = $this->verifyUserDataGet();
        $company_banner = D('Banner')->getCarouselFigure();
        if($company_banner){
            $this->success(1000,'获取成功',$company_banner);
        }else{
            $this->error(1030,'暂无数据返回');
        }
    }
}