<?php
/**
* Download a file from the swiki's repository.
*
* @param wikipage_id [string] Identifier of a swiki
* @param filename [string] Name of the file to be download.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva
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



/**
* Start to process the request.
*/
coteia_connect();

$query = "select status from swiki where id='$swiki_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	show_error( _( "Invalid parameter: 'swiki_id'" ) );
}
$tuple = mysql_fetch_array( $result );
$status = $tuple[ "status" ];
mysql_free_result( $result );

// Check if the user is allowed to access the requested swiki and redirect to login
// if required.
if ( $status == "1" ) {
  session_name( "coteia" );
  session_start();
  if ( ! isset( $_SESSION[ "swiki_" . $swiki_id ] ) ) {
    $url = "login.php?wikipage_id=$wikipage_id&amp;repository=$wikipage_id&amp;filename=" . rawurlencode( $_REQUEST[ "filename" ] );
    header( "Location: $url" );
    exit();
  }
}

$filename = basename( $_REQUEST[ "filename" ] );
$checked_file = $UPLOADS_DIR  . "/" . $swiki_id . "/" . $filename;
if ( !is_file( $checked_file ) ) {
	show_error( _( "The requested file couldn't be found." ) );
}

header( "Pragma: public" );
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header( "Content-Type: application/force-download" );
header( "Content-Disposition: filename=\"" . $filename . "\"" );
header( "Content-Description: File Transfer" );
readfile( $checked_file );

?>
