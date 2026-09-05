<?php	
	require("connect.php");
	require("header.php");
	require("menu.php");
?>

<script> setActive("household"); </script>

<link href="style/pds.css" rel="stylesheet" type="text/css"/>

<style>@media screen and (max-width:720px){.thid{display:none;}}</style>	

<!-- Controls Toolbar -->
<div class="t_controls d-flex justify-content-center mt-0 mb-3 no-print">
	<div class="d-inline-flex p-2 rounded border shadow-sm flex-wrap justify-content-center">
		<button class="btn btn-sm btn-outline-primary mx-2 px-3" onclick="jump('households_list.php')" title="List View">
			<i class="fas fa-list mr-1"></i><span class="thid"> List View</span>
		</button>
		<button class="btn btn-sm btn-outline-primary mx-2 px-3" onclick="jump('households_grid.php')" title="Card View">
			<i class="fas fa-th mr-1"></i></i><span class="thid"> Card View</span>
		</button>
		<?php if(isset($_GET["households"])){ ?>
		<a rel="facebox" href="households_add_mem.php?households=<?php echo htmlspecialchars($_GET['households']); ?>" class="btn btn-success mx-2 px-3" title="Add Members">
			<i class="fas fa-user-plus mr-1"></i></i><span class="thid"> Add Member</span>
		</a>
		<?php } ?>
		<button class="btn btn-sm btn-primary mx-2 px-3" onclick="printF()" title="Print">
			<i class="fas fa-print mr-1"></i></i><span class="thid"> Print</span>
		</button>
	</div>
</div>

<div class="list"></div>

