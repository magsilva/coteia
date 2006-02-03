<?php
/**
* Edit wikipages.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

/**
* Checking the parameters.
*/

/**
* If creating a new wikipage, the request must have two parameters: "swiki_id"
* and "index". No more, no less.
* If editing a wikipage, the request must have the parameter "wikipage_id". No
* more, no less.
*
* First, we see if an action was specified. If not, we try to guess.
*/
if ( isset( $_REQUEST[ "action" ] ) ) {
	$action = $_REQUEST[ "action" ];
}

if ( !isset( $action) && isset( $_REQUEST[ "swiki_id" ] ) && isset( $_REQUEST[ "index" ] ) ) {
	$action = "create";
}
if ( !isset( $action) && isset( $_REQUEST[ "wikipage_id" ] ) ) {
	$action = "edit";
}

if ( !isset( $action ) ) {
	show_error( _( "An action was not specified and it was not possible to guess the desirable action. Contact the system administrator." ) );
}

if ( isset( $action ) && !( $action == "create" || $action == "edit"  ) ) {
	show_error( _( "Unsupported action: " ) . $action );
}


/**
* Known the action, the required parameters for each action is verified.
*/
if ( $action == "create" ) {
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
	if ( get_magic_quotes_gpc() == 1 ) {
		$index = stripslashes( $index );
	}
}

if ( $action == "edit" ) {
	if ( ! isset( $_REQUEST[ "wikipage_id" ] ) ) {
		show_error( _( "Missing parameter: 'wikipage_id'" ), 0 );
	}

	$wikipage_id = $_REQUEST[ "wikipage_id" ];
	if ( check_wikipage_id( $wikipage_id ) === false ) {
		show_error( _( "The parameter 'wikipage_id' is invalid." ) );
	}
	$swiki_id = extract_swiki_id( $wikipage_id );
}




/****************************************************************************
* Show time.
*/

// We will need the database from now on.
coteia_connect();


