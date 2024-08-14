<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header">
			<div class="card-tools">
				<a href="excel/Sample-Faculty-Format.xlsx" download="Sample-Faculty-Format.xlsx" class="btn btn-sm btn-default btn-flat border-primary"><i class="fa fa-download"></i> Download Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary new_faculty_excel" href="javascript:void(0)"><i class="fa fa-plus"></i> Add Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary new_faculty" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="50%">
					<col width="25%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Name</th>
						<th class="text-center">Department</th>
						<th class="text-center">Level</th>
						<th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM table_faculty order by id asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td><b><?php echo $row['firstname']." ".$row['middlename']." ".$row['lastname'] ?></b></td>
						<td><b><?php echo $row['department'] ?></b></td>
						<td><b><?php echo $row['level'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
							<div class="dropdown-menu" style="">
		                      <a class="dropdown-item manage_faculty" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_faculty" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
		$('.new_faculty').click(function(){
			uni_modal("New faculty","contents/faculty/manage_faculty.php")
		})
		$('.new_faculty_excel').click(function(){
			uni_modal("New faculty","contents/faculty/manage_faculty_excel.php")
		})
		$('.manage_faculty').click(function(){
			uni_modal("Manage faculty","contents/faculty/manage_faculty.php?id="+$(this).attr('data-id'))
		})
		$('.delete_faculty').click(function(){
		_conf("Are you sure to delete this faculty?","delete_faculty",[$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_faculty($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_faculty',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload();
					},1500);
				}
				else{
					alert_toast(resp,'error')
					setTimeout(function(){
						location.reload();
					},1500);
				}
			}
		})
	}
</script>