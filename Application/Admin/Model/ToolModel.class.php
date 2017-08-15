<?php

namespace Common\Model;

use Common\Model\BaseModel;

class ToolModel extends BaseModel
{

	/*
	 * 初始化
	 */
    function __construct(){}

    /**
     * 组织构架左侧树形
     */
    public function tree($pid){

        $where['a.user_id'] = array("eq",$_SESSION['user']['id']);

        //获取用户上级组织名称
        $group_data = M("tissue_group_access a")
			->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
			->field("b.id,b.pid")
			->order('b.id asc')
			->where($where)
			->find();

        $treeInfo = D('AdminTissue')->tree($group_data['id']);

        return array($treeInfo);
    }

    /**
     * 获取岗位
     */
    public function getJob(){

        $tissue_id = I("get.tissue_id",0,'int');

        $job_list = M("tissue_group_access")->field("job_id")->where("tissue_id=".$tissue_id)->select();

        if(!empty($job_list)){

            foreach($job_list as $item){
                $jobId[] = $item['job_id'];
            }

            $str_jobid = implode(",",$jobId);

            $where['id'] = array("in",$str_jobid);

            $jobs_manage = M("jobs_manage")->field("id,name")->where($where)->select();


        }else{

            $jobs_manage = array();

        }

        return $jobs_manage;

    }

    /**
     * 获取所属组织下所有讲师
     */
    public function getLecturer(){

        $tissue_id = I("get.tissue_id",0,'int');

        $user_id_list = M("tissue_group_access")->field("user_id")->where("tissue_id=".$tissue_id)->select();

        if(!empty($user_id_list)){

            foreach($user_id_list as $item){
                $user_idId[] = $item['user_id'];
            }

            $where['user_id'] = array("in",$user_idId);

            $lecturer_data = M("lecturer")->field("id,name")->where($where)->select();

        }else{

            $lecturer_data = array();

        }

        return $lecturer_data;
    }

    /**
     * 获取学习目标
     */
    public function target(){

        $tissue_id = I("get.tissue_id",0,'int');

        $year = I("get.year",0,'int');

        $job_id = I("get.job_id",0,'int');

        $typeid = array(0,1,2,3,4);

        if(!empty($tissue_id) and !empty($year) and !empty($job_id)){

            foreach($typeid as $id){

                $data['typeid']  = $id;
                $data['tissue_id'] = $tissue_id;
                $data['job_id'] = $job_id;
                $data['year'] = $year;

                $is_data = M("tool_learning")->where($data)->find();
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$data['id'] = getNextId('tool_learning');
				}
				
                if(empty($is_data)){
                    M('tool_learning')->add($data);
                    $rows[$id] = M("tool_learning")->where($data)->find();
                }else{
                    $rows[$id] = $is_data;
                }
            }

        }else{

            $rows = array();

        }

