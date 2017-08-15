<?php 

namespace Admin\Model;

use Common\Model\BaseModel;
/**
 * 系统设置-学分模型
 */
class CreditsModel extends BaseModel{	

   /**
     * 学分搜索统计列表
     */
   public function creditsList(){
    
     $p = I('p') ? I('p') : 1;
     $size = 15;

     //精确搜索
      $condition1 = I('user_id') ? I('user_id') : '';
    //   echo  $condition1;
      $condition2 = I('type') ? I('type') : '' ;
    //   echo  $condition2;
      $start_time = I('start_time') ? strtotime(I('start_time')) : '0';
    //   echo  $start_time;
      $end_time = I('end_time') ? strtotime(I('end_time')) : '2484790660';
    //    echo  $end_time;
      $condition3 = array("between","$start_time,$end_time");
    //   $condition['user_id'] = I('end_time');
      $where = array(
          'a.user_id'=>array("like","%".$condition1."%"),
          'a.type'=>array('like',"%".$condition2."%"),
          'a.add_time'=> $condition3,
          '_logic'=>'and'
      );
      

     $res = $this->alias(a)
          ->field('a.add_time,b.username,a.type,a.score,a.source,c.project_name')
          ->join('left join __USERS__ b on a.user_id = b.id')
          ->join('left join __ADMIN_PROJECT__ c on a.project_id = c.id')
        //   ->where('')
          ->where($where)
          ->page($p, $size)
          ->select();
     $count = $this->alias(a)
          ->field('a.add_time,b.username,a.type,a.score,a.source,c.project_name')
          ->join('left join __USERS__ b on a.user_id = b.id')
          ->join('left join __ADMIN_PROJECT__ c on a.project_id = c.id')
        //   ->where('')
          ->where($where)
          ->count();
     
     $page = $this->pageClass($count,$size);

    //  $user = $this->getRights(); //从组织结构获取搜索员工
    // 从学分表获取搜索员工
     $user = $this->alias(a)
          ->distinct(true)
          ->join('left join __USERS__ b on a.user_id = b.id')
          ->join('left join __ADMIN_PROJECT__ c on a.project_id = c.id')
        //   ->where('')
          ->getField('a.user_id,b.username',true);
    //  echo $user;
    //  print_r($user);
     $data = array(
         '0'=>$res,
         '1'=>$page,
         '2'=>$user
     );
    
     return $data;
   }





    /**
	 * 获取登陆者所在组织下一级或同级组织与学员（被选为内部讲师）的关联的数据
	 * 返回的是数组new
	 */
	public function getLecturers()
	{  
		// echo $_SESSION['user']['id'];
         //获取当前组织下的所有组织树形结构
        $data=D('AdminTissue')->treeInfo();

		//$tissues = M('tissue_rule')->field('id')->where(['pid'=>$tissueid])->select();

			//$data['rules']

        // print_r($data);
        //获取该当前组织下的所有学员
        $arr = $this->getRights();
		// var_dump($arr);
		// exit;
        // print_r($this->getTreeData()) ;
		//获取登录者当前所在组织id
		$tissue = M('tissue_group_access')->where(array('user_id'=>$_SESSION['user']['id']))->find();
        $tissueid =  $tissue['tissue_id'];
       //获取登录者当前所在组织的下一级组织id,array
	    $tissues = M('tissue_rule')->field('id')->where(array('pid'=>$tissueid))->select();
		// print_r($tissues);
		
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
	//   array_unshift($tissues,$tissueid);
	//   print_r($tissues);
	  foreach($tissues as $v){
			$v = $v['id']; //下一级组织id
			// echo $v.'<br/>';
       $user_all = array();

        //获取下一级组织权限
        $rows = M("tissue_rule")->field("rules")->where(array("id"=>$v))->find();
        // print_r($rows);
	//    echo $rows['rules'];

	  if(empty($rows['rules'])){
        $items = M("tissue_group_access")->field("user_id")->where(array('tissue_id'=>$v))->select();
	  }else{
		//   echo $rows['rules'] ; echo '<br/>';
		$rows['rules'] = $rows['rules'].','.$v;
		// echo $rows['rules'] ;echo '<br/>';
        //获取当前组织下所有允许访问的会员
         $tdata['tissue_id'] = array("in",$rows['rules']);
        // print_r($rows);
         $items = M("tissue_group_access")->field("user_id")->where($tdata)->select();
        //   print_r($items); exit;
	  }
       
        foreach($items as $item){
            $user_all[] = $item['user_id'];
        }

        $str_user[$v] = implode(",",$user_all);
    }

	  //登录者所在组织的人员
	    $user_us = M('tissue_group_access')->where(array('tissue_id'=>$tissueid))->field('user_id')->select(); 
		foreach($user_us as $k=>$v){
           $temp[] =  $v['user_id'];
		}
		
		$user_us = implode(',',$temp);
        $str_user[$tissueid] = $user_us;
		// print_r($user_us);
		ksort($str_user);



        // return $str_user;
        // print_r($str_user);exit;
		// return  $str_user;

		      $m = M('tissue_rule');
			  $u = M('users');
              foreach($str_user as $k=>$v){
				$v = explode(',',$v); //学员的数组
                $name = $m->field('name')->where(array('id'=>$k))->find();
                $tissuename = $name['name'];
				foreach($v as $vo){
                  $username = $u->where(array('id'=>$vo))->getField('username');
				//   $new[$tissuename]  = array($vo=>$username);
				 $new[$tissuename][$vo]  = $username;
				}				
				
			  }
			//   print_r($new);
			  return  $new;
	}







}