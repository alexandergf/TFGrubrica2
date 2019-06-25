<?php
    include "../php/protege.php";
    require_once("../php/conexion_pdo.php");
	$db = new Conexion();
	$dbTabla='PROFESOR';
	$consulta="SELECT * FROM $dbTabla WHERE idUsuario != :iu";
	$result = $db->prepare($consulta);
	$result->execute(array(":iu" => $_SESSION["id"]));
?>
<html>
<head>
	<title>Mensajes</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/mensajes.css">
    <link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script type="text/javascript" src="../js/header.js"></script>
    <script type="text/javascript" src="../js/mensajes.js"></script>
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
		<img src="../resources/images/mensajes.jpg" id="imgEva" />
    </div>
    <div id="mensajes-body">
        <form id="mensajes-form" method="POST" action="../php/mensajes.php">
            <div id="destinatario">
                <p>Per a:</p>
                <select id="select_profesor" name="select_profesor" onchange="look()">
                    <option value="pregunta" selected="selected">Selecciona al profesor</option>
                    <?php
                        foreach ($result as $fila){
                            echo "<option value='".$fila[idUsuario]."'>".$fila[nombre]." ".$fila[apellido]."</option>";
                        }
                    ?>
                </select>
            </div>
            <div id="asunto">
                <p>Assumpte:</p>
                <input type="text" id="asumpto-mensaje" name="asumpto-mensaje" maxlength="100" required>
            </div>
            
            <div id="texto-mensaje">
                <p>Enviar:</p>
                <textarea name="comment" required></textarea>
            </div>
            <div id="env">
                <input type="submit" name="submit" id="submit-btn" value="Enviar missatge" disabled>
                <button type="button" name="btn-review" id="btn-review" onclick="review()">Volver</button>
            </div>
            
        </form>
    </div>
</body>
</html> 