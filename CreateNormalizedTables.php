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
	global $wpdb;
	$MySqlCode = file_get_contents("Tables.sql");
	$sqls = explode(';', (string)$MySqlCode);
	foreach($sqls as $sql){
		$EverythingIsOk = 1;
		//echo '<p>'.$sql.'</p>';
		$MyRes = $wpdb->get_results($sql);
		if (is_null($MyRes) || !empty($wpdb->last_error)) {
			$EverythingIsOk = 0;
		}
		if($EverythingIsOk == 1){
			echo "<p style='color:green;'>Query succeed! :)</p>";
			echo "<p style='color:orange;'>Query: ".$sql."</p>";
		}else{
			echo "<p style='color:red;'>Query failed! :(</p>";
			echo "<p style='color:red;'>Query: ".$sql."</p>";
		}
	}
	
	
}
echo '<h2 style="color:green;";>Normalized tables info<h3>';
//ShowTempTables();
CreateNormalizedTables();
?>
