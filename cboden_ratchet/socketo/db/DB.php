<?php
class DB{
	private $dbHost     = "localhost";
	private $dbUsername = "root";
	private $dbPassword = "";
	private $dbName     = "socketo";
	public function __construct(){
		if(!isset($this->db)){
			// Connect to the database
			$conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
			if($conn->connect_error){
				die("Failed to connect with MySQL: " . $conn->connect_error);
			}else{
				$this->db = $conn;
			}
		}
	}
	public function getDataById($id){
		$sql = "select * from users where users.id = '$id'";
		$result = $this->db->query($sql);
		$row = $result->fetch_assoc();
		return $row;
	}
	public function getUserByEmail($email){
		$sql = "select * from users where users.email = '$email'";
		$result = $this->db->query($sql);
		$row = $result->fetch_assoc();
		return $row;
	}
	public function updateLoginStatus($loginStatus, $lastLogin, $id) {
		$query = "update users set login_status='$loginStatus', last_login='$lastLogin' where id=$id";
		$update = $this->db->query($query);
		return $update?$this->db->affected_rows:false;
	}
	public function save($name, $email, $loginStatus, $lastLogin) {
		$query = "INSERT INTO users(name, email, login_status, last_login) VALUES ('$name', '$email', '$loginStatus', '$lastLogin')";
		$insert = $this->db->query($query);
		return $insert?$this->db->insert_id:false;
	}
	public function getAllUsers() {
		$sql = "select * from users";
		$result = $this->db->query($sql);
		$data = array();
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$data[] = $row;
			}
		}
		return $data;
	}
	public function saveChatRoom($userId, $message, $currentDatetime) {
		$query = "INSERT INTO chatrooms(user_id, message, created_at) VALUES ('$userId', '$message', '$currentDatetime')";
		$insert = $this->db->query($query);
		return $insert?$this->db->insert_id:false;
	}
	public function getAllChatRooms() {
		$sql = "select chatrooms.*, users.name from chatrooms join users on chatrooms.user_id=users.id order by chatrooms.id desc";
		$result = $this->db->query($sql);
		$data = array();
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				$data[] = $row;
			}
		}
		return $data;
	}
}