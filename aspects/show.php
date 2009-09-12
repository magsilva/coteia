<?aophp filename="swiki_authentication.aophp" debug="off"
/*
Show wikipages: show the requested wikipage, compiling an updated version if
outdated.

@param wikipage_id [string] Identifier of a wikipage. If the wikipage_id
is set to zero, then it's a new page that's being created. In this case,
the parameter 'index' and 'swiki_id' must be set too.
@param swiki_id [string] Identifier of a swiki (must be set only when
creating a wikipage).
@param index [string] Name of a wikipage (must be set only when creating
a wikipage).

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

Copyright (C) 2004 Marco Aurélio Graciotto Silva <magsilva@gmail.com>
*/

require_once( "config.php" );
include_once( "coteia.inc.php" );
include_once( "presentation.inc.php");
include_once( "error.inc.php" );
include_once( "wikipage.inc.php" );

$action = "view";

/**
* Checking parameters.
*/

$wikipage_id = extract_wikipage_id( $_REQUEST[ "wikipage_id" ] );

// We are creating a new wikipage...
if ( isset( $_REQUEST[ "swiki_id" ] ) || isset( $_REQUEST[ "index" ] ) ) {
	$swiki_id = $_REQUEST[ "swiki_id" ];
	$index = $_REQUEST[ "index" ];
	if ( get_magic_quotes_gpc() == 1 ) {
                $index = stripslashes( $index );
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
	session_write_close();
	header( "Location: ./" );
	exit();
}

// Find the swiki the wikipage belongs to
db_connect();

// Create wikipage if it doesn't exist yet.
$query = "select id_pag from gets where id_sw='$swiki_id' and id_pag='$wikipage_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	if ( $swiki_id == $wikipage_id ) {
		$query = "select titulo from swiki where id='$swiki_id'";
		$result = mysql_query( $query );
		$tuple = mysql_fetch_array( $result );
		$index = $tuple[ "titulo" ];
		session_write_close();
		header( "Location: edit.php?wikipage_id=$swiki_id&swiki_id=$swiki_id&index=" . rawurlencode( $index ) );
		exit();
	}
	if ( isset( $index ) ) {
		session_write_close();
		header( "Location: edit.php?wikipage_id=0&swiki_id=$swiki_id&index=" . rawurlencode( $index ) );
		exit();
	} else {
		show_error( _( "Wikipage could not be created." ) );
	}
}
mysql_free_result( $result );

$result = update_wikipage( $wikipage_id, $format );

if ( $result !== true ) {
	show_error( _( "An error was found in this wikipage. Please, contact the system administrator." ) );
}


readfile( $OUTPUT_DIR . "/" . $format . "/" . $wikipage_id . "." . $format );

?>
