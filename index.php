<?php
/*
 * Editeur De Fichiers PHP sur edf2
 */
$contents = "";
$filename = "";
$isEnEdition = false;
$charset = 'ISO-8859-1';
$fileindex = 'index.txt';
$fileindex_a = array();
define("COPYRIGHT" , "&copy; La Toilerie du Poitou 2012");
define("DESCRIPTION" , "Editeur de fichier");

try 
{
	// récupération du nom du fichier en GET ou POST
	if ( isset($_GET['filename']) ) {
		$filename = $_GET['filename'];
	} else {
		if ( isset($_POST['filename']) ) {
			$filename = $_POST['filename'];
		} // endif
	} // endif
	
	// en Edition ?
	if ( isset($_POST["editer"]) ) {
		$isEnEdition = true;
	} // endif
	
	// récupération du nom du fichier en GET ou POST
	if ( isset($_GET['charset']) ) {
		$charset = $_GET['charset'];
	} else {
		if ( isset($_POST['charset']) ) {
			$charset = $_POST['charset'];
		} // endif
	} // endif
	
	// nom du ficher qui contient la liste des fichiers à présenter dans la liste
	if ( isset($_GET['fileindex']) ) {
		$fileindex = $_GET['fileindex'];
	} else {
		if ( isset($_POST['fileindex']) ) {
			$fileindex = $_POST['fileindex'];
		} //endif
	} // endif
	
	// enregistrement du fichier si submit via le bouton Enregistrer
	if ( isset($_POST["enregistrer"]) && isset($_POST["file_content"]) ) {
		$fp = fopen($filename, 'w');
		if ( $fp ) {
			$data = $_POST["file_content"];
			//if ($sEncoding = mb_detect_encoding($data, 'auto', true) != $charset)
			//	$data = mb_convert_encoding($data, $charset, $sEncoding);
			//$data = mb_convert_encoding($data, $charset, $sEncoding);
			fwrite($fp, $data );
			fclose($fp);
		} // endif
	} // endif
	
	// lecture de fileindex
	if ( $fileindex != "" ) {
		if ( file_exists($fileindex) ) {
			$fri = fopen($fileindex, "r");
			$c = htmlentities(fread($fri, filesize($fileindex)), ENT_QUOTES);
			$fileindex_a = preg_split("/[\s,]+/", $c);
			fclose($fri);
		} // endif
	} // endif
	
	// lecture du fichier si filename trouvé dans la request GET ou POST
	if ( $filename != "" ) {
		if ( file_exists($filename) ){
			$fr = fopen($filename, "r");
			$data = fread($fr, filesize($filename));
			$charset2 = mb_detect_encoding($data, 'auto', true);
			if ( $charset2 != 'UTF-8' ) $charset = 'ISO-8859-1'; else $charset = $charset2;
			$contents = htmlentities($data, ENT_QUOTES, $charset);
			fclose($fr);
		} // endif
	} // endif
} catch (Exception $e) {
	echo 'Caught exception: ',  $e->getMessage(), "\n";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset; ?>" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv='pragma' content='no-cache' />
<meta name="publisher" content="Eclipse-php sous Xubuntu" />
<meta name="copyright" content="<?php echo constant("COPYRIGHT"); ?>" />
<meta name="description" content="<?php echo constant("DESCRIPTION"); ?>" />
<title><?php echo constant("DESCRIPTION"); ?></title>
<!-- Le styles -->
<link href="bootstrap/css/bootstrap.css" rel="stylesheet">
<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
</style>
<link href="bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
</head>
<body>
	<form name="maForm" method="POST">

		<div id="header" class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="btn btn-navbar" data-toggle="collapse"
						data-target=".nav-collapse"> 
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a class="brand" href="#"><?php echo constant("DESCRIPTION"); ?></a>
					<div class="nav-collapse navbar-responsive-collapse in collapse" style="heikght: auto;">
						<ul class="nav">
							<li class="active"><a href="<?php echo $_SERVER["PHP_SELF"].'?fileindex='.$fileindex.'&filename='.$fileindex; ?>"><?php echo strtoupper($fileindex); ?></a></li>
													<li><a href="<?php echo $_SERVER["PHP_SELF"].'?fileindex='.$fileindex.'&filename='.$fileindex; ?>"><?php echo strtoupper($fileindex); ?></a></li>
													<li><a href="<?php echo $_SERVER["PHP_SELF"].'?fileindex='.$fileindex.'&filename='.$fileindex; ?>"><?php echo strtoupper($fileindex); ?></a></li>
												</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span3">
					<div class="well sidebar-nav">
						<ul class="nav nav-list">
						<?php foreach ($fileindex_a as $fi): ?>
						<li><a
							href="<?php echo $_SERVER["PHP_SELF"].'?fileindex='.$fileindex.'&filename='.$fi; ?>"><?php echo $fi; ?></a></li>
						<?php endforeach;?>
						</ul>
					</div>
				</div>
				<div class="span9">
					<div class="navbar-form well well-small">Fichier :
						
						<input class="span5" size="50" type="text" name="filename"	id="filename" value="<?php echo $filename ?>" />
						<button class="btn" type="submit" name="ouvrir">Ouvrir</button>
						<input class="span2" size="50" type="text" name="charset"id="charset" value="<?php echo $charset?>" disabled="disabled"/>
						<?php if ( $isEnEdition ) :?>
							<button class="btn btn-success" type="submit" name="enregistrer">Enregistrer</button>
						<?php else :?>
							<button class="btn btn-success" type="submit" name="editer">Editer</button>
						<?php endif;?>
					</div>
					<?php if ( $isEnEdition ) :?>
						<textarea class="span12" name="file_content" id="file_content" rows="35"><?php echo $contents; ?></textarea>
					<?php else :?>
						<textarea class="span12" disabled="disabled" name="file_content" id="file_content" rows="30"><?php echo $contents; ?></textarea>
					<?php endif;?>
				</div>
				<!--/.span -->
			</div>
			<!--/row-->
			<div class="footer">
			<hr/>
			<?php echo constant("COPYRIGHT"); ?> 
			</div>
			
		</div>
		<!--/.fluid-container-->

		<input type="hidden" name="fileindex"
			value="<?php echo $fileindex; ?>" />
	</form>
</body>
</html>
