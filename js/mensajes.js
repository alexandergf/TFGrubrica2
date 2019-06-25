function review(){
	location.href='../html/coordinador.php';
}
function look(){
	var select=$('#select_profesor').val();
    if(select != "pregunta"){
        $("#submit-btn").removeAttr("disabled");
    }else{
        $("#submit-btn").prop("disabled", "disabled");
    }
}