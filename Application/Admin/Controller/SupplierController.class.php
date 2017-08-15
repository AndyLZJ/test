<?php

namespace Admin\Controller;

use Common\Controller\AdminBaseController;

/**
 * 供应商管理控制器
 * @Andy
 */
class SupplierController extends AdminBaseController
{
    public $total_page = 15;
    /*
     * 供应商管理,列表
     */
    public function supplierManage()
    {
        $total_page = $this->total_page;
        $approved_data = D('Supplier')->supplierSearchList($total_page);
        
        //接收返回的列表数据
        $this->assign('approved_list', $approved_data['list']);
        //接收返回的分页信息
        $this->assign('approved_page', $approved_data['page']);
        //接收返回的供应商类别数据
        $this->assign('data', $approved_data['xhr']);
        //接收返回的搜索条件
        $this->assign('keyword', $approved_data['keyword']);
        $this->assign('keywords', $approved_data['keywords']);
        $this->display();

    }

    /*
     * 供应商详情  外部讲师列表
     */
    public function supplierDetail()
    {
        $total_page = $this->total_page;
        $data = array();
        $data = I('get.');
        $id = $data['sid'];
        if (isset($id)) {
            $result = D('Supplier')->supplierStyleCheck($id);
            // print_r($result);
            $this->assign('ret', $result['list']);
            $this->assign('ret2', $result['main_courses']);
        }
        //供应商详情里关联的外部讲师列表
        $data = D('Supplier')->getOutsideLecturer($total_page, $id);
        $this->assign('list', $data['list']);
        $this->assign('count', $data['count']);
        $this->assign('page', $data['page']);
        $this->display();
    }


    /*
     * 新增供应商
     */
    public function addSupplier()
    {
        if (IS_POST) {

            $data = I("post.");
            $data['main_course'] = implode(',', $data['tag']);//以,方式拼接主打课程
            if(strtolower(C('DB_TYPE')) == 'oracle'){
				$data['sid'] = getNextId('supplier');
			}
            $model = D('Supplier');
            
            if ($model->create($data, 1)) {

                $data['auth_user_id'] = $_SESSION['user']['id'];

                $res = $model->data($data)->add();

                if ($res) {
                    F('addSupplier',NULL);
                    $this->success('添加成功', U('supplierManage'));
                }
            } else {

                F('addSupplier',$data);
                $this->error($model->getError());
                
            }

        } else {

            $addSupplier = F('addSupplier');

            $ret = M('SupplierType')->select();//获取供应商类型/领域

            // $ret = D('IsolationData')->isolationData($ret);

            $this->assign($addSupplier);
            $this->assign('res',$ret);
            $this->display();
        }

    }


    /*
     * 编辑供应商
     */
    public function editSupplier()
    {
        if (IS_GET) {
            $id = I('get.sid');
            $model = D('Supplier');
            $result = $model->supplierStyleCheck($id);
            $ret = M('SupplierType')->select();
            $this->assign('res', $ret);
            $this->assign('list', $result['list']);
            $this->assign('main_courses', $result['main_courses']);
            // C('TOKEN_ON',false);
            $this->display();
        } else {
            $data = I('post.');
            unset($data['main_course']);
            $data['main_course'] = implode(",", $data['tags']);
            unset($data['tags']);

            // C('TOKEN_ON',false); //此处关闭表单验证,表单令牌验证一般用于表单的添加操作，编辑时不能用
          

            if (D('Supplier')->token(false)->create($data,1)) {
                $xhr = D('Supplier')->where(array('sid' => $data['sid']))->save();
                if ($xhr) {
                    $this->success('修改成功', U('supplierManage'));
                } else {
                    $this->error('未作任何修改');
                }
            } else {
                // $this->error('输入电话或手机有误');
                $this->error(D('Supplier')->getError());
            }
        }

    }

    /*
     * 供应商删除操作
     */
    function delSupplier()
    {
        if (IS_POST) {
            $id = I('post.id');
            $exist = M('lecturer')->where(array("sid" => $id))->select();
            if ($exist) {
                $data = array('status' => 0, 'msg' => '该供应商下有相关的讲师！');
                $this->ajaxReturn($data);
            } else {
                $res = M("Supplier")->where(array('sid' => $id))->delete();
                if ($res) {
                    $data = array('status' => 1, 'msg' => '删除成功');
                    $this->ajaxReturn($data);
                } else {
                    $data = array('status' => 0, 'msg' => '删除失败');
                    $this->ajaxReturn($data);
                }
            }

        }
    }


    /*
     * 新增供应商擅长类别
     */
    public function addSupplierStyle()
    {
        $data = array();
        $data['tname'] = I('post.tname');
	
	if(strtolower(C('DB_TYPE')) == 'oracle'){
		$data['id'] = getNextId('suppliertype');
	}
		
        $model = M('SupplierType');

        $data['auth_user_id'] = $_SESSION['user']['id'];

        $model->data($data)->add();

        $this->ajaxReturn(true);

        /*
        if ($model->create($data, 1))//2为数据更新时的验证
        {
            $res =  $model->data($data)->add();

            // dump($res);
            if ($res) {
                $this->ajaxReturn(true);
            } else {
                $this->ajaxReturn(false);
            }
        } else {
            $this->ajaxReturn(false);
        }*/

    }                       

    /*
     * 供应商类别
     */
    public function supplierCategory()
    {
        $total_page = $this->total_page;
        $approved_data = D('SupplierType')->getSupplierStyle($total_page);
        //接收返回的列表数据
        $this->assign('approved_list', $approved_data['list']);
        //接收返回的分页信息
        $this->assign('approved_page', $approved_data['page']);
        //接收返回的搜索条件
        $this->assign('keyword', $approved_data['keyword']);
        $this->display();
    }

    /*
     * 供应商类别编辑
     */
    public function editSupplierCategory()
    {
        $ret = D('SupplierType')->updateSupplierType();
        if ($ret == 0) {
            $this->ajaxReturn(0);
        } elseif ($ret == 1) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(2);
        }
    }

    /*
     * 批量删除
     */
    public function del_all()
    {
        $post = (I('post.id'));
        $res = M('SupplierType')->where(array('id' => array('in', $post)))->delete();
        if ($res) {
            $this->ajaxReturn(1);
        } else {
            $this->ajaxReturn(0);
        }
    }

    /*
     * 删除供应商类别
     */
    public function delSupplierCategory()
    {
        if (IS_POST) {
            $data = array();
            $data = I("post.sid");
            $res = M('SupplierType')->where(array('id' => $data))->delete();
            if ($res) {
                $this->ajaxReturn($res);
            } else {
                $this->ajaxReturn(0);
            }
        } else {
            $this->ajaxReturn(array('info' => '不合法请求'));
        }
    }
}