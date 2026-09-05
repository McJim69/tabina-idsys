<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script>setActive("solo");</script>
<script>setActive("social");</script>

<script>
	function printF(){
		getID('menu_').style.display='none';
		$(".t_controls").css("display","none");	
		$(".grid").css("display","none");
		
	window.print();
		getID('menu_').style.display='block';
		$(".t_controls").css("display","block");
		$(".grid").css("display","block");		
	}
</script>

<link href="style/pds.css" rel="stylesheet" type="text/css"/>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="jump('solo_parent_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="jump('solo_parent_grid.php')"><i class="fa fa-th"></i> Card View</button>
			</div>				
		</div>
	</div>
</div>

<div class="grid"></div>

<div class="container py-4">

<?php		
	$rec=1;
	$p=$_GET['page'];
	if($p>1){
		$to=$rec;
		$from=($p*$rec)-$rec;
		$i=(($p-1)*$rec)+1;
	}else{
		$to=$rec;
		$from=0;
		$i=1;
		$p=1;
	}			
		
	$mem="";
	if($_GET["solo_parent"]!=""){
		$mem=" and idn='".$_GET["solo_parent"]."' ";
	}
														
	$ex=$link->query("select * from solo_parent where idn=idn $mem order by idn limit $from,$to ");

	while($rs=mysqli_fetch_array($ex)){
				if (!empty($rs['date_assoc_reg']) && $rs['date_assoc_reg'] !== '0000-00-00') {
					$assoc_time = strtotime($rs['date_assoc_reg']);
					$rs['assoc_reg_month'] = date('m', $assoc_time);
					$rs['assoc_reg_day'] = date('d', $assoc_time);
					$rs['assoc_reg_year'] = date('Y', $assoc_time);
				} else {
					$rs['assoc_reg_month'] = '';
					$rs['assoc_reg_day'] = '';
					$rs['assoc_reg_year'] = '';
				}
				if (!empty($rs['date_interview']) && $rs['date_interview'] !== '0000-00-00') {
					$inter_time = strtotime($rs['date_interview']);
					$rs['inter_month'] = date('m', $inter_time);
					$rs['inter_day'] = date('d', $inter_time);
					$rs['inter_year'] = date('Y', $inter_time);
				} else {
					$rs['inter_month'] = '';
					$rs['inter_day'] = '';
					$rs['inter_year'] = '';
				}
		
		$ex_inner=$link->query("select * from solo_parent where solo_parent.idn='$rs[0]' and solo_parent.idn=solo_parent.idn ");
		$ii=1;
			
		while($rs=mysqli_fetch_array($ex_inner)){
			
			// Generate QR Code on the fly
			include_once('qrlib/qrlib.php');
			$qr_dir = "images/solo_parent/qrcodes/";
			if (!file_exists($qr_dir)) {
				mkdir($qr_dir, 0777, true);
			}
			$qr_content = "Name: " . $rs["name_1st"] . " " . $rs["name_fam"] . "\nID: " . sprintf("%04d", $rs["assoc_id_no"]) . "\nProgram: Solo Parent";
			$qr_file = $qr_dir . $rs[0] . ".png";
			QRcode::png($qr_content, $qr_file, QR_ECLEVEL_L, 3);
			$qr_path = $qr_file . "?" . date("h:i:s");

			// Consolidate variables
			$fullname = $rs["name_1st"] . " ";
			if ($rs["name_mid"] != "") {
				$fullname .= $rs["name_mid"] . ". ";
			}
			$fullname .= $rs["name_fam"];

			$system_id_no = sprintf("%04d", $rs[0]);
			
			// Format Solo Parent ID
			$aid = sprintf("%04d", $rs["assoc_id_no"]);
			$arm = sprintf("%02d", $rs["assoc_reg_month"]);
			$ard = sprintf("%02d", $rs["assoc_reg_day"]);
			$solo_parent_id_no = $aid . "-" . $arm . $ard . "-" . $rs["assoc_reg_year"];

			$photo_path = 'images/blank.jpg';
			if (file_exists("images/solo_parent/" . $rs[0] . ".jpg")) {
				$photo_path = "images/solo_parent/" . $rs[0] . ".jpg?" . date("h:i:s");
			}

			$full_purok = $rs["purok"] . ", " . $rs["barangay"] . ", " . $rs["city_mun"] . ", " . $rs["province"];
			$date_birth = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
			$age = "N/A";
			$birthdate_str = "N/A";
			if (!empty($date_birth) && $date_birth !== '0000-00-00') {
				$birthDate_arr = explode("-", $date_birth);
				$birth_year = intval($birthDate_arr[0]);
				$birth_month = intval($birthDate_arr[1]);
				$birth_day = intval($birthDate_arr[2]);
				$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
				$birthdate_str = date("m/d/Y", strtotime($date_birth));
			}

			echo "
			<div class='pds-card card mb-4'>
				<!-- Premium Header -->
				<div class='pds-header d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-left'>
					<div class='d-flex align-items-center mb-3 mb-md-0'>
						<img src='images/favicon.png' height='70' class='mr-3 rounded bg-white p-1' alt='LGU Seal'/>
						<div>
							<h3 class='font-weight-bold mb-1'>LGU-TABINA</h3>
							<span class='badge badge-light mt-2 px-3 py-1 font-weight-bold text-uppercase' style='letter-spacing: 1px;'>Solo Parent Profile</span>
						</div>
					</div>
				</div>

				<div class='card-body p-4'>
					<div class='row'>
						<!-- Left Sidebar -->
						<div class='col-lg-4 text-center mb-4 mb-lg-0 border-right'>
							<div class='d-flex flex-column align-items-center pr-lg-3'>
								<img src='{$photo_path}' class='pds-avatar img-fluid mb-3 shadow' alt='Solo Parent Photo'/>
								<h4 class='font-weight-bold text-dark mb-1 text-uppercase'>{$fullname}</h4>
								<span class='badge badge-primary badge-pds mb-3'>Solo Parent Member</span>								
								<div class='w-100 mt-2 text-left'>
									<div class='info-group mb-2'>
										<div class='info-label'>System ID No.</div>
										<div class='info-value text-primary font-weight-bold'>{$system_id_no}</div>
									</div>
									<div class='info-group mb-2'>
										<div class='info-label'>Solo Parent ID Card No.</div>
										<div class='info-value text-success font-weight-bold'>{$solo_parent_id_no}</div>
									</div>
									<div class='info-group'>
										<div class='info-label'>Barangay / Purok</div>
										<div class='info-value'>{$rs["purok"]}, {$rs["barangay"]}</div>
									</div>
								</div>
								
							</div><br>
							<!-- Section 4: Digital ID Card -->
							<div class='section-title'>Digital System Card</div>
							<div class='digital-id-card'>
								<div>
									<img src='{$qr_path}' style='height: 70px; border-radius: 4px; background: #fff; padding: 2px; margin-right: 15px;' alt='QR Code'/>
									<img src='images/dswd.png' height='70' class='rounded bg-white p-1' alt='DSWD Seal'/>
								</div>
								<div style='margin-top:20px'>
									<h1 class='font-weight-bold text-white tracking-wide m-0' style='font-size: 1.5rem;'>{$solo_parent_id_no}</h1>
								</div>
							</div>
						</div>

						<!-- Right Content Details -->
						<div class='col-lg-8'>
							<!-- Section 1: Personal Details -->
							<div class='section-title'>Personal Information</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Gender</div>
										<div class='info-value'>{$rs["sex"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Age</div>
										<div class='info-value'>{$age} Years Old</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Birthdate</div>
										<div class='info-value'>{$birthdate_str}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Civil Status</div>
										<div class='info-value'>{$rs["civilstatus"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Association</div>
										<div class='info-value'>{$rs["association"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Position</div>
										<div class='info-value'>{$rs["position"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Mobile No.</div>
										<div class='info-value'>{$rs["mobileno"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Email Address</div>
										<div class='info-value'>{$rs["emailadd"]}</div>
									</div>
								</div>
								<div class='col-md-12 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Full Address</div>
										<div class='info-value'>{$full_purok}</div>
									</div>
								</div>
							</div>

							<!-- Section 2: Emergency Contact -->
							<div class='section-title'>Emergency Contact Information</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Contact Person</div>
										<div class='info-value'>{$rs["contactperson"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Relationship</div>
										<div class='info-value'>{$rs["relationship"]}</div>
									</div>
								</div>
								<div class='col-md-12 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Contact Number</div>
										<div class='info-value'>{$rs["emergencyno"]}</div>
									</div>
								</div>
							</div>

							<!-- Section 3: Verification details -->
							<div class='section-title'>Verification Details</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Interviewer</div>
										<div class='info-value'>{$rs["interviewer"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Date Interviewed</div>
										<div class='info-value'>{$rs["inter_month"]}-{$rs["inter_day"]}-{$rs["inter_year"]}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			";
			$ii++;
		}
	}
?>

</div>

</body>

</html>
