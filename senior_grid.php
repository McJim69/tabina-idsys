<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 
	
	$value=$_GET['value'];
				
	$bar="";
		if($_GET["barangay"]!="All barangays" && $_GET["barangay"]!="")
			$bar=" and barangay='".$_GET["barangay"]."'";

	$age="";
		if($_GET["age"]!="All ages" && $_GET["age"]!="")
			$age=" and age='".$_GET["age"]."'";	

	$pen="";
		if($_GET["pensioner"]!="Width pensions" && $_GET["pensioner"]!="")
			$pen=" and pensioner='".$_GET["pensioner"]."'";		
					
	if(isset($_POST["b_search"])){
		$value=$_POST["t_search"];
	}

	$rec=20;
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
				
	$ex=$link->query("select * from senior l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or	
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%' or
		l.pensioner like'%".$value."%') $bar $age $pen order by name_fam LIMIT $from,$to ");		

	$ex1=$link->query("select * from senior l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or	
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%' or
		l.pensioner like'%".$value."%') $bar $age $pen order by name_fam ");		
	//
?>

<script>setActive("senior");</script>
<script>setActive("social");</script>
<script>setActive("seniorgrid");</script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="getID('t_search').value='';jump('senior_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<button class="bmargin btn btn-sm btn-outline-dark" type="button" value="List View" onclick="jump('senior_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All barangays')jump('senior_grid.php'); else jump('?barangay='+this.value)" >
					<option>All Barangays</option>
					<?php
						$ex2=$link->query("select barangay from senior where city_mun like'".$_GET["municipality"]."%' group by barangay order by barangay") or die(mysqli_error($link));
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["barangay"]===$rs2[0])
							echo "selected";
							echo" >$rs2[0]</option>";
						}
					?>
				</select>
				<select class="thid bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All ages')jump('senior_grid.php'); else jump('?age='+this.value+'&barangay=<?php echo $_GET["barangay"];?>&pensioner=<?php echo $_GET["pensioner"];?>')" >
					<option>Ages</option>
					<?php
						$ex2=$link->query("select age from senior where barangay like'".$_GET["barangay"]."%' and pensioner like'".$_GET["pensioner"]."%' group by age order by age") or die(mysqli_error($link));
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["age"]===$rs2[0])
							echo "selected";
							echo" >$rs2[0]</option>";
						}
					?>
				</select>
				<select class="thid bmargin btn btn-sm btn-outline-secondary" onchange="if(this.value=='With pensions')jump('senior_grid.php'); else jump('?pensioner='+this.value+'&barangay=<?php echo $_GET["barangay"];?>&age=<?php echo $_GET["age"];?>')" >
					<option>Pensioner</option>
					<?php
						$ex2=$link->query("select pensioner from senior where barangay like'".$_GET["barangay"]."%' and age like'".$_GET["age"]."%' group by pensioner order by pensioner") or die(mysqli_error($link));
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
							if($_GET["pensioner"]===$rs2[0])
							echo "selected";
							echo" >$rs2[0]</option>";
						}
					?>
				</select>						
				<button class="bmargin btn btn-sm btn-outline-danger" type="button" onclick="jump('senior_grid_80up.php')"><i class="fa fa-wheelchair tpad"></i> <x class="thid">SC 80 UP</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='senior_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Senior</x></button></a>";
				?>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('senior_statistics.php')"><i class="fa fa-chart-area tpad"></i> <x class="thid">View Stats</x></button>				
			</div>				
		</div>
	</div>
</div>

<div class="spid"></div>

