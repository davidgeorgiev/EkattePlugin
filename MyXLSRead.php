<?php
error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors', 1);
require_once 'phpExcelReader/Excel/reader.php';
// ExcelFile($filename, $encoding);
$data =& new Spreadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('UTF8');
if(isset($_GET["fileToRead"])){
	//echo '<script>alert("'.$_GET["fileToRead"].'")</script>';
	PrintInnerXls('./download/'.$_GET["fileToRead"]);
}
function PrintInnerXls($xlsName){
	global $data;
	$data->read($xlsName);
	echo '<h2>'.$xlsName.'</h2>';
	for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
		for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
			echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
		}
		echo "<br/>";
	}
}
?>
