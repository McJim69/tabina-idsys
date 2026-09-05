<?php
	require_once("connect.php");
	include("header.php");
	include("menu.php"); 

	$get_departments = isset($_GET["departments"]) ? $_GET["departments"] : '';
	$get_positions = isset($_GET["positions"]) ? $_GET["positions"] : '';
	$get_barangays = isset($_GET["barangays"]) ? $_GET["barangays"] : '';
	$get_value = isset($_GET['value']) ? $_GET['value'] : '';
	$get_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
?>

<script> setActive("employee"); </script>
<script> setActive("empgrid"); </script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php echo htmlspecialchars(isset($_POST["t_search"]) ? $_POST["t_search"] : '', ENT_QUOTES); ?>"/>
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-dark" type="button" onclick="jump('employees_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='employees_add.php'><button class='thid bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus'></i> Add Employee</button></a>";
				?>			
				<select class="swid spad bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All departments')jump('employees_grid.php'); else jump('employees_grid.php?departments='+encodeURIComponent(this.value)+'&positions=<?php echo urlencode($get_positions);?>&barangays=<?php echo urlencode($get_barangays);?>')">
					<option>All departments</option>
					<?php
						$positions_escaped = $get_positions;
						$ex2 = $link->query("select department from employees where position='".$positions_escaped."' group by department order by department");
						if($get_positions=="" || $get_positions=="All positions")
						$ex2 = $link->query("select department from employees group by department order by department");										
						while($rs2 = mysqli_fetch_array($ex2)){					
							echo"<option ";
							if($get_departments==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('employees_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='employees_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add</x></button></a>";
				?>			
				<select class="thid bmargin btn btn-sm btn-outline-primary" onchange="jump('?departments=<?php echo urlencode($get_departments);?>&barangays=<?php echo urlencode($get_barangays);?>&positions='+encodeURIComponent(this.value))">
					<option>All positions</option>
					<?php
						$departments_escaped = $get_departments;
						$ex2 = $link->query("select position from employees where department='".$departments_escaped."' group by position order by position");
						if($get_departments=="" || $get_departments=="All departments")
						$ex2 = $link->query("select position from employees group by position order by position");										
						while($rs2 = mysqli_fetch_array($ex2)){
							echo"<option ";
							if($get_positions==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>	
				<select class="thid bmargin btn btn-sm btn-outline-secondary" onchange="jump('?departments=<?php echo urlencode($get_departments);?>&positions=<?php echo urlencode($get_positions);?>&barangays='+encodeURIComponent(this.value))">
					<option>All barangays</option>
					<?php
						$positions_escaped = $get_positions;
						$departments_escaped = $get_departments;
						$ex2 = $link->query("select barangay from employees where position='".$positions_escaped."' and department='".$departments_escaped."' group by barangay order by barangay");
						if($get_positions=="" || $get_positions=="All positions")
						$ex2 = $link->query("select barangay from employees group by barangay order by barangay");										
						while($rs2 = mysqli_fetch_array($ex2)){
							echo"<option ";
							if($get_barangays==="$rs2[0]")
							echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>						
			</div>				
		</div>
	</div>
</div>

</div><div class="spid"></div>

<div class="container py-4 grid">
	<div class="row"> 
		<?php			
			$value = $get_value;
					
			if(isset($_POST["b_search"])){
				$value = $_POST["t_search"];
			}
			
			$value_escaped = $value;
			$departments_escaped = $get_departments;
			$positions_escaped = $get_positions;
			$barangays_escaped = $get_barangays;

			$dep="";
			if($get_departments!="All departments" && $get_departments!="") {
				$dep=" and department='".$departments_escaped."'";
			}
				
			$pos="";
			if($get_positions!="All Positions" && $get_positions!="") {
				$pos=" and position='".$positions_escaped."'";
			}

			$bar="";
			if($get_barangays!="All Barangays" && $get_barangays!="") {
				$bar=" and barangay='".$barangays_escaped."'";
			}

			$rec=20;
			$p = $get_page;
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
				
			$ex = $link->query("select * from employees l where 
			   (l.idn like'%".$value_escaped."%' or
			    l.name_fam like'%".$value_escaped."%' or
				l.name_1st like'%".$value_escaped."%' or
				l.name_mid like'%".$value_escaped."%' or
				l.department like'%".$value_escaped."%' or		
				l.agency like'%".$value_escaped."%' or
				l.position like'%".$value_escaped."%' or
				l.purok like'%".$value_escaped."%' or
				l.barangay like'%".$value_escaped."%') $dep $pos $bar order by name_fam LIMIT $from,$to ");			
			
			$ex1 = $link->query("select * from employees l where 
			   (l.idn like'%".$value_escaped."%' or
			    l.name_fam like'%".$value_escaped."%' or
				l.name_1st like'%".$value_escaped."%' or
				l.name_mid like'%".$value_escaped."%' or
				l.department like'%".$value_escaped."%' or		
				l.agency like'%".$value_escaped."%' or
				l.position like'%".$value_escaped."%' or
				l.purok like'%".$value_escaped."%' or
				l.barangay like'%".$value_escaped."%') $dep $pos $bar order by name_fam ");			

			$search_val = strtoupper(isset($_POST["t_search"]) ? $_POST["t_search"] : $value);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$search_val</b>";
			$is_admin = ($_SESSION["access"] !== "");

			while($rs = mysqli_fetch_array($ex)){
				$app_time = !empty($rs['date_appointed']) && $rs['date_appointed'] !== '0000-00-00' ? strtotime($rs['date_appointed']) : time();
				$rs['app_day'] = date('d', $app_time);
				$rs['app_month'] = date('m', $app_time);
				$rs['app_year'] = date('Y', $app_time);
	
				$date_birth = isset($rs["date_birth"]) ? $rs["date_birth"] : '';
				$age = "N/A";
				if (!empty($date_birth) && $date_birth !== '0000-00-00') {
					$birthDate_arr = explode("-", $date_birth);
					$birth_year = intval($birthDate_arr[0]);
					$birth_month = intval($birthDate_arr[1]);
					$birth_day = intval($birthDate_arr[2]);
					$age = (date("md", date("U", mktime(0, 0, 0, $birth_month, $birth_day, $birth_year))) > date("md") ? ((date("Y") - $birth_year) - 0) : (date("Y") - $birth_year));
				}
					
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from employees where idn='$rs[0]'");
					jump("");
				}
				
				if(isset($_POST["b_upImg_$rs[0]"])){
					move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/employees/photos/$rs[0].jpg");
					$link->query("update employees set ispicset=1 where idn='$rs[0]'");
					jump("");
				}

				$photo_path = 'images/user.png';
				if(file_exists("images/employees/photos/$rs[0].jpg")){
					$photo_path = "images/employees/photos/$rs[0].jpg?" . date("h.i.s");
				}

				$fullname = $rs["name_1st"] . " ";
				if ($rs["name_mid"] !== "") {
					$fullname .= substr($rs["name_mid"], 0, 1) . ". ";
				}
				$fullname .= $rs["name_fam"];

				$position_name = "";
				$exp = $link->query("select * from positions where pscode='".$rs["position"]."'");					
				while($row=mysqli_fetch_array($exp)){
					$position_name = $row["psname"];
				}			

				$purok_str = "";
				$add = $rs["purok"];
				if(in_array(strtolower($add[0] ?? ''), array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
					$purok_str = $rs["purok"] . ", " . $rs["barangay"] . ", " . $rs["city_mun"];
				}else{
					$purok_str = $rs["barangay"] . ", " . $rs["city_mun"];
				}
				
				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card employee-card h-100 shadow-sm border-2">
						<div class="employee-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="Employee Photo" onclick="jump('employees_pds.php?employees=<?php echo $rs[0]; ?>')" style="cursor:pointer;" />
							
							<!-- Hover actions overlay -->
							<div class="employee-hover-actions">
								<?php if ($is_admin): ?>
									<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();">
										<i class="fas fa-camera mr-1"></i> Change Photo
									</button>
									<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="jump('employees_idcard_1.php?employees=<?php echo $rs[0]; ?>')">
										<i class="fas fa-id-card mr-1"></i> ID Card
									</button>
									<button type="button" class="btn btn-sm btn-info btn-block my-1" onclick="jump('employees_idtag_1.php?employees=<?php echo $rs[0]; ?>')">
										<i class="fas fa-id-badge mr-1"></i> Office Tag
									</button>
									<a rel="facebox" href="employees_edit_form.php?employees=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Profile
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('employees', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove
									</button>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Fullname -->
								<a href="employees_pds.php?employees=<?php echo $rs[0]; ?>" title="View PDS">
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo str_replace($search_val, $rep, $fullname); ?>
								</h6>
								
								<!-- Position -->
								<div class="small font-weight-bold text-primary mb-2">
									<i class="fas fa-user-tie mr-1"></i><?php echo str_replace($search_val, $rep, $position_name); ?>
								</div>
								</a>
								<!-- ID No -->
								<div class="small mb-1">
									<span class="text-muted">ID No:</span>
									<strong class="text-danger">
										<?php 
											$apm=$rs["app_month"];
											$apd=$rs["app_day"];
											$fdid=$rs[0];
											printf("%02d%02d%s-%03d-%s", $apm, $apd, $rs["app_year"], $fdid, date("Y"));
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
									<?php echo str_replace($search_val, $rep, $purok_str); ?>
								</div>
							</div>
							
							<!-- Contact/Email Info -->
							<div class="small text-muted border-top pt-2">
								<div><i class="fas fa-envelope mr-1 text-info text-truncate d-block"></i><?php echo $rs["emailadd"]; ?></div>
								<div><i class="fas fa-phone mr-1 text-success"></i><strong><?php echo $rs["contact"]; ?></strong></div>
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