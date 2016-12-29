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

function PrintTitleOfTheQuery($title){
	echo "<h2 style='color:brown;'>".$title."</h2>";
}

function PrintSettlementNumber($SettlementsCounter){
	echo "<h1>SETTLEMENT ".$SettlementsCounter."</h1>";
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
	$MyResSettlements = $wpdb->get_results("SELECT name,abc,ekatte,oblast,obstina,tsb,document,t_v_m FROM EkattePluginTempEk_atte WHERE abc != '';");
	$SettlementsCounter = 0;
	foreach($MyResSettlements as $CurrentSettlement){
		$SettlementsCounter++;
		if($SettlementsCounter >= $Last){
			PrintSettlementNumber($SettlementsCounter);
			
			PrintTitleOfTheQuery("CHECK IF SETTLEMENT IF EXISTS");//CHECK IF SETTLEMENT IF EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkatteTableNSettlement WHERE ekatte = ".$CurrentSettlement->ekatte.";";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifSettlementExists = count($MyRes);
			
			
			if($ifSettlementExists&&$EverythingIsOk){
				PrintTitleOfTheQuery("GET UID OF THE SETTLEMENT TO DELETE");//GET UID OF THE SETTLEMENT TO DELETE
				$EverythingIsOk = 1;
				$sql = "SELECT * FROM EkatteTableNSettlement WHERE ekatte = ".$CurrentSettlement->ekatte.";";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
				$IdOfTheSettlementToDelete = $MyRes[0]->U_ID; 
				
				PrintTitleOfTheQuery("DELETE CONNECTION SETTLEMENT-MANICIPALITY IF EXISTS");//DELETE CONNECTION SETTLEMENT-MANICIPALITY IF EXISTS
				$EverythingIsOk = 1;
				$sql = "DELETE FROM EkatteTableNManicipalitySettlement WHERE settlement_id = ".$IdOfTheSettlementToDelete.";";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
				
				PrintTitleOfTheQuery("DELETE SETTLEMENT IF EXISTS");//DELETE SETTLEMENT IF EXISTS
				$EverythingIsOk = 1;
				$sql = "DELETE FROM EkatteTableNSettlement WHERE U_ID = ".$IdOfTheSettlementToDelete.";";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
			}
			PrintTitleOfTheQuery("INSERTING SETTLEMENT");//INSERTING SETTLEMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNSettlement (name,ekatte) VALUES('".$CurrentSettlement->name."',".$CurrentSettlement->ekatte.")";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$InsertedSettlementId = $wpdb->insert_id;
			
			
			PrintTitleOfTheQuery("GETTING REAL AREA");//GETTING REAL AREA
			$realarea = preg_replace("/[^A-Z]/","",$CurrentSettlement->obstina);
			
			
			PrintTitleOfTheQuery("CHECK IF AREA EXISTS");//CHECK IF AREA EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkatteTableNArea WHERE curtailment='".$realarea."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifareaexists = count($MyRes);
			
			
			PrintTitleOfTheQuery("GET NEEDED AREA");//GET NEEDED AREA
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkattePluginTempEk_ob WHERE oblast='".$realarea."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$areaname = $MyRes[0]->name;
			$areaekatte = $MyRes[0]->ekatte;
			$areadocument = $MyRes[0]->document;
			
			
			PrintTitleOfTheQuery("INSERT AREA IF NEEDED");//INSERT AREA IF NEEDED
			$EverythingIsOk = 1;
			if(!$ifareaexists){
				$sql = "INSERT INTO EkatteTableNArea (name,ekatte,curtailment) VALUES('".$areaname."',".$areaekatte.",'".$realarea."');";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
			}
			
			
			PrintTitleOfTheQuery("SELECT UID OF THE AREA");//SELECT UID OF THE AREA
			$EverythingIsOk = 1;
			$sql = "SELECT U_ID FROM EkatteTableNArea WHERE curtailment='".$realarea."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$areaid = $MyRes[0]->U_ID;
			
			
			PrintTitleOfTheQuery("GET NAME AND EKATTE OF THE MANICIPALITY");//GET NAME AND EKATTE OF THE MANICIPALITY
			$EverythingIsOk = 1;
			$sql = "SELECT name,ekatte FROM EkattePluginTempEk_obst WHERE obstina='".$CurrentSettlement->obstina."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$manicipalityname = $MyRes[0]->name;
			$manicipalityekatte = $MyRes[0]->ekatte;
			
			
			PrintTitleOfTheQuery("CHECK IF MANICIPALITY EXISTS");//CHECK IF MANICIPALITY EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkatteTableNManicipality WHERE ekatte = ".$manicipalityekatte.";";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifmanicipalityexists = count($MyRes);
			
			
			PrintTitleOfTheQuery("INSERT MANICIPALITY IF NEEDED");//INSERT MANICIPALITY IF NEEDED
			if(!$ifmanicipalityexists){
				$EverythingIsOk = 1;
				$sql = "INSERT INTO EkatteTableNManicipality (name,ekatte) VALUES('".$manicipalityname."',".$manicipalityekatte.");";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
			}
			
			
			PrintTitleOfTheQuery("GET MANICIPALITY UID");//GET MANICIPALITY UID
			$EverythingIsOk = 1;
			$sql = "SELECT U_ID FROM EkatteTableNManicipality WHERE ekatte = '".$manicipalityekatte."';";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$manicipalityid = $MyRes[0]->U_ID;
			
			
			PrintTitleOfTheQuery("GETTING NUMBER OF THE MANICIPALITY");//GETTING NUMBER OF THE MANICIPALITY
			$manicipalitynumber = preg_replace("/[^0-9]/","",$CurrentSettlement->obstina);
			
			
			PrintTitleOfTheQuery("CHECK IF CONNECTION MANICIPALITY - AREA IS EXISTS");//CHECK IF CONNECTION MANICIPALITY - AREA IS EXISTS
			$EverythingIsOk = 1;
			$sql = "SELECT * FROM EkatteTableNManicipalityAreaNumber WHERE manicipality_id=".$manicipalityid." AND area_id=".$areaid." AND manicipality_number=".$manicipalitynumber.";";
			$MyRes = $wpdb->get_results($sql);
			if (is_null($MyRes) || !empty($wpdb->last_error)) {
				$EverythingIsOk = 0;
			}
			FeedBack($EverythingIsOk,$sql);
			$ifconnectionmanicipalityareaexists = count($MyRes);
			
			
			PrintTitleOfTheQuery("CONNECTING MANICIPALITY AND AREA");//CONNECTING MANICIPALITY AND AREA
			if(!$ifconnectionmanicipalityareaexists){
				$EverythingIsOk = 1;
				$sql = "INSERT INTO EkatteTableNManicipalityAreaNumber(manicipality_id,area_id,manicipality_number) VALUES(".$manicipalityid.",".$areaid.",".$manicipalitynumber.");";
				$MyRes = $wpdb->get_results($sql);
				if (is_null($MyRes) || !empty($wpdb->last_error)) {
					$EverythingIsOk = 0;
				}
				FeedBack($EverythingIsOk,$sql);
			}
			
			
			PrintTitleOfTheQuery("CONNECTING MANICIPALITY AND SETTLEMENT");//CONNECTING MANICIPALITY AND SETTLEMENT
			$EverythingIsOk = 1;
			$sql = "INSERT INTO EkatteTableNManicipalitySettlement(manicipality_id,settlement_id) VALUES(".$manicipalityid.",".$InsertedSettlementId.");";
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
