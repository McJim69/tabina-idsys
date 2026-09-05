<?php
	require("connect.php");
	include("header.php");
	include("menu.php"); 
?>

<script> setActive("social"); </script>
<script> setActive("4ps"); </script>
<script> setActive("4psgrid"); </script>

<link href="style/grid-cards.css?v=2.0.8" rel="stylesheet" type="text/css"/>

<form method="post" enctype="multipart/form-data">

<div class="t_controls" style="background:url('images/bg.jpg');border:0px;padding-top:15px;padding-bottom:15px">
	<div class="container">
		<div class="row">
			<div class="col justify-content-between align-items-center text-center">
				<input class="swid bmargin btn btn-sm btn-outline-info" placeholder="Search 4Ps..." type="text" name="t_search" id="t_search" value="<?php if($_POST["t_search"]!=""){echo $_POST["t_search"];} ?>" />
				<button class="bmargin btn btn-sm btn-outline-info" type="submit" name="b_search"><i class="fa fa-search tpad"></i> <x class="thid">Search</x></button>
				<button class="bmargin btn btn-sm btn-outline-success" type="button" onclick="jump('indigents_list.php')"><i class="fa fa-list tpad"></i> <x class="thid">List View</x></button>
				<select class="swid bmargin btn btn-sm btn-outline-primary" onchange="if(this.value=='All barangays')jump('indigents_grid.php'); else jump('indigents_grid.php?barangays='+this.value+'')">
					<option>All barangays</option>
					<?php
						$ex2=$link->query("select barangay from indigents group by barangay order by barangay");										
						while($rs2=mysqli_fetch_array($ex2)){
							echo "<option ";
								if($_GET["barangays"]===$rs2[0])
								echo "selected";
							echo">$rs2[0]</option>";
						}
					?>
				</select>
				<button class="bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('indigents_grid.php')"><i class="fa fa-sync tpad"></i> <x class="thid">Refresh</x></button>
				<?php
					if(!isset($_SESSION['user'])){
						echo"";
					}else
						echo"
					<a rel='facebox' href='indigents_add_form.php'><button class='bmargin btn btn-sm btn-outline-primary' type='button'><i class='fa fa-plus tpad'></i> <x class='thid'>Add 4Ps</x></button></a>";
				?>		
				<button class="thid bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('indigents_duplicates.php')"><i class="fa fa-users tpad"></i> <x class="thid">Duplicated</x></button>
				<button class="thid bmargin btn btn-sm btn-outline-info" type="button" onclick="jump('indigents_update.php')"><i class="fa fa-users tpad"></i> <x class="thid">Update Data</x></button>
			</div>				
		</div>
	</div>
</div>

</div><div class="spid"></div>

