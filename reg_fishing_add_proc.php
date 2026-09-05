<?php
	require("connect.php");
	require("header2.php");
	
	if(isset($_POST["bSave"])){
		$session_user_val = isset($_SESSION['uno']) ? intval($_SESSION['uno']) : 0;

		$insert = $link->query("INSERT INTO reg_fishing (
			idn, regtype, name_1st, name_mid, name_fam, purok, barangay, city_mun, province, homeport, tradename, builder, build_place, build_year, build_hull, former_owner, former_vname, fvtype, fvcolor, service_type, description, lenght, breadth, depth, grosston, netton, enginemake, enginesn, enginehp, engcylinder, engineno, crewno, coastgno, gearused, isorno, oramount, ispicset, user_id, status, timestamp, date_issued, date_or
		) VALUES (
			'".$_POST["idn"]."',
			'".$_POST["regtype"]."',
			'".$_POST["name_1st"]."',
			'".$_POST["name_mid"]."',
			'".$_POST["name_fam"]."',
			'".$_POST["purok"]."',
			'".$_POST["barangay"]."',
			'".$_POST["city_mun"]."',
			'".$_POST["province"]."',
			'".$_POST["homeport"]."',
			'".$_POST["tradename"]."',
			'".$_POST["builder"]."',
			'".$_POST["build_place"]."',
			'".$_POST["build_year"]."',
			'".$_POST["build_hull"]."',
			'".$_POST["former_owner"]."',
			'".$_POST["former_vname"]."',		
			'".$_POST["fvtype"]."',
			'".$_POST["fvcolor"]."',
			'".$_POST["service_type"]."',
			'".$_POST["description"]."',
			'".$_POST["lenght"]."',
			'".$_POST["breadth"]."',
			'".$_POST["depth"]."',
			'".$_POST["grosston"]."',
			'".$_POST["netton"]."',
			'".$_POST["enginemake"]."',
			'".$_POST["enginesn"]."',
			'".$_POST["enginehp"]."',
			'".$_POST["engcylinder"]."',
			'".$_POST["engineno"]."',
			'".$_POST["crewno"]."',
			'".$_POST["coastgno"]."',
			'".$_POST["gearused"]."',		
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
			logAuditTrail($link, $admin, "Created new Fishing Boat License record: " . $_POST['tradename'] . " (Owned by: " . $fullname . ", ID: " . $_POST["idn"] . ")");

			$fullname = trim($_POST['name_1st'] . " " . $_POST['name_mid'] . " " . $_POST['name_fam']);

			$idn = $_POST["idn"];
			$data = "Name of FV: ".$_POST["tradename"]."\nOperator: ".$_POST["name_1st"]." ".$_POST["name_mid"]." ".$_POST["name_fam"]."\nMFVR No: ".$idn ." Engine SN: ".$_POST["enginesn"]." Address: ".$_POST["purok"]." ".$_POST["barangay"]." ".$_POST["city_mun"]."";		
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
