<?php 

namespace Admin\Model;

use Think\Model;

class SurveyCategoryModel extends Model{

	//获取所有的栏目分类
	public function getAllCategory(){

		$surveyCategorys=$this->select();

		return $surveyCategorys;
	}


}