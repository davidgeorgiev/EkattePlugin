<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);


if(isset($_GET["urladdress"])){
	if(DownloadAndExtractAllSubArchives($_GET["urladdress"])){
		echo '<p style="color:green;">The files are done for analysing</p>';
	}else{
		echo '<p style="color:red;">Something is gone wrong! Check the url!</p>';
	}
}
if(isset($_GET["deletefolder"])){
	if(DeleteDownloadFolder()==1){
		echo '<p style="color:green;">The folder is deleted successfully</p>';
	}else if(DeleteDownloadFolder()==0){
		echo '<p style="color:red;">Something is gone wrong! The folder can not be deleted!</p>';
	}else if(DeleteDownloadFolder()==2){
		echo '<p style="color:orange;">Something is gone wrong! The folder not exists!</p>';
	}
}


function ExtractAllZipInDir($tempfilename,$DirToExtract){
	$zip = new ZipArchive;
	$res = $zip->open($tempfilename);
	if ($res === TRUE) {
		$zip->extractTo($DirToExtract);
		$zip->close();
	}
}

function IfFilenameEndsWith($haystack, $needle) {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

function DeleteDownloadFolder(){
	if(file_exists('./download/')){
		$dir = new DirectoryIterator("./download/");
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				unlink("./download/".$fileinfo->getFilename());
			}
		}
		if(rmdir("./download")){
			return 1;
		}else{
			return 0;
		}
	}else{
		return 2;
	}
}

function DownloadAndExtractAllSubArchives($url_address){//www.nsi.bg/sites/default/files/files/EKATTE/Ekatte.zip
	DeleteDownloadFolder();
	if (!file_exists('./download/')) {
		mkdir('./download/', 0777, true);
	}
	$tempfilename = "./download/Tmpfile.zip";
	$fh = fopen($tempfilename, 'w');
	fclose($fh);
	
	if(!($WebFile = fopen($url_address, 'r'))){
		return 0;
	}
	
	file_put_contents($tempfilename, $WebFile);

	$Found_Zip = 1;
	while($Found_Zip==1){
		$Found_Zip = 0;
		$dir = new DirectoryIterator("./download/");
		foreach ($dir as $fileinfo) {
			if (!$fileinfo->isDot()) {
				if(IfFilenameEndsWith($fileinfo->getFilename(),".zip")){
					$Found_Zip = 1;
					ExtractAllZipInDir("./download/".$fileinfo->getFilename(),"./download/");
					unlink("./download/".$fileinfo->getFilename());
				}else if(!IfFilenameEndsWith($fileinfo->getFilename(),".xls")){
					unlink("./download/".$fileinfo->getFilename());
				}
			}
		}
	}
	return 1;
}

?>
