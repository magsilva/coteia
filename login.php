<?php
/*
* Authenticate users and set session variables.
*
* Copyright (c) 2001, 2002, 2003 Carlos E. Arruda Jr.
* Modified by Marco Aurélio Graciotto Silva.
*/

if ( isset( $_REQUEST[ "login" ] ) {

	include_once( "function.inc" );

	if ( check_wikipage_id( $_REQUEST[ "id" ] ) == false ) {
		show_error( 0 );
	}

	$dbh = db_connect();
	$result = login_swiki( $_REQUEST[ "username" ], $_REQUEST[ "password" ], $_REQUEST[ "id" ], $dbh );

	if ( $result ) {
		if ( $token == "1" ) {
			header( "Location:mostra.php?ident=$id" ); 
			exit;
		}

		if ( $token == "0" ) {
			header( "Location:create.php?ident=$id&index=" . rawurlencode( $index ) );
			exit;
		}
	} else {
		echo '<br /><div align="center">Área Restrita.<br /><br /><a href="javascript:history.go(-1)">Voltar</a></div>';
	}
} else {
?>

<html>

<head>
	<title>Login</title>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<h1>Login Obrigatório</h1>

<form name="login" method="post" action="autentica.php">
	<br /><b>Usuário:</b>
	<br /><input type="text" name="username" size="16" />

	<br /><b>Senha:</b>
	<br /><input type="password" name="password" size="16" />

	<br /><input type=submit value="Login" name="login" />

	<input type="hidden" name="id" value="<?php echo $id;?>" />
	<input type="hidden" name="token" value="<?php echo $token;?>" />
	<input type="hidden" name="index" value="<?php echo $index;?>" />
</form>

</body>

</html>

<?php
}
?>
