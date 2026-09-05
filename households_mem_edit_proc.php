<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){
		$hmid = $_POST['hmid'];
		$hm_belong = $_POST['hm_belong'];	
		$purok = $_POST['purok'];	
		$barangay = $_POST['barangay'];	
		$city_mun = $_POST['city_mun'];	
		$hm_name = $_POST['hm_name'];
		$hm_sex = $_POST['hm_sex'];
		$hm_birth = $_POST['hm_birth'];
		$hm_education = $_POST['hm_education'];
		$hm_enrolled = $_POST['hm_enrolled'];
		$hm_main_income = $_POST['hm_main_income'];
		$hm_second_income = $_POST['hm_second_income'];
		$hm_estimated_income = $_POST['hm_estimated_income'];
		$hm_social = $_POST['hm_social'];
		$hm_remarks = $_POST['hm_remarks'];
		
	$update = $link->query("UPDATE hh_members set
		hmid = '$hmid',
		hm_belong = '$hm_belong',
		purok = '$purok',
		barangay = '$barangay',
		city_mun = '$city_mun',
		hm_name = '$hm_name',
		hm_sex = '$hm_sex',
		hm_birth = '$hm_birth',
		hm_education = '$hm_education',
		hm_enrolled = '$hm_enrolled',
		hm_main_income = '$hm_main_income',
		hm_second_income = '$hm_second_income',
		hm_estimated_income = '$hm_estimated_income',
		hm_social = '$hm_social',
		hm_remarks = '$hm_remarks' where hmid = '$hmid'")or die (mysqli_error($link));

		if(($update)== TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Household Member: " . $_POST['hm_name'] . " (ID: " . $hmid . ")");

		echo'
			<script type="text/javascript">
				swal({
				  title: "Success!",
				  text: "Data process successfully for '.$hm_name.'",
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
