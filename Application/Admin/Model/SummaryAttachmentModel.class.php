<?php 
namespace Admin\Model;

use Think\Model;

class SummaryAttachmentModel extends Model{

	/**
	写入培训总结附件
	**/
	public function addAttachment($data){

		$res=$this->add($data);

		return $res;
	}

	/**
	获取培训总结附件
	**/
	public function getAttachment($id){
		$map["project_id"]=$id;
		$attachments=$this->where($map)->select();
		return $attachments;
	}


	



}