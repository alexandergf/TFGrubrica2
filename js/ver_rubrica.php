<?php
require_once("../php/conexion_pdo.php");
$db = new Conexion();
//$_SESSION["nombre"];
$contador=0;
$dbTabla='RUBRICA1';
$dbTabla2='Tiene';
//SELECT COUNT(documento) FROM RUBRICA1 INNER JOIN Tiene ON RUBRICA1.idTFG=Tiene.idTFG AND Tiene.idAlum=1
$dbTabla='Tiene';
$dbTabla2='ALUMNO';
$dbTabla3='Pert1';
$consulta = "SELECT COUNT($dbTabla.idTFG) from $dbTabla INNER JOIN $dbTabla2 ON  $dbTabla.idAlum= $dbTabla2.idUsuario AND $dbTabla2.idUsuario=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG = $dbTabla2.idUsuario"; 
$result = $db->prepare($consulta);
$result->execute(array(":iu" => $_SESSION["id"]));
$total = $result->fetchColumn();
if($total == 19){
    $dbTabla3='Pert2';
	$consulta = "SELECT COUNT($dbTabla.idTFG) from $dbTabla INNER JOIN $dbTabla2 ON  $dbTabla.idAlum= $dbTabla2.idUsuario AND $dbTabla2.idUsuario=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG = $dbTabla2.idUsuario"; 
	$result = $db->prepare($consulta);
	$result->execute(array(":iu" => $_SESSION["id"]));
	$total = $result->fetchColumn();
    if($total == 11){
        $dbTabla3='Pert3';
		$consulta = "SELECT COUNT($dbTabla.idTFG) from $dbTabla INNER JOIN $dbTabla2 ON  $dbTabla.idAlum= $dbTabla2.idUsuario AND $dbTabla2.idUsuario=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG = $dbTabla2.idUsuario"; 
		$result = $db->prepare($consulta);
		$result->execute(array(":iu" => $_SESSION["id"]));
		$total = $result->fetchColumn();
        if($total == 9){
            $contador=3;
        }else{
            $contador=2;
        }
    }else{
        $contador=1;
    }
}
?>
<script type="text/javascript">
function inici(){
	
		var valor = <?php echo $contador; ?>;
		$('#select_rubrica').empty();
		if(valor>0 && valor<4){
			$('#select_rubrica').append('<option value="pregunta" selected="selected">Quina rúbrica dessitja veure?</option>');
		}
		switch(valor){
			case 3:
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				$('#select_rubrica').append('<option value="2">Rúbrica 2</option>');
				$('#select_rubrica').append('<option value="3">Rúbrica 3</option>');
				break;
			case 2:
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				$('#select_rubrica').append('<option value="2">Rúbrica 2</option>');
				break;
			case 1:
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				break;
			default:
				$('#select_rubrica').append('<option value="error">No hay rúbricas que ver.</option>');
				break;
		}
};
function look(){
    var select=$('#select_rubrica').val();
    if((select != "pregunta") && (select != "error")){
        $("#submit-btn").removeAttr("disabled");
    }else{
        $("#submit-btn").prop("disabled", "disabled");
    }
};
</script>