<div class="container py-4 grid">
	<div class="row"> 
		<?php
			$value=strtoupper($_POST["t_search"]);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$value</b>";
			$is_admin = ($_SESSION["access"] !== "Users");
			
			while($rs=mysqli_fetch_array($ex)){
				
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from senior where idn='$rs[0]'");
					jump("");
				}
				
				if(isset($_POST["b_upImg_$rs[0]"])){
					move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/senior/$rs[0].jpg");
					$link->query("update senior set ispicset=1 where idn='$rs[0]'");
					jump("");
				}

				$photo_path = 'images/user.png';
				if(file_exists("images/senior/$rs[0].jpg")){
					$photo_path = "images/senior/$rs[0].jpg?" . date("h.i.s");
				}

				$date_birth = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
				$age = "N/A";
				if (!empty($date_birth) && $date_birth !== '0000-00-00') {
					$birthDate_arr = explode("-", $date_birth);
					$birth_year = intval($birthDate_arr[0]);
					$birth_month = intval($birthDate_arr[1]);
					$birth_day = intval($birthDate_arr[2]);
					$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
				}
				$link->query("UPDATE senior set age = '$age' where idn='".$rs["idn"]."'");

				$pensioner_str = ($rs["pensioner"] === "Yes") ? "Pensioner" : "Non-Pensioner";
				$pensioner_badge = ($rs["pensioner"] === "Yes") ? "badge-success" : "badge-danger";

				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card senior-card h-100 shadow-sm border-2">
						<div class="senior-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="Senior Photo" onclick="jump('senior_pds.php?senior=<?php echo $rs[0]; ?>')" style="cursor:pointer;" />
							
							<!-- Hover actions overlay -->
							<div class="senior-hover-actions">
								<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="jump('senior_pds.php?senior=<?php echo $rs[0]; ?>')">
									<i class="fas fa-eye mr-1"></i> View Profile
								</button>
								<?php if ($is_admin): ?>
									<button type="button" class="btn btn-sm btn-info btn-block my-1" onclick="jump('senior_idcard1.php?senior=<?php echo $rs[0]; ?>')">
										<i class="fas fa-id-card mr-1"></i> ID Card
									</button>
									<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();">
										<i class="fas fa-camera mr-1"></i> Change Photo
									</button>
									<a rel="facebox" href="senior_edit_form.php?senior=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Profile
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('senior', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove Senior
									</button>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Fullname -->
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo str_replace($value, $rep, $rs["name_fam"] . ", " . $rs["name_1st"] . " " . $rs["name_mid"]); ?>
								</h6>
								
								<!-- Subtitles -->
								<div class="mb-2 d-flex justify-content-between flex-wrap">
									<span class="badge badge-secondary mr-2 mb-1">OSCA ID: <?php echo sprintf("%04d", $rs["assoc_id_no"]); ?></span>
									<span class="badge <?php echo $pensioner_badge; ?> mb-1"><?php echo $pensioner_str; ?></span>
								</div>

								<!-- NCSC RRN -->
								<div class="small mb-2">
									<span class="text-muted">NCSC RRN:</span>
									<?php if ($rs["ncsc_rrn"] !== ""): ?>
										<strong class="text-dark"><?php echo $rs["ncsc_rrn"]; ?></strong>
									<?php else: ?>
										<strong class="text-danger">Unregistered</strong>
									<?php endif; ?>
								</div>
								
								<hr class="my-2">
								
								<!-- Personal Info Row -->
								<div class="d-flex justify-content-between small text-muted mb-2">
									<span>Sex: <strong class="text-dark"><?php echo $rs["sex"]; ?></strong></span>
									<span>Age: <strong class="text-dark"><?php echo $age; ?> y.o.</strong></span>
								</div>
								
								<!-- Address -->
								<div class="small text-muted mb-2 text-truncate">
									<i class="fas fa-map-marker-alt mr-1 text-danger"></i>
									<?php echo str_replace($value, $rep, $rs["barangay"] . ", " . $rs["city_mun"] . ", ZDS"); ?>
								</div>
								
								<?php if ($_SESSION['access'] === 'Administrator' || $_SESSION['access'] === 'Executive'): ?>
								<!-- Application Status Selector -->
								<div class="mt-2 pt-2 border-top">
									<div class="d-flex align-items-center justify-content-between">
										<span class="small text-muted font-weight-bold">Status:</span>
										<select class="form-control form-control-sm border-0 font-weight-bold status-select" data-table="senior" data-id="<?php echo $rs['idn']; ?>" style="width: 110px; border-radius: 20px; font-size: 11px; position: relative; z-index: 10 !important;">
											<option value="Pending" <?php if (strtolower($rs['status'] ?? '') === 'pending') echo 'selected'; ?>>Pending</option>
											<option value="Approved" <?php if (strtolower($rs['status'] ?? '') === 'approved') echo 'selected'; ?>>Approved</option>
											<option value="Denied" <?php if (strtolower($rs['status'] ?? '') === 'denied') echo 'selected'; ?>>Denied</option>
										</select>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<?php if ($is_admin): ?>
						<input type="file" name="b_file_<?php echo $rs[0]; ?>" id="b_file_<?php echo $rs[0]; ?>" style="display:none" onchange="if(this.value!='') $('#b_upImg_<?php echo $rs[0]; ?>').click();"/> 
						<input type="submit" name="b_upImg_<?php echo $rs[0]; ?>" id="b_upImg_<?php echo $rs[0]; ?>" style="display:none"/> 
					<?php endif; ?>
				</div>
				<?php
				$i++;
			}
		?>
	</div>
</div>

</form>

<?php include("footerNAV.php");?>
<?php require("crud_functionjs.php"); ?>
<?php require("status_selector.php"); ?>

</body>

</html>
