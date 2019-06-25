<?php
include "../php/protege.php";
?>
<html>
<head>
	<title>Evaluar Rúbrica<?php echo $_GET["rub"];?></title>
	<meta charset="utf-8">
	<script type="text/javascript" src="../js/evaluar.js"></script>
	<link rel="stylesheet" href="../css/evaluar_rubrica.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
</head>
<body>
<?php
	$rub = $_GET["rub"];
	$apartado=$_GET["apartado"];
	$perfil=$_GET["perfil"];
	$nom=$_GET["nombre"];
	if($perfil != "profesor"){
		$perfil="estudiante";
	}
	$error=$_GET["error"];
	$errorText='';
	
	switch ($error) {
		case 1:
			$errorText="Error con los sub-apartados.";
			break;
		case 2:
			$errorText="Error con la rúbrica.";
			break;
		case 3:
			$errorText="Error con las puntuaciones.";
			break;
		case 4:
			$errorText="Faltan apartados por evaluar.";
			break;
		case 5:
			$errorText="Error al calcular la nota final.";
			break;
		case 6:
			$errorText="Error al insertar la nota final en la base de datos.";
			break;	
		default:
			
			break;
	}

	require_once("../php/conexion_pdo.php");
	$db = new Conexion();


	$dbTabla='APARTADOS'.$rub; 
	$consulta = "SELECT COUNT(*) FROM $dbTabla";  
	$result = $db->prepare($consulta);
	$result->execute();
	$total = $result->fetchColumn();



	$dbTabla='APARTADOS'.$rub; 
	$consulta = "SELECT * FROM $dbTabla WHERE idApartado=:ia"; 
	$result = $db->prepare($consulta);
	$result->execute(array(":ia" => $apartado));
	$fila=$result->fetchObject();
	switch ($rub) {
		case 1:
			echo "<h2 id='title'>Primera rúbrica TFG (versión $perfil)</h2>";
			break;
		case 2:
			echo "<h2 id='title'>Segona rúbrica TFG (versión $perfil)</h2>";
			break;
		case 3:
			echo "<h2 id='title'>Tercera rúbrica TFG (versión $perfil)</h2>";
			break;
		default:
			echo "<h2 id='title'>Rúbrica TFG (versión $perfil)</h2>";
			break;
	}
	echo "<div class='subtitle'><h2>".$fila->titulo." (".$fila->porcentage."%)</h2></div>";
	$dbTabla='Posee'.$rub;	
	$dbTabla2='SUBAPARTADOS'.$rub; 
	$dbTabla3='Pert'.$rub;
	$consulta = "SELECT $dbTabla.idSub, nombre FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla2.idSub=$dbTabla.idSub AND idApartado=:is "; 
	//SELECT Posee1.idSub, nombre, notaSubapartado FROM Posee1 INNER JOIN SUBAPARTADOS1 ON SUBAPARTADOS1.idSub=Posee1.idSub AND Posee1.idApartado=1 INNER JOIN Pert1 ON Pert1.idSub=Posee1.idSub
	$result = $db->prepare($consulta);
	$result->execute(array(":is" => $apartado));

	//notas

	$dbTabla4='Tiene';
	$dbTabla5='ALUMNO';
	//SELECT notaSubapartado FROM Pert1 INNER JOIN Posee1 ON Posee1.idSub=Pert1.idSub AND Pert1.idTFG=(SELECT idTFG FROM Tiene INNER JOIN ALUMNO ON Tiene.idAlum=ALUMNO.idUsuario AND ALUMNO.nombre='Alexander') AND Posee1.idApartado=1
	$consulta2 = "SELECT notaSubapartado FROM $dbTabla3 INNER JOIN $dbTabla ON $dbTabla.idSub=$dbTabla3.idSub AND $dbTabla3.idTFG=(SELECT idTFG FROM $dbTabla4 INNER JOIN $dbTabla5 ON $dbTabla4.idAlum=$dbTabla5.idUsuario AND $dbTabla5.nombre=:it) AND $dbTabla.idApartado=:ia"; 
	$result2 = $db->prepare($consulta2);
	$result2->execute(array(":it" => $nom,":ia" => $apartado));
	$array=array();
	foreach($result2 as $fila2){
		array_push($array,$fila2["notaSubapartado"]);			
	}
	if (!$result) {
		//fallo
		echo "Error";
	} else {
		echo "<form method='POST' name='form' id='form'>";
		echo "<input type='hidden' name='nombreAlumno' value='$nom'>";
		if($_GET["final"]!="true"){
			$is=0;
			foreach ($result as $fila) {
				echo "<h3>".$fila["nombre"]."</h3>";
				echo "<script>mostrar($is,$rub,$apartado);</script>";
				echo "<div id='guia$is'></div>";
				echo "<h3>Teniendo en cuenta los criterios anteriores, califica la pregunta:</h3>";
				echo "<div class='answer' id='answer'>";
					for ($i = 0; $i <= 10; $i++) {
						if($perfil != "profesor"){
							if ($i == $array[$is] && !empty($array)) {
								print "<div id='colum' class='colum'><input type='radio' class='res' id='res' name='".$fila["idSub"]."' value='$i' checked disabled><p class='res-num' id='res-num'>$i</p></div>";
							}else{
								print "<div id='colum' class='colum'><input type='radio' class='res' id='res' name='$fila[0]' value='$i' disabled><p class='res-num' id='res-num'>$i</p></div>";
							}
						}else{
							if ($i == $array[$is] && !empty($array)) {
								print "<div id='colum' class='colum'><input type='radio' class='res' id='res' name='".$fila["idSub"]."' value='$i' checked><p class='res-num' id='res-num'>$i</p></div>";
							}else{
								print "<div id='colum' class='colum'><input type='radio' class='res' id='res' name='$fila[0]' value='$i' required='required' /><p class='res-num' id='res-num'>$i</p></div>";
							}
						}
					}
				echo "</div>";
			$is++;
				
			}
			echo "<div class='buttons' id='buttons'>";
			$atras="../php/cambiar_apartado.php?rub=$rub&apartado=$apartado&accion=atras";
			if ($apartado>1) {
				echo "<input type='button' class='btn-at' id='btn-at' onclick=\"pag('$atras')\" value='Atras'>";
			}
			$alante="../php/cambiar_apartado.php?rub=$rub&apartado=$apartado&accion=alante";
			if ($apartado<$total) {
				echo "<input type='button' class='btn-si' id='btn-si' onclick=\"pag('$alante')\" value='Siguiente'>";
			}
			$enviar="../php/cambiar_apartado.php?rub=$rub&apartado=$apartado&accion=evaluar";
			if ($apartado==$total && $perfil=="profesor") {
				echo "<input type='button' class='btn-si' id='btn-si' onclick=\"pag('$enviar')\" value='Enviar'>";
			}
			echo "</div>";
		}else{
			echo "<h3>Evaluació finalitzada.</h3>";
		}
		echo "<input type='hidden' name='perfil' value='$perfil'>";
		echo "</form>";
		if(!empty($error)){
			echo "<div id='error'><p>$errorText</p></div>";
		}
	}
?>
</body>
</html>
