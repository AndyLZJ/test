<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class AttendanceModel extends BaseModel{


	/**获取各种状态的考勤信息
	*/
	public function  salary($course_id,$project_id){

		$map["pid"]=$project_id;

		$map["course_id"]=$course_id;

		$map["status"]=0;

		$data=array();

		//统计缺勤人数

		$data['absenteeism']=$this->where($map)->count();

		//统计迟到人数
        $arr["pid"]=$project_id;

		$arr["course_id"]=$course_id;

		$arr["status"]=2;

		$data['late']=$this->where($arr)->count();

		//统计按时人数
        $array["pid"]=$project_id;

		$array["course_id"]=$course_id;

		$array["status"]=1;

		$data['on_time']=$this->where($array)->count();

		return $data;
	}
   
    /**
	 *根据课程id获取课程名称
	 */

	public function getCoursename($course_id){
      $course_name=M('course')->where(['id'=>$course_id])->getField('course_name');
	  return $course_name;

	}


	/**获取学生考勤信息列表,导入流程调用
	*/

	public function lists($course_id,$project_id){

		$map["course_id"]=$course_id;
        $map["pid"]=$project_id;
		$list= $this->field('a.id,a.status,a.mobile_scanning,b.username,c.tissue_id,c.job_id')
		                ->alias('a')
		                ->join('left join __USERS__ b on a.user_id=b.id')
		                ->join('left join __TISSUE_GROUP_ACCESS__ c on a.user_id=c.user_id')
		                ->where($map)
						->select();
		// print_r($list);
		$tissue=M("TissueRule");

		$jobsManage=M("JobsManage");

		for($i=0;$i<count($list);$i++){

			$maps["id"]=$list[$i]["tissue_id"];

			$arr["id"]=$list[$i]["job_id"];

			// $list[$i]['company_name']=$tissue->field("name")->where($maps)->find();
            $list[$i]['company_name']=$tissue->where($maps)->getField("name");

			// $list[$i]["job_name"]=$jobsManage->field("name")->where($arr)->find();
			$list[$i]["job_name"]=$jobsManage->where($arr)->getField("name");
		}


        // print_r($list);
		return $list;
		
	}

        /**
	     **获取学生考勤信息列表,点击保存按钮则save数据
      	 */
    public function newlists($project_id,$course_id){
		if(IS_POST){
			// echo aa;
			$data = $_POST;
			unset($data['id']);
			unset($data['pid']);
			unset($data['course_name']);
			unset($data['course_id']);
			unset($data['start_time']);
			// echo $pid = I('id');
			foreach($data as $k=>$v){
            $where = array(
				'id'=>$k,
				'status'=>$v,
				'attendance_time'=> date('Y-m-d H:i:s')
		    	);

	         M("attendance")->save($where);
			}
			
            // print_r($data);die;
		}
		// echo I('pid');die;
        // $map["project_id"]=I('pid');
		// $pid = I('pid');
        // $list= M('admin_project')->field('c.username,f.name as company_name,e.name as job_name')
		//                 ->alias('a')
		// 				->join('left join __DESIGNATED_PERSONNEL__ b on a.id=b.project_id')
		//                 ->join('left join __USERS__ c on b.user_id=c.id')
		// 				->join('left join __TISSUE_GROUP_ACCESS__ d on b.user_id=d.user_id ')
		// 				->join('left join __JOBS_MANAGE__ e on e.id=d.job_id ')
		// 				->join('left join __TISSUE_RULE__ f on f.id=d.tissue_id ')
		// 				->join('left join __PROJECT_COURSE__ g on a.id=g.project_id')
		// 				// ->join('left join __ATTENDANCE__ h on h.course_id=g.id ')
		//                 ->where(["b.project_id=$pid"])
		// 				->select();
						// print_r($list);
		
        // $course_id=I("id");  
		// echo $course_id;
        //取得考勤关联表数据
		$datas= M('admin_project')->field('a.id as pid,c.course_id as course_id,b.user_id,3 as status')
		                ->alias('a')
						->join('left join __DESIGNATED_PERSONNEL__ b on b.project_id=a.id')
						->join('left join __PROJECT_COURSE__ c on c.project_id=a.id')
		                ->where(["a.id=$project_id and c.course_id=$course_id"])
						->select();
		
		// print_r($datas);exit;
	
		// 初始课程考勤表加入考勤数据，第二次则不会重复加入
		foreach($datas as $k=>$v){
		 $user_id = $v['user_id'];
		 if(!empty($user_id)){
            $res = M('attendance')->where(["pid=$project_id and course_id=$course_id and user_id=$user_id"])->select();
		   if(empty($res)){
		    M('attendance')->add($v); 
		   }
		 }
         
		}

		
		
		// if(empty($res)){
		// 	foreach($datas as $k=>$v){
		//    M('attendance')->add($v);
	    //    }
		// }
	   
// print_r($datas);

        
        $list= M('admin_project')->field('b.id,c.username,f.name as company_name,e.name as job_name,b.status')
		                ->alias('a')
						->join('left join __ATTENDANCE__ b on b.pid=a.id ')
		                ->join('left join __USERS__ c on c.id=b.user_id')
						->join('left join __TISSUE_GROUP_ACCESS__ d on d.user_id=c.id ')
						->join('left join __JOBS_MANAGE__ e on e.id=d.job_id ')
						->join('left join __TISSUE_RULE__ f on f.id=d.tissue_id ')
		                ->where(["b.pid=$project_id and course_id=$course_id"])
						->select();
						// print_r($list);

        return $list;
	}
     



	/**更新学生考勤信息
	*/

	public function update($map){
		
		$maps["id"]=$map["id"];

		$arr["status"]=$map["status"];

		$res=$this->where($maps)->save($arr);

		return $res;
	}

	/***
	导入考勤,进行与数据库attendance表的考勤人员匹配，并save学员考勤状态
	*/
    public function importAttendance($file){
		
		 $course_id=I('post.course_id');
		 $project_id=I('post.pid');
		 
         $data = import_excel_int($file);
		//  echo 22;die;
         unset($data[1]);
        //  print_r($data); 

         foreach($data as $k=>$v){
		  	
		 //手机号码不能为空，判断逻辑
            if($v[1]==''){
				 
				$this->error = "导入表格的第 $k 行手机用户号码不能为空";
				return false;
			}
			if($v[2]==''){
				$this->error = "导入表格的第 $k 行考勤信息不能为空";
				return false;
			}

	    		//用户是否在数据库考勤表中存在，判断逻辑:根据导入表格的手机号码做判断
	    	 // echo $v[1];
	    	 $where = array(
	    			 'b.phone'=>$v[1],
	    	 		 'a.pid'=> $project_id,
	    	 		 'a.course_id'=>$course_id
	    	 );
	    	 $res = M('attendance')->alias('a')
	    	 						->join('left join __USERS__ b on b.id=a.user_id')
	    	 						->where($where)
	    	 						->find();
	    	 // dump($res);die;
	    	 if(!$res){
	    	 	$this->error = "导入表格的第 $k 行用户不存在";
	    	 	return false;
	    	 }	  

			// echo $v[2];
			if($v[2]!=='缺勤'&&$v[2]!=='按时'&&$v[2]!=='迟到'){
				$this->error = "导入表格的第 $k 行考勤信息不正确";
				return false;
			}	
		 }   
           //返回数据
			return $data;
	}

	/***
	导入考勤 old
	*/
	public function importAttendances($file){

		 $list = import_excel($file);
         print_r($list);
		 $lists=array();

		 for($a=0;$a<count($list);$a++){

			if(!empty($list[$a][1])){

				$lists[]=$list[$a];
		 	}
		 }
		
		 $data=array();

		 $user=D("Users");

		 $tissueGroupAccess=D("TissueGroupAccess");

		 $array=array("按时","迟到","缺席");


		 
		 for($i=1;$i<count($lists);$i++){

			$data[$i]["username"]=$lists[$i][0];

		 	$data[$i]["mobile"]=$lists[$i][1];

		 	$data[$i]["attendance"]=$lists[$i][2];

			if($data[$i]["attendance"]=="按时"){

		 		$data[$i]["attendance"]=1;
		 	
		 	}elseif($data[$i]["attendance"]=="迟到"){

		 		$data[$i]["attendance"]=2;

		 	}else{

		 		$data[$i]["attendance"]=0;

		 	}

			if(empty($data[$i]["username"])||empty($data[$i]["mobile"])||empty($data[$i]["attendance"])){

				$data[$i]["status"]=false;

		 		$data[$i]["message"]="请完善第".$i."行信息";
			
			}else{

				 $map["phone"]=$data[$i]["mobile"];

				 $userInfo=$user->where($map)->find();

				 $data[$i]["user_id"]= $userInfo["id"];

				 $maps["user_id"]=$data[$i]["user_id"];

				 $data[$i]['department_id']=$tissueGroupAccess->field("tissue_id")->where($maps)->find();

				 $data[$i]["position_id"]=$tissueGroupAccess->field("job_id")->where($maps)->find();

				 if($userInfo==null){

				 	$data[$i]["status"]=false;

				 	$data[$i]["message"]="第".$i."行用户不存在";

				 }

				 if(in_array($data[$i]["attendance"],$array)){

					$data[$i]["status"]=false;

				 	$data[$i]["message"]="第".$i."行考勤信息不正确";
				 }


			}

		 }

		
		
		return $data;
	}
    
	/***
	更新考勤信息 
	*/
    public function saveAttendance($project_id,$course_id,$list){
		// print_r($list);
		 $attendance = M('attendance');
         foreach($list as $v){
			// 考勤状态0表示缺勤，1表示按时,2表示迟到,3表示未考勤
		   $flag = array('缺勤','按时','迟到');
           if($v[2]==$flag[0]){
			   $v[2] = 0;
		   }else if($v[2]==$flag[1]){
			   $v[2] = 1;
		   }else if($v[2]==$flag[2]){
			   $v[2] = 2;
		   }
		   //逐条更改考勤信息
           $where = array(
               'pid'=>$project_id,
			   'user_id'=>$v[1],
			   'course_id'=>$course_id,
			   'status'=>3
		   );
		//    echo $v[2]."<br/>"; 
		//    echo $project_id."<br/>";
		//    echo $course_id."<br/>";
		//    echo $v[1]."<br/>";
		    $user_id = M('users')->where(array('phone'=>$v[1]))->getField('id'); 
		//    $map = array(
        //        'pid'=>$project_id,
		// 	   'user_id'=>$user_id,
		// 	   'course_id'=>$course_id,
		// 	   'status'=>$v[2]
		//    );
		//    dump($map);

		    //判断是否已在页面的点击保存按钮进行保存，若是则不再该条学员考勤信息,预留使用
		//    $res = $attendance->alias('a')->where($where)->find();
		//    if($res){
		//      $attendance->alias('a')->where($map)->save();
		//    }
		    $data['status'] = $v[2];
			$data['attendance_time'] = date('Y-m-d H:i:s');
            $res = M('attendance')->where(['pid'=>$project_id,'user_id'=>$user_id,'course_id'=>$course_id])->save($data);
           

			// dump($res);
		                  
		 }
		  return $res;

	}

	/***
	更新考勤信息 old
	*/
	public function updateAttendance($course_id,$data){

		$user_id=$data["user_id"];
		
		$map["user_id"]=$data["user_id"];

		$map["status"]=$data["attendance"];

		$map["course_id"]=$course_id;

		$map["department_id"]=$data["department_id"]["tissue_id"];

		$map["position_id"]=$data["position_id"]["job_id"];

		$count=$this->where("user_id=$user_id")->count();

		

		if($count=0){

			$res=$this->add($map);
		
		}else{

			$res=$this->where("user_id=$user_id")->save($map);
		}

		return $res;

	}

}