// Find the swiki the swiki the wikipage belongs to
$query = "select status from swiki where id='$swiki_id'";
$result = mysql_query( $query );
if ( mysql_num_rows( $result ) == 0 ) {
	show_error( _( "The swiki the wikipage will belong to couldn't be found." ) );
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

/**
* After futher checking the paremeters for each action, the skeleton of a
* wikipage must be createn (wikipage_raw). If creating a new wikipage, it
* really is a skeleton. If editing a wikipage, the current data is loaded
* fro the database.
*/
if ( $action == "create" ) {
	$query = "select COUNT(*) as counter from paginas,gets where gets.id_sw='$swiki_id' and gets.id_pag=paginas.ident and binary indexador='" . mysql_escape_string( $index ) . "'";
	$result = mysql_query( $query );
	$tuple = mysql_fetch_row( $result );
	if ( $tuple[ 0 ] != 0 ) {
		show_error( _( "There is a wikipage with the requested index already." ) );
	}
	mysql_free_result( $result );

	$wikipage_raw = array();
	$wikipage_raw[ "ident" ] = 0;
	$wikipage_raw[ "indexador" ] = $_REQUEST[ "index" ];
	$wikipage_raw[ "autor" ] = "";
	$wikipage_raw[ "titulo" ] = $_REQUEST[ "index" ];
	$wikipage_raw[ "kwd1" ] = "";
	$wikipage_raw[ "kwd2" ] = "";
	$wikipage_raw[ "kwd3" ] = "";
	$wikipage_raw[ "pass" ] = NULL;
	$wikipage_raw[ "conteudo" ] = "";
}

// If we are editing a wikipage, load the requested wikipage's data from the database.
if ( $action == "edit" ) {
	// Check if there's a wikipage with the given "ident".
	$query = "select * from paginas where ident='" . $wikipage_id . "'";
	$result = mysql_query( $query );
	if ( mysql_num_rows( $result ) == 0 ) {
		show_error( _( "The requested wikipage couldn't be found. Please contact the swiki's administrator." ) );
	}
	$wikipage_raw = mysql_fetch_array( $result );
}



/**
* This is the second phase of every action, the data commitment.
*/
if ( isset( $_REQUEST[ "save" ] ) ) {

	// Check password (if there is one to check against).
	$password = $wikipage_raw[ "pass" ];
	if ( $password != NULL ) {
		if ( strcasecmp( $password, md5( $_REQUEST[ "password" ] ) ) != 0 ) {
			show_error( _( "Incorrect password. Please, try again." ) );
		}
	}

	/**
	* When creating a new wikipage, a new (and unique) identificator (wikipage_id) must be
	* generated. If it's the swiki's root (in other words, the swiki's first wikipage),
	* the "wikipage_id" will be the same as the "swiki_id". Otherwise, it will be the
	* swiki's wikipage's counter prefixed by "$swiki_id." (this will start with "1").
	*/
	if ( $action == "create" ) {
		$query = "select count(*) as counter from paginas where ident like '$swiki_id.%' or ident='$swiki_id'";
		$result = mysql_query( $query );
		$tuple = mysql_fetch_array( $result );
		if ( $tuple[ "counter" ] == 0 ) {
			$wikipage_id = $swiki_id;
		} else {
			$wikipage_id = $swiki_id . "." . $tuple[ "counter" ];
		}
	}

	// Handle the case the wikipage's content is sent via an upload file.
	if ( isset( $_FILES[ "filename" ] ) ) {
		if ( is_uploaded_file( $_FILES['filename']['tmp_name'] ) ) {
			$_REQUEST[ "content" ] =  file_get_contents( $_FILES["filename"]["tmp_name"] );
		}
	}

	// Check were to insert the new data (above or below).
	if ( isset( $_REQUEST[ "position" ] ) ) {
	  if ( $_REQUEST[ "position" ] == "bottom" ) {
  	  $_REQUEST[ "content" ] = $wikipage_raw[ "conteudo" ] . $_REQUEST[ "content" ];
	  } else {
  	  $_REQUEST[ "content" ] = $_REQUEST[ "content" ] . $wikipage_raw[ "conteudo" ];
	  }
	}

	$wikipage_raw[ "titulo" ] = $_REQUEST[ "title" ];
	$wikipage_raw[ "autor" ] = $_REQUEST[ "author" ];
	$wikipage_raw[ "kwd1" ] = $_REQUEST[ "keyword1" ];
	$wikipage_raw[ "kwd2" ] = $_REQUEST[ "keyword2" ];
	$wikipage_raw[ "kwd3" ] = $_REQUEST[ "keyword3" ];
	$wikipage_raw[ "conteudo" ] = $_REQUEST[ "content" ];

	/**
	* Check the wikipage for syntax errors before saving the data.
	*/
	$validation_result = validate_wikipage( $wikipage_id, $wikipage_raw );
	if ( $validation_result !== true ) {
		show_error( $validation_result );
	}

	// Prepare data for database insertion.
	$wikipage_db = prepare_for_db( $wikipage_raw );

	// Set wikipage's edition protection.
	if ( !isset( $_REQUEST[ "lock" ] ) ) {
		$wikipage_db[ "password" ] = "NULL";
	} else {
		$wikipage_db[ "password" ] = "" . $_REQUEST[ "password" ];
		if ( get_magic_quotes_gpc() == 1 ) {
			$wikipage_db[ "password" ] = stripslashes( $wikipage_db[ "password" ] );
		}
		$wikipage_db[ "password" ] = "'" . md5( $wikipage_db[ "password" ] ) . "'";
	}

	$d = getdate();
	$date=$d["year"]."-".$d["mon"]."-".$d["mday"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];

	if ( $action == "create" ) {
		$ip = getenv( "REMOTE_ADDR" );
		$query = "insert into paginas (ident,indexador,titulo,conteudo,ip," .
			"data_criacao,data_ultversao,pass,kwd1,kwd2,kwd3,autor) values (" .
			"'$wikipage_id'" .
			",'" . mysql_escape_string( $index ) . "'" .
			",'" .$wikipage_db[ "title" ] . "'" .
			",'" .$wikipage_db[ "content" ] . "'" .
			",'" .$ip . "'" .
			",'" .$date . "'" .
			",'" .$date . "'" .
			"," . $wikipage_db[ "password" ] . "" .
			",'" .$wikipage_db[ "keyword1" ] . "'" .
			",'" .$wikipage_db[ "keyword2" ] . "'" .
			",'" .$wikipage_db[ "keyword3" ] . "'" .
			",'" .$wikipage_db[ "author" ] . "')";

    $result = mysql_query( $query );
		if ( $result == false && mysql_affected_rows() != 1 ) {
	    show_error( _( "It wasn't possible to create the wikipage. Please, try again. If the error persist, contact the system administrator." ) );
  	}

    $query = "insert into gets (id_pag,id_sw,data) values (" .
			"'$wikipage_id'" .
			",'$swiki_id'" .
			",'$date')";
    $result = mysql_query( $query );
		if ( $result == false && mysql_affected_rows() != 1 ) {
  		show_error( _( "It wasn't possible to associate the wikipage to the swiki. Please, try again. If the error persist, contact the system administrator." ) );
		}
	}

	if ( $action == "edit" ) {
		$query = "update paginas set " .
			"conteudo='" . $wikipage_db[ "content" ]     . "'," .
			"titulo='"   . $wikipage_db[ "title" ]       . "'," .
			"kwd1='"     . $wikipage_db[ "keyword1" ] . "'," .
			"kwd2='"     . $wikipage_db[ "keyword2" ] . "'," .
			"kwd3='"     . $wikipage_db[ "keyword3" ] . "'," .
			"autor='"    . $wikipage_db[ "author" ]      . "'," .
			"data_ultversao='" . $date                   . "'," .
			"pass="      . $wikipage_db[ "password" ]    . "  " .
			"where ident='"    . $wikipage_id            . "'";

		$result = mysql_query( $query );
		$result = true;

		if ( $result == false && mysql_affected_rows() != 1 ) {
			show_error( _( "It wasn't possible to update the wikipage. Please, try again. If the error persist, contact the system administrator." ) );
		}
	}

	header("Location: show.php?wikipage_id=$wikipage_id");
} else {

echo get_header( _( "Edit wikipage" ) );
?>
</head>

<body>

<?php
include( "toolbar.php.inc" );
?>


<form method="post" name="edit" action="edit.php" onSubmit="return validar(this);" enctype="multipart/form-data">
 
<div class="metadata">
<table>
<tr>
	<td>
	<?php echo _( "Title" ); ?>
	<br /><input type="text" name="title" value="<?php echo $wikipage_raw[ "titulo" ];?>" size="45" />
	</td>
</tr>
<tr>
	<td>
	<?php echo _( "Author" ); ?>
	<br /><input type="text" name="author" value="<?php echo $wikipage_raw[ "autor" ]; ?>" size="45" />
	</td>
</tr>
<tr>
	<td>
	<?php echo _( "Keywords" ); ?>
	<br />
	<input type="text" name="keyword1" size="15" value="<?php echo $wikipage_raw[ "kwd1" ]; ?>" />
	<input type="text" name="keyword2" size="15" value="<?php echo $wikipage_raw[ "kwd2" ]; ?>" />
	<input type="text" name="keyword3" size="15" value="<?php echo $wikipage_raw[ "kwd3" ]; ?>" />
	</td>
</tr>
</table>
</div>

<div class="lock">
	<?php echo _( "Lock" ); ?>
	<br /><input type="checkbox" name="lock" <?php if ( $wikipage_raw[ "pass" ] != NULL ) echo " checked"; ?> />

	<br /><?php echo _( "Password" ); ?>
	<br /><input type="password" size="10" name="password" onChange="window.document.edit.lock.checked=true;return true;" />

<?php
	if ( $wikipage_raw[ "pass" ] == NULL ) {
?>
	<br /><?php echo _( "Re-enter password" ); ?>
	<br /><input type="password" size="10" name="repassword" onChange="window.document.edit.lock.checked=true;return true;" />
<?php
	}
?>
</div>

<?php
	if ( isset( $_REQUEST[ "add" ] ) ) {
?>
<div class="optional">
  <strong><?php echo _( "Where to add the text" ); ?></strong>
  <br /><input type="radio" name="position" value="top" checked />Acima
  <br /><input type="radio" name="position" value="bottom" />Abaixo
</div>

<br />

<div class="content" >
	<input type="reset" value="<?php echo _( "Reset" ); ?>" />
	<input type="submit" name="save" value="<?php echo _( "Save" ); ?>" />
	<br />
	<textarea name="content" wrap=virtual rows="7" cols="100" style="width: 100%"></textarea>

	<br />
	<iframe src="show.php?wikipage_id=<?php echo $wikipage_id; ?>" width="100%" height="30%" scrolling="auto" frameborder="1">
	<?php echo _( "Your browser does not support frames or is currently configured not to display frames. You can preview the current document by <a href=\"show.php?wikipage_id=$wikipage_id\">following this link: Preview wikipage</a>." ); ?>
  </iframe>
</div>

<?php
	} else {
?>

<br />
<div class="content" >
	<input type="reset" value="<?php echo _( "Reset" ); ?>" onClick="return confirm('<?php echo _( "Are you sure? This will restore the original text\n(in another words, you will lose every change made to the text)." ); ?>');" />
	<input type="submit" name="save" value="<?php echo _( "Save" ); ?>" />
	<input type="file" size="40" name="filename" />
	<br />
	<textarea name="content" wrap=virtual rows="15" cols="100" style="width: 100%"><?php echo $wikipage_raw[ "conteudo" ]; ?></textarea>
</div>

<?php
	}
?>

	<input type="hidden" name="wikipage_id" value="<?php echo $wikipage_raw[ "ident" ]; ?>" />
<?php
	if ( isset( $index ) ) {
?>
	<input type="hidden" name="index" value="<?php echo $wikipage_raw[ "indexador" ]; ?>" />
	<input type="hidden" name="swiki_id" value="<?php echo $swiki_id; ?>" />
<?php
	}
?>

</form>

<?php
}
?>

</body>

</html>
