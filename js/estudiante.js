function redirec(){
	var value=document.getElementById("select_list").value;
	if (value == "veure") {
		location.href='../html/ver_rubrica.php';
	} else if (value == "penjar") {
		location.href='../html/penjar_rubrica.php';
	}
}