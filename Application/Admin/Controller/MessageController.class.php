<?php 

namespace Admin\Controller;

use Common\Controller\AdminBaseController;
/**
 * 消息通知控制器，站内消息推送思路：每个需推送消息节点，写相应逻辑推送数据到admin_message表即可
 */
class MessageController extends AdminBaseController{
    /**
    *消息通知列表
    */
    public function messageList(){
        $condition = I('table_search');
        $searchType = I('searchType');
        $Message = D('Message');
        // print_r($d);
        $user_id = $_SESSION['user']['id']; 
        // $user_id = 88; //调试用
        $list = $Message->messList($user_id);
        // print_r($list);
        foreach($list as $k=>$v){
            $k = $k + 1;
            $k = 'list'.$k;
            $this->assign($k,$v);   //list1表示全部消息，list2表示未读，list3表示已读，list4为全部消息搜索框值，list5为未读消息搜索框值，list6为已读消息搜索框值
                                   //list7表示输出的page1，list8表示输出的page2，list9表示输出的page3
        //    if($k == 7){
        //     $this->assign('show1',$v); 
        //    }
        }
        $this->display('message_list');
      
    }
    /**
    *消息通知列表搜索
    */
    public function search(){
        

    } 

    /**
    *消息单个删除
    */
   public function delone(){ 
      $Message = D('Message');
      $user_id = $_SESSION['user']['id']; 
    //   $user_id = 88; //调试用
      $res = $list = $Message->deleteOne($user_id);
       if (!$res) {
            $this->error('数据异常');
            }else{
            $this->success('删除成功',U('Admin/Message/messageList'));
        }
    }

     /**
     *消息批量删除
     */
   public function multidel(){ 
    
      $Message = D('Message');
      $user_id = $_SESSION['user']['id']; 
    //   $user_id = 88; //调试用
      $res = $list = $Message->batchDelete($user_id);
      
       if (!$res) {
            // $this->error('数据异常');
             $data['status'] = 0;  
             $data['info'] = '删除失败';  
             $data['url'] = U('Admin/Message/messageList'); 
             $this->ajaxReturn($data);
            }else{
            // $this->success('删除成功',U('Admin/Message/messageList'));
             $data['status'] = 1;  
             $data['info'] = '删除成功';  
             $data['url'] = U('Admin/Message/messageList'); 
             $this->ajaxReturn($data);
        }
    }


   /**
    *消息查看内容后变为已读
    */
    public function read(){ 
      $Message = D('Message');
  
      $user_id = $_SESSION['user']['id']; 
      $res = $list = $Message->messageRead($user_id);
     if (!$res) {
            //   $this->error('数据异常');
         $data['status'] = 0;  
         $data['info'] = '消息已读';  
         $data['url'] = U('Admin/Message/messageList'); 
         $this->ajaxReturn($data);
            }else{
            //   $this->success('删除成功');
         $data['status'] = 1;  
         $data['info'] = '正在读取消息';  
         $data['url'] = U('Admin/Message/messageList'); 
         //调用公共Model里的方法,查看系统消息 积分触发
         $res = D('Trigger')->intergrationTrigger($_SESSION['user']['id'],2);

         $this->ajaxReturn($data);
          } 
     
         }
    



         
   /**
    *话题小组－确认加入/取消加入
    */
    public function topicaudit(){ 
      $Message = D('Message');
      $topicuser_id = I('topicuser_id');
      $correlate_id = I('correlate_id');
      $status = I('status');

      $user_id = $_SESSION['user']['id']; 
      $res  = $Message->topicaudit();




      if (!$res) {
         $data['status'] = 1;  
         $data['info'] = D('Message')->getError(); 
         $data['url'] = U('Admin/Message/messageList'); 
         $this->ajaxReturn($data);
       }else{
            //   $this->success('删除成功');
         $data['status'] = 1;  
         $data['info'] = $res;  
         $data['url'] = U('Admin/Message/messageList'); 
         
         //触发小组系统消息
		     @$res = D('Trigger')->sendTopicMessage($topicuser_id,'',$correlate_id,0,$_SESSION["user"]["id"],1);
         $this->ajaxReturn($data);
         } 
     
         }

         
     /**
    *消息查看内容详情
    */
     public function detail(){ 
         $Message = D('Message');
     
        $user_id = $_SESSION['user']['id']; 
         $res = $list = $Message->messageDetail($user_id);  
         if($res){
	         $title =  $res['title'];
	         $type_name =  $res['type_name'];
	         $contents_time =  $res['contents_time'];
	         $cat_detail =  $res['cat_detail'];
	         $data['status'] = 1;  
	         $data['title'] = $title;  
	         $data['cat_detail'] = $cat_detail; 
	         $data['contents_time'] = $contents_time;  
	         $this->ajaxReturn($data);
         }
     }
     /**
    *消息查看内容详情--话题小组
    */
     public function topicdetail(){ 
         $Message = D('Message');
     
        $user_id = $_SESSION['user']['id']; 
         $res = $list = $Message->topicmessageDetail($user_id);  
         if($res){
         $showStatus = $res['showStatus'];
         $correlate_id = $res['correlate_id'];
         $topicuser_id = $res['topicuser_id'];
         $cat_detail = $res['cat_detail'];
         $topicmessagetitle =  $res['topicmessagetitle'];
         $title =  $res['title'];
         $theme =  $res['theme'];
         $contents_time =  $res['contents_time'];
        
         $data['showStatus'] = $showStatus;
         $data['status'] = 1;  
         $data['correlate_id'] = $correlate_id; 
         $data['topicuser_id'] = $topicuser_id; 
         $data['cat_detail'] = $cat_detail; 
         $data['topicmessagetitle'] = $topicmessagetitle;  
         $data['title'] = $title; 
         $data['theme'] = $theme; 
         $data['contents_time'] = $contents_time;  
         $this->ajaxReturn($data);
         }
     }
      /**
      *  消息通知详情"点击前往"跳转公共方法
      */
      public function entry(){
        $i = I('get.id');
        $data = M('admin_message')->where(array('id'=>$i))->find();
        $this->redirect($data['url']);

      }

