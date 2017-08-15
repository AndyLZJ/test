<?php 

namespace Admin\Model;
/**
 * 审核管理模型
 */
use Common\Model\BaseModel;
/**
 * 审核管理模型
 */
class BannerModel extends BaseModel{
   protected $tableName= 'company_banner';
    /** 
     *banner的上传  
     */
     public function upload($filepath)
     {
        //公司banner图
       $img = $this->getDetail();
       if($img){
           //修改数据
           $banner_img = array(
               'banner_img'=>$filepath
           );
           $res = M('Company_banner')->where(array('company_name'=>'深圳典阅'))->save($banner_img);
          
       }else{
           //增加数据
           $banner_img = array(
               'company_name'=>'深圳典阅',
               'banner_img'=>$filepath
           );
		   	if(strtolower(C('DB_TYPE')) == 'oracle'){
				$banner_img['id'] = getNextId('company_banner');
			}
           $res = M('Company_banner')->add($banner_img);
       } 
          return $res;
     }


    /** 
     *banner的已上传图片获取  
     */
     public function getDetail()
     {
       //公司banner图
       $img = M('Company_banner')->field('banner_img')->find();
       
       return $img;
       
     }



    //-------------------------------------3.2期----------------------------------------//


    /** 
     *banner的列表 
     */
     public function bannerList()
     {
        $lists = M('Company_banner')->order('banner_img_site asc')->select();  
        // print_r($lists);
        foreach($lists as $k=>$v){
          if($v['banner_img_site'] == 1){
           $lists[$k]['siteName'] = '位置1';
          }else if($v['banner_img_site'] == 2){
           $lists[$k]['siteName'] = '位置2';
          }else if($v['banner_img_site'] == 3){
           $lists[$k]['siteName'] = '位置3';
          }else if($v['banner_img_site'] == 4){
           $lists[$k]['siteName'] = '位置4';
          }else if($v['banner_img_site'] == 5){
           $lists[$k]['siteName'] = '位置5';
          }
          
        }
        //  print_r($lists);
        return $lists;
     }

    /** 
     *banner的增加/编辑，上限5条 
     */
     public function add()
     {
        $data = I('post.');
        
        $title = I('post.title');
        $banner_img = I('post.banner_img');
        $banner_img_site = I('post.banner_img_site');
        $type = I('post.type');
        $start_date = I('post.start_date');
        $end_date = I('post.end_date');
        $correlate_item = I('post.correlate_item');
        $id = I('post.id');
        
        //判断图片位置是否给占用
        $where = array('banner_img_site'=>$banner_img_site);
        if($id){
        	$where['id'] = array('neq',$id);
		}
		
    	$exist = M('company_banner')->where($where)->find();
        if($exist){
            $this->error = '当前位置已被占用，请重新选择';
            return false;
        }
       
        $ajaxdata = array(
           'title'=>$title,
           'banner_img'=>$banner_img,
           'banner_img_site'=>$banner_img_site,
           'type'=>$type,
           'start_date'=>$start_date,
           'end_date'=>$end_date,
           'correlate_item'=>$correlate_item
        );
        
        //判断图片是否达到上限
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$ajaxdata['id'] = getNextId('company_banner');
			$ajaxdata['start_date'] = array('exp',"to_date('".$start_date."','yy-mm-dd')");
			$ajaxdata['end_date'] = array('exp',"to_date('".$end_date."','yy-mm-dd')");
		}
        
