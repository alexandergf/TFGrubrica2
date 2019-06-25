<?PHP
	include "protege.php";
	$selectValue = $_POST["select_list"];
	require_once("conexion_pdo.php");
	$db = new Conexion();

	$dbTabla='PROFESOR'; 
	$consulta = "SELECT COUNT(idUsuario) FROM $dbTabla WHERE idUsuario=:iu"; 
	$result = $db->prepare($consulta);
	$result->execute(array(":iu" => $_SESSION["id"]));
	$total = $result->fetchColumn();
	if($total==1){
		$consulta = "SELECT * FROM $dbTabla WHERE idUsuario=:iu"; 
		$result = $db->prepare($consulta);
		$result->execute(array(":iu" => $_SESSION["id"]));

		if (!$result) { 
			print "<p>Error en la consulta. 1</p>\n";
					//header("location:login.php");
		}else{
			$fila=$result->fetchObject();
			$coor=$fila->coordinador;
			if ($coor == 1 && $selectValue == "Coordinador") {
				//Eres coordinador
				header("location:../html/coordinador.php");
			} else if($coor == 0 && $selectValue == "Professor"){
				//Eres profesor
				header("location:../html/profesor.php");
			} else if($coor == 1 && $selectValue == "Professor"){
				//Eres coordinador pero entras como profesor
				header("location:../html/profesor.php");
			} else {
				header("location:../html/perfil.php?perfil=$selectValue");
			}
		}
	}else{
		//header("location:index.php?error=1");
		if ($selectValue == "Estudiant") {
			//Eres estudiante
			header("location:../html/estudiante.php");
		}else{
			header("location:../html/perfil.php?perfil=$selectValue");
		}
	}
?>