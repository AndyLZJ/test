<?php

namespace Common\Model;

use Common\Model\BaseModel;

class RsCourseModel extends BaseModel
{

	//初始化
	public function __construct(){}

	/**
	 * 首页
	 */
	public function index($total_page = 10){

		//获取当前所属组织所有会员
		$start_page = I("get.p",0,'int');

		$keywords = I("get.keywords");

		$typeid = I("get.typeid");

		if($typeid == ''){
			$typeid = 1;
		}

		$parameter = array("p"=>$start_page,"typeid"=>$typeid);

		$conditions['a.status'] = array("eq",$typeid);

		if(!empty($keywords)){
			$conditions['a.course_name'] = array("like","%$keywords%");
		}

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

		$conditions['a.auth_user_id'] = array("in",$specifiedUser);

		$list = M("course a")
			->field("a.id,a.course_name,a.course_time,a.is_public,a.course_way,a.create_time,a.auditing,a.auth_user_id,c.cat_name,d.username")
			->join("LEFT JOIN __COURSE_DETAIL__ b ON a.id = b.id LEFT JOIN __COURSE_CATEGORY__ c ON a.course_cat_id = c.id LEFT JOIN __USERS__ d ON a.user_id = d.id")
			->order('a.id desc')
			->where($conditions)
			->page($start_page,$total_page)
			->select();

		$count = M("course a")
			->field("a.course_name,a.course_time,a.is_public,a.course_way,a.create_time,a.auth_user_id,c.cat_name")
			->join("LEFT JOIN __COURSE_DETAIL__ b ON a.id = b.id LEFT JOIN __COURSE_CATEGORY__ c ON a.course_cat_id = c.id")
			->where($conditions)
			->order('a.id desc')
			->count();
	
		
		//隔离数据过滤
		$list = D('IsolationData')->isolationData($list);

		$show = $this->pageClass($count,$total_page,$parameter);

		$data = array(
			"typeid"=>$typeid,
			"list"=>$list,
			"pages"=>$show
		);

		return $data;


	}

	/**
	 * 资源管理模块 - 课程分类
	 */
	public function CourseClass(){

		//获取所有分类
		$itesm = $this->tree();

		return $itesm;
	}

	/**
	 *增加分类
	 */
	public function addCategory()
	{
		$cat_name = I("post.cat_name");

		$pid = I("post.pid");

		if(!empty($cat_name)){
			$data = array("pid"=>$pid,"cat_name"=>$cat_name);
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('course_category');
			}
			$results = M('course_category')->add($data);

		}else{

			$results = false;

		}

