<?php
/**
* Search wikipages.
*
* Copyright (C) Carlos de Arruda Júnior
* Modified by Marco Aurélio Graciotto Silva
*
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

// Check parameters
if ( ! isset( $_REQUEST[ "wikipage_id" ] ) ) {
	show_error( _( "Missing parameter: 'wikipage_id'" ), 0 );
}
$wikipage_id = $_REQUEST[ "wikipage_id" ];
if ( check_wikipage_id( $wikipage_id ) === false ) {
	show_error( _( "The parameter 'wikipage_id' is invalid." ), 0 );
}
$swiki_id = extract_swiki_id( $wikipage_id );

if ( isset( $_REQUEST[ "submit" ] ) ) {
	if ( ! isset( $_REQUEST[ "target_swiki_id" ] ) ) {
		show_error( _( "Missing parameter: 'target_swiki_id'" ), 0 );
	}
	$target_swiki_id = $_REQUEST[ "target_swiki_id" ];
	if ( check_swiki_id( $target_swiki_id ) === false ) {
		show_error( _( "The parameter 'target_swiki_id' is invalid." ), 0 );
	}

	if ( isset( $_REQUEST[ "title" ] ) ) {
		$title = trim( $_REQUEST[ "title" ] );
	}

	if ( !isset( $_REQUEST[ "content" ] ) ) {
		$content = trim( $_REQUEST[ "content" ] );
	}

	if ( !isset( $_REQUEST[ "keywords" ] ) ) {
		$keywords = trim( $_REQUEST[ "keywords" ] );
	}
}


// Sending data
echo get_header( _( "Search" ) );

?>
</head>

<body>

<?php

include( "toolbar.php.inc" );

coteia_connect();

if ( isset( $_REQUEST[ "submit" ] ) ) {
	echo "\n<h1>" . _( "Search results" ) . "</h1>";

	$count = 0;
	$query = "select id,titulo from swiki";
	// Search all the swikis
	if ( $target_swiki_id != "0" ) {
		$query .= " where id='$target_swiki_id'";
	}
	$query .= " order by titulo";
	$result = mysql_query( $query );
	while ( $tuple = mysql_fetch_array( $result ) ) {
		$title = $tuple[ "titulo" ];
		$id = $tuple[ "id" ];
		$query2 = "select distinct paginas.titulo,paginas.ident from paginas,gets,swiki where gets.id_sw='$id' and gets.id_pag=paginas.ident";
		if ( isset( $title ) ) {
			$query2 .= " and paginas.titulo LIKE \"%" . mysql_escape_string( $title ) . "%\"";
		}
		if ( isset( $content ) ) {
			$query2 .= " and paginas.conteudo LIKE \"%" . mysql_escape_string( $content ) . "%\"";
		}
		if ( isset( $keywords ) ) {
			$query2 .= " and (paginas.kwd1=\"" . mysql_escape_string( $keywords ) . "\" or paginas.kwd2=\"" . mysql_escape_string( $keywords ) . "\" or paginas.kwd3=\"" . mysql_escape_string( $keywords ) . "\")";
		}
		$result2 = mysql_query( $query2 );
		if ( mysql_num_rows( $result2 ) > 0 ) {
			echo "\n<h2>$title</h2>";
			echo "\n<ul>";
			while ( $tuple2 = mysql_fetch_array( $result2 ) ) {
				$title2 = $tuple2[ "titulo" ];
				$id2 = $tuple2[ "ident" ];
				$count++;
				echo "\n\t<li><a href=\"show.php?wikipage_id=$id2\">$title2</a></li>";
			}
			echo "\n</ul>";
			echo "\n<br />";
		}
	}
	echo "<br />" . _( "Pages found: ") . $count;
} else {
?>
<h1><?php echo _( "Search" ); ?></h1>

<p><?php echo _( "You can search, a specific or all the swikis, by title, content or keywords." ); ?></p>

<form method="post" action="search.php">
	<input type="hidden" name="swiki_id" value="<?php echo $swiki_id;?>">
	<input type="hidden" name="wikipage_id" value="<?php echo $wikipage_id;?>">

	<br />
	<?php echo _( "Swiki" ); ?>
	<select name="target_swiki_id">

		<option value="0"><?php echo _( "Any swiki" ); ?></option>
		<?php
			$query = "select id,titulo from swiki order by titulo";
			$result = mysql_query( $query );
			while ( $tuple = mysql_fetch_array( $result ) ) {
				$title = $tuple[ "titulo" ];
				$id = $tuple[ "id" ];
				echo "<option value=\"$id\"";
				if ( $id == $swiki_id ) {
					echo " selected";
				}
				echo ">" . $title . "</option>";
			}
		?>
	</select>
	<br />
	<?php echo _( "Title" ); ?>
	<input type="text" name="title" width="30"  />
	<br />
	<?php echo _( "Content" ); ?>
	<input type="text" name="content" width="30" />
	<br />
	<?php echo _( "Keyword" ); ?>
	<input type="text" name="keywords" width="20" />
	<br />
	<br />
  <input type="submit" name="submit" value="<?php echo _( "Search" ); ?>" />
</form>
<?
}
?>
</body>
</html>
