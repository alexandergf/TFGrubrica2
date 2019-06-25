<?php
include "../php/protege.php";
$parser = 'fpdi-pdf-parser';
//$filename = '../rubricas_pdf/id2.pdf';
$filename = '../rubricas_pdf/'.$_GET["rubrica"];
$pag=$_GET["pag"];
$pag=$pag-1;
$rub=$_GET["rub"];
$comentario=$_GET["comentario"];
$titulo=$_GET["title"];
$value=$_GET["value"];
$bool=$_GET["bool"];
require_once('../vendor/setasign/fpdf/fpdf.php');
require_once('../vendor/setasign/fpdi/src/autoload.php');

use setasign\Fpdi;

class Pdf extends Fpdi\Fpdi{
    /**
     * @var string
     */
    protected $pdfParserClass = null;

    /**
     * Set the pdf reader class.
     *
     * @param string $pdfParserClass
     */
    public function setPdfParserClass($pdfParserClass)
    {
        $this->pdfParserClass = $pdfParserClass;
    }
    /**
     * Get a new pdf parser instance.
     *
     * @param Fpdi\PdfParser\StreamReader $streamReader
     * @return Fpdi\PdfParser\PdfParser|setasign\FpdiPdfParser\PdfParser\PdfParser
     */
    protected function getPdfParserInstance(Fpdi\PdfParser\StreamReader $streamReader)
    {
        if ($this->pdfParserClass !== null) {
            return new $this->pdfParserClass($streamReader);
        }

        return parent::getPdfParserInstance($streamReader);
    }

    /**
     * Checks whether a compressed cross-reference reader instance was used or not.
     *
     * @return bool
     */
    public function isCompressedXref()
    {
        foreach (array_keys($this->readers) as $readerId) {
            $crossReference = $this->getPdfReader($readerId)->getParser()->getCrossReference();
            $readers = $crossReference->getReaders();
            foreach ($readers as $reader) {
                if ($reader instanceof \setasign\FpdiPdfParser\PdfParser\CrossReference\CompressedReader) {
                    return true;
                }
            }
        }

        return false;
    }
}

$pdf = new Pdf();
if ($parser === 'default') {
    $pdf->setPdfParserClass(Fpdi\PdfParser\PdfParser::class);
}
require_once("../php/conexion_pdo.php");
$db = new Conexion();
$dbTabla='comentarios'.$rub; 
$dbTabla2='TFG';
$consulta="SELECT COUNT(*) FROM $dbTabla WHERE pagina=$pag AND idTFG=(SELECT idTFG FROM $dbTabla2 WHERE titulo=$titulo)";
$result = $db->prepare($consulta);
$result->execute(array());
$total = $result->fetchColumn();
if (empty($_GET["comentario"])) {
	$comentario=" ";
}
//echo $total." ".$consulta." ";
if (empty($bool)) {
	if($total==0){
		$dbTabla='comentarios'.$rub; 
		//INSERT INTO comentarios1 (idTFG,pagina,comentario) VALUES ((SELECT idTFG FROM TFG WHERE titulo="Disseny sistemes rubriques."),4,"Cuarto comentario")
		$consulta="INSERT INTO $dbTabla (idTFG,pagina,comentario) VALUES ((SELECT idTFG FROM $dbTabla2 WHERE titulo=$titulo),$pag,\"$comentario\")";
		$result = $db->prepare($consulta);
		$result->execute();
		if (!$result) {
			//echo "mierda";
		}else{
			//echo $consulta;
		}
	}else{
		$dbTabla='comentarios'.$rub; 
		$consulta="UPDATE $dbTabla SET comentario=\"$comentario\" WHERE pagina=$pag AND idTFG=(SELECT idTFG FROM $dbTabla2 WHERE titulo=$titulo)";
		$result = $db->prepare($consulta);
		$result->execute(array());
		if (!$result) {
			//echo "mierda";
		}else{
			//echo $consulta;
		}
	}
}


$numeroPaginas= numeroPaginasPdf($filename);
if($value=="enviar"){
	for ($i=1; $i <= $numeroPaginas; $i++) { 
		$pageCount = $pdf->setSourceFile($filename);
	    $tplIdx = $pdf->ImportPage($i);
	    $pdf->AddPage();
	    $size = $pdf->useTemplate($tplIdx, 17, 10, 175);
	    $pdf->SetDrawColor(216);
	    $pdf->Rect(17, 10, 175, $size['height'], 'D');
	    //------------------------------------------
	    $dbTabla='comentarios'.$rub; 
		$dbTabla2='TFG';
		$consulta="SELECT comentario FROM $dbTabla WHERE pagina=$i AND idTFG=(SELECT idTFG FROM $dbTabla2 WHERE titulo=$titulo)";
		$result = $db->prepare($consulta);
		$result->execute();
		$com=$result->fetchColumn();
	    //------------------------------------------
	    $leftMargin = $pdf->getX();
	    $pdf->SetLeftMargin($leftMargin);
	    $pdf->SetXY(17, 263);
	    $pdf->SetFont('helvetica');
	    //arreglarlo para los comentarios
	    $pdf->Write(2, $com);
	}
	$pdf->output($filename,"F");
	$pdf->output();
}else{
	$pageCount = $pdf->setSourceFile($filename);
    $tplIdx = $pdf->ImportPage($pag+1);
    $pdf->AddPage();
    $size = $pdf->useTemplate($tplIdx, 0, 0, null);
    $pdf->SetDrawColor(216);
	$pdf->output();
}

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