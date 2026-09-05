<?php
	require("connect.php");
	require("header2.php");

		if(isset($_POST['update'])){	
			$idn = mysqli_real_escape_string($link, $_POST['idn']);
			$name_fam = mysqli_real_escape_string($link, $_POST['name_fam']);
			$name_1st = mysqli_real_escape_string($link, $_POST['name_1st']);
			$name_mid = mysqli_real_escape_string($link, $_POST['name_mid']);
			$date_birth = mysqli_real_escape_string($link, $_POST['date_birth']);
			$sex = mysqli_real_escape_string($link, $_POST['sex']);
			$parent = mysqli_real_escape_string($link, $_POST['parent']);
			$contact = mysqli_real_escape_string($link, $_POST['contact']);
			$purok = mysqli_real_escape_string($link, $_POST['purok']);
			$barangay = mysqli_real_escape_string($link, $_POST['barangay']);
			$city_mun = mysqli_real_escape_string($link, $_POST['city_mun']);

		$update = $link->query("UPDATE kinder set
			idn = '$idn',	
			name_fam = '$name_fam', 
			name_1st = '$name_1st', 
			name_mid = '$name_mid', 
			date_birth = '$date_birth', 
			sex = '$sex',
			parent = '$parent', 
			contact = '$contact',
			purok = '$purok',
			barangay = '$barangay',
			city_mun = '$city_mun' where idn = '$idn'");

		include('qrlib/qrlib.php');
		
		if(($update)== TRUE){
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			$fullname = trim($_POST['name_1st'] . ' ' . ($_POST['name_mid'] ?? '') . ' ' . $_POST['name_fam']);
			logAuditTrail($link, $admin, "Updated Kindergarten record: " . $fullname . " (ID: " . $idn . ")");


			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			$data = "".$_POST["name_1st"]." ".$_POST["name_mid"].". ".$_POST["name_fam"]."\nID Number: ".$idn." Parent: ".$_POST["parent"]."";		
			$tempDir = "images/kinder/qrcodes/";
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
