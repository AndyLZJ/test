<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * ModelName
 */
class AdminProjectModel extends BaseModel{



    /*获取所有的培训项目*/
    public function getAll($type,$condition){
        $map=array();
        if(!empty($condition)){
            $condition="%" . $condition . "%";
            $map['project_name|class_name|project_description']=array('like',$condition);
        }
        $map["type"]=$type;
        $count=$this->where($map)->count();
        $Page=new \Think\Page($count,4);
        $Page->type=$type;
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $show=$Page->show();
        $list = $this->where($map)->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $assign=array(
            "show"=>$show,
            "list"=>$list
                );
        return $assign;
        
    }

    /*保存新建培训项目*/

    public function createProject($data){
        $result=$this->data($data)->add();
        return $result;
    }

    /***
    获取新建培训项目
    */
    public function getOne($id){
        $map["id"]=$id;
        $newCourse=$this->field("start_time,end_time")->where($map)->find();
        return $newCourse;
    }

    /***
    
    判断当前项目是否通过审核，成功返回项目信息
    
    */

    public function isCheck($id){
        $map["id"]=$id;
        $map['type']=4;
        $project=$this->where($map)->find();
        if($project==null){
            return false;
        }
        return $project;
    }

    /***
    获取培训项目详情
    */

    public function obtain($id){
        $map["id"]=$id;
        $project=$this->where($map)->find();
        for($i=0;$i<count($project);$i++){
        }
        return $project;
    }
    
    /***
    删除培训项目
    */
    public function deleteData($id){
        $map["id"]=$id;
        $res=$this->where($map)->delete();
        return $res; 
    }

    /**
    更新培训项目
    **/
    public function update($data){
        $map["id"]=$data["project_id"];
        $result=$this->where($map)->save($data);
        return $result;
    }

 


}
