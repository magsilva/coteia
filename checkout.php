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
