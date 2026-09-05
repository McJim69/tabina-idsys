<?php require("connect.php"); ?>

<?php
	$rec=1;
	$p=isset($_GET['page']) ? $_GET['page'] : 1;
	if($p>1){
		$to=$rec;
		$from=($p*$rec)-$rec;
	}else{
		$to=$rec;
		$from=0;
		$p=1;
	}			
		
	$mem="";
	if(isset($_GET["employees"]) && $_GET["employees"]!="")
		$mem=" and idn='".$_GET["employees"]."' ";
										
	$ex = $link->query("select * from employees where idn=idn $mem order by idn limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Employee <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <div class="mb-3 text-center">
            <?php if(file_exists("images/employees/".$rs[0].".jpg")): ?>
                <img src="images/employees/<?php echo $rs[0]; ?>.jpg?<?php echo time(); ?>" class="img-thumbnail shadow-sm rounded-circle mb-2" style="width: 110px; height: 110px; object-fit: cover;">
            <?php else: ?>
                <img src="images/blank.jpg" class="img-thumbnail shadow-sm rounded-circle mb-2" style="width: 110px; height: 110px; object-fit: cover;">
            <?php endif; ?>
        </div>

        <form action="employees_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <!-- Employee Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" value="<?php echo htmlspecialchars($rs["name_1st"]);?>" />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" name="name_mid" type="text" value="<?php echo htmlspecialchars($rs["name_mid"]);?>" maxlength="2" />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" value="<?php echo htmlspecialchars($rs["name_fam"]);?>" />
                </div>
            </div>

            <!-- Agency, Department & Position -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Agency</label>
                    <input class="form-control" required name="agency" type="text" value="<?php echo htmlspecialchars($rs["agency"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Department</label>
                    <input class="form-control" required name="department" type="text" value="<?php echo htmlspecialchars($rs["department"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Position</label>
                    <input class="form-control" required name="position" type="text" value="<?php echo htmlspecialchars($rs["position"]);?>" />
                </div>
            </div>

            <!-- Appointment Date -->
            <div class="form-row mb-3">
                <div class="col-md-12 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Appointment Date</label>
                    <input class="form-control" name="date_appointed" type="date" value="<?php echo htmlspecialchars($rs["date_appointed"]);?>" />
                </div>
            </div>

            <!-- Address Section -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / District</label>
                    <input class="form-control" required name="purok" type="text" value="<?php echo htmlspecialchars($rs["purok"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
                    <input class="form-control" required name="barangay" type="text" value="<?php echo htmlspecialchars($rs["barangay"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">City / Municipality</label>
                    <input class="form-control" required name="city_mun" type="text" value="<?php echo htmlspecialchars($rs["city_mun"]);?>" />
                </div>
            </div>

            <!-- Contact & Birth -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" name="contact" type="text" value="<?php echo htmlspecialchars($rs["contact"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Email Address</label>
                    <input class="form-control" name="emailadd" type="email" value="<?php echo htmlspecialchars($rs["emailadd"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" type="date" required name="date_birth" value="<?php echo htmlspecialchars($rs["date_birth"]);?>" />
                </div>
            </div>

            <!-- Benefit IDs -->
            <div class="form-row mb-3">
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Pag-IBIG No.</label>
                    <input class="form-control" name="pagibig" type="text" value="<?php echo htmlspecialchars($rs["pagibig"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">PhilHealth No.</label>
                    <input class="form-control" name="philhealth" type="text" value="<?php echo htmlspecialchars($rs["philhealth"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">GSIS / SSS</label>
                    <input class="form-control" name="gsis" type="text" value="<?php echo htmlspecialchars($rs["gsis"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">TIN</label>
                    <input class="form-control" name="tin" type="text" value="<?php echo htmlspecialchars($rs["tin"]);?>" />
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="form-row mb-4">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Emergency Contact</label>
                    <input class="form-control" name="contactperson" type="text" value="<?php echo htmlspecialchars($rs["contactperson"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Relationship</label>
                    <input class="form-control" name="relationship" type="text" value="<?php echo htmlspecialchars($rs["relationship"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" name="emergencyno" type="text" value="<?php echo htmlspecialchars($rs["emergencyno"]);?>" />
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Employee
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>