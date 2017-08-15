<?php
namespace Common\Model;

use Common\Model\BaseModel;
/**
 * ------------------------------------------------------------------------------
 * Description  供应商类别操作模型
 * @filename SupplierTypeModel.class.php
 * @author Andy
 * @datetime 2016-12-25 11:37:29
 * -------------------------------------------------------------------------------
 */
class SupplierTypeModel extends BaseModel {

    //定义表前缀表名，过滤表单字段
    protected $tablePrefix = 'think_';
    protected $tableName = 'supplier_type';
    protected $insertFields = array('id','tname');
    protected $pk = 'id';
    //定义验证规则
    protected $_validate = array(
        array('tname','require','供应商类别名称不能为空',1),
    ); 
    
    /*
     * 供应商类别列表
     */
    public function getSupplierStyle($total_page = 10){
        $start_page = I("get.p",0,'int');
        $keyword = I('get.keyword');
       if(!empty($keyword)){
           //供应商类别搜索
          
           $where['tname'] = array(
               'like','%'.$keyword.'%'
           );
           $result = M('SupplierType')->where($where)->page($start_page,$total_page)->select();
           $count = M('SupplierType')->where($where)->page($start_page,$total_page)->count();
           
       }else{
           $result = M('SupplierType')->order('id DESC')->page($start_page,$total_page)->select();
           $count = M('SupplierType')->count();
       }

        $show = $this->pageClass($count,$total_page);

	    $data = array(
	        "list" =>$result,
	        "page"=>$show,
	        'keyword' => $keyword,
	        
	    );
	    return $data;
    }
    
    
    /*
     * 编辑供应商
     */
     public function updateSupplierType(){
         if(IS_POST)
         {
             $data = array();
             $data = I("post.");
             $ret = M('SupplierType')->where(array('id'=>$data['tid']))->save($data);
             return $ret;
         }else{
             return 2;
         }
     }

}