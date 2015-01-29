<?php 
	include_once('database.php');

	class User extends Database{

		public function login($username, $password){
			$conn = $this->conn();

			$query = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'";
			$user = $conn->query($query);

			$this->close($conn);

			return $user;
		}
	}
 ?>