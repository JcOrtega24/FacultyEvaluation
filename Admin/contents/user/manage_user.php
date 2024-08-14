<?php
	include '../../db_connect.php';
	if(isset($_GET['id'])){
		$qry = $conn->query("SELECT * FROM table_users where id={$_GET['id']}")->fetch_array();
		foreach($qry as $k => $v){
			$$k = $v;
		}
	}
?>
<div class="container-fluid">
	<form action="" id="manage-user">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="form-group">
			<label for="" class="control-label">First Name</label>
			<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Last Name</label>
			<input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
		</div>
		<div class="form-group">
			<label class="control-label">Email</label>
			<input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
			<small id="#msg"></small>
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

		$('#manage-user').submit(function(e){
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
					url:'ajax.php?action=save_user',
					method:'POST',
					data:$(this).serialize(),
					success:function(resp){
						if(resp == 1){
							alert_toast("Data successfully saved.","success");
							setTimeout(function(){
								location.reload()	
							},750)
						}else if(resp == 2){
							$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
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