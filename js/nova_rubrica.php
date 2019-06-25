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
		$dbTabla4='RUBRICA1';
		$consulta = "SELECT $dbTabla4.documento,COUNT($dbTabla3.idTFG) as contador FROM $dbTabla4 INNER JOIN $dbTabla3 ON  $dbTabla3.idTFG=$dbTabla4.idTFG WHERE $dbTabla4.idTFG=(SELECT idTFG FROM $dbTabla WHERE idAlum=(SELECT idUsuario FROM $dbTabla2 WHERE nombre=:iu))"; 
		//SELECT RUBRICA2.documento,COUNT(Pert2.idTFG) FROM RUBRICA2 INNER JOIN Pert2 ON Pert2.idTFG=RUBRICA2.idTFG WHERE RUBRICA2.idTFG=(SELECT idTFG FROM Tiene WHERE idAlum=(SELECT idUsuario FROM ALUMNO WHERE nombre='Alexander'))
		$result = $db->prepare($consulta);
		$result->execute(array(":iu" => $nombre));
		$total = $result->fetchObject();
		if(($total->contador == 0) && ($total->documento != null)){
			$contador = 1;
		}else{
			$dbTabla3='Pert2';
			$dbTabla4='RUBRICA2';
			$consulta = "SELECT $dbTabla4.documento,COUNT($dbTabla3.idTFG) as contador FROM $dbTabla4 INNER JOIN $dbTabla3 ON  $dbTabla3.idTFG=$dbTabla4.idTFG WHERE $dbTabla4.idTFG=(SELECT idTFG FROM $dbTabla WHERE idAlum=(SELECT idUsuario FROM $dbTabla2 WHERE nombre=:iu))"; 			$result = $db->prepare($consulta);
			$result->execute(array(":iu" => $nombre));
			$total = $result->fetchObject();
			if(($total->contador == 0) && ($total->documento != null)){
				$contador=2;
			}else{
				$dbTabla3='Pert3';
				$dbTabla4='RUBRICA3';
				$consulta = "SELECT $dbTabla4.documento,COUNT($dbTabla3.idTFG) as contador FROM $dbTabla4 INNER JOIN $dbTabla3 ON  $dbTabla3.idTFG=$dbTabla4.idTFG WHERE $dbTabla4.idTFG=(SELECT idTFG FROM $dbTabla WHERE idAlum=(SELECT idUsuario FROM $dbTabla2 WHERE nombre=:iu))"; 				$result = $db->prepare($consulta);
				$result->execute(array(":iu" => $nombre));
				$total = $result->fetchObject();
				if(($total->contador == 0) && ($total->documento != null)){
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
			  if(diseny.length == 0){
				$('#select_estudiant').empty();
				$('#select_estudiant').append('<option value="no">No hi ha alumnes registrats.</option>');
			  }else{
				diseny.forEach(function(element,index){
					if(index%2==0){
						$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
					}
				});
			  }
		    break;
		  case "multi":
				if(multi.length == 0){
					$('#select_estudiant').empty();
					$('#select_estudiant').append('<option value="no">No hi ha alumnes registrats.</option>');
				}else{
					multi.forEach(function(element,index){
						if(index%2==0){
							$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
						}
					});
			  	}
		    break;
		  case "jocs":
		  	  if(jocs.length == 0){
				$('#select_estudiant').empty();
				$('#select_estudiant').append('<option value="no">No hi ha alumnes registrats.</option>');
			  }else{
				jocs.forEach(function(element,index){
					if(index%2==0){
						$('#select_estudiant').append('<option value="'+element+'">'+element+'</option>');
					}
				});
			  }
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
		  		if(element == eSelect){
					valor = diseny[index+1];
				}
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
		if(valor!=0){
			$('#select_rubrica').append('<option value="pregunta">Selecciona la Rúbrica</option>');
		}
		switch(valor){
			case "1":
				$('#select_rubrica').append('<option value="1">Rúbrica 1</option>');
				break;
			case "2":
				$('#select_rubrica').append('<option value="2">Rúbrica 2</option>');
				break;
			case "3":
				$('#select_rubrica').append('<option value="3">Rúbrica 3</option>');
				break;
			default:
				$('#select_rubrica').append('<option value="no">No hi ha rúbricas per corregir.</option>');
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