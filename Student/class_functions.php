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

	//Student login functions
	function login_Student(){
		extract($_POST);
		$qry = $this->db->query("SELECT *, CONCAT(firstname,' ',lastname) as name FROM table_student WHERE student_id = '".$studentid."' AND password = '".md5($password)."' AND status = 1");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			return 1; // Login accepted
		} else {
			// Check if status is 0
			$statusCheckQry = $this->db->query("SELECT status FROM table_student WHERE student_id = '".$studentid."'");
			if($statusCheckQry->num_rows > 0){
				$statusRow = $statusCheckQry->fetch_assoc();
				if($statusRow['status'] == 0){
					return 2; // Login rejected due to status being 0
				}
			}
			return 3; // Login rejected due to other reasons
		}
	}
	
	

	function logout_Student(){
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

	//Update student data
	function update_student(){
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

	//Evaluation function
	function save_evaluation(){
		extract($_POST);
		$schoolyear_id = $this->db->real_escape_string($schoolyear_id);
		$class_id = $this->db->real_escape_string($class_id);
		$faculty_id = $this->db->real_escape_string($faculty_id);
		$studentclass_id = $this->db->real_escape_string($studentclass_id);
		$level = $this->db->real_escape_string($level);
		$department = $this->db->real_escape_string($department);
		$branch = $this->db->real_escape_string($branch);

		$data = " schoolyear_id = '$schoolyear_id' ";
		$data .= ", class_id = '$class_id' ";
		$data .= ", faculty_id = '$faculty_id' ";
		$data .= ", studentclass_id = '$studentclass_id' ";
		$data .= ", level = '$level' ";
		$data .= ", department = '$department' ";
		$data .= ", branch = '$branch' ";
		$save = $this->db->query("INSERT INTO evaluation_list set $data");

		if($save){
			$eid = $this->db->insert_id;
			foreach($qid as $k => $v){
				$data = " evaluation_id = $eid ";
				$data .= ", question_id = $v ";
				$data .= ", criteria = {$criteria[$v]} ";
				$data .= ", rate = {$rate[$v]} ";
				$ins[] = $this->db->query("INSERT INTO evaluation_answers SET $data ");
			}

			foreach ($cid as $comment_question_id => $comment_value) {
				$comment_data = " evaluation_id = $eid ";
				$comment_data .= ", question = $comment_value ";
				$comments = $this->db->real_escape_string($response[$comment_value]);
				$comment_data .= ", response = '$comments' ";
				$save_comment = $this->db->query("INSERT INTO evaluation_comments SET $comment_data");
			}
		
			if ($save_comment) {
				$update = $this->db->query("UPDATE table_schedule SET eval_status = '1' WHERE id = $studentclass_id");
				if ($update) {
					return 1;
				}
			}
		}
	}
}