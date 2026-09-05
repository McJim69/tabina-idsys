<?php
	require("connect.php");
	require("header.php");
	require("menu.php"); 	

	$value = '';
	if (isset($_POST['t_search'])) {
		$value = $_POST['t_search'];
	} else if (isset($_GET['value'])) {
		$value = $_GET['value'];
	}
		
	$bar = "";
	if (isset($_GET["barangays"]) && strtolower($_GET["barangays"]) != "all barangays" && $_GET["barangays"] != "") {
		$bar = " and barangay='" . $_GET["barangays"] . "'";
	}
	
	$rec = 20;
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

<script>setActive("social");</script>
<script>setActive("kinder");</script>
<script>setActive("kindercard");</script>

<link href="style/idcard.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword..." type="text" name="t_search" id="t_search" value="<?php echo htmlspecialchars(stripslashes($value)); ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-dark" type="button" onclick="getID('t_search').value='';jump('kinder_idcard.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('kinder_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<select class="swid spad bmargin btn btn-sm btn-outline-dark" onchange="let url='kinder_idcard.php?barangays='+encodeURIComponent(this.value); let sVal=document.getElementById('t_search').value; if(sVal) { url+='&value='+encodeURIComponent(sVal); } if(this.value.toLowerCase()=='all barangays') { url='kinder_idcard.php'; if(sVal) { url+='?value='+encodeURIComponent(sVal); } } jump(url);">
					<option <?php if(!isset($_GET["barangays"]) || $_GET["barangays"] == "" || $_GET["barangays"] == "All Barangays") echo "selected"; ?>>All Barangays</option>
					<?php
						$ex2=$link->query("select barangay from kinder group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if(isset($_GET["barangays"]) && $_GET["barangays"]===$rs2[0]) {
								echo "selected";
							}
							echo ">" . htmlspecialchars($rs2[0]) . "</option>";
						}
					?>
				</select>
				<?php
					if(isset($_SESSION['user'])){
						echo "<a rel='facebox' href='kinder_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Kinder</x></button></a>";
					}
				?>
				<button class="bmargin btn btn-sm btn-outline-secondary" type="button" onclick="printF()"><i class="fa fa-print tpad"></i> <x class="thid">Print</x></button>
				<button class="btn btn-sm btn-outline-info" type="button" onclick="jump('kinder_grid.php')"><i class="fa fa-th tpad"></i> <x class="thid">Card View</x></button>
			</div>				
		</div>
	</div>
</div>

<div class="idmarg"></div>