		return $results;
	}

	/**
	 * 获取树形
	 */
	public function tree(){

		$rule_list = M("course_category")->order("sort asc")->select();

		// 获取一级下所有下级分类
		$item = \Org\Nx\Data::channelLevel($rule_list,0,'&nbsp;','id');

		return $item;
	}

	/**
	 * 编辑分类
	 */
	public function editorCategory(){

		$cat_name = I("post.cat_name");

		$pid = I("post.pid");

		$type = I("post.type");

		if($type == 'get'){

			$category = M("course_category")->field("cat_name")->where("id=".$pid)->find();

			$data = array(
				"category"=>$category['cat_name']
			);


		}else{

			if(!empty($cat_name)){

				$results = M('course_category')->where("id=".$pid)->setField("cat_name",$cat_name);
			}else{

				$results = false;
			}

			$data = array(
				"status"=> $results,
			);

		}

		return $data;
	}

	/**
	 * 删除分类
	 */
	public function delCategory(){

		$pid = I("post.pid");

		$is_category = M("course_category")->where("pid=".$pid)->find();


		if(empty($is_category)){

			$results = M("course_category")->where("id=".$pid)->delete();

		}else{

			$results = false;
		}

		return $results;

	}

	/**
	 * 添加课程
	 */
	public function addCourse(){

		//获取课程分类
		$category = $this->CourseClass();

		//获取内外部讲师
		$getLecturer = $this->getLecturer();

		//获取当前课程编辑内容
		$UpdateCourse = $this->UpdateCourse();

		if(!empty($UpdateCourse['tag_id'])){

			$where['id'] = array("in",$UpdateCourse['tag_id']);

			$tag_list = M("users_tag")->where($where)->select();

			$UpdateCourse['tag_list'] = $tag_list;

		}else{

			$UpdateCourse['tag_list'] = array();

		}

		//获取所属岗位
		$jobs_manage = M("jobs_manage")->order("id desc")->select();

		$data = array(
			"category_all"=>$category,
			"external"=>$getLecturer['external'],
			"internal"=>$getLecturer['internal'],
			"detail"=>$UpdateCourse,
			"jobs_manage"=>$jobs_manage
		);

		return $data;

	}

	/**
	 * 选择用户标签
	 */
	public function TagList(){

		//获取所属标签
		$tag_list = M("users_tag")->order("id desc")->select();

		return $tag_list;

	}


	/**
	 * 获取课程内容
	 */
	public function UpdateCourse(){

		$id = I("get.id");

		$conditions['a.id'] = array("eq",$id);

		$list = M("course a")
			->field("a.id,a.course_name,a.course_code,a.course_time,a.course_cat_id,a.is_public,a.course_way,a.create_time,a.lecturer,a.media_src,a.maker,a.chapter,a.course_cover,a.credit,a.auditing,a.lecturer_name,a.arrangement_id,a.tag_id,a.jobs_id,b.course_intro,b.course_aim,b.course_summary,b.course_outline,c.cat_name")
			->join("LEFT JOIN __COURSE_DETAIL__ b ON a.id = b.id LEFT JOIN __COURSE_CATEGORY__ c ON a.course_cat_id = c.id")
			->order('a.id desc')
			->where($conditions)
			->find();
		return $list;

	}


	/**
	 * 编辑课程
	 */
	public function editorCourse(){

		$data = I("post.");
        // $t = json_decode($data['chapter'],true);
		// dump($t);
		// die;
		if(!empty($data['user_id'])){
			$tag_id = implode(",",$data['user_id']);
		}else{
			$tag_id = '';
		}

		if(empty($data['course_name']) || empty($data['course_cat_id'])){

			$results = false;

		}else{

		 	$auditing = empty($data['auditing']) ? 0 : 1;

			//线上课程和线下课程的默认封面图
			if($data['course_way'] == 0){
				$data['course_cover'] = empty($data['course_cover']) ? '' : $data['course_cover'];
			}else if($data['course_way'] == 1){
				$data['course_cover'] = empty($data['course_cover']) ? '' : $data['course_cover'];
			}

			$orderno =  D('Trigger')->orderNumber(2);
            
			$course_data = array(
				"course_name"=>$data['course_name'],
				"course_code"=>$data['course_code'],
				"course_cat_id"=>$data['course_cat_id'],
				"arrangement_id"=>$data['arrangement_id'],
				"course_way"=>$data['course_way'],
				"course_time"=>$data['course_time'],
				"media_src"=>$data['media_src'],
				"maker"=>$data['maker'],
				"chapter"=>$data['chapter'],
				"course_cover"=>$data['course_cover'],
				"credit"=>$data['credit'],
				"auditing"=>$auditing,
				"create_time"=>time(),
				"user_id"=>$_SESSION['user']['id'],
				"orderno"=>$orderno,
				"tag_id"=>$tag_id,
				"jobs_id"=>$data['jobs_id'],
				"auth_user_id"=>$_SESSION['user']['id']
			);

			if($data['course_way'] == 0){
				$course_data['lecturer_name'] = $data['lecturer_name'];
				$course_data['lecturer'] = $data['online_lecturer'] ? $data['online_lecturer'] : 0;
			}else{
				$course_data['lecturer'] = $data['face_lecturer'] ? $data['face_lecturer'] : 0;
				$course_data['lecturer_name'] = '';
			}

			$course_detail_data = array(
				"course_intro"=>$data['course_intro'],
				"course_aim"=>$data['course_aim'],
				"course_outline"=>$data['course_outline'],
			);
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$course_data['id'] = getNextId('course');
			}
			
			if(empty($data['id'])){

				//新增
				try {

					$DB = M();

					$DB->startTrans();

					$increment_id = $DB->table('__COURSE__')->data($course_data)->add();

                    //新增成功后调用方法插入课程章节表（think_course_chapter_file）的关联数据
                     $res = $this->fileConvertToTable($increment_id,$data['chapter'],1);
                    

					$course_detail_data['id'] = $increment_id;

					$is_course_detail = $DB->table('__COURSE_DETAIL__')->data($course_detail_data)->add();

					if(!empty($increment_id) && !empty($is_course_detail)){

						if (!$DB->autoCheckToken($data)){

							$results = false;

						}else{

							$DB->commit();

							$results = true;

						}

					}

				} catch ( Exception $e ) {

					$DB->rollback();

					$results = false;
				}


			}else{

				//编辑
				try {

					$where['id'] = array("eq",$data['id']);

					$DB = M();

					$DB->startTrans();
					//查询上个版本并插入

					$course_bak = M("course")->where($where)->find();

					$course_detail_bak = M("course_detail")->where($where)->find();

					$course_bak['course_id'] = $data['id'];
					$course_bak['user_id'] = $_SESSION['user']['id'];
					$course_bak['update_time'] = time();

					unset($course_bak['id']);

					$course_bak_id = $DB->table('__COURSE_BAK__')->data($course_bak)->add();

					$course_detail_bak['course_id'] = $data['id'];
					$course_detail_bak['id'] = $course_bak_id;

					$DB->table('__COURSE_DETAIL_BAK__')->data($course_detail_bak)->add();
                    
					$exist = M("course")->where(array('chapter'=>$data['chapter']))->find();
					//更新课程
					$DB->table('__COURSE__')->where($where)->save($course_data);

					$DB->table('__COURSE_DETAIL__')->where($where)->save($course_detail_data);
        
                    //编辑成功后调用方法 重新插入课程章节表（think_course_chapter_file）的关联数据		
					if(!$exist){
                      $res = $this->fileConvertToTable($data['id'],$data['chapter'],2);
					}
                    
					
					$DB->commit();

					$results = true;

				} catch ( Exception $e ) {

					$DB->rollback();

					$results = false;
				}

			}
		}

		return $results;
	}



	/**
	 * 生成章节转换文件表的关联数据
	 * 参数： $course_id ； $chapter json格式课程章节;   $typeL:1新增 2编辑
	 * 作用：向章节转换文件表的关联数据，用于课件文档预览
	 */
	public function fileConvertToTable($course_id,$chapter,$type){
           if($type == 2){
              $res = M('course_chapter_file')->where(array('course_id'=>$course_id))->delete();
			  
			}
           if($res === false){
			   return false;
		   }
		   
          $chapterArr = json_decode($chapter,true);
        //   dump( $chapterArr);die;
		  // 自动启动事务支持
          M('course_chapter_file')->startTrans();
          try {
           foreach($chapterArr as $k=>$v){
             
			 $result = $this->fileConvert($v['src']);
			 
			 if($result['type'] == 'html'){
				 $result['type'] = 0;
			 }else{
				 $result['type'] = 1;
			 }
			 $onechapter = array(
				 'course_id'=>$course_id,
				 'chapter_id'=>$k,
				 'view_file_path'=>$result['new_path'],
				 'file_type'=>$result['type']
			 );
			 if($result['code'] == 1000){
				 $res = M('course_chapter_file')->add($onechapter); 			 
			 }
             
              
		    }
            
		    M('course_chapter_file')->commit();
			$results = true;
             
		} catch ( Exception $e ) {

			M('course_chapter_file')->rollback();
			$results = false;
		 }
       return $results;
       
	}







	/**
	 * 文件转换
	 * 参数： $httppath 七牛返回的文件路径； 
	 * 作用：QINIU服务器返回的地址转换成pdf或HTML文件保存在服务器文件夹，并返回保存文件的路径$message;
	 */
	public function fileConvert($httppath){
        //下载七牛文件到本地
		//获取文件名
		$fileArr = explode("/", $httppath);
		$inputFileName = end($fileArr);

        $inputPath = "./Upload/docConvert/".date("YmdHis");
		mkdir ($inputPath, 0777, true);  
		$inputFile = $inputPath."/".$inputFileName;  

		$cnt = file_get_contents($httppath);           
        file_put_contents($inputFile, $cnt); 
        
		// $inputFile = "/Upload/docConvert/survey999.xls";
		$message = A("Office")->convert($inputFile);
		return $message;
		// print_r($jsonmessage);
	}





	/**
	 * 获取内外部讲师数据
	 */
	public function getLecturer(){

		$rows = array();

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->centerSeparation();

		$conditions['auth_user_id'] = array("in",$specifiedUser);

		$items = M("lecturer")->field("auth_user_id,id,name,type")->where($conditions)->order("id asc")->select();

		foreach($items as $k=>$item){

			if($item['type'] == 1){
				$rows['external'][] = $item;
			}else{

				$rows['internal'][] = $item;
			}

		}

		return $rows;

	}

	/**
	 * 设置公开课
	 */
	public function setOpen(){

		$ids = I("post.ids");

		$is_public = I("post.is_public");

		try {

			$data['id'] = array("in",$ids);

			$course_list = M('course')->field("id,auditing")->where($data)->select();

			foreach($course_list as $list){
				if($list['auditing'] == 1){
					$course_id[] = $list['id'];
				}
			}

			$where['id'] = array("in",$course_id);

			$DB = M('course');

			$DB->startTrans();

			if(empty($course_id)){
				$increment_id = 402;
			}else{
				$increment_id =  $DB->where($where)->setField("is_public",$is_public);
			}

			if($increment_id){
				$DB->commit();
				$increment_id = 1;
			}

		} catch ( Exception $e ) {

			$DB->rollback();

		}

		return $increment_id;

	}

	/**
	 * 设置是否禁用
	 */
	public function setTrigger(){

		$id = I("post.id");

		$items = M("course")->field("auditing")->where("id=".$id)->find();

		try {

			$data['id'] = array("eq",$id);

			$DB = M('course');

			$DB->startTrans();

			if($items['auditing']){

				$increment_id =  $DB->where($data)->setField(array("is_public"=>0,"auditing"=>0));

			}else{

				$increment_id =  $DB->where($data)->setField(array("auditing"=>1));
			}

			if($increment_id){

				$DB->commit();
			}

		} catch ( Exception $e ) {

			$DB->rollback();

		}

		return $increment_id;

	}

	/**
	 * 删除课程
	 */
	public function delCourse(){

		$ids = I("post.ids");

		try {

			$data['id'] = array("in",$ids);

			$where['course_id'] = array("in",$ids);

			$DB = M();

			$DB->startTrans();

			$increment_id = $DB->table('__COURSE__')->where($data)->delete();

			$is_course_detail = $DB->table('__COURSE_DETAIL__')->where($data)->delete();

			$DB->table('__COURSE_BAK__')->where($where)->delete();

			$DB->table('__COURSE_DETAIL_BAK__')->where($where)->delete();

			if(!empty($increment_id) && !empty($is_course_detail)){

				$DB->commit();

				$results = true;
			}

		} catch ( Exception $e ) {

			$DB->rollback();

			$results = false;

		}

		return $results;


	}

	/**
	 * 添加多媒体视频
	 */
	public function addVideo(){

		/*视频转码
		$setting=C('UPLOAD_SITEIMG_QINIU');

		$policy = array(
			'persistentOps' => $setting['driverConfig']['persistentOps'],
			'persistentPipeline'=>$setting['driverConfig']['persistentPipeline'],
		);

		$auth = new \Think\Upload($setting,'Qiniu',$setting['driverConfig']);

		$upToken = $auth->UploadToken($setting['driverConfig']['secretKey'],$setting['driverConfig']['accessKey'],$policy);
		*/

		$setting=C('UPLOAD_SITEIMG_QINIU');

		$auth = new \Think\Upload($setting,'Qiniu',$setting['driverConfig']);

		$upToken = $auth->UploadToken($setting['driverConfig']['secretKey'],$setting['driverConfig']['accessKey']);

		$data = array(
			"uptoken"=>$upToken
		);

		return $data;
	}

	/**
	 * 检查视频是否转码成功
	 */
	public function pfopStatus(){

		$id = I("get.id");

		$url = "http://api.qiniu.com/status/get/prefop?id=$id";

		$curl = curl_get_contents($url);

		return $curl;
	}


	/**
	 *  分页公共方法
	 */
	public function pageClass($count,$total_page,$parameter){

		$Page = new \Think\Page($count,$total_page);

		if(!empty($parameter)){

			foreach($parameter as $key=>$val) {
				$Page->parameter[$key]   =   urlencode($val);
			}

		}

		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

		$Page->setConfig('header',"<span>%TOTAL_PAGE%</span>");

		$Page->setConfig('prev','上一页');

		$Page->setConfig('next','下一页');

		$show = $Page->show();

		return $show;
	}

	/**
	 * 课程历史版本
	 */
	public function HistoryVersion($total_page = 10){

		$course_id = I('get.id');

		$start_page = I("get.p",0,'int');

		$conditions['a.course_id'] = array("eq",$course_id);

		$course = M("course")->field("course_name")->where("id=".$course_id)->find();


		$list = M("course_bak a")
			->field("a.id,a.update_time,b.username")
			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
			->order('a.update_time desc')
			->where($conditions)
			->page($start_page,$total_page)
			->select();

		$count = M("course_bak a")
			->where($conditions)
			->count();


		$show = $this->pageClass($count,$total_page);

		$data = array(
			'course_name'=>$course['course_name'],
			'list'=>$list,
			'show'=>$show
		);

		return $data;
	}

	/**
	 * 查看历史版本
	 */
	public function checkcourse(){

		$course_id = I('get.id');

		$conditions['a.id'] = array("eq",$course_id);

		$data = M("course_bak a")
			->field("a.*,b.username,c.*")
			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id LEFT JOIN __COURSE_DETAIL_BAK__ c ON a.id = c.id")
			->order('a.id desc')
			->where($conditions)
			->find();

		if(!empty($data['chapter'])){

			$data['chapter'] = json_decode($data['chapter'],true);

		}

		if(!empty($data['lecturer'])){
			$lecturer = M("lecturer")->field("name")->where("id=".$data['lecturer'])->find();
		}


		if(!empty($data['tag_id'])){

			$tag_id['id'] = array("in",$data['tag_id']);

			$users_tag = M("users_tag")->field("tag_title")->where($tag_id)->select();

			foreach($users_tag as $tag){
				$tag_str[] = $tag['tag_title'];
			}

			$data['tag_name'] = implode(",",$tag_str);
		}

		//查看讲师名称
		if(empty($data['course_way'])){
			if(!empty($data['lecturer'])){
				$data['lecturer_name'] = $lecturer['name'];
			}
		}else{
			$data['lecturer_name'] = $lecturer['name'];
		}

		//查看上下版本

		$where['course_id'] = array("eq",$data['course_id']);

		$page_list = M("course_bak a")->field("a.id")->order('a.id desc')->where($where)->select();

		foreach($page_list as $k=>$list){

			if($course_id == $list['id']){

				if(!empty($page_list[$k-1])){
					$up  = $page_list[$k-1]['id'];
				}else{
					$up  = false;
				}

				if(!empty($page_list[$k+1])){
					$next  = $page_list[$k+1]['id'];
				}else{
					$next  = false;
				}

			}

		}

		$data['page'] = array(
			"up"=>$up,
			"next"=>$next
		);

		return $data;
	}

}