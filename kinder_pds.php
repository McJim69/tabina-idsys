<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script>setActive("kinder");</script>
<script>setActive("social");</script>

<link href="style/pds.css" rel="stylesheet" type="text/css"/>

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-outline-primary" type="button" onclick="jump('kinder_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-outline-success" type="button" onclick="jump('kinder_grid.php')"><i class="fa fa-th"></i> Card View</button>
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
	if($_GET["kinder"]!="")
		$mem=" and idn='".$_GET["kinder"]."' ";
														
	$ex=$link->query("select * from kinder where idn=idn $mem order by idn limit $from,$to ");
	
	while($rs=mysqli_fetch_array($ex)){
									
		$ex_inner=$link->query("select * from kinder where kinder.idn='$rs[0]' and kinder.idn=kinder.idn ");
		$ii=1;
			
		while($rs=mysqli_fetch_array($ex_inner)){

			// Consolidate variables
			$fullname = $rs["name_1st"] . " ";
			if ($rs["name_mid"] != "") {
				$fullname .= $rs["name_mid"] . ". ";
			}
			$fullname .= $rs["name_fam"];

			$system_id_no = sprintf("%04d", $rs[0]);
			
			$add = $rs["purok"];
			if ($add !== "") {
				$purok1 = $rs["purok"] . ", " . $rs["barangay"];
				$full_purok = $rs["purok"] . ", " . $rs["barangay"] . ", " . $rs["city_mun"] . ", ZDS";
			} else {
				$purok1 = $rs["barangay"];
				$full_purok = $rs["barangay"] . ", " . $rs["city_mun"] . ", ZDS";
			}

			$photo_path = 'images/blank.jpg';
			if (file_exists("images/kinder/" . $rs[0] . ".jpg")) {
				$photo_path = "images/kinder/" . $rs[0] . ".jpg?" . date("h:i:s");
			}

			$qr_path = '';
			if (file_exists("images/kinder/qrcodes/" . $rs[0] . ".png")) {
				$qr_path = "images/kinder/qrcodes/" . $rs[0] . ".png?" . date("h:i:s");
			}

			$gender = ($rs["sex"] !== "") ? $rs["sex"] : "N/A";
			$parent_name = ($rs["parent"] !== "") ? $rs["parent"] : "N/A";
			$parent_contact = ($rs["contact"] !== "") ? $rs["contact"] : "N/A";
			
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
							<h3 class='font-weight-bold mb-1'>LGU-TABINA INFO SYSTEM</h3>
							<p class='mb-0 text-white-50'>{$rs["city_mun"]}, Zamboanga del Sur</p>
							<span class='badge badge-light mt-2 px-3 py-1 font-weight-bold text-uppercase' style='letter-spacing: 1px;'>Kindergarten Profile Sheet</span>
						</div>
					</div>
					<div>";
					if ($qr_path != '') {
						echo "<img src='{$qr_path}' style='height: 70px; border-radius: 4px; background: #fff; padding: 2px;' alt='QR Code'/>";
					}
					echo "
					</div>
				</div>

				<div class='card-body p-4'>
					<div class='row'>
						<!-- Left Sidebar -->
						<div class='col-lg-4 text-center mb-4 mb-lg-0 border-right'>
							<div class='d-flex flex-column align-items-center pr-lg-3'>
								<img src='{$photo_path}' class='pds-avatar img-fluid mb-3 shadow' alt='Student Photo'/>
								<h4 class='font-weight-bold text-dark mb-1 text-uppercase'>{$fullname}</h4>
								<span class='badge badge-primary badge-pds mb-3'>Kindergarten Student</span>
								
								<div class='w-100 mt-2 text-left'>
									<div class='info-group mb-2'>
										<div class='info-label'>System ID No.</div>
										<div class='info-value text-primary font-weight-bold'>{$system_id_no}</div>
									</div>
									<div class='info-group'>
										<div class='info-label'>Barangay / Purok</div>
										<div class='info-value'>{$purok1}</div>
									</div>
								</div>
							</div>
							<div class='digital-id-card'>
								<h5 class='text-uppercase font-weight-bold text-white-50 mb-2'>Student ID Number</h5>
								<h1 class='font-weight-bold text-white tracking-wide m-0' style='font-size: 2.8rem;'>{$system_id_no}</h1>
							</div>
						</div>

						<!-- Right Content Details -->
						<div class='col-lg-8'>
							<!-- Section 1: Student Details -->
							<div class='section-title'>Student Information</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Gender</div>
										<div class='info-value'>{$gender}</div>
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
										<div class='info-label'>City / Municipality</div>
										<div class='info-value'>{$rs["city_mun"]}</div>
									</div>
								</div>
								<div class='col-md-12 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Full Address</div>
										<div class='info-value'>{$full_purok}</div>
									</div>
								</div>
							</div>

							<!-- Section 2: Parent & Guardian Details -->
							<div class='section-title'>Parent & Guardian Information</div>
							<div class='row mb-4'>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Parent / Guardian Name</div>
										<div class='info-value'>{$parent_name}</div>
									</div>
								</div>
								<div class='col-md-6 mb-3'>
									<div class='info-group'>
										<div class='info-label'>Contact Number</div>
										<div class='info-value'>{$parent_contact}</div>
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

</body>

</html>
