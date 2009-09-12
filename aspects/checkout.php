<?aophp filename="swiki_authentication.aophp" debug="off"
/*
Download a file from the swiki's repository.

@param wikipage_id [string] Identifier of a swiki
@param filename [string] Name of the file to be download.

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

include_once( "config.php" );
include_once( "coteia.inc.php" );
include_once( "error.inc.php" );
include_once( "wikipage.inc.php" );

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
db_connect();

$filename = utf8_decode( $_REQUEST[ "filename" ] );
$filename = basename( $filename );
$checked_file = $UPLOADS_DIR  . "/" . $swiki_id . "/" . $filename;
if ( ! is_file( $checked_file ) ) {
	show_error( _( "The requested file couldn't be found." ) );
}

session_write_close();
header( "Pragma: public" );
header( "Expires: 0" );
header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header( "Content-Type: application/force-download" );
header( "Content-Disposition: attachment;filename=\"" . $filename . "\"" );
header( "Content-Description: File Transfer" );
readfile( $checked_file );
?>
