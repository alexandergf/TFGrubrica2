<?php
include "../php/protege.php";
?>
<html>
<head>
	<title>Profesor</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/home.css">
	<link rel="stylesheet" href="../css/estudiante.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script type="text/javascript" src="../js/header.js"></script>
	<script type="text/javascript" src="../js/profesor.js"></script>
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
		<img src="../resources/images/profesor.jpg" id="imgEva">
	</div>
	<div class="formulario">
			<select id="select_list" name="select_list">
	          <option selected value="Què dessitja fer?">Què dessitja fer?</option>
	          <option value="iniciar">Iniciar una nova rúbrica</option>
	          <option value="continuar">Continuar una rúbrica ja començada</option>
	          <option value="veure">Veure missatges del coordinador</option>
	        </select>
	        <button onclick="redirec()">Siguiente</button>
			<button onclick="review()">Volver</button> 
	</div>
</body>
</html> 