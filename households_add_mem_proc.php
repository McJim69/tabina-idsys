<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['bSave'])){	
		$hm_names = $_POST['hm_name'];
		$hm_belong = mysqli_real_escape_string($link, $_POST['hm_belong']);
		$purok = mysqli_real_escape_string($link, $_POST['purok']);
		$barangay = mysqli_real_escape_string($link, $_POST['barangay']);
		$city_mun = mysqli_real_escape_string($link, $_POST['city_mun']);
		
		$success = true;
		$inserted_count = 0;
		
		// Loop through all submitted member rows
		for($idx = 0; $idx < count($hm_names); $idx++) {
			$name = trim($hm_names[$idx]);
			if($name === "") {
				continue; // Skip blank rows
			}
			
			$sex = mysqli_real_escape_string($link, $_POST['hm_sex'][$idx]);
			$birth = mysqli_real_escape_string($link, $_POST['hm_birth'][$idx]);
			$education = mysqli_real_escape_string($link, $_POST['hm_education'][$idx]);
			$enrolled = mysqli_real_escape_string($link, $_POST['hm_enrolled'][$idx]);
			$main_income = mysqli_real_escape_string($link, $_POST['hm_main_income'][$idx]);
			$second_income = mysqli_real_escape_string($link, $_POST['hm_second_income'][$idx]);
			$est_income = mysqli_real_escape_string($link, $_POST['hm_estimated_income'][$idx]);
			$social = mysqli_real_escape_string($link, $_POST['hm_social'][$idx]);
			$remarks = mysqli_real_escape_string($link, $_POST['hm_remarks'][$idx]);
			
			$query = "INSERT INTO hh_members VALUES(
				0, 
				'$hm_belong', 
				'$purok', 
				'$barangay', 
				'$city_mun', 
				'" . mysqli_real_escape_string($link, $name) . "', 
				'$sex', 
				'$birth', 
				'$education', 
				'$enrolled', 
				'$main_income', 
				'$second_income', 
				'$est_income', 
				'$social', 
				'$remarks', 
				0
			)";
			
			$update = $link->query($query);
			if($update) {
				$inserted_count++;
			} else {
				$success = false;
				break;
			}
		}
		
		if($success && $inserted_count > 0){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Added " . $inserted_count . " member(s) to Household ID: " . $hm_belong);

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
			</script>';		}	
	}
?>
