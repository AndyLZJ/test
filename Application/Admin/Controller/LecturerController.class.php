<?php
namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 讲师管理控制器
 */
class LecturerController extends AdminBaseController
{
    /**
     *讲师列表展示页 ,内部讲师
     */
    public function pagelist()
    {
       
        //获取搜索条件,用作模板框的显示
        $condition = I('get.table_search');

        //获取页码
        $p = I('get.p');
       
        //每页大小
        $size = 15;

        //讲师类型
        $type = I('get.type') ?  I('get.type') :0 ;

        //获取讲师列表
        $Model = D('Lecturer');
        $data = $Model->getPagelist($type, $p,$size);
     
        $show = $data[0];
        $list = $data[1];
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('condition', $condition);

        if ($type == 0) {
            $this->display('page_inside');
        } else {
            $this->display('page_outside');
        }
    }
    /**
     *讲师列表展示页 ,外部讲师
     */
    public function pagelistoutside()
    {
        //获取搜索条件,用作模板框的显示
        $condition = I('get.table_search');
        
        //获取页码
        $p = I('get.p');

        //每页大小
        $size = 15;

        //讲师类型
        $type = I('get.type') ? I('get.type') : 1;

        //获取讲师列表
        $Model = D('Lecturer');
        $data = $Model->getPagelist($type, $p,$size);
       
        // $Page = new \Think\Page($count, $size);
        // $Page->setConfig('theme','%HEADER% %FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        // $Page->setConfig('header',"<span>%TOTAL_PAGE%</span>");
        // $Page->setConfig('prev','上一页');
        // $Page->setConfig('next','下一页');
        // $show = $Page->show();
        $show = $data[0];
        $list = $data[1];
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->assign('condition', $condition);
        $this->display('page_outside');
        
    }
    

    /**
     *讲师列表批量删除（没用上）
     */
    public function multidel(){
       
        $M = D('Lecturer');
        // $user_id = 88; //调试用
        $res = $list = $M->batchDelete();
      
       if (!$res) {
            // $this->error('数据异常');
         $data['status'] = 0;  
         $data['info'] = '删除失败';  
         $data['url'] = ''; 
         $this->ajaxReturn($data);
        }else{
    
         $data['status'] = 1;  
         $data['info'] = '删除成功';  
         $data['url'] = ''; 
         $this->ajaxReturn($data);
        }
    }
      
    /**
     *讲师列表单个删除，实质删除
     */
    public function delone(){
        $M = D('Lecturer');
        // $user_id = 88; //调试用
        $res = $list = $M->lecturerDelone();
        if ($res) {
         $this->success('删除成功！');
        
        }else{
         $this->error('删除失败！讲师下有相关课程！');
        }
    }

    // public function pagelist()
    // {
    //     $this->display('page_inside');
    // }
    
    /**
     *选择时获取所有内部学员姓名,测试
     */
    public function getLecturersAdd()
    {
     $user = D('Lecturer')->gusertissue(112);
    //   $user = D('Lecturer')->getToLecturer();
      print_r($user);
    }



    /**
     *新增讲师展示页
     */
    public function add()
    {
        if (IS_POST) {
           
            $this->update();
            exit;
        }

        $Model = D('Lecturer');
        $supplier = $Model->getSupplier(); //获取所有的供应商
        $tnamelist = $Model->getTname(); //获取所有的擅长领域
        
        // $user = $this->getName(); //选择时获取所有内部学员姓名
        $user = D('Lecturer')->getToLecturer(); //选择时获取所有注册用户姓名
        $this->assign('type', 'add'); //定义类型为新增
        $this->assign('supplier', $supplier);
        $this->assign('tnamelist', $tnamelist);
        $this->assign('user', $user);  //输出选择内部讲师人员
        $this->display('new');
    }

    /**
     *新增或编辑数据操作,点击保存按钮请求此方法
     */
    public function update()
    {

        $Model = D('Lecturer');
        $id = $Model->update();
        //  $this->error($Model->getError());
        if ($id) {
            $this->success('成功',U('Admin/Lecturer/pagelist'));
        } else {
             $temp = I('post.id');
             $detail = array('sid'=>I('post.sid'),
                    'tagtype'=>1,
                    'levels'=>I('post.levels'),
                    'name'=>I('post.name'),
                    'address'=>I('post.address'),
                    'age'=>I('post.age'),
                    'year'=>I('post.year'),
                    'price'=>I('post.price'),
                    'description'=>I('post.description'),
                    'certificates'=>I('post.certificates'),
                    );
            
             if(I('post.type') == 1 && empty($temp)){
                //   print_r($detail);exit;
               $this->error($Model->getError(),U('Admin/Lecturer/add',
                     array(
                    'sid'=>I('post.sid'),
                    'tagtype'=>1,
                    'levels'=>I('post.levels'),
                    'name'=>I('post.name'),
                    'address'=>I('post.address'),
                    'age'=>I('post.age'),
                    'year'=>I('post.year'),
                    'price'=>I('post.price'),
                    'description'=>I('post.description'),
                    'certificates'=>I('post.certificates')
                    )));
             }else{
               $this->error($Model->getError());
             }
           
        }
    }

    /**
     *编辑展示页
     */
    public function edit()
    {
        $id = I('get.id');
        if (empty($id)) {
            $this->error('参数错误');
        }
        $Model = D('Lecturer');
        $detail = $Model->detail($id); //获取编辑时的详情展示
        $detail['tname'] = json_decode($detail['tname'],true);
        $supplier = $Model->getSupplier(); //获取所有的供应商
        $tnamelist = $Model->getThisTname($id); //获取所有的已经选择的类别

        // $user = $this->getName();
        $user = D('Lecturer')->getToLecturer(); //选择时获取所有内部学员姓名
        $this->assign('type', 'edit'); //定义类型为编辑
        $this->assign('supplier', $supplier);
        $this->assign('tnamelist', $tnamelist);
        $this->assign('user', $user);
        $this->assign('detail', $detail);
        $this->display('new');
    }

    /**
     *获取用户名
     */
    public function getName(){   
        $User = M('users');
        $data = $User->field('id,username')->select();
        return $data;
    }

    /**
     *讲师详情页
     */
    public function detail()
    {
        $type = I('get.type');//$type , 0表示内部讲师， 1表示外部讲师
        // echo $type;exit;
        $Lecturer = D('Lecturer');
        $detail = $Lecturer->getDetail($type);
        $this->assign('type', $type);
        $this->assign('detail', $detail);
        $this->display('detail_list');
    }

    /**
     *讲师详情列表页(展示课程数据)
     */
    public function detailList()
    {
        $id = I('get.id');
        $p =I('get.p');
        $Lecturer = D('Lecturer');
        $detail = $Lecturer->getDetail();
        $lecturer_id = $detail['id'];
        $size = 3;
        $Course = M('course');
        $course_detail = $Course->where(array('lecturer' => $lecturer_id))->page($p,$size)->select();
        $count = $Course->where(array('lecturer' => $lecturer_id))->count();
        $Page = new \Think\Page($count, $size);
        $show = $Page->show();
        $this->assign('show', $show);
        $this->assign('course_detail', $course_detail);
        $this->display('detail_list');
    }


   
     public function t(){
         $data = D('Lecturer')->t(267);

     }


}