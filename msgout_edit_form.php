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
		
	$msg="";
	if(isset($_GET["msgout"]) && $_GET["msgout"]!="")
		$msg=" and idn='".$_GET["msgout"]."' ";
										
	$ex=$link->query("select * from msgout where idn=idn $msg order by idn limit $from,$to ");
	
	while($rs=mysqli_fetch_array($ex)){
?>

<div class="card border-0 shadow-none">
    <!-- Modal Header -->
    <div class="card-header bg-warning text-dark py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold">
            <i class="fas fa-edit mr-2"></i>Edit Message: <b><?php echo $rs[0];?></b>
        </h5>
    </div>

    <!-- Modal Body -->
    <div class="card-body p-4" style="max-height: 80vh; overflow-y: auto;">
        <form action="msgout_edit_proc.php" method="POST">
            <input type="hidden" name="idn" value="<?php echo $rs[0];?>"/>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Message From</label>
                    <input class="form-control" required name="msg_from" type="text" value="<?php echo htmlspecialchars($rs["msg_from"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Office From</label>
                    <input class="form-control" required name="msg_office" type="text" value="<?php echo htmlspecialchars($rs["msg_office"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Message To</label>
                    <input class="form-control" name="msg_to" type="text" value="<?php echo htmlspecialchars($rs["msg_to"]);?>" />
                </div>
                <div class="col-md-6 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Attention</label>
                    <input class="form-control" name="msg_attn" type="text" value="<?php echo htmlspecialchars($rs["msg_attn"]);?>" />
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Month</label>
                    
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Day</label>
                    <input class="form-control" name="date_msg" type="date" value="<?php echo htmlspecialchars($rs["date_msg"]);?>" />
                </div>
                <div class="col-md-4 mb-2">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1">Year</label>
                    
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="small font-weight-bold text-muted text-uppercase mb-1">Content</label>
                <textarea class="form-control" rows="4" name="msg_content"><?php echo htmlspecialchars($rs["msg_content"]);?></textarea>
            </div>

            <!-- Action Buttons Footer -->
            <div class="d-flex align-items-center justify-content-end border-top pt-3">
                <button type="submit" name="update" value="Update" class="btn btn-warning font-weight-bold shadow-sm px-4">
                    <i class="fas fa-save mr-1"></i> Update Message
                </button>
            </div>
        </form>
    </div>
</div>

<?php } ?>