<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class TissueController extends AdminBaseController{

//******************权限***********************
    /**
     * 组织架构首页
     */
    public function index(){

        $total_page = $this->total_page;

        $data=D('AdminTissue')->index($total_page);
        $this->assign($data);
        $this->display();
    }

    /**
     * 指定人员
     */
    public function designee(){

        $typeid = I("get.typeid");

        $total_page = $this->total_page;
        
        $data=D('AdminTissue')->index($total_page);

        $this->assign($data);
        $this->assign("typeid",$typeid);
        $this->display();

    }

    /**
     * 新增会员
     */
    public function addusers(){

        $total_page = $this->total_page;
        $user_id =  I('get.user_id');
        $data=D('AdminTissue')->index($total_page);
        $tag_data =D('AdminTissue')->tag();

        $typeid = I("get.typeid");
        $redact = I("get.redact");

        //编辑用户
        if($typeid == 1){
            $user_info =D('AdminTissue')->editUser();
            $tag = $user_info['tag_list'];
            $this->assign($user_info['user_info']);
            $this->assign("typeid",$typeid);
            $this->assign("redact",$redact);
            $this->assign("user_id",$user_id);
        }else{
            $tag = $tag_data['items'];
        }

        $this->assign("tag_list",$tag);
        $this->assign($data);
        $this->assign("tree_items",$data['tree_items'][0]);
        $this->display();
    }

    /**
     * 新添下级组织
     */
    public function addTissue(){

        $results = D('AdminTissue')->addTissue();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');


    }

    /**
     * 添加新员工
     */
    public function addUser(){

        $results =D('AdminTissue')->addUser();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');
    }

    /**
     * 编辑当前组织名称
     */
    public function editorTissue(){

        $results =D('AdminTissue')->editorTissue();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }


    /**
     * 移动会员到组织架构
     */
    public function moveUser(){

        $results =D('AdminTissue')->moveUser();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');
    }

    /**
     * 提交更新会员资料
     */
    public function UpdateUser(){

        $results =D('AdminTissue')->UpdateUser();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 删除组织架构会员
     */
        public function delUser(){

            $results =D('AdminTissue')->delUser();

            $data = array(
                "status"=> $results,
            );

            $this->ajaxReturn($data,'json');

    }

    /**
     * 组织架构设置管理员
     */
    public function setAdmin(){

        $results =D('AdminTissue')->setAdmin();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 删除组织
     */
    public function delTissue(){

        $results =D('AdminTissue')->delTissue();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 新建标签
     */
    public function tag(){

        $data =D('AdminTissue')->tag();

        $this->assign($data);

        $this->display();
    }

    /**
     * 删除标签
     */
    public function deltag(){

        $results =D('AdminTissue')->deltag();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 添加标签
     */
    public function addtag(){


        $results =D('AdminTissue')->addtag();
        
        $this->ajaxReturn($results,'json');

    }

    /**
     * 批量标签列表
     */
    public function batchmarking(){

        $data =D('AdminTissue')->tag();

        $this->assign($data);

        $this->display();

    }

    /**
     * 批量添加标记
     */
    public function addbatchmarking(){

        $results =D('AdminTissue')->addbatchmarking();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }


}


