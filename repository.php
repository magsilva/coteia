<?php
/**
* Control a swiki's repository, also controlling files uploading.
*
* @param swiki_id [string] Identifier of a swiki
* @param index [string] Name of a wikipage (must be set only when creating
* a wikipage).
*
* Copyright (C) 2004 Marco Aur�lio Graciotto Silva
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

/**
* Checking parameters.
*/
if ( ! isset( $_REQUEST[ "wikipage_id" ] ) ) {
	show_error( _( "The parameter 'wikipage_id' is missing." ) );
}
$wikipage_id = $_REQUEST[ "wikipage_id" ];
if ( check_wikipage_id( $wikipage_id ) === false ) {
	show_error( _( "The parameter 'wikipage_id' is invalid." ) );
}
$swiki_id = extract_swiki_id( $wikipage_id );

// Prepare upload dir
$path = $UPLOADS_DIR . "/" . $swiki_id;
if ( !is_dir( $path ) ) {
	mkdir( $path );
}


/**
* Start to process the request.
*/
coteia_connect();

$query = "select status,titulo from swiki where id='$swiki_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	show_error( _( "Invalid parameter: 'swiki_id'" ) );
}
$tuple = mysql_fetch_array( $result );
$title = $tuple[ "titulo" ];
$status = $tuple[ "status" ];
mysql_free_result( $result );

// Check if the user is allowed to access the requested swiki and redirect to login
// if required.
if ( $status == "1" ) {
  session_name( "coteia" );
  session_start();
  if ( ! isset( $_SESSION[ "swiki_" . $swiki_id ] ) ) {
    $url = "login.php?wikipage_id=$wikipage_id&amp;repository=$wikipage_id";
    header( "Location: $url" );
    exit();
  }
}

echo get_header( _( "Upload file" ) );
?>
</head>

<body>

<?php


$result = 4;
if ( isset( $_FILES[ "filename" ] ) ) {
	$filetypes = array();
	$filetypes[] = "txt";
	$filetypes[] = "pdf";
	$filetypes[] = "ps";
	$filetypes[] = "xml";
	$filetypes[] = "zip";
	$filetypes[] = "gz";
	$filetypes[] = "bz2";
	$filetypes[] = "patch";
	$filetypes[] = "diff";
	$filetypes[] = "jpg";
	$filetypes[] = "gif";
	$filetypes[] = "png";

	if ( is_uploaded_file( $_FILES['filename']['tmp_name'] ) ) {
		$realname = $_FILES['filename']['name'];
		foreach ( $filetypes as $filetype ) {
			if ( preg_match( "/\." . $filetype . "$/i", $realname ) > 0 ) {
				$result = 0;
			}
		}
		if ( $result == 0 ) {
			if ( file_exists( $path . "/" . $realname ) ) {
				// TODO: cvs update.
				$result = 3;
			}	else {
				// TODO: cvs add
				$old_mask = umask( 0111 );
				copy( $_FILES['filename']['tmp_name'], $path . "/" . $realname );
				umask( $old_mask );
				$result = 1;
			}
		}
	} else {
		$result = 2;
	}
?>

<script>
function CheckUpload( result ) {
  if ( result == 1 ) {
    alert( "<?php echo _( "The file has been successfully uploaded." ); ?>" );
  } else if ( result == 2 ) {
    alert( "<?php echo _( "Error saving the file (the maximum file size is 10 MB." ); ?>" );
  } else if ( result == 3 ) {
    alert( "<?php echo _( "The file already exists." ); ?>" );
  } else if ( result == 4 ) {
    alert( "<?php echo _( "The file extension or mime-type is invalid. Valid extensions are: ") . implode( ", ", $filetypes ); ?>" );
  }
}
</script>
<script>CheckUpload( <?php echo $result; ?> )</script>

<?php
}
include( "toolbar.php.inc" );
?>

<table width="70%" bgcolor="#E1F0FF" bordercolor="#C0C0C0" align="center" border="1">
<tr>
	<td>
		<img src="<?php echo "$IMAGES_DIR/Manager.png";?>" />
		<strong>CoTeia</strong>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo "$IMAGES_DIR/Dir_open.png";?>" />
		<strong><?php echo "Swiki: $title";?></strong>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo $IMAGES_DIR;?>/Cvs.png" /><strong><?php echo _( "File list" ); ?></strong>
		<div align="center">
			<form name="checkout" method="get" action="checkout.php" >
				<input type="hidden" name="wikipage_id" value="<?php echo $wikipage_id ?>" />
				<br />
				<select name="filename" size="10" style="width: 60%">
				<?php
					$a = array();
					$fd = opendir( $path );
					while( $entry = readdir( $fd ) ) {
						if ( ! eregi( "\.$", $entry ) ) {
							array_push( $a, $entry );
						}
					}
					closedir( $fd );
					sort( $a );
					reset( $a );
					while ( list( $key, $val ) = each ( $a ) ) {
						echo "\n\t<option value=\"$val\">$val</option>";
					}
				?>
				</select>
				<br /><input type="submit" value="<?php echo _( "Open selected file" ); ?>" />
			</form>
		</div>
	</td>
</tr>
<tr>
	<td>
		<img src="<?php echo $IMAGES_DIR;?>/files2upload.png" /><b>Upload</b>
		<div align="center">
			<form name="upload" enctype="multipart/form-data" method="post" action="repository.php">
				<input type="hidden" name="wikipage_id" value="<?php echo $wikipage_id ?>" />
				<br /><input type="file" size="40" name="filename" />
				<br /><input type="submit" name="submit" value="<?php echo _( "Upload file" ); ?>" />
			</form>
		</div>
	</td>
</tr>
<tr>
	<td>
		<div align="center">
			<a href="show.php?wikipage_id=<?php echo $wikipage_id;?>"><img src="<?php echo $IMAGES_DIR;?>/back.png" /><?php echo _( "Back" ); ?></a>
		</div>
	</td>
</tr>

</table>

</body>

</html>
