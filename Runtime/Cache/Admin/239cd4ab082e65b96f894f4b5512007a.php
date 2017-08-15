<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>三期项目互动</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="/Public/Dist/css/comm.css">
		<link rel="stylesheet" href="/Public/Css/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/Public/Dist/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="/Public/Dist/ionicons-2.0.1/css/ionicons.min.css">
		<link rel="stylesheet" href="/Public/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
		<link rel="stylesheet"href="/Public/Css/comm.css">
		<link rel="stylesheet" href="/Public/Dist/css/AdminLTE.min.css">
		<link rel="stylesheet" href="/Public/Dist/css/skins/_all-skins.min.css">
		<link rel="stylesheet"href="/Public/plugins/layer/skin/layer.css">
		<link rel="stylesheet"href="/Public/plugins/iCheck/all.css">
        <link rel="stylesheet" href="/Public/Dist/Mycourse.css">
		<style type="text/css">
			.course_img {
				position: relative;
			}
			.ulitem img{
	width: 100%;
}
			.course_time {
				padding: 7px;
				background-color: rgba(0, 0, 0, 0.5);
				z-index: 9999999;
				color: #fff;
				position: absolute;
				right: 0px;
				left: 0px;
				bottom: 0px;
			}
		</style>
	</head>

	<body>
		<!--内容区域顶栏面包屑开始-->
		<section class="content-header">
			<h1 class="info_title">
				        个人中心
				        <small>/项目互动</small>
				    </h1>
			
		</section>
		<!--内容区域顶栏面包屑结束-->
		<section class="content">
			<div class="">
				<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap ">
					<!--内容开始-->
					<div class="nav-tabs-custom   mb10">
						<!--筛选栏开始-->
							
							<!--筛选栏结束-->
						<div class="tab-content">
                            <form action="<?php echo U('Admin/ItemInteraction/index');?>" method="get">
							<div class="row">
								<div class="col-sm-4">
									<div class="input-group width_10 ">
										<input type="text" name="table_search" class="form-control pull-right" value="<?php echo ($keyword); ?>" placeholder="输入项目名称……">
										<div class="input-group-btn">
											<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
										</div>
									</div>
								</div>


							</div>
                            </form>   
					<!--项目互动列表开始-->
								<div class="row mt10">



                                 <?php if(is_array($list)): foreach($list as $key=>$v): ?><div class="col-sm-3 mb20">
										<div class="ulitem">
											<a href="<?php echo U('Admin/ItemInteraction/friendsCircleList',array('project_id'=>$v['id']));?>">
												<div class="course_img">
													<img src="<?php echo ($v["project_covers"]); ?>" onerror="this.src='/Public/Dist/img/class2.png'" />
													<div class="course_time">
                                                    <?php echo ($v["project_name"]); ?>
													</div>
												</div>
											</a>
											<div class="box-title mt5 row">
												<label class="col-sm-6 "> <i class="fa fa-paw mr5 "></i>已有<span class="text-red"><?php echo ($v["interaction_num"]); ?></span>条动态</label>
												<label class="col-sm-6 text-right"><i class="fa fa-user mr5 "></i> <span class="text-red"><?php echo ($v["designated_num"]); ?></span>位同学</label>
											</div>
										</div>
									</div><?php endforeach; endif; ?>


								</div>
								<!--分页开始-->
								<div class="row">
									<div class="float_r mr20">
										<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
											<ul class="pagination">
												<?php echo ($page); ?>
											</ul>
										</div>
									</div>
								</div>
								<!--分页结束	-->
							</div>
					</div>
					<!--内容結束-->
				</div>
			</div>
		</section>
		<script src="/Public/plugins/jQuery/jquery-2.2.3.min.js"></script>
        <script src="/Public/Js/js/bootstrap.min.js"></script>
        <script src="/Public/plugins/fastclick/fastclick.js"></script>
        <script src="/Public/Dist/js/app.min.js"></script>
        <script src="/Public/plugins/sparkline/jquery.sparkline.min.js"></script>
        <script src="/Public/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
        <script src="/Public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <script src="/Public/plugins/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="/Public/plugins/chartjs/Chart.min.js"></script>
        <script src="/Public/Dist/js/pages/dashboard2.js"></script>
        <script src="/Public/plugins/iCheck/icheck.min.js"></script>
        <script src="/Public/Dist/js/demo.js"></script>
        <script src="/Public/plugins/ckeditor/ckeditor.js"></script>
        <script src="/Public/Dist/js/demo.js"></script>
        <script src="/Public/plugins/layer/layer.js"></script>
		<script type="text/javascript">
			//radio选中样式
			$('input').iCheck({
				labelHover: false,
				cursor: true,
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_minimal-blue',
				increaseArea: '20%'
			});

			function del_click() {
				//删除分组
				layer.confirm('您确定要删除吗？', {
					btn: ['确定', '取消'],
					skin: 'layui-layer-lan'
						//按钮
				}, function() {
					layer.msg('删除成功', {
						icon: 1
					});
				}, function() {
					layer.msg('取消删除', {
						time: 2000, //2s后自动关闭
					});
				});
			}
		</script>
	</body>

</html>