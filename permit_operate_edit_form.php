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
		
	$perm="";
	if(isset($_GET["permit_operate"]) && $_GET["permit_operate"]!="")
		$perm=" and idn='".$_GET["permit_operate"]."' ";
										
	$ex=$link->query("select * from permit_operate where idn=idn $perm order by idn limit $from,$to ");
	
	while($rs=mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Permit to Operate: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <div class="mb-3 text-center">
            <?php if(file_exists("images/permit_operate/".$rs[0].".jpg")): ?>
                <img src="images/permit_operate/<?php echo $rs[0]; ?>.jpg?<?php echo time(); ?>" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php else: ?>
                <img src="images/back_ca.jpg" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php endif; ?>
        </div>

        <form action="permit_operate_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <!-- Trade Name & Activity -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Trade Name</label>
                    <input class="form-control" required name="tradename" type="text" value="<?php echo htmlspecialchars($rs["tradename"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Nature of Activity</label>
                    <input class="form-control" required name="activity" type="text" value="<?php echo htmlspecialchars($rs["activity"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Mode</label>
                    <input class="form-control" required name="is_mode" type="text" value="<?php echo htmlspecialchars($rs["is_mode"]);?>" />
                </div>
            </div>

            <!-- Owner Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Owner First Name</label>
                    <input class="form-control" required name="name_1st" type="text" value="<?php echo htmlspecialchars($rs["name_1st"]);?>" />
                </div>
                <div class="col-md-2 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">M.I.</label>
                    <input class="form-control" name="name_mid" type="text" value="<?php echo htmlspecialchars($rs["name_mid"]);?>" maxlength="2" />
                </div>
                <div class="col-md-5 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Owner Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" value="<?php echo htmlspecialchars($rs["name_fam"]);?>" />
                </div>
            </div>

            <!-- Address Section -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok / Street</label>
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

            <!-- Issue Date & Receipt -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Issue Month</label>
                    
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Issue Day</label>
                    <input class="form-control" name="date_issued" type="date" value="<?php echo htmlspecialchars($rs["date_issued"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Issue Year</label>
                    
                </div>
            </div>

            <div class="form-row mb-4">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">O.R. Number</label>
                    <input class="form-control" name="isorno" type="text" value="<?php echo htmlspecialchars($rs["isorno"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Amount Paid</label>
                    <input class="form-control" name="oramount" type="text" value="<?php echo htmlspecialchars($rs["oramount"]);?>" />
                </div>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Permit
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>