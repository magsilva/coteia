<?php

include_once("function.inc");
if ( ! check_wikipage_id( $_REQUEST[ "swiki" ] ) ) {
	$st = 0;
	include( "erro.php" );
}

$filename = basename( $_REQUEST[ "arq" ] );
?>

<html>

<head>
        <title><?php echo basename( $checked_file );?></title>
        <link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<br />

<h2>Arquivo para Download</h2>

<hr />
<br />

<a href="<?php echo "$URL_COWEB/get.php?swiki=" . $_REQUEST[ "swiki" ] . "&arq=" . rawurlencode( $filename );?>"><?php echo $filename;?></a>

</body>
 
</html>

include_once( "function.inc" );


header( "Pragma: public" );
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header( "Pragma: no-cache" );
header( "Content-Transfer-Encoding: none" );
header( "Content-Type: application/force-download" );
header( "Content-Disposition: attachment; filename=" . basename( $_REQUEST[ "arq" ] ) );
header( "Content-Description: File Transfer");
header( "Content-length: " . filesize( $checked_file );
readfile( $checked_file );

?>
