<?php 
namespace Admin\Model;
use Common\Model\BaseModel;
/**
 * 资讯详情
 * @author Dujuqiang 20170406
 */
class NewsShowModel extends BaseModel{
	//初始化
	public function __construct(){}
	
    /**
     * 获取图表数据及数据列
     * page 当前页码，不传默认为1
     * pageLen 每页数据条数，不传默认15
     */
	public function index($param){
		$user_id = $_SESSION["user"]["id"];
		$size = 15;
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$news = M('news')
					->page($param['p'], $size)
					->field("id,template,tissue_id,title,type,content,user_id,img,to_char(create_time,'YYYY-MM-DD HH24:MI:SS') as create_time")
					->order('id desc')
					->select();
		}else{
			$news = M('news')->page($param['p'], $size)->order('id desc')->select();
		}
		//关联用户表获取用户名
		foreach ($news as $k => $v) {
			$name = M('users')->field('username')->find($v['user_id']);
			$news[$k]['username'] = $name['username'];
		}
		$count = M('news')->field("count(id) as num")->select();
		$count = $count[0]["num"];
		$pageNav = $this->pageClass($count, $size);
		return array("pageNav"=>$pageNav, "list"=>$news);
	}
}