<?php
	$stdid = $_SESSION['login_id'];

	$schoolyear = $conn->query("SELECT * FROM table_schoolyear where isDefault='1'")->fetch_assoc();
	$schlyear = $schoolyear['id'];
	$evalstatus = $schoolyear['status'];

	$class = $conn->query("SELECT * FROM table_schedule where student_id='$stdid' and schoolyear='$schlyear'");
?>
<div class="col-lg-12"  style="margin-top: 10px">
    <div class="card card-outline">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-bordered" id="list">
                    <colgroup>
                        <col width="15%">
                        <col width="48%">
                        <col width="17%">
                        <col width="10%">
                        <col width="10%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">Subject Code</th>
                            <th class="text-center">Instructor</th>
                            <th class="text-center">Day/Time</th>
                            <th class="text-center">Room</th>
                            <th class="text-center">Evaluation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php			
                            if ($class->num_rows > 0) {
                                while ($schedule = mysqli_fetch_array($class)) {   
                                    $qry = $conn->query("SELECT * FROM table_class where id='{$schedule['classes_id']}' order by id asc ");
                                    while($row = $qry->fetch_assoc()):
                                        $subject = $conn->query("SELECT * FROM table_subject where id='{$row['subjcode']}'");
                                        if ($subject->num_rows > 0) {
                                            while ($data = mysqli_fetch_array($subject)) {
                                                echo  "<th>".$data['code']."</th>";
                                            }
                                        }
                                        
                                        $faculty = $conn->query("SELECT * FROM table_faculty where id='{$row['instructor']}'");
                                        if ($faculty->num_rows > 0) {
                                            while ($data = mysqli_fetch_array($faculty)) {
                                                echo  "<th>".$data['firstname']." ".$data['middlename']." ".$data['lastname']."</th>";
                                            }
                                        }
                        ?>
                                        <th><?php echo $row['time']." ".substr($row['days'], 0, 3) ?></th>
                                        <th><?php echo $row['room'] ?></th>
                                        <td class="text-center">		
                                            <?php if($evalstatus == 0): ?>
                                                <span class="badge badge-secondary">Not yet Started</span>
                                            <?php elseif($evalstatus == 1): ?>
                                                <?php if($schedule['eval_status'] == 0): ?>
                                                    <?php if($_SESSION['login_level'] == "Elementary"){?>
                                                        <a class="btn btn-default btn-sm btn-flat border-info wave-effect text-info evaluate_class" href="./index.php?page=evaluate_elementary&id=<?php echo $schedule['id'] ?>">Evaluate</a>
                                                    <?php } elseif($_SESSION['login_level'] == "Highschool"){ ?>
                                                        <a class="btn btn-default btn-sm btn-flat border-info wave-effect text-info evaluate_class" href="./index.php?page=evaluate_highschool&id=<?php echo $schedule['id'] ?>">Evaluate</a>
                                                    <?php } else{ ?>
                                                        <a class="btn btn-default btn-sm btn-flat border-info wave-effect text-info evaluate_class" href="./index.php?page=evaluate_college&id=<?php echo $schedule['id'] ?>">Evaluate</a>
                                                    <?php }?>
                                                <?php else: ?>
                                                    <span class="badge badge-success" style="font-size: 13px">Done</span>
                                                <?php endif; ?>
                                            <?php elseif($evalstatus == 2): ?>
                                                <span class="badge badge-secondary">Closed</span>
                                            <?php endif; ?>					
                                        </td>
                                    </tr>	
                        <?php 
                                    endwhile; 
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="msg" class="form-group"></div>
<script>
	$(document).ready(function(){
		$('#list').dataTable();
	});
</script>