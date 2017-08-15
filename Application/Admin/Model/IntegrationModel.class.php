<?php 

namespace Admin\Model;

use Common\Model\BaseModel;

   /**
     福利管理模块
    **/ 

    /**
    * 积分管理模型
    */
class IntegrationModel extends BaseModel{
    //新增福利的自动验证
    // protected $trueTableName= 'think_welfare';	
    protected $tableName = 'welfare';
    public $insertFields = 'name,welfare_covers,need_score,is_public';  //自动过滤

    public $_auto = array(    //自动填充
        // array(完成字段1,完成规则,[完成条件,附加规则]),
        array('add_time','time',3,'function'),
        array('update_time','time',3,'function'),
     
    );
    public $_validate = array(  // $_validate 关键字是系统规定的 ，自动验证
        // array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('name', 'require', '福利名称不能为空'),
        array('name','2,12','福利名称长度应在2-12个字符','1','length','3'),  //验证规则是length2-12长度 //1是验证条件，必须验证 //3代表新增或编辑时全都验证     
        array('welfare_covers', 'require', '福利图片不能为空'),
        array('need_score', 'require', '积分不能为空'),
        array('need_score','1,9','积分长度应在1-9位数字','1','length','3'), 
        array('need_score', 'number', '所需积分只能是数字！', 1, '', 3),  
        array('need_score','pr','所需积分不能为负数','1','callback','3'),  // 自定义函数验证密码格式  
                                                         
    );
      
      public function pr(){
        $need_score = I('need_score');
        if($need_score < 0){
         return false;
        }
      }


    public function addWelfare(){
        $data['name'] = I('post.name');
        $data['welfare_covers'] = I('post.welfare_covers');
        $data['welfare_describe'] = I('post.welfare_describe');
        $data['add_time'] = time();
        $data['update_time'] = time();
        $data['need_score'] = I('post.need_score');
        $data['auth_user_id'] = $_SESSION['user']['id'];
        $data['is_public'] = I('post.is_public')?'1': '0';

        if(empty($data['name'])){
            $this->error = "福利名称不能为空";
            $res = false;
            return $res;
        }
        if(utf8_strlen($data['name'])<2 || utf8_strlen($data['name'])>12){
            $this->error = "福利名称长度应在2-12个字符";
            $res = false;
            return $res;
        }
        
        if(empty($data['welfare_covers'])){
            $this->error = "福利图片不能为空";
            return $res;
        }
        if(empty($data['welfare_describe'])){
            $this->error = "福利简介不能为空";
            return $res;
        }
        if(empty($data['need_score'])){
            $this->error = "积分不能为空";
            return $res;
        }
        if(!is_numeric($data['need_score'])){
            $this->error = "所需积分只能是数字！";
            return $res;
        }
        if($data['need_score'] < 0){
            $this->error = "所需积分不能为负数";
            return $res;
        }
        if(strlen($data['need_score']) > 9){
            $this->error = "积分长度应在1-9位数字";
            return $res;
        }
        
        //图片裁剪操作
        // $this->ImageCropper($data['welfare_covers']);
		
        $res = $this->add($data);
        return  $res;
    }  

    /**
     * 图片裁剪操作
     */
     public function ImageCropper($image)
	 {
        $img_base = substr($image,1);
        $image = new \Think\Image();
        $image->open($img_base);

        if($image->width() < 270){
            $image->thumb(220,125,\Think\Image::IMAGE_THUMB_FILLED)->save($img_base);
        }else{
            $image->thumb(220,125,\Think\Image::IMAGE_THUMB_FIXED)->save($img_base);
        }

     }


	/**
     * 福利管理列表
     */
     public function welfareList()
	 {
      $condition = I('table_search');
      $map = array(
          'is_delete'=>0,
          'name'=>array("like","%".$condition."%")
      );
      $size = 15;
      $p1 = $_GET['p1'] ?  $_GET['p1'] : 1 ;

      //隔离数据过滤
      $specifiedUser = D('IsolationData')->centerSeparation();
      $map['auth_user_id'] = array("in",$specifiedUser);

      $list = M('welfare')->order('id desc')->where($map)->page($p1.','.$size)->select();
      $count1 = M('welfare')->order('id desc')->where($map)->count();
      $i=1; //设置url的tabType=1

      //隔离数据过滤
      $list = D('IsolationData')->isolationData($list);

      $show1 = tabPage($count1,$size,'p1',$i);
      
      $data = array(
         'list'=>$list,
         'page1'=>$show1
      );
      return $data;
     }
    
