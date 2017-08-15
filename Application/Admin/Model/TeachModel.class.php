<?php 

namespace Admin\Model;

use Common\Model\BaseModel;
/**
 * 培训管理-我的授课模型
 */
class TeachModel extends BaseModel{	
     protected $trueTableName= 'think_admin_project';
     /**
    *我的授课列表
    */
     public function teachList($a)
	 {   
         $arg = $a;
         
         $condition = I('course_name') ? I('course_name') : '';
         $size=15;
         $p = I('p') ? I('p') : 1 ;
      	//根据登陆者查找相关的授课，项目关联的课程的讲师具有授课资格
         $user_id=$_SESSION["user"]["id"];
         if($arg == 1){ //$arg == 1 为我的授课的考勤
            $where = array(
                'e.id'=>$user_id,
                'a.type'=> array('in','0,4'),
                'c.course_name' =>array('like',"%".$condition."%")
             );  
         }else{   //$arg == 2 为考勤管理
            $where = array(
                //将所有的课程考勤的列表数据取出来
                'e.id'=>$user_id, //只有负责人可查看考勤
                'a.type'=>array('in','0,4'),
                'c.course_way'=>1,
                'c.course_name' =>array('like',"%".$condition."%")
             ); 
         }
           
        // echo time();
      
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$list = $this->alias('a')
              	->field("a.id as pid,b.course_id,c.course_name,c.course_way,c.course_time,to_char(b.start_time,'YYYY-MM-DD HH24:MI:SS') as start_time,to_char(b.end_time,'YYYY-MM-DD HH24:MI:SS') as end_time,
				      b.credit,b.location,e.username,a.project_name,b.is_attachment")
               	->join('left join __PROJECT_COURSE__ b on a.id=b.project_id')
		       	->join('left join __COURSE__ c on b.course_id=c.id')
		       	->join('left join __USERS__ e on b.manager_id=e.id')
		       	->where($where)
               	->order('b.start_time desc')
               	->page($p, $size)
		       	->select();
		}else{
			$list = $this->alias('a')
              	->field('a.id as pid,b.course_id,c.course_name,c.course_way,c.course_time,b.start_time,b.end_time,
				      b.credit,b.location,e.username,a.project_name,b.is_attachment')
               	->join('left join __PROJECT_COURSE__ b on a.id=b.project_id')
		       	->join('left join __COURSE__ c on b.course_id=c.id')
		       	->join('left join __USERS__ e on b.manager_id=e.id')
		       	->where($where)
               	->order('b.start_time desc')
               	->page($p, $size)
		       	->select();
		}

        // whetherteach:1代表可查看考勤，0代表不可查看考勤
        foreach($list as $k=>$v){
        	$list[$k]['tokinaga'] = round(floor(strtotime($v['end_time'])-strtotime($v['start_time']))%86400/60);
			$list[$k]['whetherteaching'] = time()-strtotime($v['start_time']);
            if(($v['whetherteaching']+1800) > 0 && $v['is_attachment'] == 1){
                $list[$k]['whetherteach'] = 1;
            }else{
                $list[$k]['whetherteach'] = 0;
            }
             
        }
    //   $count = M('admin_project')->alias('a')
	//                    ->join('left join __PROJECT_COURSE__ b on a.id=b.project_id')
	// 			       ->join('left join __COURSE__ c on b.course_id=c.id')
	// 			       ->join('left join __LECTURER__ d on c.lecturer=d.id')
	// 			       ->join('left join __USERS__ e on d.user_id=e.id')
	// 			       ->where($where)
	// 			       ->count();
        $count = $this->alias('a')
	                  ->field('a.id as pid,b.course_id,c.course_name,c.course_way,b.start_time,b.end_time,
						      b.credit,b.location,e.username,a.project_name,b.is_attachment')
	                   ->join('left join __PROJECT_COURSE__ b on a.id=b.project_id')
				       ->join('left join __COURSE__ c on b.course_id=c.id')
				       ->join('left join __USERS__ e on b.manager_id=e.id')
				       ->where($where)
                       ->order('b.start_time desc')
				       ->count();



     $show = $this->pageClass($count,$size);
     $data = array(
        '0'=>$list,
        '1'=>$show,
        'arg'=>$arg
         );
     return $data;

     }
 


}