<?php 
	session_start();

	unset($_SESSION['loged']);
	session_destroy();

	header('Location:index.php');
 ?>