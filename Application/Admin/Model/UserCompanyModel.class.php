<?php 
namespace Admin\Model;

use Think\Model;

class UserCompanyModel extends Model{	
	
	/*获取用户对应的公司*/

	public function getCompany($user_id){

		$map['user_id']=$user_id;

		$data=$this->where($map)->find();

		$arr["company_id"]=$data['company_id'];

		$user_ids=$this->where($arr)->select();

		$Users=M("Users");

		$users=array();

		for($i=0;$i<count($user_ids);$i++){

			$maps['id']=$user_ids[$i]['user_id'];

			$user=$Users->where($maps)->find();

			$users[$i]['id']=$user["id"];

			$users[$i]['username']=$user["username"];

			
		}

		return $users;

		
	}


}