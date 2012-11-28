<?php
include "geshi.php";
try {
// nom du fichier à éditer en GET ou POST
$filename = '';
if ( isset($_GET['filename']) ) {
	$filename = $_GET['filename'];
} else {
	if ( isset($_POST['filename']) ) {
		$filename = $_POST['filename'];
	} // endif
} // endif

// nom du ficher qui contient la liste des fichiers à présenter dans la liste
$fileindex = 'index.txt'; 
if ( isset($_GET['fileindex']) ) {
	$fileindex = $_GET['fileindex'];
} else {
	if ( isset($_POST['fileindex']) ) {
		$fileindex = $_POST['fileindex'];
	} //endif
} // endif

// download du fichier si submit via le bouton Télécharger
if ( isset($_POST["download"]) ) {
	header ("Content-Type: application/octet-stream");
	header ("Accept-Ranges: bytes");
	header ("Content-Length: ".filesize($filename));
	header ("Content-Disposition: attachment; filename=".$filename);
	readfile($filename);
	exit;
} // endif

// enregistrement du fichier si submit via le bouton Enregistrer
if ( isset($_POST["enregistrer"]) && isset($_POST["file_content"]) ) {
	$fp = fopen($filename, 'w');
	fwrite($fp, $_POST["file_content"]);
	fclose($fp);
} // endif

// lecture de filename
if ( $filename != "" ) {
	$fr = fopen($filename, "r");
	$contents = htmlentities(fread($fr, filesize($filename)), ENT_QUOTES);
	fclose($fr);
} // endif
// lecture de fileindex
if ( $fileindex != "" ) {
	$fri = fopen($fileindex, "r");
	$c = htmlentities(fread($fri, filesize($fileindex)), ENT_QUOTES);
	$fileindex_a = preg_split("/[\s,]+/", $c);
	fclose($fri);
} // endif

// en Edition ?
$isEnEdition = false;
if ( isset($_POST["editer"]) ) {
	 $isEnEdition = true;
} // endif

// calcul du langage du source du fichier
$language = 'conf';
$ext = pathinfo($filename, PATHINFO_EXTENSION); 
echo $ext;
if ( $ext != "" ) {
	$language = strtolower($ext);
} // endif
// code geshi
if ( ! $isEnEdition ) {
	$geshi = new GeSHi($contents);
	$geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS);
	$contents = $geshi->parse_code(); 	
} // endif
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv='pragma' content='no-cache' />
<meta name="publisher" content="eclipse for php developpers" />
<meta name="copyright" content="philippe.billerot@gmail.com" />
<meta name="description" content="Configur@cteur" />
<title>Configur@cteur</title>
<!-- Le styles -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<style>
body {
	/* padding-top: 60px;*/
	/* 60px to make the container go all the way to the bottom of the topbar */
}

.navbar {
	width: 100%;
}

.container {
	width: 100%;
}
</style>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"
	media="screen">
</head>
<body>
	<form name="maForm" method="POST">
<!-- 
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse"
						data-target=".nav-collapse"> <span class="icon-bar"></span> <span
						class="icon-bar"></span> <span class="icon-bar"></span>
					</a> <a class="brand" href="#">Configur@cteur</a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="active"><a href="#">Home</a></li>
							<li><a href="#about">About</a></li>
							<li><a href="#contact">Contact</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
-->
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span3">
					<ul class="nav nav-list">
						<li class="nav-header"><?php echo strtoupper(pathinfo($fileindex, PATHINFO_FILENAME)); ?></li>
						<?php foreach ($fileindex_a as $fi): ?>
						<li><a
							href="<?php echo $_SERVER["PHP_SELF"].'?fileindex='.$fileindex.'&filename='.$fi; ?>"><?php echo $fi; ?>
						</a></li>
						<?php endforeach;?>
					</ul>
				</div>
				<!--/.span -->
				<div class="span9">
					<div class="form-inline">
						<label class="">Nom du fichier :</label> <input class="span4"
							size="50" type="text" name="filename" id="filename"
							value="<?php echo $filename ?>" />
						<button class="btn btn-primary" type="submit" name="ouvrir">Ouvrir</button>
						<button class="btn btn-inverse" type="submit" name="download">Télécharger</button>
						<?php if ( $isEnEdition ) :?>
						<button class="btn btn-success" type="submit" name="enregistrer">Enregistrer</button>
						<?php else :?>
						<button class="btn btn-success" type="submit" name="editer">Editer</button>
						<?php endif;?>
					</div>
					<div class=" ">
						<?php if ( $isEnEdition ) :?>
							<textarea  class="span12" name="file_content" id="file_content"
							rows="30" cols="120"><?php echo $contents; ?></textarea>
						<?php else :?>
							<div  class="span12" ><?php echo $contents; ?></div>
						<?php endif;?>
					</div>
				</div>
				<!--/.span -->
			</div>
			<!--/row-->
<!-- 			
			<hr>
			<footer>
				<p>&copy; CA-CMDS 2012</p>
			</footer>
-->
		</div>
		<!--/.fluid-container-->

		<input type="hidden" name="fileindex"
			value="<?php echo $fileindex; ?>" />
	</form>
	<script src="bootstrap/js/jquery.js"></script>
	<script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
