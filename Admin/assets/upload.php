<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style = "background-color: #131C70; font-family: 'assistant';">
    
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">

               <?php
                /*if(isset($_SESSION['message']))
                {
                    echo "<h4>".$_SESSION['message']."</h4>";
                    unset($_SESSION['message']);
                }*/
                ?> 

                <div class="card">
                    <div class="card-header">
                        <h4>Upload your Grade Excel file here: (xlsx)</h4>
                    </div>
                    <div class="card-body">

                        <form method="POST" enctype="multipart/form-data">

                            <input type="file" name="import_file" class="form-control" />
                            <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
session_start();
require_once"Config.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$connection = mysqli_connect("localhost","root","");
$db = mysqli_select_db($connection, 'cs320');
#initial query
$qry= "SELECT * from tblgrd order by ID desc";
$result = mysqli_query($connection,$qry);

if ($result) {
  $row = mysqli_fetch_array($result);
if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
    #file extensions
    $allowed_ext = ['xls','csv','xlsx'];
    #checks if file is within the allowe extensions
    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        # Remove the first row (header)
        array_shift($data);
        #retrieval of data
        foreach($data as $row)
        {
                $StudNum = $row['0'];
                $name = $row['1'];
                $subCode = $row['2'];
                $subName = $row['3'];
                $sem = $row['4'];
                $Units = $row['5'];
                $prelim = $row['6'];
                $midterm = $row['7'];
                $semis = $row['8'];
                $finals = $row['9'];
                $schyr = $row['10'];
                $Id = "$StudNum|$subCode|$sem|$Units";

                $query = "SELECT * FROM tblgrd WHERE ID = '$Id' AND Name = '$name' AND SY = '$schyr'";
                $result = mysqli_query($connection, $query);

                if (mysqli_num_rows($result) > 0) {
                    #Update/Overwrite Existing data
                    $query = "REPLACE INTO tblgrd(ID,StudNum,Name,SubjCode,SubjName,semester,units,prelim,midterm,semis,finals,SY) VALUES ('$Id','$StudNum','$name','$subCode','$subName','$sem','$Units','$prelim','$midterm','$semis','$finals','$schyr')";
                    $query_run = mysqli_query($connection,$query);
                    }
                else{
                #Insert if new data
                     $query = "INSERT INTO tblgrd(ID,StudNum,Name,SubjCode,SubjName,semester,units,prelim,midterm,semis,finals,SY) VALUES ('$Id','$StudNum','$name','$subCode','$subName','$sem','$Units','$prelim','$midterm','$semis','$finals','$schyr')";
                    $query_run = mysqli_query($connection,$query);
                }
            }
              if($query_run)
              {
                $_SESSION['message'] = "Successfully Imported";
                header('Location: gradeEncode.php');
                exit(0);
              }
              else
              {
                $_SESSION['message'] = "Problem during Import";
                header('Location: gradeEncode.php');
                exit(0);
              }
    }
    else
    {
        $_SESSION['message'] = "INVALID FILE TYPE!";
        header('Location: gradeEncode.php');
        exit(0);
    }
}
} else {
  echo "Error executing query: " . mysqli_error($connection);
}

?>