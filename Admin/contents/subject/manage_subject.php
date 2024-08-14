<?php
include '../../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM table_subject where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-subject" novalidate>
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="subject" class="control-label">Subject Code</label>
			<input type="text" class="form-control form-control-sm" name="code" id="code" value="<?php echo isset($code) ? $code : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="subject" class="control-label">Subject Name</label>
			<input type="text" class="form-control form-control-sm" name="subject" id="subject" value="<?php echo isset($subject) ? $subject : '' ?>" required>
		</div>
		<div class="form-group">
			<label for="level">Level</label>
			<select name="level" id="level" class="form-control form-control-sm select2" required>
				<option value="" hidden></option>
				<option value="Elementary" <?php echo isset($level) && $level == "Elementary" ? "selected" : "" ?>>Elementary</option>
				<option value="Highschool" <?php echo isset($level) && $level == "Highschool" ? "selected" : "" ?>>Highschool</option>
				<option value="College" <?php echo isset($level) && $level == "College" ? "selected" : "" ?>>College</option>
			</select>
		</div>
		<div class="form-group">
			<label for="department" class="control-label">Department</label>
			<select name="department" id="department" class="form-control form-control-sm select2" required>
				<option value="" hidden></option>
				<option value="Arts and Science" <?php echo isset($department) && $department == "Arts and Science" ? "selected" : "" ?>>Arts and Science</option>
				<option value="Criminal Justice Education" <?php echo isset($department) && $department == "Criminal Justice Education" ? "selected" : "" ?>>Criminal Justice Education</option>
				<option value="Business Administration" <?php echo isset($department) && $department == "Business Administration" ? "selected" : "" ?>>Business Administration</option>
				<option value="Computer Science" <?php echo isset($department) && $department == "Computer Science" ? "selected" : "" ?>>Computer Science</option>
				<option value="Education" <?php echo isset($department) && $department == "Education" ? "selected" : "" ?>>Education</option>
			</select>
		</div>		
	</form>
</div>
<div id="msg" class="form-group"></div>
<script>
	$(document).ready(function(){
		$('#manage-subject').submit(function(e){
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
					url:'ajax.php?action=save_subject',
					method:'POST',
					data:$(this).serialize(),
					success:function(resp){
						if(resp == 1){
							alert_toast("Data successfully saved.","success");
							setTimeout(function(){
								location.reload()	
							},750)
						}else if(resp == 2){
							$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Subject Code already exist.</div>')
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