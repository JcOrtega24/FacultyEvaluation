<?php 
include '../../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM criteria_list where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-criteria" novalidate>
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div id="msg" class="form-group"></div>
		<div class="form-group">
			<label for="criteria" class="control-label">Criteria Description</label>
			<input type="text" class="form-control form-control-sm" name="criteria" id="criteria" value="<?php echo isset($criteria) ? $criteria : '' ?>" required>
		</div>	
		<div class="form-group">
			<label for="education_level" class="control-label">Criteria Type</label>
			<select name="type" id="type" class="form-control form-control-sm" required>
				<option value="Rate" <?php echo isset($type) && $type == "Rate" ? "selected" : "" ?>>Rate</option>
				<option value="Comments" <?php echo isset($type) && $type == "Comments" ? "selected" : "" ?>>Comments</option>
			</select>
		</div>	
		<div class="form-group">
			<label for="education_level" class="control-label">Year Level</label>
			<select name="education_level" id="education_level" class="form-control form-control-sm" required>
				<option value="Elementary" <?php echo isset($education_level) && $education_level == "Elementary" ? "selected" : "" ?>>Elementary</option>
				<option value="Highschool" <?php echo isset($education_level) && $education_level == "Highschool" ? "selected" : "" ?>>Highschool</option>
				<option value="College" <?php echo isset($education_level) && $education_level == "College" ? "selected" : "" ?>>College</option>
			</select>
		</div>		
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#manage-criteria').submit(function(e){
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
					url:'ajax.php?action=save_criteria',
					method:'POST',
					data:$(this).serialize(),
					success:function(resp){
						if(resp == 1){
							alert_toast("Data successfully saved.","success");
							setTimeout(function(){
								location.reload()	
							},750)
						}else if(resp == 2){
							$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Question already exist.</div>')
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