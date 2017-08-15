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
        <small>/积分管理</small>
     </h1>
			
		</section>
		<!--内容区域顶栏面包屑结束-->
		<section class="content">
		  <div>
		    <div class="dataTables_wrapper form-inline dt-bootstrap ">
				<div class="nav-tabs-custom">
					<ul  id="myTab" class="nav nav-tabs">
						<li>
							<a href="#rules" name="tab1" data-toggle="">积分规则</a>
						</li>
						<li>
							<a href="#record" name="tab2" data-toggle="">积分流水</a>
						</li>
						<!--考试筛选开始-->
						
						<!--考试筛选结束-->
					</ul>
					<div class="tab-content">
						<div class="tab-pane" id="rules">
							<!--课程列表开始-->
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
                                    <div class="pull-right mb10 font_bold color_or">
										<span>注：点击数字直接进行编辑</span>
									</div>
									<!--内容开始-->
									<div class="row">
										<div class="col-sm-12">
											<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
												<thead>
													<tr role="row">
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending" width="50">序号</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">规则名称</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">分值</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">封顶</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">类型</th>
													</tr>
												</thead>
												<tbody id="tab">
													<?php if(is_array($list1)): foreach($list1 as $k=>$v): if(($v["id"] == 3) OR ($v["id"] == 4)): ?><tr role="row" class="odd text-center">
														<td><?php echo ($k+1); ?></td>
														<td><?php echo ($v["name"]); ?></td>
														<td ><span id="score" title="<?php echo ($v["id"]); ?>" class="caname"><?php echo ($v["score"]); ?></span>分</td>
														<td><span id="oneday_score" title="<?php echo ($v["id"]); ?>" class="caname"><?php echo ($v["oneday_score"]); ?></span>分/月</td>
														<td><?php echo ($v["type"]); ?></td>
													<?php else: ?>
													    <tr role="row" class="odd text-center">
														<td><?php echo ($k+1); ?></td>
														<td><?php echo ($v["name"]); ?></td>
														<!--<input type="hidden" id="ruleid" value="<?php echo ($v["id"]); ?>">-->
														<td><span id="score" title="<?php echo ($v["id"]); ?>" class="caname"><?php echo ($v["score"]); ?></span>分</td>
                                                       <?php if(empty($v["oneday_score"])): ?><td><span class="caname"></span></td>
                                                        <?php else: ?> 
                                                        <td><span id="oneday_score" title="<?php echo ($v["id"]); ?>" class="caname"><?php echo ($v["oneday_score"]); ?></span>分/天</td><?php endif; ?> 	
														<td><?php echo ($v["type"]); ?></td><?php endif; ?>
													</tr><?php endforeach; endif; ?>


													
												</tbody>
											</table>
										</div>
									</div>
									<!--内容結束-->
								</div>
							</div>
							<!--课程列表结束-->
						</div>
						<div class="tab-pane " id="record">
							<!--课程审核开始-->
							<div class="box-body">
								<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">
									<!--内容开始-->
									<div class="row">
										<div class="col-sm-12">
											<table id="example1" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
												<thead>
													<tr role="row">
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">时间</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">姓名</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">部门</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">积分</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">类型</th>
														<th class="sorting text-center" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">说明</th>
													</tr>
												</thead>
												<tbody id="tab">
													<?php if(is_array($list2)): foreach($list2 as $k=>$v): ?><tr role="row" class="odd text-center">
														<td><?php echo date('Y-m-d H:i:s',$v['time']);?></td>
														<td><?php echo ($v["username"]); ?></td>
														<td><?php echo ($v["department"]); ?></td>
														<td><?php echo ($v["score"]); ?></td>
														<td><?php echo ($v["type"]); ?></td>
														<td><?php echo ($v["describe"]); ?></td>
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
         layer.alert('点击选中规则中的分数框可直接进入编辑状态',{time:5000}); 
		 $('#myTab a').click(function (e) {
          e.preventDefault(); //阻止绑定事件的默认行为
       //   alert(1);
	      var flag = $(e.target).attr("name");       // e.target表示被点击的目标
		  if(flag == 'tab1'){
            location.href = "<?php echo U('Admin/Integration/integrationlist',array('tabType'=>1));?>";
		  }else if(flag == 'tab2'){
            location.href = "<?php echo U('Admin/Integration/integrationHistoryList',array('tabType'=>2));?>";
		  }
        });

    //传参定位tab
        var i = "<?php echo I('tabType');?>";  //参数tabType是定位tab位置
        var tabType = i ? "<?php echo I('tabType');?>" : '';

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
            //点击数字直接进行编辑js逻辑
			(function($){
		        $(".caname").click(function () { 
					
					var td = $(this); 
					var id = td.attr("title");
					var txt = $.trim(td.text()); 
					var input = $("<input class='form-control' type='text'value='" + txt + "' style='width:60px;'/>"); 
					td.html(input); 
					input.click(function () { return false; }); 
					//获取焦点 
					input.trigger("focus"); 
					//文本框失去焦点后提交内容，重新变为文本 
					input.blur(function () { 

					var newtxt = $(this).val(); 
					var tempattr =  td.attr("id");
					// alert(tempattr);
					var score = '';
					var oneday_score = '';

                    
                    
					if(tempattr == 'score'){
						var oneday_scoreVal = parseInt($.trim($(this).parents(".odd").children().children("#oneday_score").text()));
						if(newtxt > oneday_scoreVal){
						td.html(txt); 
						layer.msg('封顶分值不能小于基本分值', {icon: 2});
						exit;
						}
					 
                       score = newtxt;
					}else if(tempattr == 'oneday_score'){
						 
						var scoreVal = parseInt($.trim($(this).parents(".odd").children().children("#score").text()));
						if(newtxt < scoreVal){
						td.html(txt); 
						layer.msg('封顶分值不能小于基本分值', {icon: 2});
						exit;
						}
                       oneday_score = newtxt;
					}


					//判断修改后文本为空
					if (newtxt == '') { 
					td.html(txt); 
					layer.msg('数字不能为空', {icon: 2});
					exit;
				    }
                    //判断修改后文本为空
					if (newtxt == '') { 
					td.html(txt); 
					layer.msg('数字不能为空', {icon: 2});
					exit;
				    }
					//判断修改后文本为的长度
					if (newtxt.length > 5) { 
					td.html(txt); 
					layer.msg('数字的长度不能超过5位', {icon: 2});
					exit;
				    }
					//判断修改后文本为的长度
					if (isNaN(newtxt)) { 
					td.html(txt); 
					layer.msg('只能输入数字', {icon: 2});
					exit;
				    }
					//判断文本有没有修改 
					if (newtxt == txt) { 
					td.html(newtxt); 
				    }else{
                    $.post("<?php echo U('Admin/Integration/editIntegrationlist');?>",{id:id,score:score,oneday_score:oneday_score,tempattr:tempattr}, function($data) {  
                        
                          if($data.status == 1){  
							  
                              td.html(newtxt);
							  layer.msg($data.info,{time: 1500,icon: 1});//bootstrap框架确认弹窗
                            //   setTimeout(function(){
                            //    location.href = $data.url; 
                            //   },1000);   
                          }else{  
							//    layer.msg($data.info, { time: 1000});
                            //    location.href = $data.url; 
							  td.html(txt);
							  layer.msg($data.info, {icon: 2});
                          }     
                      },'json');


				    }
			});
				});
		    })(jQuery);

		</script>
	</body>

</html>