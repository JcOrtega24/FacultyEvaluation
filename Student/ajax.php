<?php
	ob_start();
	date_default_timezone_set("Asia/Manila");

	/*if(isset($_GET['action'])) {
		$action = $_GET['action'];
		
		include 'class_functions.php';
		$crud = new Action();
		
		switch($action) {
			case 'login_student':
				$login = $crud->login_student();
				echo $login ? $login : "Login failed!";
				break;
			case 'logout_student':
				$logout = $crud->logout_student();
				echo $logout ? $logout : "Logout failed!";
				break;
			case 'save_evaluation':
				$save = $crud->save_evaluation();
				echo $save ? $save : "Evaluation save failed!";
				break;
			default:
				echo "Invalid action!";
				break;
		}
	} else {
		echo "No action specified!";
	}*/

	$action = $_GET['action'];
	include 'class_functions.php';
	$crud = new Action();
	if($action == 'login_student'){
		$login = $crud->login_student();
		if($login)
			echo $login;
	}
	if($action == 'logout_student'){
		$logout = $crud->logout_student();
		if($logout)
			echo $logout;
	}

	if($action == 'update_student'){
		$save = $crud->update_student();
		if($save)
			echo $save;
	}

	if($action == 'save_evaluation'){
		$save = $crud->save_evaluation();
		if($save)
			echo $save;
	}

	ob_end_flush();
?>
