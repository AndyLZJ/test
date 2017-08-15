<?php
namespace Common\Model;
use Common\BaseModel\Model;
/**
 * 处理组织架构公共模型
 */
class TissueRuleModel extends BaseModel{
 
    /**
    * 获取登陆者所在组织下一级或同级组织与学员（被选为内部讲师）的关联的数据
    * 返回的是数组array $data\
    *	 Array
    *  (
    *     [中国太平洋保险] => Array
    *        (
    *            [1] => admin
    *            [695] => 张三
    *            [757] => dhjd
    *        )
    *     [稽核中心] => Array
    *        (
    *            [448] => 罗威
    *            [450] => 汪炳昌
    *            [697] => test001
    *            [698] => test002
    *            [718] => 思思
    *            [753] => wfwefewf
    *        )
    *		)
    */
	public function getToLecturer()
	{  
		//获取登录者当前所在组织id
		$login_id = $_SESSION['user']['id'];
		$tissue = M('tissue_group_access')->where(array('user_id'=>$login_id))->find();
        $tissue_id = $tissue['tissue_id'];
       
    
		$data = array();
        $tissue_name = $this->gtissuename($tissue_id); //登录者所在组织名称
        $data[$tissue_name] = $this->gTissueUser($tissue_id); 
        
	    $nextLevelExist = M('tissue_rule')->field('id')->where(array('pid'=>$tissue_id))->select();
 
	    if(!empty($nextLevelExist)){
         foreach ($nextLevelExist as $k => $v) {
			$tissue_name = $this->gtissuename($v['id']); //登录者所在组织名称
            
			$nextTissues = $this->getNextTissues($v['id']);
			
			$map = array('in',$nextTissues);
            
			$tissueUse = $this->gTissueUser($map);


            $data[$tissue_name] = $tissueUse;
		 } 
	    } 

        return $data;

	}





		/**
		* 根据组织ＩＤ获取其下级所有的组织id
		**/
     public function getNextTissues($tissue_id){
         $temparr = array();
         $arr = M('tissue_rule')->SELECT();
		 
         $data = $this->getOneTree($arr,$parent_id = $tissue_id,$lev=1);
		 $temparr[] = $tissue_id;

        foreach($data as $v){
			$temparr[] = $v['id'];
		}
            

		 $data = implode(',',$temparr);

         return $data;
     }
	 
	/**
	 * 根据组织ＩＤ获取公司名
	 */
	public function gtissuename($id)
	{
		$Model = M('tissue_rule');
		$result = $Model->field('name')->where(array('id' => $id))->find();
		return $result['name'];
	}

	/**
	 * 根据组织ＩＤ获取当前组织的所有用户, array $result
	 *    Array
     *      (
	 *        [448] => 罗威
	 *        [450] => 汪炳昌
	 *        [697] => test001
	 *        [698] => test002
	 *        [718] => 思思
	 *        [753] => wfwefewf
     *      )
	 */
	public function gTissueUser($id)
	{
		
		$result = M('tissue_group_access')->alias('a')
		           ->field('b.id,b.username')
				   ->join('left join __USERS__ b on b.id=a.user_id')
				   ->where(array('a.tissue_id' => $id))
				   ->select();
        $result = array();
        $user_ids =  M('tissue_group_access')->field('user_id')->where(array('tissue_id' => $id))->select();
		foreach($user_ids as $k=>$v){
			 
             $result[$v['user_id']] = $this->gname($v['user_id']);
		}
		return $result;
	}

	/**
	 * 根据用户ＩＤ获取用户名
	 */
	public function gname($user_id)
	{
		$Model = M('users');
		$result = $Model->field('username')->where(array('id' => $user_id))->find();
		return $result['username'];
	}






























}
