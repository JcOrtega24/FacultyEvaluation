<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header" style="display: flex; justify-content: flex-end;">
			<div class="card-tools">	
				<a href="excel/Sample-Subject-Format.xlsx" download="Sample-Subject-Format.xlsx" class="btn btn-sm btn-default btn-flat border-primary"><i class="fa fa-download"></i> Download Excel</a>			
				<a class="btn btn-sm btn-default btn-flat border-primary new_subject_excel" href="javascript:void(0)"><i class="fa fa-plus"></i> Add Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary new_subject" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="33%">
					<col width="17%">
					<col width="40%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">Level</th>
						<th class="text-center">Subject Code</th>
						<th class="text-center">Subject Name</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM table_subject order by code asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<td><b><?php echo $row['level'] ?></b></td>
						<td><b><?php echo $row['code'] ?></b></td>
						<td><b><?php echo $row['subject'] ?></b></td>
						<td class="text-center">
						  	<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
							<div class="dropdown-menu" style="">
		                      <a class="dropdown-item manage_subject" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_subject" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
		$('.new_subject').click(function(){
			uni_modal("New subject","contents/subject/manage_subject.php")
		})
		$('.new_subject_excel').click(function(){
			uni_modal("New subject","contents/subject/manage_subject_excel.php")
		})
		$('.manage_subject').click(function(){
			uni_modal("Manage subject","contents/subject/manage_subject.php?id="+$(this).attr('data-id'))
		})
		$('.delete_subject').click(function(){
		_conf("Are you sure to delete this subject?","delete_subject",[$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_subject($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_subject',
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