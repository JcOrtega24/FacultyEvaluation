<?php include'db_connect.php' 
?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header">
			<div class="card-tools">
				<a href="excel/Sample-Class-Format.xlsx" download="Sample-Class-Format.xlsx" class="btn btn-sm btn-default btn-flat border-primary"><i class="fa fa-download"></i> Download Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary new_class_excel" href="javascript:void(0)"><i class="fa fa-plus"></i> Add Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary new_class" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="15%">
					<col width="35%">
					<col width="10%">
					<col width="15%">
					<col width="10%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Subject Code</th>
						<th class="text-center">Instructor</th>
						<th class="text-center">Day</th>
						<th class="text-center">Time</th>
						<th class="text-center">Room</th>
						<th class="text-center"></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$qry = $conn->query("SELECT * FROM table_schoolyear where isDefault='1'")->fetch_assoc();
					$schlyear = $qry['id'];

					$i = 1;
					$qry = $conn->query("SELECT * FROM table_class where schoolyear='$schlyear' order by id asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<?php
							$subject = $conn->query("SELECT * FROM table_subject where id='{$row['subjcode']}'");
							if ($subject->num_rows>0) {
								while ($data = mysqli_fetch_array($subject)) {
									echo  "<th>".$data['code']."</th>";
								}
							}
							
							$faculty = $conn->query("SELECT * FROM table_faculty where id='{$row['instructor']}'");
							if ($faculty->num_rows>0) {
								while ($data = mysqli_fetch_array($faculty)) {
									echo  "<th>".$data['firstname']." ".$data['middlename']." ".$data['lastname']."</th>";
								}
							}
						?>
						<th><?php echo $row['days'] ?></th>
						<th><?php echo $row['time'] ?></th>
						<th><?php echo $row['room'] ?></th>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      	Action
		                    </button>
							<div class="dropdown-menu" style="">
								<a class="dropdown-item" href="./index.php?page=add_student&id=<?php echo $row['id'] ?>">Add Student</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item manage_class" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
		                      	<div class="dropdown-divider"></div>
		                      	<a class="dropdown-item delete_class" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
		$('#list').dataTable()
		$('.new_class').click(function(){
			uni_modal("New class","contents/class/manage_class.php")
		})
		$('.new_class_excel').click(function(){
			uni_modal("New class","contents/class/manage_class_excel.php")
		})
		$('.manage_class').click(function(){
			uni_modal("Manage class","contents/class/manage_class.php?id="+$(this).attr('data-id'))
		})
		$('.delete_class').click(function(){
		_conf("Are you sure to delete this class?","delete_class",[$(this).attr('data-id')])
		})
	})
	function delete_class($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_class',
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