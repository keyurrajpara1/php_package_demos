<?php 
	session_start();
	if(isset($_POST['action']) && $_POST['action'] == 'leave') {
		require("db/DB.php");
		$db = new DB();

		$loginStatus = 0;
		$lastLogin = date('Y-m-d h:i:s');
		$id = $_POST['userId'];

		$updated = $db->updateLoginStatus($loginStatus, $lastLogin, $id);

	 	if($updated) {
	 		unset($_SESSION['user']);
	 		session_destroy();
	 		echo json_encode(['status'=>1, 'msg'=>"Logout.."]);
	 	}
	 	else{
	 		echo json_encode(['status'=>0, 'msg'=>"Somthing went wrong.."]);
	 	}
	}
 ?>