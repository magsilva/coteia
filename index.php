<?php
/**
* CoTeia's main page.
*
* Show the visible swikis and some usage statistics.
*
* Copyright (C) 2001, 2002, 2003 Carlos Roberto E. de Arruda Jr
* Changed by Marco Aurélio Graciotto Silva (2004).
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

echo get_header( _( "CoTeia's Main Page" ) );
?>
<body>

<h1><img src="<?php echo $IMAGES_DIR; ?>/logo.png" alt="<?php echo _( "CoTeia - Web Based Collaborative Edition Tool" ); ?>" /> CoTeia</h1>
<hr align="right" />

<?php
$today = getdate(); 
$year = $today['year']; 
if ( $today['mon'] <= '6') {
	$semester = 1;
} else {
	$semester = 2;
}

echo "\n<h2>" . sprintf( _( "Current semester: %d of %d" ), $semester, $year ) . "</h2>";

echo "\n<ul>";
$dbh = coteia_connect();
$sem_atual = $semester . '_' . $year;
$query = "SELECT id,titulo,admin,admin_mail,visivel FROM swiki where (semestre='$sem_atual' || semestre='T') order by titulo";
$result = mysql_query( $query );
while ( $tuple = mysql_fetch_array( $result ) ) {
	if ( $tuple[ "visivel" ] == 'S' ) {
		$title = $tuple[ "titulo" ];
		$admin = $tuple[ "admin" ];
		$email = $tuple[ "admin_mail" ];
		$wikipage_id = $tuple[ "id" ];
		
		echo "\n\t<li><a href=\"show.php?wikipage_id=$wikipage_id\"><strong>$title</strong></a> <a href=\"list.php?swiki_id=$wikipage_id\">[" . _( "index" ) . "]</a>";
		echo _( " (Admin: " ) . "<a href=\"mailto:$email\">$admin</a>)</li>";
	}
}
mysql_free_result( $result );
echo "\n</ul>\n";



$query = "SELECT id,titulo,admin,admin_mail,visivel FROM swiki where (semestre<>'$sem_atual' && semestre<>'T') order by titulo";
$result = mysql_query( $query );

if ( mysql_num_rows( $result ) != 0 ) {
	echo "\n<h2>" . _( "Previous semesters" ) . "</h2>";
	echo "\n<ul>";


	while ( $tuple = mysql_fetch_array( $result ) ) {
		if ( $tuple[ "visivel" ] == 'S' ) {
			$title = $tuple[ "titulo" ];
			$admin = $tuple[ "admin" ];
			$email = $tuple[ "admin_mail" ];
			$wikipage_id = $tuple[ "id" ];


			echo "\n\t<li><a href=\"show.php?wikipage_id=$wikipage_id\"><strong>$title</strong></a> <a href=\"list.php?swiki_id=$wikipage_id\">[" . _( "index" ) . "]</a>";
			echo _( " (Admin: " ) . "<a href=\"mailto:$email\">$admin</a>)</li>";
		}
	}
	echo "\n</ul>";
}
mysql_free_result( $result );

?>

</body>

</html>
