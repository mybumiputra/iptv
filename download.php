<?php
header("Content-disposition: attachment; filename=playlist.xspf");
header("Content-type: application/xspf+xml");
readfile("./export/playlist.xspf");
?>