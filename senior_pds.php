<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script>setActive("senior");</script>
<script>setActive("social");</script>

<link href="style/pds.css" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-outline-primary" type="button" onclick="jump('senior_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-outline-success" type="button" onclick="jump('senior_grid.php')"><i class="fa fa-th"></i> Card View</button>
			</div>				
		</div>
	</div>
</div>

<div class="grid" style="margin:40px"></div>

<style>
	.pds-card {
		background: #ffffff;
		border-radius: 12px;
		box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
		border: none;
		margin-bottom: 30px;
		overflow: hidden;
	}
	.pds-header {
		background: linear-gradient(135deg, #113f67, #38598b);
		color: #ffffff;
		padding: 25px;
	}
	.pds-avatar {
		width: 150px;
		height: 150px;
		border-radius: 50%;
		border: 4px solid #ffffff;
		box-shadow: 0 4px 15px rgba(0,0,0,0.15);
		object-fit: cover;
		transition: transform 0.2s ease-in-out;
	}
	.pds-avatar:hover {
		transform: scale(1.03);
	}
	.badge-pds {
		font-size: 14px;
		font-weight: 600;
		padding: 6px 12px;
		border-radius: 20px;
	}
	.info-group {
		background: #f8f9fa;
		border: 1px solid #e9ecef;
		border-radius: 8px;
		padding: 12px 15px;
		height: 100%;
	}
	.info-label {
		font-size: 11px;
		font-weight: bold;
		color: #868e96;
		text-transform: uppercase;
		margin-bottom: 4px;
		letter-spacing: 0.5px;
	}
	.info-value {
		font-size: 14px;
		font-weight: 600;
		color: #212529;
		word-break: break-word;
	}
	.section-title {
		font-size: 16px;
		font-weight: bold;
		color: #113f67;
		border-bottom: 2px solid #e9ecef;
		padding-bottom: 8px;
		margin-bottom: 20px;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}
	.digital-id-card {
		background: linear-gradient(135deg, #162447, #1f4068);
		color: #ffffff;
		border-radius: 12px;
		padding: 25px;
		text-align: center;
		box-shadow: 0 4px 15px rgba(0,0,0,0.15);
		border: 1px solid rgba(255,255,255,0.1);
	}
	@media print {
		.pds-header {
			background: #113f67 !important;
			color: #ffffff !important;
			-webkit-print-color-adjust: exact;
		}
		.pds-card {
			box-shadow: none !important;
			border: 1px solid #ddd !important;
		}
		.digital-id-card {
			background: #162447 !important;
			color: #ffffff !important;
			-webkit-print-color-adjust: exact;
		}
		.t_controls {
			display: none !important;
		}
	}
</style>

<div class="spacer" style="height:70px">&nbsp;</div>

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
	if($_GET["senior"]!=""){
		$mem=" and idn='".$_GET["senior"]."' ";
	}
														
	$ex=$link->query("select * from senior where idn=idn $mem order by idn limit $from,$to ");

	while($rs=mysqli_fetch_array($ex)){
		
		$ex_inner=$link->query("select * from senior where senior.idn='$rs[0]' and senior.idn=senior.idn ");
		$ii=1;
			
		while($rs=mysqli_fetch_array($ex_inner)){
			
			// Generate QR Code on the fly
			include_once('qrlib/qrlib.php');
			$qr_dir = "images/senior/qrcodes/";
			if (!file_exists($qr_dir)) {
				mkdir($qr_dir, 0777, true);
			}
			$qr_content = "Name: " . $rs["name_1st"] . " " . $rs["name_fam"] . "\nID: " . sprintf("%04d", $rs["assoc_id_no"]) . "\nProgram: Senior Citizen";
			$qr_file = $qr_dir . $rs[0] . ".png";
			QRcode::png($qr_content, $qr_file, QR_ECLEVEL_L, 3);
			$qr_path = $qr_file . "?" . date("h:i:s");

			// Consolidate variables
			$fullname = $rs["name_1st"] . " ";
			if ($rs["name_mid"] != "") {
				$fullname .= $rs["name_mid"] . " ";
			}
			$fullname .= $rs["name_fam"];

			$system_id_no = sprintf("%04d", $rs[0]);
			
			// Format OSCA ID
			$aid = sprintf("%04d", $rs["assoc_id_no"]);
			$osca_id_no = "OSCA: " . $aid;
			if ($rs["ncsc_rrn"] !== "") {
				$osca_id_no .= " &bull; NCSC: " . $rs["ncsc_rrn"];
			}

			$photo_path = 'images/blank.jpg';
			if (file_exists("images/senior/" . $rs[0] . ".jpg")) {
				$photo_path = "images/senior/" . $rs[0] . ".jpg?" . date("h:i:s");
			}

			$full_purok = "";
			if ($rs["purok"] !== "") {
				$full_purok .= $rs["purok"] . ", ";
			}
			$full_purok .= $rs["barangay"] . ", " . $rs["city_mun"] . ", " . $rs["province"];
			
			$birthdate_str = "";
			if (!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00") {
				$birthdate_str = date("m/d/Y", strtotime($rs["date_birth"]));
			}
			$age = $rs["age"];

			$pensioner_str = ($rs["pensioner"] === "Yes") ? "Pensioner" : "Non-Pensioner";
			$position_str = ($rs["position"] !== "") ? $rs["position"] : "Member";

			echo "
			<div class='pds-card card mb-4'>
				<!-- Premium Header -->
				<div class='pds-header d-flex flex-column flex-md-row justify-content-between align-items-center text-center'>
					<div class='mb-3 mb-md-0 text-md-right' style='flex: 1;'>
						<img src='images/favicon.png' height='70' class='rounded bg-white p-1' alt='LGU Seal'/>
					</div>
					<div class='mx-md-4 mb-3 mb-md-0' style='flex: 2;'>
						<h3 class='font-weight-bold mb-1'>LGU-TABINA OSCA</h3>
						<p class='mb-0 text-white-50'>{$rs["city_mun"]}, {$rs["province"]}</p>
						<span class='badge badge-light mt-2 px-3 py-1 font-weight-bold text-uppercase' style='letter-spacing: 1px;'>Senior Citizen Profile</span>
					</div>
					<div class='text-md-left' style='flex: 1;'>
						<img src='images/osca_logo2.png' height='70' class='rounded bg-white p-1' alt='OSCA Seal'/>
					</div>
				</div>

				<div class='card-body p-4'>
					<div class='row'>
						<!-- Left Sidebar -->
						<div class='col-lg-4 text-center mb-4 mb-lg-0 border-right'>
							<div class='d-flex flex-column align-items-center pr-lg-3'>
								<img src='{$photo_path}' class='pds-avatar img-fluid mb-3 shadow' alt='Senior Photo'/>
								<h4 class='font-weight-bold text-dark mb-1 text-uppercase'>{$fullname}</h4>
								<span class='badge badge-primary badge-pds mb-3'>{$pensioner_str}</span>
								
								<div class='w-100 mt-2 text-left'>
									<div class='info-group mb-2'>
										<div class='info-label'>System ID No.</div>
										<div class='info-value text-primary font-weight-bold'>{$system_id_no}</div>
									</div>
									<div class='info-group mb-2'>
										<div class='info-label'>OSCA ID Card / NCSC</div>
										<div class='info-value text-success font-weight-bold' style='font-size: 13px;'>{$osca_id_no}</div>
									</div>
									<div class='info-group mb-2'>
										<div class='info-label'>Barangay / Address</div>
										<div class='info-value'>{$rs["purok"]}, {$rs["barangay"]}</div>
									</div>
									<div class='info-group mb-2 text-center p-3'>
										<div class='info-label mb-2'>QR Verification Code</div>
										<img src='{$qr_path}' style='height: 120px; border-radius: 8px; border: 1px solid #ddd; background: #fff; padding: 4px;' alt='QR Code'/>
									</div>
									<div class='digital-id-card p-3'>
										<h6 class='text-uppercase font-weight-bold text-white-50 mb-1' style='font-size: 10px;'>Senior Citizen Card</h6>
										<h3 class='font-weight-bold text-white tracking-wide m-0' style='font-size: 1.3rem;'>{$osca_id_no}</h3>
									</div>
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
										<div class='info-label'>Pensioner Status</div>
										<div class='info-value'>{$rs["pensioner"]}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Position</div>
										<div class='info-value'>{$position_str}</div>
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
										<div class='info-value'>"; echo ($rs["contactperson"] == null ? "N/A" : $rs["contactperson"]); echo "</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Relationship</div>
										<div class='info-value'>"; echo ($rs["relationship"] == null ? "N/A" : $rs["relationship"]); echo "</div>
									</div>
								</div>
								<div class='col-md-12 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Contact Number</div>
										<div class='info-value'>"; echo ($rs["emergencyno"] == null ? "N/A" : $rs["emergencyno"]); echo "</div>
									</div>
								</div>
							</div>

							<!-- Section 3: Verification details -->
							<div class='section-title'>Verification Details</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Interviewer / Encoder</div>
										<div class='info-value'>"; echo ($rs["interviewer"] == null ? $_SESSION['fullname'] : $rs["interviewer"]); echo "</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Date Interviewed</div>
										<div class='info-value'>{$rs["inter_date"]}</div>
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
