<?php
session_start();
require_once("conexion_pdo.php");
$db = new conexion();
$dbTabla='USUARIO';

    $consulta="SELECT idUsuario,email FROM $dbTabla WHERE email=:em AND idUsuario=:iu";
	$result =$db->prepare($consulta);
	$result->execute(array(":em" => $_SESSION["nombre"], ":iu" => $_SESSION["id"]));
	$fila= $result->fetchObject();
if(($_SESSION["nombre"]!=$fila->email) || ($_SESSION["id"]!=$fila->idUsuario)){
	header("location:../php/cerrar_sesion.php");
}

?>