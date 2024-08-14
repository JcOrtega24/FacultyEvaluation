<?php 
  include('db_connect.php'); 
  $qry = $conn->query("SELECT * FROM table_schoolyear where isDefault='1'")->fetch_assoc();
	$schlyear = $qry['id'];
?>
<div class="row">
  <div class="col-12 col-sm-7 col-md-4">
    <div class="small-box bg-light shadow-sm border"> 
      <div class="inner">
        <?php 
          $query = "SELECT eval_status, count(*) as number FROM table_schedule 
                    WHERE classes_id IN (SELECT id FROM table_class WHERE level = 'Elementary' AND schoolyear = '$schlyear') 
                    GROUP BY eval_status";  
          $result = $conn->query($query);
        ?>    
        <script type="text/javascript">  
          google.charts.load('current', {'packages':['corechart']});  
          google.charts.setOnLoadCallback(drawChart);  
          function drawChart(){  
              var data = google.visualization.arrayToDataTable([  
                  ['eval_status', 'number', { role: 'style' }],  
                  <?php  
                  while($row = mysqli_fetch_array($result)){  
                      if($row['eval_status'] == "0"){
                          $status = "Not yet taken";
                          $color = "red";
                      }
                      else if($row['eval_status'] == "1"){
                          $status = "Completed";
                          $color = "blue";
                      }
                      echo "['".$status."', ".$row["number"].", '".$color."'],";  
                  }  
                  ?>  
              ]);  
              var options = {  
                  'legend':'bottom',
                  'title':'Evaluation Status in Elementary',
                  'colors': ['red', 'blue'] // Define custom colors for each status
              };  
              var chart = new google.visualization.PieChart(document.getElementById('piechart'));  
              chart.draw(data, options);  
          }  
        </script>
        <div id="piechart" style=""></div>  
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <div class="small-box bg-light shadow-sm border">
      <div class="inner">
        <?php 
          $query = "SELECT eval_status, count(*) as number FROM table_schedule 
                    WHERE classes_id IN (SELECT id FROM table_class WHERE level = 'Highschool' AND schoolyear = '$schlyear') 
                    GROUP BY eval_status";  
          $result = $conn->query($query);
        ?>    
        <script type="text/javascript">  
          google.charts.load('current', {'packages':['corechart']});  
          google.charts.setOnLoadCallback(drawChart);  
          function drawChart(){  
              var data = google.visualization.arrayToDataTable([  
                  ['eval_status', 'number', { role: 'style' }],  
                  <?php  
                  while($row = mysqli_fetch_array($result)){  
                      if($row['eval_status'] == "0"){
                          $status = "Not yet taken";
                          $color = "red";
                      }
                      else if($row['eval_status'] == "1"){
                          $status = "Completed";
                          $color = "blue";
                      }
                      echo "['".$status."', ".$row["number"].", '".$color."'],";  
                  }  
                  ?>  
              ]);  
              var options = {  
                  'legend':'bottom',
                  'title':'Evaluation Status in Highschool',
                  'colors': ['red', 'blue'] // Define custom colors for each status
              };  
              var chart = new google.visualization.PieChart(document.getElementById('piechart1'));  
              chart.draw(data, options);  
          }  
        </script>
        <div id="piechart1" style=""></div>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-md-4">
    <div class="small-box bg-light shadow-sm border">
      <div class="inner">
        <?php 
          $query = "SELECT eval_status, count(*) as number FROM table_schedule 
                    WHERE classes_id IN (SELECT id FROM table_class WHERE level = 'College' AND schoolyear = '$schlyear') 
                    GROUP BY eval_status";  
          $result = $conn->query($query);
        ?>    
        <script type="text/javascript">  
          google.charts.load('current', {'packages':['corechart']});  
          google.charts.setOnLoadCallback(drawChart);  
          function drawChart(){  
              var data = google.visualization.arrayToDataTable([  
                  ['eval_status', 'number', { role: 'style' }],  
                  <?php  
                  while($row = mysqli_fetch_array($result)){  
                      if($row['eval_status'] == "0"){
                          $status = "Not yet taken";
                          $color = "red";
                      }
                      else if($row['eval_status'] == "1"){
                          $status = "Completed";
                          $color = "blue";
                      }
                      echo "['".$status."', ".$row["number"].", '".$color."'],";  
                  }  
                  ?>  
              ]);  
              var options = {  
                  'legend':'bottom',
                  'title':'Evaluation Status in College',
                  'colors': ['red', 'blue'] // Define custom colors for each status
              };  
              var chart = new google.visualization.PieChart(document.getElementById('piechart2'));  
              chart.draw(data, options);  
          }  
        </script>  
        <div id="piechart2" style=""></div>
      </div>
    </div>
  </div>
</div>
