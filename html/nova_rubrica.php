<?php
include "../php/protege.php";
$pag=$_GET["id"];
if($pag==1){
	require_once('../js/nova_rubrica.php');
} else {
	require_once('../js/continuar_rubrica.php');
}
?>
<html>
<head>
	<title>Nova Rúbrica</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/home.css">
	<link rel="stylesheet" href="../css/nova_rubrica.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script type="text/javascript" src="../js/header.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script> 
    $(function(){
	  $("#headerOUT").load("header.php"); 
	});
    </script> 
</head>
<body>
	<div id="headerOUT"></div>
	<div class="intro_img">
	<?php
		if($pag == 1){
			echo "<img src='../resources/images/nueva_rubrica.jpg' id='imgEva'>";
		} else {
			echo "<img src='../resources/images/continuar_rubrica.jpg' id='imgEva'>";
		}
	?>
	</div>
	<div class="formulario">
		<form method="POST" action="rubrica.php" id="formulario">
			<div id="selects">
			<select id="select_grau" name="select_grau" onchange="selectGrau()">
	          <option selected value="Selecciona el Grau">Selecciona el Grau</option>
	          <option value="diseny">Grau en Diseño Animació i Art Digital</option>
	          <option value="multi">Grau en Multimèdia</option>
	          <option value="jocs">Grau en Disseny i Desenvolupament de Videojocs</option>
	        </select>
	        <select id="select_estudiant" name="select_estudiant" onchange="selectEst()">
				<option selected value="Selecciona el estudiant">Selecciona l'estudiant</option>
	        </select>
	        <select id="select_rubrica" name="select_rubrica" onchange="look()">
				<option selected value="Selecciona la rubrica">Selecciona la Rúbrica</option>
			</select> 
			</div> 
	        <input type="hidden" name="perfil" value="profesor">
	        <input type="submit" id="submit-btn" name="submit" disabled="disabled">
		</form>
	</div>
</body>
</html> 