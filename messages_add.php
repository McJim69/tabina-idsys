<?php
	require("connect.php");
	
	$startyear = date("Y")-5;
	$endyear = date("Y"); 

	$months = array('', '01-Jan', '02-Feb', '03-Mar', '04-Apr', '05-May', '06-Jun', '07-Jul', '08-Aug', '09-Sep', '10-Oct', '11-Nov', '12-Dec');		

	$squery = "SELECT MAX(idn) FROM messages";
	$result = $link->query($squery);
	$values = $result->fetch_array();
	$lastID = $values[0] + 1;
?>

<div class="card border-0 shadow-none" style="min-width: 550px; max-width: 680px;">
    <!-- Modal Header -->
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
            <i class="fas fa-inbox mr-2"></i>Incoming Message (IM)
        </h5>
        <button type="button" class="close text-white opacity-100" onclick="jQuery(document).trigger('close.facebox')" aria-label="Close" style="outline:none;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- Modal Body & Form -->
    <div class="card-body p-4 bg-light">
        <form action="messages_add_proc.php" method="post" enctype="multipart/form-data" class="mb-0">
            <input type="hidden" name="idn" value="<?php echo $lastID; ?>" />

            <!-- FROM Section -->
            <div class="form-row">
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-paper-plane text-primary mr-1"></i>From (Sender Name)</label>
                    <input type="text" class="form-control" name="msg_from" placeholder="Sender Name" required autofocus>
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-building text-primary mr-1"></i>Office / Agency</label>
                    <input type="text" class="form-control" name="msg_office" placeholder="Sender Office/Agency" required>
                </div>
            </div>

            <!-- TO Section -->
            <div class="form-row">
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-user-check text-primary mr-1"></i>To (Recipient Name)</label>
                    <input type="text" class="form-control" name="msg_to" placeholder="Recipient Name" required>
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-user-tag text-primary mr-1"></i>Attention (Office/Person)</label>
                    <input type="text" class="form-control" name="msg_attn" placeholder="Attention Office/Person" required>
                </div>
            </div>

            <!-- DATED Section -->
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="far fa-calendar-alt text-primary mr-1"></i>Date Received / Logged</label>
                <input class="form-control" name="date_msg" type="date" value="<?php echo date('Y-m-d'); ?>" required />
            </div>

            <!-- Message Contents -->
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-align-left text-primary mr-1"></i>Message Contents</label>
                <textarea class="form-control" name="msg_content" rows="3" placeholder="Type message contents here..." required></textarea>
            </div>

            <!-- Confirmation Date (Optional) -->
            <div class="form-group mb-3">
                <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-calendar-check text-primary mr-1"></i>Confirmation Date (Optional)</label>
                <input class="form-control" name="date_confirm" type="date" />
            </div>

            <!-- Contact Details -->
            <div class="form-row">
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-address-book text-primary mr-1"></i>Contact Person</label>
                    <input type="text" class="form-control" name="contact_person" placeholder="Contact Person">
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-phone text-primary mr-1"></i>Contact Number</label>
                    <input type="text" class="form-control" name="contact_number" placeholder="Contact Number">
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="form-group col-md-6 mb-0">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-envelope text-primary mr-1"></i>Email Address</label>
                    <input type="email" class="form-control" name="contact_email" placeholder="Email Address">
                </div>
                <div class="form-group col-md-6 mb-0">
                    <label class="small font-weight-bold text-muted text-uppercase mb-1"><i class="fas fa-map-marker-alt text-primary mr-1"></i>Postal Address</label>
                    <input type="text" class="form-control" name="contact_postal" placeholder="Postal Address">
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex align-items-center justify-content-between pt-3 border-top mt-3">
                <span class="badge badge-secondary py-2 px-3 font-weight-bold">ID: #<?php echo sprintf("%04d", $lastID); ?></span>
                <div>
                    <button type="button" class="btn btn-outline-secondary mr-2" onclick="jQuery(document).trigger('close.facebox')">
                        <i class="fas fa-times mr-1"></i>Cancel
                    </button>
                    <button type="submit" name="bSave" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i>Submit Message
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>