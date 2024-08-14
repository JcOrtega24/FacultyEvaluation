<?php 
if (!empty($_GET['id'])) {
	$id = preg_replace("/[^a-zA-Z0-9]/", "", $_GET["id"]);   
	$id  = (int)($id);

	$stmt_class = $conn->prepare("SELECT * FROM table_schedule WHERE id = ?");
    $stmt_class->bind_param("i", $id);
    $stmt_class->execute();
    $result_class = $stmt_class->get_result();
    $classSched = $result_class->fetch_assoc();
    $stmt_class->close();
	
	if($classSched != 0){
		$schoolyear = $conn->query("SELECT * FROM table_schoolyear where isDefault='1'")->fetch_assoc();
		$classEval_status = $classSched['eval_status'];
?>

<div style="width: 90%; margin-left: 5%;">
	<div class="table-main card card-outline">
		<form id="manage-evaluation" novalidate>	
			<div class="card-body">			
				<h4 class="text-center"><b>Teacher Evaluation Form</b></h4>
				<?php
					$class = $conn->query("SELECT * FROM table_class where id='{$classSched['classes_id']}'")->fetch_assoc();
				?>
				<div class="form-group">
				<?php
					$faculty = $conn->query("SELECT * FROM table_faculty where id='{$class['instructor']}'")->fetch_assoc();
				?>	
				<label for="" class="control-label">Teacher's Name: <?php echo $faculty['firstname']." ".$faculty['middlename']." ".$faculty['lastname'] ?></label>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<?php
								$subject = $conn->query("SELECT * FROM table_subject where id='{$class['subjcode']}'")->fetch_assoc();
							?>
							<label for="" class="control-label">Subject Code: <?php echo $subject['code'] ?></label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Time/Day: <?php echo $class['time']." ".$class['days'] ?></label>
						</div>
					</div>
				</div>
				<div class="row">
					<?php
						$schoolyear = $conn->query("SELECT * FROM table_schoolyear where id='{$class['schoolyear']}'")->fetch_assoc();
					?>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Campus: <?php echo $class['branch']?></label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">School Year: <?php echo $schoolyear['schoolyear'] ?></label>
						</div>
					</div>
				</div>
				<fieldset>
					<p>Directions: Please rate your Teacher on the indicated qualitites by selecting answers which corresponds to your choice based on the criteria</p>
					<p>4 = Very Satisfactory, 3 = Satisfactory, 2 = Unsatisfactory, 1 = Very Unsatisfactory</p>
				</fieldset>
				<div class="clear-fix mt-2"></div>			
			</div>
			<?php 
				$i = 0;
				$q_arr = array();
				$criteria = $conn->query("SELECT * FROM criteria_list WHERE education_level='{$_SESSION['login_level']}' ORDER BY ABS(id) ASC");
				while($crow = $criteria->fetch_assoc()):
					if($crow['type'] == "Rate"):
			?>
				<table class="table table-condensed">
					<thead>
						<tr class="bg-gradient-primary">
							<th width="70%"><b><?php echo $crow['criteria'] ?></b></th>
							<th class="text-center">5</th>
							<th class="text-center">4</th>
							<th class="text-center">3</th>
							<th class="text-center">2</th>
							<th class="text-center">1</th>
						</tr>
					</thead>
					<tbody class="tr-sortable">
					<?php 
						$questions = $conn->query("SELECT * FROM questionnaire_list WHERE criteria='{$crow['id']}' AND education_level='{$_SESSION['login_level']}' ORDER BY ABS(id) ASC");
						while($row = $questions->fetch_assoc()):
							$q_arr[$row['id']] = $row;
							$i++;
					?>
							<tr class="bg-white">
								<td width="70%">
									<?php echo $i.". ".$row['questiondesc'] ?>
									<input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
									<input type="hidden" name="criteria[<?php echo $row['id'] ?>]" value="<?php echo $row['criteria'] ?>">
								</td>
								<?php for($c=5; $c>=1; $c--): ?>
									<td class="text-center" style="padding-left: 19px">
										<div class="icheck-success d-inline">
											<input type="radio" required name="rate[<?php echo $row['id'] ?>]" <?php //echo $c == 1 ? "checked" : '' ?> id="qradio<?php echo $row['id'].'_'.$c ?>" value="<?php echo $c ?> ">
											<label for="qradio<?php echo $row['id'].'_'.$c ?>">
											</label>
										</div>
									</td>
								<?php endfor; ?>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
				<?php else: ?>
					<?php 
						$questions = $conn->query("SELECT * FROM questionnaire_list WHERE criteria='{$crow['id']}' AND education_level='{$_SESSION['login_level']}' ORDER BY ABS(id) ASC ");
						while($row = $questions->fetch_assoc()):
							$q_arr[$row['id']] = $row;
							$i++;
					?>
					<table class="table table-condensed">
						<thead>
							<tr class="bg-gradient-primary">
								<th><b><?php echo $row['questiondesc'] ?></b></th>
								<input type="hidden" name="cid[]" value="<?php echo $row['id'] ?>">
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><textarea type="text" rows="3" name="response[<?php echo $row['id'] ?>]" class="form-control"></textarea></td>
							</tr>
						</tbody>
					</table>
					<?php endwhile; ?>
				<?php endif; ?>
			<?php endwhile; ?>

			<input type="hidden" name="schoolyear_id" value="<?php echo $schoolyear['id'] ?>">
			<input type="hidden" name="class_id" value="<?php echo $classSched['classes_id'] ?>">
			<input type="hidden" name="faculty_id" value="<?php echo $faculty['id'] ?>">	
			<input type="hidden" name="studentclass_id" value="<?php echo $id?>">
			<input type="hidden" name="level" value="<?php echo $class['level'] ?>">
			<input type="hidden" name="department" value="<?php echo $class['department'] ?>">
			<input type="hidden" name="branch" value="<?php echo $class['branch']?>">

			<div class="card-header">
				<div class="card-tools">
					<button type="submit" class="btn btn-primary">Submit Evaluation</button>
				</div>
			</div>
		</form>
	</div>
</div>

<?php
		}
	}
