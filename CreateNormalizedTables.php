<?php
error_reporting(E_ALL ^ E_NOTICE);
require "../../../wp-load.php";

function EkatteStringStartsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}

function ShowTempTables(){
	global $wpdb;
	echo '<h3 style="color:orange;">Your temp tables here</h3>';
	$MyResult = $wpdb->get_results("SHOW TABLES;");
	
	for($i = 0;$i<count($MyResult);$i++){
		if(EkatteStringStartsWith($MyResult[$i]->Tables_in_wordpress,"EkattePluginTemp")){
			echo '<p>'.$MyResult[$i]->Tables_in_wordpress.'</p>';
		}
	}
}
function CreateNormalizedTables(){
	
}
echo '<h2 style="color:green;";>Normalized tables info<h3>';
ShowTempTables();
CreateNormalizedTables();
?>
