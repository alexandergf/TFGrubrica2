<?PHP
	include "protege.php";
	require_once("../php/conexion_pdo.php");
	$db = new Conexion();

	$rub = $_GET["rub"];
	$apartado = $_GET["apartado"];
	$accion = $_GET["accion"];
	$nom = $_POST["nombreAlumno"];
	$perfil=$_POST["perfil"];
	$dbTabla='Posee'.$rub;
	$consulta = "SELECT COUNT(idSub) as total,idSub FROM $dbTabla WHERE idApartado=:ia LIMIT 1"; 
	$result = $db->prepare($consulta);
	$result->execute(array(":ia" => $apartado));
	if(!$result){
		header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom&error=1");
	}
	$fila = $result->fetchObject();
	$total=$fila->total;
	$subApartado=$fila->idSub;

	$dbTabla='Pert'.$rub;
	$dbTabla2='Tiene';
	$dbTabla3='ALUMNO';

	$consulta = "SELECT COUNT(*) FROM $dbTabla WHERE idTFG=(SELECT idTFG FROM $dbTabla2 INNER JOIN $dbTabla3 ON $dbTabla2.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:it) AND idSub=:isu"; 
	$result = $db->prepare($consulta);
	$result->execute(array(":it" =>$nom, ":isu" => $subApartado));
	if(!$result){
		header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom&error=2");
	}
	$aux = $result->fetchColumn();
	if($aux == 0){
		for ($i = 0; $i < $total; $i++) {
			if(!isset($_POST[$i+$subApartado])){
				$_POST[$i+$subApartado]=0;
			}
			$consulta="INSERT INTO $dbTabla VALUES ((SELECT idTFG FROM $dbTabla2 INNER JOIN $dbTabla3 ON $dbTabla2.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:it),:isub,:nota)";
			$result2= $db->prepare($consulta);
			$result2->execute(array(":it" =>$nom, ":isub" => $i+$subApartado, ":nota" => $_POST[$i+$subApartado]));
			if(!$result2){
				header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom&error=3");
			}
		}
	} else {
		for ($i = 1; $i <= $total; $i++) {
			if($_POST[$i] != null){
				$consulta="UPDATE $dbTabla SET notaSubapartado = :nota WHERE idTFG=(SELECT idTFG FROM $dbTabla2 INNER JOIN $dbTabla3 ON $dbTabla2.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:it) AND idSub = :isub";
				$result2= $db->prepare($consulta);
				$result2->execute(array(":it" =>$nom, ":isub" => $i, ":nota" => $_POST[$i]));
				if(!$result2){
					header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom&error=3");
				}
			}
		}
	}	
	
	if ($accion == "atras") {
		$apartado--;
		header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom");
	} elseif ($accion == "alante") {
		$apartado++;
		header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom");
	} else if($accion == "evaluar"){
		$numAux=0;
		switch($rub){
			case '1':
				$numAux=19;
				break;
			case '2':
				$numAux=11;
				break;
			case '3':
				$numAux=9;
				break;
		}
		$dbTabla='Posee'.$rub;	
		$dbTabla2='SUBAPARTADOS'.$rub; 
		$dbTabla3='Pert'.$rub;
		$dbTabla4='Tiene';
		$dbTabla5='ALUMNO';
		$consulta2 = "SELECT COUNT(notaSubapartado) FROM $dbTabla3 INNER JOIN $dbTabla ON $dbTabla.idSub=$dbTabla3.idSub AND $dbTabla3.idTFG=(SELECT idTFG FROM $dbTabla4 INNER JOIN $dbTabla5 ON $dbTabla4.idAlum=$dbTabla5.idUsuario AND $dbTabla5.nombre=:it)"; 
		$result = $db->prepare($consulta2);
		$result->execute(array(":it" => $nom));
		$total=$result->fetchColumn();
		if($total == $numAux){	
				$bool=false;
				$notaFinal=0;
				$dbTabla='APARTADOS'.$rub;
				$consulta="SELECT COUNT(idApartado) FROM $dbTabla";
				$result = $db->prepare($consulta);
				$result->execute();
				$total=$result->fetchColumn();
				$dbTabla='Cont'.$rub;
				$dbTabla2='Tiene';
				$dbTabla3='ALUMNO';
				$dbTabla4='Pert'.$rub;
				$dbTabla5='Posee'.$rub;
				$dbTabla6='APARTADOS'.$rub;
				for($i=1;$i<=$total;$i++){
					$consulta="SELECT AVG($dbTabla4.notaSubapartado) as nota,$dbTabla6.porcentage as multi FROM $dbTabla4 INNER JOIN $dbTabla5 ON $dbTabla4.idSub=$dbTabla5.idSub AND $dbTabla5.idApartado=:ia AND idTFG=(SELECT idTFG FROM $dbTabla2 INNER JOIN $dbTabla3 ON $dbTabla2.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:it) INNER JOIN $dbTabla6 ON $dbTabla5.idApartado=$dbTabla6.idApartado";
					$result = $db->prepare($consulta);
					$result->execute(array(":it" =>$nom, ":ia" => $i));
					$pre=$result->fetchObject();
					$notaApa=$pre->nota * $pre->multi / 100;
					$consulta2="INSERT INTO $dbTabla VALUES ((SELECT idTFG FROM $dbTabla2 INNER JOIN $dbTabla3 ON $dbTabla2.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:it),:isub,:nota)";
					$result2= $db->prepare($consulta2);
					if($result2->execute(array(":it" =>$nom, ":isub" => $i, ":nota" => $notaApa))){
						$bool = true;
						$notaFinal=$notaFinal+$notaApa;
					}else{
						header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom&error=5");
					}
				}
				if($bool == true){
					$dbTabla='RUBRICA'.$rub;
					$consulta="UPDATE $dbTabla SET notaFinal = :nota WHERE idTFG=(SELECT idTFG FROM $dbTabla2 INNER JOIN $dbTabla3 ON $dbTabla2.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:it)";
					$result= $db->prepare($consulta);
					if($result->execute(array(":nota" => $notaFinal, ":it"=>$nom))){
						header("location:../html/notas.php?rub=".$rub."&nombre=$nom");
					}else{
						header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom&error=6");
					}
				}
		}else{
			//Falta por evaluar
			header("location:../html/evaluar_rubrica.php?rub=$rub&apartado=$apartado&perfil=$perfil&nombre=$nom&error=4");
		}
	}
?>