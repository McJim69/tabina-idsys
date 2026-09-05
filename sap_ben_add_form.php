<?php
	require("connect.php");
	
	$squery = "SELECT MAX(idn) FROM sap_ben";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0]+1;
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-hand-holding-usd mr-2"></i>Add SAP - SID: <b><?php echo $lastID;?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4">
        <form action="sap_ben_add_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $lastID;?>" />
            <input type="hidden" name="period" value="" />
            <input type="hidden" name="amount" value="" />
            <input type="hidden" name="signature" value="" />
            <input type="hidden" name="date_paid" value="" />

            <!-- Beneficiary Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" type="text" name="name_1st" placeholder="First Name" required autofocus />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Middle Name</label>
                    <input class="form-control" type="text" name="name_mid" placeholder="Middle Name" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" type="text" name="name_fam" placeholder="Family Name" required />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Ext. Name</label>
                    <input class="form-control" type="text" name="name_ext" placeholder="Jr., III, etc." />
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
			
            <!-- SAP Form No. & Barangay -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">SAP Form Number</label>
                    <input class="form-control" type="text" name="sap_form" placeholder="SAP Form No." required />
                </div>
				 <div class="col-md-6 mb-2">
					<label class="small font-weight-bold text-muted text-uppercase mb-1">Remarks</label>
					<input class="form-control" name="remarks" placeholder="Remarks" />
				</div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="bSave" class="btn btn-primary px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-1"></i> Submit SAP Beneficiary
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