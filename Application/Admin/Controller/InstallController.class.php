<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;

/**
 * 安装初始化
 */
class InstallController extends AdminBaseController{
	public function setPage(){
		$DB_NAME = C('DB_NAME');
		$html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>初始化数据</title>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
		</head><body><h1>您将要初始化数据库：'.$DB_NAME.'，此操作不可逆，确定要操作吗？</h1>
		<form method="post" action="setData" id="setForm">
		<p>操作密码：<input style="height:25px; padding:0 10px; " type="password" name="authCode"/></p>
		<input style="height:40px; padding:0 30px; color:red; font-weight:700; font-size:16px; " type="submit" class="btn" value="初始化"/>
		</form>
		<script>
			$("#setForm").submit(function(){
			  	if(!confirm("确定要操作吗？")){
					return false;
				}
			});
		</script></body></html>';
		echo $html;
		session('Install', time());
	}
	/**
	初始化数据 sql文件在InitData
	需要初始化数据的表：
	think_admin_message_type 消息类型表
	think_admin_nav 菜单表
	think_audit_condition 审核条件表
	think_audit_rule 审核规则表
	think_auth_group 用户组表
	think_auth_group_access 用户组明细表
	think_auth_rule 权限规则表 
	think_integration_rule 积分规则表
	think_tissue_rule 组织架构【-----只要第一层级----】
	think_tissue_group_access 组织用户关联表【-----只要超级管理员信息----】
	think_users 用户表 【----只要超级管理员信息----】
	think_province_city_area 地区表
	 */
	public function setData(){
		$InstallStatus = session('Install');
		if(!$InstallStatus){
			$this->error('非法操作', U('Admin/install/setPage'), 1);
			exit;
		}
		if(I("post.authCode") != "452B-BF3F-13D8-DF0D"){
			$this->error('非法操作', U('Admin/install/setPage'), 1);
			exit;
		}
		
		//读取数据库配置文件
		$DB_TYPE = C('DB_TYPE');
		$DB_HOST = C('DB_HOST');
		$DB_NAME = C('DB_NAME');
		$DB_USER = C('DB_USER');
		$DB_PWD = C('DB_PWD');
		$DB_PORT = C('DB_PORT');
		$DB_PREFIX = C('DB_PREFIX');
		
		//连接数据库
		$conn = mysql_connect($DB_HOST.":".$DB_PORT, $DB_USER, $DB_PWD) or die("mysql error connecting");
		mysql_query("set names 'utf8'");
		mysql_select_db($DB_NAME);
		
		$tables = M()->db()->getTables();
		foreach ($tables as $thisTable){
			mysql_query("TRUNCATE table `$thisTable`");
		}
		echo "初始化数据....<br/>";
		
		//读取初始数据文件
		$wwwRoot = $_SERVER['DOCUMENT_ROOT'];
		$dir = $wwwRoot."/InitData/";
		$sqlFile = array();
		if(is_dir($dir)){
			if($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if(filetype($dir . $file) == "file"){
						$sqlFile[] = $dir.$file;
						echo "执行文件--》$file<br>";
					}
				}
				closedir($dh);
			}
		}
		
		if(!$sqlFile){
			echo "InitData文件夹下没找到初始数据sql文件";
			exit;
		}
		
		foreach ($sqlFile as $value){
			$sqlStr = file_get_contents($value);
			$sqlArray = explode(PHP_EOL, $sqlStr);
			foreach ($sqlArray as $thisSql){
				//只处理INSERT INTO语句
				$preStr = substr($thisSql , 0 , 11);
				$preStr = strtoupper($preStr);
				if($preStr != "INSERT INTO"){
					continue;
				}
				
				//替换前缀
				$thisSql = str_replace("think_", $DB_PREFIX, $thisSql);
				mysql_query($thisSql);
			}
		}
		
		//关闭MySQL连接
		mysql_close();
		
		echo "初始化数据完成<br/>";
		session('Install', null);
	}
}
