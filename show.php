<?php
/**
* Show wikipages.
*
* Show the requested wikipage, compiling an updated version
* if outdated.
*
* @param wikipage_id [string] Identifier of a wikipage. If the wikipage_id
* is set to zero, then it's a new page that's being created. In this case,
* the parameter 'index' and 'swiki_id' must be set too.
* @param swiki_id [string] Identifier of a swiki (must be set only when
* creating a wikipage).
* @param index [string] Name of a wikipage (must be set only when creating
* a wikipage).
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
	show_error( _( "Missing parameter: 'wikipage_id'" ), 0 );
}
$wikipage_id = extract_wikipage_id( $_REQUEST[ "wikipage_id" ] );
if ( $wikipage_id === false ) {
	show_error( _( "The parameter 'wikipage_id' is invalid." ) );
}

// We are creating a new wikipage...
if ( isset( $_REQUEST[ "swiki_id" ] ) || isset( $_REQUEST[ "index" ] ) ) {
	if ( ! isset( $_REQUEST[ "swiki_id" ] ) ) {
		show_error( _( "The parameter 'swiki_id' is missing." ) );
	}
	if ( ! isset( $_REQUEST[ "index" ] ) ) {
		show_error( _( "The parameter 'index' is missing." ) );
	}

	$swiki_id = $_REQUEST[ "swiki_id" ];
	if ( check_swiki_id( $swiki_id ) === false ) {
		show_error( _( "The parameter 'swiki_id' is invalid." ) );
	}

	$index = $_REQUEST[ "index" ];
	if ( ! is_string( $index ) ) {
		show_error( _( "The parameter 'index' is invalid." ) );
	}
} else {
	$swiki_id = extract_swiki_id( $wikipage_id );
}

$format = $DEFAULT_OUTPUT_FORMAT;
if ( isset( $_REQUEST[ "format" ] ) ) {
	$format = basename( $_REQUEST[ "format" ] );
}

/**
* Start to process the request.
*/

// The wikipage "0" is an special case. The "0" is always the root of the application.
if ( $wikipage_id == "0" && !isset( $index ) ) {
//	header( "Location: $URL_COWEB/" );
	header( "Location: ./" );
	exit();
}

// Find the swiki the wikipage belongs to
coteia_connect();
$query = "select status from swiki where id='$swiki_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	show_error( _( "The swiki the wikipage supposedly belongs to couldn't be found." ) );
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
		$url = "login.php?wikipage_id=$wikipage_id";
		if ( isset( $index ) ) {
			$url .= "&amp;swiki_id=$swiki_id&amp;index=" . rawurlencode( $index );
		}
		header( "Location: $url" );
		exit();
  }
}

// Create wikipage if it doesn't exist yet.
$query = "select id_pag from gets where id_sw='$swiki_id' and id_pag='$wikipage_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	if ( isset( $index ) ) {
		header( "Location: edit.php?wikipage_id=0&amp;swiki_id=$swiki_id&amp;index=" . rawurlencode( $index ) );
		exit();
	} else {
		show_error( _( "Wikipage doesn't exist" ) );
	}
}
mysql_free_result( $result );

$result = update_wikipage( $wikipage_id, $format );

if ( $result !== true ) {
	show_error( _( "An error was found in this wikipage. Please, contact the system administrator." ) );
}

readfile( $OUTPUT_DIR . "/" . $format . "/" . $wikipage_id . "." . $format );
