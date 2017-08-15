<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14
 * Time: 17:10
 */

namespace Mobile\Model;
use Think\Model;

/**
 * Class BannerModel
 * @package Mobile\Model
 * User: @Andy-lizhongjian
 */
class BannerModel extends CommonModel{

    protected $tableName = 'Company_banner';

    public function getCarouselFigure(){
        $date = date('Y-m-d');
        $time =  strtotime($date);
        $company_banner = M('Company_banner')->field("company_name",true)->order("banner_img_site ASC")->select();
        foreach($company_banner as $value){
            $start_time = strtotime($value['start_date']);
            $end_time = strtotime($value['end_date']);
          //判断轮播图片是否在有效时间内
            if(($start_time <= $time) &&  ($time <= $end_time)){
                $_company_banner[] = $value;
            }
        }
        return $_company_banner;
    }
}