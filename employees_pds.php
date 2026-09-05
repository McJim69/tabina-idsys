<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("employees"); </script>

<!-- Controls Toolbar -->
<div class="t_controls d-flex justify-content-center mt-0 mb-3 no-print">
	<div class="d-inline-flex p-2 rounded border shadow-sm flex-wrap justify-content-center">
		<button class="btn btn-sm btn-outline-primary mx-2 px-3" onclick="jump('employees_list.php')" title="List View">
			<i class="fas fa-list mr-1"></i><span class="thid"> List View</span>
		</button>
		<button class="btn btn-sm btn-outline-primary mx-2 px-3" onclick="jump('employees_grid.php')" title="Grid View">
			<i class="fas fa-th mr-1"></i><span class="thid"> Grid View</span>
		</button>
	</div>
</div>

<div class="grid py-3 no-print"></div>

<div class="container d-flex justify-content-center mb-5">
	<div class="col-lg-10 col-md-12 bg-white p-4 border rounded shadow-sm">
		<?php		
			$rec=1;
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
				
			$mem="";
			if($_GET["employees"]!="") {
				$mem=" and idn='".$_GET["employees"]."' ";
			}
																							
			$ex=$link->query("select * from employees where idn=idn $mem order by idn limit $from,$to ");

			while($rs=mysqli_fetch_array($ex)){
				$app_time = !empty($rs['date_appointed']) && $rs['date_appointed'] !== '0000-00-00' ? strtotime($rs['date_appointed']) : time();
				$rs['app_day'] = date('d', $app_time);
				$rs['app_month'] = date('m', $app_time);
				$rs['app_year'] = date('Y', $app_time);
				$psn = "";
				$exp=$link->query("select * from positions where pscode='".$rs["position"]."'");
				while($row=mysqli_fetch_array($exp)){
					$psn = "".$row["psname"]."";
				}
				
				$ofn = "";
				$exo=$link->query("select * from offices where ofcode='".$rs["department"]."'");
				while($row=mysqli_fetch_array($exo)){		
					$ofn = "".$row["ofname"]."";
				}

				$purok_str = "";
				$add=$rs["purok"];
				if(in_array(strtolower($add[0] ?? ''), array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
					$purok_str = $rs["purok"] . ", " . $rs["barangay"] . ", " . $rs["city_mun"] . ", " . $rs["province"];
				}else{
					$purok_str = $rs["barangay"] . ", " . $rs["city_mun"] . ", " . $rs["province"];
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
				<!-- Header Row -->
				<div class="row align-items-center mb-4">
					<div class="col-md-2 text-center mb-3 mb-md-0">
						<img src="images/seal.png" alt="Logo" class="img-fluid" style="70%">
					</div>
					<div class="col-md-8 text-center bg-light py-2 rounded border border-primary">
						<h6 class="text-muted mb-0">Republic of the Philippines</h6>
						<h4 class="font-weight-bold text-primary mb-1" style="letter-spacing: 1px;">CITIZEN-CENTRIC DIGITAL PLATFORM</h4>
						<h6 class="text-muted mb-0">Tabina, Zamboanga del Sur</h6>
					</div>
					<div class="col-md-2 text-center mt-3 mt-md-0 thid">
						<?php if(file_exists("images/employees/qrcodes/{$rs[0]}.png")): ?>
							<img src="images/employees/qrcodes/<?php echo $rs[0]; ?>.png" style="width:80%;" class="img-fluid border rounded p-1" alt="QR Code"/>
						<?php endif; ?>
					</div>
				</div>

				<!-- Overview Row -->
				<div class="row mb-4 align-items-center">
					<!-- Photo -->
					<div class="col-md-3 text-center mb-3 mb-md-0">
						<?php
							$photo_path = 'images/blank.jpg';
							if(file_exists("images/employees/photos/{$rs[0]}.jpg")){
								$photo_path = "images/employees/photos/{$rs[0]}.jpg?" . date("h:i:s");
							}
						?>
						<img src="<?php echo $photo_path; ?>" class="img-fluid rounded img-thumbnail shadow-sm" style="max-height: 180px; object-fit: cover; cursor: pointer;" onclick="jump('employees_idcard_1.php?employees=<?php echo $rs[0]; ?>')" alt="Employee Photo"/>
					</div>
					<!-- Details -->
					<div class="col-md-6">
						<div class="table-responsive">
							<table class="table table-sm table-borderless mb-0">
								<tbody>
									<tr>
										<td style="width: 20%;" class="font-weight-bold text-muted">Full Name:</td>
										<td class="font-weight-bold text-uppercase text-dark" style="font-size: 1.1rem;">
											<?php 
												echo str_replace($val, $rep, $rs["name_1st"]);
												if($rs["name_mid"] == ""){
													echo " ";
												}else{
													echo " " . substr($rs["name_mid"],0,1) . ". ";
												}
												echo str_replace($val, $rep, $rs["name_fam"]);
											?>
										</td>
									</tr>
									<tr>
										<td class="font-weight-bold text-muted">Position:</td>
										<td class="font-weight-bold text-primary"><?php echo $psn; ?></td>
									</tr>
									<tr>
										<td class="font-weight-bold text-muted">Address:</td>
										<td><?php echo $purok_str; ?></td>
									</tr>
									<tr>
										<td class="font-weight-bold text-muted">Contact:</td>
										<td>
											<?php 
												$phone = $rs["contact"];
												if(in_array(strtolower($phone[0] ?? ''), array('0','1','2','3','4','5','6','7','8','9'))){
													echo "<span class='mr-4'><i class='fas fa-phone mr-1 text-success'></i>" . $phone . "</span>";
												}
												$mail = $rs["emailadd"];
												if(in_array(strtolower($mail[0] ?? ''), array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'))){
													echo "<span><i class='fas fa-envelope mr-1 text-info'></i>" . $mail . "</span>";
												}
											?>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-md-3 justify-content-center pl-5 pr-5 py-2">
						<div class="row p-2" onclick="jump('employees_idcard_1.php?employees=<?php echo $rs[0]; ?>')">
							<button class="btn btn-sm btn-info rounded-pill sm-1" style="width:100%">
								<i class="fa fa-drivers-license"></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ID Card
							</button>
						</div>
						<div class="row p-2" onclick="jump('employees_idtag_1.php?employees=<?php echo $rs[0]; ?>')">
							<button class="btn btn-sm btn-success rounded-pill sm-1" style="width:100%">
								<i class="fa fa-address-card"></i>&nbsp;&nbsp;Office Tag
							</button>
						</div>
						<a rel="facebox" href="employees_edit_form.php?employees=<?php echo $rs[0];?>" style="text-decoration:none">
						<div class="row p-2">
							<button class="btn btn-sm btn-warning rounded-pill sm-1" style="width:100%">
								<i class="fa fa-pen text-left"></i> Edit Profile
							</button>
						</div>
						</a>
					</div>					
				</div>

				<!-- Details Card -->
				<div class="card bg-light border-secondary">
					<div class="card-body p-4">
						<div class="row">
							<!-- Column 1 -->
							<div class="col-md-4 mb-3 mb-md-0">
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">ID No:</small>
									<span class="text-dark font-weight-bold"><?php printf("%s-%04d-%s-%s", $rs["department"], $rs[0], $rs["position"], $rs["app_year"]); ?></span>
								</div>
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">Birthdate:</small>
									<span class="text-dark">
									<?php 
										$months  = [
											"1"  => "January",
											"2"  => "February",
											"3"  => "March",
											"4"  => "April",
											"5"  => "May",
											"6"  => "June",
											"7"  => "July",
											"8"  => "August",
											"9"  => "September",
											"10" => "October",
											"11" => "November",
											"12" => "December"
										];

										if (!empty($rs["date_birth"]) && $rs["date_birth"] !== "0000-00-00") {
											echo date("F d, Y", strtotime($rs["date_birth"]));
										} else {
											echo "N/A";
										}
									?>
									</span>
								</div>
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">GSIS/SSS Number:</small>
									<span class="text-dark"><?php echo !empty($rs["gsis"]) ? $rs["gsis"] : 'N/A'; ?></span>
								</div>
								<div>
									<small class="text-muted d-block font-weight-bold">Contact Person:</small>
									<span class="text-dark"><?php echo $rs["contactperson"]; ?></span>
								</div>
							</div>
							
							<!-- Column 2 -->
							<div class="col-md-4 mb-3 mb-md-0">
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">Agency:</small>
									<span class="text-dark"><?php echo $rs["agency"]; ?></span>
								</div>
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">Age:</small>
									<span class="text-dark"><?php echo $age; ?> Years Old</span>
								</div>
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">T.I.N. & PhilHealth:</small>
									<span class="text-dark"><?php echo $rs["tin"] . " &bull; " . $rs["philhealth"]; ?></span>
								</div>
								<div>
									<small class="text-muted d-block font-weight-bold">Relationship:</small>
									<span class="text-dark"><?php echo $rs["relationship"]; ?></span>
								</div>
							</div>

							<!-- Column 3 -->
							<div class="col-md-4">
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">Office:</small>
									<span class="text-dark"><?php echo $ofn; ?></span>
								</div>
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">Gender:</small>
									<span class="text-dark"><?php echo $rs["sex"]; ?></span>
								</div>
								<div class="mb-3 border-bottom pb-1">
									<small class="text-muted d-block font-weight-bold">PagIBIG Number:</small>
									<span class="text-dark"><?php echo $rs["pagibig"]; ?></span>
								</div>
								<div>
									<small class="text-muted d-block font-weight-bold">Emergency Contact No:</small>
									<span class="text-danger font-weight-bold"><?php echo $rs["emergencyno"]; ?></span>
								</div>
							</div>
						</div>
					</div>
				</div>
		<?php
			}
		?>
	</div>
</div>
<?php include("footer1.php");?>
</body>
</html>
