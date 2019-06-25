<?PHP
	session_start();
	$rub = $_POST["select_rubrica"];
	require_once("conexion_pdo.php");
	$db = new Conexion();
	$nombre=explode("@", $_SESSION["nombre"]);
	$_SESSION["nombre"]=$nombre[0];
	$extension=explode(".",$_FILES['file_input']['name']);
	$extension=$extension[count($extension)-1];
	$_FILES["file_input"]["name"]=$rub . $_SESSION["id"] . $_SESSION["nombre"] . "." . $extension;
	if ($_FILES['file_input']['type'] != 'application/pdf') {
		header("location:../html/penjar_rubrica.php?error=1");
		//print "<p>Error tipo fichero</p>\n";
	} else {
		if ($_FILES["file_input"]["error"] > 0) {
			header("location:../html/penjar_rubrica.php?error=2");
			//print "<p>Error file input</p>\n";
		} else {
			if (move_uploaded_file($_FILES["file_input"]["tmp_name"],"../rubricas_pdf/" . $_FILES["file_input"]["name"])) {
				$dbTabla='Tiene'; 
				$consulta = "SELECT Tiene.idTFG FROM Tiene WHERE Tiene.idAlum=:iu"; 
				$result = $db->prepare($consulta);
				$result->execute(array(":iu" => $_SESSION["id"]));
				if (!$result) {
					//Fallo
					header("location:../html/penjar_rubrica.php?error=4");
					//print "<p>No existe en la base de datos el TFG.</p>\n";
				}else{
					$tfg=$result->fetchColumn();
					if ($tfg == NULL) {
						//Fallo
						header("location:../html/penjar_rubrica.php?error=5");
						//print "<p>No existe en la base de datos el TFG.</p>\n";
					} else {
						$dbTabla2='RUBRICA'.$rub; 
						$consulta = "SELECT COUNT($dbTabla2.idTFG) FROM $dbTabla2 WHERE $dbTabla2.idTFG=:iu"; 
						//SELECT COUNT(Tiene.idTFG) as contador,RUBRICA1.idTFG FROM `Tiene` INNER JOIN RUBRICA1 ON Tiene.idTFG=RUBRICA1.idTFG AND Tiene.idAlum=1
						$result = $db->prepare($consulta);
						$result->execute(array(":iu" => $tfg));
						$total=$result->fetchColumn();
						if ($total == 0) {
							$consulta="INSERT INTO $dbTabla2 (idTFG,documento) VALUES (:it,:nom)";
							$result3= $db->prepare($consulta);
							if ($result3->execute(array(":it" =>$tfg, ":nom" => $_FILES["file_input"]["name"]))) {
								//Insertado
								header("location:../html/estudiante.php");
							} else {
								//Fallo insert
								header("location:../html/penjar_rubrica.php?error=6");
							}
						} else {
							$consulta="UPDATE $dbTabla SET documento=:nom WHERE idTFG=:it";
							$result3= $db->prepare($consulta);
							if ($result3->execute(array(":it" =>$tfg, ":nom" => $_FILES["file_input"]["name"]))) {
								//Actualizado
								header("location:../html/estudiante.php");
							} else {
								//Fallo actualizacion
								header("location:../html/penjar_rubrica.php?error=7");
								//print "<p>Error en el update.</p>\n";
							}
						}
					}
				}
				
			} else {
				//Fallo al mover el fichero
				header("location:../html/penjar_rubrica.php?error=3");
				//print "<p>Fallo al mover el fichero</p>\n";
			}
		}
	}
?>