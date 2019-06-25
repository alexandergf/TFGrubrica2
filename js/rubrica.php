<?php
	require_once("../php/conexion_pdo.php");
	$db = new Conexion();
	$dbTabla='comentarios'.$rub; 
	$dbTabla2='TFG';
	$consulta="SELECT * FROM $dbTabla WHERE idTFG=(SELECT idTFG FROM $dbTabla2 WHERE titulo=:tit)";
	$result = $db->prepare($consulta);
	$result->execute(array(":tit" => $titulo));
?>

<script type="text/javascript">
var coment=[];
<?php foreach($result as $fila){ echo 'coment.push({pagina:'.$fila[pagina].',comentario:\''.$fila[comentario].'\'});';}; ?>

console.log(coment);
function volver(perfil){
	if (perfil == "profesor") {
		location.href='../html/profesor.php';
	} else if (perfil == "estudiant"){
		location.href='../html/estudiante.php';
	} else if(perfil=="coordinador"){
		location.href='../html/coordinador.php';
	}
};
function pagSig(){
	var num= document.getElementById("numeroPag").value;
	var nomDoc= document.getElementById("pdfDocument").value;
	var rubric= document.getElementById("rubric").value;
	var max= document.getElementById("max").value;
	var comentario= document.getElementById("comentario").value;
	var titulo= document.getElementById("title").value;
	if (num != max) {
		num++;
	}else{
		document.getElementById("enviar").style.display = "block"; 
	}
		
	document.getElementById("numeroPag").value=num;
	for (var i = 0; i < coment.length; i++) {
		if(coment[i].pagina==num){
			document.getElementById("comentario").value=coment[i].comentario;
		}
	}
	document.getElementById("pdf").src="pdf.php?rubrica="+nomDoc+"&pag="+num+"&rub="+rubric+"&title="+titulo+"&comentario="+comentario+"&value=";
};
function pagAnt(){
	var num= document.getElementById("numeroPag").value;
	var nomDoc= document.getElementById("pdfDocument").value;
	var rubric= document.getElementById("rubric").value;
	var comentario= document.getElementById("comentario").value;
	var titulo= document.getElementById("title").value;
	if(num!=1){
		num--;
	}
	document.getElementById("numeroPag").value=num;
	
	for (var i = 0; i < coment.length; i++) {
		if(coment[i].pagina==num){
			document.getElementById("comentario").value=coment[i].comentario;
		}
	}
	document.getElementById("pdf").src="pdf.php?rubrica="+nomDoc+"&pag="+num+"&rub="+rubric+"&title="+titulo+"&comentario="+comentario+"&value=";
};
function finalizar(){
	var num= document.getElementById("numeroPag").value;
	var nomDoc= document.getElementById("pdfDocument").value;
	var rubric= document.getElementById("rubric").value;
	var max= document.getElementById("max").value;
	var comentario= document.getElementById("comentario").value;
	var titulo= document.getElementById("title").value;
	document.getElementById("numeroPag").value=num;
	
	document.getElementById("pdf").src="pdf.php?rubrica="+nomDoc+"&pag="+num+"&rub="+rubric+"&title="+titulo+"&comentario="+comentario+"&value=enviar";
}
</script>