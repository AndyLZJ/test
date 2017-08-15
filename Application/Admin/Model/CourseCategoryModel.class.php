<?php 

namespace Admin\Model;

use Think\Model;

class CourseCategoryModel extends Model{

	//获取对应的栏目分类
	public function getCategory($cat_id){

		
		$map["id"]=$cat_id;

		$cateGory=$this->where($map)->find();

		return $cateGory["cat_name"];
	}

	//获取所有的栏目分类
	public function getAllCategory(){

		$couresCategorys=$this->select();

		$couresCategorys=tree($couresCategorys);

		return $couresCategorys;
	}


}