<div class="container grid mt-2 mb-4">

	<div class="row">
		<div class="col-lg-12">

		<?php		
			$rec=1;
			$p=isset($_GET['page']) ? (int)$_GET['page'] : 1;
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
				
			$hhh="";
			if(isset($_GET["households"]) && $_GET["households"]!="")
				$hhh=" and hhid='".$link->real_escape_string($_GET["households"])."' ";
																								
			$ex=$link->query("select * from households where hhid=hhid $hhh order by hhid limit $from,$to ");
			
			while($rs=mysqli_fetch_array($ex)){
																										
				$ex1=$link->query("select * from households h where h.hhid='$rs[0]' and h.hhid=h.hhid ");
				$ii=1;

				while($rs=mysqli_fetch_array($ex1)){

					$birthDateStr = $rs["hh_birth"];
					$age = 0;
					if (!empty($birthDateStr)) {
						try {
							$birth = new DateTime($birthDateStr);
							$today = new DateTime();
							$age = $today->diff($birth)->y;
						} catch (Exception $e) {
							$age = 0;
						}
					}
						
					// QR Code path
					$qr_path = "images/households/qrcodes/" . $rs[0] . ".png";
					if (!file_exists($qr_path)) {
						// Generate QR Code if missing
						include_once('qrlib/qrlib.php');
						$qr_dir = "images/households/qrcodes/";
						if (!file_exists($qr_dir)) {
							mkdir($qr_dir, 0777, true);
						}
						$qr_content = "Name: " . $rs["fullname"] . "\nID: " . sprintf("%04d", $rs[0]) . "\nHousehold Profile";
						QRcode::png($qr_content, $qr_path, QR_ECLEVEL_L, 3);
					}
					$qr_path_display = $qr_path . "?" . date("h:i:s");

					echo "
					<!-- HOUSEHOLD INFORMATION CARD -->
					<div class='card mb-4 shadow-sm border-secondary'>
						<div class='card-header bg-secondary text-white d-flex justify-content-between align-items-center'>
							<h5 class='mb-0'><i class='fas fa-home mr-2'></i>HH PROFILE</h5>
							<span class='badge badge-light p-2 font-weight-bold' style='font-size: 14px;'>
								HH ID: " . sprintf("%04d", $rs["hhid"]) . "
							</span>
						</div>
						<div class='card-body'>
							<div class='row'>
								<!-- Profile Image -->
								<div class='col-lg-2 text-center mb-3 mb-lg-0'>
									<img class='img-fluid img-thumbnail rounded shadow-sm' style='max-height: 150px; object-fit: cover;' ";
									if(file_exists("images/households/$rs[0].jpg")){
										echo "src='images/households/$rs[0].jpg?".date("h:i:s")."' />";
									}else{
										echo "src='images/user.png' />";
									}
									echo "
										<div style='margin-top:5px'>
											<img src='{$qr_path_display}' style='text-align:center;height: 85px; border-radius: 4px; background: #fff; padding: 2px;' alt='QR Code'/>
										</div>

								</div>
								
								<!-- Details Table -->
								<div class='col-lg-7'>
									<div class='table-responsive'>
										<table class='table table-sm table-borderless mb-0'>
											<tbody>
												<tr>
													<td style='width: 30%;' class='font-weight-bold text-muted'>Household Head:</td>
													<td class='text-uppercase font-weight-bold text-primary'>" . $rs["hh_name"] . "</td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Sex / Age:</td>
													<td>" . $rs["hh_sex"] . " / <b>$age</b> yrs old</td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Date of Birth:</td>
													<td>" . $rs["hh_birth"] . "</td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Occupation:</td>
													<td>" . $rs["hh_occupation"] . "</td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Religion / Ethnicity:</td>
													<td>" . $rs["hh_religion"] . " / " . $rs["hh_ethnicity"] . "</td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Contact No:</td>
													<td>" . $rs["hh_contact"] . "</td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Members Count:</td>
													<td><span class='badge badge-info'>" . $rs["hh_members"] . " members</span></td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Address:</td>
													<td>" . $rs["purok"] . ", " . $rs["barangay"] . ", " . $rs["city_mun"] . ", " . $rs["province"] . "</td>
												</tr>
												<tr>
													<td class='font-weight-bold text-muted'>Remarks:</td>
													<td><span class='text-warning font-italic'>" . $rs["remarks"] . "</span></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								
								<!-- Verification Panel -->
								<div class='col-lg-3'>
									<div class='card bg-light border-light shadow-xs'>
										<div class='card-body p-3 font-size-13'>
											<h6 class='card-subtitle mb-3 text-muted font-weight-bold'>
												<i class='fas fa-check-circle mr-1 text-success'></i>Verification Details
											</h6>
											<div class='mb-2'>
												<small class='text-muted d-block'>Enumerator</small>
												<span class='font-weight-bold text-dark'>" . $rs["enumerator"] . "</span>
											</div>
											<div class='mb-2'>
												<small class='text-muted d-block'>Verified By</small>
												<span class='font-weight-bold text-dark'>" . $rs["verifier"] . "</span>
											</div>
											<div class='mb-0'>
												<small class='text-muted d-block'>Date Verified</small>
												<span class='font-weight-bold text-dark'>" . $rs["date_verified"] . "</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- CHARACTERISTICS CARD -->
					<div class='card mb-4 shadow-sm border-info'>
						<div class='card-header bg-info text-white'>
							<h5 class='mb-0'><i class='fas fa-users mr-2'></i><x class='thid'>CHARACTERISTICS OF</x> HOUSEHOLD MEMBERS</h5>
						</div>
						<div class='card-body p-0'>
							<div class='table-responsive'>
								<table class='table table-hover table-striped table-bordered mb-0 text-center'>
									<thead class='bg-light'>
										<tr>
											<th width='3%'>No</th>					
											<th width='20%'>Name <x class='thid'>of Household Members</x></th>
											<th width='4%'>Sex</th>
											<th width='8%'>Birth <x class='thid'>Date</x></th>
											<th width='4%'>Age</th>
											<th width='10%'>Education</th>
											<th width='5%'>Enrolled</th>
											<th width='8%'>Main Income</th>
											<th width='8%'>Secondary</th>
											<th width='9%'>Est. Income</th>
											<th width='8%'>Benefits</th>
											<th width='6%' class='hid'>Remarks</th>
											<th width='6%' class='hid'>Action</th>
										</tr>
									</thead>
									<tbody>";
									
									$ex3=$link->query("select * from hh_members where hh_members.hm_belong='".$rs[0]."' and hh_members.hmid=hh_members.hmid");
									
									$i=1;
									
									while($rs3=mysqli_fetch_array($ex3)){
															
										$birthDate1 = $rs3["hm_birth"];
										$age1 = 0;
										if (!empty($birthDate1)) {
											try {
												$birth1 = new DateTime($birthDate1);
												$today1 = new DateTime();
												$age1 = $today1->diff($birth1)->y;
											} catch (Exception $e) {
												$age1 = 0;
											}
										}

										$income1=$rs3["hm_main_income"];
										$income1=ucwords(strtolower((string)$income1));

										$income2=$rs3["hm_second_income"];
										$income2=ucwords(strtolower((string)$income2));
										
										$hhm=$rs3[0];
										
										echo "					
										<tr id='tr_$hhm'>
											<td class='align-middle'>$i.</td>
											<td class='align-middle text-left text-uppercase font-weight-bold'>" . $rs3["hm_name"] . "</td>
											<td class='align-middle'>" . ($rs3["hm_sex"] == "Male" ? "M" : "F") . "</td>
											<td class='align-middle'>" . $rs3["hm_birth"] . "</td>
											<td class='align-middle'>$age1</td>
											<td class='align-middle'>" . $rs3["hm_education"] . "</td>
											<td class='align-middle'>" . $rs3["hm_enrolled"] . "</td>
											<td class='align-middle'>" . $income1 . "</td>
											<td class='align-middle'>" . $income2 . "</td>
											<td class='align-middle'>" . $rs3["hm_estimated_income"] . "</td>
											<td class='align-middle'>" . $rs3["hm_social"] . "</td>
											<td class='align-middle hid'>" . $rs3["hm_remarks"] . "</td>
											<td class='align-middle hid'>
												<div class='btn-group' role='group'>
													<a rel='facebox' href='households_mem_edit_form.php?hh_members=$hhm' class='btn btn-sm btn-outline-info' title='Edit'>
														<i class='fas fa-edit'></i>
													</a>
													<button onclick=\"deleteRecord('hh_members', '$hhm', 'tr_$hhm')\" class='btn btn-sm btn-outline-danger' title='Delete'>
														<i class='fas fa-trash-alt'></i>
													</button>
												</div>
											</td>
										</tr>";
										
										$i++;
									}
						
									echo "
									</tbody>
								</table>
							</div>
						</div>
					</div>
				
					<!-- SOCIAL INDICATORS CARD -->			
					<div class='card mb-4 shadow-sm border-success'>
						<div class='card-header bg-success text-white'>
							<h5 class='mb-0'><i class='fas fa-heartbeat mr-2'></i>SOCIAL INDICATORS</h5>
						</div>
						<div class='card-body'>
							<div class='row'>
								<!-- Health & Safety -->
								<div class='col-md-6 mb-3 mb-md-0 border-right'>
									<h6 class='font-weight-bold text-success border-bottom pb-2 mb-3'>
										Health & Well-being <small>(Last 12 Months)</small>
									</h6>
									<ul class='list-group list-group-flush'>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-skull text-muted mr-2'></i>Death in Household</span>";
											if ($rs["mem_death"]=="Yes"){
												echo "<span class='badge badge-danger p-2'>Yes (Cause: " . $rs["death_cause_if_yes"] . ")</span>";
											}else{
												echo "<span class='badge badge-secondary p-2'>No</span>";
											}
											echo "
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-hospital text-muted mr-2'></i>Member Hospitalized</span>";
											if ($rs["mem_hospitalize"]=="Yes"){
												echo "<span class='badge badge-warning p-2 text-dark'>Yes (Illness: " . $rs["illness_if_yes"] . ")</span>";
											}else{
												echo "<span class='badge badge-secondary p-2'>No</span>";
											}
											echo "
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-exclamation-triangle text-muted mr-2'></i>Crime Victim</span>";
											if ($rs["mem_crime_victim"]=="Yes"){
												echo "<span class='badge badge-danger p-2'>Yes (Crime: " . $rs["what_crime_if_yes"] . ")</span>";
											}else{
												echo "<span class='badge badge-secondary p-2'>No</span>";
											}
											echo "
										</li>
									</ul>
								</div>
								
								<!-- Waste Management -->
								<div class='col-md-6'>
									<h6 class='font-weight-bold text-success border-bottom pb-2 mb-3'>
										Solid Waste Management <x class='thid'>Compliance</x>
									</h6>
									<div class='table-responsive'>
										<table class='table table-sm table-bordered text-center mb-0'>
											<thead class='bg-light'>
												<tr>
													<th>Reuse</th>
													<th>Reduce</th>
													<th>Recycling</th>
													<th>Composting</th>
													<th>Waste to MRF</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><span class='badge badge-" . ($rs["swm_reuse"] == "Yes" ? "success" : "secondary") . " p-1'>" . $rs["swm_reuse"] . "</span></td>
													<td><span class='badge badge-" . ($rs["swm_reduce"] == "Yes" ? "success" : "secondary") . " p-1'>" . $rs["swm_reduce"] . "</span></td>
													<td><span class='badge badge-" . ($rs["swm_recycling"] == "Yes" ? "success" : "secondary") . " p-1'>" . $rs["swm_recycling"] . "</span></td>
													<td><span class='badge badge-" . ($rs["swm_composting"] == "Yes" ? "success" : "secondary") . " p-1'>" . $rs["swm_composting"] . "</span></td>
													<td><span class='badge badge-" . ($rs["swm_waste_to_mrf"] == "Yes" ? "success" : "secondary") . " p-1'>" . $rs["swm_waste_to_mrf"] . "</span></td>
												</tr>
											</tbody>
										</table>
									</div>";
									if (!empty($rs["swm_remarks"])) {
										echo "
										<div class='mt-3 small text-muted'>
											<strong>Remarks:</strong> " . $rs["swm_remarks"] . "
										</div>";
									}
									echo "
								</div>
							</div>
						</div>
					</div>	
					
					<!-- SETTLEMENT & ACCESSIBILITY CARD -->
					<div class='card mb-4 shadow-sm border-warning'>
						<div class='card-header bg-warning text-dark'>
							<h5 class='mb-0'><i class='fas fa-map-marked-alt mr-2'></i>SETTLEMENT <x class='thid'>& ACCESSIBILITY</x></h5>
						</div>
						<div class='card-body'>
							<div class='row'>
								<!-- Settlement & Utilities -->
								<div class='col-md-6 mb-3 mb-md-0 border-right'>
									<h6 class='font-weight-bold text-dark border-bottom pb-2 mb-3'>Settlement & Utilities</h6>
									<ul class='list-group list-group-flush'>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-toilet text-muted mr-2'></i>Toilet Type</span>
											<span class='font-weight-bold text-dark'>" . $rs["toilet_have"] . "</span>
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-tint text-muted mr-2'></i>Potable Water Access</span>
											<span class='font-weight-bold text-dark'>" . $rs["water_access"] . "</span>
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-campground text-muted mr-2'></i>Settlement Type</span>
											<span class='font-weight-bold text-dark'>" . $rs["settlement"] . "</span>
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-building text-muted mr-2'></i>House Type</span>
											<span class='font-weight-bold text-dark'>" . $rs["house_type"] . "</span>
										</li>
									</ul>
								</div>
								
								<!-- Accessibility -->
								<div class='col-md-6'>
									<h6 class='font-weight-bold text-dark border-bottom pb-2 mb-3'>Distance to Essential Services</h6>
									<ul class='list-group list-group-flush'>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-first-aid text-muted mr-2'></i>Medical Facility</span>
											<span class='font-weight-bold text-dark'>";
											if ($rs["access_hospital"]=="Yes"){
												echo "Yes - " . $rs["access_hospital_distance"] . " Km";
											}else{
												echo "No";
											}
											echo "</span>
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-graduation-cap text-muted mr-2'></i>School</span>
											<span class='font-weight-bold text-dark'>";
											if ($rs["access_school"]=="Yes"){
												echo "Yes - " . $rs["access_school_distance"] . " Km";
											}else{
												echo "No";
											}
											echo "</span>
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-church text-muted mr-2'></i>Church</span>
											<span class='font-weight-bold text-dark'>";
											if ($rs["access_church"]=="Yes"){
												echo "Yes - " . $rs["access_church_distance"] . " Km";
											}else{
												echo "No";
											}
											echo "</span>
										</li>
										<li class='list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent'>
											<span><i class='fas fa-tree text-muted mr-2'></i>Recreation Facility</span>
											<span class='font-weight-bold text-dark'>";
											if ($rs["access_recreation"]=="Yes"){
												echo "Yes - " . $rs["access_recreation_distance"] . " Km";
											}else{
												echo "No";
											}
											echo "</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>";			
					$ii++;
				}
			}
		?>
		</div>		
	</div>
</div>
	
<script>
	function printF(){
		$(".t_controls").css("display","none");	
		$(".list").css("display","none");	
		
		window.print();
		$(".t_controls").css("display","block");
		$(".list").css("display","block");	
	}
</script>

<?php require("crud_functionjs2.php");?>

</body>

</html>
