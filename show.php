<?php
/**
* Show wikipages.
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* Copyright (C) 2004 Marco Aurélio Graciotto Silva
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.inc" );

$swiki_id = extract_swiki_id( $_REQUEST[ "wikipage_id" ] );
if ( $swiki_id == false ) {
	show_error( 0 );
}
$dbh = coteia_connect();
$query = "select minimum_role from swikis where id='$swiki_id'";
$result = mysql_query( $query );
$tuple = mysql_fetch_array( $result );
$status = $tuple[ "minimum_role" ];
// Se o weight do $_SESSION[ "role" ] do usuário foi maior ou igual o minimum_role, deixa entrar.

$sucesso = @include( "$PATH_ARQUIVOS/$ident.html" );
