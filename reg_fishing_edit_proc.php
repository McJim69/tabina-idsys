<?php
	require("connect.php");
	require("header2.php");

	if(isset($_POST['update'])){	
		$idn		 = $_POST['idn'];
		$tradename 	 = $_POST['tradename'];
		$fvcolor 	 = $_POST['fvcolor'];
		$build_hull  = $_POST['build_hull'];
		$enginemake  = $_POST['enginemake'];
		$enginesn 	 = $_POST['enginesn'];
		$engineno 	 = $_POST['engineno'];
		$enginehp 	 = $_POST['enginehp'];
		$engcylinder = $_POST['engcylinder'];	
		$name_1st	 = $_POST['name_1st'];
		$name_mid	 = $_POST['name_mid'];
		$name_fam	 = $_POST['name_fam'];
		$purok 	 = $_POST['purok'];
		$barangay 	 = $_POST['barangay'];
		$city_mun 	 = $_POST['city_mun'];

		$update = $link->query("UPDATE reg_fishing set
		idn 		 = '$idn',	
		tradename 	 = '$tradename',
		fvcolor 	 = '$fvcolor',
		build_hull 	 = '$build_hull',
		enginemake 	 = '$enginemake',
		enginesn 	 = '$enginesn',
		engineno 	 = '$engineno',
		enginehp 	 = '$enginehp',
		engcylinder  = '$engcylinder',
		name_1st 	 = '$name_1st', 
		name_mid 	 = '$name_mid', 
		name_fam 	 = '$name_fam', 
		purok 	 = '$purok',
		barangay 	 = '$barangay',
		city_mun 	 = '$city_mun' where idn = '$idn'");

		include('qrlib/qrlib.php');

		if(($update)== TRUE){

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);
			
			// Log audit trail
			$admin = $_SESSION['user'] ?? 'Unknown';
			logAuditTrail($link, $admin, "Updated Fishing Boat License record: " . $_POST['tradename'] . " (Owned by: " . $fullname . ", ID: " . $idn . ")");
			
			$data = "Name of FV: ".$tradename."\nOperator: ".$name_1st." ".$name_mid." ".$name_fam."\nMFVR No: ".$idn ." Engine SN: ".$enginesn." Address: ".$purok." ".$barangay." ".$city_mun."";		
			$tempDir = "images/reg_fishing/qrcodes/";
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
