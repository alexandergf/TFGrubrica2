<?PHP
    include "protege.php";
    require_once("conexion_pdo.php");
    $db = new Conexion();
    
	$destinatario = $_POST["select_profesor"];
	$asunto = $_POST["asumpto-mensaje"];
    $texto = $_POST["comment"];
    $fecha = date("Y-m-d H:m:s");

	$dbTabla='MENSAJES'; 
	$consulta = "INSERT INTO $dbTabla (asumpto,contenido,fecha,coordinador,profesor) VALUES (:am,:con,:fec,:coo,:pr)"; 
	$result = $db->prepare($consulta);
    if ($result->execute(array(":am" => $asunto, ":con" => $texto, ":fec" => $fecha, ":coo" => $_SESSION["id"], ":pr" => $destinatario))) {
        //Insertado
        header("location:../html/coordinador.php");
        //echo "Bieeeeeeeeeeeeeeeeeeen";
    } else {
        //Fallo insert
        print "<p>Error en el insert.</p>\n";
        //echo "Mierda";
    }
?>