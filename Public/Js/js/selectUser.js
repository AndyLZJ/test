
var searchAjax=function(){};
var G_tocard_maxTips=100;


// 更新选中标签标签
$(function(){
	setSelectTips();
	$('#myTags').append($('#myTags a'));
	$('#open').append($('#open a'));
});
var searchAjax = function(name, id, isAdd){
	setSelectTips();
};

var open_searchAjax = function(name, id, isAdd){
	open_setSelectTips();
};

// 更新显示
(function(){
	// 更新高亮显示
	setSelectTips = function(){
		var arrName = getTips();
		if(arrName.length){
			$('#myTags').show();
		}else{
			$('#myTags').hide();
		}
		$('.default-tag a').removeClass('selected');
		$.each(arrName, function(index,name){
			$('.default-tag a').each(function(){
				var $this = $(this);
				if($this.attr('title') == name){
					$this.addClass('selected');
					return false;
				}
			})
		});
	}

	open_setSelectTips = function(){
		var arrName = open_getTips();

		if(arrName.length){
			$('#open').show();
		}else{
			$('#open').hide();
		}
		$('.default-tag a').removeClass('selected');
		$.each(arrName, function(index,name){
			$('.default-tag a').each(function(){
				var $this = $(this);
				if($this.attr('title') == name){
					$this.addClass('selected');
					return false;
				}
			})
		});
	}

	var a=$("#myTags");
		 $(document).on('click', 'em', function()
		{
			var c=$(this).parents("a"),b=c.attr("title"),d=c.attr("value");
			delTips(b,d)
		});

	var open=$("#open");
	$(document).on('click', '.myTags', function()
	{
		var c=$(this).parents("a"),b=c.attr("title"),d=c.attr("value");

		delTips(b,d);

	});

	$(document).on('click', '.open', function()
	{

		var c=$(this).parents("a"),b=c.attr("title"),d=c.attr("value");

		open_delTips(b,d);

	});

	hasTips=function(b){
			var d=$("a",a),c=false;
			d.each(function(){
				if($(this).attr("title")==b){
					c=true;
					return false
				}
			});
			return c
		};

	open_hasTips=function(b){
		var d=$("a",open),c=false;
		d.each(function(){
			if($(this).attr("title")==b){
				c=true;
				return false
			}
		});
		return c
	};

		isMaxTips=function(){
			return
			$("a",a).length>=G_tocard_maxTips
		};

		setTips=function(c,d){
			if(hasTips(c)){
				return false
			}if(isMaxTips()){
				alert("最多添加"+G_tocard_maxTips+"个标签！");
				return false
			}
			var b=d?'value="'+d+'"':"";
			a.append($("<a "+b+' title="'+c+'" href="javascript:void(0);" ><span>'+c+"</span><em class='myTags'><input type='hidden' name='user_id[]' value='" + d+"'/></em></a>"));
			searchAjax(c,d,true);
			return true;
		};

		OpenTips = function(c,d){

			if(open_hasTips(c)){

				return false;
			}

			var b=d?'value="'+d+'"':"";

			open.append($("<a "+b+' title="'+c+'" href="javascript:void(0);" ><span>'+c+"</span><em class='open'><input type='hidden' name='tissue_id[]' value='" + d+"'/></em></a>"));

			open_searchAjax(c,d,true);

			return true;
		};

		test=function(){
		   alert("klss");
			return true
		};

		delTips=function(b,c){
			if(!hasTips(b)){
				return false
			}
			$("a",a).each(function(){
				var d=$(this);
				if(d.attr("title")==b){
					var number = $('.number');
					number.html(number.html()-1);
					d.remove();
					return false
				}
			});
			searchAjax(b,c,false);
			return true
		};

		open_delTips=function(b,c){
			if(!open_hasTips(b)){
				return false
			}
			$("a",open).each(function(){
				var d=$(this);
				if(d.attr("title")==b){
					d.remove();
					return false
				}
			});
			open_searchAjax(b,c,false);
			return true
		};

		getTips=function(){
			var b=[];
			$("a",a).each(function(){
				b.push($(this).attr("title"))
			});
			return b
		};

		open_getTips=function(){
			var b=[];
			$("a",open).each(function(){
				b.push($(this).attr("title"))
			});
			return b
		};

		getTipsId=function(){
			var b=[];
			$("a",a).each(function(){
				b.push($(this).attr("value"))
			});
			return b
		};
		
		getTipsIdAndTag=function(){
			var b=[];
			$("a",a).each(function(){
				b.push($(this).attr("value")+"##"+$(this).attr("title"))
			});
			return b
		}
	

})();