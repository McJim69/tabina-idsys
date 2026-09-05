<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 	

	$value = isset($_GET['value']) ? $_GET['value'] : '';
		
	$bar = "";
	if (isset($_GET["barangays"]) && $_GET["barangays"] != "All barangays" && $_GET["barangays"] != "") {
		$bar = " and barangay='" . $_GET["barangays"] . "'";
	}
			
	if (isset($_POST["b_search"])) {
		$value = $_POST["t_search"];
	}
	
	$rec = 1; // Show one certificate per page
	$p = isset($_GET['page']) ? intval($_GET['page']) : 1;
	if ($p > 1) {
		$to = $p * $rec;
		$from = $to - $rec;
		$i = $to + 1 - $rec;
	} else {
		$to = $rec;
		$from = 0;
		$i = 1;
		$p = 1;
	}
	
	$mem = "";
	if (isset($_GET["kinder"]) && $_GET["kinder"] != "") {
		$mem = " and l.idn='" . $_GET["kinder"] . "' ";
	}
	
	$ex1 = $link->query("select * from kinder l where 
	   (l.name_fam like '%" . $value . "%' or
		l.name_1st like '%" . $value . "%' or
		l.name_mid like '%" . $value . "%' or
		l.purok like '%" . $value . "%' or
		l.barangay like '%" . $value . "%' or
		l.city_mun like '%" . $value . "%' or
		l.date_birth like '%" . $value . "%' or
		l.sex like '%" . $value . "%') $bar $mem");
				
	$ex = $link->query("select * from kinder l where 
	   (l.name_fam like '%" . $value . "%' or
		l.name_1st like '%" . $value . "%' or
		l.name_mid like '%" . $value . "%' or
		l.purok like '%" . $value . "%' or
		l.barangay like '%" . $value . "%' or
		l.city_mun like '%" . $value . "%' or
		l.date_birth like '%" . $value . "%' or
		l.sex like '%" . $value . "%')
	$bar $mem order by name_fam LIMIT $from,$to ");			
?>

<script>setActive("kinder");</script>
<script>setActive("social");</script>

<link href="style/kinder-cert.css" rel="stylesheet" type="text/css"/>

<script>
	function printF(){
		$('.t_controls').css("display","none");
		window.print(); 
		$('.t_controls').css("display","block");
	}
</script>

<?php include_once("display_notice.php");?>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<button class="bmargin btn btn-sm btn-outline-primary" type="button" onclick="jump('kinder_cert.php')"><i class="fa fa-sync"></i> Refresh</button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="jump('kinder_list.php')"><i class="fa fa-list"></i> List View</button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('kinder_grid.php')"><i class="fa fa-th"></i> Card View</button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" value='Print' onclick="printF()"><i class="fa fa-print"></i> Print</button>		
			</div>				
		</div>
	</div>
</div>

<div class="spacer"></div>

<div align="center" style="width:100%;margin:0 auto">
	<div style="padding:3px;"></div>

	<?php
		while($rs=mysqli_fetch_array($ex)){
			
			$exs = $link->query("select * from kinder l where l.idn='$rs[0]' and l.idn=l.idn ");
			
			while($rs_inner=mysqli_fetch_array($exs)){
				
				$fullname = $rs_inner["name_1st"] . " ";
				if ($rs_inner["name_mid"] != "") {
					$fullname .= $rs_inner["name_mid"] . ". ";
				}
				$fullname .= $rs_inner["name_fam"];

				$system_id_no = sprintf("%04d", $rs_inner[0]);

				$qr_path = 'images/no_qrcode.png';
				if (file_exists("images/kinder/qrcodes/" . $rs_inner[0] . ".png")) {
					$qr_path = "images/kinder/qrcodes/" . $rs_inner[0] . ".png?" . date("h:i:s");
				}

				// Format date day suffix
				$day_num = date("d");
				$day_suffix = "th";
				if ($day_num == "01" || $day_num == "21" || $day_num == "31") $day_suffix = "st";
				else if ($day_num == "02" || $day_num == "22") $day_suffix = "nd";
				else if ($day_num == "03" || $day_num == "23") $day_suffix = "rd";

				$formatted_date = date("jS") . " day of " . date("F, Y");

				echo "
				<div class='certificate-container'>
					<!-- Left Logo (LGU Seal of Tabina) -->
					<img src='images/favicon.png' style='position: absolute; top: 40px; left: 45px; height: 70px; z-index: 10;' alt='LGU Seal'/>

					<!-- Right Logo (DSWD Logo) -->
					<img src='images/dswd.png' style='position: absolute; top: 40px; right: 45px; height: 70px; z-index: 10;' alt='DSWD Logo'/>

					<!-- Watermark Background -->
					<img src='images/SEAL.png' class='cert-watermark' alt='LGU Seal Watermark'/>

					<!-- Header Details -->
					<div class='cert-header' style='padding-left: 80px; padding-right: 80px;'>
						<div style='font-size:10px; font-weight:bold; letter-spacing: 1px; text-transform:uppercase;'>Republic of the Philippines</div>
						<div style='font-size:12px; font-weight:bold; color: #113f67;'>PROVINCE OF ZAMBOANGA DEL SUR</div>
						<div style='font-size:11px; font-weight:500; font-style:italic;'>Municipality of Tabina</div>
						<div style='font-size:9px; margin-top:2px; text-transform:uppercase; color: #555;'>Municipal Social Welfare & Development Office</div>
						
						<div class='cert-title'>Certificate of Completion</div>
						<div style='font-size:14px; font-style:italic; margin-top:-3px;'>This certificate is proudly presented to</div>
					</div>

					<!-- Body Details -->
					<div class='cert-body'>
						<div class='cert-name'>{$fullname}</div>
						<p style='margin-top:10px;'>
							for having successfully completed the prescribed course of study in <b>Kindergarten Education</b> 
							as approved by the Department of Education, and having met all graduation requirements 
							established by the Municipal Social Welfare and Development Office.
						</p>
						<p style='margin-top:10px; font-style:italic;'>
							Given in Tabina, Zamboanga del Sur, Philippines, this {$formatted_date}.
						</p>
					</div>

					<!-- Signatures and QR -->
					<div class='cert-signatures'>
						<div>
							<!-- Signature placeholder or spacer -->
							<div style='height:25px;'></div>
							<div class='sig-line'>
								MSSD Officer<br>
								<span style='font-size:9px; font-weight:normal; text-transform:none; color:#666;'>Department Head</span>
							</div>
						</div>

						<!-- Center QR Code -->
						<div style='text-align:center; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; margin-bottom: -5px;'>
							<div style='background: #fff; padding: 2px; border: 1px solid #ddd; display: inline-block;'>
								<img src='{$qr_path}' height='70' width='70' alt='Verification QR'/>
							</div>
							<div style='font-size:8px; text-align:center; margin-top:2px; color:#555; font-family: monospace;'>MSWDO-{$system_id_no}</div>
						</div>
						
						<div>
							<!-- Signature Placeholder image -->
							<div style='height:25px; text-align:center;'>
								<img src='images/mayor_bader.png' height='40' style='margin-top:-15px; z-index:2; position:relative;' alt='Mayor Badge'/>
							</div>
							<div class='sig-line'>
								HON. JUHAINE A. MALACO<br>
								<span style='font-size:9px; font-weight:normal; text-transform:none; color:#666;'>Municipal Mayor</span>
							</div>
						</div>
					</div>
				</div>
				<div style='page-break-after: always;'></div>
				";
			}
		}
	?>

</div>
</form>
		
</body>
</html>