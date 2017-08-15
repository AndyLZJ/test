<?php
namespace Home\Controller;
use Think\Controller;
use \Org\Util\Rbac;
class RegisterController extends Controller {
   
	  /**
    *注册显示页面
    *@return [type] [description]
    */
    public function register(){

        $this->display();

    }
    /**
     * 注册动作-手机号码注册
     * @return [type] [description]
     */
    public function signup(){
        $code = I('post.code');
        $mobile=I("post.mobile");

        $sendCode = F('sendCode');
        if($code!=$sendCode['code']){
        	$data=array("code"=>0,"message"=>"验证码不正确");
            $this->ajaxReturn($data);
        }elseif($sendCode['mobile']!=$mobile){
            $data=array("code"=>0,"message"=>"接收短信手机号码不正确");
            $this->ajaxReturn($data);
        }elseif($sendCode['time']<time()){
            $data=array("code"=>0,"message"=>"验证码已过期,请重新获取");
            $this->ajaxReturn($data);
        }
        $username=I("post.username");
        $password=I("post.password");

        $number=I("post.number");
        $map=array();
        $map["phone"]=$mobile;
        //验证账号密码
        $data=M('Users')->where($map)->find();
        if(!empty($data)){
           
            if($data['status'] == 2){
            //待审核用户的注册
            $data=array("code"=>1 ,"message"=>"用户待审核状态，请等待审核结果!");
            $this->ajaxReturn($data);  

            }else if($data['status'] == 3){
            //注册但被逻辑删除(禁用)的用户登录系统，处理逻辑
            $data=array("code"=>1 ,"message"=>"用户禁用状态，请联系管理员处理!");
            $this->ajaxReturn($data);
            }else if($data['status'] == 1){
            //已注册审核通过用户
            $data=array("code"=>1 ,"message"=>"该号码已注册，请登陆!");
            $this->ajaxReturn($data);
            }else if($data['status'] == 0){ 
            //注册但拒绝用户
            $orderno = D('Trigger')->orderNumber(9);
    
            $fdata = array(
                'username' => $username,
                'password' => md5($password),
                'avatar'=>'/Upload/avatar/default.png',
                "register_time"=> date('Y-m-d H:i:s'),
                'orderno'=>$orderno,
                'status'=>2
                );
            $status = M('Users')->where(array('id'=>$data['id']))->save($fdata);
            if($status !== false){ 
                //用户重新提交注册审核接口触发
                $res = D('Trigger')->projectResubmit($data['id'],9);
                
                M('auth_group_access')->where(array('user_id'=>$data['id']))->save(array("group_id"=>3));
                M('tissue_group_access')->where(array('user_id'=>$data['id']))->save(array("tissue_id"=>0,"job_id"=>0,"manage_id"=>0));
                $data=array("code"=>3,"message"=>"注册成功，等待跳转！");
                $this->ajaxReturn($data);
            }else{
                $data=array("code"=>3,"message"=>"注册失败，请重新注册");
                $this->ajaxReturn($data);
            }
          }

        }else{

        $orderno = D('Trigger')->orderNumber(9);
        
        $data = array(
            'username' => $username,
            'password' => md5($password),
            'avatar'=>'/Upload/avatar/default.png',
            'phone'   => $mobile,
            "register_time"=> date('Y-m-d H:i:s'),
            'orderno'=>$orderno
            );
        $status = M('Users')->add($data);
        if($status){ 
            M('auth_group_access')->add(array("user_id"=>$status,"group_id"=>3));
            M('tissue_group_access')->add(array("user_id"=>$status,"tissue_id"=>0,"job_id"=>0,"manage_id"=>0));
            $data=array("code"=>3,"message"=>"注册成功，等待跳转！");
            $this->ajaxReturn($data);
        }else{
            $data=array("code"=>3,"message"=>"注册失败，请重新注册");
            $this->ajaxReturn($data);
        }

       }
    }



