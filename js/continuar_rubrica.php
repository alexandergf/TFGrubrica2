<?php
	require_once("../php/conexion_pdo.php");
	$db = new Conexion();
	$array= array("diseny" => array(),"multi" => array(),"jocs" => array());
	$dbTabla='ALUMNO'; 
	$dbTabla2='Tiene';
	$dbTabla3='TFG';
	$consulta="SELECT nombre,apellido,$dbTabla3.grado FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla2.idAlum=$dbTabla.idUsuario INNER JOIN $dbTabla3 ON $dbTabla3.idTFG=$dbTabla2.idTFG AND $dbTabla2.idProf=:ip";
	$result = $db->prepare($consulta);
	$result->execute(array(":ip" => $_SESSION["id"]));
	foreach ($result as $fila) {
        $nrub=estudiant($fila[nombre]);
		switch ($fila[grado]) {
		    case "Grau en Diseño Animació i Art Digital":
				array_push($array["diseny"],$fila[nombre] . " " . $fila[apellido]);
				$array["diseny"][$fila[nombre]. " " . $fila[apellido]] = $nrub;
		        break;
		    case "Grau en Multimèdia":
				array_push($array["multi"],$fila[nombre] . " " . $fila[apellido]);
				$array["multi"][$fila[nombre]. " " . $fila[apellido]] = $nrub;
		        break;
		    case "Grau en Disseny i Desenvolupament de Videojocs":
				array_push($array["jocs"],$fila[nombre] . " " . $fila[apellido]);
				$array["jocs"][$fila[nombre]. " " . $fila[apellido]] = $nrub;			
		        break;
		    default:
		    	//no hay nombre grado
		    	break;
		}
	}

	function estudiant($nombre) {
		require_once("../php/conexion_pdo.php");
		$db = new Conexion();
		$contador=0;
		$dbTabla='Tiene';
		$dbTabla2='ALUMNO';
		$dbTabla3='Pert1';
		$consulta = "SELECT COUNT($dbTabla3.idTFG) as contador FROM $dbTabla3 WHERE $dbTabla3.idTFG=(SELECT idTFG FROM $dbTabla WHERE idAlum=(SELECT idUsuario FROM $dbTabla2 WHERE nombre=:iu))"; 
		$result = $db->prepare($consulta);
		$result->execute(array(":iu" => $nombre));
		$total = $result->fetchColumn();
		if($total>0 && $total<19){
			$contador = 1;
		}else{
			$dbTabla3='Pert2';
			$consulta = "SELECT COUNT($dbTabla3.idTFG) as contador FROM $dbTabla3 WHERE $dbTabla3.idTFG=(SELECT idTFG FROM $dbTabla WHERE idAlum=(SELECT idUsuario FROM $dbTabla2 WHERE nombre=:iu))"; 
			$result = $db->prepare($consulta);
			$result->execute(array(":iu" => $nombre));
			$total = $result->fetchColumn();
			if($total>0 && $total<11){
				$contador=2;
			}else{
				$dbTabla3='Pert3';
				$consulta = "SELECT COUNT($dbTabla3.idTFG) as contador FROM $dbTabla3 WHERE $dbTabla3.idTFG=(SELECT idTFG FROM $dbTabla WHERE idAlum=(SELECT idUsuario FROM $dbTabla2 WHERE nombre=:iu))"; 
				$result = $db->prepare($consulta);
				$result->execute(array(":iu" => $nombre));
				$total = $result->fetchColumn();
				if($total>0 && $total<9){
					$contador=3;
				}else{
					$contador=0;
				}
			}
		}
		return $contador;
	}

?>

<script type="text/javascript">
var diseny =[];
var multi=[];
var jocs=[];
<?php foreach($array['diseny'] as $fila){ echo 'diseny.push(\''.$fila.'\');';}?>
<?php foreach($array['multi'] as $fila){ echo 'multi.push(\''.$fila.'\');';}?> 
<?php foreach($array['jocs'] as $fila){ echo 'jocs.push(\''.$fila.'\');';}?>   

function selectGrau(){
	var fillEstudiant = function(){
		var selected = $('#select_grau').val(); 
		$('#select_estudiant').empty();
		$('#select_estudiant').append("<option value='pregunta'>Selecciona l'estudiant</option>");
		switch(selected) {
		  case "diseny":
		    diseny.forEach(function(element,index){
				if(index%2==0){
					$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
				}
			});
		    break;
		  case "multi":
		    multi.forEach(function(element,index){
				if(index%2==0){
					$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
				}
			});
		    break;
		  case "jocs":
		    jocs.forEach(function(element,index){
				if(index%2==0){
					$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
				}
			});
			break;
			default:
				$('#select_estudiant').append("<option value='no'>No s'ha seleccionat grau.</option>");
			break;
		} 
	}
	fillEstudiant();
	selectEst();
};
function selectEst(){
	var fillRub = function(){
		var gSelect = $('#select_grau').val(); 
		var eSelect = $('#select_estudiant').val();
		var valor;
		switch(gSelect) {
		  case "diseny":
				diseny.forEach(function(element,index){
					if(element == eSelect){
						valor = diseny[index+1];
					}
				});
		    break;
		  case "multi":
		    multi.forEach(function(element,index){
				if(element == eSelect){
					valor = multi[index+1];
				}
			});
		    break;
		  case "jocs":
		    jocs.forEach(function(element,index){
				if(element == eSelect){
					valor = jocs[index+1];
				}
			});
			break;
		  default:
		  	$('#select_estudiant').append("<option value='pregunta'>Selecciona l'estudiant</option>");
			break;
		}
		$('#select_rubrica').empty();
		if(valor>0 && valor<4 ){
			$('#select_rubrica').append('<option value="pregunta">Selecciona la Rúbrica</option>');
		}
		switch(valor){
			case "3":
				$('#select_rubrica').append('<option value="3">Rúbrica 3</option>');
				break;
			case "2":
				$('#select_rubrica').append('<option value="2">Rúbrica 2</option>');
				break;
			case "1":
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				break;
			case "0":
				$('#select_rubrica').append('<option value="no">No hay rúbricas empezadas.</option>');
				break;
			default:
				$('#select_rubrica').append('<option value="pregunta">Selecciona la Rúbrica</option>');
				break;
        }
	}
    fillRub();
};
function look(){
    var select=$('#select_rubrica').val();
    var sel=$('#select_estudiant').val();
    if((select != "pregunta") && (select != "no") && (sel != "pregunta") && (sel != "no")){
        $("#submit-btn").removeAttr("disabled");
    }else{
        $("#submit-btn").prop("disabled", "disabled");
    }
};
</script>