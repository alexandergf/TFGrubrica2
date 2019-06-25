<?php
session_start();
require_once('../js/penjar_rubrica.php');
$error=$_GET["error"];
?>
<html>
<head>
	<title>Penjar rubrica</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/home.css">
	<link rel="stylesheet" href="../css/perfil.css">
	<link rel="stylesheet" href="../css/penjar_rubrica.css">
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
		<img src="../resources/images/subir_rubrica.jpg" id="imgEva">
	</div>
	<div class="formulario">
		<form action="../php/insertRubrica.php" method="POST" enctype="multipart/form-data">
				<select id="select_rubrica" name="select_rubrica" onchange="look()"></select>
				<label for="userfile" id="userfile" >
					<input type="file" id='file_input' name="file_input" />
					<img src="../resources/logos/upload_logo.png" id='image_input' onclick="image()" />
				</label>   
				<div id="btns" class="btns"> 	
					<input type="submit" name="submit" id="submit-btn" class="submit-btn" value="Pujar" disabled="disabled" />
					<button type="button" onclick="redirec()" id="btn-return">Tornar a l'inici</button>
				</div>
		</form>
		<?php
			switch($error){
				case "1":
					echo "<div id='respuesta'><p>Error con el tipo de fichero.</p></div>";
					break;
				case "2":
					echo "<div id='respuesta'><p>Error con el fichero.</p></div>";
					break;
				case "3":
					echo "<div id='respuesta'><p>Error al subir el fichero.</p></div>";
					break;
				case "4":
					echo "<div id='respuesta'><p>Error con el TFG.</p></div>";
					break;
				case "5":
					echo "<div id='respuesta'><p>Error con el TFG.</p></div>";
					break;
				case "6":
					echo "<div id='respuesta'><p>Error con la base de datos.</p></div>";
					break;
				case "7":
					echo "<div id='respuesta'><p>Error con la base de datos.</p></div>";
					break;
				default:

					break;
			}
		?>
	</div>
</body>
</html> 