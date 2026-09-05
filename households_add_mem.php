<?php require("connect.php"); ?>

<?php
	$mem = "";
	if(isset($_GET["households"]) && $_GET["households"] != "") {
		$mem = " and hhid='" . mysqli_real_escape_string($link, $_GET["households"]) . "' ";
	} else {
		die("Invalid Household ID");
	}
									
	$ex = $link->query("select * from households where hhid=hhid $mem order by hhid limit 1");
	if($rs = mysqli_fetch_array($ex)){
?>
	<!-- Inline Facebox Sizing Overrides -->
	<style>
		#facebox {
			max-width: 95% !important;
			width: 1000px !important;
		}
		#facebox .popup {
			width: 100% !important;
		}
		.member-row {
			transition: all 0.2s ease;
		}
		.member-row:hover {
			border-color: #007bff !important;
			box-shadow: 0 4px 10px rgba(0,0,0,0.05);
		}
	</style>

	<div class="p-3" style="max-height: 75vh; overflow-y: auto;">
		<div class="text-center mb-4">
			<h4 class="font-weight-bold text-primary mb-0"><?php echo htmlspecialchars($rs["hh_name"]); ?></h4>
			<small class="text-muted text-uppercase tracking-wider">Household Head</small>
			<h5 class="font-weight-bold text-dark mt-3">Add Household Members</h5>
		</div>

		<form action='households_add_mem_proc.php' method='POST'>
			<input type='hidden' name='hm_belong' value='<?php echo $rs[0]; ?>' />
			<input type='hidden' name='purok' value='<?php echo htmlspecialchars($rs["purok"]); ?>' />
			<input type='hidden' name='barangay' value='<?php echo htmlspecialchars($rs["barangay"]); ?>' />
			<input type='hidden' name='city_mun' value='<?php echo htmlspecialchars($rs["city_mun"]); ?>' />

			<div id="member-rows-container">
				<!-- Member Row 1 -->
				<div class="member-row border rounded p-3 mb-3 position-relative" style="background: rgba(255,255,255,0.02); border-color: #e2e8f0;">
					<button type="button" class="btn btn-sm btn-outline-danger remove-row-btn position-absolute" style="top: 10px; right: 10px;" onclick="removeRow(this)">
						<i class="fas fa-trash-alt"></i>
					</button>
					<div class="row">
						<!-- Name -->
						<div class="col-md-4 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Member Name</label>
							<input class="form-control form-control-sm" type="text" name="hm_name[]" placeholder="First / MI / Family Name" required />
						</div>
						<!-- Sex -->
						<div class="col-md-2 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
							<select class="form-control form-control-sm" name="hm_sex[]" required>
								<option value="">Sex</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div>
						<!-- Birth Date -->
						<div class="col-md-3 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Birth Date</label>
							<input class="form-control form-control-sm" type="date" name="hm_birth[]" required />
						</div>
						<!-- Education -->
						<div class="col-md-3 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Education</label>
							<select class="form-control form-control-sm" name="hm_education[]" required>
								<option value="" selected="1">Education</option>
								<option value="N/A">N/A</option>
								<option value="Illiterate">Illiterate</option>
								<option value="Nursery">Nursery</option>
								<option value="Pre-School">Pre-School</option>
								<option value="Kindergarten">Kindergarten</option>
								<option value="Grade-1">Grade-1</option>
								<option value="Grade-2">Grade-2</option>
								<option value="Grade-3">Grade-3</option>
								<option value="Grade-4">Grade-4</option>
								<option value="Grade-5">Grade-5</option>
								<option value="Grade-6">Grade-6</option>
								<option value="Grade-7">Grade-7</option>
								<option value="Grade-8">Grade-8</option>
								<option value="Grade-9">Grade-9</option>
								<option value="Grade-10">Grade-10</option>
								<option value="Grade-11">Grade-11</option>
								<option value="Grade-12">Grade-12</option>
								<option value="Elementary Level">Elementary Level</option> 
								<option value="HS Level">HS Level</option>
								<option value="HS Graduate">HS Graduate</option>		
								<option value="Vocational">Vocational</option>			
								<option value="1st Year College">1st Year College</option>
								<option value="2nd Year College">2nd Year College</option>	
								<option value="3rd Year College">3rd Year College</option>					
								<option value="4rt Year College">4rt Year College</option>					
								<option value="5th Year College">5th Year College</option>
								<option value="College Level">College Level</option>
								<option value="College Graduate">College Graduate</option>					
								<option value="Masteral / Ph.D">Masteral / Ph.D</option>
							</select>
						</div>
					</div>
					<div class="row">
						<!-- Enrolled -->
						<div class="col-md-2 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Enrolled?</label>
							<select class="form-control form-control-sm" name="hm_enrolled[]" required>
								<option value="">Enrolled?</option>
								<option value="Yes">Yes</option>
								<option value="No">No</option>
							</select>
						</div>
						<!-- Main Income -->
						<div class="col-md-2 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Main Income</label>
							<input class="form-control form-control-sm" type="text" name="hm_main_income[]" placeholder="Main Income" />
						</div>
						<!-- Second Income -->
						<div class="col-md-2 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Second Income</label>
							<input class="form-control form-control-sm" type="text" name="hm_second_income[]" placeholder="Secondary" />
						</div>
						<!-- Est. Income -->
						<div class="col-md-2 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Est. Income</label>
							<input class="form-control form-control-sm" type="text" name="hm_estimated_income[]" placeholder="Est. Total" />
						</div>
						<!-- Beneficiary -->
						<div class="col-md-2 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Beneficiary</label>
							<select class="form-control form-control-sm" name="hm_social[]">
								<option value="">Beneficiary</option>
								<option value="SP">SP</option>
								<option value="SC">SC</option>
								<option value="4Ps">4Ps</option>
								<option value="SAP">SAP</option>
								<option value="PWD">PWD</option>
							</select>
						</div>
						<!-- Remarks -->
						<div class="col-md-2 mb-2">
							<label class="small font-weight-bold text-muted text-uppercase mb-1">Remarks</label>
							<input class="form-control form-control-sm" type="text" name="hm_remarks[]" placeholder="Remarks" />
						</div>
					</div>
				</div>
			</div>

			<!-- Form Actions -->
			<div class="d-flex justify-content-between align-items-center mt-4">
				<button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3" onclick="addRow()">
					<i class="fas fa-plus mr-1"></i> Add Another Member
				</button>
				<div>
					<input class="btn btn-primary btn-sm rounded-pill px-4" type="submit" name="bSave" value="Save"/>
				</div>
			</div>
		</form>
	</div>

	<!-- Row Template -->
	<div id="row-template" style="display: none;">
		<button type="button" class="btn btn-sm btn-outline-danger remove-row-btn position-absolute" style="top: 10px; right: 10px;" onclick="removeRow(this)">
			<i class="fas fa-trash-alt"></i>
		</button>
		<div class="row">
			<!-- Name -->
			<div class="col-md-4 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Member Name</label>
				<input class="form-control form-control-sm" type="text" name="hm_name[]" placeholder="First / MI / Family Name" required />
			</div>
			<!-- Sex -->
			<div class="col-md-2 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
				<select class="form-control form-control-sm" name="hm_sex[]" required>
					<option value="">Sex</option>
					<option value="Male">Male</option>
					<option value="Female">Female</option>
				</select>
			</div>
			<!-- Birth Date -->
			<div class="col-md-3 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Birth Date</label>
				<input class="form-control form-control-sm" type="date" name="hm_birth[]" required />
			</div>
			<!-- Education -->
			<div class="col-md-3 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Education</label>
				<select class="form-control form-control-sm" name="hm_education[]" required>
					<option value="" selected="1">Education</option>
					<option value="N/A">N/A</option>
					<option value="Illiterate">Illiterate</option>
					<option value="Nursery">Nursery</option>
					<option value="Pre-School">Pre-School</option>
					<option value="Kindergarten">Kindergarten</option>
					<option value="Grade-1">Grade-1</option>
					<option value="Grade-2">Grade-2</option>
					<option value="Grade-3">Grade-3</option>
					<option value="Grade-4">Grade-4</option>
					<option value="Grade-5">Grade-5</option>
					<option value="Grade-6">Grade-6</option>
					<option value="Grade-7">Grade-7</option>
					<option value="Grade-8">Grade-8</option>
					<option value="Grade-9">Grade-9</option>
					<option value="Grade-10">Grade-10</option>
					<option value="Grade-11">Grade-11</option>
					<option value="Grade-12">Grade-12</option>
					<option value="Elementary Level">Elementary Level</option> 
					<option value="HS Level">HS Level</option>
					<option value="HS Graduate">HS Graduate</option>		
					<option value="Vocational">Vocational</option>			
					<option value="1st Year College">1st Year College</option>
					<option value="2nd Year College">2nd Year College</option>	
					<option value="3rd Year College">3rd Year College</option>					
					<option value="4rt Year College">4rt Year College</option>					
					<option value="5th Year College">5th Year College</option>
					<option value="College Level">College Level</option>
					<option value="College Graduate">College Graduate</option>					
					<option value="Masteral / Ph.D">Masteral / Ph.D</option>
				</select>
			</div>
		</div>
		<div class="row">
			<!-- Enrolled -->
			<div class="col-md-2 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Enrolled?</label>
				<select class="form-control form-control-sm" name="hm_enrolled[]" required>
					<option value="">Enrolled?</option>
					<option value="Yes">Yes</option>
					<option value="No">No</option>
				</select>
			</div>
			<!-- Main Income -->
			<div class="col-md-2 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Main Income</label>
				<input class="form-control form-control-sm" type="text" name="hm_main_income[]" placeholder="Main Income" />
			</div>
			<!-- Second Income -->
			<div class="col-md-2 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Second Income</label>
				<input class="form-control form-control-sm" type="text" name="hm_second_income[]" placeholder="Secondary" />
			</div>
			<!-- Est. Income -->
			<div class="col-md-2 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Est. Income</label>
				<input class="form-control form-control-sm" type="text" name="hm_estimated_income[]" placeholder="Est. Total" />
			</div>
			<!-- Beneficiary -->
			<div class="col-md-2 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Beneficiary</label>
				<select class="form-control form-control-sm" name="hm_social[]">
					<option value="">Beneficiary</option>
					<option value="SP">SP</option>
					<option value="SC">SC</option>
					<option value="4Ps">4Ps</option>
					<option value="SAP">SAP</option>
					<option value="PWD">PWD</option>
				</select>
			</div>
			<!-- Remarks -->
			<div class="col-md-2 mb-2">
				<label class="small font-weight-bold text-muted text-uppercase mb-1">Remarks</label>
				<input class="form-control form-control-sm" type="text" name="hm_remarks[]" placeholder="Remarks" />
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function addRow() {
			var container = document.getElementById('member-rows-container');
			var template = document.getElementById('row-template');
			var newRow = document.createElement('div');
			newRow.className = 'member-row border rounded p-3 mb-3 position-relative';
			newRow.style.background = 'rgba(255,255,255,0.02)';
			newRow.style.borderColor = '#e2e8f0';
			newRow.innerHTML = template.innerHTML;
			container.appendChild(newRow);
		}

		function removeRow(button) {
			var row = button.closest('.member-row');
			var container = document.getElementById('member-rows-container');
			if (container.getElementsByClassName('member-row').length > 1) {
				row.remove();
			} else {
				alert('At least one member row must remain.');
			}
		}
	</script>
<?php 
	} else {
		echo "<div class='alert alert-danger'>Household record not found.</div>";
	}
?>
