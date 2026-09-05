<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 
	
	$value=$_GET['value'];
				
	$bar="";
		if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="")
			$bar=" and barangay='".$_GET["barangays"]."'";
					
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
				
	$ex=$link->query("select * from solo_parent l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or		
		l.purok like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $bar order by idn DESC LIMIT $from,$to ");		

	$ex1=$link->query("select * from solo_parent l where 
	   (l.idn like'%".$value."%' or
		l.name_fam like'%".$value."%' or		
		l.name_1st like'%".$value."%' or		
		l.name_mid like'%".$value."%' or		
		l.purok like'%".$value."%' or			
		l.barangay like'%".$value."%' or
		l.city_mun like'%".$value."%' or
		l.province like'%".$value."%') $bar order by name_fam ");		
	//
?>

<script>setActive("solo");</script>
<script>setActive("social");</script>
<script>setActive("sologrid");</script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container justify-content-between align-items-center text-center">
		<div class="row">
			<div class="col">
				<input class="swid bmargin btn btn-sm btn-outline-dark" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('solo_parent_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-primary" style="padding:5px" onchange="if(this.value=='All barangays')jump('solo_parent_grid.php'); else jump('solo_parent_grid.php?barangays='+this.value+'')">
					<option>All barangays</option>
					<?php
						$ex2=$link->query("select barangay from solo_parent group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('solo_parent_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='solo_parent_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Solo</x></button></a>";
				?>	
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
				
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from solo_parent where idn='$rs[0]'");
					jump("");
				}
				
				if(isset($_POST["b_upImg_$rs[0]"])){
					move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/solo_parent/$rs[0].jpg");
					$link->query("update solo_parent set ispicset=1 where idn='$rs[0]'");
					jump("");
				}

				$photo_path = 'images/user.png';
				if(file_exists("images/solo_parent/$rs[0].jpg")){
					$photo_path = "images/solo_parent/$rs[0].jpg?" . date("h.i.s");
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
				
				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card solo-card h-100 shadow-sm border-2">
						<div class="solo-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="Solo Parent Photo" onclick="jump('solo_parent_pds.php?solo_parent=<?php echo $rs[0]; ?>')" style="cursor:pointer;" />
							
							<!-- Hover actions overlay -->
							<div class="solo-hover-actions">
								<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="jump('solo_parent_pds.php?solo_parent=<?php echo $rs[0]; ?>')">
									<i class="fas fa-eye mr-1"></i> View Profile
								</button>
								<?php if ($is_admin): ?>
									<button type="button" class="btn btn-sm btn-info btn-block my-1" onclick="jump('solo_parent_idcard1.php?solo_parent=<?php echo $rs[0]; ?>')">
										<i class="fas fa-id-card mr-1"></i> ID Card
									</button>
									<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();">
										<i class="fas fa-camera mr-1"></i> Change Photo
									</button>
									<a rel="facebox" href="solo_parent_edit_form.php?solo_parent=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Profile
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('solo_parent', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove Solo Parent
									</button>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Fullname -->
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo str_replace($value, $rep, $rs["name_1st"] . " " . $rs["name_mid"] . " " . $rs["name_fam"]); ?>
								</h6>
								
								<!-- Position -->
								<div class="small font-weight-bold text-primary mb-2">
									<i class="fas fa-user-tag mr-1"></i><?php echo str_replace($value, $rep, ($rs["position"] ? $rs["position"] : "Member")); ?>
								</div>
								
								<!-- ID No -->
								<div class="small mb-1">
									<span class="text-muted">ID No:</span>
									<strong class="text-danger">
										<?php 
											$aid = sprintf("%04d", $rs["assoc_id_no"]);
											$arm = sprintf("%02d", $rs["assoc_reg_month"]);
											$ard = sprintf("%02d", $rs["assoc_reg_day"]);
											echo $aid . "-" . $arm . $ard . "-" . $rs["assoc_reg_year"];
										?>
									</strong>
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
									<?php echo str_replace($value, $rep, $rs["purok"] . ", " . $rs["barangay"] . ", " . $rs["city_mun"]); ?>
								</div>
							</div>
							
							<!-- Contact Info -->
							<div class="small text-muted border-top pt-2">
								<i class="fas fa-phone mr-1 text-success"></i>
								<strong><?php echo str_replace($value, $rep, $rs["mobileno"]); ?></strong>
							</div>
							
							<?php if ($_SESSION['access'] === 'Administrator' || $_SESSION['access'] === 'Executive'): ?>
							<!-- Application Status Selector -->
							<div class="mt-2 pt-2 border-top">
								<div class="d-flex align-items-center justify-content-between">
									<span class="small text-muted font-weight-bold">Status:</span>
									<select class="form-control form-control-sm border-0 font-weight-bold status-select" data-table="solo_parent" data-id="<?php echo $rs['idn']; ?>" style="width: 110px; border-radius: 20px; font-size: 11px; position: relative; z-index: 10 !important;">
										<option value="Pending" <?php if (strtolower($rs['status'] ?? '') === 'pending') echo 'selected'; ?>>Pending</option>
										<option value="Approved" <?php if (strtolower($rs['status'] ?? '') === 'approved') echo 'selected'; ?>>Approved</option>
										<option value="Denied" <?php if (strtolower($rs['status'] ?? '') === 'denied') echo 'selected'; ?>>Denied</option>
									</select>
								</div>
							</div>
							<?php endif; ?>
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

