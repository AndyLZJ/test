//一级菜单点击

$('.levelone_menu a').click(function(){
  var me = $(this).find('i.float_r');
  if(me.hasClass('ion-android-arrow-dropdown')){
    me.removeClass('ion-android-arrow-dropdown').addClass('ion-android-arrow-dropup');
  }else{
    me.removeClass('ion-android-arrow-dropup').addClass('ion-android-arrow-dropdown');
  }
});

//全选checkbox
var $checkboxAll = $(".js-checkbox-all"),
            $checkbox = $("tbody > tr > td").find("[type='checkbox']").not("[disabled]"),
            length = $checkbox.length,
            i=0;

    $checkboxAll.on("ifClicked",function(event){
        if(event.target.checked){
            $checkbox.iCheck('uncheck');
            i=0;
        }else{
            $checkbox.iCheck('check');
            i=length;
        }
    });



//表单验证

