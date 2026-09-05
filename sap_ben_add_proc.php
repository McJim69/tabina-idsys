<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST["bSave"])){
	$insert = $link->query("insert into sap_ben values(
		'".$_POST['idn']."',	
		'".$_POST['name_fam']."',
		'".$_POST['name_1st']."',
		'".$_POST['name_mid']."',
		'".$_POST['name_ext']."',
		'".$_POST['sap_form']."',
		'".$_POST['purok']."',
		'".$_POST['barangay']."',
		'".$_POST['city_mun']."',
		'".$_POST['period']."',
		'".$_POST['amount']."',
		'".$_POST['signature']."',
		'".$_POST['date_paid']."',
		'".$_POST['remarks']."',0)");

		include('qrlib/qrlib.php');		
				
		if(($insert) == TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new SAP Beneficiary record: " . $fullname . " (ID: " . $_POST["idn"] . ")");


			$fullname = trim($_POST["name_1st"]." ".$_POST["name_mid"]." ".$_POST["name_fam"]." ".$_POST["name_ext"]);

			$idn = $_POST["idn"];
			$data = "".$fullname."-".$idn."\nSAP Form No.: ".$_POST["sap_form"]." ".$_POST["barangay"]."";		
			$tempDir = "images/sap_ben/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						

		echo'
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "Data process successfully for '.$fullname.'",
				  type: "success"
				}).then(function() {
					window.history.back();
				})
			</script>';
		}else{
		echo '
			<script type="text/javascript">
				jQuery(function validation(){
					swal({
						title: "ERROR!",
						text: "' . addslashes(mysqli_error($link)) . '",
						icon: "warning",
						button: "Close",
					}).then(() => {
						window.history.back();
					});
				});
			</script>';		
		}	
	}
?>
