<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST["bSave"])){
		$session_user_val = isset($_SESSION['uno']) ? intval($_SESSION['uno']) : 0;

	$insert = $link->query("INSERT INTO permit_operate (
		idn, is_mode, tradename, purok, barangay, city_mun, province, name_1st, name_mid, name_fam, activity, isorno, oramount, ispicset, user_id, status, timestamp, date_issued, date_or
	) VALUES (
		'".$_POST["idn"]."',
		'".$_POST["is_mode"]."',
		'".$_POST["tradename"]."',
		'".$_POST["purok"]."',
		'".$_POST["barangay"]."',
		'".$_POST["city_mun"]."',
		'".$_POST["province"]."',
		'".$_POST["name_1st"]."',
		'".$_POST["name_mid"]."',
		'".$_POST["name_fam"]."',
		'".$_POST["activity"]."',
		'".$_POST["isorno"]."',
		'".$_POST["oramount"]."',
		0,
		$session_user_val,
		'Pending',
		CURRENT_TIMESTAMP,
		'".$_POST["date_issued"]."',
		'".$_POST["date_or"]."'
	)") or die(mysqli_error($link));

		include('qrlib/qrlib.php');

		if(($insert) == TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Permit to Operate record: " . $_POST['tradename'] . " (Owned by: " . $fullname . ", ID: " . $_POST["idn"] . ")");
		

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$idn = "".$_POST["idn"]."";
			$data = "".$_POST["tradename"]."\nPO-".$idn."-".date("d-m-Y", strtotime($_POST["date_issued"]))."";		
			$tempDir = "images/permit_operate/qrcodes/";
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
