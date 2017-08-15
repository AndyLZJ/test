<?php 

namespace Admin\Model;

use Think\Model;

class CourseDetailModel extends Model{
	//课和详情表数据操作
	function update($id)
	{
		$data = $this->create();
		if ($data['id']) {
			//修改课程详情表
			$res = $this->where(array('id' => $data['id']))->save($data);

		} else {
			//课程详情表新增内容
			$data['id'] = $id;	
			$id = $this->add($data);
		}
		
		if (empty($id)) {
			$this->error = '新增数据失败';
			return false;
		}
		return true;
	}

	//获取课程详情

	public function detail($id){

		$map['id']=$id;

		$course=M("Course")->where($map)->find();

		$courseDetail=$this->where($map)->find();

		$arr['id']=$course['lecturer'];

		$lecturer=M("Lecturer")->where($arr)->find();

		$course["lecturer_id"]=$course["lecturer"]; //讲师id
		$course["lecturer"]=$lecturer['name'];  //课程的讲师名称
       
	    


		$course['course_intro']=$courseDetail["course_intro"];

		$course["course_aim"]=$courseDetail["course_aim"];

		$course["course_outline"]=$courseDetail["course_aim"];

		$course['course_summary']=$courseDetail["course_summary"];
        
		return $course;
	}

	//获取授课课程详情,授课都为面授课程
	public function teachDetail($cid,$pid){
        // echo $cid;echo $pid;exit;
		$map['id']=$cid;
		$course=M("Course")->where($map)->find();
		$courseDetail=$this->where($map)->find();
		$arr['id']=$course['lecturer'];
		$lecturer=M("Lecturer")->where($arr)->find();
		//查出授课地址
		$where['project_id']=$pid;
		$where['course_id']=$cid;
        $temp1 = M('project_course')->where($where)->find();
		$location = $temp1['location'];
        // print_r($temp);
		//课程的限制人数，项目表字段population：限制人数，is_public：0表示不公开，1表示公开
		$temp2 = M('admin_project')->where(array("id"=>$pid))->find();
        $population = $temp2['population'];
		
		$course["location"]=$location; //授课地址
		$course["population"]=$population; //授课限定人数：即关联项目的指定人数
		$course["lecturer_id"]=$course["lecturer"]; //讲师id
		$course["lecturer"]=$lecturer['name'];  //课程的讲师名称
		$course['course_intro']=$courseDetail["course_intro"];
		$course["course_aim"]=$courseDetail["course_aim"];
		$course["course_outline"]=$courseDetail["course_aim"];
		$course['course_summary']=$courseDetail["course_summary"];   
		return $course;
	}

}