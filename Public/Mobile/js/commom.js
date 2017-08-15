$(function(){
	$(".back").click(function(){
		history.go(-1);
	})
	$(".h_calendar").click(function(){
		location.href = "calendar.html"
	})
	$(".objective .tabs li").click(function() {
		switch($(this).index()){
			case 0:
				location.href = "objective1.html";
				break;
			case 1:
				location.href = "objective2.html";
				break;
			case 2:
				location.href = "objective3.html";
				break;
		}
	});
})