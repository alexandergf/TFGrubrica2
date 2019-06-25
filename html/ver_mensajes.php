<?php
    include "../php/protege.php";
    require_once("../php/conexion_pdo.php");
    $db = new Conexion();
    $aux = "si";
	$dbTabla='MENSAJES';
	$consulta="SELECT COUNT(*) FROM $dbTabla WHERE profesor = :iu";
	$result = $db->prepare($consulta);
    $result->execute(array(":iu" => $_SESSION["id"]));
    $total=$result->fetchColumn();
    if($total == 0){
        $aux = "no";
    }else{
        //SELECT * FROM MENSAJES INNER JOIN PROFESOR ON MENSAJES.coordinador=PROFESOR.idUsuario  AND MENSAJES.profesor= 5;
        $dbTabla='MENSAJES';
        $dbTabla2='PROFESOR';
        $consulta="SELECT * FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla.coordinador=$dbTabla2.idUsuario AND $dbTabla.profesor = :iu";
        $result = $db->prepare($consulta);
        $result->execute(array(":iu" => $_SESSION["id"]));
    }    
?>
<html>
<head>
	<title>Mensajes</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../resources/fonts/fonts.css">
    <link rel="stylesheet" href="../css/ver_mensaje.css">
    <script type="text/javascript" src="../js/header.js"></script>
	<script type="text/javascript" src="../js/ver_mensajes.js"></script>
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
        <table id="mensajes-table">
            <tr>
                <th>De</th>
                <th>Assumpte</th>
                <th>Data</th>
            </tr>
                <?php
                    if($aux == "no"){
                        echo "<tr>";
                        echo "<td id='no-mesajes'>No hay mensajes.</td>";
                        echo "</tr>";
                    }else{
                        foreach ($result as $fila){
                            echo "<tr>";
                            echo "<td id='de-coord'>$fila[nombre] $fila[apellido]</td>";
                            echo "<td id='assu'>$fila[asumpto]</td>";
                            $fecha=explode(" ",$fila[fecha]);
	                        $fecha=$fecha[0];
                            echo "<td id='date'>$fecha</td>";
                            echo "<td id='btn-id'><button id='btn-mensaje' onclick='redirec($fila[idMensaje])'>VEURE</button></td>";
                            echo "</tr>";
                        }
                    }
                ?>
        </table>
        <div id="btn-ini">
            <button type="button" id="btn-inici" onclick="retorn()">Tornar a l'inici</button>
        </div>
    </div>
</body>
</html> 