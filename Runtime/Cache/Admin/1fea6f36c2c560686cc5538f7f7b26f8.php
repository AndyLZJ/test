<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>积分中心</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

		 <link rel="stylesheet" href="/Public/plugins/layer/skin/layer.css">
	     <link rel="stylesheet" href="/Public/Dist/css/comm.css">
         <link rel="stylesheet" href="/Public/plugins/iCheck/all.css">
         <link rel="stylesheet" href="/Public/Css/bootstrap/css/bootstrap.min.css">
         <link rel="stylesheet" href="/Public/Dist/font-awesome-4.7.0/css/font-awesome.min.css">
         <link rel="stylesheet" href="/Public/Dist/ionicons-2.0.1/css/ionicons.min.css">
         <link rel="stylesheet" href="/Public/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
         <link rel="stylesheet" href="/Public/Dist/css/AdminLTE.min.css">
         <link rel="stylesheet" href="/Public/Dist/css/skins/_all-skins.min.css">
		
	  		
	</head>
	<body>
		<!--内容区域顶栏面包屑开始-->
		<section class="content-header">
			<h1 class="info_title">
        积分中心
        <small>/福利管理</small>
     </h1>
			
		</section>
		<!--内容区域顶栏面包屑结束-->
		<section class="content">
		  <div>
		    <div class="dataTables_wrapper form-inline dt-bootstrap ">
				<div class="nav-tabs-custom">
					<ul id="myTab" class="nav nav-tabs">
						<li>
							<a href="#manage" name="tab1" data-toggle="">福利管理</a>
						</li>
						<li>
							<a href="#exchange" name="tab2" data-toggle="">兑换历史</a>
						</li>
						<!--考试筛选开始-->
						
						<!--考试筛选结束-->
					</ul>
					<div class="tab-content">
						<div class="tab-pane " id="manage">
							<!--课程列表开始-->
							<div class="box-body">
								<div id="example1_wrapper" class="">
                                    <div class="">
										 	<form action="<?php echo U('Admin/Integration/welfareList');?>" method="get">
                                        <div class="input-group float_l mb10">
											<input type="text" name="table_search" class="form-control pull-right" value="<?php echo ($condition); ?>" style="width:250px;" placeholder="请输入福利名称搜索">
											<div class="input-group-btn">
												<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
											</div>
										</div>
                                            </form>
										<a class="btn btn-primary float_r mb10" href="<?php echo U('Admin/Integration/addWelfare');?>">
										    <i class="fa fa-plus mr5"></i>新增
										</a>
									</div>
									<!--内容开始-->
									<div class="row">
										<div class="col-sm-12">
											<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
												<thead>
													<tr role="row">
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" width="50">序号</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">福利名称</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">所需积分</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">新增时间</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">更新时间</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">启用</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">操作</th>
													</tr>
												</thead>
												<tbody id="tab">


													<?php if(is_array($list)): foreach($list as $key=>$v): ?><tr role="row" class="odd text-center">
														<td><?php echo ($key+1); ?></td>
														<td><?php echo ($v["name"]); ?></td>
														<td><?php echo ($v["need_score"]); ?></td>
														<td><?php echo date('Y-m-d H:i:s',$v['add_time']);?></td>
														
														<td><?php echo date('Y-m-d H:i:s',$v['update_time']);?></td>
														
														<td>
															<?php if($v['is_public'] == 1): ?><span href="#" class="color_gree ">
														       <i class="fa  fa-check mr5" aria-hidden="true"></i>
														    </span><?php endif; ?>
														</td>
														<td>
															<a class="bs-callout-info mr15" href="<?php echo U('Admin/Integration/editWelfare',array('id'=>$v['id']));?>">
			                                                    <i class="fa fa-pencil mr5" aria-hidden="true"></i>编辑
			                                                </a>
															<a href="#" class="bs-callout-info color_or" onclick="del_click(<?php echo ($v["id"]); ?>);">
			                                                    <i class="fa fa-trash-o mr5" aria-hidden="true"></i>删除
			                                                </a>
														</td>
													</tr><?php endforeach; endif; ?>





													
												</tbody>
											</table>
										</div>
									</div>
									<!--内容結束-->
									<!--分页开始-->
									<div class="row">
										<div class="float_r mr15">
											<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
												<?php echo ($page1); ?>
												
											</div>
										</div>
									</div>
									<!--分页结束-->
								</div>
							</div>
							<!--课程列表结束-->
						</div>
						<div class="tab-pane " id="exchange">
							<!--课程审核开始-->
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
									<!--内容开始-->
									<div class="row">
										<div class="col-sm-12">
											<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
												<thead>
													<tr role="row">
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">兑换时间</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">福利名称</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">兑换人</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">所在部门</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">消耗积分</th>
													</tr>
												</thead>
												<tbody id="tab">
													<?php if(is_array($historylist)): foreach($historylist as $k=>$v): ?><tr role="row" class="odd text-center">
														<td><?php echo date('Y-m-d H:i:s',$v['time']);?></td>
														<td><?php echo ($v["name"]); ?></td>
														<td><?php echo ($v["username"]); ?></td>
														<td><?php echo ($v["department"]); ?></td>
														<td><?php echo ($v["need_score"]); ?></td>
													</tr><?php endforeach; endif; ?>
												</tbody>
											</table>
										</div>
									</div>
									<!--内容結束-->
									<!--分页开始-->
									<div class="row">
										<div class="float_r mr15">
											<div class="dataTables_paginate paging_simple_numbers" id="example1_paginate">
												<ul class="pagination">
													<?php echo ($page2); ?>
											
												</ul>
											</div>
										</div>
									</div>
									<!--分页结束-->
								</div>
							</div>
							<!--课程审核結束-->
						</div>
					</div>
				</div>
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
		
         $('#myTab a').click(function (e) {
          e.preventDefault(); //阻止绑定事件的默认行为
       //   alert(1);
	      var flag = $(e.target).attr("name");       // e.target表示被点击的目标
		  if(flag == 'tab1'){
            location.href = '<?php echo U('Admin/Integration/welfarelist',array('tabType'=>1));?>';
		  }else if(flag == 'tab2'){
            location.href = '<?php echo U('Admin/Integration/welfareChangehistory',array('tabType'=>2));?>';
		  }
        });

    //传参定位tab
        var i = '<?php echo I('tabType');?>';  //参数tabType是定位tab位置
        var tabType = i ? '<?php echo I('tabType');?>' : '';

           if(tabType == 1 || tabType == ''){  
              $('#myTab li:eq(0) a').tab('show'); //定位到第一个tab标签
           }else if(tabType == 2){
              $('#myTab li:eq(1) a').tab('show'); //定位到第二个tab标签
           }
			//radio选中样式
			$('input').iCheck({
				labelHover: false,
				cursor: true,
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_minimal-blue',
				increaseArea: '20%'
			});

			function del_click(id) {
				// alert(id);
				//删除分组
				layer.confirm('您确定要删除吗？', {
					btn: ['确定', '取消'],
					skin: 'layui-layer-lan'
						//按钮
				}, function() {
					 location.href = '<?php echo U('Admin/Integration/delone');?>' + "/id/"+id;
					layer.msg('删除成功', {icon: 1});
				}, function() {
					layer.msg('取消删除', {
						time: 2000, //2s后自动关闭
					});
				});
			}
			
		</script>
	</body>

</html>