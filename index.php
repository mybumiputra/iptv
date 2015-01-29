<?php 
	include_once('./class/database.php');
	include_once('./class/iptv.php');

	$iptv = new Iptv();
	$database = new Database();
	$conn = $database->conn();
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>IPTV</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="bootstrap-theme.min.css">
	<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<style type="text/css">
		.embed-container {
		position: relative;
		padding-bottom: 56.25%; /* 16/9 ratio */
		padding-top: 30px; /* IE6 workaround*/
		height: 0;
		}
		.embed-container iframe,
		.embed-container object,
		.embed-container embed {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
}
	</style>
</head>
<body>
	<nav class="navbar navbar-default">
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
                 	echo "<li><a href='index.php?channel=" . $ch['url_name'] . "#" . $ch['url_name'] . "' ><p class='glyphicon glyphicon-play-circle'></p> " . strtoupper($ch['name']) . "</a></li>";
                }
               	?>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	    <!-- Collect the nav links, forms, and other content for toggling -->
	  </div><!-- /.container-fluid -->
	</nav>
	<div class="container-fluid">
		<div class="col-md-9">
			<?php
			if(isset($_GET['channel'])){
				$id = $_GET['channel'];

				$channel = $iptv->getChannel($id);
				$ch = mysqli_fetch_array($channel);
			?>
			<h1><?php echo strtoupper($ch['name']); ?></h1>
			<div class="embed-container">
			<object>
			<embed 
				type="application/x-vlc-plugin" 
				pluginspage="http://www.videolan.org" 
				version="VideoLAN.VLCPlugin.2"
				width="640"
				height="480"
				target="<?php echo $ch['url']; ?>"
				id="vlc">
			</embed>
			</object>
			</div>
			<?php
			}else{ ?>
			<h1>Please choose channel ---></h1>
			<img src="front.jpg" class="img-responsive" alt="Responsive image">
			<?php
			}
			?>
		</div>
		<div class="col-md-3">
			<ul class="list-group" style="height: auto;max-height: 480px;overflow-x: hidden;">
			<?php
				$channels = $iptv->getChannels();

				while($ch = mysqli_fetch_array($channels)){
					echo "<li class='list-group-item'>
						<a href='index.php?channel=" . $ch['url_name'] . "#" . $ch['url_name'] . "' id='" . $ch['url_name'] . "'><p class='glyphicon glyphicon-play-circle'></p> " . strtoupper($ch['name']) . "</a>
					</li>";
				}
			?>
			</ul>
		</div>
	</div>
        <nav class="navbar navbar-inverse" style="margin-top:10px;">
	  <div class="container-fluid">
	      <ul class="nav navbar-nav">
               <li><a href="mailto:zarkostanisic@live.com">&copy;&reg; Žarko Staniši&#263;</a></li>
	      </ul>
	      	   <ul class="nav navbar-nav pull-right">
               <li><a href="admin.php"><p class='glyphicon glyphicon-ok-sign'></p></a></li>
	      </ul>
	  </div><!-- /.container-fluid -->
	</nav>
</body>
</html>