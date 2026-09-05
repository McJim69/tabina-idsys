<?php 
	require("connect.php"); 
	require("header.php");
	require("menu.php");
?>	

<script> setActive("household"); </script>
<script> setActive("hhsurvey"); </script>

<link href="style/form-card.css" rel="stylesheet" type="text/css"/>

<body style="background:#bbb;">

<?php 
	$squery = "SELECT MAX(hhid) FROM households";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>				
<br>
<div class="container my-5">
	<form action="households_add_proc.php" method="POST">
		<input type='hidden' name='hhid' value='<?php echo $lastID;?>' />
		<input name="province" type="hidden" value="Zamboanga del Sur" />	
		
		<div class="form-card card">
			<!-- Header -->
			<div class="form-header d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-left">
				<div class="mb-3 mb-md-0">
					<span class="badge badge-warning font-weight-bold px-3 py-2" style="font-size: 14px;">System ID: <?php echo $lastID; ?></span>
				</div>
				<h3 class="font-weight-bold m-0 text-uppercase flex-grow-1 text-center">Household Survey Capture <x class="thid">Form</x></h3>
				<div style="position:absolute;top:10px;right:10px">
					<a href="households_grid.php" class="btn btn-outline-light rounded-pill"><i class="fa fa-times"></i>
						<!--<img src="images/close.png"/>-->
					</a>
				</div>
			</div>

			<!-- Card Body -->
			<div class="card-body p-4">
				
				<!-- Section 1: Personal Info -->
				<div class="form-section-title">Personal Information</div>
				
				<div class="row">
					<!-- Municipality -->
					<div class="col-md-3 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Municipality</label>
						<select class="form-control" id="city_mun" name="city_mun">
						  <option value="">Municipal</option>
						  <?php
						  $res = $link->query("SELECT DISTINCT city_mun FROM districts ORDER BY city_mun");
						  while ($row = mysqli_fetch_array($res)) {
							echo "<option value='{$row['city_mun']}'>{$row['city_mun']}</option>";
						  }
						  ?>
						</select>
					</div>

					<!-- Barangay -->
					<div class="col-md-3 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Barangay</label>
						<select class="form-control" id="barangay" name="barangay">
						  <option value="">Barangay</option>
						</select>
					</div>

					<!-- Purok -->
					<div class="col-md-3 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Purok</label>
						<select class="form-control" id="purok" name="purok">
						  <option value="">Purok</option>
						</select>
					</div>

					<!-- Phone Number -->
					<div class="col-md-3 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Phone Number</label>
						<input class="form-control" name="hh_contact" type="text" placeholder="Phone Number" />
					</div>
				</div>

				<div class="row">
					<!-- Name of HH Head -->
					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Name of Household Head</label>
						<input class="form-control" name="hh_name" type="text" placeholder="Name of Household Head" required />
					</div>

					<!-- Sex -->
					<div class="col-md-2 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Sex</label>
						<select class="form-control" name="hh_sex" required>
							<option value="" selected="1">Sex</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>					
						</select>
					</div>

					<!-- Birth Date -->
					<div class="col-md-3 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Birth Date</label>
						<input class="form-control" name="hh_birth" type="date" required />
					</div>

					<!-- Church Denomination -->
					<div class="col-md-3 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Church Denomination</label>
						<select class="form-control" name="hh_religion" required>
							<option value="" selected="1">Denomination</option>
							<option value="UCCP">UCCP</option>	
							<option value="Islam">Islam</option>					
							<option value="Aglipay">Aglipay</option>	
							<option value="Kristohan">Kristohan</option>
							<option value="KAMACOP">KAMACOP</option>	
							<option value="12 Ka Panon">12 Ka Panon</option>
							<option value="Roman Catholic">Roman Catholic</option>
							<option value="Iglesia Ni Kristo">Iglesia Ni Kristo</option>	
							<option value="Born Again Christian">Born Again Christian</option>					
							<option value="Jehova's Witnessess">Jehova's Witnessess</option>
							<option value="Seventh-Day Adventist">Seventh-Day Adventist</option>											
							<option value="Other">Other Church Denomination</option>											
						</select>
					</div>
				</div>

				<div class="row">
					<!-- Occupation -->
					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Occupation</label>
						<input class="form-control" name="hh_occupation" type="text" required />
					</div>

					<!-- Ethnicity -->
					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Ethnicity</label>
						<select class="form-control" name="hh_ethnicity" required>
							<option value="" selected="1">Ethnicity</option>
							<option value="Muslim">Muslim</option>					
							<option value="Subanen">Subanen</option>					
							<option value="Cebuano">Cebuano</option>
							<option value="Boholano">Boholano</option>					
							<option value="Siquijornon">Siquijornon</option>					
						</select>
					</div>

					<!-- Remarks -->
					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Remarks</label>
						<input class="form-control" name="remarks" type="text" placeholder="Remarks" />
					</div>
				</div>

				<!-- Section 2: Characteristics instructions -->
				<div class="form-section-title">Characteristics</div>
				<div class="alert alert-info shadow-sm" role="alert">
					<h5 class="alert-heading font-weight-bold mb-2">Household Members</h5>
					<p class="mb-2 text-danger font-weight-bold">Note: <small>You can ADD household members later after submitting Household Head data.</small></p>
					<hr class="my-2">
					<p class="mb-1 font-weight-bold text-dark">How to add HH Members:</p>
					<ol class="pl-3 mb-0 txt-sm text-muted" style="font-size: 13px; line-height: 1.5;">
						<li>Click MENU<i class="fa fa-arrow-right"></i>HOUSEHOLDS<i class="fa fa-arrow-right"></i>HOUSEHOLD GRID VIEW</li>
						<li>Search the name of the household head, hover on card and click the "Add Members" button.</li>
					</ol>
				</div>

				<!-- Section 3: Social Indicators -->
				<div class="form-section-title">Social Indicators</div>
				
				<div class="row mb-4">
					<!-- Toilet Type -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<label class="font-weight-bold text-muted small text-uppercase">Toilet Type</label>
							<select class="form-control mt-1" name="toilet_have" required>
								<option value="" selected="1">Toilet</option>
								<option value="None">None</option>
								<option value="Antipolo">Antipolo</option>
								<option value="Water Sealed">Water Sealed</option>					
							</select>
						</div>
					</div>

					<!-- Access to Potable -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<label class="font-weight-bold text-muted small text-uppercase">Access to Potable Water</label>
							<select class="form-control mt-1" name="water_access" required>
								<option value="" selected="1">Water</option>
								<option value="Level I">Level I</option>
								<option value="Level II">Level II</option>
								<option value="Level III">Level III</option>					
							</select>
						</div>
					</div>

					<!-- Land Settlement -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<label class="font-weight-bold text-muted small text-uppercase">Land Settlement</label>
							<select class="form-control mt-1" name="settlement" required> 
								<option value="" selected="1">Settlement</option>
								<option value="Owned">Owned</option>
								<option value="Rented">Rented</option>					
								<option value="Informal">Informal</option>
								<option value="Tenanted">Tenanted</option>
								<option value="Government">Government</option>					
							</select>
						</div>
					</div>

					<!-- House Type -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<label class="font-weight-bold text-muted small text-uppercase">House Type</label>
							<select class="form-control mt-1" name="house_type" required>
								<option value="" selected="1">House Type</option>
								<option value="Wooden">Wooden</option>
								<option value="Nipa Hut">Nipa Hut</option>				
								<option value="Concrete">Concrete</option>
								<option value="Semi-Concrete">Semi-Concrete</option>					
							</select>
						</div>
					</div>
				</div>

				<div class="font-weight-bold text-dark mb-3 text-uppercase small" style="letter-spacing: 0.5px;">Solid Waste Management</div>
				<div class="row mb-4">
					<!-- Reuse -->
					<div class="col-md-2 mb-3">
						<div class="swm-card text-center">
							<div class="font-weight-bold mb-2">Reuse</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="reuse_yes" name="swm_reuse" class="custom-control-input" value="Yes" required>
								<label class="custom-control-label" for="reuse_yes">Yes</label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="reuse_no" name="swm_reuse" class="custom-control-input" value="No" required>
								<label class="custom-control-label" for="reuse_no">No</label>
							</div>
						</div>
					</div>

					<!-- Reduce -->
					<div class="col-md-2 mb-3">
						<div class="swm-card text-center">
							<div class="font-weight-bold mb-2">Reduce</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="reduce_yes" name="swm_reduce" class="custom-control-input" value="Yes" required>
								<label class="custom-control-label" for="reduce_yes">Yes</label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="reduce_no" name="swm_reduce" class="custom-control-input" value="No" required>
								<label class="custom-control-label" for="reduce_no">No</label>
							</div>
						</div>
					</div>

					<!-- Recycling -->
					<div class="col-md-2 mb-3">
						<div class="swm-card text-center">
							<div class="font-weight-bold mb-2">Recycling</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="recycle_yes" name="swm_recycling" class="custom-control-input" value="Yes" required>
								<label class="custom-control-label" for="recycle_yes">Yes</label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="recycle_no" name="swm_recycling" class="custom-control-input" value="No" required>
								<label class="custom-control-label" for="recycle_no">No</label>
							</div>
						</div>
					</div>

					<!-- Composting -->
					<div class="col-md-2 mb-3">
						<div class="swm-card text-center">
							<div class="font-weight-bold mb-2">Composting</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="compost_yes" name="swm_composting" class="custom-control-input" value="Yes" required>
								<label class="custom-control-label" for="compost_yes">Yes</label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="compost_no" name="swm_composting" class="custom-control-input" value="No" required>
								<label class="custom-control-label" for="compost_no">No</label>
							</div>
						</div>
					</div>

					<!-- Waste to MRF -->
					<div class="col-md-2 mb-3">
						<div class="swm-card text-center">
							<div class="font-weight-bold mb-2">Waste to MRF</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="mrf_yes" name="swm_waste_to_mrf" class="custom-control-input" value="Yes" required>
								<label class="custom-control-label" for="mrf_yes">Yes</label>
							</div>
							<div class="custom-control custom-radio custom-control-inline">
								<input type="radio" id="mrf_no" name="swm_waste_to_mrf" class="custom-control-input" value="No" required>
								<label class="custom-control-label" for="mrf_no">No</label>
							</div>
						</div>
					</div>

					<!-- SWM Remarks -->
					<div class="col-md-2 mb-3">
						<div class="swm-card d-flex flex-column justify-content-center">
							<label class="font-weight-bold small text-muted text-uppercase mb-1">SWM Remarks</label>
							<input class="form-control form-control-sm" name="swm_remarks" type="text" placeholder="Remarks"/>
						</div>
					</div>
				</div>

				<div class="row mb-4">
					<!-- Family Death -->
					<div class="col-lg-4 mb-3">
						<div class="info-box height-100">
							<div class="font-weight-bold mb-2" style="font-size: 13px;">Death in Family (last 12 months)</div>
							<div class="d-flex align-items-center mb-2">
								<div class="custom-control custom-radio custom-control-inline mr-3">
									<input type="radio" id="death_yes" name="mem_death" class="custom-control-input" value="Yes" required>
									<label class="custom-control-label" for="death_yes">Yes</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="death_no" name="mem_death" class="custom-control-input" value="No" required>
									<label class="custom-control-label" for="death_no">No</label>
								</div>
							</div>
							<input class="form-control form-control-sm" name="death_cause_if_yes" type="text" placeholder="If yes, cause of death" />
						</div>
					</div>

					<!-- Hospitalization -->
					<div class="col-lg-4 mb-3">
						<div class="info-box height-100">
							<div class="font-weight-bold mb-2" style="font-size: 13px;">Hospitalization (last 12 months)</div>
							<div class="d-flex align-items-center mb-2">
								<div class="custom-control custom-radio custom-control-inline mr-3">
									<input type="radio" id="hosp_yes" name="mem_hospitalize" class="custom-control-input" value="Yes" required>
									<label class="custom-control-label" for="hosp_yes">Yes</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="hosp_no" name="mem_hospitalize" class="custom-control-input" value="No" required>
									<label class="custom-control-label" for="hosp_no">No</label>
								</div>
							</div>
							<input class="form-control form-control-sm" name="illness_if_yes" type="text" placeholder="If yes, kind of illness" />
						</div>
					</div>

					<!-- Crime Victim -->
					<div class="col-lg-4 mb-3">
						<div class="info-box height-100">
							<div class="font-weight-bold mb-2" style="font-size: 13px;">Crime Victim (last 12 months)</div>
							<div class="d-flex align-items-center mb-2">
								<div class="custom-control custom-radio custom-control-inline mr-3">
									<input type="radio" id="crime_yes" name="mem_crime_victim" class="custom-control-input" value="Yes" required>
									<label class="custom-control-label" for="crime_yes">Yes</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="crime_no" name="mem_crime_victim" class="custom-control-input" value="No" required>
									<label class="custom-control-label" for="crime_no">No</label>
								</div>
							</div>
							<input class="form-control form-control-sm" name="what_crime_if_yes" type="text" placeholder="If yes, what crime" />
						</div>
					</div>
				</div>

				<!-- Section 4: Accessibility -->
				<div class="form-section-title">Accessibility (Facility & Distance)</div>
				
				<div class="row mb-4">
					<!-- Hospital facility -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<div class="font-weight-bold mb-2" style="font-size: 13px;">Hospital / Medical Facility</div>
							<div class="d-flex align-items-center mb-2">
								<div class="custom-control custom-radio custom-control-inline mr-2">
									<input type="radio" id="acc_hosp_yes" name="access_hospital" class="custom-control-input" value="Yes" required>
									<label class="custom-control-label" for="acc_hosp_yes">Yes</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="acc_hosp_no" name="access_hospital" class="custom-control-input" value="No" required>
									<label class="custom-control-label" for="acc_hosp_no">No</label>
								</div>
							</div>
							<input class="form-control form-control-sm" name="access_hospital_distance" type="text" placeholder="Distance (km)"/>
						</div>
					</div>

					<!-- School facility -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<div class="font-weight-bold mb-2" style="font-size: 13px;">School</div>
							<div class="d-flex align-items-center mb-2">
								<div class="custom-control custom-radio custom-control-inline mr-2">
									<input type="radio" id="acc_school_yes" name="access_school" class="custom-control-input" value="Yes" required>
									<label class="custom-control-label" for="acc_school_yes">Yes</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="acc_school_no" name="access_school" class="custom-control-input" value="No" required>
									<label class="custom-control-label" for="acc_school_no">No</label>
								</div>
							</div>
							<input class="form-control form-control-sm" name="access_school_distance" type="text" placeholder="Distance (km)"/>
						</div>
					</div>

					<!-- Church facility -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<div class="font-weight-bold mb-2" style="font-size: 13px;">Church</div>
							<div class="d-flex align-items-center mb-2">
								<div class="custom-control custom-radio custom-control-inline mr-2">
									<input type="radio" id="acc_church_yes" name="access_church" class="custom-control-input" value="Yes" required>
									<label class="custom-control-label" for="acc_church_yes">Yes</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="acc_church_no" name="access_church" class="custom-control-input" value="No" required>
									<label class="custom-control-label" for="acc_church_no">No</label>
								</div>
							</div>
							<input class="form-control form-control-sm" name="access_church_distance" type="text" placeholder="Distance (km)"/>
						</div>
					</div>

					<!-- Recreation facility -->
					<div class="col-md-3 mb-3">
						<div class="info-box">
							<div class="font-weight-bold mb-2" style="font-size: 13px;">Playground / Recreation</div>
							<div class="d-flex align-items-center mb-2">
								<div class="custom-control custom-radio custom-control-inline mr-2">
									<input type="radio" id="acc_rec_yes" name="access_recreation" class="custom-control-input" value="Yes" required>
									<label class="custom-control-label" for="acc_rec_yes">Yes</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" id="acc_rec_no" name="access_recreation" class="custom-control-input" value="No" required>
									<label class="custom-control-label" for="acc_rec_no">No</label>
								</div>
							</div>
							<input class="form-control form-control-sm" name="access_recreation_distance" type="text" placeholder="Distance (km)"/>
						</div>
					</div>
				</div>

				<!-- Section 5: Verification -->
				<div class="form-section-title">Survey Verification</div>
				
				<div class="row mb-4">
					<!-- Enumerator -->
					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Name of Enumerator</label>
						<input class="form-control" type="text" name="enumerator" placeholder="Name of Enumerator" required />
					</div>

					<!-- Verifier -->
					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Name of Verifier</label>
						<input class="form-control" type="text" name="verifier" placeholder="Name of Verifier" required />
					</div>

					<!-- Date Verified -->
					<div class="col-md-4 mb-3">
						<label class="font-weight-bold text-muted small text-uppercase">Date Verified</label>
						<input class="form-control" type="date" name="date_verified" required />
					</div>
				</div>

			</div>

			<!-- Footer Actions -->
			<div class="card-footer bg-light d-flex justify-content-end py-3">
				<a href="households_grid.php" class="btn btn-secondary btn-pds mr-3">Cancel</a>
				<button class="btn btn-primary btn-pds" type="submit" name="bSave">Submit</button>
			</div>
		</div>
	</form>
</div>

<script>
// Delegated event handling so it works inside Facebox
document.addEventListener('change', function(e) {
  // Municipal → Barangay
  if (e.target && e.target.id === 'city_mun') {
    let city_mun = e.target.value;
    fetch('get_options.php?type=barangay&city_mun=' + encodeURIComponent(city_mun))
      .then(res => res.json())
      .then(data => {
        let barangaySelect = document.getElementById('barangay');
        barangaySelect.innerHTML = '<option value="">Barangay</option>';
        data.forEach(item => {
          barangaySelect.innerHTML += '<option value="'+item+'">'+item+'</option>';
        });
        document.getElementById('purok').innerHTML = '<option value="">Purok</option>';
      });
  }

  // Barangay → Purok
  if (e.target && e.target.id === 'barangay') {
    let barangay = e.target.value;
    fetch('get_options.php?type=purok&barangay=' + encodeURIComponent(barangay))
      .then(res => res.json())
      .then(data => {
        let purokSelect = document.getElementById('purok');
        purokSelect.innerHTML = '<option value="">Purok</option>';
        data.forEach(item => {
          purokSelect.innerHTML += '<option value="'+item+'">'+item+'</option>';
        });
      });
  }
});
</script>

</body>

</html>