<?php
/*
* Authenticate user into a swiki, redirecting to the wikipage that was
* requested.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

/**
* Parameters checking.
*/
$wikipage_id = $_REQUEST[ "wikipage_id" ];
if ( check_wikipage_id( $wikipage_id ) == false ) {
	show_error( _( "The parameter 'wikipage_id' is invalid." ) );
}

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

if ( isset( $_REQUEST[ "login" ] ) ) {
	if ( !isset( $_REQUEST[ "username" ] ) ) {
		show_error( _( "Parameter 'username' is missing." ) );
	}
	if ( !isset( $_REQUEST[ "password" ] ) ) {
		show_error( _( "Parameter 'password' is missing." ) );
	}
}



/**
* Processing the request...
*/
if ( isset( $_REQUEST[ "login" ] ) ) {
	$result = coteia_login_swiki( $_REQUEST[ "username" ], $_REQUEST[ "password" ], $swiki_id );

	if ( $result ) {
		if ( isset( $_REQUEST[ "repository" ] ) ) {
			$url = "repository.php?wikipage_id=$wikipage_id";
			if ( isset ( $_REQUEST[ "filename" ] ) ) {
				$url .= "&amp;filename=" . rawurlencode( $_REQUEST[ "filename" ] );
			}
		} else {
			$url =  "show.php?wikipage_id=$wikipage_id";
			if ( isset( $index ) ) {
				$url .= "&amp;swiki_id=$swiki_id&amp;index=" . rawurlencode( $index );
			}
		}
		header( "Location: $url" );
	} else {
		show_error( _( "The username or password was incorrect. Please, try again." ) );
	}
}


/**
* Sending the authentication form.
*/
echo get_header( _( "Login" ) );
include( "toolbar.php.inc" );
?>
<body onload="document.forms['login'].username.focus()">

<h1><?php echo _( "Login" ); ?></h1>

<p><?php echo _( "The requested wikipage requires you to be authenticated with a password. Please, inform the username and password:" ); ?></p>

<form name="login" method="post" action="login.php">
	<br /><strong><?php echo _( "Username" ); ?></strong>
	<br /><input type="text" name="username" size="16" />

	<br /><strong><?php echo _( "Password" ); ?></strong>
	<br /><input type="password" name="password" size="16" />

	<br /><input type=submit value="Login" name="login" />

<?php
if ( isset( $index ) ) {
?>
	<input type="hidden" name="swiki_id" value="<?php echo $swiki_id; ?>" />
	<input type="hidden" name="index" value="<?php echo $index; ?>" />
<?php
}
?>
	<input type="hidden" name="wikipage_id" value="<?php echo $wikipage_id; ?>" />

</form>

</body>

</html>
