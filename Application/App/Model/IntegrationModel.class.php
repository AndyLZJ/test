<?php
/**
 * Created by PhpStorm.
 * User: lizhongjian
 * Date: 2017/3/9
 * Time: 16:40
 * 我的积分模型
 */

namespace App\Model;

/*@author lizhongjian 20170308
 * 我的积分控制器
 */
class IntegrationModel extends CommonModel
{

    protected $tablePrefix = 'think_';
    protected $tableName = 'integration_rule';
    /**
     * 积分规则列表
     */
    public function integrationList($userId)
    {
        $list = M('integration_rule')->field('id,name,score,oneday_score,type')->select();
        foreach($list as $k=>$v){
            if(strpos($v['oneday_score'],'/') === false){     //使用绝对等于
                //不包含
            }else{
                //包含
                $arr = explode('/',$v['oneday_score']) ;
                $list[$k]['oneday_score'] = intval($arr[0])/intval($arr[1])*30;
            }
        }
         //查看积分规则触发获取积分
        $res = D('Trigger')->intergrationTrigger($userId,4);
        return $list;
    }

    /*
     * 申请加分
     */
    public function applyAddStore($post,$model,$user_id){
        if(isset($post['description'])){
            $postData['apply_description'] = $post['description'];
        }else{
            $postData['apply_description'] = '';
        }
        $postData['attachment'] = $post['attachment'];
        $postData['add_score'] = $post['score'];
        if( $postData['apply_description'] == ''){
            return $this->error(1030,'申请说明不能为空!');
           
        }
        /*if($postData['attachment'] == ''){
            return $this->error(1030,'请先上传附件！');
            
        }*/
        if($postData['add_score'] == ''){
            return  $this->error(1030,'加分分值不能为空！');
            
        }
        if(!(is_int($postData['add_score']) || $postData['add_score'] > 0)){
            return $this->error(1030,'加分分值必须是大于零的整数！');
        }
        $postData['uid'] = $user_id;
        $postData['add_time'] = time();
        $res = $model->add($postData);
        if($res){
            return $this->success(1000,'操作成功',array('id'=>$res));
        }else{
            return $this->error(1030,'操作失败');
        }

    }


    /**
     * 我的积分列表
     */
    public function myIntegration($page,$user_id)
    {
        //我的积分模块的列表页显示：总积分，可用积分，本月积分
        $page = $page ? $page : 1;
        $pageNum =  20;
        $where1 = array(
            'uid'=>$user_id,
            'score'=>array('gt',0),
            '_logic'=>'and'
        );
        $where2 = array(
            'uid'=>$user_id,
        );
        $where3 = array(
            'uid'=>$user_id,

        );
        //计算总积分
        $all_score = M('integration_record')->where($where1)->sum('score');
        //计算可用积分
        $available_score = M('integration_record')->where($where2)->sum('score');
        $available_score = $available_score<0 ? 0 : $available_score;
        //本月积分
        $months = date("Ym");
        //$this_month_score = M('integration_record')->field("FROM_UNIXTIME(time,'%Y%m')")->where($where3)->sum('score');
        //$this_month_score = $this_month_score<0 || $this_month_score =='' ? 0 : $this_month_score;
        $this_month_score = M('integration_record')->field("uid,sum(score) as sumscore,FROM_UNIXTIME(time,'%Y%m') months")->where($where3)->group('months')->having('months='.$months)->select();
        $this_month_score = $this_month_score[0]['sumscore'];
        if($this_month_score < 0){
            $this_month_score = 0;
        }
        $personInfo = M('Users')->field('id,username,avatar,phone')->where(array('id'=>$user_id))->find();
        //福利社的展示
        $list = M('welfare')->where(array('is_delete'=>0,'is_public'=>1))->limit(($page-1) * $pageNum . ',' . $pageNum)->order('add_time DESC')->select();
        $data = array(
            'personInfo' => $personInfo,
            'all_score'=>$all_score,
            'available_score'=>$available_score,
            'this_month_score'=>$this_month_score,
            'list'=>$list
        );
        return $data;
    }