<div class="container-fluid px-4 my-2">
	<div class="row justify-content-center">
	<?php
		while($rs=mysqli_fetch_array($ex)){
			
			$date_birth = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
			$age = "N/A";
			if (!empty($date_birth) && $date_birth !== '0000-00-00') {
				$birthDate_arr = explode("-", $date_birth);
				$birth_year = intval($birthDate_arr[0]);
				$birth_month = intval($birthDate_arr[1]);
				$birth_day = intval($birthDate_arr[2]);
				$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
			}
						
			$exs = $link->query("select * from kinder l where l.idn='$rs[0]' and l.idn=l.idn ");
			
			while($rs_inner=mysqli_fetch_array($exs)){
				
				$fullname = $rs_inner["name_1st"] . " ";
				if ($rs_inner["name_mid"] != "") {
					$fullname .= $rs_inner["name_mid"] . ". ";
				}
				$fullname .= $rs_inner["name_fam"];

				$system_id_no = sprintf("%04d", $i++);

				$photo_path = 'images/blank.jpg';
				if (file_exists("images/kinder/" . $rs_inner[0] . ".jpg")) {
					$photo_path = "images/kinder/" . $rs_inner[0] . ".jpg?" . date("h.i.s");
				}

				$signature_path = 'images/no_signature.png';
				if (file_exists("images/kinder/signatures/" . $rs_inner[0] . ".png")) {
					$signature_path = "images/kinder/signatures/" . $rs_inner[0] . ".png?" . date("h.i.s");
				}

				$qr_path = 'images/no_qrcode.png';
				if (file_exists("images/kinder/qrcodes/" . $rs_inner[0] . ".png")) {
					$qr_path = "images/kinder/qrcodes/" . $rs_inner[0] . ".png?" . date("h.i.s");
				}

				$idback_img = 'images/pwd/idback/idback.jpg'; // standard fallback backing image
				if (file_exists("images/kinder/idback/idback.jpg")) {
					$idback_img = "images/kinder/idback/idback.jpg?" . date("h.i.s");
				}
		
				echo "
				<div class='col-12 mb-4 d-flex justify-content-center print-full-width'>
					<div class='id-card-container' style='margin-bottom:-10px'>
						<div class='id-card-wrapper shadow-sm rounded border' id='div_{$rs_inner[0]}'>
							<img src='{$idback_img}' class='id-card-bg' alt='ID Card Background'/>
						
							<div class='id-title-badge'>
								DAYCARE
							</div>

							<div class='id-number-badge'>
								{$system_id_no}
							</div>
						
							<div class='id-photo-container'>
								<img onclick=\"jump('kinder_pds.php?kinder={$rs_inner[0]}')\" src='{$photo_path}' alt='Student Photo'/>
							</div>

							<div class='id-signature-container'>
								<img src='{$signature_path}' height='35px' alt='Signature'/>
							</div>
							
							<div class='id-details-front'>
								<div style='padding-top:4px;font-size:22px;color:#000; text-transform:uppercase; font-weight:bold;'>{$fullname}</div>
								<div><i class='fa fa-home' style='width:18px'></i> <b>{$rs_inner["purok"]}, {$rs_inner["barangay"]}, {$rs_inner["city_mun"]}</b></div>
								<div><i class='fa fa-birthday-cake' style='width:18px'></i> Birthdate : <b>" . (!empty($rs_inner["date_birth"]) && $rs_inner["date_birth"] !== "0000-00-00" ? date("m/d/Y", strtotime($rs_inner["date_birth"])) : "N/A") . "</b></div>
								<div><i class='fa fa-venus-mars' style='width:18px'></i> Sex : <b>{$rs_inner["sex"]}</b> Age : <b>{$age}</b> y.o.</div>
								<div><i class='fa fa-registered' style='width:18px'></i> Issued On : <b>" . date("m/d/Y") . "</b></div>
								<div><i class='fa fa-check' style='width:18px'></i> Valid Until: <b>December 31, " . (date("Y") + 1) . "</b></div>
							</div>
									
							<div class='id-details-back'>
								<b style='font-size:16px;text-transform:uppercase;'>{$rs_inner["parent"]}</b><br>
								Relationship: <b>Parent / Guardian</b><br>
								Contact Number: <b>{$rs_inner["contact"]}</b><br>
								Address: <b>{$rs_inner["barangay"]}, {$rs_inner["city_mun"]}</b>
							</div>

							<div class='id-terms-statement'>
								THE HOLDER OF THIS CARD IS AN ENROLLED KINDERGARTEN STUDENT OF THE MUNICIPALITY OF TABINA 
								AND IS ENTITLED TO STUDENT PRIVILEGES, PROTECTION, AND ASSISTANCE UNDER APPLICABLE 
								LOCAL ORDINANCES AND DEPARTMENT OF EDUCATION (DEPED) REGULATIONS.
								IN CASE OF EMERGENCY, PLEASE IMMEDIATELY CONTACT THE PARENT/GUARDIAN LISTED ABOVE.
								THIS CARD IS NON-TRANSFERABLE AND MUST BE SURRENDERED UPON GRADUATION.
							</div>
						
							<div class='id-qrcode-container'>
								<img src='{$qr_path}' height='80' width='80' alt='QR Code'/>
							</div>
						</div>
					</div>
				</div>
				";
			}
		}
	?>
	</div>
</div>

</form>

<script>
	function printF(){
		$('.t_controls').css("display","none");
		window.print(); 
		$('.t_controls').css("display","block");
	}
</script>
		
</body>

</html>