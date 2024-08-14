<?php
include '../../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM table_student where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-student">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="" class="control-label">Student ID</label>
			<input type="student_id" name="student_id" id="student_id" class="form-control form-control-sm" required value="<?php echo isset($student_id) ? $student_id : '' ?>">
		</div>
		<div class="row">
			<div class="col">
				<div class="form-group">
					<label for="" class="control-label">First Name</label>
					<input type="text" name="firstname" id="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label for="" class="control-label">Middle Name</label>
					<input type="text" name="middlename" id="middlename" class="form-control form-control-sm" value="<?php echo isset($middlename) ? $middlename : '' ?>">
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label for="" class="control-label">Last Name</label>
					<input type="text" name="lastname" id="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col">
				<div class="form-group">
					<label for="level" class="control-label">Level</label>
					<select name="level" id="level" class="form-control form-control-sm select2" required>
						<option hidden></option>
						<option value="Elementary" <?php echo isset($level) && $level == "Elementary" ? "selected" : "" ?>>Elementary</option>
						<option value="Highschool" <?php echo isset($level) && $level == "Highschool" ? "selected" : "" ?>>Highschool</option>
						<option value="College" <?php echo isset($level) && $level == "College" ? "selected" : "" ?>>College</option>
					</select>
				</div>
			</div>
			<div class="col">
				<div class="form-group grpcourse">
					<label for="course" class="control-label">Course</label>
					<input type="text" name="course" id="course" class="form-control form-control-sm" value="<?php echo isset($course) ? $course : '' ?>">
				</div>
			</div>
		</div>	
		<div class="form-group">
			<label class="control-label">Password</label>
			<input type="password" class="form-control form-control-sm" name="password" <?php echo !isset($id) ? "required":'' ?>>
			<small><i><?php echo isset($id) ? "Leave this blank if you dont want to change you password":'' ?></i></small>
		</div>
		<div class="form-group">
			<label class="label control-label">Confirm Password</label>
			<input type="password" class="form-control form-control-sm" name="cpass" <?php echo !isset($id) ? 'required' : '' ?>>
			<small id="pass_match" data-status=''></small>
		</div>
	</form>
</div>
<div id="msg" class="form-group"></div>
<script>
	$(document).ready(function(){
		$('[name="password"],[name="cpass"]').keyup(function(){
			var pass = $('[name="password"]').val();
			var cpass = $('[name="cpass"]').val();
			if(cpass == '' || pass == ''){
				$('#pass_match').attr('data-status','');
			} else {
				if(cpass == pass){
					$('#pass_match').attr('data-status','1').html('<i class="text-success">Password Matched.</i>');
				} else {
					$('#pass_match').attr('data-status','2').html('<i class="text-danger">Password does not match.</i>');
				}
			}
		});

		$('#manage-student').submit(function(e){
			e.preventDefault();
			start_load();
			$('#msg').html('');
			
			if($('[name="password"]').val() != '' && $('[name="cpass"]').val() != ''){
				if($('#pass_match').attr('data-status') != 1){
					if($("[name='password']").val() != ''){
						$('[name="password"],[name="cpass"]').addClass("border-danger");
						end_load();
						return false;
					}
				}
			}

			$(this).find('.alert-danger').remove();
			$(this).find('.invalid-feedback').remove();
			var isValid = true;
			var inputs = this.querySelectorAll('input[required]');
			var selects = this.querySelectorAll('select[required]');
			var elements = Array.from(inputs).concat(Array.from(selects));

			elements.forEach(function(element) {
				if (!element.checkValidity()) {
					isValid = false;
					var feedbackMessage = document.createElement('div');
					feedbackMessage.innerHTML = 'Please fill out this field.';
					feedbackMessage.classList.add('invalid-feedback');
					$(element).parent().append(feedbackMessage);
				}
			});

			if (isValid) {
				$.ajax({
					url:'ajax.php?action=save_student',
					method:'POST',
					data:$(this).serialize(),
					success:function(resp){
						if(resp == 1){
							alert_toast("Data successfully saved.","success");
							setTimeout(function(){
								location.reload();
							},750);
						} else if(resp == 2){
							$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Student information already exist.</div>');
							end_load();
						}
					}
				});
			} else {
				end_load();
			}

			$(this).addClass('was-validated');
		});
	});
</script>
