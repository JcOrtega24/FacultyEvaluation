<style>
	.no-spacing {
        margin: 0;
        padding: 0;
    }
	.no-bold-header {
        font-weight: normal;
    }
	.with-margin-top{
		margin-top: 30px;
	}
	.with-margin-left{
		margin-left: 20px;
	}
	.with-margin-bottom{
		margin-bottom: 10px;
	}
	.no-bold {
        font-weight: normal;
    }
	.uppercase {
		text-transform: uppercase;
	}
	label {
		margin-top: 10px;
	}
	.list-group-item:hover{
		color: black !important;
		font-weight: 700 !important;
	}
	.custom-arrow {
		list-style-type: none; /* Remove default bullet */
	}

	.custom-arrow li::before {
		content: "\21D2"; /* Unicode arrow character */
		margin-right: 5px; /* Add some space between arrow and text */
	}
	hr {
        display: block; 
        height: 1px;
        border: none; 
        border-top: 1px solid #000;
    }
	h3 {
		font-size: 1.2rem;
	}
	h5 {
		font-size: 1.1rem;
	}
	.bottom-hr {
        display: block; 
        height: 1px; 
        border: none; 
        border-top: 1px solid #000; 
        bottom: 20px; 
        width: 100%; 
		margin: 0;
		margin-bottom: 3px;
    }
    .bottom-text {
        bottom: 0;
        margin: 0; 
    }
</style>
<div class="col-lg-12 hidden">
	<div class="callout callout-info">
		<div class="d-flex w-100 justify-content-center align-items-center">
			<label for="branch">SchoolYear</label>
			<div class=" mx-2 col-md-2">
				<select name="YearSelect" id="YearSelect" class="form-control form-control-sm select2">
					<option value=""></option>
					<?php
						$schoolyear = $conn->query("SELECT * FROM table_schoolyear order by id");
						if ($schoolyear->num_rows>0) {
							while ($data = mysqli_fetch_array($schoolyear)) {
						?>
							<option value="<?php echo $data['id']?>"><?php echo $data['schoolyear']." - ".$data['semester']?></option>
						<?php				
							}
						}
					?>
				</select>
			</div>
			<label for="branchSelect" id="branchSelect-label" style="display:none">Branch</label>
			<div class=" mx-1 col-md-2" id="branchSelect-div" style="display:none">
				<select name="branchSelect" id="branchSelect" class="form-control form-control-sm select2">
					<option value=""></option>
					<option value="Main">Main</option>
					<option value="Pasay">Pasay</option>
					<option value="Malabon">Malabon</option>
					<option value="Mandaluyong">Mandaluyong</option>
					<option value="Pasig">Pasig</option>
				</select>
			</div>
			<label for="branch" id="levelSelect-label" style="display:none">Level</label>
			<div class=" mx-2 col-md-2"  id="levelSelect-div" style="display:none">
				<select name="levelSelect" id="levelSelect" class="form-control form-control-sm select2">
					<option value=""></option>
					<option value="Elementary">Elementary</option>
					<option value="Highschool">Highschool</option>
					<option value="College">College</option>
				</select>
			</div>
            <label for="branch" id="facultySelect-label" style="display:none">Faculty</label>
			<div class=" mx-2 col-md-2" id="facultySelect-div" style="display:none">
				<select name="facultySelect" id="facultySelect" class="form-control form-control-sm select2">
                    <option value=""></option>
				</select>
			</div>
		</div>
	</div>
	<!--div class="row">
		<div class="col-md-12 mb-1">
			<div class="d-flex justify-content-end w-100">
				<button class="btn btn-sm btn-success bg-gradient-success" style="display:none" id="print-btn"><i class="fa fa-print"></i> Print</button>
			</div>
		</div>
	</div-->
	<div class="callout callout-info" style="display:none" id="report-div">
		<div class="w-100 justify-content-center" id="printable">
			<div class="header-print">
				<h3 class="text-center no-spacing">Arellano University</h3>	
				<h3 class="text-center no-spacing">Office of the Student Personnel Services</h3>
				<i><h5 class="text-center no-spacing no-bold-header">2600 Legarda Street, Sampaloc, Manila</h5>
				<h5 class="text-center no-spacing no-bold-header">734-7371 loc 207</h5>	
				<h5 class="text-center no-spacing no-bold-header">www.arellano.edu.ph</h5></i>
				<hr class="header-hr">		
			</div>
			<div id="evaluationContainer">
				
			</div>
			<br>
			<div class="footer-print">
				<hr class="footer-hr">
				<i><h5 class="no-spacing">Office of the Student Personnel Services</h5></i>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#YearSelect').change(function(){
			$('#branchSelect-label').show();
			$('#branchSelect-div').show();
		});
		$('#branchSelect').change(function(){
			$('#levelSelect-label').show();
			$('#levelSelect-div').show();
		});

		$('#levelSelect').change(function(){
			$('#facultySelect-div').show(); 
			$('#facultySelect-label').show(); 
		});

		$('#facultySelect').change(function(){
			$('#print-btn').show();
			$('#report-div').show();
		});

		if ($('#facultySelect').val() > 0) {
			$('#print-btn').show();
		}
	});

	$('#print-btn').click(function(){
		start_load()
		var ns =$('noscript').clone()
		var content = $('#printable').html()
		ns.append(content)
		var nw = window.open("Report","_blank","width=900,height=700")
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
			nw.close()
			end_load()
		},750)
	});

	//Function for displaying the report
	document.addEventListener("DOMContentLoaded", function() {
		$('#YearSelect, #branchSelect, #levelSelect, #facultySelect').change(function() {
			var selectedYear = $('#YearSelect').val();
			var selectedFaculty = $('#facultySelect').val();
			var selectedBranch = $('#branchSelect').val();
            var selectedLevel = $('#levelSelect').val();

			displayTables(selectedYear, selectedBranch, selectedLevel, selectedFaculty);
		});

		// Function to make AJAX request and display tables
		function displayTables(selectedYear, selectedBranch, selectedLevel, selectedFaculty) {
			var evaluationContainer = document.getElementById("evaluationContainer");

			var xhr = new XMLHttpRequest();
			xhr.open("POST", "ajax.php?action=get_evaluation_status", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.onreadystatechange = function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						var departmentHTMLs = JSON.parse(xhr.responseText);

						// Clear previous content of the evaluationContainer
						evaluationContainer.innerHTML = "";

						departmentHTMLs.forEach(function(html) {
							evaluationContainer.innerHTML += html;
						});
					} else {
						console.error("Failed to fetch department HTMLs: " + xhr.statusText);
					}
				}
			};
			xhr.send("facultyId=" + encodeURIComponent(selectedFaculty) + "&branch=" + encodeURIComponent(selectedBranch) + "&level=" + encodeURIComponent(selectedLevel) + "&year=" + encodeURIComponent(selectedYear));
		}
	});

	//Function for setting faculty dropdown value
	document.addEventListener("DOMContentLoaded", function() {
		$('#levelSelect, #branchSelect').change(function() {
			var selectedLevel = $('#levelSelect').val();
			var selectedBranch = $('#branchSelect').val();

			$('#report-div').hide();

			populateFaculty(selectedLevel, selectedBranch);
		});

		function populateFaculty(selectedLevel, selectedBranch) {
			var facultySelect = document.getElementById("facultySelect");

			facultySelect.innerHTML = '<option value=""></option>';

			$.ajax({
				type: "POST",
				url: "ajax.php?action=get_faculty_status",
				data: { level: selectedLevel, branch: selectedBranch },
				dataType: "json", 
				success: function(faculties) { 
					faculties.forEach(function(faculty) {
						var option = document.createElement("option");
						option.value = faculty.id;
						option.text = faculty.firstname + " " + faculty.middlename + " " + faculty.lastname;
						facultySelect.appendChild(option);
					});
				},
				error: function(xhr, status, error) { 
					console.error("Error fetching faculty data: " + error);
				}
			});
		}
	});
