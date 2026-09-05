<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST['update'])){	
		$idn = $_POST['idn'];		
		$fullname = $_POST['fullname'];
		$purok = $_POST['purok'];
		$barangay = $_POST['barangay'];
		$period = $_POST['period'];
		$amount = $_POST['amount'];
		$date_paid = $_POST['date_paid'];
		$remarks = $_POST['remarks'];

	$update = $link->query("UPDATE indigents set
		idn = '$idn',	
		fullname = '$fullname',		
		purok = '$purok',
		barangay = '$barangay',
		period = '$period',
		amount = '$amount',
		date_paid = '$date_paid', 
		remarks = '$remarks' where idn = '$idn'");
			
		include('qrlib/qrlib.php');		

		if(($update)== TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Indigent record: " . $_POST['fullname'] . " (ID: " . $idn . ")");

			
			$data = "".$_POST["fullname"]."\nID No.: ".$idn."-".$_POST["barangay"]."";		
			$tempDir = "images/indigents/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						
	
		echo'
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "'.$fullname.' : Data process successfully!",
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
