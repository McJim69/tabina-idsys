<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn 		= $_POST['idn'];		
		$name_1st 	= $_POST['name_1st'];
		$name_mid 	= $_POST['name_mid'];
		$name_fam 	= $_POST['name_fam'];
		$name_ext 	= $_POST['name_ext'];
		$sap_form 	= $_POST['sap_form'];
		$barangay 	= $_POST['barangay'];
		$period 	= $_POST['period'];
		$amount 	= $_POST['amount'];
		$date_paid	= $_POST['date_paid'];
		$remarks 	= $_POST['remarks'];

	$update = $link->query("UPDATE sap_ben set
		idn 		= '$idn',	
		name_1st 	= '$name_1st',
		name_mid 	= '$name_mid',
		name_fam 	= '$name_fam',			
		name_ext 	= '$name_ext',			
		barangay 	= '$barangay',
		period 		= '$period',
		amount 		= '$amount',
		date_paid	= '$date_paid', 
		remarks 	= '$remarks' where idn = '$idn'");
			
		include('qrlib/qrlib.php');		

		if(($update)== TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Updated SAP Beneficiary record: " . $fullname . " (ID: " . $idn . ")");


			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$data = "".$name_1st." ".$name_mid." ".$name_fam." ".$name_ext."-".$idn."\nSAP Form No.: ".$sap_form." ".$barangay."";		
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
