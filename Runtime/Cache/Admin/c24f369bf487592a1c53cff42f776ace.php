<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php echo ($location); ?></title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
		<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="/Public/Dist/css/comm.css">
		<link rel="stylesheet" href="/Public/Css/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/Public/Dist/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="/Public/Dist/ionicons-2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="/Public/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
		<!--滚动条样式-->
		<link rel="stylesheet" href="/Public/Css/AdminLTE.min.css">
		<link rel="stylesheet" href="/Public/Css/skins/_all-skins.min.css">
		<link rel="stylesheet" href="/Public/plugins/layer/skin/layer.css">

		<style type="text/css">
			.content-wrapper {
				min-height: 848px;
				background-color: #ffffff;
				z-index: 800;
			}	
			.ceshi {
				width: 100%;
				margin-left: 0px;
			}
			
			.menu-tindex {
				text-indent: 10px;
			}
			
			.menu-tindex li i:first-child {
				text-indent: 1px;
			}
		</style>
	</head>
	<body class="hold-transition skin-blue sidebar-mini" onload="construct(0)">
		<div class="wrapper">

			<!--顶栏区域开始-->
			<header class="main-header">

				<nav class="navbar navbar-static-top">
					<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
						<span class="sr-only">Toggle navigation</span>
					</a>
					<ul class="nav navbar-nav">
						<li class="dropdown user user-menu">
							<a href="77777777" class="dropdown-toggle" data-toggle="dropdown">
								<img src="/Public/Dist/img/logo.png" class="user-image" alt="User Image">
								<span class="hidden-xs mr30"><?php echo ($tissue_name); ?></span>
							</a>

						</li>
					</ul>
					<div class="navbar-custom-menu">
						<ul class="nav navbar-nav">
							 <!-- 首页消息入口-->
							<li class="dropdown messages-menu">
								<a target="main"  href="<?php echo U('Message/messageList');?>" class="dropdown-toggle" >
								<i class="fa fa-envelope-o"></i>
								<span class="label label-success" id="unReadMsg"><?php echo ($messagesTotal); ?></span>
								</a>
							</li>
							<li class="dropdown  messages-menu">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
									管理面板<span class="caret"></span>
								</a>
								<ul class="dropdown-menu panel_select" id="manage_panel">
									<?php if(is_array($getGroups)): foreach($getGroups as $key=>$vo): if($vo["group_id"] == 1): ?><li role="presentation">
												<a role="menuitem" tabindex="-1" href="<?php echo U('index_admin/index');?>" alt="<?php echo ($vo["group_id"]); ?>" onclick="construct(<?php echo ($vo["group_id"]); ?>)">培训主管</a>
											</li>
											<?php elseif($vo["group_id"] == 2): ?>
											<li role="presentation">
												<a role="menuitem" tabindex="-1" href="<?php echo U('Index/indexLecturer');?>" alt="<?php echo ($vo["group_id"]); ?>" onclick="construct(<?php echo ($vo["group_id"]); ?>)">讲师面板</a>
											</li>
											<?php elseif($vo["group_id"] == 3): ?>
											<li role="presentation">
												<a role="menuitem" tabindex="-1" href="<?php echo U('Index/indexStudent');?>" alt="<?php echo ($vo["group_id"]); ?>" onclick="construct(<?php echo ($vo["group_id"]); ?>)">学员面板</a>
											</li>
											<?php else: ?>
											<li role="presentation">
												<a role="menuitem" tabindex="-1" href="<?php echo U('Index/indexStudent');?>" alt="<?php echo ($vo["group_id"]); ?>" onclick="construct(<?php echo ($vo["group_id"]); ?>)"><?php echo (msubstr($vo["title"],0,8,'utf-8',false)); ?></a>
											</li><?php endif; endforeach; endif; ?>
								</ul>
							</li>

							<li class="dropdown notifications-menu">
								<a href="#" onclick="onclick_url('/admin/help/index',this)" class="dropdown-toggle" data-toggle="dropdown">
									<span class="hidden-xs">帮助</span>
									<i class="fa fa-question-circle"></i>
								</a>
							</li>
							<li class="dropdown notifications-menu">
								<a href="<?php echo U('Index/logout');?>">
									<span class="hidden-xs">退出</span>
									<i class="fa fa-sign-in"></i>
								</a>
							</li>
						</ul>
					</div>

				</nav>
			</header>
			<!--顶栏结束-->
			<!--左侧菜单开始-->
			<aside class="main-sidebar">
				<!--用户信息开始-->
				<ul class="user_box_lg">
					<li class="user-header">
						<a href="javascript:void(0)" onclick="onclick_url('/index.php/admin/info/infopage')">
							<img style="width:80px; height:80px; " src="<?php echo ($_SESSION['user']['avatar']); ?>" class="img-circle" alt="User Image" onerror="this.src='/Public/Dist/img/user8-128x128.jpg'">
						</a>
						<p class="user_name">
							<?php echo ($_SESSION['user']['username']); ?>|<?php echo ($_SESSION['user']['type']); ?>
						</p>

						<p>
							<small><?php echo ($tissue_name); ?></small>
						</p>
					</li>
				</ul>
				<ul class="user_box_mini">
					<!-- User image -->
					<li class="user-header">
						<img src="<?php echo ($_SESSION['user']['avatar']); ?>" class="img-circle" alt="User Image" onerror="this.src='/Public/Dist/img/user8-128x128.jpg'">
					</li>
				</ul>
				<!--用户信息结束-->
				<!--用户信息详情开始-->

				<!--用户信息详情结束-->
				<!--左侧菜单开始 -->
				<section class="sidebar">
					<ul class="sidebar-menu">

					</ul>
				</section>
				<!--左侧菜单结束 -->
			</aside>
			<!--消息弹出窗结束-->
			<!--中心主要切换区域开始-->
			<div class="content-wrapper" id="yichu">
				<!--内容区域顶栏面包屑开始-->
				<iframe class="content-wrapper ceshi" name="main" id="Main" src="/index.php/admin/<?php echo ($home); ?>"></iframe>

				<!--内容区域底栏版本信息开始-->
				<footer class="main-footer">
					<strong>Copyright &copy; 2014-2016 <a href="#">深圳典阅科技有限公司</a>.</strong> All rights <b>版本</b> 1.0.0 reserved.
				</footer>
				<!--内容区域底栏版本信息结束-->
				<!--内容区域顶栏面包屑结束-->
			</div>
			<!--中心主要切换区域结束-->

			<!--更改密码弹出窗-->
			<div id="password" style="display: none;">
				<div class="mt20 ml20">
					<span class="color_red">*</span>设置新密码：
					<input class="form-input ml5" type="text" id="newpassword" name="newpassword" value="" style="width: 260px;" />
					<!--<input type="text">-->
				</div>
			</div>

		</div>

		<script src="/Public/plugins/jQuery/jquery-2.2.3.min.js"></script>
		<script src="/Public/Js/js/bootstrap.min.js"></script>
		<script src="/Public/plugins/fastclick/fastclick.js"></script>
		<script src="/Public/Dist/js/app.min.js"></script>
		<script src="/Public/plugins/sparkline/jquery.sparkline.min.js"></script>
		<script src="/Public/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
		<script src="/Public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
		<script src="/Public/plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<script src="/Public/plugins/chartjs/Chart.min.js"></script>
		<script src="/Public/Dist/js/demo.js"></script>
		<script src="/Public/plugins/layer/layer.js"></script>

		<script type="text/javascript">
			setInterval(function(){
				$.ajax({
					type: "get",
					url: "/index.php/admin/message/getUnread?random="+Math.random(),
					dataType: "json",
					success: function(data){
						$("#unReadMsg").html(data);
					}
				});
			}, 10000);

			//   首页加载时判断是否"忘记密码"入口，若是则弹出蒙层修改密码
			(function($) {
				var i = "<?php echo I('forgetType');?>";
				// alert(i);
				if(i == 1) {
					password_alter();
					//禁止浏览器的返回按钮
					var counter = 0;
					if(window.history && window.history.pushState) {
						$(window).on('popstate', function() {
							window.history.pushState('forward', null, '#');
							window.history.forward(1);
							//$("#label").html("第" + (++counter) + "次单击后退按钮。");
						});
					}
					window.history.pushState('forward', null, '#'); //在IE中必须得有这两行
					window.history.forward(1);
				}
				//   javascript:window.history.forward(1); 
				//禁止浏览器的返回按钮
				var counter = 0;
				if(window.history && window.history.pushState) {
					$(window).on('popstate', function() {
						window.history.pushState('forward', null, '#');
						window.history.forward(1);
						//$("#label").html("第" + (++counter) + "次单击后退按钮。");
					});
				}
				window.history.pushState('forward', null, '#'); //在IE中必须得有这两行
				window.history.forward(1);
			})(jQuery);

			//更改密码弹窗js
			function password_alter() {
				layer.confirm('', {
					title: '更改密码',
					btn: ['确认更改'],
					area: ['400px', '180px'],
					type: 1,
					skin: 'layui-layer-lan', //样式类名
					closeBtn: false, //显示关闭按钮
					anim: 2,
					shadeClose: false, //开启遮罩关闭
					content: $("#password").html(),
					yes: function(index, layerElement) {
						var newpassword = $(layerElement).find("#newpassword").val().trim();
						//    alert(newpassword);exit;
						if(newpassword == '') {
							layer.msg('密码不能为空', {
								time: 1000,
								icon: 2
							});
						} else {
							$.post("<?php echo U('Home/Index/newpasswordSave');?>", {
									'newpassword': newpassword
								},
								function($data) {
									if($data.code == 1) {
										layer.close(index); //关闭蒙层
										layer.msg($data.message, {
											time: 1000,
											icon: 1
										});
										location.href = "<?php echo U('Admin/Index/index');?>";
									} else {
										layer.msg($data.message, {
											time: 1000,
											icon: 2
										});
									}

								}, 'json');

						}
					}
				});
			}

			function construct(group_id) {

				if(group_id > 0){
					$(".messages-menu").attr("class","dropdown  messages-menu");
				}

				$.ajax({
					type: "POST",
					url: "<?php echo U('Index/leftMenu');?>",
					data: "group_id=" + group_id,
					success: function(html) {

						$(".sidebar-menu").html(html);
						
					 
					}
				});
			}

			function onclick_url(url,obj) {
				 $("li.treeview").each(function(){
					   $(this).find("li").removeClass("active")  //清除treeview下的所有li标签active样式
					 });
					$(obj).parent().addClass("active")//给当前所点击的li加active样式
				$("#Main").attr("src", url);
			};

			(function($) {
				$(window).load(function() {
					$(".sidebar").mCustomScrollbar();

				});
			})(jQuery);

			$("#manage_panel").on("click", "li a", function(e) {
				var src = $(this).attr("href");
				$("#Main").attr("src", src);
				e.preventDefault();
				return false;
			});
		</script>
	</body>

</html>