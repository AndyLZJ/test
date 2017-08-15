<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>login</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="/Public/Css/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <!--  登录页面样式表-->
    <link rel="stylesheet" href="/Public/Css/NEWlogin.css">
</head>

<body class="hold-transition login-page login-bg" onkeydown="keyDown(event);">
<div class="body_bg"> <img src="/Public/Dist/img/logbj.png" /></div>
<div class="login-box" >
    <div class="login_header">
        <span class="login_title"></span>
    </div>
    <div class="login_body" >
        <!--登录开始-->
     
            <!--<div>
                <ul class="label">
                    <li><a <?php if($typeid == 1): ?>class="active"<?php endif; ?> id="tab1" href="<?php echo U('Home/index/index',array('typeid'=>1));?>">密码登录</a></li>-->
                    <!--<li><a <?php if($typeid == 2): ?>class="active"<?php endif; ?> id="tab2" href="<?php echo U('Home/index/index',array('typeid'=>2));?>">注册</a></li>-->
                    <!--<li><a <?php if($typeid == 4): ?>class="active"<?php endif; ?> id="tab2" href="<?php echo U('Home/index/index',array('typeid'=>4));?>">扫码登录</a></li>
                </ul>
            </div>-->
            

            <?php if($typeid == 1): ?><div class="login_body_right" id="login_div" >
                <div>
                <ul class="label">
                    <li><a <?php if($typeid == 1): ?>class="active"<?php endif; ?> id="tab1" href="<?php echo U('Home/index/index',array('typeid'=>1));?>">密码登录</a></li>
                    <!--<li><a <?php if($typeid == 2): ?>class="active"<?php endif; ?> id="tab2" href="<?php echo U('Home/index/index',array('typeid'=>2));?>">注册</a></li>-->
                    <li><a <?php if($typeid == 4): ?>class="active"<?php endif; ?> id="tab2" href="<?php echo U('Home/index/index',array('typeid'=>4));?>">扫码登录</a></li>
                </ul>
            </div>
                <div id="login1_content" class="tab_css" >

                    <form action="<?php echo U('Home/index/index');?>" method="post" >
                        <div class="info" id="ID">
                            <div class="info_box"><input class="user" type="text" autocomplete="off" name="username" placeholder="请输入登录账号">
                            </div>
                            <div class="info_box"><input type="password" class="password"autocomplete="off" name="password" placeholder="请输入登录密码"></div>
                            <div class="info_box">
                                <input autocomplete="off" class="code_inp float_l yanzheng" type="text" name="code" placeholder="请输入右侧验证码">
                                <img class="code code_img float_l" src="<?php echo U('Home/Public/verify');?>" alt="" title="点击更换验证码" id="verify_img"/>
                                <a class="refresh" id="refresh"><img src="/Public/img/newshuaxin.png" /><i class="fa fa-refresh mr5"></i>刷新</a>
                            </div>
                            <!--<div class="info_box" id="info_box"><a class="button text-center" href="#">立即登录</a></div>-->
                            <div class="info_box" >
								<span style="color: #a7a7a7;margin-left: -40px;font-size: 16px;">还没有账号？	
					           <a  style="color:#ac9456 ;" href="<?php echo U('Home/index/index',array('typeid'=>2));?>">马上注册</a>
					         <span style="color:#313548;margin-left: 10px;">▏</span>
								<a style="color:#ac9456 ;" href="<?php echo U('Home/index/index',array('typeid'=>3));?>">忘记密码</a>
								</span>
								<span id="info_box"><a class="button text-center" href="#">登录</a></span>
							</div>
                            <!--<div class="info_box">
                                <a href="<?php echo U('Home/index/index',array('typeid'=>3));?>" id="login1">
                                    <i class="fa fa-key mr5"></i>忘记密码
                                </a>
                            </div>-->
                            <!--<div class="info_box">
			    				<a onclick="registered(); href="<?php echo U('Home/index/index',array('typeid'=>2));?>"  class="registered">
			    				   <i class="fa fa-mobile mr5" ></i>新用户注册
			    				</a>
                                <a onclick="registered();" href="javascript:void(0)" class="registered">
			    				   <i class="fa fa-mobile mr5"></i>新用户注册
			    				</a>
			    				<a onclick="mobile();" href="<?php echo U('Home/index/index',array('typeid'=>3));?>" class="float_r">
			    				   忘记密码<i class="fa fa-key ml5 mr10"></i>
			    				</a>
			    			</div>-->
                        </div>
                    </form>
                </div>

                <?php elseif($typeid == 2): ?>
               
                   	<div id="registered_div" style=" width: 680px;margin: auto;">
		    	        <div class="sign_in_title">
				    		注册
				    	</div>
                  
                 
                        <div style="float: left;">
                            <div class="info_box user"><input tabindex="1"  autocomplete="off" type="text" name="usernames" placeholder="请输入姓名" value=""></div>
                            <div class="info_box">
                               <input tabindex="3" class="float_l code_inp2 yanzheng" type="text" name="mobilecodes" placeholder="请输入验证码" value="">
                               <a id="btn" alt="0" tabindex="-1" autocomplete="off" value="获取验证码" onclick="settime()" href="#" class="code text-center float_l"><span id="code">获取验证码</span></a>
                            </div>
                            <div class="info_box password"><input tabindex="5" autocomplete="off" type="password" name="confirmpassword" placeholder="请再次确认密码" value=""></div>
                            
                            
                            <!--<div class="info_box card"><input tabindex="8" autocomplete="off" type="text" name="number" placeholder="请输入工号（非必填）" value=""></div>-->
                        </div>
                       
                       <div style="float: right;">
                                  <!--<div class="info_box">
                                    <select tabindex="2" name="province" id="province">
                                        <option value="">公司所在地*</option>
                                        <?php if(is_array($province)): foreach($province as $k=>$v): ?><option value="<?php echo ($v["name"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>                                   
                                    </select>
                                    <select tabindex="3"  name="city" id="city">
                                                                
                                    </select>
                                  </div>-->
                             <!--<div class="info_box mobile"><input tabindex="2" autocomplete="off" type="text" name="mobiles" placeholder="请输入手机号" value=""></div>-->
                             <div class="info_box mobile"><input tabindex="2" autocomplete="off" type="text" name="email" placeholder="请输入邮箱" value=""></div>
                             <div class="info_box password"><input tabindex="4"  autocomplete="off" type="password" name="passwords" placeholder="请设置登录密码" value=""></div>    
                                <div>
                                    <span style="color: #a7a7a7;font-size: 16px;">已有账号？	
                                    <a  style="color:#ac9456 ;" href="<?php echo U('Home/index/index',array('typeid'=>1));?>">返回登录</a>
                                        </span>
                                    <a style="margin-right: 0px;" onclick="submit(this);"  class="button text-center" href="#">注册</a>
                                   
                                </div>
                            </div>
		    		 
		    	</div>
                <!--注册成功提示-->
				<div id="ZCsuccess" style="display: none; width: 420px;margin: auto;">
					<img src="/Public/Dist/img/successimg.png" />
                    <div id="ZCsuccesstime" style="height:100px;line-height:100px;text-align:center;font-size:30px;color:#FFFFFF;letter-spacing:8px">3s</div>
				</div>


                <?php elseif($typeid == 3): ?>
                

                 <div class="login_body_right" id="froget_password" >
					<div class="sign_in_title">
						找回密码
					</div>
                <div class="info"  >
                    <!--忘记密码部分-->
                    <!--<div class="info_box"><input autocomplete="off" class="mobile" type="text" name="mobiles" placeholder="请输入手机号" value=""></div>-->
                     <div class="info_box"><input autocomplete="off" class="mobile" type="text" name="email" placeholder="请输入登录邮箱" value=""></div>
                    <div class="info_box">
                        <input class="float_l code_inp2 yanzheng" autocomplete="off" type="text" name="emailcode" placeholder="请输入验证码" value="">
                        <a href="#" class="code text-center float_l" id="btn" alt="0" type="button" value="获取验证码" onclick="settime()"><span id="code">获取验证码</span></a>
                    </div>
                     <div class="info_box">
                        <input autocomplete="off" class="code_inp float_l yanzheng" type="text" name="codes" placeholder="请输入右侧验证码">
                        <img class="code code_img float_l" src="<?php echo U('Home/Public/verify');?>" alt="" title="点击更换验证码"
                                id="verify_img"/>
                        <a class="refresh" id="refresh"><img src="/Public/img/newshuaxin.png" /><i class="fa fa-refresh mr5"></i>刷新</a>
                    </div>
                    <!--<div class="info_box"><a onclick="mobilelogin()" class="button text-center" href="#" >立即登录</a></div>
                    <div class="info_box">
                        <a href="<?php echo U('Home/index/index',array('typeid'=>1));?>">
                            <i class="fa fa-user mr5"></i>账号密码登录
                        </a>
                    </div>-->

                    <div  class="info_box">
							<span style="color: #a7a7a7;font-size: 16px;">已有账号？	
					           <a  style="color:#ac9456 ;" href="<?php echo U('Home/index/index',array('typeid'=>1));?>">返回登录</a>
								</span>
							<a onclick="emaillogin()" class="button text-center" href="#">登录</a>
					</div>
                </div>

               </div>
               <?php elseif($typeid == 4): ?>
                  <div class="login_body_right" id="login_div" >
               <div>
                <ul class="label">
                    <li><a <?php if($typeid == 1): ?>class="active"<?php endif; ?> id="tab1" href="<?php echo U('Home/index/index',array('typeid'=>1));?>">密码登录</a></li>
                    <!--<li><a <?php if($typeid == 2): ?>class="active"<?php endif; ?> id="tab2" href="<?php echo U('Home/index/index',array('typeid'=>2));?>">注册</a></li>-->
                    <li><a <?php if($typeid == 4): ?>class="active"<?php endif; ?> id="tab2" href="<?php echo U('Home/index/index',array('typeid'=>4));?>">扫码登录</a></li>
                </ul>
            </div>
        <!--登录结束-->
        <!--扫码开始-->
                      <!--<div class="col-sm-3">
							<label class="text-aqua mt10 mr30">
                                    <img class="width_10" src="<?php echo U('Home/index/qrcodes');?>"/>	
						</div>-->
                        <!--<div id="login2_content" class="info tab_css" style="display: 1;">
		    			<img src="/Public/Dist/img/qrcode.png"  width="100%" />
		    		</div>-->
		    		<div id="login2_content" class="info tab_css" style="display: 1;">
		    			<img id="refreshCode_img" src="<?php echo U('Home/index/qrcodes');?>" alt="" width="100%" />
		    		</div>
                    <div class="ewmbottom">
							<span style="color: #a7a7a7;margin-left: -40px;font-size: 16px;">还没有账号？	
					           <a  style="color:#ac9456 ;" href="<?php echo U('Home/index/index',array('typeid'=>2));?>">马上注册</a>
					         <span style="color:#313548;margin-left: 10px;">▏</span>
							<span style="color:#ffffff;">扫描二维码登录</span>
							<a href="#" id="refreshCode" style="color: #ac9456;float: right;"><img src="/Public/img/newshuaxin.png" /> 刷新</a>
							</span>
					</div>
		<!--扫码结束--><?php endif; ?>
        </div>
             

	
		  </div>  
        <div class="login_footer text-center">
            <!--版权所有 深圳典阅科技有限公司 Copyright©2016 occupationedu.com All Rights Reserved. 服务热线：400-9600-062-->
        </div>
    </div>
  </div>