    /**
     * 福利管理的编辑
     */ 
     public function editWelfaredata()
	 {
      $formdata = I('post.');
      $formdata['update_time'] = time();
      $formdata['is_public'] = I('post.is_public') ? 1 : 0;


      if(empty($formdata['name'])){
            $this->error = "福利名称不能为空";
            $res = false;
            return $res;
        }
        if(utf8_strlen($formdata['name'])<2 || utf8_strlen($formdata['name'])>12){
            $this->error = "福利名称长度应在2-12个字符";
            $res = false;
            return $res;
        }
        
        if(empty($formdata['welfare_covers'])){
            $this->error = "福利图片不能为空";
            return $res;
        }
        if(empty($formdata['welfare_describe'])){
            $this->error = "福利简介不能为空";
            return $res;
        }
        if(empty($formdata['need_score'])){
            $this->error = "积分不能为空";
            return $res;
        }
        if(!is_numeric($formdata['need_score'])){
            $this->error = "所需积分只能是数字！";
            return $res;
        }
        if($formdata['need_score'] < 0){
            $this->error = "所需积分不能为负数";
            return $res;
        }
        if(strlen($formdata['need_score']) > 9){
            $this->error = "积分长度应在1-9位数字";
            return $res;
        }


        //图片裁剪操作
        // $this->ImageCropper($formdata['welfare_covers']);
        $res = M('welfare')->save($formdata);
        return $res;
    //   if($res){
    //       return true;
    //   }else{
    //       return false;
    //   }
     }
    /**
     * 福利数据的编辑的详情展示
     */ 
     public function editWelfare()
	 {
      $id = I('id');
      $data = M('welfare')->where(array('id'=>$id))->find();
      return $data;
     }

   
    /**
     * 福利兑换历史
     */ 
     public function welfareChangehistory()
	 {
      $size = 15;
      $p2 = $_GET['p2'] ?  $_GET['p2'] : 1 ;
      $list2 = M('welfare_record')->alias('a')
                                 ->field('a.*,b.username,d.id as did')
                                 ->join('left join __USERS__ b on b.id = a.user_id')
                                 ->join('left join __TISSUE_GROUP_ACCESS__ c on c.user_id = a.user_id')
                                 ->join('left join __TISSUE_RULE__ d on d.id = c.tissue_id')
                                 ->order("a.id desc")
                                 ->page($p2.','.$size)
                                 ->select();
      $count2 = M('welfare_record')->alias('a')
                                 ->field('a.*,b.username')
                                 ->join('left join __USERS__ b on b.id = a.user_id')
                                 ->count();
     
      //查找福利兑换人所在的部门
      foreach($list2 as $k=>$v){
        $child = $v['did'];
        $data = M('tissue_rule')->select();

        $arr = $this->gettree($data,$child,'name','id','pid'); //查找用户所在部门的家谱树
        $list2[$k]['department'] = implode('/',$arr);
        
      }                             
      $i=2; //设置url的tabType=2
      $show2 = tabPage($count2,$size,'p2',$i);
      
      $data = array(
         'list2'=>$list2,
         'page2'=>$show2
      );
      return $data;
     }



    /**
     积分管理模块
    **/ 

	/**
     * 积分规则列表
     */
     public function integrationlist()
	 {
      $list1 = M('integration_rule')->field('id,name,score,oneday_score,type')->select();
      foreach($list1 as $k=>$v){
            if(strpos($v['oneday_score'],'/') === false){     //使用绝对等于
                     //不包含                   
                 }else{
                     //包含
                  $arr = explode('/',$v['oneday_score']) ; 
                  $list1[$k]['oneday_score'] = intval($arr[0])/intval($arr[1])*30;
                 }
         }

      return $list1;
     }
	/**
     * 积分规则列表的编辑
     */
     public function editIntegrationList()
	 {
      $id = I('post.id');
      $score = I('post.score');
      $oneday_score = I('post.oneday_score');
      $tempattr = I('post.tempattr');
     
      if($id == 3 || $id == 4){
          $oneday_score = $oneday_score.'/30';
      }
      if($tempattr == 'score'){
        $map = array(
         'id'=>$id,
         'score'=>$score,
        );
      }else{
        $map = array(
         'id'=>$id,
         'oneday_score'=>$oneday_score     
        );        
      }

      $res = M('integration_rule')->save($map);
      return $res;
     }

