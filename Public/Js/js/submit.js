function testsSearch(url){



	var test_category=document.getElementById('test_category').value;

	var test_name=document.getElementById('test_name').value;

	var type=2;

	var data={'test_category':test_category,'test_name':test_name,'type':type};





	Ajax(data,url);
	
}

function Ajax(data,url){

	  $.ajax({

	                type: 'post',

	                data:data,

	                url:url,

	                dataType:'json',

	                success: function(data, textStatus){

						var datas=eval(data.data.list);

						

						var html=document.getElementById('examinations');

	                	var examinationShow=document.getElementById('test');

	                	var string="";



	                	for(var i=0;i<datas.length;i++){

	                		string+="<tr role='row' class='odd text-center'><td><a href=''>"+datas[i]["test_name"]+"</a></td><td>"+datas[i]["cat_name"]+"</td><td><input type='checkbox' class='testchecks' value='"+datas[i]['id']+"'></td></tr>";
	                	}



	                	html.innerHTML=string;

	                	examinationShow.innerHTML=data.data.show;
	                }
    });



}



function surveysSearch(url){

	var survey_category=document.getElementById('survey_category').value;

	var survey_name=document.getElementById('survey_name').value;

	var type=3;

	var data={'survey_category':survey_category,'survey_name':survey_name,'type':type};

	surveyAjax(data,url);

}

function surveyAjax(data,url){

	 $.ajax({

	                type: 'post',

	                data:data,

	                url:url,

	                dataType:'json',

	                success: function(data, textStatus){

						var datas=eval(data.data.list);



						var html=document.getElementById('surveysss');

						var surveyShow=document.getElementById('survey');

	                	var string="";

	                	for(var i=0;i<datas.length;i++){

	                		string+="<tr role='row' class='odd text-center'><td><a href=''>"+datas[i]["survey_name"]+"</a></td><td>"+datas[i]["cat_name"]+"</td><td><input type='checkbox' class='surveychecks' value='"+datas[i]['id']+"'></td></tr>";
	                	}



	                	html.innerHTML=string;

	                	surveyShow.innerHTML=data.data.show;
	                }
    });

	


}

function surveys(url,id){


			var survey_category=document.getElementById('survey_category').value;

	        var survey_name=document.getElementById('survey_name').value;

			var type=3;

			var data={"p":id,'survey_category':survey_category,'survey_name':survey_name,'type':type};

	 		$.ajax({

	                type: 'post',

	                data:data,

	                url:url,

	                dataType:'json',

	                success: function(data, textStatus){

						var datas=eval(data.data.list);



						var html=document.getElementById('surveysss');

						var surveyShow=document.getElementById('survey');

	                	var string="";

	                	for(var i=0;i<datas.length;i++){

	                		string+="<tr role='row' class='odd text-center'><td><a href=''>"+datas[i]["survey_name"]+"</a></td><td>"+datas[i]["cat_name"]+"</td><td><input type='checkbox' class='surveychecks' value='"+datas[i]['id']+"'></td></tr>";
	                	}



	                	html.innerHTML=string;

	                	surveyShow.innerHTML=data.data.show;
	                }
    });
			




}




