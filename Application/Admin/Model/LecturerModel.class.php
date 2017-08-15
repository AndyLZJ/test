<?php 

namespace Admin\Model;

use Common\Model\BaseModel;
/**
 * 资源管理-讲师管理模型
 */
class LecturerModel extends BaseModel{	
	// public function __construct(){

	// }
	
	/**
    *讲师批量删除
    */
     public function batchDelete()
	 {
     if (IS_POST) {
      //  print_r(I('post.checkbox')); exit;
               foreach (I('id') as $k => $v) {
               $where = array(
                'id'=>$v,
               ); 
               $res = $this->where($where)->delete();
            }
             return $res;
        }
           
     }

	 /**
     *讲师列表单个删除，讲师下没有相关授课课程则可以删除，彻底删除
     */
    public function lecturerDelone(){
       $id  = I('id'); 
	   $m = M('course'); 
       $num = $m->where(array('lecturer' => $id))->count();
	//    echo $num;
	   if($num == 0){
		   $this->where(array('id'=>$id))->delete();
		   return true;
	   }else{
           return false;
	   }
    }

    /**
     *封装图片上传类
     */
	 public function upfile($subDir,$ext){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     8*1024*1024 ;// 设置附件上传大小
            $upload->exts      =     $ext; //array('jpg', 'gif','png','jpeg','bmp');// 设置附件上传类型
            $upload->rootPath  =     './Upload/'; // 设置附件上传根目录
            $upload->savePath  =     $subDir; // 设置附件上传（子）目录
            $upload->autoSub = true;
            $upload->subName = '';
            // 上传文件
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                $this->error = $upload->getError();
                return false;
            }else{// 上传成功
                foreach($info as $v){
                    $file_path1 = '/Upload'.'/'.$v['savepath'];
                    $file_path2 = $v['savename'];
                }
                $file = $file_path1.$file_path2;
                // $error = 0;
                // $data['error_info']=$error;
                $data['file']=$file;
                return  $data;
            }

      }
	
	/**
	 * 讲师新增或者更新
	 */
	public function update()
	{

		// $data = $this->create();
		$data = I('post.');
		$data['levels'] = $data['level'];
		$data['description'] = $data['desc'];
		
        $data['tname'] = json_encode($data['tname']);
		// print_r($data['tname']);die;
		if (empty($data)) { //表单信息为空则抛出错误
			$this->error = '数据异常';
			return false;
		}
		$data['user_id'] = $_SESSION['user']['id']; //取登录者的id作为数据的user_id

		$data['auth_user_id'] = $_SESSION['user']['id'];

		//判断是否为外部讲师,真则是外部讲师
		if (empty($data['user_id'])) {
			if (empty($data['name'])) {
				$this->error = '外部讲师姓名不能为空';
				return false;
			}
		
		//内部讲师判断：根据user_id获得用户users表的姓名，把取得的姓名存进$data插入讲师表
		} else {

			$name = $this->gname($data['user_id']);
			if (empty($name)) {
				$this->error = '内部讲师姓名不能为空';
				return false;
			}
			
			if (empty($data['name'])) {
				$data['name'] = $name;
			}
		}
         
		 
		$id = $data['id']; 
		if (empty($id)) {   //根据$id判断是新增数据还是修改数据,//新增数据

            $data['create_time'] = time(); 
		
            //上传图片
			if(!empty($_FILES['file0']['name'])){

		
              $file =  $this->upfile('certificate/',array('jpg', 'gif','png','jpeg','bmp'));
		      if(!$file){
                 return false;
		      }
		       $data['certificates'] =  $file['file'];
			}


			//判断是否内部讲师已存在，已存在抛出错误
            $exist = M('lecturer')->where(array('user_id'=>$data['user_id']))->getField('user_id');
			if (!empty($exist)) {
				$this->error = '内部讲师不能重复添加';
				return false;
			  }
			//判断外部讲师是否重复添加
			 $exist = M('lecturer')->where(array('name'=>$data['name']))->getField('name');
			if (!empty($exist)) {
				$this->error = '外部讲师不能重复添加';
				return false;
			}
			
			//oracle数据库主键ID处理
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('lecturer');
				$data['employed_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}
			
		    $result = $this->add($data);

		} else {   //修改数据
            if(empty($_FILES['file0']['name'])){ //编辑时如果没有改变图片则去掉图片的input
              unset($data['file0']);
			 }else{
			 //上传图片
			$file =  $this->upfile('certificate/',array('jpg', 'gif','png','jpeg','bmp'));
			  if(!$file){
                   return false;
		        }
		     $data['certificates'] =  $file['file'];	 				 
			 }
		
		//编辑时判断是否内部讲师已存在，已存在抛出错误
            $exist = M('lecturer')->where(array('user_id'=>$data['user_id']))->where('id!='.$id)->getField('user_id');
			if (!empty($exist)) {
				$this->error = '内部讲师不能重复添加';
				return false;
			  }
		//编辑时判断外部讲师是否重复添加
		   $exist = M('lecturer')->where(array('name'=>$data['name']))->where('id!='.$id)->getField('name');
			if (!empty($exist)) {
				$this->error = '外部讲师不能重复添加';
				return false;
			}	
			$result = $this->save($data);
		}

		  return true;
	}



	/*
	 * 根据ID获取详情页(编辑时调用)
	 */
	public function detail($id)
	{
		$data = $this->find($id);
       
		// $Model = M('supplier');
        // $sname = $Model->field('sname')->where(array('sid' => $data['sid']))->find();
		// $sname = $sname['sname'];
		// $data['sname'] = $sname;
		// print_r($data);
		return $data;
		
	}

  

	/**
	 * 根据用户ＩＤ获取用户名
	 */
	public function gname($user_id)
	{
		$Model = M('users');
		$result = $Model->field('username')->where(array('id' => $user_id))->find();
		return $result['username'];
	}

	/**
	 * 根据用户ＩＤ获取教师工号
	 */
	public function gjobnumber($user_id)
	{
		$Model = M('users');
		$result = $Model->field('job_number')->where(array('id' => $user_id))->find();
		return $result['job_number'];
	}
    /**
	 * 获取登录者当前所在组织id
	 */
	public function ghometissue()
	{
      //
		$tissue = M('tissue_group_access')->where(array('user_id'=>$_SESSION['user']['id']))->find();
        $tissueid =  $tissue['tissue_id'];
		return $tissueid;
	}

    /**
	 * 获取用户所在组织名
	 */
	public function gusertissue($user_id)
	{
      //
	  	if(!empty($user_id)){

			  	$tissue = M('tissue_group_access a')
			  			->field("b.name")
			  			->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
			  			->where(array("a.user_id"=>array("eq",$user_id)))
			  			->find();
		  }else{

			  $tissue = '';
		  }
		
	 	return $tissue['name'];
	}

    /**
	 * 获取登陆者所在组织下一级或同级组织与学员（被选为内部讲师）的关联的数据
	 * 返回的是数组new
	 */
	public function getLecturers()
	{  
         //获取当前组织下的所有组织树形结构
        $data=D('AdminTissue')->treeInfo();

        //获取该当前组织下的所有学员
        $arr = $this->getRights();
	
		//获取登录者当前所在组织id
		$tissue = M('tissue_group_access')->where(array('user_id'=>$_SESSION['user']['id']))->find();
        $tissueid =  $tissue['tissue_id'];
       //获取登录者当前所在组织的下一级组织id,array
	    $tissues = M('tissue_rule')->field('id')->where(array('pid'=>$tissueid))->select();
	
		
		if(empty($tissues)){ //如果没有下级组织走这步
          $str = $this->getRights(); //调用base模型方法获取组织下的所有学员
		      $str_user[$tissueid] = $str;
			  $m = M('tissue_rule');
			  $u = M('users');
              foreach($str_user as $k=>$v){
				$v = explode(',',$v); //学员的数组
                $name = $m->field('name')->where(array('id'=>$k))->find();
                $tissuename = $name['name'];
				foreach($v as $vo){
                  $username = $u->where(array('id'=>$vo))->getField('username');
				  $new[$tissuename][$vo]  = $username;
				}				
				
			  }
		 return  $new;
		}
        //根据登录者当前所在组织的下一级组织id，返回下一级组织的所有成员
	  foreach($tissues as $v){
			$v = $v['id']; //下一级组织id
		
       $user_all = array();

        //获取下一级组织权限
        $rows = M("tissue_rule")->field("rules")->where(array("id"=>$v))->find();
  
	  if(empty($rows['rules'])){
        $items = M("tissue_group_access")->alias('a')
		                      ->field("user_id")
							  ->join("left join __USERS__ b on b.id = a.user_id")
							  ->where(array('b.status'=>array('NEQ',3)))
							  ->where(array('a.tissue_id'=>$v))
							  ->select();
	  }else{
	
		$rows['rules'] = $rows['rules'].','.$v;
		
        //获取当前组织下所有允许访问的会员
         $tdata['a.tissue_id'] = array("in",$rows['rules']);
    
         $items = M("tissue_group_access")->alias('a')
		                       ->field("user_id")
							   ->join("left join __USERS__ b on b.id = a.user_id")
							   ->where(array('b.status'=>array('NEQ',3)))
							   ->where($tdata)
							   ->select();

	  }
       
        foreach($items as $item){
            $user_all[] = $item['user_id'];
        }

        $str_user[$v] = implode(",",$user_all);
    }

	  //登录者所在组织的人员
	    $user_us = M('tissue_group_access')->alias('a')
		                       ->field("user_id")
							   ->join("left join __USERS__ b on b.id = a.user_id")
							   ->where(array('b.status'=>array('NEQ',3)))
							   ->where(array('tissue_id'=>$tissueid))
							   ->select(); 
		foreach($user_us as $k=>$v){
           $temp[] =  $v['user_id'];
		}
		
		$user_us = implode(',',$temp);
        $str_user[$tissueid] = $user_us;

		ksort($str_user);


		      $m = M('tissue_rule');
			  $u = M('users');
              foreach($str_user as $k=>$v){
				$v = explode(',',$v); //学员的数组
                $name = $m->field('name')->where(array('id'=>$k))->find();
                $tissuename = $name['name'];
				foreach($v as $vo){
                  $username = $u->where(array('id'=>$vo))->getField('username');
		
				 $new[$tissuename][$vo]  = $username;
				}				
				
			  }
			  print_r($new);
			  return  $new;
	}


   
      
    /**
	 * 获取登陆者所在组织下一级或同级组织与学员（被选为内部讲师）的关联的数据
	 * 返回的是数组array $data\

		*	 Array
		*  (
		*     [中国太平洋保险] => Array
		*        (
		*            [1] => admin
		*            [695] => 张三
		*            [757] => dhjd
		*        )
		*     [稽核中心] => Array
		*        (
		*            [448] => 罗威
		*            [450] => 汪炳昌
		*            [697] => test001
		*            [698] => test002
		*            [718] => 思思
		*            [753] => wfwefewf
		*        )
		*		)
	 */
	public function getToLecturer()
	{  
		return D('TissueRule')->getToLecturer();
    }


	/**
	 * 获取列表分页展示数据
	 */
	public function getPagelist($type = 0, $p = 1, $size)
	{   

		//获取搜索条件
        $condition = I('get.table_search');
		$condition = trim($condition);
		$condition = $condition ? $condition : '';
		$map = array('name'=>array('like','%'.$condition.'%'));
       
		$user_id = $_SESSION['user']['id'];

		//隔离数据过滤
		$specifiedUser = D('IsolationData')->specifiedUser();

		$map['auth_user_id'] = array("in",$specifiedUser);

		//获取内部讲师
		if ($type == 0) {
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$list = $this
						->where(array('type' => $type))
						->where($map)
						->field("id,name,user_id,auth_user_id,age,type,levels,year,price,num,times,address,description,certificates,sid,tname,to_char(employed_time,'YYYY-MM-DD HH24:MI:SS') as employed_time")
						->order('id desc')
						->page($p, $size)
						->select();
			}else{
				$list = $this->where(array('type' => $type))->where($map)->order('id desc')->page($p, $size)->select();
			}
			 
          //分页
		    $count = $this->where(array('type' => $type))->where($map)->count();

			// echo $count;
            $show = $this->pageClass($count,$size);

			foreach ($list as $k => $v) {
				$list[$k]['name'] = $this->gname($v['user_id']);
				$list[$k]['job_number'] = $this->gjobnumber($v['user_id']); 
				$list[$k]['tissuename'] = $this->gusertissue($v['user_id']); 

				$tnameArr = json_decode($v['tname'],true);
               if(empty($tnameArr)){
                  $list[$k]['tname'] = '';
			   }else{	
				$tnames = array();   
				foreach($tnameArr as $ko=>$vo){
					$tnames[] = $this->getTnamefield($vo);
					$list[$k]['tname'] = implode(',',$tnames);  
				}
               }
			}
			// print_r($list);exit;
			//预留获取部门
			//............

		}
		//获取外部讲师
		if ($type == 1) {
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){$list = $this
				->where(array('type' => $type))
				->where($map)
				->field("id,name,user_id,auth_user_id,age,type,levels,year,price,num,times,address,description,certificates,sid,tname,to_char(employed_time,'YYYY-MM-DD HH24:MI:SS') as employed_time")
				->order('id desc')
				->page($p, $size)
				->select();
			}else{
				$list = $this
				->where(array('type' => $type))
				->where($map)
				->order('id desc')
				->page($p, $size)
				->select();
			}
            //分页
		    $count = $this->where(array('type' => $type))->where($map)->count();

			// echo $count;
            $show = $this->pageClass($count,$size);

          //获取供应商,擅长领域
			$Model = M('supplier');
			foreach($list as $k=>$v){
               $sname = $Model->field('sname')->where(array('sid' => $v['sid']))->find();
			   $list[$k]['sname'] = $sname['sname'];


               $tnameArr = json_decode($v['tname'],true);
			   if(empty($tnameArr)){
                  $list[$k]['tname'] = '';
			   }else{
				$tnames = array();	   
				foreach($tnameArr as $ko=>$vo){
					$tnames[] = $this->getTnamefield($vo);
					$list[$k]['tname'] = implode(',',$tnames);  
				}
               }
			
			}
			
			
			//..........
		}

		//隔离数据过滤
		$list = D('IsolationData')->isolationData($list);

		$data = array(
			'0'=>$show,
			'1'=>$list
		);

		return $data;

	}

	/**
	 * 获取分页总数
	 */
	public function getCount($type = 0)
	{
		$user_id = $_SESSION['user']['id'];
		$count = $this->where(array('type' => $type))->count();
		return $count;	
	}

	/**
	 *获取讲师详情
	 */
	public function getDetail($type)
	{  //$type:0表示内部 1表示外部
		$id = I('get.id');
		$user_id = $_SESSION['user']['id'];

		$detail = $this->where(array('id' =>$id))->find();
		// print_r($detail);
		//获取工号
		// $number = ['company_id' => '1234'];
		$Model = M('users');
		$number = $Model->field('job_number')->where(array('id' => $detail['user_id']))->find();
		$job_number = $number['job_number'];
		$detail['job_number'] = $job_number;
		// print_r($job_number);
		
       //获取供应商
		$Model = M('supplier');
        $sname = $Model->field('sname')->where(array('sid' => $detail['sid']))->find();
		$sname = $sname['sname'];
		$detail['sname'] = $sname;
        
		//获取擅长领域
		$tnameArr = json_decode($detail['tname'],true);
		foreach($tnameArr as $ko=>$vo){
			$tnames[] = $this->getTnamefield($vo);	
		}    
        $detail['tname'] = implode(',',$tnames);  


		$data = $detail;
		// print_r($data);
		// print_r($data); die;
		// $data = array_merge($data, );
		return $data;
	}

	public function getLecturer($id){

		$map['id']=$id;
		
		$lecturer=$this->where($map)->find();

		return $lecturer;


	}

	/***
	根据user_id获取讲师信息
	*/

	public function getInfo($user_id){

		$map["user_id"]=$user_id;

		$lecture=$this->where($map)->find();

		return $lecture;


	}

  /***
	根据外部讲师供应商信息
	*/
  public function getSupplier(){
      $m = M('supplier');
      $res = $m->field('sid,sname')->select(); 
      return $res;
  }

  /***
	获取擅长领域
	*/
  public function getTname(){
      $m = M('supplier_type');
      $res = $m->field('*')->select(); 
      return $res;
  }

  /***
	获取擅长领域name
	*/
  public function getTnamefield($id){
      $m = M('supplier_type');
      $tname = $m->where(array('id'=>$id))->getField('tname');
      return $tname;
  }


  /***
	根据所选擅长领域
	*/
  public function getThisTname($id){
      
      $list = $this->getTname();
	  $tname = M('lecturer')->where(array('id'=>$id))->getField('tname');
      $tname = json_decode($tname,true);  //array
      foreach($list as $k=>$v){
          if(in_array($v['id'],$tname)){
			  $list[$k]['ischecked'] = 1;
		  }
	  }

      return $list;
  }
	
}