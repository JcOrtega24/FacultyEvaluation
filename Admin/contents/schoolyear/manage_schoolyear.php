<?php
include '../../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM table_schoolyear where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-schoolyear" novalidate>
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div id="msg" class="form-group"></div>
		<div class="form-group">
			<label for="schoolyear" class="control-label">School Year</label>
			<input type="text" class="form-control form-control-sm" name="schoolyear" id="schoolyear" value="<?php echo isset($schoolyear) ? $schoolyear : '' ?>" placeholder="(2019-2020)" required>
			<div class="invalid-feedback">
                Please input School Year.
            </div>
		</div>
		<div class="form-group">
			<label for="semester" class="control-label">Semester</label>
			<select name="semester" id="semester" class="form-control form-control-sm" required>
				<option value="1st" <?php echo isset($semester) && $semester == "1st" ? "selected" : "" ?>>1st</option>
				<option value="2nd" <?php echo isset($semester) && $semester == "2nd" ? "selected" : "" ?>>2nd</option>
				<option value="3rd" <?php echo isset($semester) && $semester == "3rd" ? "selected" : "" ?>>3rd/Summer</option>
			</select>
			<div class="invalid-feedback">
                Please select semester type.
            </div>
		</div>
		<?php if(isset($status)): ?>
			<div class="form-group">
				<label for="" class="control-label">Status</label>
				<select name="status" id="status" class="form-control form-control-sm">
					<option value="0" <?php echo $status == 0 ? "selected" : "" ?>>Pending</option>
					<option value="1" <?php echo $status == 1 ? "selected" : "" ?>>Started</option>
					<option value="2" <?php echo $status == 2 ? "selected" : "" ?>>Closed</option>
				</select>
			</div>
		<?php endif; ?>
	</form>
</div>
<style>
	.form-check-inline {
		display: inline-block;
		margin-right: 10px; /* Adjust as needed */
	}
</style>
<script>
	(function() {
		'use strict';
		window.addEventListener('load', function() {
		// Fetch all the forms we want to apply custom Bootstrap validation styles to
		var forms = document.getElementById('manage-schoolyear');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function(form) {
			form.addEventListener('submit', function(event) {
			if (form.checkValidity() === false) {
				event.preventDefault();
				event.stopPropagation();
			}
			form.classList.add('was-validated');
			}, false);
		});
		}, false);
	})();

	$(document).ready(function(){
		$('#manage-schoolyear').submit(function(e){
			e.preventDefault();
			start_load();

			// Remove any existing error messages and feedback messages
			$(this).find('.alert-danger').remove();
			$(this).find('.invalid-feedback').remove();

			// Flag to track if the form is valid
			var isValid = true;

			// Fetch all the input fields and select elements within the form
			var inputs = this.querySelectorAll('input[required]');
			var selects = this.querySelectorAll('select[required]');
			var elements = Array.from(inputs).concat(Array.from(selects)); // Combine input and select elements

			// Loop through each input field and select element
			elements.forEach(function(element) {
				// Check if the element is invalid
				if (!element.checkValidity()) {
					isValid = false; // Set isValid to false if any required field is empty

					// Show invalid-feedback message
					var feedbackMessage = document.createElement('div');
					feedbackMessage.innerHTML = 'Please fill out this field.';
					feedbackMessage.classList.add('invalid-feedback');
					$(element).parent().append(feedbackMessage);
				}
			});

			// If form is valid, proceed with AJAX form submission
			if (isValid) {
				$.ajax({
					url:'ajax.php?action=save_schoolyear',
					method:'POST',
					data:$(this).serialize(),
					success:function(resp){
						if(resp == 1){
							alert_toast("Data successfully saved.","success");
							setTimeout(function(){
								location.reload()	
							},750)
						}else if(resp == 2){
							$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Schoolyear already exist.</div>')
							end_load()
						}
					}
				});
			} else {
				end_load();
			}

			// Add 'was-validated' class to the form
			$(this).addClass('was-validated');
		});
	});
</script>