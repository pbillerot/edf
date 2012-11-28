<?php

$file = $_GET['filename'];

header ("Content-Type: application/octet-stream");
header ("Accept-Ranges: bytes");
header ("Content-Length: ".filesize($file));
header ("Content-Disposition: attachment; filename=".$filename);
readfile($_GET['filename']);
?>