	/**
     * 积分流水列表
     */
     public function integrationHistoryList()
	 {
      $size = 15;
      $p2 = $_GET['p2'] ?  $_GET['p2'] : 1 ;
      $list2 = M('integration_record')->alias('a')
                                      ->field('a.*,b.username,d.id as did ')
                                      ->join('left join __USERS__ b on b.id = a.user_id')
                                      ->join('left join __TISSUE_GROUP_ACCESS__ c on c.user_id = a.user_id')
                                      ->join('left join __TISSUE_RULE__ d on d.id = c.tissue_id')
                                      ->order('id desc') 
                                      ->page($p2.','.$size)
                                      ->select();
      $count2 = M('integration_record')->alias('a')
                                      ->join('left join __USERS__ b on b.id = a.user_id')
                                      ->join('left join __TISSUE_GROUP_ACCESS__ c on c.user_id = a.user_id')
                                      ->join('left join __TISSUE_RULE__ d on d.id = c.tissue_id') 
                                      ->count();

      foreach($list2 as $k=>$v){
        $child = $v['did'];
        $data = M('tissue_rule')->select();

        $arr = $this->gettree($data,$child,'name','id','pid'); //查找用户所在部门的家谱树
        $list2[$k]['department'] = implode('/',$arr);
        
      }  
    //   print_r($list2);

      $i=2; //设置url的tabType=2
      $show2 = tabPage($count2,$size,'p2',$i);
      
      $data = array(
         'list2'=>$list2,
         'page2'=>$show2
      );
      return $data;
     }

    /**
     我的积分模块
    **/ 

	/**
     * 我的积分列表展示
     */
     public function myintegration()
	 {
      //我的积分模块的列表页显示：总积分，可用积分，本月积分
        
        $where1 = array(
          'user_id'=>$_SESSION['user']['id'],
          'score'=>array('gt',0),
          '_logic'=>'and'
        );
        $where2 = array(
          'user_id'=>$_SESSION['user']['id'],
        );
        $where3 = array(
          'user_id'=>$_SESSION['user']['id'],
          '_logic'=>'and'
        );



        if(IS_AJAX){
         $year = I('post.year')=='' ? date("Y") : I('post.year');
         $months = I('post.months')=='' ? date("m") : I('post.months');;
         $months = $year.$months;
        //  $months = str_replace('-','',$months);
         }else{
         $year = date("Y");
         $months = date("Ym");
         }


      //年度总积分  //   $all_score = M('integration_record')->where($where1)->sum('score');
      $all_score_arr = M('integration_record')->field('user_id,score,time')->where($where1)->select();
      foreach($all_score_arr as $k=>$v){
			$t = date('Y',$v['time']);
			if(strpos($t,$year) !== false){
				$all_score += $v['score'];
			}
		}
      $all_score = $all_score<0 || $all_score =='' ? 0 : $all_score;


    
      /*
      //  $available_score = M('integration_record')->where($where2)->sum('score');
      $available_score_arr = M('integration_record')->field('user_id,score,time')->where($where2)->select();
      $available_score = $available_score =='' ? 0 : $available_score;

      $this_month_score = M('integration_record')
      					->field("user_id,sum(score) as sumscore,FROM_UNIXTIME(time,'%Y%m') months")
      					->where($where3)
      					->group('months')
      					->having('months='.$months)
      					->select();
		
      $this_month_score = $this_month_score[0]['sumscore'];*/
     

      $res = M('integration_record')->field('user_id,score,time')->where($where3)->select();
      // 年度可用积分
        foreach($res as $k=>$v){
			$t = date('Y',$v['time']);
			if(strpos($t,$year) !== false){
				$available_score += $v['score'];
			}
		}
      $available_score = $available_score =='' ? 0 : $available_score;

      //本月可用积分
		foreach($res as $k=>$v){
			$t = date('Ym',$v['time']);
			if(strpos($t,$months) !== false){
				$this_month_score += $v['score'];
			}
		}
    //   $this_month_score = $this_month_score<0 || $this_month_score =='' ? 0 : $this_month_score;
      $this_month_score =  $this_month_score =='' ? 0 : $this_month_score;
       

       //年份下拉框的年份展示
       $startYear = 2017;
       $endYear = 2117;
       $yeardata = array();
       for($i=$startYear;$i<$endYear;$i++){
             $yeardata[] = $i;
       }
    //   dump($yeardata);

    //隔离数据过滤
      $specifiedUser = D('IsolationData')->centerSeparation();
      $map['auth_user_id'] = array("in",$specifiedUser);    
       //福利社的展示
       $list = M('welfare')->where(array('is_delete'=>0,'is_public'=>1))->where($map)->select();
       //隔离数据过滤
       $list = D('IsolationData')->isolationData($list);

       $data = array(
          'yeardata'=>$yeardata,
          'all_score'=>$all_score,
          'available_score'=>$available_score,
          'this_month_score'=>$this_month_score,
          'list'=>$list
       );
       return $data;
     }


