<?php
/*
Plugin Name: EkattePlugin
Description: create data tables and do stuff
Author: David Georgiev
Version: 0.1
*/


function MainEkattePluginFunction(){
	echo '<div id="DownloadExtractAndDeleteDiv">';
	echo '<script src="/wp-content/plugins/EkattePlugin/jquery.min.js"></script>';
	echo '<input type="text" id="InputUrl" value="http://www.nsi.bg/sites/default/files/files/EKATTE/Ekatte.zip">';
	echo '<button id="DownloadAndExtractButton" type="button">Download And Extract Zip File!</button>';
	echo '<button id="DeleteDownloadFolderButton" type="button">Delete Download Folder!</button>';
	echo '<div id="MyStatDiv"></div>';
	echo '<script>
	$("#DownloadAndExtractButton").click(function(){
		$("#MyStatDiv").load("/wp-content/plugins/EkattePlugin/DownloadAndExtractZip.php?urladdress="+$("#InputUrl").val());
	});
	$("#DeleteDownloadFolderButton").click(function(){
		$("#MyStatDiv").load("/wp-content/plugins/EkattePlugin/DownloadAndExtractZip.php?deletefolder=deleteit");
	});
	</script>';
	echo '</div>';
	echo '<div id="ShowingXLSDATA">';
		echo '<button width=100 height=100 id="ShowCurrentXLSs" type="button">Show me XLS files!</button>';
		echo '<div id="XLSsHERE"></div>';
		echo '<div id="StatCreatingTable"></div>';
		echo '<div id="InnerFilePrintsHere"></div>';
	echo '</div>';
	echo '<script>$("#ShowCurrentXLSs").click(function(){
		$("#XLSsHERE").load("/wp-content/plugins/EkattePlugin/PrintXLSButtons.php");
	});</script>';
	echo '<div id="NormalizedTablesDiv">';
	echo '<button width=100 height=100 id="CreateNormalizedTablesButton" type="button">Create normalized tables</button>';
	echo '<div id="CreatingNormalizedTablesStatDiv"></div>';
	echo '</div>';
	echo '<div id="InsertIntoNormalizedTablesDiv">';
	echo '<button width=100 height=100 id="InsertIntoNormalizedTablesButton" type="button">Insert into normalized tables</button>';
	echo '<div id="InsertIntoNormalizedTablesStatDiv"></div>';
	echo '</div>';
	echo '<script>$("#CreateNormalizedTablesButton").click(function(){
		$("#CreatingNormalizedTablesStatDiv").load("/wp-content/plugins/EkattePlugin/PrintNormalizingLoading.php");
		$("#CreatingNormalizedTablesStatDiv").load("/wp-content/plugins/EkattePlugin/CreateNormalizedTables.php");
	});</script>';
	echo '<script>$("#InsertIntoNormalizedTablesButton").click(function(){
		$("#InsertIntoNormalizedTablesStatDiv").load("/wp-content/plugins/EkattePlugin/PrintInsertNormalizingLoading.php");
		$("#InsertIntoNormalizedTablesStatDiv").load("/wp-content/plugins/EkattePlugin/InsertIntoNormalizedTables.php");
	});</script>';
	
}



?>