        if(empty($id)){
           //判断图片总数最大为5张
	        $count = M('company_banner')->count();
	        if($count >= 5){
	            $this->error = 'Banner图总数最大不超过5张';
	            return false;
	        }
			
	        $res = M('Company_banner')->add($ajaxdata);
	        if(!$res){
	            $this->error = '新增失败';
	            return false;
	        }
        }else{
	        $res = M('Company_banner')->where(array('id'=>$id))->save($ajaxdata);
	        if(!$res){
	            $this->error = '编辑失败';
	            return false;
	        }
        }



     }

    /** 
     *banner条数的删除
     */
     public function del($id)
     {
       $banner_img_site = M('company_banner')->where(array('id'=>$id))->getField('banner_img_site');

       $res = M('Company_banner')->where(array('id'=>$id))->delete(); 
        // echo M('')->getLastSql(); exit;
       $res = $this->orderAgain();
       return $res;
     }


    /** 
     *banner上移/下移/删除后的重新排序
     */
     public function orderAgain()
     {
       $lists = M('Company_banner')->order('banner_img_site asc')->select(); 
       foreach($lists as $k=>$v){
           $v['banner_img_site'] = $k+1;
           $res = M('Company_banner')->where(array('id'=>$v['id']))->save($v);
       }
       return $res;
     }
    /** 
     *banner的详情
     */
     public function bannerDetail($id)
     {
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data = M('Company_banner')
					->where(array('id'=>$id))
					->field("id,company_name,banner_img,title,banner_img_site,speed,type,correlate_item,to_char(start_date,'YYYY-MM-DD') as start_date,to_char(end_date,'YYYY-MM-DD') as end_date")
					->find();
		}else{
			$data = M('Company_banner')->where(array('id'=>$id))->find();
		}
        $itemName = $this->getItemName($data['type'],$data['correlate_item']);
        // echo $itemName;
        $data['itemName'] = $itemName;
        return $data;
     }

    /** 
     *banner轮播速度设置 
     */
     public function speedSet()
     {   
         $speed  = I('post.speed');
         $data = array('speed'=>$speed);
         $res = M('Company_banner')->where(1)->save($data); 
         if(!$res){
            $this->error = '设置失败';
            // $this->error = $speed;
            return false;
        } 
     }

    /** 
     *banner的排序
     */
     public function orderNumber()
     {
       $id = I('get.id'); 
       $order = I('get.order'); 
       $res = $this->orderAgain();
       if($res === false){
     
         $this->error = "系统错误";
         return false;
       
       }
       $dataone = M('company_banner')->where(array('id'=>$id))->find();
       if($order == 1){
          $banner_img_site = $dataone['banner_img_site'] + 1;
          $nextid = M('company_banner')->where(array('banner_img_site'=>$banner_img_site))->getField('id');
          $res = M('company_banner')->where(array('id'=>$id))->save(array('banner_img_site'=>$banner_img_site));
          $res = M('company_banner')->where(array('id'=>$nextid))->save(array('banner_img_site'=>$dataone['banner_img_site']));
       }else if($order == -1){
          $banner_img_site = $dataone['banner_img_site'] - 1;
          $nextid = M('company_banner')->where(array('banner_img_site'=>$banner_img_site))->getField('id');
          $res = M('company_banner')->where(array('id'=>$id))->save(array('banner_img_site'=>$banner_img_site));
          $res = M('company_banner')->where(array('id'=>$nextid))->save(array('banner_img_site'=>$dataone['banner_img_site']));
       }

       return $res;
     }

    /** 
     *banner详情页的资讯/课程/调研管理列表获取 
     */
     public function getItem($type)
     {

        $size = 15;
        
        $p = I('p') ? I('p') : 1 ;

       //获取搜索条件
        $condition = I('get.table_search');
        $condition = trim($condition);
        $condition = $condition ? $condition : '';
        $map1 = array('title'=>array('like','%'.$condition.'%'));
        $map2 = array('course_name'=>array('like','%'.$condition.'%'));
        $map3 = array('research_name'=>array('like','%'.$condition.'%'));
        
        
        if($type == 1){
         $list = M('news')->page($p.','.$size)->field('id,title')->where($map1)->select(); 
         $count = M('news')->field('id,title')->where($map1)->count(); 
        }else if($type == 2){
         $list = M('course')->page($p.','.$size)->field('id,course_name as title')->where($map2)->where(array('status'=>1))->select();
         $count = M('course')->field('id,course_name as title')->where($map2)->where(array('status'=>1))->count();  
        }else if($type == 3){
         $list = M('research')->page($p.','.$size)->field('id,research_name as title')->where($map3)->where(array('audit_state'=>1))->select(); 
         $count = M('research')->field('id,research_name as title')->where($map3)->where(array('audit_state'=>1))->count(); 
        }

        $show = $this->pageClass($count,$size);
        $data = array(
          'show'=>$show,
          'list'=>$list
        );
        return $data;
     }

    /** 
     *banner详情页的资讯/课程/调研管理的指定名称获取
     */
     public function getItemName($type,$correlate_item)
     {

   
        $map = array('id'=>$correlate_item);
        

        if($type == 1){
         $data = M('news')->field('id,title as itemname')->where($map)->find(); 
        
        }else if($type == 2){
         $data = M('course')->field('id,course_name as itemname')->where($map)->where(array('status'=>1))->find();
        
        }else if($type == 3){
         $data = M('research')->field('id,research_name as itemname')->where($map)->where(array('audit_state'=>1))->find(); 
        
        }

      
        return $data['itemname'];
     }






}