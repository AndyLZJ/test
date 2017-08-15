<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>个人中心_通讯录</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="/Public/Dist/css/comm.css">
    <link rel="stylesheet" href="/Public/Css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Public/Dist/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/Public/Dist/ionicons-2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/Public/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="/Public/Dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="/Public/Dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="/Public/plugins/iCheck/all.css">
    <style type="text/css">
	label:hover{cursor:pointer;}
	.box-title {margin-right: 20px !important;}
	.organization_box {margin-bottom: 0px !important;}
	.no_employees {height: 500px;}
	.no_employees p {line-height: 50px;padding-top: 160px;}
	.organ_input {width: 250px;}
	.box-solid > .sidebar-menu > li.active > a, .box-solid > .sidebar-menu > li > a:hover, .box-solid > .sidebar-menu > li > a {padding: 10px 5px 10px 15px;}
	.layui-layer-content > .form-horizontal > .box-body > .form-group {margin-right: 0px !important;margin-left: 0px !important;}
	.control-label {width: 150px !important;}
	.layui-layer-content > .form-horizontal > .box-body > .form-group > .col-sm-10 {width: 300px !important;}
    </style>
</head>
<body>
<!--内容区域顶栏面包屑开始-->
<section class="content-header">
    <h1 class="info_title">
        通讯录
    </h1>
    <ol class="breadcrumb">
    </ol>
</section>
<!--内容区域顶栏面包屑结束-->
<section class="content">
    <div class="row mb10 pr15 pl15">
        <div class="input-group ">
            <input type="text" name="keywords" id="keyword" class="form-control pull-right" placeholder="搜索员工" style="width: 200px;">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </div>
    <div class="row">
        <!--组织架构树图开始-->
        <div class="col-md-2">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">组织架构</h3>
                </div>
                <div class="box box-solid mt10 sidebar">
                	<ul class="sidebar-menu">
                         <li class="treeview active" >
                             <a href="javascript:void(0);">
                                 <label>
                                 	 <input type="hidden" class="tissue_id" value="<?php echo ($root_id); ?>"/>                                  
                                     <span class="main_part"><?php echo ($root_name); ?></span>
                                 </label>
                                 <span class="pull-right-container">
                                     <i class="fa fa-angle-left pull-right"></i>
                                 </span>
                             </a>
                             <ul class="treeview-menu menu-open">
                             	<?php if(is_array($data)): foreach($data as $key=>$items): ?><li>
                                 	<!-- 一级 -->
                                     <a href="javascript:void(0);">
                                         <input type="hidden" class="tissue_id" value="<?php echo ($items['id']); ?>" />
                                         <label class="main_part"><?php echo ($items['name']); ?>(<?php echo ($items['part_num']); ?>)</label>
                                         <span class="pull-right-container">
                                             <i class="fa fa-angle-left pull-right"></i>
                                         </span>
                                     </a>
                                     
                                     <!-- 二级 -->
                                     <ul class="treeview-menu menu-open" style="display: none;">
                                     	<?php if(is_array($items['sub_list'])): foreach($items['sub_list'] as $key=>$sub_v): ?><li>
                                             <a href="javascript:void(0);">
                                             	<input type="hidden" class="tissue_id" value="<?php echo ($sub_v['id']); ?>" />
                                                 <label class="main_part"><?php echo ($sub_v['name']); ?>(<?php echo ($sub_v['part_num']); ?>)</label>
                                                         <span class="pull-right-container">
                                                     <i class="fa fa-angle-left pull-right"></i>
                                                 </span>
                                             </a>
                                             <ul class="treeview-menu menu-open" style="display: none;">
                                                 <?php if(is_array($sub_v['sub_items'])): foreach($sub_v['sub_items'] as $key=>$items_v): ?><li>
                                                         <a href="javascript:void(0);">
                                                             <input type="hidden" class="tissue_id" value="<?php echo ($items_v['id']); ?>" />
                                                             <label class="sub_part"><?php echo ($items_v['name']); ?>(<?php echo ($items_v['part_num']); ?>)</label>
                                                         </a>

                                                     </li><?php endforeach; endif; ?>
                                               </ul>
                                         </li><?php endforeach; endif; ?>
                                     </ul>
                                 </li><?php endforeach; endif; ?>
                                 
                             </ul>
                         </li>
                         <li class="treeview active" >
                             <a href="javascript:void(0);">
                                 <label>                                      
									<input type="hidden" class="tissue_id" value="unset"/>
									<span class="sub_part">未分配人员 (<?php echo ($unset); ?>) </span>
                                 </label>
                                 <span class="pull-right-container">
                                     <i class="fa fa-angle-left pull-right"></i>
                                 </span>
                              </a>
                      	</li>
                     </ul>
                </div>
            </div>
        </div>
        <!--组织架构树图结束-->
        <!--组织架构员工表格开始-->
        <div class="col-md-10">
            <div class="box box-success">
                <!-- <div class="box-header with-border">
                    <h3 class="box-title"><?php echo (getTissue($tissue_id)); ?></h3>
                    <?php if(is_array($adminList)): foreach($adminList as $key=>$vo): if($vo["manage_id"] == 1): ?><span class="text-muted mr30">管理员：<?php echo ($vo["username"]); ?></span>
                    	<?php else: ?>
	                       <span class="text-muted">负责人：<?php echo ($vo["username"]); ?></span><?php endif; endforeach; endif; ?>
                </div> -->
                <!--有员工表格开始-->
                <div>
                    <div class="mailbox-messages mr10 ml10 mb10 mt10">
                        <table class="table table-bordered table-striped dataTable organ_table" role="grid"
                               aria-describedby="example1_info">
                            <thead>
									<tr role="row">
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">姓名</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Platform(s): activate to sort column ascending">工号</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">手机</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">办公电话</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">邮箱</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">组织</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">岗位</th>
										<th class="sorting" tabindex="0" aria-controls="example1" rowspan="1" colspan="1" aria-label="CSS grade: activate to sort column ascending">操作</th>
									</tr>
								</thead>
                            <tbody id="tbody">
                            </tbody>
                        </table>
                        <!--分页开始-->
                        <div>
                            <div class="float_r mr15 mt30">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <?php echo ($pages); ?>
                                </div>
                            </div>
                        </div>
                        <!--分页结束-->
                    </div>
                </div>
                <!--有员工表格结束-->
            </div>
        </div>
        <!--组织架构员工表格开始-->
    </div>
