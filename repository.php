<html>

<head>
	<title>Repositório de arquivos</title>
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
include_once("function.inc");

if ( isset( $_REQUEST[ "upload" ] ) ) {
	$ok = 4;
	$filetypes = array();
	$filetypes[] = "txt";
	$filetypes[] = "pdf";
	$filetypes[] = "ps";
	$filetypes[] = "xml";
	$filetypes[] = "zip";
	$filetypes[] = "gz";
	$filetypes[] = "bz2";
	if ( is_uploaded_file( $_FILES[ "uploads"][ "tmp_name" ] ) ) {
		foreach ( $filetypes as $filetype ) {
			if ( preg_match( "/\." . $filetype . "$/i", $_FILES[ "uploads" ][ "name" ] ) > 0 ) {
				$ok = 0;
			}
		}

		if ( $ok == 0 ) {
			$path = $PATH_UPLOAD  . "/" . $swiki_id . "/" . $_FILES[ "uploads" ][ "name" ];
			if ( file_exists( $path ) ) {
				// TODO: cvs update.
				$ok = 3;
			}	else {
				// TODO: cvs add
				copy( $_FILES['uploads']['tmp_name'], $path );
				chmod( $path, 0444 );
				$ok = 1;
			}
		}
	} else {
		$ok = 2;
	}
	echo "<script>StatusUpload( $ok )</script>";
}



// Check if the ident is valid (and extract the swiki id if it's valid).
$swiki_id = extract_swiki_id( $_REQUEST[ "wikipage_id" ] );
if ( $swiki_id == false ) {
	show_error( 0 );
}
$dbh = coteia_connect();
$query = "select title from swiki where id='$swiki_id'";
$result = mysql_query( $query, $dbh );
$tuple = mysql_fetch_array( $result );
$title = $tuple[ "title" ];

?>

<table width="70%" bgcolor="#E1F0FF" bordercolor="#C0C0C0" align="center" border="1">
<tr>
	<td>
		<img src="<?php echo "$URL_IMG/Dir_open.png";?>" />
		<b>Repositório de arquivos da swiki <?php echo "Swiki: $title";?></b>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo $URL_IMG;?>/Cvs.png" /><b>Lista de Arquivos</b>
		<div align="center">
			<form name="checkout" method="post" action="checkout.php">
			<br />
			<select name="filename" size="10" style="width: 60%">
			<?php
				// Abre lista_arquivos
				$repository_filenames = array();
				$repository = opendir( "$PATH_UPLOAD/$swiki_id/" );
				while ( $filename = readdir( $repository ) ) {
					if ( !eregi( "\.$", $filename ) ) {
						$repository_fileanames[] = $filename;
					}
				}
				closedir( $repository );
				sort( $repository_filenames );
				reset( $repository_filenames );
				foreach ( $repository_filenames as $filename ) {
					echo "\t<option value=\"$filename\">$filename</option>\n";
				}
			?>
			</select>
			<br />
			<input type="hidden" name="swiki_id" value="<?php echo $swiki_id;?>" />
			<input type="submit" name="submit" value="Pegar arquivo" onClick="return CheckChoosenFile( document.checkout.filename );" />
			</form>
		</div>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo $URL_IMG;?>/files2upload.png" /><b>Upload</b>
		<div align="center">
			<form enctype="multipart/form-data" method="post" name="upload" action="upload.php">
				<input type="file" size="40" name="filename" />
				<br />
				<input type="hidden" name="swiki_id" value="<?php echo $swiki_id;?>" />
				<input type="submit" name="submit" value="Enviar" />
			</form>
		</div>
	</td>
</tr>
</table>

<div align="center">
	<a href="show.php?ident=<?php echo $_REQUEST[ "wikipage_id" ];?>">
		<img src="<?php echo $URL_IMG;?>/back.png" />Voltar
	</a>
</div>

</body>

</html>
