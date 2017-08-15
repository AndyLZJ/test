<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 培训管理控制器
 */
class TeachController extends AdminBaseController{
	
	/***
	 +显示我的授课列表
	 */
	public function index(){
	
        $data = D('Teach')->teachList(1);
        
        $condition = I('course_name') ? I('course_name') : '';
		$list = $data[0];
		$show = $data[1];
		$arg = $data['arg'];
      	$this->assign("lessons",$list);
		$this->assign("condition",$condition);
	    $this->assign("show",$show);
        $this->assign("arg",$arg);
		

		$this->display();

	}

    
 public function  test1(){
     $data =  R('Admin/Attendance/qrcodeAttendance',array(157,85,312));
	 dump($data);
   }

    /***
	显示我的授课列表1
	*/
	public function index1(){


		//$condition=I("get.course_name")?I("get.course_name"):"";

		$user_id=$_SESSION["user"]["user_id"]=88;
        
		$lecture=D("Lecturer")->getInfo($user_id);
        


		$projectCourses=D("ProjectCourse")->getAll();

		$myCourseId=array();

		for($i=0;$i<count($projectCourses);$i++){

			$courseId=explode(",",$projectCourses[$i]["course_id"]);

			$specificInformation=json_decode($projectCourses[$i]["specific_information"],true);

			for($e=0;$e<count($courseId);$e++){

				$res=D("Course")->isMy($lecture,$courseId[$e]);

				if($res){

					$myCourseId[$projectCourses[$i]["project_id"]][$e]["course_id"]=$courseId[$e];

					$myCourseId[$projectCourses[$i]["project_id"]][$e]["specific_information"]=$specificInformation[$courseId[$e]];
				}

			}


		}

		$data=array();

		$Course=D("Course");

		static $b=0;



		foreach($myCourseId as $k=>$v){

			$result=D("AdminProject")->isCheck($k);




			if(!$result){

				unset($myCourseId[$k]);

				continue;
			}

			for($a=0;$a<count($v);$a++){

				$data[$b]["project_name"]=$result["project_name"];

				$data[$b]["course_id"]=$v[$a]["course_id"];

				$course=$Course->getOne($v[$a]["course_id"]);

				$data[$b]["course_way"]=$course["course_way"];

				$data[$b]["course_name"]=$course["course_name"];

				$data[$b]["start_time"]=$v[$a]["specific_information"]["start_time"];

				$data[$b]['tokinaga']=floor((strtotime($v[$a]["specific_information"]["start_time"])-strtotime($v[$a]["specific_information"]["end_time"]))%86400/60);
				
				$criteria=strtotime($v[$a]["specific_information"]["start_time"])-time();

				if($criteria<0){

					$data[$b]['whether_teach']="已授课";
				}

				$data[$b]['whether_teach']="未授课";
				
				$b++;
			}

			
		}



		// if($condition!=""||isset($_SESSION["my_pleasure"])){

		// 	$_SESSION["my_pleasure"]=$condition;
			
		// 	for($f=0;$f<count($data);$f++){

		// 		if(!strstr($data[$f]["course_name"],$_SESSION["my_pleasure"])){



		// 			unset($data[$f]);
		// 		}
		// 	}

			
		// }

		$count=count($data);

		$Page=new \Think\Page($count,2);

		$Page->setConfig('header','共 %TOTAL_ROW% 条记录');

       	$Page->setConfig('prev','上一页');

        $Page->setConfig('next','下一页');

        $Page->type=$type;

		$show=$Page->show();

		$data=array_slice($data,$Page->firstRow,$Page->listRows);



		$this->assign("show",$show);

		$this->assign("lessons",$data);

		$this->display();
	}

	/***
	按条件搜索
	*/
	public function search(){


		$condition=I("get.course_name")?I("get.course_name"):"";

		$user_id=$_SESSION["user"]["user_id"];

		$lecture=D("Lecturer")->getInfo($user_id);

		$projectCourses=D("ProjectCourse")->getAll();

		$myCourseId=array();

		for($i=0;$i<count($projectCourses);$i++){

			$courseId=explode(",",$projectCourses[$i]["course_id"]);

			$specificInformation=json_decode($projectCourses[$i]["specific_information"],true);

			for($e=0;$e<count($courseId);$e++){

				$res=D("Course")->isMy($lecture,$courseId[$e]);

				if($res){

					$myCourseId[$projectCourses[$i]["project_id"]][$e]["course_id"]=$courseId[$e];

					$myCourseId[$projectCourses[$i]["project_id"]][$e]["specific_information"]=$specificInformation[$courseId[$e]];
				}

			}
		}

		$data=array();

		$Course=D("Course");

		$data=array();

		static $b=0;

		foreach($myCourseId as $k=>$v){

			$result=D("AdminProject")->isCheck($k);

			if(!$result){

				unset($myCourseId[$k]);

				continue;
			}

			for($a=0;$a<count($v);$a++){

				$data[$b]["project_name"]=$result["project_name"];

				$data[$b]["course_id"]=$v[$a]["course_id"];

				$course=$Course->getOne($v[$a]["course_id"]);

				$data[$b]["course_way"]=$course["course_way"];

				$data[$b]["course_name"]=$course["course_name"];

				$data[$b]["start_time"]=$v[$a]["specific_information"]["start_time"];

				$data[$b]['tokinaga']=floor((strtotime($v[$a]["specific_information"]["start_time"])-strtotime($v[$a]["specific_information"]["end_time"]))%86400/60);
				
				$criteria=strtotime($v[$a]["specific_information"]["start_time"])-time();

				if($criteria<0){

					$data[$b]['whether_teach']="已授课";
				}

				$data[$b]['whether_teach']="未授课";
				
				$b++;
			}

			
		}

		$datas=array();

		if($condition!=""||isset($_SESSION["my_pleasure"])){

			$_SESSION["my_pleasure"]=$condition;


			
			for($f=0;$f<count($data);$f++){

				$bool=strpos($data[$f]["course_name"],$_SESSION["my_pleasure"]);

				if(md5($bool)=="cfcd208495d565ef66e7dff9f98764da"){
					
					$bool=6;
				}

				if($bool==false){

					unset($data[$f]);

				}else{

					$datas[]=$data[$f];

				}
			}
		}

		$count=count($datas);

		$Page=new \Think\Page($count,2);

		$Page->setConfig('header','共 %TOTAL_ROW% 条记录');

       	$Page->setConfig('prev','上一页');

        $Page->setConfig('next','下一页');

        $show=$Page->show();

		$datas=array_slice($datas,$Page->firstRow,$Page->listRows);

		$this->assign("show",$show);

		$this->assign("lessons",$datas);

		$this->display("index");
	}







	






}