      //获取未读消息总数
	public function getUnread(){
		$user_id = $_SESSION['user']['id'];
      	$map = array();
      	$map['user_id'] = $user_id;
      	$map['is_delete'] = "0";
      	$map["status"] = "0";
      	$messages = M('admin_message')->field("count(id) as num")->where($map)->select();
      	echo json_encode($messages[0]["num"]);
	}

//------------------------------------------test-----------------------------//

 public function tt(){
         $data['status'] = 1;  
         $data['title'] = 'biaotihahah';  
         $data['cat_detail'] = '测一测分类la'; 
         $this->ajaxReturn($data);
 }




   /**
    *测试导出Excel
    */
     public function test(){ 
     $data = array(
            array(NULL, 2010, 2011, 2012),
            array('Q1',   12,   15,   21),
            array('Q2',   56,   73,   86),
            array('Q3',   52,   61,   69),
            array('Q4',   30,   32,    0),
           );
   
     $filename='simple.xls';
     if(I('type') == 'excel'){
    create_xls($data,$filename);
     }
        $this->assign('list','输出了hello world');

        $this->display('test');
        
     }

   /**
    *测试导出网页的页面内容，pdf
    */
    public function pdf(){
    //  echo $url=$_SERVER['HTTP_HOST'];
        //  $url=$_SERVER['HTTP_HOST'].'/tpl/Admin/Summary/summary.html';
         $this->assign('list','输出了hello world');  //需要在test模板展示的数据
         $content = $this->fetch('test');  //fetch没有展示模板，只是获取html页面内容
         pdf($content); //使用公共函数方法,pdf文档输出
       }
  
   /**
    *测试导出网页的页面内容，Word
    */
    public function Word(){
         $this->assign('list','输出了hello world');
         $content = $this->fetch('test');  //fetch没有展示模板，只是获取html页面内容

         $fileName = iconv('utf-8', 'GBK', '培训项目总结报告' . '_' . '_' . rand(100, 999));
		 header('Content-Type: application/doc');
		 header('Content-Disposition: attachment; filename=' . $fileName . '.doc');

         getWordDocument($content); //使用公共函数方法,word文档输出
       }