	/**
     * 积分兑换
     */
     public function integrationExchange()
	 { 
        $name = I('post.name');
        $need_score = I('post.need_score');
        $available_score = M('integration_record')->where(array('user_id'=>$_SESSION['user']['id']))->sum('score');
       if($available_score >= $need_score){
        
        // 自动启动事务支持
        $this->startTrans();
        try {
           //把相应的兑换记录插入积分记录表
         $arr = array(
               'time'=>time(),
               'user_id'=>$_SESSION['user']['id'],
               'score'=>$need_score*(-1),
               'type'=>'积分兑换',
               'describe'=>'积分兑换-'.$name,
           ) ;
		   	if(strtolower(C('DB_TYPE')) == 'oracle'){
				$arr['id'] = getNextId('integration_record');
			}
          $ret = M('integration_record')->add($arr);
                if (false === $ret) {
                    // 发生错误自动回滚事务
                    $this->rollback();
                    $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
                    return $res;
                }
          //把相应的兑换记录插入福利记录表
           $map = array(
               'name'=>$name,
               'user_id'=>$_SESSION['user']['id'],
               'need_score'=>$need_score,
               'time'=>time()              
           ) ;
		   
		   	if(strtolower(C('DB_TYPE')) == 'oracle'){
				$map['id'] = getNextId('welfare_record');
			}
           $ret = M('welfare_record')->add($map);  
           if (false === $ret) {
                    // 发生错误自动回滚事务
                    $this->rollback();
                    $res = array(
                     'status'=>0,
                     'info'=>'系统发生错误！'
                      );
                    return $res;
                }
            // 提交事务
            $this->commit();
            $res = array(
                     'status'=>1,
                     'info'=>'兑换成功！'
                      );
             return $res;
        } catch (ThinkException $e) {
            $this->rollback();
        }
        
       
       }else{
        //    $res = '可用积分不足！';
           $res = array(
              'status'=>0,
              'info'=>'可用积分不足！'
          );
           return $res;
       }

     }



     /**
     * 申请加分
     */
     public function myintegrationApply()
	 {
        $postData = I('post.');
        $apply_title = I('post.apply_title');
        $apply_description = I('post.apply_description');
        $attachment = I('post.attachment');
        $add_score = I('post.add_score');
        $add_score += 0;
        
        if($apply_title == ''){
            $this->error = '申请标题不能为空！';
            return false;
        }else if(mb_strlen($apply_title,'utf-8') > 40){
            $this->error = '申请标题长度不能大于40字符！';
            return false;
        }else if($apply_description == ''){
            $this->error = '申请说明不能为空！';
            return false;
        }else if($attachment == ''){
            // $this->error = '请先上传附件！';
            // return false;
        }else if($add_score == ''){
            $this->error = '加分分值不能为空！';
            return false;
        }else if($add_score <= 0){
            $this->error = '加分分值必须大于0！';
            return false;
        }else if($add_score >= 999){
        	$this->error = '加分分值必须小于999！';
        	return false;
        }
        $orderno = D('Trigger')->orderNumber(8);

        $where = array(
            'user_id'=>$_SESSION['user']['id'],
            'apply_title'=>$apply_title,
            'apply_description'=>$apply_description,
            'attachment'=>$attachment,
            'add_score'=>$add_score,
            'add_time'=>time(),
            'orderno'=>$orderno
        );
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$where['id'] = getNextId('integration_apply');
		}
		
