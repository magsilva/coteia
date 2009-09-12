<?php
/*
 View the recent changes made into a wikipage.

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

include_once( "presentation.inc.php" );
include_once( "db.inc.php" );
include_once( "swiki.inc.php" );


echo get_header( _( "Recent changes" ) );
?>

<body>

<?php

include( "toolbar.inc.php" );


db_connect();

if ( isset( $_REQUEST[ "swiki_id" ] ) ) {
	$swiki_id = $_REQUEST[ "swiki_id" ];
	if ( swiki_check_id( $swiki_id ) === false ) {
		show_error( _( "The requested swiki is invalid. Please contact the system's administrator." ) );
	}

	$query = "select id,titulo from swiki ";
	if ( $swiki_id !== "0" ) {
		$query .= "where id=$swiki_id ";
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

<form method="get" action="changes.php">
	<select name="swiki_id">
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
	<input type="submit" value="<?php echo _( "Search" ); ?>" />
</form>
<?php
}
?>

</body>

</html>
