<?PHP
	$mail = $_POST["username"];
	$pas = $_POST["password"];
	require_once("conexion_pdo.php");
	$db = new Conexion();

	$dbTabla='USUARIO'; 
	$consulta = "SELECT COUNT(idUsuario) FROM $dbTabla WHERE email = :log AND password = :pas"; 
	$result = $db->prepare($consulta);
	$result->execute(array(":log" => $mail, ":pas" => md5($pas)));
	$total = $result->fetchColumn();
	if($total==1){
		$consulta = "SELECT * FROM $dbTabla WHERE email=:log AND password=:pas"; 
		$result = $db->prepare($consulta);
		$result->execute(array(":log" => $mail, ":pas" => md5($pas)));

		if (!$result) { 
			//print "<p>Error en la consulta. 1</p>\n";
			header("location:../html/home.php?error=1");
		}else{
			session_start();
			$fila=$result->fetchObject();
			$_SESSION["id"] = $fila->idUsuario;
			$_SESSION["nombre"] = $fila->email;
			header("location:../html/perfil.php");
		}
	}else{
		header("location:../html/home.php?error=1");
		//print "<p>Error en la busqueda.</p>\n";
	}

?>