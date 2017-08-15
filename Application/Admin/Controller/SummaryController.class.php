<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 培训总结控制器
 */
class SummaryController extends AdminBaseController{

	/**
	 * 培训总结查看
	 */
	public function index(){
		
		$id=I('get.id')?I('get.id'):$_SESSION["project_id"];
		$project=D('AdminProject')->obtain($id);//获取培训项目信息
		$summary=D('ProjectSummary')->getOne($id);
		$attachments=D('SummaryAttachment')->getAttachment($id);
		$count=count(D('DesignatedPersonnel')->getOne($id));//获取指定参与人员
		$number=D('DesignatedPersonnel')->count($id);
		$projectCourses=D('Course')->getCourseOneSelect($id);
		$projectExamination=D('Examination')->getExaminationOneSelect($id);
		$projectSurvey=D('Survey')->getSurveyOneSelect($id);
		for($e=0;$e<count($projectExamination);$e++){
			$projectExamination[$e]['absenteeism']=D('ExaminationAttendance')->salary($projectExamination[$e]['id']);
		}
		for($p=0;$p<count($projectSurvey);$p++){
			$projectSurvey[$p]['absenteeism']=D('SurveyAttendance')->salary($projectSurvey[$p]['id']);
		}
		$attendance=D('Attendance');
		for($i=0;$i<count($projectCourses);$i++){
			$projectCourses[$i]['statistics']=$attendance->salary($projectCourses[$i]['id']);
		}
		$this->assign('count',$count);
		$this->assign('number',$number);
		$this->assign('projectExamination',$projectExamination);
		$this->assign('projectCourses',$projectCourses);
		$this->assign('projectSurvey',$projectSurvey);
		$this->assign('project',$project);
		$this->assign('summary',$summary);
		$this->assign("attachments",$attachments);
		$this->display("index");
	
	}

	/***
	保存培训项目总结
	*/
	public function add(){

		$data=I('post.');
		$res=D('ProjectSummary')->addSummary($data);
		if (!empty($_FILES)) {
            //图片上传设置
            $config = array(   
                'maxSize'    =>    3145728, 
                'savePath'   =>    '/',
                'rootPath'   =>    './Upload/file',
                'saveName'   =>    array('uniqid',''), 
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg','xlsx','doc','pdf','rtf','zip','xls'),
                'autoSub'    =>    true,   
                'subName'    =>    array('date','Ymd')
            );
			$upload = new \Think\Upload($config);// 实例化上传类
			$images = $upload->upload();
			if(!$images){
				$this->error($upload->getError());
			}else{
				$project_covers='/Upload/file'. $images['attachment_address']['savepath'] . $images['attachment_address']['savename'];
				$photo='./Upload/file'. $images['attachment_address']['savepath'] . $images['attachment_address']['savename'];
				//保存文件路径
				$photosPath='./Upload/file/'.date('Ymd',time()).'/'.$images['attachment_address']['savename'];
				$map['project_id']=$data['project_id'];
				$map['attachment_address']=substr_replace($photosPath,'',0,1);
				$result=D('SummaryAttachment')->addAttachment($map);
				$_SESSION["project_id"]=$map['project_id'];
				$this->success('提交成功','index',3);
			}
			
		}
	}

}