</section>
<input type="hidden" id="currPage" value=""/>
<input type="hidden" id="tissue_id" value=""/>
<!--设置负责人-->
<script src="/Public/plugins/jQuery/jquery-2.2.3.min.js"></script>
<script src="/Public/Js/js/bootstrap.min.js"></script>
<script src="/Public/plugins/fastclick/fastclick.js"></script>
<script src="/Public/Dist/js/app.min.js"></script>
<script src="/Public/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/Public/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/Public/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="/Public/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<script src="/Public/plugins/chartjs/Chart.min.js"></script>
<script src="/Public/plugins/iCheck/icheck.min.js"></script>
<script src="/Public/plugins/layer/layer.js"></script>

<script type="text/javascript">
	function getPart(){
		var page = $("#currPage").val();
		var keyword = $("#keyword").val();
		var id = $("#tissue_id").val();
		$.ajax({
			type: "post",
			url: "/index.php/admin/contacts/getPart/p/"+page,
			data: "id="+id+"&keyword="+keyword+"&page="+page,
			dataType: "json",
			success: function(data){
				if(data.code == 1000){
					var html = '';
					for(var i=0; i<data.data.list.length; i++){
						var thisData = data.data.list[i];
						html += '<tr role="row" class="odd">';
						html += '<td>'+thisData.username+'</td>';
						html += '<td>'+thisData.job_number+'</td>';
						html += '<td>'+thisData.phone+'</td>';
						html += '<td>'+thisData.tel+'</td>';
						html += '<td>'+thisData.email+'</td>';
						html += '<td>'+thisData.part_name+'</td>';
						html += '<td>'+thisData.job_name+'</td>';
						html += '<td style="width:64px; padding:8px 0; "><a href="'+thisData.details+'">查看详情</a></td>';
						html += '</tr>';
					}
					$("#tbody").html(html);
					$(".dataTables_paginate").html(data.data.pageNav);
				}else{
					
				}
			}
		});
	}
	getPart();
	
	$(".dataTables_paginate").on("click","a",function(){
		var page = $(this).attr("href");
		page = page.split("/p/");
		page = page[1];
		$("#currPage").val(page);
		getPart();
		return false;
	});
	
	//搜索员工
	$(".input-group-btn").click(function(){
		$("#currPage").val(1);
		$("#tissue_id").val("");
		getPart();
	});
	
	$(".main_part").click(function(){
		$("#currPage").val(1);
		var tissue_id = $(this).siblings(".tissue_id").val();
		$("#tissue_id").val(tissue_id);
		$("#keyword").val("");
		getPart();
		//return false;
	});
	
	$(".sub_part").click(function(){
		$("#currPage").val(1);
		var tissue_id = $(this).siblings(".tissue_id").val();
		$("#tissue_id").val(tissue_id);
		$("#keyword").val("");
		getPart();
		return false;
	});
	
</script>
</body>
</html>