<?php
	require_once("../php/conexion_pdo.php");
	$db = new Conexion();
	$array= array("diseny" => array(),"multi" => array(),"jocs" => array());
	$dbTabla='ALUMNO'; 
	$dbTabla2='Tiene';
	$dbTabla3='TFG';
	$consulta="SELECT nombre,apellido,$dbTabla3.grado FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla2.idAlum=$dbTabla.idUsuario INNER JOIN $dbTabla3 ON $dbTabla3.idTFG=$dbTabla2.idTFG";
	$result = $db->prepare($consulta);
	$result->execute();
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
		$consulta = "SELECT COUNT($dbTabla.idTFG) from $dbTabla INNER JOIN $dbTabla2 ON  $dbTabla.idAlum= $dbTabla2.idUsuario AND $dbTabla2.nombre=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG = $dbTabla2.idUsuario"; 
		$result = $db->prepare($consulta);
		$result->execute(array(":iu" => $nombre));
		$total = $result->fetchColumn();
		if($total==19){
			$dbTabla3='Pert2';
			$consulta = "SELECT COUNT($dbTabla.idTFG) from $dbTabla INNER JOIN $dbTabla2 ON  $dbTabla.idAlum= $dbTabla2.idUsuario AND $dbTabla2.nombre=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG = $dbTabla2.idUsuario"; 
			$result = $db->prepare($consulta);
			$result->execute(array(":iu" => $nombre));
			$total = $result->fetchColumn();
			if($total==11){
				$dbTabla3='Pert3';
				$consulta = "SELECT COUNT($dbTabla.idTFG) from $dbTabla INNER JOIN $dbTabla2 ON  $dbTabla.idAlum= $dbTabla2.idUsuario AND $dbTabla2.nombre=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG = $dbTabla2.idUsuario"; 
				$result = $db->prepare($consulta);
				$result->execute(array(":iu" => $nombre));
				$total = $result->fetchColumn();
				if($total==9){
					$contador=3;
				}else{
					$contador=2;
				}
			}else{
				$contador = 1;
			}
		}else{
			$contador=0;
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
		switch(selected) {
		  case "diseny":
		  	$('#select_estudiant').append("<option value='pregunta'>Selecciona l'estudiant</option>");
		    diseny.forEach(function(element,index){
				if(index%2==0){
					$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
				}
			});
		    break;
		  case "multi":
		  	$('#select_estudiant').append("<option value='pregunta'>Selecciona l'estudiant</option>");
		    multi.forEach(function(element,index){
				if(index%2==0){
					$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
				}
			});
		    break;
		  case "jocs":
		  	$('#select_estudiant').append("<option value='pregunta'>Selecciona l'estudiant</option>");
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
		}
		$('#select_rubrica').empty();
		if(valor>0 && valor<4){
			$('#select_rubrica').append('<option value="pregunta">Selecciona la rúbrica</option>');
		}
		switch(valor){
			case "3":
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				$('#select_rubrica').append('<option value="2">Rúbrica 2</option>');
				$('#select_rubrica').append('<option value="3">Rúbrica 3</option>');
				break;
			case "2":
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				$('#select_rubrica').append('<option value="2">Rúbrica 2</option>');
				break;
			case "1":
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				break;
			case "0":
				$('#select_rubrica').append('<option value="no">No hay rúbricas corregidas.</option>');
				break;
			default:
				$('#select_rubrica').append('<option value="pregunta">Selecciona la rúbrica</option>');
				break;
        }
	}
    fillRub();
};
function look(){
    var select=$('#select_rubrica').val();
    if((select != "pregunta") && (select != "no")){
        $("#submit-btn").removeAttr("disabled");
    }else{
        $("#submit-btn").prop("disabled", "disabled");
    }
};
</script>