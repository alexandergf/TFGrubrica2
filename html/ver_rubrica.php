<?php
include "../php/protege.php";
require_once('../js/ver_rubrica.php');
?>
<html>
<head>
	<title>Veure Rúbrica</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/home.css">
	<link rel="stylesheet" href="../css/perfil.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script type="text/javascript" src="../js/header.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script> 
    $(function(){
	  $("#headerOUT").load("header.php"); 
	  inici();
    });
    </script> 
</head>
<body>
	<div id="headerOUT"></div>
	<div class="intro_img">
		<img src="../resources/images/ver_rubrica.jpg" id="imgEva">
	</div>
	<div class="formulario">
		<form method="POST" action="rubrica.php">
			<select id="select_rubrica" name="select_rubrica" onchange="look()"></select>
			<input type="hidden" name="perfil" value="estudiant">
	        <input type="submit" name="submit" id="submit-btn" disabled="disabled">
		</form>
	</div>
</body>
</html> 