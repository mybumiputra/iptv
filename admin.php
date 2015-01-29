<?php 
	session_start();

	include_once('./class/iptv.php');
	include_once('./class/user.php');

	$iptv = new Iptv();
	$user = new user();
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin | IPTV</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-theme.min.css">
	<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="index.php"><span class="glyphicon glyphicon-home"></span></a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav">
               <?php
               	$channels_f = $iptv->getFavourite();

                while($ch = mysqli_fetch_array($channels_f)){
                 	echo "<li><a href='index.php?channel=" . $ch['url_name'] . "#" . $ch['url_name'] . "'><p class='glyphicon glyphicon-play-circle'></p> " . strtoupper($ch['name']) . "</a></li>";

                }
               	?>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-4"></div>
			<div class="col-md-4">
				<?php 
					if(isset($_POST['login'])){
						$username = $_POST['username'];
						$password = md5($_POST['password']);
						
						$user = $user->login($username, $password);

						$u = mysqli_num_rows($user);

						if($u == 1){
							$_SESSION['loged'] = 'loged';

							header('Location:upload.php');
						}else{
							echo '<div class="alert alert-danger" role="alert">
							  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
							  <span class="sr-only">Error:</span>
							  Invalid login!
							</div>';
						}
					}
				 ?>
				<form method="POST" action="admin.php">
					<div class="form-group"><label for="file">Username</label><input type="text" name="username" class="form-control"></div>
					<div class="form-group"><label for="file">Password</label><input type="password" name="password" class="form-control"></div>
					<button type="submit" class="btn btn-primary form-control" name="login">Login</button>
		        </form>
	        </div>
	        <div class="col-md-4"></div>
        </div>
	</div>
        <nav class="navbar navbar-inverse" style="margin-top:10px;">
	  <div class="container-fluid">
	      <ul class="nav navbar-nav">
               <li><a href="mailto:zarkostanisic@live.com">&copy;&reg; Žarko Staniši&#263;</a></li>
	      </ul>
	  </div><!-- /.container-fluid -->
	</nav>
</body>
</html>