   /**
    *邮箱推送测试
    */
    public function e(){
      // dump($_SESSION['user']);exit;
      // echo U('admin/message/e');
    //  echo $os_name=php_uname();
      //  echo U($url='admin/message/e/sdcvd ',$vars='',$suffix=true,$domain=true); exit;
     $user_id = 1;$title = '你有待学习课程即将开始，信息如下';$contents_time="2017-05-15 11:02:56";$type_id=10;$from_id=1 ;$url="Admin/FriendsCircle/friendsCircleList#c834";
     D('Trigger')->messageTrigger($user_id,$title,$contents_time,$type_id,$from_id,$url);
     exit;


     $address = "651738941@qq.com";
     $title = "你的互动有新的评论/赞，请查看";
     $content = "名称：互动点赞<br />
                 时间：2017-05-15 11:02:56<br /><br /><br />"
                 ."http://peixun.com/index.php/admin/my_exam/lookresultoffline/test_id/31/examination_id/0/flag/flag"
                 ."<br /><br />"
                 ."此邮件由培训平台系统自动发出,请勿回复！";
     $res = send_email($address,$title,$content);
     if($res['error'] == 0){
       echo "发送成功";
      
     }else{
       echo "发送失败";
        dump($res);

     }
    }



   /**
    *测试网页预览pdf--1步
    */

    public function MakePropertyValue($name,$value,$osm){
      $oStruct=$osm->Bridge_GetStruct("com.sun.star.beans.PropertyValue");
      $oStruct->Name = $name;
      $oStruct->Value = $value;
    return $oStruct;
    }


   /**
    *测试网页预览pdf--2步
    */
    public function word2pdf($doc_url, $output_url){
      $osm = new \COM("com.sun.star.ServiceManager") or die ("请确认http://OpenOffice.org库是否已经安装.\n");
      $args = array($this->MakePropertyValue("Hidden",true,$osm));
      $oDesktop = $osm->createInstance("com.sun.star.frame.Desktop");
      $oWriterDoc = $oDesktop->loadComponentFromURL($doc_url,"_blank", 0, $args);
      $export_args = array($this->MakePropertyValue("FilterName","writer_pdf_Export",$osm));
      $oWriterDoc->storeToURL($output_url,$export_args);
      $oWriterDoc->close(true);
      return true;
    }


   /**
    *测试网页预览pdf-
    */
    public function lookpdf(){
        //  phpinfo();die;
      //  $obj = new \COM("com.sun.star.ServiceManager") or die("Unable to instanciate Word");
//       echo 11;
// echo $output_file = "file:///".str_replace("\\","/",getcwd()).'/Upload/test.pdf';die;
    //  echo $url=getcwd(); die;
    //   $output_dir = "D:/temp/";
    //   $doc_file = "D:/temps/test.docx";
    //   // $doc_file = "D:/temps/tt.ppt";
    //   $pdf_file = "test.pdf";
    //   $output_file = $output_dir.$pdf_file;
    //   $doc_file = "file:///".$doc_file;
    //  echo $output_file = "file:///".$output_file;
    //   echo $output_file = "file:///".str_replace("\\","/",getcwd()).'/Upload/test.pdf';
    //  echo $res = word2pdf($doc_file,$output_file); //必须使用绝对路径
  
  
   $doc_file = '/Upload/22.docx'; //源文件路径
   $cnt = file_get_contents('http://v1.occupationedu.com/%E5%9F%B9%E8%AE%AD%E8%AE%A1%E5%88%92.doc');
    file_put_contents('./01.doc', $cnt);
  //  echo 'ok';die;
   $output_file = '/Upload/kejian/'.getRandCode().'.pdf'; //转化后的pdf路径
   $res = word2pdf($doc_file,$output_file); //路径的格式：'/Upload/22.docx';
    if($res){
      echo '转化成功';
    }else{
      echo '转化失败';
    }





          echo $word;
          //打开一个文档 $word->Documents->OPen("F:/wamp/www/oa/111.doc"); 
          //读取文档内容 $test= $word->ActiveDocument->content->Text;


         $this->display('test');
       }

       
   /**
    *测试邮箱发送
    */
    public function emailsend(){
                if(IS_AJAX){

                $email = $data['email'];
                $email = '675283203@qq.com';
                $sendCode = rand(10000, 99999);

                $emailtitle = "培训系统验证码";

                $txt = "您的验证码为<span style='color:#FF0000;font-weight:bold'>" . $sendCode . "</span>,请在30分钟内输入验证码,切勿将信息泄露给其他人";

                $emailcontent = $txt . "<br /><br />" . "此邮件由培训平台系统自动发出,请勿回复！";
                $res = send_email($email, $emailtitle, $emailcontent);
                if($res){
                   $data['status'] = 1;
                   $this->ajaxReturn($data);
                }
                }

               $this->display('test'); 
    }




}