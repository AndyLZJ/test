function check(){

	var start_time=document.getElementById('datebut').value;

	var end_time=document.getElementById('datainps').value;

	var d1 = new Date(start_time.replace(/\-/g, "\/"));  
 	
 	var d2 = new Date(end_time.replace(/\-/g, "\/"));

 	if(start_time==""||end_time=="")  
 	{  
  		layer.alert("开始学习时间或结束学习时间不能为空！");  
  		
  		return false;  
 	}  

 	if(start_time!=""&&end_time!=""&&d1 >=d2)  
 	{  
  		layer.alert("开始学习不能大于学习结束时间！");  
  		
  		return false;  
 	}

 	return true;
}

function refresh(){

	
	var start_time=document.getElementById('datebut').value;

	var end_time=document.getElementById('datainps').value;

	var d1 = new Date(start_time.replace(/\-/g, "\/"));  
 	
 	var d2 = new Date(end_time.replace(/\-/g, "\/"));

 	if(start_time==""||end_time=="")  
 	{  
  		layer.alert("项目开始时间或项目结束时间不能为空！");  
  		
  		return false;  
 	}  

 	if(start_time!=""&&end_time!=""&&d1 >=d2)  
 	{  
  		layer.alert("项目开始不能大于项目结束时间！");  
  		
  		return false;  
 	}

  return true;

}




