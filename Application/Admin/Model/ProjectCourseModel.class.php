<?php 
namespace Admin\Model;

use Think\Model;

class ProjectCourseModel extends Model{

	/***
	写入项目课程关联表
	*/
	public function adds($data){
		
		$map["project_id"]=$data["project_id"];


		$res=$this->field("course_id,specific_information")->where($map)->find();
		$res_course = explode(",",$res["course_id"]);
		
		$data_cours = explode(",",$data["course_id"]);

		foreach($data_cours as $id){
			if(!in_array($id,$res_course)){

				$items[$id] = $data["specific_information"][$id];

				$add_id[] = $id;
			}
		}

		$course_info = json_decode($res['specific_information'],true);

		$course_id = array_merge($res_course,$add_id);

		$str_courseid = implode(",",$course_id);

		if(!empty($course_info)){
			$specific_information = array_merge($course_info,$items);
		}else{
			$specific_information  = $items;
		}

		$data["specific_information"]= json_encode($specific_information);
		
		$data["course_id"]=trim($str_courseid,",");


		
		if(!empty($res)){

			$result=$this->where($map)->save($data);

		}else{
			$result=$this->add($data);
		}
		return $result;
	}

	/***
	删除项目下面的课程
	*/
	public function deleteCourse($id){
		$map["project_id"]=$_SESSION["project"]["id"];
		$res=$this->field("course_id,specific_information")->where($map)->find();
		$data["course_id"]=implode(",",array_diff(explode(",",$res["course_id"]),$id));
		$data["specific_information"]=json_decode($res["specific_information"],true);
		unset($data['specific_information'][$id[0]]);
		$data["specific_information"]=json_encode($data['specific_information']);
		$result=$this->where($map)->save($data);
		return $result;
	}

	/***
	获取所有已经选定的课程
	*/

	public function getAll(){
		$projectCourses=$this->select();
		return $projectCourses;
	}

	/*
	获取新建项目的课程信息
	***/

	public function getOne($id){
		$map["project_id"]=$id;
		$courses=$this->where($map)->find();
		return $courses;
	}

	/***
    删除培训项目
    */
    public function deleteData($id){
		$map["project_id"]=$id;       
		$res=$this->where($map)->delete();
		return $res; 
    }

    /***
	编辑更新信息
    */
   public function saveData($data){
   		$map["project_id"]=$data["project_id"];
		$res=$this->where($map)->save($data);
		return $res;
   }




	



}