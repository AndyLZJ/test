<?php 
namespace Admin\Model;
use Common\Model\BaseModel;

/**
 * 试题库模型
 */
class QuestionBankModel extends BaseModel{
	
	/**
	 * 首页
	 */
	public function index($id=false){
		$total_page=10;
		$name = I('get.name');
		$start_page = I('get.p',1,'int');
		
		if(!empty($name)){
			$where['name'] = array('like',"%$name%");
		}
		if($id){
			$where['id'] = array('neq',$id);
		}
		
		$specifiedUser = D('IsolationData')->specifiedUser();
		$where['auth_user_id'] = array('in',$specifiedUser);
		$data = M('question_bank')->page($start_page,$total_page)->where($where)->select();
		
		$count = M('question_bank')->where($where)->count();
		
		//隔离数据过滤
		$data = D('IsolationData')->isolationData($data);

		$show = $this->pageClass($count,$total_page);
		
		foreach($data as $k=>$v){
			$data[$k]['num'] = $this->getNumByBankId($v['id']);
		}
		return array(
			'data'=>$data,
			'page'=>$show,
			'name'=>$name
		);
	}
	/**
	 * 根据题库ID获取题库中试题个数
	 */
	public function getNumByBankId($id=''){
		if($id){
			$num = M('examination_item')->where(array('question_bank_id'=>$id))->count();
			return ($num > 999) ? '999+' : $num;
		}else{
			$ids = M('question_bank')->getField('id',true);
			$data = array();
			foreach($ids as $k=>$v){
				$num = M('examination_item')
						->where(array('question_bank_id'=>$v))
						->count();
				$data[$v] = ($num > 999) ? '999+' : $num;
			}
			return $data;
		}
	}
	
	/**
	 * 查找试题库
	 */
	public function search(){
		$name = I('get.name');
		$data = M('question_bank')->where(array('name'=>array('like',"%$name%")))->select();
		foreach($data as $k=>$v){
			$data['num'] = $this->getNumByBankId($v['id']);
		}
	}
	
	/**
	 * 重命名试题库
	 */
	public function reName(){
		$post = I('post.');
		$info = M('question_bank')->where(array('id'=>$post['id']))->field('name,cate')->find();
		
		if($post['name']== $info['name'] && $post['cate']== $info['cate']){
			return array('status'=>1,'info'=>'修改成功');
		}else{
			$exist = $this->existsName($post['name']);
			if($exist){
				return array('status'=>0,'info'=>'该题库名称已存在');
			}
			$data = M('question_bank')
					->where(array('id'=>$post['id']))
					->save(array('name'=>$post['name'],'cate'=>$post['cate']));
			if($data === false){
				return array('status'=>0,'info'=>'修改失败DB_ERROR');
			}else{
				return array('status'=>1,'info'=>'修改成功');
			}
		}
	}
	
