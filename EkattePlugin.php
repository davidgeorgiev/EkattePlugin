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
	echo '<input type="text" id="InputUrl">';
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
}



?>