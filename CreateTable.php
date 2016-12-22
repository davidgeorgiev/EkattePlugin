<?php
error_reporting(E_ALL ^ E_NOTICE);
//ini_set('display_errors', 1);
require_once 'phpExcelReader/Excel/reader.php';
require "../../../wp-load.php";
// ExcelFile($filename, $encoding);
$data =& new Spreadsheet_Excel_Reader();
// Set output Encoding.
$data->setOutputEncoding('UTF8');
if(isset($_GET["fileToRead"])){
	//echo '<script>alert("'.$_GET["fileToRead"].'")</script>';
	MakeTableFromXls('./download/'.$_GET["fileToRead"]);
}
function MakeTableFromXls($xlsName){
	global $data;
	global $wpdb;
	$data->read($xlsName);
	//echo '<h2>'.$xlsName.'</h2>';
	$sql1 = "DROP TABLE IF EXISTS EkattePluginTemp".chop($_GET["fileToRead"],".xls").";";
	$sql2 = "CREATE TABLE EkattePluginTemp".chop($_GET["fileToRead"],".xls")."(";
	$Type = " varchar(255)";
	$ListForInsert = "";
	for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
		$sql2.= $data->sheets[0]['cells'][1][$j];
		$ListForInsert .= $data->sheets[0]['cells'][1][$j];
		$sql2.= $Type;
		if($j != $data->sheets[0]['numCols']){
			$sql2.=",";
			$ListForInsert.=",";
		}
	}
	$sql2.= ") ENGINE=InnoDB DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_unicode_ci;";
	$EverythingIsOk = 1;
	$Res1 = $wpdb->get_results($sql1);
	if (is_null($Res1) || !empty($wpdb->last_error)) {
		$EverythingIsOk = 0;
	}
	$Res2 = $wpdb->get_results($sql2);
	if (is_null($Res2) || !empty($wpdb->last_error)) {
		$EverythingIsOk = 0;
	}
	if($EverythingIsOk == 1){
		echo "<p style='color:green;'>Table "."EkattePluginTemp".chop($_GET["fileToRead"],".xls")." created successfully</p>";
		echo "<p style='color:orange;'>Query: ".$sql1.$sql2."</p>";
	}else{
		echo "<p style='color:red;'>Error cant create table "."EkattePluginTemp".chop($_GET["fileToRead"],".xls")."</p>";
	}
	
	//INSERTING INTO TABLE
	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
		$EverythingIsOk = 1;
		$sql3 = "INSERT INTO "."EkattePluginTemp".chop($_GET["fileToRead"],".xls")."(".$ListForInsert.") VALUES(";
		for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
			$sql3.="\"".str_replace("\"","\\\"",$data->sheets[0]['cells'][$i][$j])."\"";
			
			if($j != $data->sheets[0]['numCols']){
				$sql3.=",";
			}
		}
		$sql3.=");";
		$Res3 = $wpdb->get_results($sql3);
		if (is_null($Res3) || !empty($wpdb->last_error)) {
			$EverythingIsOk = 0;
		}
		if($EverythingIsOk == 1){
			echo "<p style='color:green;'>Row inserted successfully.</p>";
			echo "<p style='color:orange;'>Query: ".$sql3."</p>";
		}else{
			echo "<p style='color:red;'>Insertion failed.</p>";
		}
	}
}
?>
