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
			
	$clear="";
	if(isset($_GET["kinder"]) && $_GET["kinder"]!="")
		$clear=" and idn='".$_GET["kinder"]."' ";
										
	$ex=$link->query("select * from kinder where idn=idn $clear order by idn limit $from,$to ");
	
	while($rs=mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-child mr-2"></i>Edit Kinder - ID: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <div class="mb-3 text-center">
            <?php if(file_exists("images/kinder/".$rs[0].".jpg")): ?>
                <img src="images/kinder/<?php echo $rs[0]; ?>.jpg?<?php echo time(); ?>" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php else: ?>
                <img src="images/back_ca.jpg" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php endif; ?>
        </div>

        <form action="kinder_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <!-- Child Full Name -->
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

            <!-- Address Section -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / District</label>
                    <input class="form-control" required name="purok" type="text" value="<?php echo htmlspecialchars($rs["purok"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
                    <input class="form-control" required name="purok" type="text" value="<?php echo htmlspecialchars($rs["purok"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">City / Municipality</label>
                    <input class="form-control" required name="city_mun" type="text" value="<?php echo htmlspecialchars($rs["city_mun"]);?>" />
                </div>
            </div>

            <!-- Birth Date & Sex -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date of Birth</label>
                    <input class="form-control" type="date" required name="date_birth" value="<?php echo htmlspecialchars($rs["date_birth"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Sex</label>
                    <select class="form-control" required name="sex">
                        <option value="Male" <?php if($rs["sex"]==="Male") echo "selected";?>>Male</option>
                        <option value="Female" <?php if($rs["sex"]==="Female") echo "selected";?>>Female</option>
                    </select>
                </div>
            </div>

            <!-- Parent / Guardian Information -->
            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Parent / Guardian Name</label>
                    <input class="form-control" required name="parent" type="text" value="<?php echo htmlspecialchars($rs["parent"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Contact Number</label>
                    <input class="form-control" required name="contact" type="text" value="<?php echo htmlspecialchars($rs["contact"]);?>" />
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Child Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>