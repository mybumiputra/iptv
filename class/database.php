<?php 
	class Database{
		private $host = "localhost";
		private $user = "root";
		private $pass = "";
		private $database = "stream";

		public function conn(){
			return mysqli_connect($this->host, $this->user, $this->pass, $this->database);
		}

		public function close($conn){
			mysqli_close($conn);
		}
	}
 ?>