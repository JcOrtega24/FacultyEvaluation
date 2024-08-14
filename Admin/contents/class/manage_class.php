<?php
include '../../db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM table_class where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-class" novalidate>
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div id="msg" class="form-group"></div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="days" class="control-label">Days</label>
					<input type="text" class="form-control form-control-sm" name="days" id="days" value="<?php echo isset($days) ? $days : '' ?>" required>
					<!--select name="days" id="days" class="form-control form-control-sm select2" required>
						<option value="" selected hidden></option>
						<option value="Monday" <?php //echo isset($days) && $days == "Monday" ? "selected" : "" ?>>Monday</option>
						<option value="Tuesday" <?php //echo isset($days) && $days == "Tuesday" ? "selected" : "" ?>>Tuesday</option>
						<option value="Wednesday" <?php //echo isset($days) && $days == "Wednesday" ? "selected" : "" ?>>Wednesday</option>
						<option value="Thursday" <?php //echo isset($days) && $days == "Thursday" ? "selected" : "" ?>>Thursday</option>
						<option value="Friday" <?php //echo isset($days) && $days == "Friday" ? "selected" : "" ?>>Friday</option>
						<option value="Saturday" <?php //echo isset($days) && $days == "Saturday" ? "selected" : "" ?>>Saturday</option>
						<option value="Sunday" <?php //echo isset($days) && $days == "Sunday" ? "selected" : "" ?>>Sunday</option>
					</select-->
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="time" class="control-label">Time</label>
					<input type="text" class="form-control form-control-sm" name="time" id="time" value="<?php echo isset($time) ? $time : '' ?>" required>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="branch" class="control-label">Branch</label>
					<select name="branch" id="branch" class="form-control form-control-sm select2" required>
						<option value="" selected hidden></option>
						<option value="Main" <?php echo isset($branch) && $branch == "Main" ? "selected" : "" ?>>Main</option>
						<option value="Pasay" <?php echo isset($branch) && $branch == "Pasay" ? "selected" : "" ?>>Pasay</option>
						<option value="Malabon" <?php echo isset($branch) && $branch == "Malabon" ? "selected" : "" ?>>Malabon</option>
						<option value="Mandaluyong" <?php echo isset($branch) && $branch == "Mandaluyong" ? "selected" : "" ?>>Mandaluyong</option>
						<option value="Pasig" <?php echo isset($branch) && $branch == "Pasig" ? "selected" : "" ?>>Pasig</option>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="room" class="control-label">Room</label>
					<input type="text" class="form-control form-control-sm" name="room" id="room" value="<?php echo isset($room) ? $room : '' ?>" required>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label for="schoolyear" class="control-label">School Year</label>
					<select name="schoolyear" id="schoolyear" class="form-control form-control-sm select2" required>
						<option value="" selected hidden></option>
						<?php
							$schlyear = $conn->query("SELECT * FROM table_schoolyear order by id DESC");
							if ($schlyear->num_rows>0) {
								while ($data = mysqli_fetch_array($schlyear)) {
							?>
								<option value="<?php echo $data['id']?>" <?php echo isset($schoolyear) && $schoolyear == $data['id'] ? "selected" : "" ?>><?php echo $data['schoolyear']." - ".$data['semester']?></option>
							<?php				
								}
							}
						?>
					</select>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label for="level" class="control-label">Level</label>
					<select name="level" id="level" class="form-control form-control-sm select2" required>
						<option value="" selected hidden></option>
						<option value="Elementary" <?php echo isset($level) && $level == "Elementary" ? "selected" : "" ?>>Elementary</option>
						<option value="Highschool" <?php echo isset($level) && $level == "Highschool" ? "selected" : "" ?>>Highschool</option>
						<option value="College" <?php echo isset($level) && $level == "College" ? "selected" : "" ?>>College</option>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="department" class="control-label">Department</label>	
			<select name="department" id="department" class="form-control form-control-sm select2">
				<option value="" selected hidden></option>
				<option value="Arts and Science" <?php echo isset($department) && $department == "Arts and Science" ? "selected" : "" ?>>Arts and Science</option>
				<option value="Criminal Justice Education" <?php echo isset($department) && $department == "Criminal Justice Education" ? "selected" : "" ?>>Criminal Justice Education</option>
				<option value="Business Administration" <?php echo isset($department) && $department == "Business Administration" ? "selected" : "" ?>>Business Administration</option>
				<option value="Computer Science" <?php echo isset($department) && $department == "Computer Science" ? "selected" : "" ?>>Computer Science</option>
				<option value="Education" <?php echo isset($department) && $department == "Education" ? "selected" : "" ?>>Education</option>
			</select>
		</div>
		<div class="form-group">
			<label for="subjcode" class="control-label">Subject</label>
			<select name="subjcode" id="subjcode" class="form-control form-control-sm select2" required>
				<option value="" selected hidden></option>
				<?php
					$subject = $conn->query("SELECT * FROM table_subject order by department");
					if ($subject->num_rows>0) {
						while ($data = mysqli_fetch_array($subject)) {
					?>
						<option value="<?php echo $data['id']?>" <?php echo isset($subjcode) && $subjcode == $data['id'] ? "selected" : "" ?>><?php echo $data['code']." - ".$data['subject']?></option>
					<?php				
						}
					}
				?>
			</select>
		</div>
		<div class="form-group">
			<label for="instructor" class="control-label">Instructor</label>
			<!--input type="text" class="form-control form-control-sm" name="instructor" id="instructor" value="<?php //echo isset($instructor) ? $instructor : '' ?>" required-->
			<select name="instructor" id="instructor" class="form-control form-control-sm select2" required>
				<option value="" selected hidden></option>
				<?php
					$faculty = $conn->query("SELECT * FROM table_faculty");
					if ($faculty->num_rows>0) {
						while ($data = mysqli_fetch_array($faculty)) {
					?>
						<option value="<?php echo $data['id']?>" <?php echo isset($instructor) && $instructor == $data['id'] ? "selected" : "" ?>><?php echo $data['firstname']." ".$data['middlename']." ".$data['lastname']?></option>
					<?php				
						}
					}
				?>
			</select>
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('#manage-class').submit(function(e){
			e.preventDefault();
			start_load();

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
					url:'ajax.php?action=save_class',
					method:'POST',
					data:$(this).serialize(),
					success:function(resp){
						if(resp == 1){
							alert_toast("Data successfully saved.","success");
							setTimeout(function(){
								location.reload()    
							},750)
						}else if(resp == 2){
							$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Class already exist.</div>')
							end_load()
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