</script>

<!-- Print Design -->
<noscript>
	<style>
		label {
			margin-top: 10px;
		}
		.list-group-item:hover{
			color: black !important;
			font-weight: 700 !important;
		}
		.custom-arrow {
			list-style-type: none;
		}
		.custom-arrow li::before {
			content: "\21D2"; /* Unicode arrow character */
			margin-right: 5px; 
		}
		.no-spacing {
            margin: 0;
            padding: 0;
        }
		.no-bold-header {
            font-weight: normal;
        }
		.with-margin-top{
			margin-top: 30px;
		}
		.with-margin-left{
			margin-left: 20px;
		}
		.with-margin-bottom{
			margin-bottom: 10px;
		}
		.no-bold {
            font-weight: normal;
        }
		.uppercase {
			text-transform: uppercase;
		}
        
		/** For table **/
		table{
			width:100%;
			border-collapse: collapse;
		}
		table.wborder tr,table.wborder td,table.wborder th{
			border:1px solid black;
			padding: 3px
		}
		table.wborder thead tr{
			/*background: #6c757d linear-gradient(180deg,#828a91,#6c757d) repeat-x!important;*/
    		color: #000;
		}
		.text-center{
			text-align:center;
		} 
		.text-right{
			text-align:right;
		} 
		.text-left{
			text-align:left;
		} 

		/** For Divs  **/
		.header-print{
			position: fixed;
			top: 0;
			margin: 0;
			width: 100%;
			z-index: 1000;
		}
		.header-hr {
            display: block; 
            height: 2px;
            border: none; 
            border-top: 2px solid #000;
        }
		.footer-print{
			position: fixed;
			bottom: 0;
			margin: 0;
			width: 100%;
			z-index: 1000;
		}
		.footer-hr {
            display: block;
			height: 2px;
            border: none; 
            border-top: 2px solid #000;
            position: fixed;
            bottom: 20px;
            width: 100%;
			margin: 0;
			z-index: 1000;
        }
		#evaluationContainer{
			margin-top: 150px;
		}
	</style>
</noscript>