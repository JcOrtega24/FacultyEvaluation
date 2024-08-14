<?php
	ob_start();
	date_default_timezone_set("Asia/Manila");

	/*
		include 'class_functions.php';
		$crud = new Action();

		$allowed_actions = [
			'login_admin', 'logout_admin', 'save_user', 'update_user', 'delete_user',
			'save_subject', 'delete_subject', 'save_class', 'delete_class',
			'save_schoolyear', 'delete_schoolyear', 'make_default', 'save_question',
			'delete_question', 'save_faculty', 'delete_faculty', 'save_student',
			'delete_student', 'add_class', 'remove_class', 'get_report'
		];

		if (isset($_GET['action']) && in_array($_GET['action'], $allowed_actions)) {
			$action = $_GET['action'];
			$response = $crud->$action();
			if ($response) {
				echo $response;
			}
		} else {
			http_response_code(400);
			echo "Invalid action!";
		}
	*/

	$action = $_GET['action'];
	include 'class_functions.php';
	$crud = new Action();

	//Login and Logout
	if($action == 'login_admin'){
		$login = $crud->login_admin();
		if($login)
			echo $login;
	}
	if($action == 'logout_admin'){
		$logout = $crud->logout_admin();
		if($logout)
			echo $logout;
	}

	//User
	if($action == 'save_user'){
		$save = $crud->save_user();
		if($save)
			echo $save;
	}
	if($action == 'update_user'){
		$save = $crud->update_user();
		if($save)
			echo $save;
	}
	if($action == 'delete_user'){
		$save = $crud->delete_user();
		if($save)
			echo $save;
	}

	//Subject
	if($action == 'save_subject'){
		$save = $crud->save_subject();
		if($save)
			echo $save;
	}
	if($action == 'delete_subject'){
		$save = $crud->delete_subject();
		if($save)
			echo $save;
	}
	if($action == 'save_subject_excel'){
		$save = $crud->save_subject_excel();
		if($save)
			echo $save;
	}

	//Class
	if($action == 'save_class'){
		$save = $crud->save_class();
		if($save)
			echo $save;
	}
	if($action == 'save_class_excel'){
		$save = $crud->save_class_excel();
		if($save)
			echo $save;
	}
	if($action == 'delete_class'){
		$save = $crud->delete_class();
		if($save)
			echo $save;
	}
	if($action == 'add_student'){
		$save = $crud->add_student();
		if($save)
			echo $save;
	}
	if($action == 'remove_student'){
		$save = $crud->remove_student();
		if($save)
			echo $save;
	}
	if($action == 'save_schedule_excel'){
		$save = $crud->save_schedule_excel();
		if($save)
			echo $save;
	}

	//School Year
	if($action == 'save_schoolyear'){
		$save = $crud->save_schoolyear();
		if($save)
			echo $save;
	}
	if($action == 'delete_schoolyear'){
		$save = $crud->delete_schoolyear();
		if($save)
			echo $save;
	}
	if($action == 'make_default'){
		$save = $crud->make_default();
		if($save)
			echo $save;
	}

	//Criteria
	if($action == 'save_criteria'){
		$save = $crud->save_criteria();
		if($save)
			echo $save;
	}
	if($action == 'delete_criteria'){
		$save = $crud->delete_criteria();
		if($save)
			echo $save;
	}

	//Questionnaire
	if($action == 'save_question_excel'){
		$save = $crud->save_question_excel();
		if($save)
			echo $save;
	}
	if($action == 'save_question'){
		$save = $crud->save_question();
		if($save)
			echo $save;
	}
	if($action == 'delete_question'){
		$save = $crud->delete_question();
		if($save)
			echo $save;
	}

	//Faculty
	if($action == 'save_faculty'){
		$save = $crud->save_faculty();
		if($save)
			echo $save;
	}
	if($action == 'save_faculty_excel'){
		$save = $crud->save_faculty_excel();
		if($save)
			echo $save;
	}
	if($action == 'delete_faculty'){
		$save = $crud->delete_faculty();
		if($save)
			echo $save;
	}

	//Student
	if($action == 'save_student'){
		$save = $crud->save_student();
		if($save)
			echo $save;
	}
	if($action == 'save_student_excel'){
		$save = $crud->save_student_excel();
		if($save)
			echo $save;
	}
	if($action == 'delete_student'){
		$save = $crud->delete_student();
		if($save)
			echo $save;
	}

	//Evaluation Report for Faculty
	if($action == 'get_reportFaculty'){
		$get = $crud->get_reportFaculty();
		if($get)
			echo $get;
	}

	//Evaluation Report for Department
	if($action == 'get_reportDepartment'){
		$get = $crud->get_reportDepartment();
		if($get)
			echo $get;
	}

	//Evaluation Status for Faculty
	if($action == 'get_evaluation_status'){
		$get = $crud->get_evaluation_status();
		if($get)
			echo $get;
	}

	//Get faculty to be inserted in a select in evaluation status
	if($action == 'get_faculty_status'){
		$get = $crud->get_faculty_status();
		if($get)
			echo $get;
	}

	//Get faculty to be inserted in a select
	if($action == 'get_faculty'){
		$get = $crud->get_faculty();
		if($get)
			echo $get;
	}

	//Get department to be inserted in a select
	if($action == 'get_department'){
		$get = $crud->get_department();
		if($get)
			echo $get;
	}

	ob_end_flush();
?>
