<?php
error_reporting(E_ALL ^ E_NOTICE);
require "../../../wp-load.php";

function FeedBack($EverythingIsOk,$sql){
	if($EverythingIsOk == 1){
		echo "<p style='color:green;'>Query succeed! :)</p>";
		echo "<p style='color:orange;'>Query: ".$sql."</p>";
	}else{
		echo "<p style='color:red;'>Query failed! :(</p>";
		echo "<p style='color:red;'>Query: ".$sql."</p>";
	}
}

function DocumentCreateIfNotExists($documentid){
		global $wpdb;
	//CHECKING IF DOCUMENT EXISTS
		$EverythingIsOk = 1;
		$sql = "SELECT * FROM EkatteTableNDocument WHERE U_ID = ".$documentid.";";
		$MyRes = $wpdb->get_results($sql);
		if (is_null($MyRes) || !empty($wpdb->last_error)) {
			$EverythingIsOk = 0;
		}
		FeedBack($EverythingIsOk,$sql);
		$ifdocumentexists = count($MyRes);
		//GETTING NEEDED DOCUMENT
		$EverythingIsOk = 1;
		$sql = "SELECT document,doc_date,doc_name FROM EkattePluginTempEk_doc WHERE document ='".$documentid."';";
		$MyRes = $wpdb->get_results($sql);
		if (is_null($MyRes) || !empty($wpdb->last_error)) {
			$EverythingIsOk = 0;
		}
		FeedBack($EverythingIsOk,$sql);
		$mydocument = $MyRes[0]->document;
		$mydocumentdate = $MyRes[0]->doc_date;
		$mydocumentname = $MyRes[0]->doc_name;
		//INSERT DOCUMENT IF NEEDED
		$EverythingIsOk = 1;
		if(!$ifdocumentexists){
			$sql = "INSERT INTO EkatteTableNDocument (U_ID,name,doc_date) VALUES(".$mydocument.",'".$mydocumentname."',".$mydocumentdate.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifdocumentexists = count($MyRes);
		}
}

