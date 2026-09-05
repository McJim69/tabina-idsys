<?php 
	include("connect2.php");

	if (!isset($_SESSION['user'])) {
		echo '
		<script type="text/javascript">
			swal({
				title: "Not Logged In",
				text: "Please login to continue.",
				icon: "warning",
				button: "Login"
			}).then(function() {
				window.location.href = "login.php";
			});
		</script>';
	}
	elseif (empty($_SESSION["purok"]) && empty($_SESSION["phone"])) {
		$edit="\"Edit Profile\"";
		echo '
		<script type="text/javascript">
			swal({
				title: "Incomplete Account",
				text: "Please complete your account details to update. Click the button '.addslashes($edit).' below your profile details to continue.",
				icon: "warning",
				button: "Close"
			}).then(function() {
				window.location.href = "public_dashboard.php";
			});
		</script>';
	}
	
	$attn_val = isset($_GET['attn']) ? htmlspecialchars($_GET['attn']) : '';
	$subject_val = isset($_GET['subject']) ? htmlspecialchars($_GET['subject']) : '';
?>

<link href="style/post-message.css" rel="stylesheet" type="text/css"/>

<div class="card border-0 shadow-none">
    <div class="card-header bg-primary text-white py-3 px-4 d-flex align-items-center justify-content-between">
        <h5 class="mb-0 font-weight-bold text-white">
			<i class="fa fa-paper-plane"></i> POST MESSAGE
        </h5>
    </div>
    <div class="card-body p-4">
        <form action="post_message_proc.php" method="POST">
            <div class="form-row mb-3">
				<div class="col-md-6 mb-2">
					<label class="form-label mb-1">FULL NAME</label>
					<input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars(isset($_SESSION['fullname']) ? $_SESSION['fullname'] : ''); ?>" readonly />
				</div>
				<div class="col-md-6 mb-2">
					<label class="form-label mb-1">EMAIL</label>
					<input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars(isset($_SESSION['email']) ? $_SESSION['email'] : ''); ?>" readonly />
				</div>
			</div>
            <div class="form-row mb-3">
				<div class="col-md-6 mb-2">
					<div class="form-group mb-3">
						<label class="form-label mb-1">ATTENTION</label>
						<input type="text" class="form-control" name="attention" value="<?php echo $attn_val; ?>" placeholder="Attention to..." required />
					</div>
				</div>
                <div class="col-md-6 mb-2">                
					<label class="form-label mb-1">SUBJECT</label>
					<input type="text" class="form-control" name="subject" value="<?php echo $subject_val; ?>" placeholder="Subject" required />
				</div>
			</div>
			<div class="form-row mb-3">
				<div class="col-md-12 mb-2"> 
					<label class="form-label mb-1">MESSAGE</label>
					<textarea class="form-control" rows="5" name="message" placeholder="Type your message here" style="resize: none;" required></textarea>
				</div>
			</div>
            <div class="form-row d-flex align-items-center justify-content-center">
                <button class="btn btn-primary rounded-pill" type="submit" name="submit"><i class="fa fa-paper-plane"></i> Post Message</button>
            </div>
        </form>
    </div>
</div>