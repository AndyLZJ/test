<?php 

namespace Admin\Controller;

use Think\Controller;

class FileController extends Controller{


		/***
		 *H5 uploadify -- 后台处理
		*/
	  public function uploadify(){
		    $filename = $_FILES['file']['name'];
			$newfilename = date('ymdHis').rand(0000,9999).strrchr($filename, '.'); 
			$key = $_POST['key'];
			$key2 = $_POST['key2'];
            $key2 = $key2.'/'.date(Ymd);
			$path="./Upload/".$key2;
             if (!is_dir($path)){  
				//第3个参数“true”意思是能创建多级目录，iconv防止中文目录乱码
				
				$res=mkdir(iconv("UTF-8", "GBK", $path),0777,true); 
			 }

			if ($filename) {
				$src = "/Upload/".$key2."/".$newfilename;
				move_uploaded_file($_FILES["file"]["tmp_name"], "./Upload/".$key2."/".$newfilename);
			}
			// echo $key;
			// echo $key2;
			 $this->ajaxReturn($src, 'json');
			
	  }

		/***
		 *flash uploadify
		*/
	  public function upload(){
			if (IS_POST) {
	            $config = array(
	                'maxSize' => 3145728,
	                'savePath' => '',
	                 'rootPath'     => './Upload/',
	                'saveName' => array('uniqid', ''),
	                'exts' => array('doc','docx','jpg', 'gif', 'png', 'jpeg'),
	                'autoSub' => true,
	                'subName' => array('date', 'Ymd'),
	            );
	            $upload = new \Think\Upload($config);
				$info = $upload->upload();
				if (!$info) {
	                $this->error($upload->getError());
	            } else {

					$typeid = I("get.typeid");
					$src = ['src' => $info['file']['savepath'] . $info['file']['savename']];

					if($typeid == 1){
						$img_base = 'Upload/'.$src['src'];
						$image = new \Think\Image();
						$image->open($img_base);

						if($image->width() < 270){
							$image->thumb(371,258,\Think\Image::IMAGE_THUMB_FILLED)->save($img_base);
						}else{
							$image->thumb(371,258,\Think\Image::IMAGE_THUMB_FIXED)->save($img_base);
						}
					}else if($typeid == 2){
                        $img_base = 'Upload/'.$src['src'];
						$image = new \Think\Image();
						$image->open($img_base);

						if($image->width() < 270){
							$image->thumb(220,124,\Think\Image::IMAGE_THUMB_FILLED)->save($img_base);
						}else{
							$image->thumb(220,124,\Think\Image::IMAGE_THUMB_FIXED)->save($img_base);
						}


					}

	                $this->ajaxReturn($src, 'json');
	            }
	        } else {
	            echo '失败';

	        }
	    }

	    public function download(){
			$id=I("get.id");
			$http=new \Org\Net\Http;
			$uploadpath="./Upload/file/";
			$courseArticle=D("CourseArticle");
			$chapter=$courseArticle->getChapter($id);
			if($chapter==false){
				$data=array("code"=>0,"message"=>"下载的文件不存在");
                $this->ajaxReturn($data);
            }
			$saveName=$chapter["save_name"];//文件保存名
			$showName=$chapter["true_name"];//文件原名
			$filename=$uploadpath.$saveName;//完整文件名（路径加名字）
			$http::download($filename,$showName);
		}

		/***
		考勤模板下载
		*/
		public function attendanceDownload(){
			$http=new \Org\Net\Http;
			$uploadpath="./Upload/file/";
			$saveName="attendance.xlsx";//文件保存名
			$showName="attendance.xlsx";//文件原名
			$filename=$uploadpath.$saveName;//完整文件名（路径加名字）
			$http::download($filename,$showName);
		}
		/***
		线下考试成绩模板下载
		*/
		public function offlineDownload(){
			$http=new \Org\Net\Http;
			$uploadpath="./Upload/file/";
			$saveName="offline.xlsx";//文件保存名
			$showName="offline.xlsx";//文件原名
			$filename=$uploadpath.$saveName;//完整文件名（路径加名字）
			$http::download($filename,$showName);
		}

		/***
		培训总结附件下载
		*/
		public function attachmentDownload(){
			$http=new \Org\Net\Http;
			$map["id"]=I("get.id");
			$address=M("SummaryAttachment")->field("attachment_address")->where($map)->find();
			$uploadpath="." . $address["attachment_address"];
			$showName="培训项目总结（" . $map["id"] . ")";
			$http::download($uploadpath,$showName);    
		}
}