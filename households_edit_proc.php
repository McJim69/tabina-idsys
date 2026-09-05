<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$hhid = $_POST['hhid'];		
		$hh_name = $_POST['hh_name'];
		$hh_occupation = $_POST['hh_occupation'];
		$purok = $_POST['purok'];
		$barangay = $_POST['barangay'];
		$city_mun = $_POST['city_mun'];		
		$hh_sex = $_POST['hh_sex'];
		$hh_birth = $_POST['hh_birth'];
		$hh_religion = $_POST['hh_religion'];
		$hh_contact = $_POST['hh_contact'];
		$hh_ethnicity = $_POST['hh_ethnicity'];
		$date_verified = $_POST['date_verified'];

	$update = $link->query("UPDATE households set
		hhid = '$hhid',	
		hh_name = '$hh_name',
		purok = '$purok',			
		barangay = '$barangay',
		city_mun = '$city_mun',
		hh_sex = '$hh_sex',
		hh_birth = '$hh_birth',
		hh_religion = '$hh_religion', 
		hh_contact = '$hh_contact',
		hh_ethnicity = '$hh_ethnicity',
		date_verified = '$date_verified' where hhid = '$hhid'")or die (mysqli_error($link));
			
		include('qrlib/qrlib.php');		

		if(($update)== TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Household record (Head: " . $_POST['hh_name'] . ", ID: " . $hhid . ")");

			$data = "".$_POST["hh_name"]."\nHH ID: ".$hhid."-".$_POST["barangay"]."";		
			$tempDir = "images/households/qrcodes/";
			$codeContents =  "".$data."";
			$fileName = "".$hhid.".png";
			$pngAbsoluteFilePath = $tempDir.$fileName;
			QRcode::png($codeContents, $pngAbsoluteFilePath); 						

		echo'
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "Data process successfully for '.$hh_name.'",
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
