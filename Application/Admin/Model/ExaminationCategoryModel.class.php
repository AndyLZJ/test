<?php 

namespace Admin\Model;

use Think\Model;

class ExaminationCategoryModel extends Model{

	//获取所有的栏目分类
	public function getAllCategory(){

		$examinationCategorys=$this->select();

		return $examinationCategorys;
	}


}