<div class="container py-4 grid">
	<div class="row"> 
		<?php
			$value = $_GET['value'];
			$bar = "";
			if($_GET["barangays"] != "All Barangays" && $_GET["barangays"] != "") {
				$bar = " and barangay='" . $_GET["barangays"] . "'";
			}
			if(isset($_POST["b_search"])) {
				$value = $_POST["t_search"];
			}
			$rec = 20;
			$p = $_GET['page'];
			if($p > 1) {
				$to = $rec;
				$from = ($p * $rec) - $rec;
				$i = (($p - 1) * $rec) + 1;
			} else {
				$to = $rec;
				$from = 0;
				$i = 1;
				$p = 1;
			}
				
			$ex = $link->query("select * from indigents l where 
			   (l.idn like'%".$value."%' or
				l.fullname like'%".$value."%' or			   
				l.barangay like'%".$value."%') $bar order by fullname LIMIT $from,$to ");			
			
			$ex1 = $link->query("select * from indigents l where 
			   (l.idn like'%".$value."%' or
				l.fullname like'%".$value."%' or			   
				l.barangay like'%".$value."%') $bar order by fullname ");			

			$search_val = strtoupper($_POST["t_search"]);
			$rep = "<b style='color:#0014d0;background:#ffa0a0'>$search_val</b>";
			$is_admin = ($_SESSION["access"] !== "");

			if ($ex->num_rows > 0) {	
				while($rs = mysqli_fetch_array($ex)){
					if(isset($_POST["b_remove_$rs[0]"])){
						$link->query("delete from indigents where idn='$rs[0]'");
						jump("");
					}
					
					if(isset($_POST["b_upImg_$rs[0]"])){
						move_uploaded_file($_FILES["b_file_$rs[0]"]["tmp_name"], "images/indigents/$rs[0].jpg");
						$link->query("update indigents set ispicset=1 where idn='$rs[0]'");
						jump("");
					}

					$photo_path = 'images/blank.jpg';
					if(file_exists("images/indigents/$rs[0].jpg")){
						$photo_path = "images/indigents/$rs[0].jpg?" . date("h.i.s");
					}
					
					?>
					<div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4" id="div_<?php echo $rs[0]; ?>">
						<div class="card indigent-card h-100 shadow-sm border-2">
							<div class="indigent-card-img-container">
								<!-- Index Badge -->
								<span class="badge badge-dark position-absolute m-2" style="top: 5px; left: 0; z-index: 10; font-size: 14px; opacity: 0.8;">#<?php echo $i; ?></span>
								
								<!-- Image -->
								<img src="<?php echo $photo_path; ?>" alt="Indigent Photo" onclick="jump('indigents_pds.php?indigents=<?php echo $rs[0]; ?>')" style="cursor:pointer;" />
								
								<!-- Hover actions overlay -->
								<div class="indigent-hover-actions">
									<button type="button" class="btn btn-sm btn-primary btn-block my-1" onclick="jump('indigents_pds.php?indigents=<?php echo $rs[0]; ?>')">
										<i class="fas fa-eye mr-1"></i> View Profile
									</button>
									<?php if ($is_admin): ?>
										<button type="button" class="btn btn-sm btn-light btn-block my-1" onclick="$('#b_file_<?php echo $rs[0]; ?>').click();">
											<i class="fas fa-camera mr-1"></i> Change Photo
										</button>
										<button type="button" class="btn btn-sm btn-info btn-block my-1" onclick="updateStatus('Payment', '<?php echo $rs[0]; ?>')">
											<i class="fas fa-cash-register mr-1"></i> Payment
										</button>
										<a rel="facebox" href="indigents_edit_form.php?indigents=<?php echo $rs[0]; ?>" class="btn btn-sm btn-warning btn-block my-1">
											<i class="fas fa-edit mr-1"></i> Edit Profile
										</a>
										<button type="button" class="btn btn-sm btn-danger btn-block my-1" onclick="deleteRecord('indigents', <?php echo $rs[0]; ?>, 'div_<?php echo $rs[0]; ?>')">
											<i class="fas fa-trash-alt mr-1"></i> Remove Indigent
										</button>
									<?php endif; ?>
								</div>
							</div>
							
							<div class="card-body p-3 d-flex flex-column justify-content-between">
								<div>
									<!-- Fullname -->
									<h6 class="font-weight-bold mb-1 text-uppercase text-dark text-truncate">
										<?php echo str_replace($search_val, $rep, $rs["fullname"]); ?>
									</h6>
									
									<!-- Address/Barangay -->
									<div class="small font-weight-bold text-primary mb-2">
										<i class="fas fa-map-marker-alt mr-1"></i><?php echo str_replace($search_val, $rep, $rs["barangay"]); ?>
									</div>
									
									<!-- ID No -->
									<div class="small mb-1">
										<span class="text-muted">ID No:</span>
										<strong class="text-dark">
											<?php 
												$cont = $rs[0]; 
												printf("%04d", $cont);  
												echo " - 4PS";
											?>
										</strong>
									</div>
									
									<!-- Period -->
									<div class="small mb-1">
										<span class="text-muted">Period:</span>
										<strong class="text-secondary"><?php echo $rs["period"]; ?></strong>
									</div>

									<!-- Amount -->
									<div class="small mb-1">
										<span class="text-muted">Amount:</span>
										<strong class="text-success">
											<?php 
												if($rs["amount"] == "") {
													echo "N/A";
												} else {
													echo "&#8369; " . number_format($rs["amount"]) . ".00";
												}
											?>
										</strong>
									</div>

									<!-- Date Paid -->
									<div class="small mb-1">
										<span class="text-muted">Received:</span>
										<strong class="text-info">
											<?php 
												if($rs["date_paid"] === "null" || !$rs["date_paid"]) {
													echo "Unpaid";
												} else {
													echo $rs["date_paid"];
												}
											?>
										</strong>
									</div>
									
									<hr class="my-2">
									
									<!-- Remarks -->
									<div class="small text-muted mb-2 text-truncate">
										<strong>Remarks:</strong> <?php echo $rs["remarks"]; ?>
									</div>
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
			} else {
				echo "<div class='col-12 text-center py-5 text-muted'><h3>No Records Found!</h3></div>";
			}
			$link->close();
		?>
	</div>
</div>
</form>

<?php include("footerNAV.php");?>
<?php include("crud_functionjs.php");?>

<script>	
	function updateStatus(value,idn){	
		if(value=="Payment"){
			var rem=prompt("Pls. Enter Date of Payment:");
			updateRemarks(idn,rem);
		}
	}
	
	function updateRemarks(idn,remarks){	
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				if(xmlhttp.responseText==""){
					jump("");
				}else
					alert(xmlhttp.responseText);
			}
		}						
		xmlhttp.open("GET","indigents_update.php?idn="+idn+"&remarks="+remarks,true);
		xmlhttp.send();
	}	
</script>

</body>

</html>

