function pag(num){ 
	$('form').attr('action',num); 
	$('form').submit(); 
} 
function mostrar(num,rub,apartat){
	$("#guia").empty();
	  $.getJSON("../resources/json/rubrica"+rub+"/apartat"+apartat+".json",
			function(data){
				for(var i = 0; i < data.valoracions[num].length; i++){
					$("#guia"+num).append("<div id='row' class='row'><p id='number' class='number'>"+data.puntuacions[i]["punts"]+"</p><p id='text' class='text'>"+data.valoracions[num][i]["text"]+"</p></div>");	
			  	}
		})
}
