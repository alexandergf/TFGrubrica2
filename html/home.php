<?php
	$error=$_GET["error"];
	if($error == 1){
		$header="header.php?error=1";
	}else{
		$header="header.php";
	}
?>
<html>
<head>
	<title>Inicio</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/home.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script type="text/javascript" src="../js/header.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script> 
    $(function(){
      $("#headerOUT").load("<? echo $header; ?>"); 
    });
    </script> 
</head>
<body>
	<div id="headerOUT"></div>
	<div class="intro_img">
		<img src="../resources/images/evaluacion_tfg.jpg" id="imgEva">
	</div>
</body>
</html> 