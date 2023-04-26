<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Chat application in php using web socket programming</title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<script type="text/javascript" src="assets/js/jquery-3.6.4.min.js"></script>
</head>
<body>
	<div class="container">
		<h2 class="text-center" style="margin-top: 5px; padding-top: 0;">
			Chat application in php & mysql using ratchet library
		</h2>
		<hr>
		<?php 
			if(isset($_POST['join'])){
				session_start();
				
				require("db/DB.php");
				$db = new DB();

				$email = $_POST['email'];
				$uname = $_POST['uname'];
				$loginStatus = 1;
				$lastLogin = date('Y-m-d h:i:s');

			 	$userData = $db->getUserByEmail($email);

			 	if($userData){
			 		$updated = $db->updateLoginStatus($loginStatus, $lastLogin, $userData['id']);
			 		if($updated) {
			 			echo "User login..";
			 			$_SESSION['user'][$userData['id']] = $userData;
			 			header("location: chatroom.php");
			 		}
			 		else{
			 			echo "Failed to login.";
			 		}
			 	}
			 	else{
			 		$lastId = $db->save($uname, $email, $loginStatus, $lastLogin);
				 	if($lastId){

				 		$userData = $db->getUserByEmail($email);

						$_SESSION['user'][$lastId] = [ 
							'id' => $userData["id"], 
							'name' => $userData["name"], 
							'email' => $userData["email"], 
							'login_status' => $userData["login_status"], 
							'last_login' => $userData["last_login"] 
						];

				 		echo "User Registred..";
				 		header("location: chatroom.php");
				 	}
				 	else{
				 		echo "Failed..";
				 	}
				}
			}
		?>
		<div class="row join-room">
			<div class="col-md-6 col-md-offset-3">
				<form id="join-room-frm" role="form" method="post" action="" class="form-horizontal">
					<div class="form-group">
	                  	<div class="input-group">
	                        <div class="input-group-addon addon-diff-color">
	                            <span class="glyphicon glyphicon-user"></span>
	                        </div>
	                        <input type="text" class="form-control" id="uname" name="uname" placeholder="Enter Name">
	                  	</div>
	                </div>
					<div class="form-group">
	                	<div class="input-group">
	                        <div class="input-group-addon addon-diff-color">
	                            <span class="glyphicon glyphicon-envelope"></span>
	                        </div>
	                    	<input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" value="">
	                	</div>
	                </div>
	                <div class="form-group">
	                    <input type="submit" value="JOIN CHATROOM" class="btn btn-success btn-block" id="join" name="join">
	                </div>
			    </form>
			</div>
		</div>
	</div>
</body>
</html>