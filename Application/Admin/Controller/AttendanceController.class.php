<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;

class AttendanceController extends AdminBaseController
{
   /***
   显示课程的考勤列表
   */
   public function index($project_id='',$course_id='',$user_id='',$start_time,$end_time,$course_name){
     

       if($project_id == ''){
          $course_id=I("id");
          $course_name=I("course_name");

          $start_time=I("start_time");
          // $start_time = date('Y-m-d H:i:s', $start_time);
          $end_time=I("end_time");
          // echo $end_time;die;
          // echo  $start_time;die;
          $project_id = I('pid');
          //  echo  $z = $_POST;
          $type = I('type');
       }
        
        
        // $list=D("Attendance")->lists($course_id);//获取学员考勤列表
        $list=D("Attendance")->newlists($project_id,$course_id);//根据关联项目id获取学员考勤列表（即项目指定人数）,点击保存按钮则save数据
        $statistics=D("Attendance")->salary($course_id,$project_id);//获取各种状态的考勤人数
        $this->assign("course_id",$course_id);
        $this->assign("project_id",$project_id);
        $this->assign("statistics",$statistics);
        $this->assign("list",$list);
        $this->assign("start_time",$start_time);
        $this->assign("end_time",$end_time);
        $this->assign("course_name",$course_name);
        $this->assign("id",$course_id);
        $this->assign("type",$type);
        if(IS_POST){
            $this->success('保存成功！',U('Admin/attendance/index',array('id'=>$course_id,'pid'=>$project_id,'course_name'=>$course_name,'start_time'=>$start_time,'end_time'=>$end_time)));
        }else {
          $this->display('Attendance/index');
        }
        
       
        
        
   }





   /***
   二维码生成
   */
   public function  qrcode(){
        $course_id=I("get.id");
        $project_id=I("get.project_id");
        $url=json_encode(array("course_id"=>$course_id,"project_id"=>$project_id,"type"=>2));
        qrcode($url,40);
   }







  /***
   导入学员考勤信息
   */
  public function import(){
          if(IS_POST){
            // echo aa;
            $options = I('post.attendance');
            $course_id=I('post.course_id');
		        $project_id=I('post.pid');
            $start_time =I('start_time');
            $end_time =I('end_time');
            $course_name=D('Attendance')->getCoursename($course_id);
            // $start_time = date('Y-m-d H:i:s', $start_time);
            // echo  $start_time;
            // echo  $end_time;die;
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 300*1024*1024 ;// 设置附件上传大小
            $upload->exts = array('xls','xlsx');// 设置附件上传类型
            $upload->rootPath = './Upload/file'; // 设置附件上传根目录
            $upload->savePath = 'excel/'; // 设置附件上传（子）目录
            $upload->autoSub = true;
            $upload->subName = '';
            $info = $upload->upload();// 上传文件
            if(!$info) {// 上传错误提示错误信息
                $this->error($upload->getError());
            }else{// 上传成功
                foreach($info as $v){
                    $file_path1 = './Upload/file'.$v['savepath'];
                    $file_path2 = $v['savename'];
                }
                $file = $file_path1.$file_path2;
                // echo $file;
                if(file_exists($file)){
                      // if(file_exists($file)){
                      //    $data = import_excel($file);
                      //    unset($data[1]);
                      //    print_r($data); 
                      //  // unlink($file);
                      // }                 
                    // echo 11;die;
                    $list = D('Attendance')->importAttendance($file);
                    $Model = D('Attendance');
                    // dump($list);die;
                    //返回回错误信息，并抛出错误信息
                    if(!$list){
                       $this->error($Model->getError()); 
                    }
                    //更改考勤数据
                     $res=D('Attendance')->saveAttendance($project_id,$course_id,$list);
                     if($res !== false){ //save 若更改相同数据则返回0
     
                       $this->success('导入考勤成功！',U('Admin/attendance/index',array('id'=>$course_id,'pid'=>$project_id,'course_name'=>$course_name,'start_time'=>$start_time,'end_time'=>$end_time)));
                     }
                    //  dump($res);die;   
                    // foreach($list as $k=>$v){
                    //   if(isset($v['status'])){
                    //     $this->error($v["message"]);
                    //   }
                    //   $res=D('Attendance')->updateAttendance($course_id,$v);
                    // }
                    // $statistics=D("Attendance")->salary($course_id,$project_id);//获取各种状态的考勤人数
                    // $list=D("Attendance")->lists($course_id,$project_id);//获取学员考勤列表
                    // $this->assign("course_id",$course_id);
                    // $this->assign("project_id",$project_id);
                    // $this->assign("statistics",$statistics);
                    // $this->assign("list",$list);
                    // $this->assign("start_time",$start_time);
                    // $this->assign("end_time",$end_time);
                    // $this->assign("course_name",$course_name);
                    // $this->assign("id",$course_id);
                    // $this->display("index");
                  }
            }
        }else{
            $this->error('非法数据提交');
        };
    }
    

  /***
   *考勤管理模块,list
   */
  public function attendanceManage(){
         $data = D('Teach')->teachList(2);
        
         $condition = I('course_name') ? I('course_name') : '';
	       $list = $data[0];
	  	   $show = $data[1];
         $arg = $data['arg'];
      	 $this->assign("lessons",$list);
	       $this->assign("condition",$condition);
	       $this->assign("show",$show);
         $this->assign("arg",$arg);
         $this->assign("type",2);  //type=2代表 考勤管理模块
         
         $this->display('Teach/index');


  }







     /***
     保存更新考勤信息
     */
    public function save(){
              $data=I("post.");
              $course_id=$data["course_id"];
              $course_name=$data["course_name"];
              $start_time=$data["start_time"];
              foreach($data as $k=>$v){
                $decide=substr($v,-1,1);
                if($decide=="a"){
                  $map["status"]=0;
                }elseif($decide=="l"){
                  $map["status"]=2;
                }else{
                  $map["status"]=1;
                }
                $map["id"]=$k;
                $res=D("Attendance")->update($map);
              }
              $statistics=D("Attendance")->salary($course_id,$project_id);//获取各种状态的考勤人数
              $list=D("Attendance")->lists($course_id);//获取学员考勤列表
              $this->assign("course_id",$course_id);
              $this->assign("statistics",$statistics);
              $this->assign("list",$list);
              $this->assign("start_time",$start_time);
              $this->assign("course_name",$course_name);
              $this->assign("id",$course_id);
              $this->display("index");
     }

}