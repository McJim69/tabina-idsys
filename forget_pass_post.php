<form action="forget_pass_post_proc.php" method="POST" class="p-3" style="max-width: 450px; min-width: 310px;">
	<div class="text-center mb-3">
		<h4 class="font-weight-bold text-success">
			<i class="fas fa-key mr-2"></i>Password Recovery
		</h4>
		<p class="text-secondary small">Submit a request to recover or reset your account password.</p>
	</div>
	<hr class="my-3"/>
	
	<!-- Name -->
	<div class="form-group row text-left">
		<label for="name" class="col-sm-3 col-form-label font-weight-bold text-primary">Name</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="name" id="name" required placeholder="Username or Full Name">
		</div>
	</div>

	<!-- Email -->
	<div class="form-group row text-left">
		<label for="email" class="col-sm-3 col-form-label font-weight-bold text-primary">Email</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="email" id="email" required placeholder="Email or Cellphone">
		</div>
	</div>

	<!-- Message -->
	<div class="form-group row text-left">
		<label for="message" class="col-sm-3 col-form-label font-weight-bold text-primary">Notes</label>
		<div class="col-sm-9">
			<textarea class="form-control" name="message" id="message" rows="3" placeholder="Type your message here" style="resize: none;" required></textarea>
		</div>
	</div>

	<!-- Buttons -->
	<div class="row mt-4">
		<div class="col-sm-9 offset-sm-3 d-flex justify-content-start">
			<button type="submit" name="submit" class="btn btn-success font-weight-bold px-4 mr-2">
				<i class="fas fa-paper-plane mr-1"></i>Submit
			</button>
			<a href="index.php" class="btn btn-secondary font-weight-bold px-4">
				<i class="fas fa-times mr-1"></i>Cancel
			</a>
		</div>
	</div>
</form>