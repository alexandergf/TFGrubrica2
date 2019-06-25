function openCloseSign(){
	var attr = document.getElementById("form-ini").style.display;
	if (attr == "none") {
		document.getElementById("form-ini").style.display = "block";
	}else{
		document.getElementById("form-ini").style.display = "none";
	}
}
function logout(){
	location.href='../php/cerrar_sesion.php';
}