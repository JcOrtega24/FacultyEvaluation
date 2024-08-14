<?php
include 'db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM table_class where id={$_GET['id']}")->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
	$level = $qry['level'];
}

$_SESSION['classID'] = $_GET['id'];
?>
<div class="col-lg-12">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<div class="card-tools">
				<a href="excel/Sample-Add-Student-To-Class-Format.xlsx" download="Sample-Add-Student-To-Class-Format.xlsx" class="btn btn-sm btn-default btn-flat border-primary"><i class="fa fa-download"></i> Download Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary add_student_excel" href="javascript:void(0)"><i class="fa fa-plus"></i> Add Excel</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="15%">
					<col width="45%">
					<col width="30%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Student ID</th>
						<th class="text-center">Name</th>
						<th class="text-center">Level</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php			
					$i = 1;
					$qry = $conn->query("SELECT * FROM table_student WHERE level = '".$level."' ORDER BY id ASC");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td><b><?php echo $row['student_id'] ?></b></td>
						<td><b><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname'] ?></b></td>
						<td><b><?php echo $row['level'] ?></b></td>
						<td class="text-center">
							<?php 
								$chk = $conn->query("SELECT * FROM table_schedule where student_id = '{$row['id']}' and classes_id = '{$_GET['id']}' ")->num_rows;
								if($chk > 0){
							?>
								<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info" aria-expanded="true">
									<a class="remove_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Remove</a>
								</button>
							<?php
								}else{
							?>
								<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info" aria-expanded="true">
									<a class="add_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Add</a>
								</button>
							<?php		
								}
							?>							
						</td>
					</tr>	
				<?php endwhile;?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div id="msg" class="form-group"></div>
<script>
$(document).ready(function(){
    $('#list').dataTable();

    // Event delegation for "Remove" button
    $(document).on('click', '.remove_student', function(){
        _conf("Are you sure to remove this student?", "remove_student", [$(this).attr('data-id')]);
    });

    // Event delegation for "Add" button
    $(document).on('click', '.add_student', function(){
        _conf("Are you sure to add this student?", "add_student", [$(this).attr('data-id')]);
    });

	$('.add_student_excel').click(function(){
		uni_modal("Add student to a class","contents/class/manage_schedule_excel.php")
	});
});

function remove_student($id){
    start_load();
    $.ajax({
        url: 'ajax.php?action=remove_student',
        method: 'POST',
        data: {id: $id},
        success: function(resp){
            if(resp == 1){
                alert_toast("Data successfully deleted", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                alert_toast(resp, 'error');
                setTimeout(function(){
                    location.reload();
                }, 500);
            }
        }
    });
}

function add_student($id){
    start_load();
    $.ajax({
        url: 'ajax.php?action=add_student',
        method: 'POST',
        data: {id: $id},
        success: function(resp){
            if(resp == 1){
                alert_toast("Data successfully saved", "success");
                setTimeout(function(){
                    location.reload();  
                }, 500);
            }
        }
    });
}

</script>