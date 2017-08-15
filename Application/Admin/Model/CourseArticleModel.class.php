<?php 

namespace Admin\Model;

use Think\Model;

class CourseArticleModel extends Model{

	protected $saveName;

	protected $trueName;

	//获取对应课程文章章节
	public function getArticles($course_id){

		$map["course_id"]=$course_id;

		$count=$this->where($map)->count();

        $Page=new \Think\Page($count,4);

        $Page->setConfig('prev','<<');

        $Page->setConfig('next','>>');

        $show=$Page->show();

        $list = $this->where($map)->order("sort asc")->limit($Page->firstRow.','.$Page->listRows)->select();

		$assign=array(
            "show"=>$show,
            "list"=>$list
                );

		return $assign;
	}

	//判断是否有视频源
	public function isVideo($course_id){

		$map["course_id"]=$course_id;

		$articles=$this->where($map)->order("sort asc")->select();

		for($i=0;$i<count($articles);$i++){

			if($articles[$i]["type"]==4){

				return $articles[$i]["address"];
			}

		}

		return false;
	}

	//获取某一章节的信息
	public function getChapter($id){

		$map['id']=$id;

		$chapter=$this->where($map)->find();

		$this->saveName=$chapter["save_name"];

		$this->trueName=$chapter["true_name"];

		return $chapter;

	}

}