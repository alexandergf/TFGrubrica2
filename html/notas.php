<?php
include "../php/protege.php";
?>
<html>
<head>
	<title>Notas Rúbrica<?php echo $_GET["rub"];?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="../css/notas.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
<?php
	$rub = $_GET["rub"];
	$nom=$_GET["nombre"];

	require_once("../php/conexion_pdo.php");
    $db = new Conexion();
    //SELECT RUBRICA1.idTFG,RUBRICA1.notaFinal  FROM `RUBRICA1` 
    //INNER JOIN Tiene ON RUBRICA1.idTFG=Tiene.idTFG INNER JOIN ALUMNO ON Tiene.idAlum=ALUMNO.idUsuario AND idUsuario=1
    $dbTabla='RUBRICA'.$rub;
    $dbTabla2='Tiene';
    $dbTabla3='ALUMNO';
    $consulta="SELECT $dbTabla.idTFG,$dbTabla.notaFinal,$dbTabla3.apellido FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla.idTFG=$dbTabla2.idTFG INNER JOIN $dbTabla3 ON $dbTabla2.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:iu";
    
    $result = $db->prepare($consulta);
	$result->execute(array(":iu" => $nom));
    $fila=$result->fetchObject();
    //SELECT Cont1.notaApartado, APARTADOS1.titulo, APARTADOS1.porcentage 
    //FROM Cont1 INNER JOIN APARTADOS1 ON Cont1.idApartado=APARTADOS1.idApartado AND Cont1.idTFG=1
    $dbTabla='Cont'.$rub;
    $dbTabla2='APARTADOS'.$rub;
    $consulta2="SELECT $dbTabla.notaApartado,$dbTabla2.idApartado, $dbTabla2.titulo, $dbTabla2.porcentage FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla.idApartado=$dbTabla2.idApartado AND $dbTabla.idTFG=:it";
    $result2 = $db->prepare($consulta2);
    $result2->execute(array(":it" => $fila->idTFG));
    
	switch ($rub) {
		case 1:
			echo "<h2 id='title'>$nom $fila->apellido</h2>";
			break;
		case 2:
			echo "<h2 id='title'>$nom $fila->apellido</h2>";
			break;
		case 3:
			echo "<h2 id='title'>$nom $fila->apellido</h2>";
			break;
		default:
			echo "<h2 id='title'>Rúbrica TFG</h2>";
			break;
    }
    echo "<div class='subtitle'><h2>Nota final: ". round($fila->notaFinal,3)."</h2></div>";
        echo "<form method='POST' name='form' id='form'>";   
			foreach ($result2 as $fila2) {
				echo "<h3>Apartado ".$fila2[idApartado].": ".$fila2[titulo]." (".$fila2[porcentage]."%) = ". round($fila2[notaApartado],3) ."</h3>";
			}
		echo "</form>";
	
?>
</body>
</html>