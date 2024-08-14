<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header" style="display: flex; justify-content: flex-end;">
			<div class="card-tools">
				<a href="excel/Sample-Questionnaire-Format.xlsx" download="Sample-Questionnaire-Format.xlsx" class="btn btn-sm btn-default btn-flat border-primary"><i class="fa fa-download"></i> Download Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary new_question_excel" href="javascript:void(0)"><i class="fa fa-plus"></i> Add Excel</a>
				<a class="btn btn-sm btn-default btn-flat border-primary new_question" href="javascript:void(0)"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="45%">
					<col width="25%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">Question Description</th>
						<th class="text-center">Criteria</th>
						<th class="text-center">Level</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT * FROM questionnaire_list order by id asc ");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['questiondesc'] ?></b></td>
						<td class="text-center"><b>
							<?php 
								$criteria = $conn->query("SELECT * FROM criteria_list WHERE id = '{$row['criteria'] }'")->fetch_assoc();;

								echo $criteria['criteria'];
							?></b>
						</td>
						<td class="text-center"><b><?php echo $row['education_level'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
							<div class="dropdown-menu" style="">
		                      <a class="dropdown-item manage_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_question" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
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
		$('.new_question').click(function(){
			uni_modal("New Question","contents/questionnaire/manage_questionnaire.php")
		})
		$('.new_question_excel').click(function(){
			uni_modal("New Question","contents/questionnaire/manage_question_excel.php")
		})
		$('.manage_question').click(function(){
			uni_modal("Manage Question","contents/questionnaire/manage_questionnaire.php?id="+$(this).attr('data-id'))
		})
		$('.delete_question').click(function(){
			_conf("Are you sure to delete this question?","delete_question",[$(this).attr('data-id')])
		})
		$('#list').dataTable()
	})
	function delete_question($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_question',
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