<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台权限管理
 */
class JurisdictionController extends AdminBaseController{

//******************权限***********************
    /**
     * 权限列表
     */
    public function index(){

        $data=D('AdminJurisdiction')->jurisdictionList();

        $assign=array(
            'rule_data'=>$data['rule_data'],
            'data'=>$data['list'],
            'page'=>$data['page']
        );

        $this->assign($assign);
        $this->display();
    }

    /**
     * 添加角色
     */
    public function addRole(){

        $results =D('AdminJurisdiction')->addRole();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 删除角色
     */
    public function delRole(){

        $results =D('AdminJurisdiction')->delRole();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 获取当前角色权限
     */
    public function editorHtml(){

        $results =D('AdminJurisdiction')->editorHtml();

        $this->ajaxReturn($results,'json');

    }

    /**
     * 编辑角色权限
     */
    public function editorRole(){

        $results =D('AdminJurisdiction')->editorRole();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }


    /**
     * 取消管理员
     */
    public function cancelAdmin(){

        $results =D('AdminJurisdiction')->cancelAdmin();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     *  权限 - 查看用户
     */
    public function view_user(){

        $group_id = I("get.group_id",0);
        $total_page = $this->total_page;

        $data = D('AdminJurisdiction')->view_user($total_page);

        $this->assign('view_user_list',$data['list']);
        $this->assign('view_user_page',$data['page']);
        $this->assign('title',$data['title']);
        $this->assign('group_id',$group_id);

        $this->display();
    }

    /**
     * 设置管理员页面
     */
    public function view_admin(){

        $total_page = $this->total_page;

        $data = D('AdminJurisdiction')->view_admin($total_page);

        $this->assign('data',$data);
        $this->display();

    }


    /**
     * 设置管理员
     */
    public function updateAdmin(){

        $results =D('AdminJurisdiction')->updateAdmin();

        $data = array(
            "status"=> $results,
        );

        $this->ajaxReturn($data,'json');

    }

    /**
     * 更改权限列表
     */
    public function authority(){

        $typeid = I('get.typeid');

        //获取组织架构信息

        if($typeid == 1 OR $typeid == 2){

            $where['a.user_id'] = array("eq",$_SESSION['user']['id']);

            //获取用户上级组织名称
            $group_data = M("tissue_group_access a")
            		->join("LEFT JOIN __TISSUE_RULE__ b ON a.tissue_id = b.id")
            		->field("b.id,b.pid")
            		->order('b.id asc')
            		->where($where)
            		->find();

            $treeInfo = D('AdminTissue')->tree($group_data['id']);


            $this->assign('treeInfo',array($treeInfo));

        }else{

            $treeInfo = D('AdminTissue')->tree(1);

            $this->assign('treeInfo',array($treeInfo));
        }

        $level = D('AdminTissue')->hierarchy($treeInfo['id']);


        $this->assign('level',$level);
        $this->assign('typeid',$typeid);
        $this->display();
    }

}
