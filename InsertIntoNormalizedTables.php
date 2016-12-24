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

function InsertIntoNormalizedTables(){
	global $wpdb;
	$Last = 0;
	$MyRes = $wpdb->get_results("SELECT U_ID FROM EkkateTableNSettlement;");
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
		if(($SettlementsCounter > $Last)&&($SettlementsCounter<=10)){
			echo "<h1>SETTLEMENT ".$SettlementsCounter."</h1>";
			//INSERTING SETTLEMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkkateTableNSettlement (name,ekatte) VALUES('".$CurrentSettlement->name."',".$CurrentSettlement->ekatte.")";
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
				$sql = "INSERT INTO EkkateTableNSettlementKind (kind_id, settlement_id) VALUES (2,".$InsertedSettlementId.")";
			}else if(strpos($CurrentSettlement->t_v_m, 'гр') !== false){
				$sql = "INSERT INTO EkkateTableNSettlementKind (kind_id, settlement_id) VALUES (1,".$InsertedSettlementId.")";
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
			$sql = "SELECT * FROM EkkateTableNTSB WHERE name = '".$mytsbname."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			$IfExists = count($MyRes);
			FeedBack($EverythingIsOk,$sql);
			//INSERTING TSB
			if(!$IfExists){
				$EverythingIsOk = 1;
				$sql = "INSERT INTO EkkateTableNTSB (name,curtailment) VALUES('".$mytsbname."','".$mytsb."')";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
			}
			//SELECT TSB UID
			$EverythingIsOk = 1;
			$sql = "SELECT U_ID FROM EkkateTableNTSB WHERE name = '".$mytsbname."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			$TSBUID = $MyRes[0]->U_ID;
			FeedBack($EverythingIsOk,$sql);
			//CONNECT TSB AND SETTLEMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkkateTableNSettlementTSB (tsb_id, settlement_id) VALUES(".$TSBUID.",".$InsertedSettlementId.");";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			//CHECKING IF DOCUMENT EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkkateTableNDocument WHERE U_ID = ".$CurrentSettlement->document.";";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifdocumentexists = count($MyRes);
			//GETTING NEEDED DOCUMENT
			$EverythingIsOk = 1;
			$sql = "SELECT document,doc_date,doc_name FROM EkattePluginTempEk_doc WHERE document ='".$CurrentSettlement->document."';";
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
				$sql = "INSERT INTO EkkateTableNDocument (U_ID,name,doc_date) VALUES(".$mydocument.",'".$mydocumentname."',".$mydocumentdate.");";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
				$ifdocumentexists = count($MyRes);
			}
			//CONNECTING DOCUMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkkateTableNDocumentSettlement (document_id,settlement_id) VALUES(".$CurrentSettlement->document.",".$InsertedSettlementId.");";
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
