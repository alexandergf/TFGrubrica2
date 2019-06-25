<?php
include "../php/protege.php";
require_once('../js/veure_rubrica.php');
?>
<html>
<head>
	<title>Veure Rúbrica</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/home.css">
	<link rel="stylesheet" href="../css/veure_rubrica.css">
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
		<img src="../resources/images/cordinador.jpg" id="imgEva">
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
					<option value="pregunta">Selecciona l'estudiant</option>
				</select>
				<select id="select_rubrica" name="select_rubrica" onchange="look()">
					<option value="pregunta">Selecciona la rúbrica</option>
				</select>  
				<input type="hidden" name="perfil" value="coordinador">
			</div>
			<input type="submit" id="submit-btn" name="submit" value="Anar a la rúbrica" disabled="disabled">
			
		</form>
	</div>
</body>
</html> 