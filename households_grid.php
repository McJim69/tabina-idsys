<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 

	// Update Typos //
	// $link->query ("UPDATE households SET hh_religion = REPLACE(hh_religion, 'Roman Cathoic', 'Roman Catholic') WHERE hh_religion LIKE '%Roman Cathoic%'") ;	
	
	// Remove HH Head Duplicate //	
	// $link->query("DELETE a FROM idsystem_lgu.households a INNER JOIN idsystem_lgu.households a2 
	// WHERE a.hhid > a2.hhid
	// AND   a.hh_name = a2.hh_name
	// AND   a.barangay = a2.barangay
	// AND   a.purok = a2.purok")or die(mysqli_error($link));
	
	// Remove HH Member Duplicate //
	// $link->query("DELETE a FROM idsystem_lgu.hh_members a INNER JOIN idsystem_lgu.hh_members a2 
	// WHERE a.hmid > a2.hmid
	// AND   a.hm_name = a2.hm_name
	// AND   a.barangay = a2.barangay
	// AND   a.purok = a2.purok")or die(mysqli_error($link));		
?>

<script> setActive("household"); </script>
<script> setActive("hhgrid"); </script>

<link href="style/grid-cards.css" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container justify-content-between align-items-center text-center">
		<div class="row">
			<div class="col">
				<input  class="swid bmargin btn btn-sm btn-outline-dark" placeholder="Search HH Head..." type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('households_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('households_grid.php'); else jump('households_grid.php?barangays='+this.value+'')">
					<option>All Barangays</option>
					<?php
						$ex2=$link->query("select barangay from households group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('households_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<button class='bmargin btn btn-sm btn-outline-success' onclick=\"jump('households_add_form.php')\" type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Household</x></button>";
				?>	
			</div>				
		</div>
	</div>
</div>

</div><div class="spid"></div>

<div class="container py-4 grid">
	<div class="row">
		<?php
			$value=$_GET['value'];

			$bar="";
			if($_GET["barangays"]!="All Barangays" && $_GET["barangays"]!="") {
				$bar=" and barangay='".$_GET["barangays"]."'";
			}
					
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
				
			$ex=$link->query("select * from households l where 
			   (l.hh_name like'%".$value."%' or
				l.hh_occupation like'%".$value."%' or
				l.hh_religion like'%".$value."%' or
				l.hh_ethnicity like'%".$value."%' or
				l.hh_birth like'%".$value."%' or
				l.barangay like'%".$value."%') $bar order by hh_name LIMIT $from,$to ");			

			$ex1=$link->query("select * from households l where 
			   (l.hh_name like'%".$value."%' or
				l.hh_occupation like'%".$value."%' or
				l.hh_religion like'%".$value."%' or
				l.hh_ethnicity like'%".$value."%' or
				l.hh_birth like'%".$value."%' or
				l.barangay like'%".$value."%') $bar order by hh_name");			
			
			$search_val = strtoupper($_POST["t_search"]);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$search_val</b>";
			$is_admin = ($_SESSION["access"] !== "");
	
			while($rs=mysqli_fetch_array($ex)){
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from households where hhid='$rs[0]'");
					jump("");
				}
				
				if(isset($_POST["b_upImg_$rs[0]"])){
					move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/households/$rs[0].jpg");
					$link->query("update households set ispicset=1 where hhid='$rs[0]'");
					jump("");
				}
				
				$hhid=$rs["hhid"];
				$ex4=$link->query("select count(*) from hh_members where hh_members.hm_belong='$hhid' and hh_members.hmid=hh_members.hmid");
				$shmc=mysqli_fetch_array($ex4);
				$thmc=number_format($shmc[0],0);
				
				$link->query("update households set hh_members='$thmc' where hhid='$hhid'") or die (mysqli_error($link));

				$birthDate = $rs["hh_birth"];
				$birthDate = explode("-", $birthDate);
				$age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[2], $birthDate[0]))) > date("md") ? ((date("Y")-$birthDate[0])-0):(date("Y")-$birthDate[0]));

				$photo_path = 'images/user.png';
				if(file_exists("images/households/$rs[0].jpg")){
					$photo_path = "images/households/$rs[0].jpg?" . date("h:i:s");
				}

				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card hh-card h-100 shadow-sm border-2">
						<div class="hh-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="Household Photo" onclick="jump('households_pds.php?households=<?php echo $rs[0]; ?>')" style="cursor:pointer;" />
							
							<!-- Hover actions overlay -->
							<div class="hh-hover-actions">
								<?php if ($is_admin): ?>
									<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="$('#b_file_<?php echo $rs['hhid']; ?>').click();">
										<i class="fas fa-camera mr-1"></i> Change Photo
									</button>
									<a rel="facebox" href="households_add_mem.php?households=<?php echo $rs['hhid']; ?>" class="btn btn-sm btn-info btn-block my-1">
										<i class="fas fa-plus-circle mr-1"></i> Add Members
									</a>
									<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="updateStatus('Remarks', '<?php echo $rs[0]; ?>')">
										<i class="fas fa-comment-dots mr-1"></i> Add Remarks
									</button>
									<a rel="facebox" href="households_edit_form.php?households=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Profile
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('households', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove
									</button>
								<?php endif; ?>
							</div>
						</div>
						<a href="households_pds.php?households=<?php echo $rs[0]; ?>" title="View Profile">
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Head Name -->
								
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo str_replace($search_val, $rep, $rs["hh_name"]); ?>
								</h6>

								<!-- Barangay / Address -->
								<div class="small font-weight-bold text-primary mb-2 text-truncate">
									<i class="fas fa-map-marker-alt mr-1"></i><?php echo $rs["purok"] . ", " . str_replace($search_val, $rep, $rs["barangay"]); ?>
								</div>
								
								<!-- Household ID & Members count -->
								<div class="small mb-1 d-flex justify-content-between">
									<span>HHID: <strong class="text-dark"><?php printf("%04d", $rs["hhid"]); ?></strong></span>
									<span>Members: <strong class="text-info"><?php echo $rs["hh_members"]; ?></strong></span>
								</div>
								
								<hr class="my-2">

								<!-- Birth Date / Age -->
								<div class="small text-muted mb-1">
									Birth Date: <strong class="text-dark"><?php echo $rs["hh_birth"]; ?></strong> (<?php echo $age; ?> y.o.)
								</div>

								<div class="small text-muted mb-1">
									Occupation: <strong class="text-secondary"><?php echo $rs["hh_occupation"]; ?></strong>
								</div>
								
								<div class="small text-muted mb-1">
									Ethnicity: <strong class="text-secondary"><?php echo $rs["hh_ethnicity"]; ?></strong>
								</div>

								<div class="small text-muted mb-1">
									Religion: <strong class="text-secondary"><?php echo $rs["hh_religion"]; ?></strong>
								</div>

								<div class="small text-muted mb-1 text-truncate">
									Remarks: <strong class="text-dark"><?php echo $rs["remarks"]; ?></strong>
								</div>
							</div>
							</a>
							<!-- Contact Info -->
							<div class="small text-muted border-top pt-2">
								<i class="fas fa-phone mr-1 text-success"></i><strong><?php echo $rs["hh_contact"]; ?></strong>
							</div>
						</div>
					</div>

					<?php if ($is_admin): ?>
						<input type="file" name="b_file_<?php echo $rs['hhid']; ?>" id="b_file_<?php echo $rs['hhid']; ?>" style="display:none" onchange="if(this.value!='') $('#b_upImg_<?php echo $rs['hhid']; ?>').click();"/> 
						<input type="submit" name="b_upImg_<?php echo $rs['hhid']; ?>" id="b_upImg_<?php echo $rs['hhid']; ?>" style="display:none"/> 
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
<?php include("crud_functionjs.php");?>

</body>

</html>
