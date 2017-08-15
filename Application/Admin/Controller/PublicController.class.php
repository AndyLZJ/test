<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
use \Think\Controller;


/**
 * 后台首页控制器
 */
class PublicController extends AdminBaseController{

	public function draft(){
		$post=I("post.");
		if (!empty($_FILES)) {
			$config = array(   
                'maxSize'    =>    3145728, 
                'savePath'   =>    '/',
                'rootPath'   =>    './Upload/photo',
                'saveName'   =>    array('uniqid',''), 
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg'),  
                'autoSub'    =>    true,   
                'subName'    =>    array('date','Ymd')
            );
			$upload = new \Think\Upload($config);// 实例化上传类
			$images = $upload->upload();
			if(!$images){
				$this->error($upload->getError());
			}else{
				$project_covers="/Upload/photo". $images['project_covers']['savepath'] . $images['project_covers']['savename'];
				$photo="./Upload/photo". $images['project_covers']['savepath'] . $images['project_covers']['savename'];
				$photosPath="./Upload/photo/".$images['project_covers']['savename'];
				$image = new \Think\Image(); 
				$image->open("$photo");
				$image->thumb(271,188)->save("$photosPath");
				$post["project_covers"]=substr_replace($photosPath,"",0,1);
				$post["type"]=1;
				$result=D("AdminProject")->createProject($post);
				if(!$result){
					$this->error("项目保存失败","Admin/Manage/create","2");
				}else{
					$this->success("创建成功");
				}
			}
		}
	}











}