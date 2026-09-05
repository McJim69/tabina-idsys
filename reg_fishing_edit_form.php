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
	if(isset($_GET["reg_fishing"]) && $_GET["reg_fishing"]!="")
		$perm=" and idn='".$_GET["reg_fishing"]."' ";
										
	$ex=$link->query("select * from reg_fishing where idn=idn $perm order by idn limit $from,$to ");
	
	while($rs=mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Fishing Vessel Registry: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <div class="mb-3 text-center">
            <?php if(file_exists("images/reg_fishing/".$rs[0].".jpg")): ?>
                <img src="images/reg_fishing/<?php echo $rs[0]; ?>.jpg?<?php echo time(); ?>" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php else: ?>
                <img src="images/back_ca.jpg" class="img-thumbnail shadow-sm rounded mb-2" style="max-height: 140px; object-fit: cover;">
            <?php endif; ?>
        </div>

        <form action="reg_fishing_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <!-- Vessel Trade Name & Color -->
            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Vessel Trade Name</label>
                    <input class="form-control" required name="tradename" type="text" value="<?php echo htmlspecialchars($rs["tradename"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">FV Color</label>
                    <input class="form-control" required name="fvcolor" type="text" value="<?php echo htmlspecialchars($rs["fvcolor"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Hull Material</label>
                    <input class="form-control" required name="build_hull" type="text" value="<?php echo htmlspecialchars($rs["build_hull"]);?>" />
                </div>
            </div>

            <!-- Engine Specifications -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Engine Make</label>
                    <input class="form-control" required name="enginemake" type="text" value="<?php echo htmlspecialchars($rs["enginemake"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Engine S/N</label>
                    <input class="form-control" name="enginesn" type="text" value="<?php echo htmlspecialchars($rs["enginesn"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Horse Power</label>
                    <input class="form-control" required name="enginehp" type="text" value="<?php echo htmlspecialchars($rs["enginehp"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">No. of Engines</label>
                    <input class="form-control" required name="engineno" type="text" value="<?php echo htmlspecialchars($rs["engineno"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">No. of Cylinders</label>
                    <input class="form-control" name="engcylinder" type="text" value="<?php echo htmlspecialchars($rs["engcylinder"]);?>" />
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
            <div class="form-row mb-4">
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

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Vessel Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>