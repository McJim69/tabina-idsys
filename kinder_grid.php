<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 
?>

<script>setActive("kinder");</script>
<script>setActive("social");</script>
<script>setActive("kindergrid");</script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container justify-content-between align-items-center text-center">
		<div class="row">
			<div class="col">
				<input class="swid bmargin btn btn-sm btn-outline-dark" placeholder="Search Kindergarten..." type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn-sm btn btn-outline-info" type="button" onclick="jump('kinder_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('kinder_grid.php'); else jump('kinder_grid.php?barangays='+this.value+'')">
					<option>All barangays</option>
					<?php
						$ex2=$link->query("select barangay from kinder group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn-sm btn btn-outline-info" type="button" onclick="getID('t_search').value='';jump('kinder_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='kinder_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Kinder</x></button></a>";
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
			if($_GET["barangays"]!="All barangays" && $_GET["barangays"]!="") {
				$bar=" and barangay='".$_GET["barangays"]."'";
			}
					
			if(isset($_POST["b_search"])){
				$value=$_POST["t_search"];
			}
			
			$rec=20;
			$p = isset($_GET['page']) ? intval($_GET['page']) : 1;
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
								
			$query_str = "select * from kinder l where 
			   (l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.purok like'%".$value."%' or			
				l.barangay like'%".$value."%' or
				l.city_mun like'%".$value."%' or
				l.province like'%".$value."%') $bar order by name_fam LIMIT $from,$to ";
			$ex = $link->query($query_str);
			if (!$ex) {
				die("SQL Error in kinder_grid.php: " . $link->error . " | Query: " . htmlspecialchars($query_str));
			}

			$query_str1 = "select * from kinder l where 
			   (l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.purok like'%".$value."%' or			
				l.barangay like'%".$value."%' or
				l.city_mun like'%".$value."%' or
				l.province like'%".$value."%') $bar order by name_fam ";
			$ex1 = $link->query($query_str1);
			if (!$ex1) {
				die("SQL Error in kinder_grid.php: " . $link->error . " | Query: " . htmlspecialchars($query_str1));
			}
				
			$search_val = strtoupper($_POST["t_search"]);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$search_val</b>";
			$is_admin = ($_SESSION["access"] !== "");
			
			while($rs=mysqli_fetch_array($ex)){
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from kinder where idn='$rs[0]'");
					jump("");
				}
				
				if(isset($_POST["b_upImg_$rs[0]"])){
					move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/kinder/$rs[0].jpg");
					$link->query("update kinder set ispicset=1 where idn='$rs[0]'");
					jump("");
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
				
				$photo_path = 'images/DSWD.png';
				if(file_exists("images/kinder/$rs[0].jpg")){
					$photo_path = "images/kinder/$rs[0].jpg?" . date("h:i:s");
				}

				$fullname = $rs["name_1st"] . " ";
				if ($rs["name_mid"] !== "") {
					$fullname .= $rs["name_mid"] . " ";
				}
				$fullname .= $rs["name_fam"];

				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card kinder-card h-100 shadow-sm border-2">
						<div class="kinder-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="Kinder Photo" />
							
							<!-- Hover actions overlay -->
							<div class="kinder-hover-actions">
								<button type="button" class="btn btn-sm btn-info btn-block my-1" onclick="jump('kinder_cert.php?kinder=<?php echo $rs[0]; ?>')">
									<i class="fas fa-certificate mr-1"></i> View Certificate
								</button>
								<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="jump('kinder_idcard.php?kinder=<?php echo $rs[0]; ?>')">
									<i class="fas fa-id-card mr-1"></i> View ID Card
								</button>
								<?php if ($is_admin): ?>
									<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();">
										<i class="fas fa-camera mr-1"></i> Change Photo
									</button>
									<a rel="facebox" href="kinder_edit_form.php?kinder=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Profile
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('kinder', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove Kinder
									</button>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Fullname -->
								<a href="kinder_pds.php?kinder=<?php echo $rs[0]; ?>" title="View Profile">
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo str_replace($search_val, $rep, $fullname); ?>
								</h6>
								
								<!-- Barangay -->
								<div class="small font-weight-bold text-primary mb-2 text-truncate">
									<i class="fas fa-school mr-1"></i>Kindergarten - <?php echo str_replace($search_val, $rep, $rs["barangay"]); ?>
								</div>
								</a>
								<hr class="my-2">

								<!-- Birth Date / Age -->
								<div class="small text-muted mb-1">
									Birth Date: <strong class="text-dark"><?php echo htmlspecialchars($rs["date_birth"]); ?></strong> (<?php echo $age; ?> y.o.)
								</div>

								<!-- Address -->
								<div class="small text-muted mb-1 text-truncate">
									Purok: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["purok"]); ?></strong>
								</div>
								<div class="small text-muted mb-1 text-truncate">
									Barangay: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["barangay"]); ?></strong>
								</div>
								<div class="small text-muted mb-1 text-truncate">
									Municipality: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["city_mun"]); ?></strong>
								</div>
								<div class="small text-muted mb-1 text-truncate">
									Province: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["province"]); ?></strong>
								</div>
								
								<!-- Parent Info -->
								<div class="small text-muted mb-1 text-truncate border-top pt-2">
									Parent: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["parent"]); ?></strong>
								</div>
							</div>
							
							<!-- Contact Info -->
							<div class="small text-muted border-top pt-2">
								<i class="fas fa-phone mr-1 text-success"></i><strong><?php echo str_replace($search_val, $rep, $rs["contact"]); ?></strong>
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
<?php include("crud_functionjs.php");?>

</body>

</html>
