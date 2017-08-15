<?php 

namespace Admin\Model;

use Think\Model;

class ExaminationModel extends Model
{
	//获取所有审核通过的考试
	public function getAll(){

		import("Org.Nx.AjaxPage");

		$limitRows = 4;

		$map["status"]=1;

		$map["is_available"]=1;
		
		$testCategoryTable=$this->tablePrefix . "examination_category";

		$count=$this->where($map)->count();

		$p = new \AjaxPage($count, $limitRows,"test");

		$p->setConfig('theme','%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

       			
       	$sql="select a.id,a.test_name,a.test_cat_id,a.test_mode,b.cat_name from " . $this->trueTableName . " a left join " . $testCategoryTable . " b on a.test_cat_id=b.id where a.status=1 limit " . $p->firstRow . " , " . $p->listRows;

		$examintations=$this->query($sql);
		
		$show = $p->show();

		$assign=array(
			"list"=>$examintations,
			"show"=>$show
		);

		return $assign;
	}

	//组合条件搜索考试

	public function searchExamination($data){

		import("Org.Nx.AjaxPage");

		$limitRows = 4;

		$map=array();

		$where=" a.status=1 and a.is_available=1";


		if($data["test_category"]!=0){

			$map["test_cat_id"]=$data["test_category"];

			$where.=" and a.test_cat_id= " . $data["test_category"];


		}

		if($data["test_name"]!=""){

			$datas["test_name"]="%" . $data["test_name"] . "%";

			$map['test_name']=array('like',$datas["test_name"]);

			$where.=" and a.test_name like " . "'%" . $data["test_name"] . "%'";
		}

		$map["status"]=1;

		$map["is_available"]=1;

		$testCategoryTable=$this->tablePrefix . "examination_category";

		$count=$this->where($map)->count();

		$p = new \AjaxPage($count, $limitRows,"test");

		$p->setConfig('theme','%upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');

		
		$sql="select a.id,a.test_name,a.test_cat_id,b.cat_name from " . $this->trueTableName . " a left join " . $testCategoryTable . " b on a.test_cat_id=b.id where " . $where . " limit " . $p->firstRow . " , " . $p->listRows;

		$examinations=$this->query($sql);

		$show = $p->show();

		$assign=array(
			"list"=>$examinations,
			"show"=>$show
		);

		return $assign;

	}


/***
获取已经选定的考试	
**/

public function getExaminationSelect(){

	$arr["project_id"]=$_SESSION['project']['id'];

	$projectExamination=D("projectExamination")->where($arr)->find();

	$id=array_unique(explode(",",$projectExamination["test_id"]));

	$map["id"]=array("in",$id);

	$projectExamination=D("Examination")->where($map)->select();

	return $projectExamination;

}

/***
获取资源库对应考试的考试信息
*/
public function getOne($id){

	$map["id"]=$id;

	$examination=$this->where($map)->find();

	return $examination;
}

/***
获取指定项目的考试信息
**/

public function getExaminationOneSelect($project_id){

	$arr["project_id"]=$project_id;

	$projectExamination=D("projectExamination")->where($arr)->find();

	$id=array_unique(explode(",",$projectExamination["test_id"]));

	$map["id"]=array("in",$id);

	$projectExamination=D("Examination")->where($map)->select();

	return $projectExamination;

}
 /*获取资源库课程信息*/

    public function getExamination($arr){
        $map["id"]=array("in",$arr);
        $examinations=$this->field("id,test_name,test_score,test_mode")->where($map)->order("id")->select();
       	return $examinations;
    }

	/**
	 * 获取资源管理 - 试卷管理数据
	 */
	public function gets_Examination(){

		$rows = M("examination a")->join("LEFT JOIN __EXAMINATION_CATEGORY__ b ON a.test_cat_id = b.id")->field("a.id,a.test_name,b.cat_name")->where("status=0")->select();

		return $rows;

	}


}