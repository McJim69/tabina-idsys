<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script>
	function printF(){
		getID('menu_').style.display='none';
		$(".t_controls").css("display","none");	
		$(".grid").css("display","none");
		$(".hid").css("display","none");
		$(".head").css("display","visible");
		
	window.print();
		getID('menu_').style.display='block';
		$(".t_controls").css("display","block");
		$(".grid").css("display","block");		
		$(".hid").css("display","visible");
		$(".head").css("display","none");
	}
</script>

<script> setActive("sap"); </script>
<script> setActive("social"); </script>

<link href="style/pds.css" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-outline-success" type="button" onclick="jump('sap_ben_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-outline-primary" type="button" onclick="jump('sap_ben_grid.php')"><i class="fa fa-th"></i> Card View</button>
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
	if($_GET["sap_ben"]!=""){
		$mem=" and idn='".$_GET["sap_ben"]."' ";
	}
														
	$ex=$link->query("select * from sap_ben where idn=idn $mem order by idn limit $from,$to ");

	while($rs=mysqli_fetch_array($ex)){
		
		$ex_inner=$link->query("select * from sap_ben where sap_ben.idn='$rs[0]' and sap_ben.idn=sap_ben.idn ");
		$ii=1;
			
		while($rs=mysqli_fetch_array($ex_inner)){
			
			// QR Code path
			$qr_path = "images/sap_ben/qrcodes/" . $rs[0] . ".png";
			if (!file_exists($qr_path)) {
				// Generate QR Code if missing
				include_once('qrlib/qrlib.php');
				$qr_dir = "images/sap_ben/qrcodes/";
				if (!file_exists($qr_dir)) {
					mkdir($qr_dir, 0777, true);
				}
				$qr_content = "Name: " . $rs["name_1st"] . " " . $rs["name_fam"] . "\nID: " . sprintf("%04d", $rs[0]) . "-" . $rs["sap_form"] . "\nProgram: SAP Beneficiary";
				QRcode::png($qr_content, $qr_path, QR_ECLEVEL_L, 3);
			}
			$qr_path_display = $qr_path . "?" . date("h:i:s");

			// Consolidate variables
			$fullname = $rs["name_1st"] . " ";
			if ($rs["name_mid"] != "") {
				$fullname .= $rs["name_mid"] . " ";
			}
			$fullname .= $rs["name_fam"];
			if ($rs["name_ext"] != "") {
				$fullname .= ", " . $rs["name_ext"];
			}

			$system_id_no = sprintf("%04d", $rs[0]);
			$sap_id_no = $system_id_no . "-" . $rs["sap_form"];

			$photo_path = 'images/blank.jpg';
			if (file_exists("images/sap_ben/" . $rs[0] . ".jpg")) {
				$photo_path = "images/sap_ben/" . $rs[0] . ".jpg?" . date("h:i:s");
			}

			$full_address = $rs["barangay"] . ", " . $rs["city_mun"] . ", Zamboanga del Sur";

			echo "
			<div class='pds-card card mb-4'>
				<!-- Premium Header -->
				<div class='pds-header d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-left'>
					<div class='d-flex align-items-center mb-3 mb-md-0'>
						<img src='images/favicon.png' height='70' class='mr-3 rounded bg-white p-1' alt='LGU Seal'/>
						<div>
							<h3 class='font-weight-bold mb-1'>SAP MEMBER</h3>
							<span class='badge badge-light mt-2 px-3 py-1 font-weight-bold text-uppercase' style='letter-spacing: 1px;'>LGU TABINA ZDS</span>
						</div>
					</div>
				</div>

				<!-- Program Logos Bar -->
				<div class='bg-light border-bottom p-2 d-flex flex-wrap justify-content-center align-items-center logo-container'>
					<img src='images/dbm_logo.png' alt='DBM'/>
					<img src='images/dole_logo.png' alt='DOLE'/>
					<img src='images/dilg_logo.png' alt='DILG'/>
					<img src='images/dswd.png' alt='DSWD'/>
					<img src='images/dof_logo.png' alt='DOF'/>
					<img src='images/dti_logo.png' alt='DTI'/>
					<img src='images/da_logo.png' alt='DA'/>
				</div>

				<div class='card-body p-4'>
					<div class='row'>
						<!-- Left Sidebar -->
						<div class='col-lg-4 text-center mb-4 mb-lg-0 border-right'>
							<div class='d-flex flex-column align-items-center pr-lg-3'>
								<img src='{$photo_path}' class='pds-avatar img-fluid mb-3 shadow' alt='SAP Photo'/>
								<h4 class='font-weight-bold text-dark mb-1 text-uppercase'>{$fullname}</h4>
								<span class='badge badge-primary badge-pds mb-3'>SAP Beneficiary</span>
								
								<div class='w-100 mt-2 text-left'>
									<div class='info-group mb-2'>
										<div class='info-label'>System ID No.</div>
										<div class='info-value text-primary font-weight-bold'>{$system_id_no}</div>
									</div>
									<div class='info-group mb-2'>
										<div class='info-label'>SAP Card Number</div>
										<div class='info-value text-success font-weight-bold'>{$sap_id_no}</div>
									</div>
									<div class='info-group'>
										<div class='info-label'>Barangay / Purok</div>
										<div class='info-value'>{$rs["barangay"]}</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Right Content Details -->
						<div class='col-lg-8'>
							<!-- Section 1: Program details -->
							<div class='section-title'>Beneficiary Information</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Full Address</div>
										<div class='info-value'>{$full_address}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>SAP Form Ref No.</div>
										<div class='info-value'>{$rs["sap_form"]}</div>
									</div>
								</div>
							</div>

							<!-- Section 2: Payment Details -->
							<div class='section-title'>Payment & Amelioration Details</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Period</div>
										<div class='info-value'>"; echo ($rs["period"] !== "" ? $rs["period"] : "N/A"); echo "</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Amount Received</div>
										<div class='info-value'>"; echo ($rs["amount"] !== "" ? "₱" . number_format($rs["amount"], 2) : "N/A"); echo "</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Date Paid</div>
										<div class='info-value'>"; echo ($rs["date_paid"] !== "" ? $rs["date_paid"] : "N/A"); echo "</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Remarks / Mode</div>
										<div class='info-value'>"; echo ($rs["remarks"] !== "" ? $rs["remarks"] : "N/A"); echo "</div>
									</div>
								</div>
							</div>

							<!-- Section 3: Digital ID Card -->
							<div class='section-title'>Digital System Card</div>
							<div class='digital-id-card'>
								<img src='{$qr_path_display}' style='height: 100px; border-radius: 4px; background: #fff; padding: 2px;' alt='QR Code'/>
								<h1 class='font-weight-bold text-white tracking-wide m-0' style='font-size: 2.2rem;'>{$sap_id_no}</h1>
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
