<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default btn-flat border-primary new_schoolyear" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="25%">
					<col width="25%">
					<col width="15%">
					<col width="15%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Year</th>
						<th>Semester</th>
						<th>System Default</th>
						<th>Evaluation Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM table_schoolyear order by abs(schoolyear) desc,abs(semester) desc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['schoolyear'] ?></b></td>
						<td><b><?php echo $row['semester'] ?></b></td>
						<td class="text-center">
							<?php if($row['isDefault'] == 0): ?>
								<button type="button" class="btn btn-secondary bg-gradient-secondary col-sm-4 btn-flat btn-sm px-1 py-0 make_default" data-id="<?php echo $row['id'] ?>">No</button>
							<?php else: ?>
								<button type="button" class="btn btn-primary bg-gradient-primary col-sm-4 btn-flat btn-sm px-1 py-0">Yes</button>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<?php if($row['status'] == 0): ?>
								<span class="badge badge-secondary">Not yet Started</span>
							<?php elseif($row['status'] == 1): ?>
								<span class="badge badge-success">Starting</span>
							<?php elseif($row['status'] == 2): ?>
								<span class="badge badge-primary">Closed</span>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
							<div class="dropdown-menu" style="">
		                      <a class="dropdown-item manage_schoolyear" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
							  <?php if($row['isDefault'] == 0): ?>
								<div class="dropdown-divider"></div>
		                      	<a class="dropdown-item delete_schoolyear" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
							  <?php endif; ?>
							</div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.new_schoolyear').click(function(){
			uni_modal("New school year","contents/schoolyear/manage_schoolyear.php")
		})
		$('.manage_schoolyear').click(function(){
			uni_modal("Manage school year","contents/schoolyear/manage_schoolyear.php?id="+$(this).attr('data-id'))
		})
		$('.delete_schoolyear').click(function(){
		_conf("Are you sure to delete this school year?","delete_schoolyear",[$(this).attr('data-id')])
		})
		$('.make_default').click(function(){
		_conf("Are you sure to make this school year as the system default?\nIf you set this to default, the associated records will be shown by default.\nOther records can still be manually checked out.","make_default",[$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_schoolyear($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_schoolyear',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	function make_default(id){
		start_load(); // Assuming start_load() shows a loading indicator

		$.ajax({
			url: 'ajax.php?action=make_default',
			method: 'POST',
			data: {id: id},
			success: function(resp){
				if(resp == 1){
					alert_toast("Default School Year Updated", 'success');
					setTimeout(function(){
						location.reload();
					}, 1500);
				} else if(resp == 2){
					alert_toast("Failed to update students", 'error'); // Show error message
					end_load(); // Assuming end_load() hides the loading indicator
				} else if(resp == 3){
					alert_toast("Failed to fecth students", 'error'); // Show error message
					end_load(); // Assuming end_load() hides the loading indicator
				} else if(resp == 4){
					alert_toast("Failed to update default school year", 'error'); // Show error message
					end_load(); // Assuming end_load() hides the loading indicator
				}
			},
			error: function(xhr, status, error) {
				alert_toast("An error occurred while updating default school year", 'error'); // Show error message
				end_load(); // Assuming end_load() hides the loading indicator
			}
		});
	}

</script>