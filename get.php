<?php

include_once( "function.inc" );

if ( ! check_wikipage_id( $_REQUEST[ "swiki" ] ) ) {
	$st = 0;
	include( "erro.php" );
}

$checked_file = $PATH_UPLOAD  . "/" . $_REQUEST[ "swiki" ] . "/" . basename( $_REQUEST[ "arq" ] );

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
