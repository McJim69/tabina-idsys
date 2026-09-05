<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST["bSave"])){
		$date_confirm = !empty($_POST["date_confirm"]) ? "'".$_POST["date_confirm"]."'" : "NULL";
		$insert = $link->query("INSERT INTO msgout (
			idn, msg_from, msg_office, msg_to, msg_attn, date_msg, msg_content, date_confirm, contact_person, contact_number, contact_email, contact_postal, ispicset
		) VALUES (
			'".$_POST["idn"]."',
			'".$_POST["msg_from"]."',
			'".$_POST["msg_office"]."',
			'".$_POST["msg_to"]."',
			'".$_POST["msg_attn"]."',
			'".$_POST["date_msg"]."',
			'".$_POST["msg_content"]."',
			$date_confirm,
			'".$_POST["contact_person"]."',
			'".$_POST["contact_number"]."',
			'".$_POST["contact_email"]."',
			'".$_POST["contact_postal"]."',
			0
		)") or die(mysqli_error($link));

		include('qrlib/qrlib.php');

		if(($insert) == TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Sent Outbox Message (ID: " . $_POST["idn"] . ")");

			
			$idn = $_POST["idn"];
			$data = "Fm: ".$_POST["msg_from"]."\nTo :".$_POST["msg_to"]."\nMsg ID: ".$idn." Dated: ".date("m-d-Y", strtotime($_POST["date_msg"]))."\nRE: ".$_POST["msg_content"]."";		
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
