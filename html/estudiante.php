<?php
session_start();
?>
<html>
<head>
	<title>Estudiante</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/home.css">
	<link rel="stylesheet" href="../css/estudiante.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script type="text/javascript" src="../js/header.js"></script>
	<script type="text/javascript" src="../js/estudiante.js"></script>
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
		<img src="../resources/images/estudiante.jpg" id="imgEva">
	</div>
	<div class="formulario">
			<select id="select_list" name="select_list">
	          <option selected value="Què desitja fer?">Què desitja fer?</option>
	          <option value="veure">Veure rúbrica</option>
	          <option value="penjar">Penjar rúbrica</option>
	        </select>
	        <button onclick="redirec()">Siguiente</button>  
	</div>
</body>
</html> 