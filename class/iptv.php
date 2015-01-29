<?php 
	include_once('database.php');

	class Iptv extends Database{

		public function getChannels(){
			$conn = $this->conn();

			$query = "SELECT * FROM channels ORDER BY name";
			$channels = $conn->query($query);

			$this->close($conn);

			return $channels;
		}
		
		public function getChannelsAdmin(){
			$conn = $this->conn();

			$query = "SELECT * FROM channels ORDER BY status DESC, name";
			$channels = $conn->query($query);

			$this->close($conn);

			return $channels;
		}

		public function getChannel($id){
			$conn = $this->conn();

			$query = "SELECT url, name FROM channels WHERE url_name='" . $id . "'";
			$channel = $conn->query($query);

			$this->close($conn);

			return $channel;
		}

		public function uploadPlaylist($file){
			if($file['name'] != 'iptv.xspf'){
				return '<div class="alert alert-danger" role="alert">
				  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
				  <span class="sr-only">Error:</span>
				  Name must be iptv.xspf!
				</div>';
			}else{
				if(move_uploaded_file($file['tmp_name'], 'iptv.xspf')){
					$conn = $this->conn();
	                $query = "DELETE FROM channels";
					
					if($conn->query($query)){
						$iptv = simplexml_load_file('iptv.xspf');
						foreach ($iptv->trackList->track as $track) {
							$query_insert = "INSERT INTO channels VALUES('', '" . $track->title . "', '" . $track->location . "', '" . preg_replace('/\s+/', '_', $track->title) . "', '0')";
							$conn->query($query_insert);
						}

						return '<div class="alert alert-success" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only"></span>
						  Success!
						</div>';
					}

					$this->close($conn);
	            }
			}
		}

		public function getFavourite(){
			$conn = $this->conn();

			$query = "SELECT * FROM channels WHERE status='1' ORDER BY name";
			$channels = $conn->query($query);

			$this->close($conn);

			return $channels;
		}
		
		public function favChannel($id, $status){
			$conn = $this->conn();

			$query = "UPDATE channels SET status='" . $status . "' WHERE id='" . $id . "'";
			$fav = $conn->query($query);

			$this->close($conn);

			return '<div class="alert alert-success" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only"></span>
			  Success!
			</div>';
		}

		public function getHash(){
			$conn = $this->conn();

			$query = "SELECT * FROM settings WHERE name='hash'";
			$hash = $conn->query($query);

			$this->close($conn);

			return $hash;
		}

		public function setHashVolimTv($hash){
			$conn = $this->conn();

			$channels = $this->getChannels();
			$hash_f = $this->getHash();
			$hs = mysqli_fetch_array($hash_f);

			while($ch = mysqli_fetch_array($channels)){
				if(strpos($ch['url'], 'hash=')){

					$replace = str_replace('hash=' . $hs['value'], 'hash=' . $hash, $ch['url']);

					$query_url = "UPDATE channels SET url='" . $replace . "' WHERE id='" . $ch['id'] . "'";
					$hash_url = $conn->query($query_url);
				}
			}

			$query_h = "UPDATE settings SET value='" . $hash . "' WHERE name='hash'";
			$hash_h = $conn->query($query_h);

			$this->close($conn);

			return '<div class="alert alert-success" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only"></span>
			  Success!
			</div>';
		}
		
		public function editChannel($id, $name, $url){
			$conn = $this->conn();

			$query = "UPDATE channels SET name='" . $name . "', url='" . $url . "', url_name='" . preg_replace('/\s+/', '_', $name) . "'  WHERE id='" . $id . "'";
			$edit = $conn->query($query);

			$this->close($conn);

			return '<div class="alert alert-success" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only"></span>
			  Success!
			</div>';
		}
		
		public function deleteChannel($id){
			$conn = $this->conn();

			$query = "DELETE FROM channels WHERE id='" . $id . "'";
			$delete = $conn->query($query);

			$this->close($conn);
			
			return '<div class="alert alert-success" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only"></span>
			  Success!
			</div>';
		}
		
		public function exportPlaylist(){
			$conn = $this->conn();
			
			$channels = $this->getChannels();
			$xml = new DOMDocument("1.0", "UTF-8");

			$playlist = $xml->createElement('playlist');
			$playlist->setAttribute( 'xmlns', 'http://xspf.org/ns/0/');
			$playlist->setAttribute( 'xmlns:vlc', 'http://www.videolan.org/vlc/playlist/ns/0/');
			$playlist->setAttribute( 'version', '1');
			$xml->appendChild($playlist);

			$title = $xml->createElement('title', 'Playlist');
			$trackList = $xml->createElement('trackList');
			$playlist->appendChild($title);
			$playlist->appendChild($trackList);



			while($ch = mysqli_fetch_array($channels)){
				$track = $xml->createElement('track');

				$location = $xml->createElement('location', $ch['url']);
				$title = $xml->createElement('title', $ch['name']);
				$track->appendChild($location);
				$track->appendChild($title);

				$trackList->appendChild($track);	
			}
			$xml->save('./export/playlist.xspf');
			
			return '<div class="alert alert-success" role="alert">
			  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
			  <span class="sr-only"></span>
			  Success! <a href="./download.php">Download playlist</a>
			</div>';

			$this->close($conn);
		}
	}
 ?>