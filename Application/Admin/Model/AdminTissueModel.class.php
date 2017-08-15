<?php
namespace Common\Model;

use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class AdminTissueModel extends BaseModel{

	//初始化
	public function __construct(){}

	/**
	 *  组织架构首页
	 */
	public function index($total_page = 10){

		//获取组织架构信息
		//$treeInfo = $this->treeInfo();

		//获取当前用户所属组织岗位管理
		$jobs_manage = M("jobs_manage")->select();

		//获取所有用户标签
		$users_tag = M("users_tag")->select();

		//获取当前所属组织所有会员
		$start_page = I("get.p",0,'int');

		$keywords = I("get.keywords");

		$tissue_id = I("get.tissue_id");
		$type = I("get.type");

		$jobs_id = I("get.jobs_id");

		$tag_id = I("get.tag_id");


		/**
		 * 左侧菜单
		 */
		$map['a.user_id'] = array("eq",$_SESSION['user']['id']);

		//获取用户上级组织名称
		$group_data = M("tissue_group_access a")
			->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
			->field("b.id,b.pid")
			->order('b.id asc')
			->where($map)
			->find();

		$treeInfo = D('AdminTissue')->tree($group_data['id']);

		if(!empty($tag_id)){

			$user_list = M("users_tag_relation")->field("user_id")->where("tag_id=".$tag_id)->select();

			$user_arr = array();

			foreach($user_list as $user){
				$user_arr[] = $user['user_id'];
			}

			$conditions['a.user_id'] = array("in",$user_arr);
		}

		if(!empty($keywords)){

			$conditions['b.username|b.phone|b.email'] = array("like","%$keywords%");

			$Rules = $this->getRules($tissue_id);

			if(!empty($Rules)){
				array_unshift($Rules,$tissue_id);
				$conditions['a.tissue_id'] = array("in",$Rules);
			}else{
				$conditions['a.tissue_id'] = array("in",$tissue_id);
			}

			if(!empty($jobs_id)){
				$conditions['a.job_id'] = array("eq",$jobs_id);
			}

			$conditions['b.status'] = array("neq",3);

			$list = M("tissue_group_access a")
				->field("b.id,b.username,b.job_number,b.phone,b.email,a.tissue_id,a.job_id")
				->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
				->where($conditions)
				->order('a.user_id desc')
				->page($start_page,$total_page)
				->select();

			$count = M("tissue_group_access a")
				->field("a.group_id,b.id,b.username,b.job_number,c.title,d.*")
				->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
				->where($conditions)
				->order('a.user_id desc')
				->count();


		}else{

			if(!empty($tissue_id)){

				$conditions['a.tissue_id'] = array("eq",$tissue_id);

				$leveldata = $this->leveldata($tissue_id);

			}else{

				//未分配人员
				if($type == 'undistributed'){

					$conditions['a.tissue_id'] = array("eq",0);

				}else if($type == 'administrator'){

					//获取当前用户所在级别
					$level = D('AdminTissue')->hierarchy($treeInfo['id']);

					//获取所有组织数据
					$tissue_rule_list = D('IsolationData')->specifiedUser(false);

					foreach($tissue_rule_list as $id){

						$user_tissue_id = M("tissue_group_access")
							->where("user_id=".$id)
							->getField('tissue_id');

						$level_num = D('AdminTissue')->hierarchy($user_tissue_id);

						if($level_num < 4){
							$rule_arrid[] = $id;
						}

					}

					$rule_arrid = $rule_arrid ? $rule_arrid :  array(null);

					$conditions['a.user_id'] = array("in",$rule_arrid);

				}else{
					$tissue_id = $treeInfo['id'];
					if(!$tissue_id) $tissue_id = "0";
					$conditions['a.tissue_id'] = array("in",$tissue_id);
				}

			}

			if(!empty($jobs_id)){
				$conditions['a.job_id'] = array("eq",$jobs_id);
			}

			$conditions['b.status'] = array("eq",1);
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$group = 'a.user_id,b.id,b.username,b.job_number,b.phone,b.email,a.tissue_id,a.job_id';
			}else{
				$group = 'a.user_id';
			}
			
			$list = M("tissue_group_access a")
				->field("b.id,b.username,b.job_number,b.phone,b.email,a.tissue_id,a.job_id")
				->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
				->where($conditions)
				->order('a.user_id desc')
				->group($group)
				->page($start_page,$total_page)
				->select();

			$count = M("tissue_group_access a")
				->field("b.id,b.username,b.job_number,b.phone,b.email,a.tissue_id,a.job_id")
				->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
				->where($conditions)
				->group($group)
				->count();
		}

		$conditions['a.tissue_id'] = array("eq",0);

		$tissue_group_total = M("tissue_group_access a")
			->field("b.id,b.username,b.job_number,b.phone,b.email,a.tissue_id,a.job_id")
			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
			->where($conditions)
			->count();

		$show = $this->pageClass($count,$total_page);


		//查询当前负责人
		$where['tissue_id']  = array("eq",$tissue_id);

		$DB_PREFIX = strtolower(C('DB_PREFIX').'tissue_rule');

		if(!empty($tissue_id)){
			$is_display = $this->hierarchy($tissue_id);
		}else{
			$is_display = "";
		}

		$where['manage_id']  = array("in",'1,2');

		$setAdmin = M("tissue_group_access a")
			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
			->field("a.manage_id,b.username")
			->where($where)
			->order("a.manage_id asc")
			->select();

		$parentName = $this->parentName($tissue_id);

		$user_tissue_id = M("tissue_group_access")->field('tissue_id')->where("user_id=".$_SESSION['user']['id'])->find();

		$is_level = M("tissue_rule")->field("pid")->where("id=(select pid from $DB_PREFIX where id =".$user_tissue_id['tissue_id'].")")->find();

		if($is_level['pid'] > 0){
			$parentName = $treeInfo['name'];
		}

		$items = array();

		foreach($list as $k=>$vo){

			$tag_html = '';

			$items[$k] = $vo;

			$tag_list = M("users_tag_relation a")
					->join("LEFT JOIN __USERS_TAG__ b ON a.tag_id = b.id")
					->field('b.tag_title')
					->where("a.user_id=".$vo['id'])
					->select();

			if(!empty($tag_list)){
				$tag = array();

				foreach($tag_list as $list){
					$tag[] = $list['tag_title'];
				}

				$tag_html = implode(",",$tag);
			}

			$items[$k]['tag'] = $tag_html;
		}
		
		//获取二级组织（xx中心，分公司在xx中心层级下）
		$centerList = M("tissue_rule")->where("pid=1")->select();

		$level = $this->hierarchy($treeInfo['id']);

		$data = array(
			"tissue_group_total"=>$tissue_group_total,
			"tree_items"=>array($treeInfo),
			"jobs_manage"=>$jobs_manage,
			"jobs_id"=>$jobs_id,
			"tag_id"=>$tag_id,
			"users_tag"=>$users_tag,
			"list"=>$items,
			"tissue_id"=>$tissue_id,
			"setadmin"=>$setAdmin,
			"parentName"=>$parentName,
			"is_display"=>$is_display,
			"is_level"=>$is_level['pid'],
			"leveldata"=>$leveldata,
			"type"=>$type,
			"pages"=>$show,
			"centerList"=>$centerList,
			'level'=>$level
		);

		return $data;

	}

	/**
	 * 获取当前组织所有关联组织
	 */
	public function leveldata($tissue_id,&$rows){

		$rule = M("tissue_rule")->field("pid")->where("id=".$tissue_id)->find();

		if(!empty($rule)){

			$rows[] = $rule['pid'];

			$this->leveldata($rule['pid'],$rows);
		}

		return $rows;

	}

	/**
	 * 查询当前所在层级
	 */
	public function hierarchy($id,&$num = 0){

		$is_display = M("tissue_rule")->field("pid")->where("id=".$id)->find();

		if(!empty($is_display)){
			$num++;
			$this->hierarchy($is_display['pid'],$num);
		}

		return $num;
	}

	/**
	 * 获取上一级ID 名称
	 */
	function parentName($tissue_id){

		if(!empty($tissue_id)){

			$where['id'] = array("eq",$tissue_id);

			$rows = M("tissue_rule")->where($where)->find();

			$name = $rows['name'];

		}else{

			$name = '';
		}

		return $name;

	}

	/**
	 * 获取组织架信息
	 */
	public function treeInfo(){

		$superiorTissue = $this->superiorTissue();

		//获取左侧树形结构
		$tree_items = $this->tree($superiorTissue['id']);

		return $tree_items;
	}

	/**
	 * 组织构架左侧树形
	 */
	public function tree($pid){

		$rule_list = M("tissue_rule")->select();
		if($pid==null){
			$pid=0;
		}

		//获取一级分类
		$top = M("tissue_rule")->where("id=".$pid)->find();
		
		// 获取一级下所有下级分类
		$item = \Org\Nx\Data::channelLevel($rule_list,$pid,'&nbsp;','id');

		$top['_data'] = $item;

		return $top;
	}


	/**
	 * 新添下级组织
	 */
	public function addTissue(){

		$tissue_name = I('post.tissue_name');
		$tissue_id = I('post.tissue_id');

		$getRules = $this->getRules($tissue_id);

		$data=array(
			'name'=>$tissue_name,
			'pid'=>$tissue_id,
		);


		$is_name = D("tissue_rule")->where(array("name"=>array("eq",$tissue_name)))->find();

		if(empty($is_name)){

			try {

				$DB = M('tissue_rule');

				$DB->startTrans();
				
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$data['id'] = getNextId('tissue_rule');
				}
				$increment_id = $DB->data($data)->add();

				if(empty($getRules)){

					$rules = $increment_id;

				}else{
					array_push($getRules,$increment_id);

					$rules = implode(",",$getRules);
				}

				$where['id'] = array("eq",$tissue_id);

				$results = $DB->where($where)->setField("rules",$rules);

				if($increment_id && $results){
					$DB->commit();
				}

			} catch ( Exception $e ) {

				$DB->rollback();

			}

		}else{

			$results = false;

		}

		return $results;

	}

	/**
	 * 获取当前级别上级信息
	 */
	public function superiorTissue(){

		$where['a.user_id'] = array("eq",$_SESSION['user']['id']);

		//获取用户上级组织名称
		$tissue_name = M("tissue_group_access a")
				->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
				->field("b.id,b.name")
				->where($where)
				->find();
		
		return $tissue_name;

	}

	/**
	 * 获取所有下级权限
	 */
	public function getRules($pid){

		$rule_list = M("tissue_rule")->select();

		$data = \Org\Nx\Data::channelList($rule_list,$pid,'&nbsp;','id');

		$rows = array(0);

		if(!empty($data)){

			foreach($data as $item){
				$rows[] = $item['id'];
			}

		}else{

			$rows[] = $pid;

		}

		return $rows;

	}


	/**
	 * 添加新员工
	 */
	public function addUser(){

		$post =  I('post.');

		//插入标签
		$tag_list = $this->addusertag($post['tag']);

		if($tag_list['code'] == 200){
			$tag_list = $tag_list['tag_list'];
		}else{
			return 407;
		}

		$tissue_id = $post['tissue_id'] ? $post['tissue_id'] : 0;

		$jobs_id = $post['jobs_id'] ? $post['jobs_id'] : 0;

		$orderno =  D('Trigger')->orderNumber(9);

		$data = array(
			'phone'   => $post['phone'],
			'password' => md5($post['password']),
			'username' => $post['username'],
			"birthday"=>$post['birthday'],
			"gender"=>$post['gender'],
			"group_time"=>$post['group_time'],
			"center_time"=>$post['center_time'],
			"area"=>$post['area'],
			"age"=>$post['age'],
			"job_number"=>$post['job_number'],
			"room"   =>$post['room'],
			"rank"   =>$post['rank'],
			'sequence'   => $post['sequence'],
			'education'   => $post['education'],
			'mobilephone'   => $post['mobilephone'],
			'tel'   => $post['tel'],
			"email"=>$post['email'],
			"ip_phone"=>$post['ip_phone'],
			"register_time"=>time(),
			"orderno"=>$orderno,
			"status"=>1
		);
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['group_time'] = array('exp',"to_date('".date('Y-m-d')."','yy-mm-dd')");
			$data['center_time'] = array('exp',"to_date('".date('Y-m-d')."','yy-mm-dd')");
		}

		//头像处理
		if(!empty($post["imgType"])){
			$fileName = date("Ymd").uniqid().".png";
			$image = explode(',', $post["avatar"]);
			$image = $image[1];
			$image = base64_decode($image);
			$result = file_put_contents("./Upload/avatar/".$fileName, $image);
			if($result > 0){
				$_SESSION['user']['avatar'] = "/Upload/avatar/".$fileName;
				$data["avatar"] = "/Upload/avatar/".$fileName;
			}else{
				$increment_id = 405;
				return $increment_id;
			}
		}

		if($post['education'] != ""){
			$has = M("tissue_group_access")->where("serial_number='".$post['education']."'")->select();
			if($has){
				$increment_id = 404;
				return $increment_id;
				//return array("code"=>1021, "message"=>"当前序列号已存在，请更换");
			}
		}

		if(!empty($post['email'])){

			$has = M("users")->where("email='".$post['email']."'")->find();

			if($has){
				$increment_id = 406;
				return $increment_id;
			}

		}

		if(empty($post["email"]) || $post["email"] && !filter_var($post["email"],FILTER_VALIDATE_EMAIL)){

			$increment_id = "408";
			return $increment_id;

		}


		if(!empty($data['phone'])){

			$where['phone'] = array("eq",$data['phone']);

			$is_phone = M("Users")->where($where)->find();

			if($is_phone){

				$increment_id = 401;

				return $increment_id;

			}

		}

		if(!empty($post['job_number'])){

			$map['job_number'] = array("eq",$post['job_number']);

			$is_number = M("Users")->where($map)->find();

			if($is_number){

				$increment_id = 402;

				return $increment_id;

			}
		}

		if(!empty($data['username']) && !empty($data['password']) && !empty($data["email"])){

			try {

				$DB = M('Users');

				$DB->startTrans();
				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$data['id'] = getNextId('users');
					$data['register_time'] = array('exp',"to_date('".date('Y-m-d H:i:s',$data['register_time'])."','yy-mm-dd hh24:mi:ss')");
				}
				
				$increment_id = $DB->data($data)->add();

				if($increment_id){

					M('auth_group_access')->add(array("user_id"=>$increment_id,"group_id"=>3));

					M('tissue_group_access')->add(array("user_id"=>$increment_id,"tissue_id"=>$tissue_id,"job_id"=>$jobs_id,"manage_id"=>0));

					foreach($tag_list as $tag){

						//插入数据
						$tag_data = array(
							'user_id'=>$increment_id,
							'tag_id'=>$tag
						);

						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$tag_data['id'] = getNextId('auth_group');
						}

						M('users_tag_relation')->add($tag_data);
					}

					$DB->commit();

					$increment_id = 200;

				}

			} catch ( Exception $e ) {

				$DB->rollback();

			}

		}else{

			$increment_id = 403;

		}



		return $increment_id;
	}

	/**
	 * @param $post
	 * @return mixed
	 * 插入用户标签
	 */
	public function addusertag($post){

		//判断标签重复
		foreach($post as $item){

			$where = array();

			$where['tag_title'] = array("eq",$item[1]);

			if(!empty($item[0])){
				$where['id'] = array("neq",$item[0]);
			}

			$is_tag = M("users_tag")->where($where)->find();

			if($is_tag){
				$msg = array("code"=>407,"info"=>"该标签已存在: ".$item[1]);
				break;
			}
		}

		if(empty($msg)){

			try {

				$DB = M('users_tag');

				foreach($post as $k=>$val){

					if(empty($val[0])){
						$data = array(
							"tag_title"=>$val[1]
						);

						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$data['id'] = getNextId('auth_group');
						}

						if(!empty($val[1])){
							$tag[$k] = $DB->data($data)->add();
						}

					}else{
						$tag[$k] = $val[0];
						$DB->where('id='.$val[0])->setField("tag_title",$val[1]);
					}
				}

				$msg = array("code"=>200,"info"=>"操作成功","tag_list"=>$tag);

				$DB->commit();

			} catch ( Exception $e ) {

				$DB->rollback();

			}

		}

		return $msg;

	}

	/**
	 * 修改用户标签
	 */
	public function delusertag($data_list){

		//判断标签重复
		foreach($data_list as $item){

			$where = array();

			$where['tag_title'] = array("eq",$item[1]);

			if(!empty($item[0])){
				$where['id'] = array("neq",$item[0]);
			}

			$is_tag = M("users_tag")->where($where)->find();

			if($is_tag){
				$msg = array("code"=>407,"info"=>"该标签已存在: ".$item[1]);
				break;
			}
		}

		if(empty($msg)){

			try {

				$DB = M('users_tag');

				foreach($data_list as $k=>$val){

					if(empty($val[0])){
						$data = array(
							"tag_title"=>$val[1]
						);
						if(!empty($val[1])){
							$tag[$k] = $DB->data($data)->add();
						}

					}else{
						$tag[$k] = $val[0];
						$DB->where('id='.$val[0])->setField("tag_title",$val[1]);
					}
				}

				$msg = array("code"=>200,"info"=>"操作成功","tag_list"=>$tag);

				$DB->commit();

			} catch ( Exception $e ) {

				$DB->rollback();

			}

		}

		return $msg;
	}

	/**
	 * 编辑当前组织名称
	 */
	public function editorTissue(){

		$redact =  I('post.redact');

		$tissue_id =  I('post.tissue_id');

		if(!empty($redact)){

			$results = M('tissue_rule')->where("id=".$tissue_id)->setField("name",$redact);

		}else{

			$results = false;
		}

		return $results;
	}

	/**
	 * 移动会员到组织架构
	 */
	public function moveUser(){

		$typeid =  I('post.typeid');

		if($typeid == 1){

			$user_id =  I('post.user_id');

			$tissue_Id =  I('post.tissue_Id');

			$tissue_list = json_decode($tissue_Id,true);

			//删除当前所属组织
			M("tissue_auth")->where("user_id=".$user_id)->delete();

			//重新赋予新组织
			foreach($tissue_list as $id){

				M('tissue_auth')->add(array("user_id"=>$user_id,"tissue_id"=>$id));

			}

			$results = 1;

		}else{

			$ids =  I('post.ids');

			$tissue_Id =  I('post.tissue_Id');

			$user_all = json_decode($ids,true);

			$tissue_list = json_decode($tissue_Id,true);

			foreach($user_all as $user_id){

				$group_data = M("tissue_group_access")->field("job_id")->where("user_id=".$user_id)->find();

				$job_id = $group_data['job_id'] ? $group_data['job_id'] : 0;

				//删除当前所属组织
				M("tissue_group_access")->where("user_id=".$user_id)->delete();

				//重新赋予新组织
				foreach($tissue_list as $id){
					M('tissue_group_access')->add(array("user_id"=>$user_id,"tissue_id"=>$id,"job_id"=>$job_id,"manage_id"=>0));
				}

			}

			$results = 1;

		}

		return $results;
	}


	/**
	 * 编辑会员资料
	 */
	public function editUser(){

		$user_id =  I('get.user_id');

		$where['a.id'] = array("eq",$user_id);

		$user = M("Users a")
			->field("a.*,b.tissue_id as tid,b.job_id")
			->join("LEFT JOIN __TISSUE_GROUP_ACCESS__ b ON a.id = b.user_id")
			->where($where)
			->find();


		$map['a.user_id'] = array("eq",$user_id);

		$tag_list = M("users_tag_relation a")->join("LEFT JOIN __USERS_TAG__ b ON a.tag_id = b.id")->where($map)->select();

		$data = array(
			"user_info"=>$user,
			"tag_list"=>$tag_list
		);

		return $data;
	}

	/**
	 * 提交更新会员资料
	 */
	public function UpdateUser(){

		$post =  I('post.');

		$data = array(
			'phone'   => $post['phone'],
			'username' => $post['username'],
			"birthday"=>$post['birthday'],
			"gender"=>$post['gender'],
			"group_time"=>$post['group_time'],
			"center_time"=>$post['center_time'],
			"area"=>$post['area'],
			"age"=>$post['age'],
			"job_number"=>$post['job_number'],
			"room"   =>$post['room'],
			"rank"   =>$post['rank'],
			'sequence'   => $post['sequence'],
			'education'   => $post['education'],
			'mobilephone'   => $post['mobilephone'],
			'tel'   => $post['tel'],
			"email"=>$post['email'],
			"ip_phone"=>$post['ip_phone'],
		);

		//头像处理
		if(!empty($post["imgType"])){
			$fileName = date("Ymd").uniqid().".png";
			$image = explode(',', $post["avatar"]);
			$image = $image[1];
			$image = base64_decode($image);
			$result = file_put_contents("./Upload/avatar/".$fileName, $image);
			if($result > 0){
				$_SESSION['user']['avatar'] = "/Upload/avatar/".$fileName;
				$data["avatar"] = "/Upload/avatar/".$fileName;
			}else{
				$increment_id = 405;
				return $increment_id;
			}
		}

		if(!empty($post['password'])){

			$data['password'] = md5($post['password']);

		}

		if(!empty($post['phone'])){

			$where['phone'] = array("eq",$post['phone']);
			$where['id'] = array("neq",$post['id']);

			$is_phone = M("Users")->where($where)->find();
		}


		if(!empty($post['job_number'])){
			$condition['job_number'] = array("eq",$post['job_number']);
			$condition['id'] = array("neq",$post['id']);
			$is_number = M("Users")->where($condition)->find();
		}

		if(!empty($post['email'])){

			$map['email'] = array("eq",$post['email']);
			$map['id'] = array("neq",$post['id']);

			$has = M("users")->where($map)->find();

			if($has){
				$increment_id = "403";
				return $increment_id;
			}

		}

		if($post["email"] && !filter_var($post["email"],FILTER_VALIDATE_EMAIL)){

			$increment_id = "408";
			return $increment_id;

		}


		//更新标签
		$tag_list = $this->delusertag($post['tag']);

		if(!empty($is_phone)){
			$result = 401;
		}else if(!empty($is_number)){
			$result = 402;
		}else if($tag_list['code'] == 407){

			$result = $tag_list['code'];

		}else{

			try {

				$DB = M('Users');

				$DB->startTrans();


				if(strtolower(C('DB_TYPE')) == 'oracle'){
					$data['birthday'] = array('exp',"to_date('".$data['birthday']."','yy-mm-dd hh24:mi:ss')");
					$data['group_time'] = array('exp',"to_date('".$data['group_time']."','yy-mm-dd')");
					$data['center_time'] = array('exp',"to_date('".$data['center_time']."','yy-mm-dd')");
				}


				$increment_id = $DB->where('id='.$post['id'])->setField($data);

				$data = array('job_id'=>$post['jobs_id'],'tissue_id'=>$post['tissue_id']);

				$is_group = M('tissue_group_access')->where('user_id='.$post['id'])->setField($data);

				//删除标签
				M('users_tag_relation')->where('user_id='.$post['id'])->delete();

				if(!empty($tag_list['tag_list'])){
					foreach($tag_list['tag_list'] as $tag){
						if(strtolower(C('DB_TYPE')) == 'oracle'){

							$tag_data = array(
								'user_id'=>$post['id'],
								'tag_id'=>$tag
							);

							if(strtolower(C('DB_TYPE')) == 'oracle'){
								$tag_data['id'] = getNextId('auth_group');
							}

						}

						M('users_tag_relation')->add($tag_data);
					}

				}

				$DB->commit();
				$result = 200;

			} catch ( Exception $e ) {

				$DB->rollback();
				$result = 400;
			}

		}

		return $result;


	}

	/**
	 * 删除组织架构会员
	 */
	public function delUser(){

		$ids =  I('post.ids');

		$user_id = $_SESSION['user']['id'];

		$increment_id = "";

		$arr_id = explode (",",$ids);

		$userid_arr = array();

		foreach($arr_id as $id){

			if($id != $user_id){
				$userid_arr[] = $id;
			}
		}

		if(!empty($userid_arr)){

			$where['id'] = array("in",$userid_arr);

			try {

				$DB = M('Users');

				$DB->startTrans();

				$increment_id =  $DB->where($where)->setField("status",3);

				if($increment_id){

					$DB->commit();
				}

			} catch ( Exception $e ) {

				$DB->rollback();

			}

		}else{

			$increment_id = 0;
		}

		return $increment_id;

	}

	/**
	 * 组织架构设置管理员
	 */
	public function setAdmin(){

		$user_id =  I('post.user_id');
		$tissue_id =  I('post.tissue_id');
		$type =  I('post.type');

		$increment_id = "";

		try {

			$DB = M('tissue_group_access');

			$DB->startTrans();

			$DB->where(array("tissue_id"=>array("eq",$tissue_id),"manage_id"=>array("eq",$type)))->setField("manage_id",0);

			$increment_id =  $DB->where(array("tissue_id"=>array("eq",$tissue_id),"user_id"=>array("eq",$user_id)))->setField("manage_id",$type);

			if($increment_id){

				$DB->commit();
			}

		} catch ( Exception $e ) {

			$DB->rollback();

		}

		return $increment_id;

	}

	/**
	 * 删除组织
	 */
	public function delTissue(){

		$tissue_id =  I('post.tissue_id');

		$where['a.tissue_id'] = array("eq",$tissue_id);
		$where['b.status'] = array("neq",3);

		$list = M("tissue_group_access a")
			->join("LEFT JOIN __USERS__ b ON a.user_id = b.id")
			->where($where)
			->find();

		if(!empty($list)){

			$results = "";

		}else{

			$results = M("tissue_rule")->where("id=".$tissue_id)->delete();

		}

		return $results;
	}

	/**
	 * 标签
	 */
	public function tag(){

		$tag_data = M("users_tag")->select();

		$data = array(
			'items'=>$tag_data
		);

		return $data;

	}

	/**
	 * 删除标签
	 */
	public function deltag(){

		$id = I("post.id");

		$is_tag = M("users_tag_relation")->where("tag_id=".$id)->find();

		if(empty($is_tag)){

			$results = M("users_tag")->where("id=".$id)->delete();

		}else{

			$results = false;
		}

		return $results;


	}

	/**
	 * 添加标签
	 */
	public function addtag(){

		$list = I('post.list');

		//判断标签重复
		foreach($list as $item){

			$where = array();

			$where['tag_title'] = array("eq",$item[1]);

			if(!empty($item[0])){
				$where['id'] = array("neq",$item[0]);
			}

			$is_tag = M("users_tag")->where($where)->find();

			if($is_tag){
				$msg = array("code"=>401,"info"=>"该标签已存在: ".$item[1]);
				break;
			}
		}

		if(empty($msg)){

			try {
				$DB = M('users_tag');

				foreach($list as $val){

					if(empty($val[0])){
						$data = array(
							"tag_title"=>$val[1]
						);
						if(strtolower(C('DB_TYPE')) == 'oracle'){
							$data['id'] = getNextId('users_tag');
						}
						if(!empty($val[1])){
							$increment_id = $DB->data($data)->add();
						}
					}else{
						$increment_id = $DB->where('id='.$val[0])->setField("tag_title",$val[1]);
					}
				}

				if($increment_id){

					$DB->commit();
				}

				$msg = array("code"=>200,"info"=>"操作成功");

			} catch ( Exception $e ) {

				$DB->rollback();

			}

		}

		return $msg;

	}

	/**
	 * 批量添加用户标签
	 */
	public function addbatchmarking(){

		$list = I('post.list');

		$tag_id = I('post.tag_id');

		$items = json_decode($list,true);

		$tag_list = json_decode($tag_id,true);

		$where['user_id'] = array("in",$items);

		M("users_tag_relation")->where($where)->delete();

		try {

			$DB = M('users_tag_relation');

			foreach($items as $val){

				foreach($tag_list as $tag){

					$data = array(
						"tag_id"=>$tag,
						"user_id"=>$val
					);

					if(strtolower(C('DB_TYPE')) == 'oracle'){
						$data['id'] = getNextId('auth_group');
					}

					$DB->data($data)->add();

				}

			}


			$DB->commit();


		} catch ( Exception $e ) {

			$DB->rollback();

		}

		return 1;

	}


}
