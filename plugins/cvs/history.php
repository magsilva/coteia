<?php
/*
* Interface for older wikipage's retrieving.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva.
*/
?>

<html>

<head>
	<title>Histórico</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<?php
include_once( "../../function.inc" );
include_once( "cvs-api.inc" );

// Encontra id_swiki
$ident = $_REQUEST[ "ident" ];
if ( check_wikipage_id( $ident ) == false ) {
	show_error( 0 );
}
$revision = cvs_get_revisions( $CVS_MODULE . "/" . $ident . ".html" );
if ( $revision == false ) {
	show_error( 0 );
}
?>

<body>

<?php
include( "../../toolbar.inc" );
?>

<form method="post" action="retrieve_revision.php">

	<input type="checkbox" name="compare" value="true" checked />Comparar com a versão atual
	<br />
	<select name="revision">
	<?php
		foreach ( array_keys( $revision ) as $i ) {
			echo "\t<option value=\"$i\">";
			echo $revision[ $i ];
			echo "</option>\n";
		}
	?>
	</select>
	<input type="hidden" name="ident" value="<?php echo $ident;?>" />
	<input type="submit" name="submit" value="Mostrar" />
</form>

</body>

</html>
