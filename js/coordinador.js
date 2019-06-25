function redirec(){
	var value=document.getElementById("select_list").value;
	if (value == "enviar") {
		location.href='../html/mensajes.php';
	} else if (value == "veure"){
		location.href='../html/veure_rubrica.php';
	}
}
function review(){
	location.href='../html/perfil.php';
}