<?php 
	require("connect.php"); 

	$startyear = date("Y")-80;
	$endyear=date("Y"); 

	$months=array('','JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG', 'SEP','OCT','NOV','DEC');

	$squery = "SELECT MAX(idn) FROM employees";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-id-badge mr-2"></i>Employees Form <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="employees_add_proc.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>" />
			<input type="hidden" name="province" value="Zamboanga del Sur"  />
            <!-- Employee Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" type="text" name="name_1st" placeholder="First Name" required autofocus />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" type="text" name="name_mid" placeholder="M.I." maxlength="2" required />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" type="text" name="name_fam" placeholder="Family Name" required />
                </div>
            </div>
			
            <!-- Address Section -->
            <div class="form-row mb-3">
				<!-- Municipality -->
				<div class="col-md-4 mb-2">
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
				<div class="col-md-4 mb-2">
					<label class="font-weight-bold text-muted small text-uppercase">Barangay</label>
					<select class="form-control" id="barangay" name="barangay">
					  <option value="">Barangay</option>
					</select>
				</div>

				<!-- Purok -->
				<div class="col-md-4 mb-2">
					<label class="font-weight-bold text-muted small text-uppercase">Purok</label>
					<select class="form-control" id="purok" name="purok">
					  <option value="">Purok</option>
					</select>
				</div>
			</div>
			
            <!-- Agency, Department & Position -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Government Agency</label>
                    <select class="form-control" name="agency" required>
                        <option value="" selected>Select Agency</option>
                        <option value="BLGU">Barangay</option>
                        <option value="MLGU">Municipal</option>				
                        <option value="PLGU">Provincial</option>				
                        <option value="NGOV">National</option>				
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Department / Office</label>
                    <select class="form-control" required name="department">
                        <option value="" selected>Select Department</option>
                        <?php 
                            $exo = $link->query("select * from offices order by ofname");
                            while($row = $exo->fetch_array()){				
                                echo "<option value='".$row["ofcode"]."'>".$row["ofname"]."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Position / Designation</label>
                    <select class="form-control" required name="position">
                        <option value="" selected>Select Position</option>
                        <?php 
                            $exp = $link->query("select * from positions order by psname");
                            while($row = $exp->fetch_array()){						
                                echo "<option value='".$row["pscode"]."'>".$row["psname"]."</option>";
                            }
                        ?>
                    </select>
                </div>
            </div>

            <!-- Appointment Date -->
            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Appointment Date</label>
                    <input class="form-control" name="date_appointed" type="date" value="<?php echo date('Y-m-d'); ?>" required />
                </div>
            </div>

            <!-- Contact & Demographics -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" type="text" name="contact" placeholder="Contact No." required />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Gender</label>
                    <select class="form-control" required name="sex">
                        <option value="" selected>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" type="email" name="emailadd" placeholder="Email Address" />
                </div>
            </div>

            <!-- Birth Date -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" type="date" required name="date_birth" />
                </div>
            </div>

            <!-- Government Benefit IDs -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Pag-IBIG No.</label>
                    <input class="form-control" type="text" name="pagibig" placeholder="PagIbig" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">PhilHealth No.</label>
                    <input class="form-control" type="text" name="philhealth" placeholder="PhilHealth" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">GSIS / SSS No.</label>
                    <input class="form-control" type="text" name="gsis" placeholder="GSIS/SSS" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">TIN</label>
                    <input class="form-control" type="text" name="tin" placeholder="TIN" />
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="form-row mb-4">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Emergency Contact Person</label>
                    <input class="form-control" type="text" name="contactperson" placeholder="Contact Person" required />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Emergency Contact Number</label>
                    <input class="form-control" type="text" name="emergencyno" placeholder="Contact Number" required />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Relationship</label>
                    <select class="form-control" required name="relationship">
                        <option value="" selected>Select Relationship</option>
                        <option value="Brother">Brother</option>
                        <option value="Daughter">Daughter</option>				
                        <option value="Father">Father</option>
                        <option value="Parent">Guardian</option>
                        <option value="Mother">Mother</option>
                        <option value="Parent">Parent</option>
                        <option value="Relative">Relative</option>
                        <option value="Sister">Sister</option>
                        <option value="Son">Son</option>
                        <option value="Spouse">Spouse</option>
                        <option value="Relative">Supervisor</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Register Employee
                </button>
            </div>
        </form>
    </div>
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