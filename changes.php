<?php
/**
* View the recent changes made into a wikipage.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once("function.php.inc");

echo get_header( _( "Recent changes" ) );
?>

<body>

<?php

include( "toolbar.php.inc" );

$swiki_id = $_REQUEST[ "swiki_id" ];
if ( check_swiki_id( $swiki_id ) === false ) {
	show_error( _( "The requested swiki is invalid. Please contact the system's administrator." ) );
}


coteia_connect();

if ( isset( $_REQUEST[ "submit" ] ) ) {
	$selected_swiki_id = $_REQUEST[ "selected_swiki_id" ];
	if ( check_swiki_id( $selected_swiki_id ) === false ) {
		show_error( _( "The selected swiki is invalid. Please contact the system's administrator." ) );
	}
	
	$query = "select id,titulo from swiki ";
	if ( $selected_swiki_id !== "0" ) {
		$query .= "where id=$selected_swiki_id ";
	}
	$query .= "order by titulo";
	$result = mysql_query( $query );

	while ( $tuple = mysql_fetch_array( $result ) ) {
		$current_swiki_title = $tuple[ "titulo" ];
		$current_swiki_id = $tuple[ "id" ];
		echo "\n<h2>",htmlspecialchars( $current_swiki_title ),"</h2>";
		$query2 = "select paginas.data_ultversao,paginas.titulo,paginas.ident FROM paginas,gets WHERE gets.id_sw=$current_swiki_id AND gets.id_pag=paginas.ident ORDER BY paginas.data_ultversao DESC";
		$result2 = mysql_query( $query2 );
		if ( mysql_num_rows( $result2 ) != 0 ) {
			while ( $tuple2 = mysql_fetch_array( $result2 ) ) {
				$ctime = strtotime( $tuple2[ "data_ultversao" ] );
				$ctime =  date( "d/m/Y" , $ctime ) . " - " . date( "H:i", $ctime );
				$wikipage_title = $tuple2[ "titulo" ];
				$wikipage_id = $tuple2[ "ident" ];
				echo "\n<br />[$ctime] - <a href=\"show.php?wikipage_id=$wikipage_id\">",htmlspecialchars( $wikipage_title ), "</a>";
			}
		} else {
			echo _( "The swiki is empty" );
		}
		echo "\n";
	}
} else {
?>

<form method="post" action="changes.php">
	<select name="selected_swiki_id">
		<option value="0"><?php echo _( "All the swikis" ); ?></option>
		<?php
			$query = "SELECT id,titulo FROM swiki order by titulo";
			$result = mysql_query( $query );
			while ( $tuple = mysql_fetch_array( $result ) ) {
				$current_swiki_title = $tuple[ "titulo" ];
				$current_swiki_id = $tuple[ "id" ];
				echo "\n\t\t<option value=\"$current_swiki_id\"";
				if ( $current_swiki_id == $swiki_id ) {
					echo " selected";
				}
				echo ">$current_swiki_title</option>";
			}
			echo "\n";
		?>
	</select>
	<input type="submit" name="submit" value="<?php echo _( "Search" ); ?>" />
	<input type="hidden" name="swiki_id" value="<?php echo $swiki_id; ?>" />
</form>
<?php
}
?>

</body>

</html>
