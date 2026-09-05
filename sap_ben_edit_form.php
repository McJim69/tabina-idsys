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
	if(isset($_GET["sap_ben"]) && $_GET["sap_ben"]!="")
		$mem=" and idn='".$_GET["sap_ben"]."' ";
										
	$ex = $link->query("select * from sap_ben where idn=idn $mem order by idn limit $from,$to ");

	while($rs = mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit SAP - ID: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="sap_ben_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <!-- Beneficiary Full Name -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">First Name</label>
                    <input class="form-control" required name="name_1st" type="text" value="<?php echo htmlspecialchars($rs["name_1st"]);?>" />
                </div>
                <div class="col-md-3 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Middle Name</label>
                    <input class="form-control" name="name_mid" type="text" value="<?php echo htmlspecialchars($rs["name_mid"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Family Name</label>
                    <input class="form-control" required name="name_fam" type="text" value="<?php echo htmlspecialchars($rs["name_fam"]);?>" />
                </div>
                <div class="col-md-1 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Ext</label>
                    <input class="form-control" name="name_ext" type="text" value="<?php echo htmlspecialchars($rs["name_ext"]);?>" />
                </div>
            </div>

            <!-- SAP Form No & Barangay -->
            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">SAP Form Number</label>
                    <input class="form-control" required name="sap_form" type="text" value="<?php echo htmlspecialchars($rs["sap_form"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Barangay</label>
                    <input class="form-control" required name="barangay" type="text" value="<?php echo htmlspecialchars($rs["barangay"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Purok</label>
                    <input class="form-control" name="purok" type="text" value="<?php echo htmlspecialchars($rs["purok"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Period Covered</label>
                    <input class="form-control" name="period" type="text" value="<?php echo htmlspecialchars($rs["period"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Amount Received</label>
                    <input class="form-control" name="amount" type="text" value="<?php echo htmlspecialchars($rs["amount"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Date Paid / Received</label>
                    <input class="form-control" name="date_paid" type="text" value="<?php echo htmlspecialchars($rs["date_paid"]);?>" />
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Remarks</label>
                <input class="form-control" name="remarks" type="text" value="<?php echo htmlspecialchars($rs["remarks"]);?>" />
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update SAP Record
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>