    /**
     * 注册动作-邮箱注册
     * @return [type] [description]
     */
    public function emailSignup(){
        $code = I('post.code');
        $email=I("post.email");

        $sendCode = F('sendCode');
        if($code!=$sendCode['code']){
        	$data=array("code"=>0,"message"=>"验证码不正确");
            $this->ajaxReturn($data);
        }elseif($sendCode['email']!=$email){
            $data=array("code"=>0,"message"=>"接收验证码的邮箱不正确");
            $this->ajaxReturn($data);
        }elseif($sendCode['time']<time()){
            $data=array("code"=>0,"message"=>"验证码已过期,请重新获取");
            $this->ajaxReturn($data);
        }
        $username=I("post.username");
        $password=I("post.password");

        $number=I("post.number");
        $map=array();
        $map["email"]=$email;
        //验证账号密码
        $data=M('Users')->where($map)->find();
        if(!empty($data)){
           
            if($data['status'] == 2){
            //待审核用户的注册
            $data=array("code"=>1 ,"message"=>"用户待审核状态，请等待审核结果!");
            $this->ajaxReturn($data);  

            }else if($data['status'] == 3){
            //注册但被逻辑删除(禁用)的用户登录系统，处理逻辑
            $data=array("code"=>1 ,"message"=>"用户禁用状态，请联系管理员处理!");
            $this->ajaxReturn($data);
            }else if($data['status'] == 1){
            //已注册审核通过用户
            $data=array("code"=>1 ,"message"=>"该号码已注册，请登陆!");
            $this->ajaxReturn($data);
            }else if($data['status'] == 0){ 
            //注册但拒绝用户
            $orderno = D('Trigger')->orderNumber(9);
    
            $fdata = array(
                'username' => $username,
                'password' => md5($password),
                'avatar'=>'/Upload/avatar/default.png',
                "register_time"=> date('Y-m-d H:i:s'),
                'orderno'=>$orderno,
                'status'=>2
                );
            $status = M('Users')->where(array('id'=>$data['id']))->save($fdata);
            if($status !== false){ 
                //用户重新提交注册审核接口触发
                $res = D('Trigger')->projectResubmit($data['id'],9);
                
                M('auth_group_access')->where(array('user_id'=>$data['id']))->save(array("group_id"=>3));
                M('tissue_group_access')->where(array('user_id'=>$data['id']))->save(array("tissue_id"=>0,"job_id"=>0,"manage_id"=>0));
                $data=array("code"=>3,"message"=>"注册成功，等待跳转！");
                $this->ajaxReturn($data);
            }else{
                $data=array("code"=>3,"message"=>"注册失败，请重新注册");
                $this->ajaxReturn($data);
            }
          }

        }else{

        $orderno = D('Trigger')->orderNumber(9);
        
        $data = array(
            'username' => $username,
            'password' => md5($password),
            'avatar'=>'/Upload/avatar/default.png',
            // 'phone'   => $mobile,
            "register_time"=> date('Y-m-d H:i:s'),
            'orderno'=>$orderno,
            'email'=>$email
            );
		if(strtolower(C('DB_TYPE')) == 'oracle'){
			$data['id'] = getNextId('users');
			$data['register_time'] = array('exp',"to_date('".date('Y-m-d H:i:s')."','yy-mm-dd hh24:mi:ss')");
		}
		
        $status = M('Users')->add($data);
        if($status){ 
            M('auth_group_access')->add(array("user_id"=>$status,"group_id"=>3));
            M('tissue_group_access')->add(array("user_id"=>$status,"tissue_id"=>0,"job_id"=>0,"manage_id"=>0));
            $data=array("code"=>3,"message"=>"注册成功，等待跳转！");
            $this->ajaxReturn($data);
        }else{
            $data=array("code"=>3,"message"=>"注册失败，请重新注册");
            $this->ajaxReturn($data);
        }

       }
    }
}