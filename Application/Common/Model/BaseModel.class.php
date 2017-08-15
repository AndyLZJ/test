<?php
namespace Common\Model;
use Think\Model;
/**
 * 基础model
 */
class BaseModel extends Model{
   

    /**
     * 获取当前组织架构数据权限ID
     */
    public function tissueId(){

        $data['user_id'] = array("eq",$_SESSION['user']['id']);

        $rows = M("tissue_group_access")->where($data)->find();

        return $rows['tissue_id'];
    }

    /**
     * 添加数据
     * @param  array $data  添加的数据
     * @return int          新增的数据id
     */
    public function addData($data){
        // 去除键值首尾的空格
        foreach ($data as $k => $v) {
            $data[$k]=trim($v);
        }
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('admin_nav');
		}
        $id=$this->add($data);
        return $id;
    }

    /**
     * 修改数据
     * @param   array   $map    where语句数组形式
     * @param   array   $data   数据
     * @return  boolean         操作是否成功
     */
    public function editData($map,$data){
        // 去除键值首位空格
        foreach ($data as $k => $v) {
            $data[$k]=trim($v);
        }
        $result=$this->where($map)->save($data);
        return $result;
    }

    /**
     * 删除数据
     * @param   array   $map    where语句数组形式
     * @return  boolean         操作是否成功
     */
    public function deleteData($map){
        if (empty($map)) {
            die('where为空的危险操作');
        }
        $result=$this->where($map)->delete();
        return $result;
    }

    /**
     * 数据排序
     * @param  array $data   数据源
     * @param  string $id    主键
     * @param  string $order 排序字段   
     * @return boolean       操作是否成功
     */
    public function orderData($data,$id='id',$order='order_number'){
        foreach ($data as $k => $v) {
            $v=empty($v) ? null : $v;
            $this->where(array($id=>$k))->save(array($order=>$v));
        }
        return true;
    }

    /**
     * 获取全部数据
     * @param  string $type  tree获取树形结构 level获取层级结构
     * @param  string $order 排序方式   
     * @return array         结构数据
     */

    public function getTreeData($type='tree',$order='',$name='name',$child='id',$parent='pid'){
        // 判断是否需要排序
        if(empty($order)){
            $data=$this->select();
        }else{
            $data=$this->order($order)->select();
        }
        // 获取树形或者结构数据
        if($type=='tree'){
            $data=\Org\Nx\Data::tree($data,$name,$child,$parent);
        }elseif($type="level"){
            $data=\Org\Nx\Data::channelLevel($data,0,'&nbsp;',$child);
        }
        return $data;
    }


      /**
     * 获取子孙全部数据
     * @param  string $$arr  传入的数组
     * @param  string $type  tree获取树形结构 level获取层级结构
     * @param  string $order 排序方式   
     * @return array         结构数据
     */

    public function getTreeDatas($arr,$type='tree',$order='',$name='name',$child='id',$parent='pid'){
      
        $data = $arr;
        // 获取树形或者结构数据
        if($type=='tree'){
            $data=\Org\Nx\Data::tree($data,$name,$child,$parent);
        }elseif($type="level"){
            $data=\Org\Nx\Data::channelLevel($data,0,'&nbsp;',$child);
        }
        return $data;
    }



    /**
     * 通过子孙查找家谱树
     * @param  string $arr   要查找的数组源
     * @param  string $id    数据的主键id，子孙id  
     * @param  string $name  要显示的字段，为空则全部显示   
     * @param  string $childName  主键id命名   
     * @param  string $parentName  pid命名 
     * @return array         结构数据,得到的家谱树
     *调用实例
           $area = array(  
            0=>array('cat_id'=>1,'cat_name'=>'北京市','parent_id'=>0),  
            1=>array('cat_id'=>2,'cat_name'=>'馆陶县','parent_id'=>5),  
            2=>array('cat_id'=>3,'cat_name'=>'海淀区','parent_id'=>1),  
            3=>array('cat_id'=>4,'cat_name'=>'朝阳区','parent_id'=>1),  
            4=>array('cat_id'=>5,'cat_name'=>'邯郸市','parent_id'=>6),  
            5=>array('cat_id'=>6,'cat_name'=>'河北省','parent_id'=>0),  
            );  
     $list= $this->gettree($area,2,'cat_name','cat_id','parent_id');
     **/
    public function gettree($arr,$id,$name,$childName,$parentName) {  
        static $list = array();  
        $list = array();
        static $num = 0;  
        foreach($arr as $v) {  
        //  echo ++$num .'<br />';  
            if($v[$childName] == $id) {  
                $num = 0;  
                $this->gettree($arr,$v[$parentName],$name,$childName,$parentName);  
               
                if(empty($name)){
                $list[] = $v;  
                }else{
                $list[] = $v[$name];  
                }                     
            }  
        }  
        return $list;  
    }  
  
  
   
                     
     /**
     * 获取子孙全部/或指定数据
     * @param  string $arr   传入的数组
     * @param  string $$parent_id = 0,则表示获取全部数据，id为5则表示id=5该条数据的子孙树
     * @param  string $lev=1 给初始层级定义 层级数 
     * @return array         结构数据
     */ 
     public function getOneTree($arr,$parent_id = 0,$lev=1){
        // 无限级分类中,查找子孙树  
        
        static $list = array(); 
         if($lev==1){  //重新调用方法时 将静态变量$list在内存中清空
            $list = array();  
         }
        static $num = 0; 
        
        foreach($arr as $v) {   
            if($v['pid'] == $parent_id) {  
                $num = 0;  
                $v['lev'] = $lev;  
                $list[] = $v;  
                $this->getOneTree($arr,$v['id'],$lev+1);  
            }  
        } 
        
        return $list;  
                   
      }
     
    /**
     * 获取分页数据
     * @param  subject  $model  model对象
     * @param  array    $map    where条件
     * @param  string   $order  排序规则
     * @param  integer  $limit  每页数量
     * @param  integer  $field  $field
     * @return array            分页数据
     */
    public function getPage($model,$map,$order='',$limit=10,$field=''){
        $count=$model
            ->where($map)
            ->count();
        $page=new_page($count,$limit);
        // 获取分页数据
        if (empty($field)) {
            $list=$model
                ->where($map)
                ->order($order)
                ->limit($page->firstRow.','.$page->listRows)
                ->select();         
        }else{
            $list=$model
                ->field($field)
                ->where($map)
                ->order($order)
                ->limit($page->firstRow.','.$page->listRows)
                ->select();         
        }
        $data=array(
            'data'=>$list,
            'page'=>$page->show()
            );
        return $data;
    }


    /**
     * 查询当前用户所在组织并近回 “当前用户所在组织和下一级组织” 允许访问的会员列表
     */
    function getRights(){

        $user_all = array();
        //var_dump($_SESSION['user']['id']);
        //exit;

        //获取当前会员组织权限
        $rows = M("tissue_group_access a")
        		->field("b.id,b.rules")
        		->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
        		->where("a.user_id=".$_SESSION['user']['id'])
        		->find();
        
        if(!empty($rows['rules'])){

            //获取当前组织下所有允许访问的会员
            $data['tissue_id'] = array("in",$rows['rules'].','.$rows['id']);

        }else{

            $data['tissue_id'] = array("in",$rows['id']);
            
        }

        $items = M("tissue_group_access")->field("user_id")->where($data)->select();

        foreach($items as $item){
            $user_all[] = $item['user_id'];
        }

        $str_user = implode(",",$user_all);
        

        return $str_user;

    }

    /**
     * 用户学分添加
     */
    public function creditAdd($typeid,$credit,$source_id,$project_id = 0){

        try {

            $DB = M('center_study');

            $DB->startTrans();

            $data = array(
                "create_time"=>date("Y-m-d H:i:s",time()),
                "typeid"=>$typeid,
                "credit"=>$credit,
                "source_id"=>$source_id,
                "project_id"=>$project_id,
                "user_id"=>$_SESSION['user']['id']
            );
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('center_study');
				$data['create_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
			}
			if(!$project_id){
				$data['project_id'] = NULL;
			}

            $DB->data($data)->add();

            $DB->commit();

        } catch ( Exception $e ) {

            $DB->rollback();

        }

    }

    /**
     *  分页公共方法
     */
    public function pageClass($count,$total_page){

        $Page = new \Think\Page($count,$total_page);

        $Page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

        $Page->setConfig('header',"");

        $Page->setConfig('prev','上一页');

        $Page->setConfig('next','下一页');

        $show = $Page->show();

        return $show;
    }


    /**
     *  数组分页公共方法
     *  author: lijin
     */
    public function  arrayPage($array,$size){
        
        $count = count($array);

        $Page = new \Think\Page($count,$size); //导入分页类

        $list=array_slice($array,$Page->firstRow,$Page->listRows);

        $Page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');

        $Page->setConfig('header',"");

        $Page->setConfig('prev','上一页');

        $Page->setConfig('next','下一页');

        $show= $Page->show();// 分页显示输出

        $pageData = array(
            'show'=>$show,
            'list'=>$list
        );
        
        return $pageData;
      
    }






}
