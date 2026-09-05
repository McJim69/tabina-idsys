<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 
?>

<script>setActive("certclear");</script>
<script>setActive("cert");</script>
<script>setActive("certgrid");</script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="jump('cert_indigency_list.php')"><i class="fa fa-list tpad"></i> </i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All barangays')jump('cert_indigency_grid.php'); else jump('cert_indigency_grid.php?barangays='+this.value+'')">
					<option>All barangays</option>
					<?php
						$ex2 = $link->query("select barangay from cert_indigency group by barangay order by barangay");
						while($rs = mysqli_fetch_array($ex2)) {
							echo "<option ";
							if($_GET["barangays"]==="$rs[0]")
							echo "selected";
							echo">".$rs[0]."</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('cert_indigency_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
					<?php
						if(!isset($_SESSION['user'])){
							echo"";
						}else
							echo"
						<a rel='facebox' href='cert_indigency_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Certificate</x></button></a>";
					?>				
			</div>				
		</div>
	</div>
</div>

<div class="spid"></div>
	
<div class="container py-4 grid">
	<div class="row"> 
		<?php
			$value = $_GET['value'];
				
			$bar="";
			if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="") {
				$bar=" and barangay='".$_GET["barangays"]."'";
			}
					
			$idn="";
			if($_GET["idn"]!="All idn" && $_GET["idn"]!="") {
				$idn=" and idn='".$_GET["idn"]."'";
			}

			if($_POST["b_search"] !== null){
				$value=$_POST["t_search"];
			}
			
			$rec=20;
			$p = $_GET['page'] != '' ? intval($_GET['page']) : 1;
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
				
			$ex = "select * from cert_indigency l where (
				l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.purok like'%".$value."%' or			
				l.barangay like'%".$value."%' or
				l.city_mun like'%".$value."%' or
				l.province like'%".$value."%') $bar $idn order by idn DESC LIMIT $from,$to";		
			
			$ex1 = $link->query("select * from cert_indigency l where (
				l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.purok like'%".$value."%' or			
				l.barangay like'%".$value."%' or
				l.city_mun like'%".$value."%' or
				l.province like'%".$value."%') $bar $idn order by idn DESC ");		

			$result = $link->query($ex);
			
			$search_term_for_highlight = strtoupper(isset($_POST["t_search"]) ? $_POST["t_search"] : $value);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$search_term_for_highlight</b>";
			$is_admin = ($_SESSION["access"] !== "");

			if ($result->num_rows > 0) {
				while($row = $result->fetch_array()) {
					if(isset($_POST["b_remove_".$row['idn'].""])){
						$link -> query("delete from cert_indigency where idn='".$row['idn']."'");
						jump("");
					}
					$date_birth = isset($row["date_birth"]) ? $row["date_birth"] : '';
					$age = "N/A";
					if (!empty($date_birth) && $date_birth !== '0000-00-00') {
						$birthDate_arr = explode("-", $date_birth);
						$birth_year = intval($birthDate_arr[0]);
						$birth_month = intval($birthDate_arr[1]);
						$birth_day = intval($birthDate_arr[2]);
						$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
					}
					
					$photo_path = 'images/upgrade.png';
					$img_style = "opacity: 0.3;";

					$fullname = $row["name_1st"] . " ";
					if ($row["name_mid"] !== "") {
						$fullname .= substr($row["name_mid"], 0, 1) . ". ";
					}
					$fullname .= $row["name_fam"];
					
					?>
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $row['idn']; ?>">
						<div class="card cert-card h-100 shadow-sm border-2">
							<div class="cert-card-img-container">
								<!-- Index Badge -->
								<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
								
								<!-- Image -->
								<img src="<?php echo $photo_path; ?>" alt="Certificate Photo" onclick="jump('cert_indigency_pds.php?cert_indigency=<?php echo $row['idn']; ?>')" style="cursor:pointer; <?php echo $img_style; ?>" />
								
								<!-- Hover actions overlay -->
								<div class="cert-hover-actions">
									<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="jump('cert_indigency_pds.php?cert_indigency=<?php echo $row['idn']; ?>')">
										<i class="fas fa-eye mr-1"></i> Print Certificate
									</button>
									<?php if ($is_admin): ?>
										<a rel="facebox" href="cert_indigency_edit_form.php?cert_indigency=<?php echo $row['idn']; ?>" class="btn btn-sm btn-warning btn-block my-1">
											<i class="fas fa-edit mr-1"></i> Edit Details
										</a>
										<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('cert_indigency', <?php echo $row['idn']; ?>, 'div_<?php echo $row['idn']; ?>')">
											<i class="fas fa-trash-alt mr-1"></i> Remove Cert
										</button>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="card-body p-3 d-flex flex-column justify-content-between">
								<div>
									<!-- Fullname -->
									<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
										<?php echo str_replace($value, $rep, $fullname); ?>
									</h6>
									
									<!-- ID No -->
									<div class="small mb-1">
										<span class="text-muted">COI No:</span>
										<strong class="text-danger">
											<?php 
												$cont = $row["idn"];
												printf("%04d", $cont); echo "-";
												$date = $row["date_app"];
												printf("%08d", $date);
											?>
										</strong>
									</div>
									
									<hr class="my-2">

									<!-- Personal Info Row -->
									<div class="d-flex justify-content-between small text-muted mb-2">
										<span>Age: <strong class="text-dark"><?php echo $age; ?> y.o.</strong></span>
									</div>
									
									<!-- Address -->
									<div class="small text-muted mb-2 text-truncate">
										<i class="fas fa-map-marker-alt mr-1 text-danger"></i>
										<?php echo str_replace($value, $rep, $row["purok"] . ", " . $row["barangay"] . ", " . $row["city_mun"]); ?>
									</div>
									<div class="small text-muted mb-2 text-truncate">
										Province: <?php echo str_replace($value, $rep, $row["province"]); ?>
									</div>
									
									<?php if ($_SESSION['access'] === 'Administrator' || $_SESSION['access'] === 'Executive'): ?>
									<!-- Application Status Selector -->
									<div class="mt-2 pt-2 border-top">
										<div class="d-flex align-items-center justify-content-between">
											<span class="small text-muted font-weight-bold">Status:</span>
											<select class="form-control form-control-sm border-0 font-weight-bold status-select" data-table="cert_indigency" data-id="<?php echo $row['idn']; ?>" style="width: 110px; border-radius: 20px; font-size: 11px; position: relative; z-index: 10 !important;">
												<option value="Pending" <?php if (strtolower($row['app_status'] ?? '') === 'pending') echo 'selected'; ?>>Pending</option>
												<option value="Approved" <?php if (strtolower($row['app_status'] ?? '') === 'approved') echo 'selected'; ?>>Approved</option>
												<option value="Denied" <?php if (strtolower($row['app_status'] ?? '') === 'denied') echo 'selected'; ?>>Denied</option>
											</select>
										</div>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
					<?php
					$i++;
				}
			} else {
				echo "<div class='col-12 text-center py-5 text-muted'><h3>No Records Found!</h3></div>";
			}
			
			$link->close();			
		?>	
	</div>
</div>
</form>
</div>

</div>

<?php include("footerNAV.php");?>
<?php require("status_selector.php"); ?>
<?php require("crud_functionjs.php");?>

</body>

</html>

