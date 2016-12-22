<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require "DownloadAndExtractZip.php";

function PrintXlsButtons(){
	if(file_exists('./download/')){
		$noXlsFiles = 1;
		$counter = 0;
		$dir = new DirectoryIterator("./download/");
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				if(IfFilenameEndsWith($fileinfo->getFilename(),".xls")){
					$counter++;
					$noXlsFiles = 0;
					echo '<p><button id="ShowFile'.$counter.'" type="button">'.$fileinfo->getFilename().'</button>';
					echo '<button id="MakeTable'.$counter.'" type="button">Make table from '.$fileinfo->getFilename().'</button></p>';
					echo '<script>$("#ShowFile'.$counter.'").click(function(){
		$("#InnerFilePrintsHere").load("/wp-content/plugins/EkattePlugin/PrintLoading.php?fileToRead='.$fileinfo->getFilename().'");
		$("#InnerFilePrintsHere").load("/wp-content/plugins/EkattePlugin/MyXLSRead.php?fileToRead='.$fileinfo->getFilename().'");
	});</script>';
					echo '<script>$("#MakeTable'.$counter.'").click(function(){
		$("#StatCreatingTable").load("/wp-content/plugins/EkattePlugin/PrintQueryLoading.php?fileToRead='.$fileinfo->getFilename().'");		
		$("#StatCreatingTable").load("/wp-content/plugins/EkattePlugin/CreateTable.php?fileToRead='.$fileinfo->getFilename().'");
	});</script>';
				}
			}
		}
		if($noXlsFiles){
			echo '<p style="color:red;">No xml files found in the download folder!</p>';
		}
	}else{
		echo '<p style="color:red;">First you have to run the download option</p>';
	}
}

PrintXlsButtons();
?>
