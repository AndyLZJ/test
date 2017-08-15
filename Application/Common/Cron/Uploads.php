<?php

/**
 * 考试状态处理
 * @return [type] [description]
 */
$data = M('test')->where("status != 2")->field('id,start_time,end_time')->select();

$now = date('Y-m-d H:i:s');
foreach($data as $k=>$v){
    if(strtotime($v['start_time']) > time()){
        $data[$k]['status'] = 0;
    }else if(strtotime($v['end_time']) < time()){
        $data[$k]['status'] = 2;
    }else{
        $data[$k]['status'] = 1;
    }

    $info['id'] = $v['id'];
    $info['status'] = $data[$k]['status'];
    $res = M('test')->save($info);
}
