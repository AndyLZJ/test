<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class AdminNavModel extends BaseModel{

	/**
	 * 删除数据
	 * @param	array	$map	where语句数组形式
	 * @return	boolean			操作是否成功
	 */
	public function deleteData($map){
		$count=$this
			->where(array('pid'=>$map['id']))
			->count();
		if($count!=0){
			return false;
		}
		$this->where(array($map))->delete();
		return true;
	}

	/**
	 * 获取全部菜单
	 * @param  string $type tree获取树形结构 level获取层级结构
	 * @return array       	结构数据
	 */
	public function getTreeData($type='tree',$order=''){

		
		// 判断是否需要排序
		if(empty($order)){
			$data=$this->select();
		}else{
			$data=$this->order($order)->select();
		}
		// 获取树形或者结构数据
		if($type=='tree'){
			$data=\Org\Nx\Data::tree($data,'name','id','pid');
		}elseif($type="level"){
			$data=\Org\Nx\Data::channelLevel($data,0,'&nbsp;','id');
			// 显示有权限的菜单
			$auth=new \Think\Auth();

			foreach ($data as $k => $v) {

				if ($auth->check($v['mca'],$_SESSION['user']['id'])) {
					foreach ($v['_data'] as $m => $n) {
						if(!$auth->check($n['mca'],$_SESSION['user']['id'])){
							unset($data[$k]['_data'][$m]);
						}
					}
				}else{
					// 删除无权限的菜单
					unset($data[$k]);
				}
			}
		}

		  
		
		// p($data);die;
		return $data;
	}

	/**
	 *
	 * 获取菜单栏角色权限
	 */
	public function getGroups(){

		// 显示有权限的菜单
		$auth=new \Think\Auth();

		$groups = $auth->getGroups($_SESSION['user']['id']);

		return $groups;

	}

	/**
	 * 获取左侧菜单
	 */
	public function leftMenu(){

		// 分配菜单数据
		$nav_data=$this->getTreeData('level','order_number asc');

		foreach ($nav_data as $k => $v) {

			if ($this->checkMenu($v['mca'])) {

				foreach ($v['_data'] as $m => $n) {
					if (!$this->checkMenu($n['mca'])) {
						unset($nav_data[$k]['_data'][$m]);
					}
				}

			} else {
				// 删除无权限的菜单
				unset($nav_data[$k]);
			}
		}

		return $nav_data;

	}

	/**
	 *
	 * 检查左侧菜单权限
	 */
	public function checkMenu($mca){

		$groups_id = I("post.group_id");

		//获取用户角色权限
		$getGroups = $this->getGroups();

		foreach($getGroups as $group){
			$rules[$group['group_id']] = $group['rules'];
		}

		if(empty($groups_id)){
			$groups_id = $getGroups[0]['group_id'];

			if($groups_id == 3 && !empty($getGroups[1]['group_id'])){
				$groups_id = $getGroups[1]['group_id'];
			}

		}

		//获取当前切换面板菜单权限
		$data = array("id"=>array("in",$rules[$groups_id]));
		$check_list = M("auth_rule")->where($data)->select();

		foreach($check_list as $check){

			if(strcasecmp($check['name'],$mca) == 0){
				return true;
			}
		}

		return false;
	}

	/**
	 * 打包左侧菜单
	 */
	public function MenuHtml($messages = 0){

		//获取左侧菜单
		$leftMenu = $this->leftMenu();

		$html = "";

		foreach($leftMenu as $menu){

			$html .= "<li class=\"treeview\">";

			if(strcasecmp($menu['mca'],'admin/index/home') == 0 || strcasecmp($menu['mca'],'admin/test/index') == 0|| strcasecmp($menu['mca'],'admin/audit/auditlist') == 0|| strcasecmp($menu['mca'],'admin/news/page') == 0 || strcasecmp($menu['mca'],'home/index/invoice') == 0){

				$html .= "<a href=\"javascript:void(0)\" onclick=\"onclick_url('".U($menu['mca'])."')\">";

			}else{
				$html .= "<a href=\"".U($menu['mca'])."\" >";
			}

			$html .= "<i class=\"fa fa-".$menu['ico']."\"></i>";
			$html .= "<span>".$menu['name']."</span>";
			$html .= "<span class=\"pull-right-container\">";
			if(strcasecmp($menu['mca'],'Admin/Message/messageList') == 0){
				$html .= "<small class=\"label pull-right bg-red\">".$messages."</small>";
			}else{
				$html .= "<i class=\"fa fa-angle-left pull-right\"></i>";
			}
			$html .= "</span>";
			$html .= "</a>";
			$html .= "<ul class=\"treeview-menu menu-tindex\">";

			if(!empty($menu['_data'])){
				foreach($menu['_data'] as $data){
					//$html .= "<li><a href=\"".U($data['mca'])."\" target=\"Main\"><i class=\"fa fa-".$data['ico']."\"></i>".$data['name']."</a></li>";
					$html .= "<li><a href=\"javascript:void(0)\" onclick=\"onclick_url('".U($data['mca'])."',this)\"><i class=\"fa fa-".$data['ico']."\"></i>".$data['name']."</a></li>";
				}
			}

			$html .= "</ul>";
			$html .= "</li>";

		}

		return $html;
		

	}

}