	/**
	 * 试题库名字是否存在
	 */
	public function existsName($name,$cate){
		$res = M('question_bank')->where(array('name'=>$name,'cate'=>$cate))->find();
		if($res){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 删除试题库
	 */
	public function del(){
		$ids = I('post.ids');
		foreach($ids as $k=>$v){
			$info = $this->getNumByBankId($v);
			if($info > 0){
				return array('status'=>0,'info'=>"题库中有试题存在，不能删除");
			}
		}
		$res = M('question_bank')->where(array('id'=>array('in',$ids)))->delete();
		if($res === false){
			return array('status'=>0,'info'=>'删除失败DB_ERROR');
		}else{
			return array('status'=>1,'info'=>'删除成功');
		}
	}

	/**
	 * 新增试题库
	 */
	public function add(){
		$name = I('post.name','','trim');
		$cate = I('ost.test_cate');
		$exist = $this->existsName($name,$cate);
		if($exist){
			return array('status'=>0,'info'=>'该题库名称已存在');
		}
		
		$msg = array('name'=>$name,'cate'=>I('post.cate'),'auth_user_id'=>session('user.id'));
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$msg['id'] = getNextId('examination_temp');
		}
		$data = M('question_bank')->add($msg);
		if($data === false){
			return array('status'=>0,'info'=>'新增失败DB_ERROR');
		}else{
			return array('status'=>1,'info'=>'新增成功');
		}
	}
	
	/**
	 * 移动试题
	 */
	public function moveto(){
		$question_bank_id = I('post.id','','int');	//试题库ID
		$target_id = I('post.target_id');	//目标试题库ID
		$id = I('post.ids');				//试题ID
		$id = explode(',',$id);
		
		$db = M('examination_item');
		foreach($id as $k=>$v){
			if($this->IsExists($v,$target_id)){
				continue;
			}
			$res = $db
					->where(array('id'=>$v,'question_bank_id'=>$question_bank_id))
					->save(array('question_bank_id'=>$target_id));
			if($res === false){
				return array('status'=>0,'info'=>'移动试题失败DB_ERROR');
			}
		}
		return array('status'=>1,'info'=>'移动试题成功');
	}
	
	/**
	 * 复制试题
	 */
	public function copyto(){
		$question_bank_id = I('post.id','','int');	//试题库ID
		$target_id = I('post.target_id');	//目标试题库ID
		$id = I('post.ids');				//试题ID
		$id = explode(',',$id);
		
		$db = M('examination_item');
		foreach($id as $k=>$v){
			if($this->IsExists($v,$target_id)){
				continue;
			}
			$data = $db->where(array('id'=>$v))->field('id',true)->find();
			$data['question_bank_id'] = $target_id;
			
			if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['id'] = getNextId('examination_item');
			}
			
			$res = $db->add($data);
			if($res === false){
				return array('status'=>0,'info'=>'复制试题失败DB_ERROR');
			}
		}
		return array('status'=>1,'info'=>'复制试题成功');
	}
	
	/**
	 * 导出试题
	 */
	public function export(){
		$ids = I('get.ids');
		$ids = explode(',',$ids);
		foreach($ids as $k=>$v){
			$res[] = M('examination_item a')
				->join('left join __COURSE__ b on a.belongcourse=b.id')
				->join('left join __QUESTION_BANK__ c on a.question_bank_id=c.id')
				->where(array('a.id'=>$v))
				->field('a.title,a.optiona,a.optionb,a.optionc,a.optiond,a.optione,a.right_option,a.classification,a.keywords,b.course_name,c.name')
				->find();
		}
		
		foreach($res as $k=>$v){
			switch($v['classification']){
				case 1 :
					$res[$k]['classification'] = '单选题';
				break;
				case 2 :
					$res[$k]['classification'] = '多选题';
				break;
				case 3 :
					$res[$k]['classification'] = '判断题';
				break;
				case 4 :
					$res[$k]['classification'] = '简答题';
				break;
				default :
					$res[$k]['classification'] = '未知题型';
			}
		}
		return $res;
	}
	
	/**
	 * 根据试题ID判断是否存在于当前试题库中
	 */
	public function IsExists($eid,$bank_id){
		$title = M('examination_item')->where(array('id'=>$eid))->getField('title');
		
		$data = M('examination_item')
				->where(array('title'=>$title,'question_bank_id'=>$bank_id))
				->getField('id');
		
		if($data){
			return true;
		}else{
			return false;
		}
	}
	
	public function getAllBank(){
		$specifiedUser = D('IsolationData')->specifiedUser();
		$conditions['auth_user_id'] = array('in',$specifiedUser);
		
		$res = M('question_bank')->where($conditions)->select();
		$res = D('IsolationData')->isolationData($res);
		return $res;
	}
	
	/**
	 * 根据试题库ID获取各题型的数量
	 */
	public function getNumsByBankid($id){
		$db = M('examination_item');
		$res = array();
		if($id != ''){
			$ids = explode(',',$id);
			for($i = 1;$i<=4;$i++){
				foreach($ids as $k=>$v){
					$count = $db->where(array('classification'=>$i,'question_bank_id'=>$v))->count();
					$res[$i-1] += $count; 
				}
			}
		}else{
			for($i = 1;$i<=4;$i++){
				$res[] = $db->where(array('classification'=>$i))->count();
			}
		}
		return $res;
	}
}