<?php
/**
* Edit wikipages.
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* This code is licenced under the GNU General Public License (GPL).
*/

include_once("function.inc");
include_once("cvs/function_cvs.inc");

$dbh = db_connect();
mysql_select_db( $dbname, $dbh );

// Check if the ident is valid (and extract the swiki id if it's valid).
$id_swiki = extract_swiki_id( $_REQUEST[ "ident" ] );
if ( $id_swiki == false ) {
	$st = 0;
	include( "err.inc" );
}

// Check if there's a wikipage with the given "ident".
$wikipage_query = "select * from paginas where ident='" . $_REQUEST[ "ident" ] . "'";
$wikipage_result = mysql_query( $wikipage_query, $dbh );
if ( mysql_num_rows( $wikipage_result ) == 0 ) {
	$st = 0;
	include( "err.inc" );
}
$wikipage_tuple = mysql_fetch_array( $wikipage_result );

if ( isset( $_REQUEST[ "save" ] ) ) {
	// Check password (if there is one to check against).
	$password = $wikipage_tuple[ "pass" ];
	if ( $password != NULL ) {
		if ( strcasecmp( $password, md5( $_REQUEST[ "password" ] ) ) != 0 ) {
			$st = 4;
			include( "err.inc" );
		}
	}

	// Prepare data for database insertion.
	$wikipage_db = prepare_for_db( $wikipage_tuple[ "indexador" ], $_REQUEST[ "content" ], $_REQUEST[ "title" ], $_REQUEST[ "author" ], $_REQUEST[ "keyword" ] );

	if ( $_REQUEST[ "lock" ] == false ) {
		$wikipage_db[ "password" ] = "NULL";
	} else {
		$wikipage_db[ "password" ] = "" . $_REQUEST[ "password" ];
		if ( get_magic_quotes_gpc() == 1 ) {
			$wikipage_db[ "password" ] = stripslashes( $wikipage_db[ "password" ] );
		}
		$wikipage_db[ "password" ] = md5( $wikipage_db[ "password" ] );
	}

	$d = getdate();
	$data=$d["year"]."-".$d["mon"]."-".$d["mday"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];

	$update_wikipage_query = "update paginas set " .
		"conteudo='" . $wikipage_db[ "content" ] . "'," .
		"titulo='"   . $wikipage_db[ "title"]    . "'," .
		"kwd1='"     . $wikipage_db[ "keyword0" ]. "'," .
		"kwd2='"     . $wikipage_db[ "keyword1" ]. "'," .
		"kwd3='"     . $wikipage_db[ "keyword2" ]. "'," .
		"autor='"    . $wikipage_db[ "author" ]  . "'," .
		"data_ultversao='$data'," .
		"pass="      . $wikipage_db[ "password" ]. " "  .
		"where ident='". $_REQUEST[ "ident" ] . "'";
	$update_wikipage_result = mysql_query( $update_wikipage_query, $dbh );
	if ( $update_wikipage_result == false && mysql_affected_rows( $dbh ) != 1 ) {
		$st = 1;
		include( "err.inc" );
	}

	$parent_id = $ident;
	include( "update_wikipage.inc" );

	header("Location:mostra.php?ident=$ident");
} else {
?>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Formulário de Edição</title>
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
include( "toolbar.inc" );
?>

<form method="post" name="edit" action="edit.php" onSubmit="return validar(this);">
 
<div class="metadata">
<table>
<tr>
	<td>
	Título
	<br /><input type="text" name="title" value="<?php echo $wikipage_tuple[ "titulo" ];?>" size="45" />
	</td>
</tr>
<tr>
	<td>
	Autor
	<br /><input type="text" name="author" value="<?php echo $wikipage_tuple[ "autor" ]; ?>" size="45" />
	</td>
</tr>
<tr>
	<td>
	Palavras-chave:
	<br />
	<input type="text" name="keyword[0]" size="15" value="<?php echo $wikipage_tuple[ "kwd1" ]; ?>" />
	<input type="text" name="keyword[1]" size="15" value="<?php echo $wikipage_tuple[ "kwd2 "]; ?>" />
	<input type="text" name="keyword[2]" size="15" value="<?php echo $wikipage_tuple[ "kwd3 "]; ?>" />
	</td>
</tr>
</table>
</div>

<div class="lock">
  Lock
	<br /><input type="checkbox" name="lock" value="locked" <?php if ( $wikipage_tuple[ "password" ] != NULL ) echo checked; ?> />

	<br />Password
	<br /><input type="password" size="10" name="password" onChange="window.document.edit.lock.checked=true;return false;" />

<?php
	if ( $wikipage_tuple[ "password" ] == NULL ) {
?>
	<br />Re-enter password
	<br /><input type="password" size="10" name="repassword" onChange="window.document.edit.lock.checked=true;return false;" />
<?php
	}
?>
</div>

<br />
<div class="content" >
	<input type="reset" value="Limpar" onClick="return confirm('Are you sure? This will restore the original text\n(in another words, you will lose every change made to the text)')"; />
	<input type="submit" name="save" value="Salvar" />
	<br />
	<textarea name="content" wrap=virtual rows="20" cols="100" style="width: 100%"><?php echo $wikipage_tuple[ "conteudo" ]; ?></textarea>
</div>
<input type="hidden" name="ident" value="<?php echo $wikipage_tuple[ "ident" ]; ?>" />
</form>

<?php
}
?>

</body>

</html>
