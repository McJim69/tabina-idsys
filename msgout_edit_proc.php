<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn    = $_POST['idn'];
		$from   = $_POST['msg_from'];
		$office	= $_POST['msg_office'];
		$date_msg = $_POST['date_msg'];
		$msgto  = $_POST['msg_to'];
		$attn   = $_POST['msg_attn'];
		$cont   = $_POST['msg_content'];

	$update = $link->query("UPDATE msgout set
		idn 		 = '$idn',	
		msg_from 	 = '$from', 
		msg_office   = '$office', 
		date_msg 	 = '$date_msg', 
		msg_to 		 = '$msgto', 
		msg_attn	 = '$attn',
		msg_content  = '$cont' where idn = '$idn'");
			
		include('qrlib/qrlib.php');
		
		if(($update)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Outbox Message record (ID: " . $idn . ")");

			
			$data = "Fm: ".$from."\nTo :".$msgto."\nMsg ID: ".$idn." Dated: ".date("m-d-Y", strtotime($date_msg))."\nRE: ".$cont."";		
			$tempDir = "images/msgout/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$idn.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 
			
		echo'
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "Data process successfully!",
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