    /**
     * 我的积分兑换
     */
    public function integrationExchange($get,$user_id){
        $name = $get['name'];
        $need_score = $get['need_score'];
        $where['uid'] = array('eq',$user_id);
       if(empty($name)){
           return $this->error(1030,'缺少必要参数');
       }
        if($need_score == ''){
            return $this->error(1030,'缺少必要参数');
        }
        if(!(is_int($need_score) || $need_score >= 0)){
            return $this->error(1030,'所需积分必须是非负整数');
        }
        //本月可用积分
        $months = date("Ym");
        $this_month_score = M('integration_record')->field("uid,sum(score) as sumscore,FROM_UNIXTIME(time,'%Y%m') months")->where($where)->group('months')->having('months='.$months)->select();
        $this_month_score = $this_month_score[0]['sumscore'];
        if($this_month_score < 0){
            $this_month_score = 0;
        }
        $available_score = M('integration_record')->where(array('uid'=>$user_id))->sum('score');

        if($available_score >= $need_score){

            // 自动启动事务支持
            $this->startTrans();
            try {
                //把相应的兑换记录插入积分记录表
                $arr = array(
                    'time'=>time(),
                    'uid'=>$user_id,
                    'score'=>$need_score*(-1),
                    'type'=>'积分兑换',
                    'describe'=>'积分兑换-'.$name,
                ) ;
                $ret = M('integration_record')->add($arr);
                if (false === $ret) {
                    // 发生错误自动回滚事务
                    $this->rollback();
                    return $this->error(1030,'系统错误');
                }
                //把相应的兑换记录插入福利记录表
                $map = array(
                    'name'=>$name,
                    'uid'=>$user_id,
                    'need_score'=>$need_score,
                    'time'=>time()
                ) ;
                $ret = M('welfare_record')->add($map);
                if (false === $ret) {
                    // 发生错误自动回滚事务
                    $this->rollback();
                    return $this->error(1030,'系统错误');
                }
                // 提交事务
                $this->commit();
                return $this->success(1000,'操作成功');
            } catch (ThinkException $e) {
                $this->rollback();
            }
        }else{
            //    $res = '可用积分不足！';
            return $this->error(1030,'可用积分不足');
        }

    }


    /*
     * 积分记录 type 1全部 2获取  3使用
     * page 分页参数
     */
    public function myIntegrationWater($get,$user_id){

        if(empty($get['page'])){
            $get['page'] = 1;
        }else{
            $get['page'] =$get['page'];
        }

        $get['pageNum'] = 20;
        $type = intval($get['type']);
        if(empty($type)){
            return $this->error(1030,'缺少必要参数');
        }
        $months = date('Ym');
        $where = array(
            'a.uid'=>$user_id,
            '_logic'=>'and',
            'a.score' => array('gt',0)
        );
        $month_get_score = M('integration_record a')->field("a.score,a.time,a.describe,a.uid,sum(a.score) as sumscore,FROM_UNIXTIME(time,'%Y%m') months")
            ->where($where)->group('months')->having('months='.$months)->select();

        //当月使用的积分
        $wheres = array(
            'a.uid'=>$user_id,
            '_logic'=>'and',
            'a.score' => array('lt',0)
        );


        $month_use_score = M('integration_record a')->field("a.score,a.time,a.describe,a.uid,sum(a.score) as sumscore,FROM_UNIXTIME(time,'%Y%m') months")
            ->where($wheres)->group('months')->having('months='.$months)->select();
        switch($type){
            case 1;
                //“全部”积分列表
                //当月获取的积分
                $condition = array(
                    'a.uid'=>$user_id,
                );
                $month_all_score = M('integration_record a')->field("a.*,FROM_UNIXTIME(time,'%Y%m') months")->where($condition)
                    ->limit(($get['page']-1) * $get['pageNum'] . ',' . $get['pageNum'])->order('id DESC')->select();
                foreach($month_all_score as $key=>$v){
                    $month_all_score[$key]["time"] = date('Y-m-d',$v['time']);

                }
                $list = array(
                    'month_get_score' => $month_get_score[0]['sumscore'],//当月总获取积分
                    'month_use_score' => $month_use_score[0]['sumscore'],//当月总使用积分
                    'newArr'     =>  $month_all_score
                );
                return $list;
                break;

            case 2;

                $condition = array(
                    'a.uid'=>$user_id,
                    'a.score' => array('gt',0)
                );
                $month_all_score = M('integration_record a')->field("a.*,FROM_UNIXTIME(time,'%Y%m') months")->where($condition)
                    ->limit(($get['page']-1) * $get['pageNum'] . ',' . $get['pageNum'])->order('id DESC')->select();
                foreach($month_all_score as $key=>$v){
                    $month_all_score[$key]["time"] = date('Y-m-d',$v['time']);

                }
                $list = array(
                    'month_get_score' => $month_get_score[0]['sumscore'],//当月总获取积分
                    'newArr'     =>  $month_all_score
                );
                return $list;

                break;

            case 3;
                $condition = array(
                    'a.uid'=>$user_id,
                    'a.score' => array('lt',0)
                );
                $month_all_score = M('integration_record a')->field("a.*,FROM_UNIXTIME(time,'%Y%m') months")->where($condition)
                    ->limit(($get['page']-1) * $get['pageNum'] . ',' . $get['pageNum'])->order('id DESC')->select();

                foreach($month_all_score as $key=>$v){
                    $month_all_score[$key]["time"] = date('Y-m-d',$v['time']);

                }
                $list = array(
                    'month_use_score' => $month_use_score[0]['sumscore'],//当月总使用积分
                    'newArr'     =>  $month_all_score
                );
                return $list;
                break;
        }

    }


}