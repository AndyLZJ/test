//一级菜单点击

$('.levelone_menu a').click(function(){
  var me = $(this).find('i.float_r');
  if(me.hasClass('ion-android-arrow-dropdown')){
    me.removeClass('ion-android-arrow-dropdown').addClass('ion-android-arrow-dropup');
  }else{
    me.removeClass('ion-android-arrow-dropup').addClass('ion-android-arrow-dropdown');
  }
});
