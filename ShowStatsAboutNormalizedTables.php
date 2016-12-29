<?php
error_reporting(E_ALL ^ E_NOTICE);
require "../../../wp-load.php";

function startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function CountAndShow($MainTablename){
	global $wpdb;
	$MyTables = $wpdb->get_results("SHOW TABLES;");	
	foreach($MyTables as $table){
		if(startsWith($table->Tables_in_wordpress, $MainTablename)){
			$MyNumberOfRows = $wpdb->get_results("SELECT * FROM ".$table->Tables_in_wordpress.";");
			echo '<p>Rows in table '.$table->Tables_in_wordpress.' are '.count($MyNumberOfRows).'</p>';		
		}

	}
}

function ShowStatsAboutNormalizedTables(){
	global $wpdb;
	CountAndShow("EkatteTableN");
}

ShowStatsAboutNormalizedTables();

?>