?>
<style>
	.table-main {
		position: relative;
		overflow: auto;
		margin-top: 10px;
		max-height: 470px; 
		scrollbar-width: none;
	}

	.table-main thead {
		position: sticky;
		top: 0;
		z-index: 500;
	}

	.table-main::-webkit-scrollbar {
		display: none; 
	}
</style>
<script>
	$(document).ready(function(){
		var schoolyearStatus = <?php echo json_encode($schoolyear['status']); ?>;
		var classEvalStatus = <?php echo json_encode($classEval_status); ?>;
		
		if(schoolyearStatus == 0){
			uni_modal("Information","contents/not_started.php");
		} else if(schoolyearStatus == 1){
			if(classEvalStatus == 1)
				uni_modal("Information","contents/done.php");
		} else if(schoolyearStatus == 2){
			uni_modal("Information","contents/closed.php");
		}
	});

	// Optional JavaScript to add compatibility with older browsers
	// Check if the browser supports CSS position: sticky
	if (!CSS.supports('position', 'sticky')) {
		var tableHeader = document.querySelector('.table-main thead');
		var tableBody = document.querySelector('.table-main tbody');

		tableBody.addEventListener('scroll', function() {
			tableHeader.style.transform = 'translateY(' + tableBody.scrollTop + 'px)';
		});
	}

	//Screen Auto adjustment
	function adjustMaxHeight() {
		var windowWidth = window.innerWidth;
		var margin = 0; 
		var navbar = 0;

		if (windowWidth < 360) {
			var margin = 25;
			var navbar = 35;
		}
		else if (windowWidth < 500) {
			var margin = 25;
			var navbar = 45;
		}
		else if (windowWidth < 620) {
			var margin = 25;
			var navbar = 55;
		}
		else if (windowWidth < 840) {
			var margin = 25;
			var navbar = 65;
		}
		else{
			var margin = 25;
			var navbar = 80;
		}

		var windowHeight = window.innerHeight;
		var maxHeight = windowHeight - (navbar + margin);
		document.querySelector('.table-main').style.maxHeight = maxHeight + 'px';
	}

	// Call the function initially and on window resize
	adjustMaxHeight();
	window.addEventListener('resize', adjustMaxHeight);

	//Validation and Ajax Query
	$(document).ready(function(){
		$('#manage-evaluation').submit(function(e){
			e.preventDefault();
			start_load();

			if($(this).find('.alert-danger').length > 0)
				$(this).find('.alert-danger').remove();

			var isValid = true;
			var firstInvalidField = null; // Store the first invalid field

			var inputs = this.querySelectorAll('input[required]');

			inputs.forEach(function(input) {
				if (!input.checkValidity()) {
					isValid = false;
					if (firstInvalidField === null) {
						firstInvalidField = input; // Store the first invalid field
					}

					var feedbackMessage = document.createElement('div');
					feedbackMessage.innerHTML = 'Please fill out this field.';
					feedbackMessage.classList.add('invalid-feedback');
					input.parentNode.appendChild(feedbackMessage);
				}
			});

			if (isValid) {
				// Continue with form submission
				$.ajax({
					url:'ajax.php?action=save_evaluation',
					method:'POST',
					data:$(this).serialize(),
					success:function(resp){
						if(resp == 1){
							alert_toast("Data successfully saved.","success");
							setTimeout(function(){
								location.reload()
							},1750)
							location.href ='index.php?page=home';
						}
					}
				});
			} else {
				// Scroll to the first invalid field
				if (firstInvalidField !== null) {
					firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
					$(firstInvalidField).focus(); // Focus on the first invalid field
				}
				end_load();
			}

			$(this).addClass('was-validated');
		});

		$('#manage-evaluation input[required]').on('input', function() {
			$(this).removeClass('is-invalid');
			$(this).siblings('.warning-symbol').remove();
			$(this).siblings('.invalid-feedback').remove();
		});
	});
</script>