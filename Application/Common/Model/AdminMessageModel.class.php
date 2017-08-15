<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class AdminMessageModel extends BaseModel{

	/*或者未发送的消息通知数目*/

	public function countMessage($user_id=null){

		$map=array();
		$map['user_id']= array('eq',$user_id);
		$map['is_delete']=array('eq',0);
		$map["status"]=array('eq',0);

		$messages = M('AdminMessage')->where($map)->count();

		return $messages;
	}


}
