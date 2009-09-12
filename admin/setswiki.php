<?
/**
* Set/Change swiki's configuration.
*
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* Changed by Marco Aurélio Graciotto Silva (2004).
*
* This code is licenced under the GNU General Public License (GPL).
*/


include_once( "header.php.inc" );

echo get_header( _( "Set swiki configuration" ) );
?>
</head>

<h1><?php echo _( "Set swiki configuration" ); ?></h1>

<form method="post" action="update_swiki.php" name="formadmin">
<select name="swiki_id">
	<option value="0" selected><?php echo _( "Choose a swiki" ); ?></option>
	<?php
		db_connect();
		$query = "select id,titulo from swiki order by titulo"; 
		$result = mysql_query( $query );
		while ( $tuple = mysql_fetch_array( $result ) ) {
			$title = $tuple[ "titulo" ];
			$id = $tuple[ "id" ];
			echo "\t<option value=\"$id\">$title</option>";
		}
	?>
</select>

<br /><br />
<input type="submit" name="submit" value="<?php echo _( "Continue" ); ?>">
</form>

</body>

</html>
