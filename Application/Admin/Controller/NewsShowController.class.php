<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * @author Dujunqiang 20170331
 * 新闻资讯展示
 */
class NewsShowController extends AdminBaseController{
	/**
	 * 资讯首页
	 */
	public function index(){
		$user_id = $_SESSION["user"]["id"];
		if(!$user_id){
			echo "未获取到用户，请登录";
			exit;
		}
		
		$_GET['p'] += 0;
		if(!is_int($_GET['p']) || $_GET['p'] < 1){
			$_GET['p'] = 1;
		}
		
		$resp = D("NewsShow")->index($_GET);
		$this->assign('pageNav', $resp["pageNav"]);
		$this->assign('list', $resp["list"]);
		$this->display();
	}
	
	/**
	 * 资讯详情
	 */
	public function detail(){
		$_GET["new_id"] += 0;
		if(!is_int($_GET["new_id"]) || $_GET["new_id"] < 1){
			echo "未获取到资讯id";
			exit;
		}
		
		
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$new = M('news')
					->where("id=".$_GET["new_id"])
					->field("id,template,tissue_id,title,type,content,user_id,img,to_char(create_time,'YYYY-MM-DD HH24:MI:SS') as create_time")
					->limit(1)
					->select();
		}else{
			$new = M("news")->where("id=".$_GET["new_id"])->limit(1)->select();
		}
		if($new){
			$name = M('users')->field('username')->where("id=".$new[0]["user_id"])->limit(1)->select();
			$new[0]['username'] = $name[0]['username'];
		}
		$this->assign("new",$new[0]);
		$this->display();
	}
}