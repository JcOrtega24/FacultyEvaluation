<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';

    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	//Admin login functions
	function login_admin(){
		extract($_POST);
		$qry = $this->db->query("SELECT *,concat(firstname,' ',lastname) as name FROM table_users where email = '".$email."'  and password = '".md5($password)."'  ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
					return 1;
		}	
		else{
			return 2;
		}
	}
	function logout_admin(){
		session_destroy();

		$_SESSION = array();

		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
				$params["path"], $params["domain"],
				$params["secure"], $params["httponly"]
			);
		}

		header("location: login.php");
		exit();
	}

	//Admin account functions
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
			$data .= ", password=md5('$password') ";
		}

		$check = $this->db->query("SELECT * FROM table_users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		/*if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}*/
		if(empty($id)){
			$save = $this->db->query("INSERT INTO table_users set $data");
		}else{
			$save = $this->db->query("UPDATE table_users set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function update_user(){
		extract($_POST);
		$data = "";
		$type = array("","users","faculty_list","student_list");
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','table','password')) && !is_numeric($k)){
				
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		$check = $this->db->query("SELECT * FROM {$type[$_SESSION['login_type']]} where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		/*if(isset($_FILES['img']) && $_FILES['img']['tmp_name'] != ''){
			$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";

		}*/
		if(!empty($password))
			$data .= " ,password=md5('$password') ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO {$type[$_SESSION['login_type']]} set $data");
		}else{
			echo "UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id";
			$save = $this->db->query("UPDATE {$type[$_SESSION['login_type']]} set $data where id = $id");
		}

		if($save){
			foreach ($_POST as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			/*if(isset($_FILES['img']) && !empty($_FILES['img']['tmp_name']))
					$_SESSION['login_avatar'] = $fname;*/
			return 1;
		}
	}
	function delete_user(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		if ($id <= 0) {
			return; 
		}

		$stmt = $this->db->prepare("DELETE FROM table_users WHERE id = ?");
		$stmt->bind_param("i", $id);
		$stmt->execute();

		if ($stmt->affected_rows > 0) {
			return 1;
		} else {
			return 0;
		}

		$stmt->close(); 
	}

	//School Year functions
	function save_schoolyear(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (!empty($data)) {
					$data .= ", ";
				}
				$data .= "$k='" . $this->db->real_escape_string($v) . "'";
			}
		}

		$chkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_schoolyear WHERE " . str_replace(",", " AND ", $data) . " AND id != ?");
		$chkStmt->bind_param("i", $id);
		$chkStmt->execute();
		$chkStmt->bind_result($chk);
		$chkStmt->fetch();
		$chkStmt->close();

		if ($chk > 0) {
			return 2;
		}

		$hasDefault = $this->db->query("SELECT COUNT(*) FROM table_schoolyear WHERE isDefault = 1")->fetch_row()[0];

		if ($hasDefault == 0) {
			$data .= ", isDefault = 1";
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO table_schoolyear SET $data");
		} else {
			$save = $this->db->query("UPDATE table_schoolyear SET $data WHERE id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_schoolyear(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$deleteStmt = $this->db->prepare("DELETE FROM table_schoolyear WHERE id = ?");
		$deleteStmt->bind_param("i", $id);
		$delete = $deleteStmt->execute();
		$deleteStmt->close();

		if ($delete) {
			return 1;
		} else {
			return 0;
		}
	}
	function make_default(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	
		//Set isDefault to 0 to all rows
		$updateStmt = $this->db->prepare("UPDATE table_schoolyear SET isDefault = 0");
		$update = $updateStmt->execute();
		$updateStmt->close();
	
		//Set isDefault to 1 to the selected schoolyear
		$updateStmt1 = $this->db->prepare("UPDATE table_schoolyear SET isDefault = 1 WHERE id = ?");
		$updateStmt1->bind_param("i", $id);
		$update1 = $updateStmt1->execute();
		$updateStmt1->close();
	
		//Check if udpate is successful
		if ($update && $update1) {
			// Fetch all id values from table_students
			$idQuery = $this->db->query("SELECT id FROM table_student");
			if($idQuery) {
				$ids = $idQuery->fetch_all(MYSQLI_ASSOC);
				$error = 0;

				// Update each record in table_students
				foreach ($ids as $idRow) {
					$studentId = $idRow['id'];

					// Check if schedule exists
					$checkSchedule = $this->db->prepare("SELECT * FROM table_schedule WHERE student_id = ? AND schoolyear = ?");
					$checkSchedule->bind_param("ii", $studentId, $id);
					$checkSchedule->execute();
					$checkResult = $checkSchedule->get_result();

					if($checkResult->num_rows > 0) {
						// Update status to 1 if schedule exists
						$enableAccounts = $this->db->prepare("UPDATE table_student SET status = 1 WHERE id = ?");
						$enableAccounts->bind_param("i", $studentId);
						$enable = $enableAccounts->execute();

						if($enable) {
							$enableAccounts->close();
						} else {
							$error += 1;
						}
					} else {
						// Update status to 0 if schedule doesn't exist
						$disableAccounts = $this->db->prepare("UPDATE table_student SET status = 0 WHERE id = ?");
						$disableAccounts->bind_param("i", $studentId);
						$disable = $disableAccounts->execute();

						if($disable) {
							$disableAccounts->close();
						} else {
							$error += 1;
						}
					}
				}

				if($error == 0) {
					return 1; // Success
				} else {
					return 2; // Error updating student status
				}
			} else {
				// Handle error when fetching students
				return 3; // Error fetching students
			}

		} else {
			return 4; // Error updating school year or fetching school year details
		}
	}

	//Subject functions
	function save_subject(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$code = isset($_POST['code']) ? $_POST['code'] : '';

		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'user_ids')) && !is_numeric($k)) {
				if (!empty($data)) {
					$data .= ", ";
				}
				$data .= "$k='" . $this->db->real_escape_string($v) . "'";
			}
		}

		$chkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_subject WHERE code = ? AND id != ?");
		$chkStmt->bind_param("si", $code, $id);
		$chkStmt->execute();
		$chkStmt->bind_result($chk);
		$chkStmt->fetch();
		$chkStmt->close();

		if ($chk > 0) {
			return 2; 
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO table_subject SET $data");
		} else {
			$save = $this->db->query("UPDATE table_subject SET $data WHERE id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function delete_subject(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$checkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_class WHERE subjcode = ?");
		$checkStmt->bind_param("i", $id);
		$checkStmt->execute();
		$checkStmt->bind_result($count);
		$checkStmt->fetch();
		$checkStmt->close();
	
		if ($count > 0) {
			return "Cannot delete subject. Associated class records exist.";
		}

		$deleteStmt = $this->db->prepare("DELETE FROM table_subject WHERE id = ?");
		$deleteStmt->bind_param("i", $id);
		$deleteStmt->execute();
		$deleteStmt->close();
	
		return 1;
	}
	function save_subject_excel() {
		require 'assets/plugins/PHPExcel-1.8/Classes/PHPExcel.php';

		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excelFile"])) {
			$file = $_FILES["excelFile"]["tmp_name"];
		
			$fileName = $_FILES['excelFile']['name'];
			$file_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			$allowed_ext = ['xls', 'csv', 'xlsx'];
		
			// Check if the file extension is allowed
			if (in_array($file_ext, $allowed_ext)) {
				// Load the Excel file
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$data = $objPHPExcel->getActiveSheet()->toArray();
		
					// Remove the header row
					array_shift($data);
		
					// Iterate over rows and insert/update data
					foreach ($data as $row) {
						$code = isset($row[0]) ? $row[0] : '';
						$subject = isset($row[1]) ? $row[1] : '';
						$level = isset($row[2]) ? $row[2] : '';
						$department = isset($row[3]) ? $row[3] : '';
		
						// Check if the record already exists
						$stmt = $this->db->prepare("SELECT * FROM table_subject WHERE code = ? AND subject = ? AND level = ? AND department = ?");
						$stmt->bind_param("ssss", $code, $subject, $level, $department);
						$stmt->execute();

						$result = $stmt->get_result();

						if ($result !== false && $result->num_rows > 0) {
							echo "Record already exist.\n";
						} else {
							$stmt = $this->db->prepare("INSERT INTO table_subject (code, subject, level, department) VALUES (?, ?, ?, ?)");
							$stmt->bind_param("ssss", $code, $subject, $level, $department);
		
							if ($stmt->execute()) {
								echo "Record inserted successfully.\n";
							} else {
								// Check if the error is due to duplicate entry
								if ($this->db->errno == 1062) { // Error code for duplicate entry
									echo "Error inserting record: Duplicate entry.\n";
								} else {
									echo "Error inserting record: " . $stmt->error;
								}
							}
						}
					}
				} catch (Exception $e) {
					echo "Error loading Excel file: " . $e->getMessage();
				}
			} else {
				echo "Invalid file extension.";
			}
		} else {
			echo "Invalid request.";
		}
	}

	//Classes functions
	function save_class(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$user_ids = isset($_POST['user_ids']) ? $_POST['user_ids'] : [];

		$data = [];

		foreach ($_POST as $k => $v) {
			if (!in_array($k, ['id', 'user_ids']) && !is_numeric($k)) {
				$data[] = "$k='" . $this->db->real_escape_string($v) . "'";
			}
		}

		$chkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_class WHERE " . implode(" AND ", $data) . " AND id != ?");
		$chkStmt->bind_param("i", $id);
		$chkStmt->execute();
		$chkStmt->bind_result($chk);
		$chkStmt->fetch();
		$chkStmt->close();

		if ($chk > 0) {
			return 2; 
		}

		if (!empty($user_ids)) {
			$data[] = "user_ids='" . implode(',', $user_ids) . "'";
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO table_class SET " . implode(", ", $data));
		} else {
			$save = $this->db->query("UPDATE table_class SET " . implode(", ", $data) . " WHERE id = $id");
		}

		if ($save) {
			return 1;
		}
	}
	function save_class_excel(){
		require 'assets/plugins/PHPExcel-1.8/Classes/PHPExcel.php';

		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excelFile"])) {
			$file = $_FILES["excelFile"]["tmp_name"];
		
			$fileName = $_FILES['excelFile']['name'];
			$file_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			$allowed_ext = ['xls', 'csv', 'xlsx'];
		
			if (in_array($file_ext, $allowed_ext)) {
				// Load the Excel file
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$data = $objPHPExcel->getActiveSheet()->toArray();
		
					array_shift($data);

					$query = $this->db->query("SELECT * FROM table_schoolyear where isDefault='1'")->fetch_assoc();
					$schlyear = $query['id'];
		
					foreach ($data as $row) {
						$day = isset($row[0]) ? $row[0] : '';
						$time = isset($row[1]) ? $row[1] : '';
						$room = isset($row[2]) ? $row[2] : '';
						$subjcode = isset($row[3]) ? $row[3] : '';
						$instructor = isset($row[4]) ? $row[4] : '';
						$branch = isset($row[5]) ? $row[5] : '';
						$level = isset($row[6]) ? $row[6] : '';
						$department = isset($row[7]) ? $row[7] : '';
						//$schlyear =  isset($row[7]) ? $row[7] : '';

						//Checking subject in subject table
						$stmt = $this->db->prepare("SELECT id FROM table_subject WHERE code = ?");
						$stmt->bind_param("s", $subjcode);
						$stmt->execute();

						$result = $stmt->get_result();

						if ($result->num_rows > 0) {
							$row = $result->fetch_assoc();
							$subjcode = $row['id'];
						}
						else{
							echo "Subject not found.";
							return;
						}

						//Checking faculty in faculty table
						// Assuming $instructor is an array containing multiple instructor names
						$instructorString = explode(' ', $instructor);

						$instructorFirstname = '%' . $instructorString[0] . '%';
						$instructorLastname = '%' . $instructorString[count($instructorString) - 1] . '%';


						$stmt = $this->db->prepare("SELECT id FROM table_faculty WHERE firstname LIKE ? AND  lastname LIKE ?");
						$stmt->bind_param("ss", $instructorFirstname, $instructorLastname);
						$stmt->execute();

						$result = $stmt->get_result();

						if ($result->num_rows > 0) {
							$row = $result->fetch_assoc();
							$instructor = $row['id'];
						}
						else{
							echo "Faculty not found.";
							return;
						}

						//Checking question in questionnaire table
						$stmt = $this->db->prepare("SELECT * FROM table_class WHERE days = ? AND time = ? AND room = ? AND subjcode = ? AND instructor = ? AND schoolyear = ?");
						$stmt->bind_param("ssssss", $day, $time, $room, $subjcode, $instructor, $schlyear);
						$stmt->execute();

						$result = $stmt->get_result();

						if ($result !== false && $result->num_rows > 0) {
							echo "Record already exist.\n";
						} else {
							$stmt = $this->db->prepare("INSERT INTO table_class (days, time, room, subjcode, instructor, branch, level, department, schoolyear) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
							$stmt->bind_param("sssssssss", $day, $time, $room, $subjcode, $instructor, $branch, $level, $department, $schlyear);
		
							if ($stmt->execute()) {
								echo "Record inserted successfully.\n";
							} else {
								if ($this->db->errno == 1062) { 
									echo "Error inserting record: Duplicate entry.\n";
								} else {
									echo "Error inserting record: " . $stmt->error;
								}
							}
						}
					}
				} catch (Exception $e) {
					echo "Error loading Excel file: " . $e->getMessage();
				}
			} else {
				echo "Invalid file extension.";
			}
		} else {
			echo "Invalid request.";
		}
	}
	function delete_class(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	
		$checkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_schedule WHERE classes_id = ?");
		$checkStmt->bind_param("i", $id);
		$checkStmt->execute();
		$checkStmt->bind_result($count);
		$checkStmt->fetch();
		$checkStmt->close();
	
		if ($count > 0) {
			return "Cannot delete class. Associated student records exist.";
		}

		$deleteStmt = $this->db->prepare("DELETE FROM table_class WHERE id = ?");
		$deleteStmt->bind_param("i", $id);
		$deleteStmt->execute();
		$deleteStmt->close();
	
		return 1;
	}
	function add_student(){
		$classID = isset($_SESSION['classID']) ? $this->db->real_escape_string($_SESSION['classID']) : '';
		$stdID = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$qryStmt = $this->db->prepare("SELECT * FROM table_class WHERE id = ?");
		$qryStmt->bind_param("i", $classID);
		$qryStmt->execute();
		$qryResult = $qryStmt->get_result();
		$qryRow = $qryResult->fetch_assoc();
		$schlyear = $qryRow['schoolyear'];
		$qryStmt->close();

		$saveStmt = $this->db->prepare("INSERT INTO table_schedule (student_id, classes_id, schoolyear, eval_status) VALUES (?, ?, ?, '0')");
		$saveStmt->bind_param("iii", $stdID, $classID, $schlyear);
		$save = $saveStmt->execute();
		$saveStmt->close();

		if ($save) {
			$enableAccounts = $this->db->prepare("UPDATE table_student SET status = 1 WHERE id = ?");
			$enableAccounts->bind_param("i", $stdID);
			$enable = $enableAccounts->execute();

			if($enable) {
				$enableAccounts->close();
				return 1;
			}	
		} else {
			return 0;
		}
	}
	function remove_student(){
		$classID = isset($_SESSION['classID']) ? $this->db->real_escape_string($_SESSION['classID']) : '';
		$stdID = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$eval_status = 1;
		$checkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_schedule WHERE student_id = ? AND classes_id = ? AND eval_status = ?");
		$checkStmt->bind_param("iii", $stdID, $classID, $eval_status);
		$checkStmt->execute();
		$checkStmt->bind_result($count);
		$checkStmt->fetch();
		$checkStmt->close();
	
		if ($count > 0) {
			return "Cannot delete scheduled. Associated evaluation records exist.";
		}

		$deleteStmt = $this->db->prepare("DELETE FROM table_schedule WHERE student_id = ? AND classes_id = ?");
		$deleteStmt->bind_param("ii", $stdID, $classID);
		$deleteStmt->execute();
		$deleteStmt->close();
	
		return 1;
	}
	function save_schedule_excel(){
		require 'assets/plugins/PHPExcel-1.8/Classes/PHPExcel.php';

		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excelFile"])) {
			$file = $_FILES["excelFile"]["tmp_name"];
		
			$fileName = $_FILES['excelFile']['name'];
			$file_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			$allowed_ext = ['xls', 'csv', 'xlsx'];

			$classID = isset($_SESSION['classID']) ? $this->db->real_escape_string($_SESSION['classID']) : '';
			
			$qryStmt = $this->db->prepare("SELECT * FROM table_class WHERE id = ?");
			$qryStmt->bind_param("i", $classID);
			$qryStmt->execute();
			$qryResult = $qryStmt->get_result();
			$qryRow = $qryResult->fetch_assoc();
			$schlyear = $qryRow['schoolyear'];
			$qryStmt->close();

			$evalStatus = '0';
		
			if (in_array($file_ext, $allowed_ext)) {
				// Load the Excel file
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$data = $objPHPExcel->getActiveSheet()->toArray();
		
					array_shift($data);
		
					foreach ($data as $row) {
						$studentID = isset($row[0]) ? $row[0] : '';
					
						// Retrieve student ID from the table_student table
						$qryStmt = $this->db->prepare("SELECT id FROM table_student WHERE student_id = ?");
						$qryStmt->bind_param("s", $studentID);
						$qryStmt->execute();
						$qryResult = $qryStmt->get_result();
						$qryRow = $qryResult->fetch_assoc();
						$qryStmt->close();
					
						if ($qryRow) {
							$stdID = $qryRow['id'];
					
							// Check if a schedule record already exists for the student and class
							$stmt = $this->db->prepare("SELECT * FROM table_schedule WHERE student_id = ? AND classes_id = ?");
							$stmt->bind_param("ss", $stdID, $classID);
							$stmt->execute();
							$result = $stmt->get_result();
					
							if ($result->num_rows > 0) {
								echo "Record already exists.\n";
							} else {
								// Insert a new schedule record
								$stmt = $this->db->prepare("INSERT INTO table_schedule (student_id, classes_id, schoolyear, eval_status) VALUES (?, ?, ?, ?)");
								$stmt->bind_param("ssss", $stdID, $classID, $schlyear, $evalStatus);
							
								if ($stmt->execute()) {					
									// Update status to 1 upon successful query
									$enableAccounts = $this->db->prepare("UPDATE table_student SET status = 1 WHERE id = ?");
									$enableAccounts->bind_param("i", $stdID);
									$enable = $enableAccounts->execute();

									if($enable) {
										$enableAccounts->close();
										echo "Record inserted successfully.\n";
									}
								} else {
									if ($this->db->errno == 1062) { 
										echo "Error inserting record: Duplicate entry.\n";
									} else {
										echo "Error inserting record: " . $stmt->error;
									}
								}
							}
						} else {
							echo "Student ID not found: $studentID\n";
						}
					}
					
				} catch (Exception $e) {
					echo "Error loading Excel file: " . $e->getMessage();
				}
			} else {
				echo "Invalid file extension.";
			}
		} else {
			echo "Invalid request.";
		}
	}

	//Questionnaire functions
	function save_question(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$data = "";

		foreach ($_POST as $k => $v) {
			if (!in_array($k, ['id', 'user_ids']) && !is_numeric($k)) {
				if (!empty($data)) {
					$data .= ", ";
				}
				$data .= "$k='" . $this->db->real_escape_string($v) . "'";
			}
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO questionnaire_list SET $data");
		} else {
			$save = $this->db->query("UPDATE questionnaire_list SET $data WHERE id = $id");
		}

		if ($save) {
			return 1;
		} else {
			return 0;
		}
	}
	function save_question_excel(){
		require 'assets/plugins/PHPExcel-1.8/Classes/PHPExcel.php';

		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excelFile"])) {
			$file = $_FILES["excelFile"]["tmp_name"];
		
			$fileName = $_FILES['excelFile']['name'];
			$file_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			$allowed_ext = ['xls', 'csv', 'xlsx'];
		
			if (in_array($file_ext, $allowed_ext)) {
				// Load the Excel file
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$data = $objPHPExcel->getActiveSheet()->toArray();
		
					array_shift($data);
		
					foreach ($data as $row) {
						$questiondesc = isset($row[0]) ? $row[0] : '';
						$criteria = isset($row[1]) ? $row[1] : '';
						$type = isset($row[2]) ? $row[2] : '';
						$education_level = isset($row[3]) ? $row[3] : '';

						//Checking criteria in criteria table
						$stmt = $this->db->prepare("SELECT id FROM criteria_list WHERE criteria = ? AND type = ? AND education_level = ?");
						$stmt->bind_param("sss", $criteria, $type, $education_level);
						$stmt->execute();

						$result = $stmt->get_result();

						//Checking if criteria already exist if not then will be added
						if ($result->num_rows > 0) {
							$row = $result->fetch_assoc();
							$criteriaID = $row['id'];
						} else {
							$stmt = $this->db->prepare("INSERT INTO criteria_list (criteria, type, education_level) VALUES (?, ?, ?)");
							$stmt->bind_param("sss", $criteria, $type, $education_level);
							
							if ($stmt->execute()) {
								$criteriaID = $stmt->insert_id;
								echo "Criteria inserted successfully. ID: $criteriaID\n";
							} else {
								if ($this->db->errno == 1062) { 
									echo "Error inserting record: Duplicate entry.\n";
								} else {
									echo "Error inserting record: " . $stmt->error;
								}
							}
						}
				
						//Checking question in questionnaire table
						$stmt = $this->db->prepare("SELECT * FROM questionnaire_list WHERE questiondesc = ? AND criteria = ? AND type = ? AND education_level = ?");
						$stmt->bind_param("ssss", $questiondesc, $criteriaID, $type, $education_level);
						$stmt->execute();

						$result = $stmt->get_result();

						//Checking question in if already exist if not then will be added
						if ($result !== false && $result->num_rows > 0) {
							echo "Record already exist.\n";
						} else {
							$stmt = $this->db->prepare("INSERT INTO questionnaire_list (questiondesc, criteria, type, education_level) VALUES (?, ?, ?, ?)");
							$stmt->bind_param("ssss", $questiondesc, $criteriaID, $type, $education_level);
		
							if ($stmt->execute()) {
								echo "Record inserted successfully.\n";
							} else {
								if ($this->db->errno == 1062) { 
									echo "Error inserting record: Duplicate entry.\n";
								} else {
									echo "Error inserting record: " . $stmt->error;
								}
							}
						}
					}
				} catch (Exception $e) {
					echo "Error loading Excel file: " . $e->getMessage();
				}
			} else {
				echo "Invalid file extension.";
			}
		} else {
			echo "Invalid request.";
		}
	}
	function delete_question(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$deleteStmt = $this->db->prepare("DELETE FROM questionnaire_list WHERE id = ?");
		$deleteStmt->bind_param("i", $id);
		$delete = $deleteStmt->execute();
		$deleteStmt->close();

		if ($delete) {
			return 1;
		} else {
			return 0;
		}
	}

	//Criteria functions
	function save_criteria(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$data = "";

		foreach ($_POST as $k => $v) {
			if (!in_array($k, ['id', 'user_ids']) && !is_numeric($k)) {
				if (!empty($data)) {
					$data .= ", ";
				}
				$data .= "$k='" . $this->db->real_escape_string($v) . "'";
			}
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO criteria_list SET $data");
		} else {
			$save = $this->db->query("UPDATE criteria_list SET $data WHERE id = $id");
		}

		if ($save) {
			return 1;
		} else {
			return 0;
		}
	}
	function delete_criteria(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$checkStmt = $this->db->prepare("SELECT * FROM questionnaire_list WHERE criteria = ?");
		$checkStmt->bind_param("i", $id);
		$check = $checkStmt->execute();
		$checkStmt->close();

		if ($check) {
			return "Cannot delete criteria. Associated questions records exist.";
		}

		$deleteStmt = $this->db->prepare("DELETE FROM criteria_list WHERE id = ?");
		$deleteStmt->bind_param("i", $id);
		$delete = $deleteStmt->execute();
		$deleteStmt->close();

		if ($delete) {
			return 1;
		} else {
			return 0;
		}
	}

	//Faculty functions
	function save_faculty(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
		$firstname = isset($_POST['firstname']) ? $_POST['firstname'] : '';
		$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';

		$data = "";

		foreach ($_POST as $k => $v) {
			if (!in_array($k, ['id', 'user_ids']) && !is_numeric($k)) {
				if (!empty($data)) {
					$data .= ", ";
				}
				$data .= "$k='" . $this->db->real_escape_string($v) . "'";
			}
		}

		$chkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_faculty WHERE firstname = ? AND lastname = ? AND id != ?");
		$chkStmt->bind_param("ssi", $firstname, $lastname, $id);
		$chkStmt->execute();
		$chkStmt->bind_result($chk);
		$chkStmt->fetch();
		$chkStmt->close();

		if ($chk > 0) {
			return 2;
		}

		if (empty($id)) {
			$save = $this->db->query("INSERT INTO table_faculty SET $data");
		} else {
			$save = $this->db->query("UPDATE table_faculty SET $data WHERE id = $id");
		}

		if ($save) {
			return 1; 
		} else {
			return 0;
		}
	}
	function save_faculty_excel(){
		require 'assets/plugins/PHPExcel-1.8/Classes/PHPExcel.php';

		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excelFile"])) {
			$file = $_FILES["excelFile"]["tmp_name"];
		
			$fileName = $_FILES['excelFile']['name'];
			$file_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			$allowed_ext = ['xls', 'csv', 'xlsx'];
		
			if (in_array($file_ext, $allowed_ext)) {
				// Load the Excel file
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$data = $objPHPExcel->getActiveSheet()->toArray();
		
					array_shift($data);
		
					foreach ($data as $row) {
						$firstname = isset($row[0]) ? $row[0] : '';
						$middlename = isset($row[1]) ? $row[1] : '';
						$lastname = isset($row[2]) ? $row[2] : '';
						$level = isset($row[3]) ? $row[3] : '';
						$department = isset($row[4]) ? $row[4] : '';
		
						$stmt = $this->db->prepare("SELECT * FROM table_faculty WHERE firstname = ? AND middlename = ? AND lastname = ? AND level = ? AND department = ?");
						$stmt->bind_param("sssss", $firstname, $middlename, $lastname, $level, $department);
						$stmt->execute();

						$result = $stmt->get_result();

						if ($result !== false && $result->num_rows > 0) {
							echo "Record already exist.\n";
						} else {
							$stmt = $this->db->prepare("INSERT INTO table_faculty (firstname, middlename, lastname, level, department) VALUES (?, ?, ?, ?, ?)");
							$stmt->bind_param("sssss", $firstname, $middlename, $lastname, $level, $department);
		
							if ($stmt->execute()) {
								echo "Record inserted successfully.\n";
							} else {
								if ($this->db->errno == 1062) { 
									echo "Error inserting record: Duplicate entry.\n";
								} else {
									echo "Error inserting record: " . $stmt->error;
								}
							}
						}
					}
				} catch (Exception $e) {
					echo "Error loading Excel file: " . $e->getMessage();
				}
			} else {
				echo "Invalid file extension.";
			}
		} else {
			echo "Invalid request.";
		}
	}
	function delete_faculty(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$checkStmt = $this->db->prepare("SELECT COUNT(*) FROM table_class WHERE instructor = ?");
		$checkStmt->bind_param("i", $id);
		$checkStmt->execute();
		$checkStmt->bind_result($count);
		$checkStmt->fetch();
		$checkStmt->close();
	
		if ($count > 0) {
			return "Cannot delete faculty. Associated class records exist.";
		}

		$deleteStmt = $this->db->prepare("DELETE FROM table_faculty WHERE id = ?");
		$deleteStmt->bind_param("i", $id);
		$deleteStmt->execute();
		$deleteStmt->close();
	
		return 1;
	}

	//Student functions
	function save_student(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass','password')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(!empty($password)){
			$data .= ", password=md5('$password') ";
		}

		$check = $this->db->query("SELECT * FROM table_student where student_id ='$student_id' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO table_student set $data");
		}else{
			$save = $this->db->query("UPDATE table_student set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function save_student_excel(){
		require 'assets/plugins/PHPExcel-1.8/Classes/PHPExcel.php';

		if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["excelFile"])) {
			$file = $_FILES["excelFile"]["tmp_name"];
		
			$fileName = $_FILES['excelFile']['name'];
			$file_ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
			$allowed_ext = ['xls', 'csv', 'xlsx'];
		
			if (in_array($file_ext, $allowed_ext)) {
				// Load the Excel file
				try {
					$objPHPExcel = PHPExcel_IOFactory::load($file);
					$data = $objPHPExcel->getActiveSheet()->toArray();
		
					array_shift($data);
		
					foreach ($data as $row) {
						$student_id = isset($row[0]) ? $row[0] : '';
						$firstname = isset($row[1]) ? $row[1] : '';
						$middlename = isset($row[2]) ? $row[2] : '';
						$lastname = isset($row[3]) ? $row[3] : '';
						$level = isset($row[4]) ? $row[4] : '';
						$course = isset($row[5]) ? $row[5] : '';
		
						$stmt = $this->db->prepare("SELECT * FROM table_student WHERE student_id = ?");
						$stmt->bind_param("s", $student_id);
						$stmt->execute();

						$result = $stmt->get_result();

						if ($result !== false && $result->num_rows > 0) {
							echo "Record already exist.\n";
						} else {
							$password = "123";
							$hashed_password = md5($password);
							$stmt = $this->db->prepare("INSERT INTO table_student (student_id, firstname, middlename, lastname, password, level) VALUES (?, ?, ?, ?, ? , ?)");
							$stmt->bind_param("ssssss", $student_id, $firstname, $middlename, $lastname, $hashed_password, $level);
		
							if ($stmt->execute()) {
								echo "Record inserted successfully.\n";
							} else {
								if ($this->db->errno == 1062) { 
									echo "Error inserting record: Duplicate entry.\n";
								} else {
									echo "Error inserting record: " . $stmt->error;
								}
							}
						}
					}
				} catch (Exception $e) {
					echo "Error loading Excel file: " . $e->getMessage();
				}
			} else {
				echo "Invalid file extension.";
			}
		} else {
			echo "Invalid request.";
		}
	}
	function delete_student(){
		$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

		$deleteStmt = $this->db->prepare("DELETE FROM table_student WHERE id = ?");
		$deleteStmt->bind_param("i", $id);
		$delete = $deleteStmt->execute();
		$deleteStmt->close();

		if ($delete) {
			return 1; 
		} else {
			return 0;
		}
	}

	//Evaluation Report for Faculty
	function get_reportFaculty(){
		if (isset($_POST['year'], $_POST['facultyId'], $_POST['branch'], $_POST['level'])) {
			$selectedYear = $_POST['year'];
			$selectedFaculty = $_POST['facultyId'];
			$selectedBranch = $_POST['branch'];
			$selectedLevel = $_POST['level'];

			//Get the name of school year and semester
			$qry = $this->db->query("SELECT * FROM table_schoolyear where id='$selectedYear'")->fetch_assoc();
			$schlyearSY = $qry['schoolyear'];
			$schlyearSem = $qry['semester'];

			$faculty_query = $this->db->query("SELECT * FROM table_faculty WHERE id = '$selectedFaculty'");
			$faculty_row = $faculty_query->fetch_assoc();
			$lastname = $faculty_row['lastname'];
			$firstname = $faculty_row['firstname'];

			$html_per_subject = [];

			$html1 = "<h5 class='text-center no-spacing with-margin-top'>FACULTY EVALUATION REPORT</h5>";
			
			if($selectedLevel == "College"){
				$html1 .= "<h5 class='text-center no-spacing no-bold'>{$schlyearSem} SEMESTER, CALENDAR YEAR {$schlyearSY}</h5><br>";
			}
			else{
				$html1 .= "<h5 class='text-center no-spacing no-bold'>CALENDAR YEAR {$schlyearSY}</h5><br>";
			}
			
			$html1 .= "<h5 class='uppercase text-center no-spacing no-bold'>$selectedBranch CAMPUS</h5>";
			$html1 .= "<h5 class='uppercase no-bold with-margin-left with-margin-bottom'>{$lastname}, {$firstname}</h5>";
	
			array_push($html_per_subject, $html1);
		
			$criteria_array = array();
			$criteriaID_array = array();
			$criteria = $this->db->query("SELECT * FROM criteria_list WHERE education_level='$selectedLevel' AND type='Rate' ORDER BY ABS(id) ASC");
			if (!$criteria) {
				die("Error fetching criteria: " . $this->db->error);
			}
			while ($crow = $criteria->fetch_assoc()) {
				$criteria_array[] = $crow['criteria'];
				$criteriaID_array[] = $crow['id'];
			}
		
			$html = "<table class='table table-condensed wborder' style='font-size: 11px;'>";
			$html .= "<thead>
					<tr>
					<th width='20%' class='text-center'>Subjects</th>";
			$html_criteria = "";
			foreach ($criteria_array as $criterion) {
				$html_criteria .= "<th class='text-center'>{$criterion}</th>";
			}
			$html .= $html_criteria;
			$html .= "<th width='2%' class='text-center'>OVERALL MEAN</th>";
			$html .= "<th width='13%' class='text-center'>RATING</th>";
			$html .= "</tr></thead>";
			$html .= "<tbody class='tr-sortable'>";
		
			// Fetch class
			$class_query = $this->db->prepare("SELECT * FROM table_class WHERE instructor = ? AND branch = ? AND level = ? AND schoolyear = ?");
			if (!$class_query) {
				die("Error preparing class query: " . $this->db->error);
			}
			$class_query->bind_param("ssss", $selectedFaculty, $selectedBranch, $selectedLevel, $selectedYear);
			$class_query->execute();
			$class_result = $class_query->get_result();
			if (!$class_result) {
				die("Error executing class query: " . $class_query->error);
			}
		
			$subject_means = array(); // To store means per subject
		
			while ($row1 = $class_result->fetch_assoc()) {
				$class_Id = $row1['id'];
				$subject_Code = $row1['subjcode'];
				
				// Initialize arrays for counts and sums per criterion
				$criteria_counts = array_fill_keys($criteriaID_array, 0);
				$criteria_sums = array_fill_keys($criteriaID_array, 0);
		
				// Fetch evaluation results for the subject
				$evaluation_query = $this->db->prepare("SELECT * FROM evaluation_answers WHERE evaluation_id IN (SELECT evaluation_id FROM evaluation_list WHERE class_id = ?)");
				if (!$evaluation_query) {
					die("Error preparing evaluation query: " . $this->db->error);
				}
				$evaluation_query->bind_param("i", $class_Id);
				$evaluation_query->execute();
				$evaluation_result = $evaluation_query->get_result();
				if (!$evaluation_result) {
					die("Error executing evaluation query: " . $evaluation_query->error);
				}
		
				// Process evaluation results
				if ($evaluation_result->num_rows > 0) {
					while ($row2 = $evaluation_result->fetch_assoc()) {
						$criteria = $row2["criteria"];
						$rate = $row2["rate"];
						// Increment count and sum for the criterion
						$criteria_counts[$criteria]++;
						$criteria_sums[$criteria] += $rate;
					}
				}
		
				// Calculate means for each criterion
				$means = array();
				foreach ($criteriaID_array as $criterion) {
					if ($criteria_counts[$criterion] != 0) {
						$means[$criterion] = $criteria_sums[$criterion] / $criteria_counts[$criterion];
					} else {
						// Handle case where there are no evaluation responses for this criterion
						$means[$criterion] = 0;
					}
				}
		
				// Calculate overall mean
				$overall_mean = array_sum($means) / count($means);
		
				// Retrieve subject name
				$stmt = $this->db->prepare("SELECT subject FROM table_subject WHERE id = ?");
				if (!$stmt) {
					die("Error preparing subject query: " . $this->db->error);
				}
				$stmt->bind_param("s", $subject_Code);
				$stmt->execute();
				$subject_result = $stmt->get_result();
				if (!$subject_result) {
					die("Error executing subject query: " . $stmt->error);
				}
				$row3 = $subject_result->fetch_assoc();
				$subject_name = $row3['subject'];
		
				// Store means for each subject
				if (!isset($subject_means[$subject_name])) {
					$subject_means[$subject_name] = array('means' => $means, 'count' => 1, 'overall_mean' => $overall_mean);
				} else {
					// Merge means if subject already exists
					foreach ($means as $criterion => $mean) {
						$subject_means[$subject_name]['means'][$criterion] += $mean;
					}
					$subject_means[$subject_name]['count']++;
					$subject_means[$subject_name]['overall_mean'] += $overall_mean;
				}
			}
		
			// Output the results
			foreach ($subject_means as $subject_name => $data) {
				$html .= "<tr>";
				$html .= "<td>{$subject_name}</td>";
				foreach ($data['means'] as $mean) {
					$html .= "<td class='text-center'>" . number_format($mean / $data['count'], 2) . "</td>";
				}
				$html .= "<td class='text-center'>" . number_format($data['overall_mean'] / $data['count'], 2) . "</td>";
				
				if(number_format($data['overall_mean'] / $data['count'], 2) <= 4 && number_format($data['overall_mean'] / $data['count'], 2) >= 3.51){
					$html .= "<td class='text-center'>Very Satisfactory</td>";
				}
				else if(number_format($data['overall_mean'] / $data['count'], 2) <= 3.5 && number_format($data['overall_mean'] / $data['count'], 2) >= 2.51){
					$html .= "<td class='text-center'>Satisfactory</td>";
				}
				else if(number_format($data['overall_mean'] / $data['count'], 2) <= 2.5 && number_format($data['overall_mean'] / $data['count'], 2) >= 1.51){
					$html .= "<td class='text-center'>Unsatisfactory</td>";
				}
				else if(number_format($data['overall_mean'] / $data['count'], 2) <= 1.5 && number_format($data['overall_mean'] / $data['count'], 2) >= 1){
					$html .= "<td class='text-center'>Very Unsatisfactory</td>";
				}
				else{
					$html .= "<td class='text-center'></td>";
				}
				$html .= "</tr>";
			}
		
			$html .= "</tbody>";
			$html .= "</table>";
		
			$html_per_subject[] = $html;

			//Get the Comments
			if($selectedLevel == "College" || $selectedLevel == "Highschool"){
				// Fetch distinct classes taught by the faculty
				$stmt = $this->db->prepare("SELECT DISTINCT id, subjcode, days, time FROM table_class WHERE instructor = ? AND branch = ? AND level = ? AND schoolyear = ?");
				if (!$stmt) {
					die("Error preparing class query: " . $this->db->error);
				}
				$stmt->bind_param("ssss", $selectedFaculty, $selectedBranch, $selectedLevel, $selectedYear);
				$stmt->execute();
				$result = $stmt->get_result();
				$classes = $result->fetch_all(MYSQLI_ASSOC);
				$stmt->close();
			 
				$htmlhead = "<br><h5 class='text-center no-spacing no-bold'>Comments and Suggestions(Verbatim):</h5>";
				$htmlhead .= "<h5 class='text-center no-spacing no-bold'>SY {$schlyearSY} {$schlyearSem} SEMESTER</h5><br>";

				$html_per_subject[] = $htmlhead;

				// Loop through distinct classes
				foreach ($classes as $class) {
					$class_id = $class['id'];
					$subjcode = $class['subjcode'];
					$days = $class['days'];
					$time = $class['time'];

					$subjectinfo_query = $this->db->prepare("SELECT * FROM table_subject WHERE id = ?");
					$subjectinfo_query->bind_param("s", $subjcode);
					$subjectinfo_query->execute();
					$subject_result = $subjectinfo_query->get_result();

					$subject_row = $subject_result->fetch_assoc();

					if($subject_row){
						$subject = $subject_row['subject'];
					}
			 
					// Fetch comments and suggestions for the current class
					$comment_query = $this->db->prepare("SELECT * FROM evaluation_comments WHERE evaluation_id IN (SELECT evaluation_id FROM evaluation_list WHERE class_id = ?)");
					$comment_query->bind_param("i", $class_id);
					$comment_query->execute();
					$comment_result = $comment_query->get_result();
			 
					// Start generating HTML for the current class
					$html = "<h5 class='no-spacing no-bold'>{$subject}, {$days}, {$time}</h5>";
					$html .= "<ul class='custom-arrow'>";
			 
					$comments_found = false; // Flag to track if comments were found
			 
					while ($row = $comment_result->fetch_assoc()) {
						$question_id = $row['question'];
						$response = $row['response'];
			 
						// Fetch the question description
						$question_query = $this->db->prepare("SELECT questiondesc FROM questionnaire_list WHERE id = ?");
						$question_query->bind_param("s", $question_id);
						$question_query->execute();
						$question_result = $question_query->get_result();
			 
						if ($question_result->num_rows > 0) {
							$question_row = $question_result->fetch_assoc();
							$question_desc = $question_row['questiondesc'];
						} else {
							$question_desc = "Unknown Question";
						}
			 
						if (!empty($response)) {
							$comments_found = true;
							$html .= "<li>{$response}</li>";
						}
					}
			 
					if (!$comments_found) {
						$html .= "<li>No Comments and Suggestions</li>";
					}
			 
					$html .= "</ul>";
			 
					$html_per_subject[] = $html;
				}
			}
			else{
				// Fetch distinct classes taught by the faculty
				$stmt = $this->db->prepare("SELECT DISTINCT id, subjcode, days, time FROM table_class WHERE instructor = ? AND branch = ? AND level = ? AND schoolyear = ?");
				if (!$stmt) {
					die("Error preparing class query: " . $this->db->error);
				}
				$stmt->bind_param("ssss", $selectedFaculty, $selectedBranch, $selectedLevel, $selectedYear);
				$stmt->execute();
				$result = $stmt->get_result();
				$classes = $result->fetch_all(MYSQLI_ASSOC);
				$stmt->close();
			 
				$htmlhead = "<br><h5 class='text-center no-spacing no-bold'>Comments and Suggestions(Verbatim):</h5>";
				$htmlhead .= "<h5 class='text-center no-spacing no-bold'>SY {$schlyearSY} {$schlyearSem} SEMESTER</h5><br>";

				$html_per_subject[] = $htmlhead;

				// Loop through distinct classes
				foreach ($classes as $class) {
					$class_id = $class['id'];
					$subjcode = $class['subjcode'];
					$days = $class['days'];
					$time = $class['time'];

					$subjectinfo_query = $this->db->prepare("SELECT * FROM table_subject WHERE id = ?");
					$subjectinfo_query->bind_param("s", $subjcode);
					$subjectinfo_query->execute();
					$subject_result = $subjectinfo_query->get_result();

					$subject_row = $subject_result->fetch_assoc();

					if($subject_row){
						$subject = $subject_row['subject'];
					}
			 
					// Fetch comments and suggestions for the current class
					$comment_query = $this->db->prepare("SELECT * FROM evaluation_comments WHERE evaluation_id IN (SELECT evaluation_id FROM evaluation_list WHERE class_id = ?)");
					$comment_query->bind_param("i", $class_id);
					$comment_query->execute();
					$comment_result = $comment_query->get_result();
			 
					// Start generating HTML for the current class
					$html = "<h5 class='no-spacing no-bold'>{$subject}, {$days}, {$time}</h5>";
					$html .= "<ul class='custom-arrow'>";

					$comments_found = false; // Flag to track if comments were found

					$grouped_comments = array(); // Array to store comments grouped by question description

					while ($row = $comment_result->fetch_assoc()) {
						$question_id = $row['question'];
						$response = $row['response'];

						// Fetch the question description
						$question_query = $this->db->prepare("SELECT questiondesc FROM questionnaire_list WHERE id = ?");
						$question_query->bind_param("s", $question_id);
						$question_query->execute();
						$question_result = $question_query->get_result();

						if ($question_result->num_rows > 0) {
							$question_row = $question_result->fetch_assoc();
							$question_desc = $question_row['questiondesc'];
						} else {
							$question_desc = "Unknown Question";
						}

						// Group responses by question description
						$grouped_comments[$question_desc][] = $response;
					}

					foreach ($grouped_comments as $question_desc => $responses) {
						// Output question description
						$html .= "<li>{$question_desc} <ul>";

						// Output responses as nested list items
						foreach ($responses as $response) {
							$html .= "<li>{$response}</li>";
						}

						$html .= "</ul></li>";
						$comments_found = true;
					}

					if (!$comments_found) {
						$html .= "<li>No Comments and Suggestions</li>";
					}

					$html .= "</ul><br><br>";

					$html_per_subject[] = $html;
						
				}
			}

			echo json_encode($html_per_subject);
		}
	}

	//Evaluation Report for Department
	function get_reportDepartment(){
		if (isset($_POST['year'], $_POST['branch'], $_POST['level'], $_POST['department'])) {
			$selectedYear = $_POST['year'];
			$selectedBranch = $_POST['branch'];
			$selectedLevel = $_POST['level'];
			$selectedDepartment = $_POST['department'];

			$html_per_department = [];

			//Get the school year and semester
			$qry = $this->db->query("SELECT * FROM table_schoolyear where id='$selectedYear'")->fetch_assoc();
			$schlyearSY = $qry['schoolyear'];
			$schlyearSem = $qry['semester'];

			$html1 = "<h5 class='text-center no-spacing with-margin-top'>FACULTY EVALUATION REPORT</h5>";
			
			if($selectedLevel == "College"){
				$html1 .= "<h5 class='text-center no-spacing no-bold'>{$schlyearSem} SEMESTER, CALENDAR YEAR {$schlyearSY}</h5><br>";
			}
			else{
				$html1 .= "<h5 class='text-center no-spacing no-bold'>CALENDAR YEAR {$schlyearSY}</h5>";
			}
			
			$html1 .= "<h5 class='uppercase text-center no-spacing no-bold'>$selectedBranch CAMPUS</h5>";

			array_push($html_per_department, $html1);

			//Fetch the results baes on the selected department
			if($selectedDepartment == "All"){
				// Fetch distinct departments from evaluation_list
				$stmt = $this->db->prepare("SELECT DISTINCT department FROM evaluation_list WHERE branch = ? AND level = ? AND schoolyear_id = ?");
				if ($stmt) {
					$stmt->bind_param("sss", $selectedBranch, $selectedLevel, $selectedYear);
					$stmt->execute();
					$result = $stmt->get_result();
					$departments = $result->fetch_all(MYSQLI_ASSOC);
					$stmt->close();

					// Loop through departments
					foreach ($departments as $department) {
						$department_name = $department['department'];

						$criteriaDescription_array = array();
						$criteriaID_array = array();
						$criteria = $this->db->query("SELECT * FROM criteria_list WHERE education_level='$selectedLevel' AND type='Rate' ORDER BY ABS(id) ASC");
						while($crow = $criteria->fetch_assoc()){
							// Append criteria to the array
							$criteriaID_array[] = $crow['id'];
							$criteriaDescription_array[] = $crow['criteria'];
						}		

						// Start generating HTML for the current department
						$html = "<h5 class='uppercase no-bold with-margin-left with-margin-bottom'>{$department_name}</h5>";
						$html .= "<table class='table table-condensed wborder' style='font-size: 11px;'>";
						$html .= "<thead>
									<tr>
										<th width='20%' class='text-center'>NAME OF FACULTY</th>";

						// Loop through criteriaDescription_array to display each criterion
						$html_criteria = "";
						foreach ($criteriaDescription_array as $criterion) {
							$html_criteria .= "<th class='text-center'>{$criterion}</th>";
						}
						$html .= $html_criteria;

						// Continue building the rest of the HTML
						$html .= "<th width='2%' class='text-center'>OVERALL MEAN</th>";
						$html .= "<th width='13%' class='text-center'>RATING</th>";
						$html .= "</tr></thead>";
						$html .= "<tbody class='tr-sortable'>";

						// Fetch faculty members for the current department
						$faculty_query = $this->db->prepare("SELECT * FROM table_faculty WHERE id IN (SELECT faculty_id FROM evaluation_list WHERE branch = ? AND department = ? AND level = ? AND schoolyear_id = ?)");
						$faculty_query->bind_param("ssss", $selectedBranch, $department_name, $selectedLevel, $selectedYear);
						$faculty_query->execute();
						$faculty_result = $faculty_query->get_result();

						// Loop through faculty members
						while ($row1 = $faculty_result->fetch_assoc()) {
							// Fetch evaluation results for the current faculty member and department
							$evaluation_query = $this->db->prepare("SELECT * FROM evaluation_answers WHERE evaluation_id IN (SELECT evaluation_id FROM evaluation_list WHERE faculty_id = ? AND level = ? AND department = ?)");
							$evaluation_query->bind_param("iss", $row1['id'], $selectedLevel, $department_name);
							$evaluation_query->execute();
							$evaluation_result = $evaluation_query->get_result();

							$criteria_count = count($criteriaID_array);

							// Initialize counts and sums arrays with the count of criteria
							$criteria_counts = [];
							$criteria_sums = [];
							for ($i = 0; $i < $criteria_count; $i++) {
								// Use $criteriaID_array[$i] as the key
								$criteria_counts[$criteriaID_array[$i]] = 0;
								$criteria_sums[$criteriaID_array[$i]] = 0;
							}

							// Calculate sums for each criterion
							if ($evaluation_result->num_rows > 0) {
								while ($row2 = $evaluation_result->fetch_assoc()) {
									$criteria = $row2["criteria"];
									$rate = $row2["rate"];
									// Increment count and sum for the criterion
									$criteria_counts[$criteria]++;
									$criteria_sums[$criteria] += $rate;
								}
							}

							// Calculate means for each criterion
							$means = [];

							for ($i = 0; $i < $criteria_count; $i++) {
								$criterion = $criteriaID_array[$i];
								if ($criteria_counts[$criterion] != 0) {
									$means[$criterion] = $criteria_sums[$criterion] / $criteria_counts[$criterion];
								} else {
									// Handle case where there are no evaluation responses for this criterion
									$means[$criterion] = 0;
								}
							}						

							// Calculate overall mean
							$overall_mean = array_sum($means) / count($means);
							$overall_mean_rounded = round($overall_mean, 2);

							// Add the faculty member and evaluation results to the HTML content
							$html .= "<tr>";
							$html .= "<td>{$row1['lastname']}, {$row1['firstname']} {$row1['middlename']}</td>";
							foreach ($means as $mean) {
								$html .= "<td class='text-center'>" . number_format($mean, 2) . "</td>";
							}
							$html .= "<td class='text-center'>" . $overall_mean_rounded . "</td>";

							if($overall_mean_rounded <= 4 && $overall_mean_rounded >= 3.51){
								$html .= "<td class='text-center'>Very Satisfactory</td>";
							}
							else if($overall_mean_rounded <= 3.5 && $overall_mean_rounded >= 2.51){
								$html .= "<td class='text-center'>Satisfactory</td>";
							}
							else if($overall_mean_rounded <= 2.5 && $overall_mean_rounded >= 1.51){
								$html .= "<td class='text-center'>Unsatisfactory</td>";
							}
							else if($overall_mean_rounded <= 1.5 && $overall_mean_rounded >= 1){
								$html .= "<td class='text-center'>Very Unsatisfactory</td>";
							}
							else{
								$html .= "<td class='text-center'></td>";
							}

							$html .= "</tr>";
						}

						// Close the tbody and table tags for the current department
						$html .= "</tbody>";
						$html .= "</table>";

						// Store the HTML content for the current department in the array
						$html_per_department[] = $html;
					}
				} else {
					$departments = [];
				}
			}
			else{
				$faculty_data = array();

				$criteriaDescription_array = array();
				$criteriaID_array = array();
				$criteria = $this->db->query("SELECT * FROM criteria_list WHERE education_level='$selectedLevel' AND type='Rate' ORDER BY ABS(id) ASC");	

				while($crow = $criteria->fetch_assoc()){
					// Append criteria to the array
					$criteriaID_array[] = $crow['id'];
					$criteriaDescription_array[] = $crow['criteria'];
				}		

				$html = "<h5 class='uppercase no-bold with-margin-left with-margin-bottom'>{$selectedDepartment}</h5>";
				$html .= "<table class='table table-condensed wborder' style='font-size: 12px;'>";
				$html .= "<thead><tr><th width='10%' class='text-center'>RANK</th>";
				$html .= "<th width='40%' class='text-center'>NAME</th>";
				//$html .= "<th class='text-center'>RATER</th>";
				$html .= "<th class='text-center'>MEAN</th>";
				$html .= "<th width='25%' class='text-center'>RATING</th>";
				$html .= "</tr></thead>";
				$html .= "<tbody class='tr-sortable'>";

				// Fetch faculty members for the current department
				$faculty_query = $this->db->prepare("SELECT * FROM table_faculty WHERE id IN (SELECT faculty_id FROM evaluation_list WHERE branch = ? AND level = ? AND department = ? AND schoolyear_id = ?)");
				$faculty_query->bind_param("ssss", $selectedBranch, $selectedLevel, $selectedDepartment, $selectedYear);
				$faculty_query->execute();
				$faculty_result = $faculty_query->get_result();

				// Loop through faculty members
				while ($row1 = $faculty_result->fetch_assoc()) {
					// Fetch evaluation results for the current faculty member and department
					$evaluation_query = $this->db->prepare("SELECT * FROM evaluation_answers WHERE evaluation_id IN (SELECT evaluation_id FROM evaluation_list WHERE faculty_id = ? AND level = ? AND department = ?)");
					$evaluation_query->bind_param("iss", $row1['id'], $selectedLevel, $selectedDepartment);
					$evaluation_query->execute();
					$evaluation_result = $evaluation_query->get_result();

					$criteria_count = count($criteriaID_array);

					// Initialize counts and sums arrays with the count of criteria
					$criteria_counts = [];
					$criteria_sums = [];
					for ($i = 0; $i < $criteria_count; $i++) {
						// Use $criteriaID_array[$i] as the key
						$criteria_counts[$criteriaID_array[$i]] = 0;
						$criteria_sums[$criteriaID_array[$i]] = 0;
					}

					// Calculate sums for each criterion
					if ($evaluation_result->num_rows > 0) {
						while ($row2 = $evaluation_result->fetch_assoc()) {
							$criteria = $row2["criteria"];
							$rate = $row2["rate"];
							// Increment count and sum for the criterion
							$criteria_counts[$criteria]++;
							$criteria_sums[$criteria] += $rate;
						}
					}

					// Calculate means for each criterion
					$means = [];

					for ($i = 0; $i < $criteria_count; $i++) {
						$criterion = $criteriaID_array[$i];
						if ($criteria_counts[$criterion] != 0) {
							$means[$criterion] = $criteria_sums[$criterion] / $criteria_counts[$criterion];
						} else {
							// Handle case where there are no evaluation responses for this criterion
							$means[$criterion] = 0;
						}
					}						

					$overall_mean = array_sum($means) / count($means);
					$overall_mean_rounded = round($overall_mean, 2);

					// Store the faculty data in array
					$faculty_data[] = array(
						'id' => $row1['id'],
						'lastname' => $row1['lastname'],
						'firstname' => $row1['firstname'],
						'overall_mean' => $overall_mean_rounded
					);
				}

				// Sort faculty data based on overall mean (descending order)
				usort($faculty_data, function($a, $b) {
					return $b['overall_mean'] <=> $a['overall_mean'];
				});

				$prevMean = 0;
				$rank = 0;

				// Display the data
				foreach ($faculty_data as $key => $faculty) {
					//$rank = $key + 1;
					if($prevMean != $faculty['overall_mean']){
						$rank = $rank + 1;
					}
					$html .= "<tr>";
					$html .= "<td class='text-center'>$rank</td>";
					$html .= "<td>{$faculty['lastname']}, {$faculty['firstname']}</td>";
					//$html .= "<td class='text-center'></td>";
					$html .= "<td class='text-center'>{$faculty['overall_mean']}</td>";
		
					if ($faculty['overall_mean'] <= 4 && $faculty['overall_mean'] >= 3.51) {
						$html .= "<td class='text-center'>Very Satisfactory</td>";
					} else if ($faculty['overall_mean'] <= 3.5 && $faculty['overall_mean'] >= 2.51) {
						$html .= "<td class='text-center'>Satisfactory</td>";
					} else if ($faculty['overall_mean'] <= 2.5 && $faculty['overall_mean'] >= 1.51) {
						$html .= "<td class='text-center'>Unsatisfactory</td>";
					} else if ($faculty['overall_mean'] <= 1.5 && $faculty['overall_mean'] >= 1) {
						$html .= "<td class='text-center'>Very Unsatisfactory</td>";
					} else {
						$html .= "<td class='text-center'></td>";
					}
		
					$prevMean = $faculty['overall_mean'];
					$html .= "</tr>";
				}

				$html .= "</tbody>";
				$html .= "</table><br>";

				$html_per_department[] = $html;

				//Fetch the comment based on department and display it per faculty based on the rank
				if($selectedLevel == "College" || $selectedLevel == "Highschool"){
					foreach ($faculty_data as $key => $faculty) {
						// Fetch distinct classes taught by the faculty
						$stmt = $this->db->prepare("SELECT DISTINCT id, subjcode, days, time FROM table_class WHERE instructor = ? AND branch = ? AND level = ? AND schoolyear = ?");
						if (!$stmt) {
							die("Error preparing class query: " . $this->db->error);
						}
						$stmt->bind_param("ssss", $faculty['id'], $selectedBranch, $selectedLevel, $selectedYear);
						$stmt->execute();
						$result = $stmt->get_result();
						$classes = $result->fetch_all(MYSQLI_ASSOC);
						$stmt->close();

						$htmlhead = "<h5 class='no-spacing no-bold'>{$faculty['lastname']}, {$faculty['firstname']}</h5><br>";
						$htmlhead .= "<h5 class='text-center no-spacing no-bold'>Comments and Suggestions(Verbatim):</h5>";
						$htmlhead .= "<h5 class='text-center no-spacing no-bold'>SY {$schlyearSY} {$schlyearSem} SEMESTER</h5><br>";

						$html_per_department[] = $htmlhead;

						// Loop through distinct classes
						foreach ($classes as $class) {
							$class_id = $class['id'];
							$subjcode = $class['subjcode'];
							$days = $class['days'];
							$time = $class['time'];

							$subjectinfo_query = $this->db->prepare("SELECT * FROM table_subject WHERE id = ?");
							$subjectinfo_query->bind_param("s", $subjcode);
							$subjectinfo_query->execute();
							$subject_result = $subjectinfo_query->get_result();

							$subject_row = $subject_result->fetch_assoc();

							if($subject_row){
								$subject = $subject_row['subject'];
							}

							// Fetch comments and suggestions for the current class
							$comment_query = $this->db->prepare("SELECT * FROM evaluation_comments WHERE evaluation_id IN (SELECT evaluation_id FROM evaluation_list WHERE class_id = ?)");
							$comment_query->bind_param("i", $class_id);
							$comment_query->execute();
							$comment_result = $comment_query->get_result();

							// Start generating HTML for the current class
							$html = "<h5 class='no-spacing no-bold'>{$subject}, {$days}, {$time}</h5>";
							$html .= "<ul class='custom-arrow'>";

							$comments_found = false; // Flag to track if comments were found

							while ($row = $comment_result->fetch_assoc()) {
								$question_id = $row['question'];
								$response = $row['response'];

								// Fetch the question description
								$question_query = $this->db->prepare("SELECT questiondesc FROM questionnaire_list WHERE id = ?");
								$question_query->bind_param("s", $question_id);
								$question_query->execute();
								$question_result = $question_query->get_result();

								if ($question_result->num_rows > 0) {
									$question_row = $question_result->fetch_assoc();
									$question_desc = $question_row['questiondesc'];
								} else {
									$question_desc = "Unknown Question";
								}

								if (!empty($response)) {
									$comments_found = true;
									$html .= "<li>{$question_desc}: {$response}</li>";
								}
							}

							if (!$comments_found) {
								$html .= "<li>No Comments and Suggestions</li>";
							}

							$html .= "</ul><br><br>";

							$html_per_department[] = $html;
						}
					}
				}
			}
			
			// Convert the array of HTML content to JSON and echo it
			echo json_encode($html_per_department);
		}
	} 

	//Evaluation Status for Faculty
	function get_evaluation_status(){
		if (isset($_POST['year'], $_POST['facultyId'], $_POST['branch'], $_POST['level'])) {
			$selectedYear = $_POST['year'];
			$selectedFaculty = $_POST['facultyId'];
			$selectedBranch = $_POST['branch'];
			$selectedLevel = $_POST['level'];

			//Get the name of school year and semester
			$qry = $this->db->query("SELECT * FROM table_schoolyear where id='$selectedYear'")->fetch_assoc();
			$schlyearSY = $qry['schoolyear'];
			$schlyearSem = $qry['semester'];

			$faculty_query = $this->db->query("SELECT * FROM table_faculty WHERE id = '$selectedFaculty'");
			$faculty_row = $faculty_query->fetch_assoc();
			$lastname = $faculty_row['lastname'];
			$firstname = $faculty_row['firstname'];

			$html_per_subject = [];

			$html1 = "<h5 class='text-center no-spacing with-margin-top'>FACULTY EVALUATION STATUS REPORT</h5>";
			
			if($selectedLevel == "College"){
				$html1 .= "<h5 class='text-center no-spacing no-bold'>{$schlyearSem} SEMESTER, CALENDAR YEAR {$schlyearSY}</h5><br>";
			}
			else{
				$html1 .= "<h5 class='text-center no-spacing no-bold'>CALENDAR YEAR {$schlyearSY}</h5><br>";
			}
			
			$html1 .= "<h5 class='uppercase text-center no-spacing no-bold'>$selectedBranch CAMPUS</h5>";
			$html1 .= "<h5 class='uppercase no-bold with-margin-left with-margin-bottom'>{$lastname}, {$firstname}</h5>";
	
			array_push($html_per_subject, $html1);
		
			$html = "<table class='table table-condensed wborder' style='font-size: 11px;'>";
			$html .= "<thead>
					<tr>
					<th width='30%' class='text-center'>Schedule</th>";
			$html .= "<th width='30%' class='text-center'>Total students who took the evaluation</th>";
			$html .= "<th width='30%' class='text-center'>Total students have not taken the evaluation</th>";
			$html .= "<th width='10%' class='text-center'>Total</th>";
			$html .= "</tr></thead>";
			$html .= "<tbody class='tr-sortable'>";
		
			// Fetch class
			$class_query = $this->db->prepare("SELECT * FROM table_class WHERE instructor = ? AND branch = ? AND level = ? AND schoolyear = ?");
			if (!$class_query) {
				die("Error preparing class query: " . $this->db->error);
			}
			$class_query->bind_param("ssss", $selectedFaculty, $selectedBranch, $selectedLevel, $selectedYear);
			$class_query->execute();
			$class_result = $class_query->get_result();
			if (!$class_result) {
				die("Error executing class query: " . $class_query->error);
			}

			while ($row1 = $class_result->fetch_assoc()) {
				$class_Id = $row1['id'];
				$subject_Code = $row1['subjcode'];
				$days = $row1['days'];
				$time = $row1['time'];

				$evaluation_taken_count = 0;
				$evaluation_not_taken_count = 0;
		
				// Fetch schedule for the student count
				$schedule_query = $this->db->prepare("SELECT * FROM table_schedule WHERE classes_id = ?");
				if (!$schedule_query) {
					die("Error preparing evaluation query: " . $this->db->error);
				}
				$schedule_query->bind_param("i", $class_Id);
				$schedule_query->execute();
				$schedule_result = $schedule_query->get_result();
				if (!$schedule_result) {
					die("Error executing evaluation query: " . $schedule_query->error);
				}
		
				// Count the total students 
				if ($schedule_result->num_rows > 0) {
					while ($row2 = $schedule_result->fetch_assoc()) {
						if($row2['eval_status'] == 1){
							$evaluation_taken_count += 1;
						}
						else{
							$evaluation_not_taken_count += 1;
						}
					}
				}
		
				// Retrieve subject name
				$stmt = $this->db->prepare("SELECT subject FROM table_subject WHERE id = ?");
				if (!$stmt) {
					die("Error preparing subject query: " . $this->db->error);
				}
				$stmt->bind_param("s", $subject_Code);
				$stmt->execute();
				$subject_result = $stmt->get_result();
				if (!$subject_result) {
					die("Error executing subject query: " . $stmt->error);
				}
				$row3 = $subject_result->fetch_assoc();
				$subject_name = $row3['subject'];

				$total_student = $evaluation_taken_count + $evaluation_not_taken_count;

				$html .= "<tr>";
				$html .= "<td>{$subject_name} {$days} {$time}</td>";
				$html .= "<td class='text-center'>{$evaluation_taken_count}</td>";
				$html .= "<td class='text-center'>{$evaluation_not_taken_count}</td>";
				$html .= "<td class='text-center'>{$total_student}</td>";
				$html .= "</tr>";
			}
		
			$html .= "</tbody>";
			$html .= "</table>";
		
			$html_per_subject[] = $html;

			echo json_encode($html_per_subject);
		}
	}

	//Get the faculty for dropdown
	function get_faculty(){
		if (isset($_POST['level'], $_POST['branch'])) {
			$selectedLevel = $_POST['level'];
			$selectedBranch = $_POST['branch'];
	
			$stmt = $this->db->prepare("SELECT * FROM table_faculty WHERE id IN (SELECT faculty_id FROM evaluation_list WHERE level = ? AND branch = ?)");
			$stmt->bind_param("ss", $selectedLevel, $selectedBranch);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
	
			// Fetch data and return as JSON
			$faculties = [];
			while ($row = $stmt_result->fetch_assoc()) {
				$faculties[] = $row;
			}
			echo json_encode($faculties);
			
			// Close the statement
			$stmt->close();
		} else {
			// Return error message if 'level' parameter is not set
			echo "Error: 'level' parameter is not set";
		}
	}	

	//Get the faculty for dropdown
	function get_department(){
		if (isset($_POST['level'], $_POST['branch'])) {
			$selectedLevel = $_POST['level'];
			$selectedBranch = $_POST['branch'];
	
			$stmt = $this->db->prepare("SELECT DISTINCT department FROM evaluation_list WHERE level = ? AND branch = ?");
			$stmt->bind_param("ss", $selectedLevel, $selectedBranch);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
	
			// Fetch data and return as JSON
			$department = [];
			while ($row = $stmt_result->fetch_assoc()) {
				$department[] = $row;
			}
			echo json_encode($department);
			
			// Close the statement
			$stmt->close();
		} else {
			// Return error message if 'level' parameter is not set
			echo "Error: 'level' parameter is not set";
		}
	}

	//Get the faculty for dropdown for evaluation status
	function get_faculty_status(){
		if (isset($_POST['level'], $_POST['branch'])) {
			$selectedLevel = $_POST['level'];
			$selectedBranch = $_POST['branch'];
	
			$stmt = $this->db->prepare("SELECT * FROM table_faculty WHERE id IN (SELECT instructor FROM table_class WHERE id IN (SELECT classes_id FROM table_schedule WHERE level = ? AND branch = ?))");
			$stmt->bind_param("ss", $selectedLevel, $selectedBranch);
			$stmt->execute();
			$stmt_result = $stmt->get_result();
	
			// Fetch data and return as JSON
			$faculties = [];
			while ($row = $stmt_result->fetch_assoc()) {
				$faculties[] = $row;
			}
			echo json_encode($faculties);
			
			// Close the statement
			$stmt->close();
		} else {
			// Return error message if 'level' parameter is not set
			echo "Error: 'level' parameter is not set";
		}
	}	
}