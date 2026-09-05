<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST["bSave"])){
		
	$insert = $link->query("insert into households values(
		'".$_POST['hhid']."',			
		'".$_POST['purok']."',	
		'".$_POST['barangay']."',	
		'".$_POST['city_mun']."',	
		'".$_POST['province']."',	
		'".$_POST['hh_name']."',	
		'".$_POST['hh_occupation']."',	
		'".$_POST['hh_sex']."',	
		'".$_POST['hh_birth']."',	
		'".$_POST['hh_religion']."',	
		'".$_POST['hh_ethnicity']."',				
		'".$_POST['hh_contact']."',	
		'".$_POST['remarks']."',	
		'".$_POST['hh_members']."',	
		'".$_POST['toilet_have']."',	
		'".$_POST['water_access']."',	
		'".$_POST['mem_death']."',	
		'".$_POST['death_cause_if_yes']."',	
		'".$_POST['mem_hospitalize']."',	
		'".$_POST['illness_if_yes']."',	
		'".$_POST['mem_crime_victim']."',	
		'".$_POST['what_crime_if_yes']."',	
		'".$_POST['settlement']."',	
		'".$_POST['house_type']."',	
		'".$_POST['access_hospital']."',	
		'".$_POST['access_hospital_distance']."',	
		'".$_POST['access_school']."',	
		'".$_POST['access_school_distance']."',	
		'".$_POST['access_church']."',	
		'".$_POST['access_church_distance']."',	
		'".$_POST['access_recreation']."',	
		'".$_POST['access_recreation_distance']."',	
		'".$_POST['swm_reuse']."',
		'".$_POST['swm_reduce']."',
		'".$_POST['swm_recycling']."',
		'".$_POST['swm_composting']."',
		'".$_POST['swm_waste_to_mrf']."',
		'".$_POST['swm_remarks']."',			
		'".$_POST['enumerator']."',
		'".$_POST['verifier']."',
		'".$_POST['date_verified']."',0)");	

	include('qrlib/qrlib.php');			
				
	if(($insert) == TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Created new Household record (Head: " . $_POST['head'] . ", ID: " . $_POST["hhid"] . ")");

		$fullname=$_POST['hh_name'];
		$hhid = $_POST['hhid'];
		$data = "".$_POST["hh_name"]."\nHH ID: ".$_POST["hhid"]."-".$_POST["barangay"]."";		
		$tempDir = "images/households/qrcodes/";
		$codeContents =  "".$data."";
		$fileName = "".$hhid.".png";
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
