<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 
?>

<script> setActive("permit"); </script>
<script> setActive("operate"); </script>
<script> setActive("operategrid"); </script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input  class="swid bmargin btn btn-sm btn-outline-primary" placeholder="Type a keyword" type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-primary" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-dark" type="button" onclick="jump('permit_operate_list.php')"><i class="fa fa-list tpad"></i> </i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-dark" onchange="if(this.value=='All barangays')jump('permit_operate_grid.php'); else jump('permit_operate_grid.php?barangays='+this.value+'')">
								<option>All barangays</option>
								<?php
									$ex2=$link->query("select barangay from permit_operate group by barangay order by barangay");										
									while($rs2=mysqli_fetch_array($ex2)){
										echo "<option ";
											if($_GET["barangays"]===$rs2[0])
											echo "selected";
										echo">$rs2[0]</option>";
									}
								?>
							</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="getID('t_search').value='';jump('permit_operate_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='permit_operate_add.php'><button class='bmargin btn btn-sm btn-outline-success' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add Permit</x></button></a>";
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
			
			$rec=200;
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
				
			$ex=$link->query("select * from permit_operate l where 
			   (l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.tradename like'%".$value."%' or		
				l.purok like'%".$value."%' or			
				l.barangay like'%".$value."%' or
				l.city_mun like'%".$value."%' or
				l.province like'%".$value."%') $bar order by idn DESC LIMIT $from,$to ");		
				
			$ex1=$link->query("select * from permit_operate l where 
			   (l.idn like'%".$value."%' or
				l.name_fam like'%".$value."%' or		
				l.name_1st like'%".$value."%' or		
				l.name_mid like'%".$value."%' or		
				l.tradename like'%".$value."%' or		
				l.purok like'%".$value."%' or			
				l.barangay like'%".$value."%' or
				l.city_mun like'%".$value."%' or
				l.province like'%".$value."%') $bar order by idn DESC ");		

			$search_val=strtoupper($_POST["t_search"]);
			$rep="<b style='color:#0014d0;background:#ffa0a0'>$search_val</b>";
			$is_admin = ($_SESSION["access"] !== "");
			
			while($rs=mysqli_fetch_array($ex)){
				$issued_time = !empty($rs['date_issued']) && $rs['date_issued'] !== '0000-00-00' ? strtotime($rs['date_issued']) : time();
				$rs['is_day'] = date('d', $issued_time);
				$rs['is_month'] = date('m', $issued_time);
				$rs['is_year'] = date('Y', $issued_time);
				if(isset($_POST["b_remove_$rs[0]"])){
					$link->query("delete from permit_operate where idn='$rs[0]'");
					jump("");
				}
				
				if(isset($_POST["b_upImg_$rs[0]"])){
					move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/permit_operate/$rs[0].jpg");
					$link->query("update permit_operate set ispicset=1 where idn='$rs[0]'");
					jump("");
				}

				$photo_path = 'images/configuration.png';
				$img_style = "opacity: 0.3;";
				if(file_exists("images/permit_operate/$rs[0].jpg")){
					$photo_path = "images/permit_operate/$rs[0].jpg?" . date("h:i:s");
					$img_style = "";
				}

				$fullname = $rs["name_1st"] . " ";
				if ($rs["name_mid"] !== "") {
					$fullname .= substr($rs["name_mid"], 0, 1) . ". ";
				}
				$fullname .= $rs["name_fam"];

				$purok_str = "";
				$add = $rs["purok"];
				if(in_array(strtolower($add[0] ?? ''), array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
					$purok_str = $rs["purok"] . ", " . $rs["barangay"] . ", " . $rs["city_mun"];
				}else{
					$purok_str = $rs["barangay"] . ", " . $rs["city_mun"];
				}

				?>
				<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
					<div class="card permit-card h-100 shadow-sm border-2">
						<div class="permit-card-img-container">
							<!-- Index Badge -->
							<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
							
							<!-- Image -->
							<img src="<?php echo $photo_path; ?>" alt="Permit Photo" onclick="jump('permit_operate_pds.php?permit_operate=<?php echo $rs[0]; ?>')" style="cursor:pointer; <?php echo $img_style; ?>" />
							
							<!-- Hover actions overlay -->
							<div class="permit-hover-actions">
								<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="jump('permit_operate_pds.php?permit_operate=<?php echo $rs[0]; ?>')">
									<i class="fas fa-eye mr-1"></i> Print Permit
								</button>
								<?php if ($is_admin): ?>
									<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();">
										<i class="fas fa-camera mr-1"></i> Change Photo
									</button>
									<a rel="facebox" href="permit_operate_edit_form.php?permit_operate=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
										<i class="fas fa-edit mr-1"></i> Edit Details
									</a>
									<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('permit_operate', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
										<i class="fas fa-trash-alt mr-1"></i> Remove Permit
									</button>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="card-body p-3 d-flex flex-column justify-content-between">
							<div>
								<!-- Trade Name -->
								<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
									<?php echo str_replace($search_val, $rep, $rs["tradename"]); ?>
								</h6>
								
								<!-- Case / Permit No -->
								<div class="small mb-1 text-truncate">
									<span class="text-muted">PO No:</span>
									<strong class="text-danger">
										<?php 
											$cont = $rs[0];
											printf("%04d", $cont); echo "-";
											$day = $rs["is_day"];
											printf("%02d", $day); echo "-";
											$mos = $rs["is_month"];
											printf("%02d", $mos); echo "-" . $rs["is_year"];
										?>
									</strong>
								</div>
								
								<hr class="my-2">

								<div class="small text-muted mb-1 text-truncate">
									Nature: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["activity"]); ?></strong>
								</div>
								
								<div class="small text-muted mb-1">
									Application: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $rs["is_mode"]); ?></strong>
								</div>

								<div class="small text-muted mb-1 text-truncate">
									Operator: <strong class="text-success text-uppercase"><?php echo str_replace($search_val, $rep, $fullname); ?></strong>
								</div>

								<div class="small text-muted mb-1 text-truncate">
									Address: <strong class="text-dark"><?php echo str_replace($search_val, $rep, $purok_str); ?></strong>
								</div>
								
								<div class="small text-muted mb-1">
									O.R. No.: <strong class="text-danger"><?php echo $rs["isorno"]; ?></strong> - <strong class="text-primary">&#8369;<?php echo number_format($rs["oramount"]); ?>.00</strong>
								</div>

								<div class="small text-muted mb-1">
									Date Issued: <strong><?php echo $rs["is_month"] . "-" . $rs["is_day"] . "-" . $rs["is_year"]; ?></strong>
								</div>
								
								<?php if ($_SESSION['access'] === 'Administrator' || $_SESSION['access'] === 'Executive'): ?>
								<!-- Application Status Selector -->
								<div class="mt-2 pt-2 border-top">
									<div class="d-flex align-items-center justify-content-between">
										<span class="small text-muted font-weight-bold">Status:</span>
										<select class="form-control form-control-sm border-0 font-weight-bold status-select" data-table="permit_operate" data-id="<?php echo $rs['idn']; ?>" style="width: 110px; border-radius: 20px; font-size: 11px; position: relative; z-index: 10 !important;">
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

</div>

<?php include("footerNAV.php");?>
<?php require("status_selector.php"); ?>
<?php require("crud_functionjs.php"); ?>

</body>

</html>

