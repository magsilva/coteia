<?php 
include_once("function.inc");

if ( ! check_wikipage_id( $_REQUEST[ "swiki_id" ] ) ) {
	show_error( 0 );
}

header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Content-Type: application/force-download");
header( "Content-Disposition: attachment; filename=" . $_REQUEST[ "filename" ] );
header( "Content-Description: File Transfer");
readfile( $PATH_UPLOAD  . "/" . $_REQUEST[ "swiki_id" ] . "/" . $_REQUEST[ "filename" ] );
?>