<!-- jQuery 2.2.3 -->
<script src="/Public/Js/jQuery/jquery-2.2.3.min.js"></script>
<script src="/Public/Js/js/bootstrap.min.js"></script>
<script src="/Public/layer-v3.0.1/layer/layer.js"></script>
<script type="text/javascript">


    $(function(){ 
    freshCode(); //此处避免退出后，原来的qrcode还保存，再次重新登录
        //选择省份触发
    getSelectVal(); 
    $("#province").change(function(){ 
        getSelectVal(); 
      }); 
      var i = '<?php echo I('typeid');?>'; 
      if(i==4){
      //触发二维码与数据库字段校验函数，1s触发一次
      setInterval('checkThereFresh()',1000);      // 时间每溢出1次则执行1次事件t(); 
      //60秒后重新加载页面，自动刷新二维码
      setTimeout('document.location.reload()',60000); 
      }
   });  
   
   //加载时，重新刷新登录二维码
    function freshCode(){ 
//        $.post("<?php echo U('Home/Index/qrcodes');?>",{}, function($data) {
//       },'json');
        var verifyURL = "<?php echo U('Home/index/qrcodes');?>"+"?t="+Math.random();;
        var time = new Date().getTime();
        $("#refreshCode_img").attr({
            "src": verifyURL
        });
      
    }
   //登录二维码的校验
   function checkThereFresh(){ 
    $.post("<?php echo U('Home/Index/scanCode');?>",{}, function($data) {  
        // alert($data); exit;
                    if($data){
                       location.href = "<?php echo U('Home/Index/index',array());?>";
                    }  
   
                     },'json');     
   }

   //选择城市的二级联动
   function getSelectVal(){ 
  $.post('<?php echo U('Home/Index/Linkage');?>',{province:$("#province").val()}, function($data) {  
                          var city = $("#city"); 
                          $("option",city).remove(); //清空原有的选项 
                          city.append('<option  value="">所在城市*</option>');   
                          $.each($data, function(index, value) {
                         var option = "<option value='"+value['name']+"'>"+value['name']+"</option>"; 
                          city.append(option);    
                         });
                          
                           },'json'); 
     }
    
   //登录页验证码刷新
    $("#verify_img").click(function () {
        var verifyURL = "<?php echo U('Home/Public/verify');?>"+"?t="+Math.random();
        var time = new Date().getTime();
        $("#verify_img").attr('src',verifyURL);
    });

    //忘记密码页验证码刷新
    $("#refresh").click(function () {

        var verifyURL = "<?php echo U('Home/Public/verify');?>"+"?t="+Math.random();;
        var time = new Date().getTime();
        $("#verify_img").attr('src',verifyURL);
    });
     
    //刷新验证码函数
     function refreshVerify(){
        var verifyURL = "<?php echo U('Home/Public/verify');?>"+"?t="+Math.random();;
        var time = new Date().getTime();
        $("#verify_img").attr({
            "src": verifyURL
        });
     }

    //点击刷新二维码
     $("#refreshCode").click(function () {
        var verifyURL = "<?php echo U('Home/index/qrcodes');?>"+"?t="+Math.random();;
         var time = new Date().getTime();
         $("#refreshCode_img").attr({
             "src": verifyURL
         });
    });



     
    $("#info_box a").click(function () {
    	
        var username = $("input[name='username']").val().trim();
        var password = $("input[name='password']").val().trim();
        var code = $("input[name='code']").val().trim();
        if (username == "") {
            layer.alert("登录账号不能为空");
            refreshVerify();
            return false;
        }
        if (password == "") {
            layer.alert("密码不能为空");
            refreshVerify();
            return false;
        }
        if (code == "") {
        	
            layer.alert("验证码不能为空");
            refreshVerify();
            
            return false;
        }
        if (password.length < 3 || username.length < 3) {
            refreshVerify();
            layer.alert("用户名或密码长度不能小于3位");
        } else {
            var url = "<?php echo U('Home/Index/index',array());?>";
            var data = {"username": username, "password": password, "code": code}
            $.ajax({
                type: "post",
                data: data,
                url: url,
                dataType: 'json',
                success: function (data, textStatus) {
                    if (data.code == 3) {
                        location.href = "<?php echo U('Home/Index/index',array());?>";
                    } else {
                        refreshVerify();
                        layer.alert(data.message);
                    }
                }
            });
        }
    });

    // var myreg = /(^1[34578]\d{9})$/;

    // function settime() {

    //     var alt = $("#btn").attr("alt");

    //     if (alt == 0) {

    //         // $("#btn").attr("alt", 1);

    //         var countdown = 60;

    //         var mobile = $("input[name='mobiles']").val().trim();

    //         if (mobile == '' || !(myreg.test(mobile))) {
    //             refreshVerify();
    //             layer.alert("手机号码有误或格式错误");
    //             return false;
    //         } else {
    //              $("#btn").attr("alt", 1);
    //             var url = "<?php echo U('Home/Public/sendMessage');?>";
    //             var data = {"mobile": mobile};
    //             $.ajax({
    //                 type: "post",
    //                 data: data,
    //                 url: url,
    //                 dataType: 'json',
    //                 success: function (data) {
    //                     if (data.code == 3) {
    //                         location.href = "<?php echo U('Home/Index/index',array());?>";
    //                     } else {
    //                         refreshVerify();
    //                         layer.alert(data.message);
    //                     }
    //                 }
    //             });

    //             runCount(countdown);

    //         }
    //     }
    // }


    var myreg =  /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/;
    function settime() {

        var alt = $("#btn").attr("alt");

        if (alt == 0) {

            // $("#btn").attr("alt", 1);

            var countdown = 60;

            var email = $("input[name='email']").val().trim();

            if (email == '' || !(myreg.test(email))) {
                refreshVerify();
                layer.alert("邮箱有误或格式错误");
                return false;
            } else {
                 $("#btn").attr("alt", 1);
                var url = "<?php echo U('Home/Public/sendEmailMessage');?>";
                var data = {"email": email};
                $.ajax({
                    type: "post",
                    data: data,
                    url: url,
                    dataType: 'json',
                    success: function (data) {
                        if (data.code == 3) {
                            location.href = "<?php echo U('Home/Index/index',array());?>";
                        } else {
                            refreshVerify();
                            layer.alert(data.message);
                        }
                    }
                });

                runCount(countdown);

            }
        }
    }



    function runCount(countdown){

        setTimeout(function () {

            if(countdown == 1){
                $("#code").html("重新发送");
                $("#btn").attr("alt",0);
            }else{
                countdown--;
                $("#code").html("重新发送("+countdown + ")");
                runCount(countdown);
            }
        },1000)

    }
    //注册成功后的计时器
    function ZCrunCount(countdown){

        setTimeout(function () {
            if(countdown == 0){
                $("#ZCsuccesstime").html("0s");
                
            }else{
                countdown--;
                $("#ZCsuccesstime").html(countdown + "s");
                ZCrunCount(countdown);
            }
        },1000);

    }

    function mobilelogin() {
        var mobile = $("input[name='mobiles']").val();
        var code = $("input[name='mobilecode']").val();
        var codes = $("input[name='codes']").val();
        if (!(myreg.test(mobile))) {
            refreshVerify();
            layer.alert("手机号码有误，请重填");
            return;
        }

        if (code == "") {
            refreshVerify();
            layer.alert("手机验证码不能为空");
            return;
        }
        if (codes == "") {
            refreshVerify();
            layer.alert("图片验证码不能为空");
            return;
        }
        var url = "<?php echo U('Home/Index/checkcode');?>";
        var data = {"mobile": mobile, "code": code,"codes": codes};
        $.ajax({
            type: "post",
            data: data,
            url: url,
            dataType: 'json',
            success: function (data) {
                if (data.code == 3) {
                    location.href = "<?php echo U('Admin/Index',array('forgetType'=>1));?>";
                } else {
                    refreshVerify();
                    layer.alert(data.message);
                }
            }

        });

    }


    function emaillogin(){
        var email = $("input[name='email']").val();
        var emailcode = $("input[name='emailcode']").val();
        var codes = $("input[name='codes']").val();
        if (!(myreg.test(email))) {
            refreshVerify();
            layer.alert("输入邮箱有误，请重填");
            return;
        }

        if (emailcode == "") {
            refreshVerify();
            layer.alert("邮箱验证码不能为空");
            return;
        }
        if (codes == "") {
            refreshVerify();
            layer.alert("图片验证码不能为空");
            return;
        }
        var url = "<?php echo U('Home/Index/emaillogincheckcode');?>";
        var data = {"email": email, "emailcode": emailcode,"codes": codes};
        $.ajax({
            type: "post",
            data: data,
            url: url,
            dataType: 'json',
            success: function (data) {
                if (data.code == 3) {
                    location.href = "<?php echo U('Admin/Index/index',array('forgetType'=>1));?>";
                } else {
                    refreshVerify();
                    layer.alert(data.message);
                }
            }

        });

    }



    function submit(obj) { 
        var username = $("input[name='usernames']").val().trim();
        // var province = $("#province").val().trim();
        // var city = $("#city").val().trim();
        // layer.alert(city);exit;

        // var mobile = $("input[name='mobiles']").val().trim(); 手机号码注册
        // var code = $("input[name='mobilecodes']").val().trim();

        var email = $("input[name='email']").val().trim(); //邮箱注册
        var code = $("input[name='mobilecodes']").val().trim();

        var password = $("input[name='passwords']").val().trim();
        var confirmpassword = $("input[name='confirmpassword']").val().trim();
        // var number = $("input[name='number']").val().trim();
        if (username.length <= 1 ) {
            layer.alert("用户名小于2位");
            return;
        // }else if( province == ""){
        //     layer.alert("所在省份不能为空");
        //     return;
        // }else if( city == ""){
        //     layer.alert("所在城市不能为空");
        //     return;
        }else if (!(myreg.test(email))) {
            layer.alert("邮箱有误，请重填");
            return;
        }else if (code == "") {
            layer.alert("验证码不能为空");
            return;
        }else if( password.length <= 5 ||  password.length > 20){
            layer.alert("密码长度应为6-20位");
            return;
        }

        
        if (password === confirmpassword) {
        } else {
            layer.alert("密码和确认密码不一致");
            return;
        }
        
        var url = "<?php echo U('Home/Register/emailSignup');?>";
        var data = {"username": username,"email": email, "code": code, "password": password};
        $.ajax({
            type: "post",
            data: data,
            url: url,
            dataType: 'json',
            success: function (data) {

                if (data.code == 3) {
                    // layer.alert(data.message);
                    ZCsuccess();
                    setTimeout(function(){
                      location.href = "<?php echo U('Home/Index/index',array('typeid'=>1));?>";
                     },3000);   
                    
                } else {
                    layer.alert(data.message);
                }
            }
        });
    }

var allnum=0;
    //执行键盘按键命令
    function keyDown(e){
        var keycode = 0;
        //IE浏览器
        if(CheckBrowserIsIE()){
            keycode = event.keyCode;
        }else{
            //火狐浏览器
            keycode = e.which;
        }
        if (keycode == 13 ) //回车键是13
        {
            // submit();
            if(allnum==0){
            	$('.button').click();
            	allnum=1;
            }
            else{
            	
            	 layer.closeAll();//关闭所有弹出框
            	 allnum=0;
            }
            
        }
    }
    //判断访问者的浏览器是否是IE
    function CheckBrowserIsIE(){
        var result = false;
        var browser = navigator.appName;
        if(browser == "Microsoft Internet Explorer"){
            result = true;
        }
        return result;
    }


     //登录注册切换

			function sign_in()
			{
				$("#login_div").show();
				$("#registered_div").hide();
			}

			function registered()
			{
				$("#login_div").hide();
				$("#registered_div").show();
			}
        function ZCsuccess() { //返回登录
				$("#login_div").hide();
				$("#registered_div").hide();
				$("#froget_password").hide();
				$("#ZCsuccess").show();
                ZCrunCount(3);

			}

</script>

</body>
</html>