        $res = M('integration_apply')->add($where);
        if($res){
            return true;
        }else{
            $this->error = '系统错误！';
            return false;
        }

     }

  	/**
     * 申请加分记录
     */
     public function myintegrationApplyhistory()
	 {

       $size = 15;
       $p2 = I('p2') ?  I('p2') : 1 ;
       $list2 = M('integration_apply')
       			->where(array('user_id'=>$_SESSION['user']['id']))
       			->page($p2.','.$size)
       			->order('add_time desc')
       			->select();
       //2期审核标题直接显示 加分申请 
       foreach($list2 as $k=>$v){
               
           $list2[$k]['apply_description'] = msubstr(strip_tags($v['apply_description']),0,32);
       }
       $count2 = M('integration_apply')->count();
       $i=2; //设置url的tabType=2
       $show2 = tabPage($count2,$size,'p2',$i);
       
       $data = array(
         'list2'=>$list2,
         'page2'=>$show2
      );
      return $data;
     }


  	/**
     * 我的积分列表页的积分流水——全部,获取，使用列表
     */
     public function myintegrationwater()
	 {
       $user_id = $_SESSION['user']['id'] ? $_SESSION['user']['id'] : '';
       $size = 15;
       
       $p1 = I('p1') ?  I('p1') : 1 ;
      //“全部”积分列表
       $all_start_time = I('all_start_time') ? strtotime(I('all_start_time')) : '0';
       $all_end_time = I('all_end_time') ? strtotime(I('all_end_time')) : '2484790660';
       $where1 = array(
          'user_id'=>$user_id,
          'time'=> array("between","$all_start_time,$all_end_time"),
          '_logic'=>'and'
        );
       $list1 = M('integration_record')->where($where1)->page($p1.','.$size)->order('id desc')->select();

       $count1 = M('integration_record')->where($where1)->count();
       $i=1; //设置url的tabType=2
       $show1 = tabPage($count1,$size,'p1',$i);

       //“获取”积分列表
       $p2 = I('p2') ?  I('p2') : 1 ;
       $get_start_time = I('get_start_time') ? strtotime(I('get_start_time')) : '0';
       $get_end_time = I('get_end_time') ? strtotime(I('get_end_time')) : '2484790660';
       $where2 = array(
          'user_id'=>$user_id,
          'time'=> array("between","$get_start_time,$get_end_time"),
          'score'=>array('gt',0),
          '_logic'=>'and'
        );
      
       $list2 = M('integration_record')->where($where2)->page($p2.','.$size)->order('id desc')->select();

       $count2 = M('integration_record')->where($where2)->count();
       $i=2; //设置url的tabType=2
       $show2 = tabPage($count2,$size,'p2',$i);
       
       //“使用”积分列表
       $p3 = I('p3') ?  I('p3') : 1 ;
       $use_start_time = I('use_start_time') ? strtotime(I('use_start_time')) : '0';
       $use_end_time = I('use_end_time') ? strtotime(I('use_end_time')) : '2484790660';
       $where3 = array(
          'user_id'=>$user_id,
          'time'=> array("between","$use_start_time,$use_end_time"),
          'score'=>array('lt',0),
          '_logic'=>'and'
        );
       
       $list3 = M('integration_record')->where($where3)->page($p3.','.$size)->order('id desc')->select();

       $count3 = M('integration_record')->where($where3)->count();
       $i=3; //设置url的tabType=2
       $show3 = tabPage($count3,$size,'p3',$i);
       
       $data = array(
        //  'all_start_time'=>$all_start_time,
        //  'all_end_time'=>$all_end_time,
        //  'get_start_time'=>$get_start_time,
        //  'get_end_time'=>$get_end_time,
        //  'use_start_time'=>$use_start_time,
        //  'use_end_time'=>$use_end_time,
         'list1'=>$list1,
         'page1'=>$show1,
         'list2'=>$list2,
         'page2'=>$show2,
         'list3'=>$list3,
         'page3'=>$show3
      );
      return $data;
         
     }






}