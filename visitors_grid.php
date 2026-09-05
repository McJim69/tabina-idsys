<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 
	
	$link->query ("UPDATE visitors SET station = REPLACE(station, 'Zamboanga del Sur', 'ZDS') WHERE station LIKE '%Zamboanga del Sur%'") ;
	$link->query ("UPDATE visitors SET office  = REPLACE(office, 'Zamboanga del Sur', 'ZDS') WHERE office LIKE '%Zamboanga del Sur%'") ;
	$link->query ("UPDATE visitors SET address = REPLACE(address, 'Zamboanga del Sur', 'ZDS') WHERE address LIKE '%Zamboanga del Sur%'") ;

	$link->query ("UPDATE visitors SET station = REPLACE(station, 'Zamboanga del Norte', 'ZDN') WHERE station LIKE '%Zamboanga del Norte%'") ;
	$link->query ("UPDATE visitors SET office  = REPLACE(office, 'Zamboanga del Norte', 'ZDZN') WHERE office LIKE '%Zamboanga del Norte%'") ;
	$link->query ("UPDATE visitors SET address = REPLACE(address, 'Zamboanga del Norte', 'ZDN') WHERE address LIKE '%Zamboanga del Norte%'") ;
?>

<script> setActive("visitor"); </script>
<script> setActive("visitgrid"); </script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-dark" type="button" onclick="jump('visitors_list.php')"><i class="fa fa-list tpad"></i> </i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All Positions')jump('visitors_grid.php'); else jump('visitors_grid.php?positions='+this.value+'')">
					<option>All Positions</option>
					<?php
						$ex2=$link->query("select position from visitors group by position order by position");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["positions"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('visitors_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='visitors_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Visitor</x></button></a>";
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
				
			$pos="";
			if($_GET["positions"]!="All Positions" && $_GET["positions"]!="") {
				$pos=" and position='".$_GET["positions"]."'";
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
				
			$ex=$link->query("select * from visitors l where (
				l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.sex like'%".$value."%' or		
				l.position like'%".$value."%' or
				l.station like'%".$value."%' or
				l.office like'%".$value."%' or
				l.address like'%".$value."%') $pos order by name_fam DESC LIMIT $from,$to ");		
				
			$ex1=$link->query("select * from visitors l where (
				l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.sex like'%".$value."%' or		
				l.position like'%".$value."%' or
				l.station like'%".$value."%' or
				l.office like'%".$value."%' or
				l.address like'%".$value."%') $pos order by name_fam DESC");		

			$search_val = strtoupper($_POST["t_search"]);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$search_val</b>";
			$is_admin = ($_SESSION["access"] !== "");
			$is_not_admin_role = ($_SESSION["access"] !== "Administrator");
			
			while($rs=mysqli_fetch_array($ex)){
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from visitors where idn='$rs[0]'");
					jump("");
				}
				
				if(isset($_POST["b_upImg_$rs[0]"])){
					move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/visitors/$rs[0].jpg");
					$link->query("update visitors set ispicset=1 where idn='$rs[0]'");
					jump("");
				}

				$photo_path = 'images/user.png';
				$img_style = "opacity: 0.3;";
				if(file_exists("images/visitors/$rs[0].jpg")){
					$photo_path = "images/visitors/$rs[0].jpg?" . date("h:i:s");
					$img_style = "";
				}

				$fullname = $rs["name_fam"] . ", " . $rs["name_1st"];
				if ($rs["name_mid"] !== "") {
					$fullname .= " " . substr($rs["name_mid"], 0, 1) . ".";
				}

				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card visitor-card h-100 shadow-sm border-2">
						<div class="visitor-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="Visitor Photo" onclick="jump('visitors_pds.php?visitors=<?php echo $rs[0]; ?>')" style="cursor:pointer; <?php echo $img_style; ?>" />
							
							<!-- Hover actions overlay -->
							<div class="visitor-hover-actions">
								<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="jump('visitors_pds.php?visitors=<?php echo $rs[0]; ?>')">
									<i class="fas fa-eye mr-1"></i> Print Visitors CA
								</button>
								<?php if ($is_admin): ?>
									<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();">
										<i class="fas fa-camera mr-1"></i> Change Photo
									</button>
								<?php endif; ?>
								<?php if ($is_admin && !$is_not_admin_role): ?>
									<a rel="facebox" href="visitors_edit_form.php?visitors=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Details
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('visitors', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove Visitor
									</button>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Fullname -->
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo str_replace($search_val, $rep, $fullname); ?>
								</h6>
								
								<!-- Position -->
								<div class="small font-weight-bold text-primary mb-2 text-truncate">
									<i class="fas fa-user-tag mr-1"></i><?php echo str_replace($search_val, $rep, $rs["position"]); ?>
								</div>
								
								<!-- Case / Permit No -->
								<div class="small mb-1 text-truncate">
									<span class="text-muted">Control No:</span>
									<strong class="text-danger">
										<?php 
											$cont = $rs[0];
											printf("CA-%04d-%s", $cont, date("m-d-Y"));
										?>
									</strong>
								</div>
								
								<hr class="my-2">

								<div class="small text-muted mb-1 text-truncate">
									Date: <strong><?php echo $rs["visit_month"] . " " . $rs["visit_day_to"] . ", " . $rs["visit_year"]; ?></strong>
								</div>

								<div class="small text-muted mb-1 text-truncate">
									Office: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["office"]); ?></strong>
								</div>

								<div class="small text-muted mb-1 text-truncate">
									Address: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["address"]); ?></strong>
								</div>

								<div class="small text-muted mb-1 text-truncate">
									Email: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["emailadd"]); ?></strong>
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

</div>

<?php include("footerNAV.php");?>
<?php include("crud_functionjs.php");?>
	
</body>

</html>