<?PHP
	include "../php/protege.php";
	$rub = $_POST["select_rubrica"];
	$perfil=$_POST["perfil"];
	
	require_once("../php/conexion_pdo.php");
	$db = new Conexion();

	$dbTabla='Tiene'; 
	$dbTabla2='RUBRICA'.$rub;
	$dbTabla3='ALUMNO';
	if (($perfil == "profesor") || ($perfil == "coordinador")) {
		$consulta="SELECT documento FROM $dbTabla2 INNER JOIN $dbTabla ON $dbTabla2.idTFG=$dbTabla.idTFG INNER JOIN $dbTabla3 ON $dbTabla.idAlum=$dbTabla3.idUsuario AND $dbTabla3.nombre=:iu";
		$n=explode(" ", $_POST["select_estudiant"]);	
		$alumno=$n[0];
		//SELECT documento FROM RUBRICA1 INNER JOIN Tiene ON RUBRICA1.idTFG=Tiene.idTFG INNER JOIN ALUMNO ON Tiene.idAlum=ALUMNO .idUsuario AND ALUMNO.nombre='Alexander'
	}else{
		$consulta="SELECT documento FROM $dbTabla2 INNER JOIN $dbTabla ON $dbTabla2.idTFG=$dbTabla.idTFG AND $dbTabla.idAlum=:iu";
		$alumno=$_SESSION["id"];
	}
		$result = $db->prepare($consulta);
		$result->execute(array(":iu" => $alumno));
		if (!$result) {
			//Fallo
		}else{
			$fila=$result->fetchObject();
			$pdf=$fila->documento;
		}
	$dbTabla='ALUMNO'; 
	$dbTabla2='Tiene';
	$dbTabla3='TFG';
	if (($perfil == "profesor") || ($perfil == "coordinador")) {
		$consulta="SELECT nombre,apellido,$dbTabla3.titulo FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla2.idAlum=$dbTabla.idUsuario AND $dbTabla.nombre=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG=$dbTabla2.idTFG";
	}else{
		$consulta="SELECT nombre,apellido,$dbTabla3.titulo FROM $dbTabla INNER JOIN $dbTabla2 ON $dbTabla2.idAlum=$dbTabla.idUsuario AND $dbTabla.idUsuario=:iu INNER JOIN $dbTabla3 ON $dbTabla3.idTFG=$dbTabla2.idTFG";
	}
	
	//SELECT nombre,apellido,TFG.titulo FROM ALUMNO INNER JOIN Tiene ON Tiene.idAlum=ALUMNO.idUsuario AND ALUMNO.idUsuario=1 INNER JOIN TFG ON TFG.idTFG=Tiene.idTFG 
		$result = $db->prepare($consulta);
		$result->execute(array(":iu" => $alumno));
		if (!$result) {
			//Fallo
		}else{
			$fila=$result->fetchObject();
			$nom=$fila->nombre;
			$cognom=$fila->apellido;
			$titulo=$fila->titulo;
		}
		require_once("../js/rubrica.php");
			function cambiarComentario($pag,$titulo){
				require_once("../php/conexion_pdo.php");
				$db = new Conexion();
				$dbTabla='comentarios'.$_POST["select_rubrica"]; 
				$dbTabla2='TFG';
				//SELECT * FROM comentarios1 WHERE pagina=1 AND idTFG=(SELECT idTFG FROM TFG WHERE titulo="Disseny sistemes rubriques.")
				$consulta="SELECT * FROM $dbTabla WHERE pagina=:pg AND idTFG=(SELECT idTFG FROM $dbTabla2 WHERE titulo=:tit)";
				$result = $db->prepare($consulta);
				$result->execute(array(":pg" => $pag,":tit"=>$titulo));
				if (!$result) {
					//Fallo
					return "Error";
				}else{
					$fila=$result->fetchObject();
					return "$fila->comentario";
				}
			}
?>
<html>
<head>
	<title>Rubrica<?php echo $rub;?></title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="../css/header.css">
	<link rel="stylesheet" href="../css/rubrica.css">
	<link rel="stylesheet" href="../resources/fonts/fonts.css">
	<style>
		.parallax {
		background-image: url("../resources/images/rubrica<?php echo $rub; ?>.jpg");
		}
		</style>
	<script type="text/javascript" src="../js/header.js"></script>
	<!--<script type="text/javascript" src="../js/rubrica.js"></script>-->
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script> 
    $(function(){
      $("#headerOUT").load("header.php"); 
    }); 
    </script> 
</head>
<body>
	<div id="headerOUT"></div>
	<div class="parallax"></div>
	<div id="bodyOUT">
		<span class="intro" id="intro">Trabajo de Fin de Grado: <?php echo $titulo." de ".$nom." " . $cognom ; ?></span>
		<p>Documento: <?=$titulo?></p>
		<?php 
		$pag=1;
			function numeroPaginasPdf($archivoPDF){
			    $stream = fopen($archivoPDF, "r");
			    $content = fread ($stream, filesize($archivoPDF));
			    if(!$stream || !$content)
			        return 0;

			    $count = 0;
			    $regex  = "/\/Count\s+(\d+)/";
			    $regex2 = "/\/Page\W*(\d+)/";
			    $regex3 = "/\/N\s+(\d+)/";
			    if(preg_match_all($regex, $content, $matches))
			        $count = max($matches);

			    return $count[0];
			}
			
			$numMax=numeroPaginasPdf("../rubricas_pdf/".$pdf);
			if ($perfil == "profesor") {
				?>
		 		<iframe id='pdf' src='pdf.php?rubrica=<?=$pdf?>&pag=1&rub=<?=$rub?>&title="<?=$titulo?>"&bool=1'></iframe>
		 		<input type='hidden' id='numeroPag' value='1'> 
		 		<input type='hidden' id='pdfDocument' value='"<?=$pdf?>"'> 
		 		<input type='hidden' id='rubric' value='<?=$rub?>'> 
		 		<input type='hidden' id='max' value='"<?=$numMax?>"'> 
		 		<input type='hidden' id='title' value='"<?=$titulo?>"'> 
		 		<div id='btns'>
			 		<button id='atras' onclick='pagAnt()'>Anterior</button>
			 		<button id='siguiente' onclick='pagSig()'>Siguiente</button>
			 		<button id="enviar" style="display: none" onclick="finalizar()">Enviar</button>
			 		<textarea id="comentario" rows="1" cols="50"><?=cambiarComentario(1,$titulo)?></textarea>
		 		</div>
		 	<?php
		 	}else{
				echo "<iframe id='pdf' src='../rubricas_pdf/" . $pdf . "'></iframe>"; 
			}
		 	echo "<iframe id='iframe' src='evaluar_rubrica.php?rub=".$rub."&apartado=1&perfil=$perfil&nombre=$nom'></iframe>"; 
		 	if ($perfil == "profesor") {
		 		echo "<iframe id='frases' src='../resources/pdf/frases.pdf'></iframe>";
		 	}else{
				echo "<iframe id='puntuacio' src='../html/notas.php?rub=".$rub."&nombre=$nom'></iframe>";
			}
		 ?>

		<div class="return">
			<button id="btn" onclick="volver('<? echo $perfil; ?>')">Tornar a l'inici</button>
		</div>
	</div>
</body>
</html> 