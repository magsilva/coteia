<?php
/*
* Authenticate users and set session variables.
*
* Copyright (c) 2001, 2002, 2003 Carlos E. Arruda Jr.
* Modified by Marco Aurélio Graciotto Silva.
*/
 
include_once( "function.inc" );

if ( check_wikipage_id( $id ) == false ) {
	show_error( 0 );
}

$dbh = db_connect();
$retorno = login_swiki( $usuario, $passwd, $id, $dbh );

if ( $retorno ) {
	if ( $token == "1" ) {
		header( "Location:mostra.php?ident=$id" ); 
		exit;
	}

	if ( $token == "0" ) {
		header( "Location:create.php?ident=$id&index=$index" );
		exit;
	}
} else {
	echo '<br /><div align="center">Área Restrita.<br /><br /><a href="javascript:history.go(-1)">Voltar</a></div>';
}
?>
