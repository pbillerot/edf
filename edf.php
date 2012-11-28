<?php
/*
 * Editeur De Fichiers PHP
 */
$contents = "";
$filename = "";
$isEnEdition = false;
$charset = 'ISO-8859-1';
$fileindex = 'index.txt';
$fileindex_a = array();
define("COPYRIGHT" , "&copy; La Toilerie du Poitou 2012");

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

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="content-type" content="text/html; charset=<?php echo $charset; ?>" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="-1" />
<meta http-equiv='pragma' content='no-cache' />
<meta name="publisher" content="La Webisserie du Poitou" />
<meta name="copyright" content="philippe.billerot@gmail.com" />
<meta name="description" content="EDITEUR PHP" />
<link href="bootstrap/css/boottheme.css" rel="stylesheet">
<!-- Le styles -->
<link href="bootstrap/css/boottheme.css" rel="stylesheet">
<body>
<form name="maForm" method="POST">
<div class="container-fluid">
			<div class="row-fluid">
			<div class="span12">
				<div class="navbar-form well well-small">	
					<button class="btn btn-warning" title="<?php echo $fileindex; ?>" type="button" onClick="javascript: window.location='<?php echo $_SERVER["PHP_SELF"].'?fileindex='.$fileindex.'&filename='.$fileindex; ?>';">&hearts;</button>
					<?php foreach ($fileindex_a as $fi): ?>		
						<button class="btn btn-info" type="button" onClick="javascript: window.location='<?php echo $_SERVER["PHP_SELF"].'?fileindex='.$fileindex.'&filename='.$fi; ?>';"><?php echo $fi; ?></button>
					<?php endforeach;?>
				</div>
				<div class="navbar-form well well-small">Fichier :
					
					<input class="span5" size="50" type="text" name="filename"	id="filename" value="<?php echo $filename ?>" />
					<button class="btn" type="submit" name="ouvrir">Ouvrir</button>
					<input class="span2" size="50" type="text" name="charset"id="charset" value="<?php echo $charset?>" disabled="disabled"/>
					<!-- 
					<select class="span2 input-medium" name="charset" >
  						<option value="ISO-8859-1" <?php echo $charset == 'ISO-8859-1' ? 'selected' : '';?>>ISO-8859-1</option>
  						<option value="UTF-8" <?php echo $charset == 'UTF-8' ? 'selected' : '';?>>UTF-8</option>
  					</select>
  					 -->
					<?php if ( $isEnEdition ) :?>
						<button class="btn btn-success" type="submit" name="enregistrer">Enregistrer</button>
					<?php else :?>
						<button class="btn btn-success" type="submit" name="editer">Editer</button>
					<?php endif;?>
				</div>
				<div class="well well-small">
				<?php if ( $isEnEdition ) :?>
					<textarea class="span12" name="file_content" id="file_content" rows="35"><?php echo $contents; ?></textarea>
				<?php else :?>
					<textarea class="span12" disabled="disabled" name="file_content" id="file_content" rows="30"><?php echo $contents; ?></textarea>
				<?php endif;?>
				</div>
			</div>
			</div>
			<div class="footer">
		<?php echo constant("COPYRIGHT"); ?> 
		</div>
	</div>
</form>
</body>
</html>
