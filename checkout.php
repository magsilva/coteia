<?php

include_once( "function.inc" );

if ( ! check_wikipage_id( $_REQUEST[ "swiki" ] ) ) {
	$st = 0;
	include( "erro.php" );
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header( "Content-Disposition: attachment; filename=" . $_REQUEST[ "arq" ] );
header( "Content-Description: File Transfer");
readfile( $PATH_UPLOAD  . "/" . $_REQUEST[ "swiki" ] . "/" . $_REQUEST[ "arq" ] );
?>
