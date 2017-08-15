<?php
namespace Common\Model;

use Common\Model\BaseModel;
/**
 * 菜单操作model
 */
class ResearchModel extends BaseModel{

	//初始化
	public function __construct(){}

	/**
	 * 调研管理 - 首页
	 */
	public function index($total_page){

		$start_page = I("get.p",0,'int');
		$keyword=I("get.keyword")?I("get.keyword"):"";
		$typeid = I("get.typeid",1,'int');

		if(!empty($keyword)){
			$where['_string']="(research_name like '%".$keyword."%')";
		}

		//只获取本级+下级数据
		$specifiedUser = D('IsolationData')->specifiedUser(false);
		$where['auth_user_id'] = array('in',$specifiedUser);
		$where['audit_state'] = array('eq',$typeid);
		
		$results = M("research")
					->where($where)
					->page($start_page,$total_page)
					->order("end_time desc,start_time desc")
					->select();

		$count = M("research")->where($where)->count();

		$result = D('IsolationData')->isolationData($result);
		
		//输出分页
		$show=$this->pageClass($count,$total_page);

		$data = array(
			'typeid'=>$typeid,
			'page' => $show,
			'map' => $results,
			'keyword'=>$keyword
		);

		return $data;
	}

	/**
	 * 添加调研
	 */

	public function editor(){

		$post = I('post.');

		$data = array(
			"research_name"=>$post['research_name'],
			"start_time"=>$post['start_time'],
			"end_time"=>$post['end_time'],
			"survey_id"=>$post['survey_id'],
			"create_time"=>date("Y-m-d H:i:s",time()),
			"create_user"=>$_SESSION['user']['id'],
			"auth_user_id"=>$_SESSION['user']['id']
		);

		$tissue_array = $post['user_id'];

		if(empty($post['id'])){

			$orderno =  D('Trigger')->orderNumber(6);

			$data['orderno'] = $orderno;

			try {

				$DB = M('research');

				$DB->startTrans();

				$research_id = $DB->data($data)->add();

				foreach($tissue_array as $id){
					M('research_tissueid')->add(array("research_id"=>$research_id,"tissue_id"=>$id));
				}

				if($research_id){

					if (!$DB->autoCheckToken($post)){

						$results = false;

					}else{

						$DB->commit();
						$results = true;

					}

				}

			} catch ( Exception $e ) {

				$DB->rollback();

				$results = false;

			}

		}else{

			try {

				$data['audit_state'] = 0;

				$DB = M('research');

				$DB->startTrans();

				M("research_tissueid")->where('research_id = '.$post['id'])->delete();

				$DB->where("id=".$post['id'])->setField($data);

				foreach($tissue_array as $id){
					M('research_tissueid')->add(array("research_id"=>$post['id'],"tissue_id"=>$id));
				}

				$DB->commit();

				$results = true;


			} catch ( Exception $e ) {

				$DB->rollback();

				$results = false;

			}

		}

		return $results;
	}

	/**
	 * 获取编辑资料
	 */
	public function getEditor(){

		$id = I('get.id',0);

		if($id > 0){

			$where['a.id'] = array("eq",$id);

			$data = M('research a')
					->field("a.*,b.survey_name")
					->join("LEFT JOIN __SURVEY__ b ON a.survey_id = b.id")
					->where($where)
					->find();

			//获取相关部门
			$tissueid_items = M('research_tissueid a')
							->field("a.tissue_id,b.name")
							->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
							->where("research_id = ".$id)
							->select();

			foreach($tissueid_items as $item){
				$tissue_id[] = $item['tissue_id'];
				$tissue_name[] = $item['name'];
			}

//			$str_tissue_id = implode(",",$tissue_id);
//			$str_tissue_name = implode(",",$tissue_name);

			$items = array(
				"id"=>$id,
				"data"=>$data,
				"tissue_id"=>$tissue_id,
				"tissue_name"=>$tissue_name
			);

		}else{
			$items = array();
		}

		return $items;
	}

	/**
	 * 删除调研
	 */
	public function delete(){

		$ids = I('post.ids');

		$data['id'] = array("in",$ids);

		$results = M("research")->where($data)->setField("audit_state",3);

		return $results;
	}

	/**
	 * 查看详情
	 */
	public function view(){

		$id = I("get.research_id");

		$research_data = M('research')->where("id=".$id)->find();

		$start_bccomp = bccomp(time(),strtotime($research_data['start_time']));
		
		//已开始
		if($start_bccomp >= 0){
			$data['typeid'] = 1;
		}else{
			//问卷基本资料
			$research_data = M('research a')->where("a.id = ".$id)->find();
			if($research_data){
				$survey = M('survey a')->field("a.*,b.cat_name")
					->join("LEFT JOIN __SURVEY_CATEGORY__ b ON a.survey_cat_id = b.id")
					->where(array("a.id"=>$research_data["survey_id"]))->find();
				
				$surveyItem = M("survey_item")->where("survey_id=".$research_data["survey_id"])->order("order asc")->select();
				foreach ($surveyItem as $key=>$value){
					if($value["classification"] == 1 || $value["classification"] == 2){
						//单选 多选获取选项
						$itemOpt = M("survey_item_opt")->where("item_id=".$value["id"])->order("order asc")->select();
						$surveyItem[$key]["option"] = $itemOpt;
					}
				}
			}
			
			return $data = array(
					"survey" => $survey,//详情
					"surveyItem" => $surveyItem,//题目
					"typeid" => 2
			);
			return $data;
		}
	}
}