        return $rows;
    }

    /**
     * 修改目标管理学时
     */
    public function uptarget(){

        $post = I("post.");

        $where = array(
            "typeid"=>$post['typeid'],
            "tissue_id"=>$post['tissue_id'],
            "job_id"=>$post['job_id'],
            "year"=>$post['year'],
        );

        $items = M("tool_learning")->where($where)->find();

        if($post['planid'] == 1){
            //年度
            if($items['planid'] == $post['planid'] or empty($items['planid'])){

                $post['v'] = round(($post['v'] / 12),1);

                $data = array(
                    "january"=>$post['v'],
                    "february"=>$post['v'],
                    "march"=>$post['v'],
                    "april"=>$post['v'],
                    "may"=>$post['v'],
                    "june"=>$post['v'],
                    "july"=>$post['v'],
                    "august"=>$post['v'],
                    "september"=>$post['v'],
                    "october"=>$post['v'],
                    "november"=>$post['v'],
                    "december"=>$post['v'],
                    "year_data"=>$post['v'],
                    "planid"=>$post['planid']
                );

            }else{

                $result = $items['planid'];

            }


        }elseif($post['planid'] == 2){

            //季度
            if($items['planid'] == $post['planid'] or empty($items['planid'])){

                $post['v'] = round(($post['v'] / 3),1);

                switch($post['month']){
                    case 1:
                        $data['january'] = $post['v'];
                        $data['february'] = $post['v'];
                        $data['march'] = $post['v'];
                        break;
                    case 2:
                        $data['april'] = $post['v'];
                        $data['may'] = $post['v'];
                        $data['june'] = $post['v'];
                        break;
                    case 3:
                        $data['july'] = $post['v'];
                        $data['august'] = $post['v'];
                        $data['september'] = $post['v'];
                        break;
                    case 4:
                        $data['october'] = $post['v'];
                        $data['november'] = $post['v'];
                        $data['december'] = $post['v'];
                        break;
                }

                $data['planid'] = $post['planid'];


            }else{

                $result = $items['planid'];

            }


        }else{
            //月度
            if($items['planid'] == $post['planid'] or empty($items['planid'])){

                switch($post['month']){
                    case 1:
                        $data['january'] = $post['v'];
                        break;
                    case 2:
                        $data['february'] = $post['v'];
                        break;
                    case 3:
                        $data['march'] = $post['v'];
                        break;
                    case 4:
                        $data['april'] = $post['v'];
                        break;
                    case 5:
                        $data['may'] = $post['v'];
                        break;
                    case 6:
                        $data['june'] = $post['v'];
                        break;
                    case 7:
                        $data['july'] = $post['v'];
                        break;
                    case 8:
                        $data['august'] = $post['v'];
                        break;
                    case 9:
                        $data['september'] = $post['v'];
                        break;
                    case 10:
                        $data['october'] = $post['v'];
                        break;
                    case 11:
                        $data['november'] = $post['v'];
                        break;
                    case 12:
                        $data['december'] = $post['v'];
                        break;
                }

                $data['planid'] = $post['planid'];


            }else{

                $result = $items['planid'];

            }

        }


        if(isset($data)){

            M("tool_learning")->where($where)->setField($data);

            $result = 200;
        }

        return $result;

    }

    /**
     * 获取授课目标
     */
    public function teaching(){

        $tissue_id = I("get.tissue_id",0,'int');

        $year = I("get.year",0,'int');

        $lecturer_id = I("get.lecturer_id",0,'int');

        $typeid = array(0,1,2);

        if(!empty($tissue_id) and !empty($year) and !empty($lecturer_id)){

            foreach($typeid as $id){

                $data['typeid']  = $id;
                $data['tissue_id'] = $tissue_id;
                $data['lecturer_id'] = $lecturer_id;
                $data['year'] = $year;

                $is_data = M("tool_teaching")->where($data)->find();

                if(empty($is_data)){
                    M('tool_teaching')->add($data);
                    $rows[$id] = M("tool_teaching")->where($data)->find();
                }else{
                    $rows[$id] = $is_data;
                }
            }

        }else{

            $rows = array();

        }

        return $rows;

    }

    /**
     * 修改授课目标
     */
    public function upteaching(){

        $post = I("post.");

        $where = array(
            "typeid"=>$post['typeid'],
            "tissue_id"=>$post['tissue_id'],
            "lecturer_id"=>$post['lecturer_id'],
            "year"=>$post['year'],
        );

        $items = M("tool_teaching")->where($where)->find();

        if($post['planid'] == 1){
            //年度
            if($items['planid'] == $post['planid'] or empty($items['planid'])){

                $post['v'] = round(($post['v'] / 12),1);

                $data = array(
                    "january"=>$post['v'],
                    "february"=>$post['v'],
                    "march"=>$post['v'],
                    "april"=>$post['v'],
                    "may"=>$post['v'],
                    "june"=>$post['v'],
                    "july"=>$post['v'],
                    "august"=>$post['v'],
                    "september"=>$post['v'],
                    "october"=>$post['v'],
                    "november"=>$post['v'],
                    "december"=>$post['v'],
                    "year_data"=>$post['v'],
                    "planid"=>$post['planid']
                );

            }else{

                $result = $items['planid'];

            }


        }elseif($post['planid'] == 2){

            //季度
            if($items['planid'] == $post['planid'] or empty($items['planid'])){

                $post['v'] = round(($post['v'] / 3),1);

                switch($post['month']){
                    case 1:
                        $data['january'] = $post['v'];
                        $data['february'] = $post['v'];
                        $data['march'] = $post['v'];
                        break;
                    case 2:
                        $data['april'] = $post['v'];
                        $data['may'] = $post['v'];
                        $data['june'] = $post['v'];
                        break;
                    case 3:
                        $data['july'] = $post['v'];
                        $data['august'] = $post['v'];
                        $data['september'] = $post['v'];
                        break;
                    case 4:
                        $data['october'] = $post['v'];
                        $data['november'] = $post['v'];
                        $data['december'] = $post['v'];
                        break;
                }

                $data['planid'] = $post['planid'];


            }else{

                $result = $items['planid'];

            }


        }else{
            //月度
            if($items['planid'] == $post['planid'] or empty($items['planid'])){

                switch($post['month']){
                    case 1:
                        $data['january'] = $post['v'];
                        break;
                    case 2:
                        $data['february'] = $post['v'];
                        break;
                    case 3:
                        $data['march'] = $post['v'];
                        break;
                    case 4:
                        $data['april'] = $post['v'];
                        break;
                    case 5:
                        $data['may'] = $post['v'];
                        break;
                    case 6:
                        $data['june'] = $post['v'];
                        break;
                    case 7:
                        $data['july'] = $post['v'];
                        break;
                    case 8:
                        $data['august'] = $post['v'];
                        break;
                    case 9:
                        $data['september'] = $post['v'];
                        break;
                    case 10:
                        $data['october'] = $post['v'];
                        break;
                    case 11:
                        $data['november'] = $post['v'];
                        break;
                    case 12:
                        $data['december'] = $post['v'];
                        break;
                }

                $data['planid'] = $post['planid'];


            }else{

                $result = $items['planid'];

            }

        }


        if(isset($data)){

            M("tool_teaching")->where($where)->setField($data);

            $result = 200;
        }

        return $result;

    }

    /**
     * 培训项目预算
     */
    public function train(){

        $data = M("tool_train")->select();

        return $data;

    }

    /**
     * 添加培训项目预算
     */
    public function addTrain(){

        $data = array("tissue_id"=>0);
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('tool_train');
		}
        $result = M('tool_train')->add($data);

        return $result;
    }

    /**
     * 修改培训项目预算
     */
    public function uptrain(){

        $post = I("post.");

        switch($post['typeid']){
            case 1:
                $data['internal_lecturer_fee'] = $post['v'];
                break;
            case 2:
                $data['external_lecturer_fee'] = $post['v'];
                break;
            case 3:
                $data['curriculum_fee'] = $post['v'];
                break;
            case 4:
                $data['external_training_fee'] = $post['v'];
                break;
            case 5:
                $data['training_charge_fee'] = $post['v'];
                break;
            case 6:
                $data['training_equipment_fee'] = $post['v'];
                break;
            case 7:
                $data['site_fee'] = $post['v'];
                break;
            case 8:
                $data['accommodation_fee'] = $post['v'];
                break;
            case 9:
                $data['traffic_travel_fee'] = $post['v'];
                break;
            case 10:
                $data['other_fee'] = $post['v'];
                break;
            default:
                $data['tissue_id'] = $post['v'];
        }

        $where = array(
            "id"=>$post['id'],
        );

        M("tool_train")->where($where)->setField($data);

        return true;

    }



}