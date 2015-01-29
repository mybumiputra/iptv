<?php 
	session_start();

	if(!isset($_SESSION['loged']) || $_SESSION['loged'] != 'loged'){
		header('location:index.php');
	}

	include_once('./class/iptv.php');

	$iptv = new Iptv();
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Upload | IPTV</title>
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
                 	echo "<li><a href='index.php?channel=" . $ch['url_name'] . "#" . $ch['url_name'] . "' ><p class='glyphicon glyphicon-play-circle'></p> " . strtoupper($ch['name']) . "</a></li>";
                }
               	?>
               	<li><a href='logout.php'><p class='glyphicon glyphicon-remove-sign'></p> Logout</a>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
		        <h1>Upload Playlist</h1>
		        <?php 
					 if(isset($_POST['upload'])){
						$file = $_FILES['file'];
						echo $iptv->uploadPlaylist($file);
					 }
		        ?>
		        <?php 
					 if(isset($_POST['export'])){
						echo $iptv->exportPlaylist();
					 }
		        ?>
				<form method="POST" action="upload.php" enctype="multipart/form-data" >
					<div class="form-group">
					<label for="file">Playlist</label>
			        <input type="file" name="file" class="form-control">
			        </div>
					<button type="submit" class="btn btn-primary" name="upload">Upload</button>
					<button type="submit" class="btn btn-info" name="export">Export</button>
				</form>
	        </div>
	        <div class="col-md-6">
				<h1>Hash For Volim.Tv</h1>
		        <?php 
					 if(isset($_POST['set_hash'])){
						$hash_value = $_POST['hash_value'];
						
						echo $iptv->setHashVolimTv($hash_value);
					 }

					 $hash = $iptv->getHash();
					 $hs = mysqli_fetch_array($hash);
		        ?>
				<form method="POST" action="upload.php" enctype="multipart/form-data">
					<div class="form-group"><label for="file">Hash</label><input type="text" name="hash_value" class="form-control" value="<?php echo $hs['value']; ?>"></div>
					<button type="submit" class="btn btn-primary" name="set_hash">Set hash</button>
		        </form>
	        </div>
        </div>
		<div class="row">
			<div class="col-md-12">
	        	<h1>Manage Playlist Flash</h1>
	        	<?php 
		        	if(isset($_POST['ch_edit'])){
						$ch_id = $_POST['ch_edit'];
		        		$ch_url_edit = $_POST['ch_url_edit'][$ch_id];
		        		$ch_name_edit = $_POST['ch_name_edit'][$ch_id];
		        		echo $iptv->editChannel($ch_id, $ch_name_edit, $ch_url_edit);
		        	}

		        	if(isset($_POST['ch_delete'])){
		        		$ch_id = $_POST['ch_delete'];
		        		
		        		echo $iptv->deleteChannel($ch_id);
		        	}
					
					if(isset($_POST['ch_fav'])){
		        		$ch_fav = $_POST['ch_fav'];
		        		
		        		echo $iptv->favChannel($ch_fav, '1');
		        	}
					
					if(isset($_POST['ch_unfav'])){
		        		$ch_unfav = $_POST['ch_unfav'];
		        		
		        		echo $iptv->favChannel($ch_unfav, '0');
		        	}
		         ?>
		        <table class="table table-responsive row">
		        <thead>
		        	<tr>
		        		<th class="col-md-5">Name</th>
		        		<th class="col-md-5">Url</th>
		        		<th class="col-md-2">Option</th>
		        	</tr>
		        </thead>
		        <tbody>
	        	<form method="POST" action="upload.php" enctype="multipart/form-data">
	        		<?php 

	        			$channels = $iptv->getChannelsAdmin();

						while($ch = mysqli_fetch_array($channels)){
							echo '<tr><td><div class="form-group">
					        <label for="flash_url_edit"></label>
					        <input type="text" name="ch_name_edit[' . $ch['id'] . ']" value="' . $ch['name'] . '" class="form-control">
					        </div></td>';
					        echo '<td><div class="form-group">
					        <label for="flash_url_edit"></label>
					        <input type="text" name="ch_url_edit[' . $ch['id'] . ']" value="' . $ch['url'] . '" class="form-control">
					        </div></td><td>
					        <div class="form-group">';
							if($ch['status'] == 0){
							echo '<button type="submit" class="btn btn-success" name="ch_fav" value="' . $ch['id'] . '">Fav</button>';
							}else{
							echo '<button type="submit" class="btn btn-warning" name="ch_unfav" value="' . $ch['id'] . '">UnFav</button>';
							}
							echo '<button type="submit" class="btn btn-primary" name="ch_edit" value="' . $ch['id'] . '">Edit</button>
							<button type="submit" class="btn btn-danger" name="ch_delete" value="' . $ch['id'] . '">Delete</button>
					        </div>
					        </td</tr>';
						}
	        		 ?>
		        </form>
		        </tbody>
				</table>
	        </div>
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