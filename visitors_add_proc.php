<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST["bSave"])){
	$insert = $link->query("insert into visitors values(
		'".$_POST["idn"]."',
		'".$_POST["name_fam"]."',
		'".$_POST["name_1st"]."',
		'".$_POST["name_mid"]."',
		'".$_POST["sex"]."',	
		'".$_POST["position"]."',			
		'".$_POST["station"]."',
		'".$_POST["office"]."',		
		'".$_POST["purok"]."',
		'".$_POST["contact"]."',
		'".$_POST["emailadd"]."',
		'".$_POST["visit_month"]."',		
		'".$_POST["visit_day_from"]."',		
		'".$_POST["visit_day_to"]."',		
		'".$_POST["visit_year"]."',
		'".$_POST["visit_purpose"]."',0)");

		include('qrlib/qrlib.php');

		if(($insert)==TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Created new Visitor record: " . $fullname . " (ID: " . $_POST["idn"] . ")");


			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$idn = "".$_POST['idn']."";
			$vin = str_pad($idn, 5, '0', STR_PAD_LEFT); 				
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"].". ".$_POST["name_fam"]."\n".$_POST["position"]."\n".$_POST["office"]."\nCA Number: ".$vin."";		
			$tempDir = "images/visitors/qrcodes/";
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