function InsertIntoNormalizedTables(){
	global $wpdb;
	$Last = 0;
	$MyRes = $wpdb->get_results("SELECT U_ID FROM EkatteTableNSettlement;");
	if (is_null($MyRes) || !empty($wpdb->last_error)) {
		$Last = 0;
	}else{
		$max = 0;
		foreach($MyRes as $curr){
			if($max<$curr->U_ID){
				$max = $curr->U_ID;
			}
		}
		$Last = $max;
	}
	$MyResSettlements = $wpdb->get_results("SELECT DISTINCT(name),abc,ekatte,oblast,obstina,tsb,document,t_v_m FROM EkattePluginTempEk_atte WHERE abc != '';");
	$SettlementsCounter = 0;
	foreach($MyResSettlements as $CurrentSettlement){
		$SettlementsCounter++;
		if(($SettlementsCounter >= $Last)&&($SettlementsCounter<=40)){
			echo "<h1>SETTLEMENT ".$SettlementsCounter."</h1>";
			//INSERTING SETTLEMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNSettlement (name,ekatte) VALUES('".$CurrentSettlement->name."',".$CurrentSettlement->ekatte.")";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$InsertedSettlementId = $wpdb->insert_id;
			//INSERTING KIND
			$sql = "";
			$EverythingIsOk = 1;
			if(strpos($CurrentSettlement->t_v_m, 'с') !== false){
				$sql = "INSERT INTO EkatteTableNSettlementKind (kind_id, settlement_id) VALUES (2,".$InsertedSettlementId.")";
			}else if(strpos($CurrentSettlement->t_v_m, 'гр') !== false){
				$sql = "INSERT INTO EkatteTableNSettlementKind (kind_id, settlement_id) VALUES (1,".$InsertedSettlementId.")";
			}
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			//GETTING NEEDED TSB
			$EverythingIsOk = 1;
			$sql = "SELECT tsb,name FROM EkattePluginTempEk_tsb WHERE tsb ='".$CurrentSettlement->tsb."'";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$mytsb = $MyRes[0]->tsb;
			$mytsbname = $MyRes[0]->name;
			//CHECKING IF TSB EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkatteTableNTSB WHERE name = '".$mytsbname."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			$IfExists = count($MyRes);
			FeedBack($EverythingIsOk,$sql);
			//INSERTING TSB
			if(!$IfExists){
				$EverythingIsOk = 1;
				$sql = "INSERT INTO EkatteTableNTSB (name,curtailment) VALUES('".$mytsbname."','".$mytsb."')";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
			}
			//SELECT TSB UID
			$EverythingIsOk = 1;
			$sql = "SELECT U_ID FROM EkatteTableNTSB WHERE name = '".$mytsbname."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			$TSBUID = $MyRes[0]->U_ID;
			FeedBack($EverythingIsOk,$sql);
			//CONNECT TSB AND SETTLEMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNSettlementTSB (tsb_id, settlement_id) VALUES(".$TSBUID.",".$InsertedSettlementId.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			//CREATING DOCUMENT IF NOT EXISTS
			DocumentCreateIfNotExists($CurrentSettlement->document);
			//CONNECTING DOCUMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNDocumentSettlement (document_id,settlement_id) VALUES(".$CurrentSettlement->document.",".$InsertedSettlementId.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			//CHECK IF AREA EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkatteTableNArea WHERE curtailment='".$CurrentSettlement->oblast."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifareaexists = count($MyRes);
			//GET NEEDED AREA
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkattePluginTempEk_ob WHERE oblast='".$CurrentSettlement->oblast."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$areaname = $MyRes[0]->name;
			$areaekatte = $MyRes[0]->ekatte;
			$areadocument = $MyRes[0]->document;
			//INSERT AREA IF NEEDED
			$EverythingIsOk = 1;
			if(!$ifareaexists){
				$sql = "INSERT INTO EkatteTableNArea (name,ekatte,curtailment) VALUES('".$areaname."',".$areaekatte.",'".$CurrentSettlement->oblast."');";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
			}
			//SELECT UID OF THE AREA
			$EverythingIsOk = 1;
			$sql = "SELECT U_ID FROM EkatteTableNArea WHERE curtailment='".$CurrentSettlement->oblast."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$areaid = $MyRes[0]->U_ID;
			//GET NAME AND EKATTE AND DOCUMENT OF THE MANICIPALITY
			$EverythingIsOk = 1;
			$sql = "SELECT name,ekatte,document FROM EkattePluginTempEk_obst WHERE obstina='".$CurrentSettlement->obstina."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$manicipalityname = $MyRes[0]->name;
			$manicipalityekatte = $MyRes[0]->ekatte;
			$manicipalitydocument = $MyRes[0]->document;
			//CHECK IF MANICIPALITY EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkatteTableNManicipality WHERE name='".$manicipalityname."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifmanicipalityexists = count($MyRes);
			//INSERT MANICIPALITY IF NEEDED
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNManicipality (name,ekatte) VALUES('".$manicipalityname."',".$manicipalityekatte.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			//GET MANICIPALITY UID
			$EverythingIsOk = 1;
			$sql = "SELECT U_ID FROM EkatteTableNManicipality WHERE name = '".$manicipalityname."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$manicipalityid = $MyRes[0]->U_ID;
			//GETTING NUMBER OF THE MANICIPALITY
			$manicipalitynumber = preg_replace("/[^0-9]/","",$CurrentSettlement->obstina);
			//CONNECTING MANICIPALITY AND AREA
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNManicipalityAreaNumber(manicipality_id,area_id,manicipality_number) VALUES(".$manicipalityid.",".$areaid.",".$manicipalitynumber.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			//CONNECTING MANICIPALITY AND SETTLEMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNManicipalitySettlement(manicipality_id,settlement_id) VALUES(".$manicipalityid.",".$InsertedSettlementId.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			
			DocumentCreateIfNotExists($manicipalitydocument);
			DocumentCreateIfNotExists($areadocument);
			//CONNECTING DOCUMENT TO MANICIPALITY
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNDocumentManicipality (document_id,manicipality_id) VALUES(".$CurrentSettlement->document.",".$manicipalityid.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			//CONNECTING DOCUMENT TO AREA
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNDocumentArea (document_id,area_id) VALUES(".$CurrentSettlement->document.",".$areaid.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			
		}
	}
}
echo '<h2 style="color:green;";>Inserting info is here<h3>';
InsertIntoNormalizedTables();
?>
