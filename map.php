<?php
/**
* Show the swiki's map.
*
* Copyright (C) ?
*
* This code is licenced under the GNU General Public License (GPL).
*/

include_once( "function.php.inc" );

// Check parameters
if ( ! isset( $_REQUEST[ "swiki_id" ] ) ) {
	show_error( _( "Missing parameter: 'swiki_id'" ), 0 );
}
$swiki_id = $_REQUEST[ "swiki_id" ];
if ( check_swiki_id( $swiki_id ) === false ) {
	show_error( _( "The parameter 'swiki_id' is invalid." ) );
}

if ( isset( $_REQUEST[ "p" ] ) ) {
	$p = $_REQUEST[ "p" ];
}


// Start to send the output
echo get_header( _( "Map of the swiki" ) );

include( "toolbar.php.inc" );
?>
</head>

<body>

<h1><?php echo _( "Map fo the swiki" ); ?></h1>

<?php
coteia_connect();

$maxlevel = 0;
$cnt = 0;
$query = "select ident,indexador from paginas where ident='$swiki_id' or ident like '$swiki_id.%'";
$result = mysql_query( $query );

while ( $tuple = mysql_fetch_array( $result ) ) {
	$level = substr_count( $tuple[ "ident" ] . "" , "." );
	$level = $level + 1;
	$tree[ $cnt ][ 0 ] = $level;
	$tree[ $cnt ][ 1 ] = $tuple[ "indexador" ];
	$tree[ $cnt ][ 2 ] = "show.php?wikipage_id=" . $tuple[ "ident" ];
	$tree[ $cnt ][ 3 ] = 0;
	if ( $tree[ $cnt ][ 0 ] > $maxlevel ) {
		$maxlevel=$tree[ $cnt ][ 0 ];
	}
	$cnt++;
}

$img_expand   = "$IMAGES_DIR/tree_expand.png";
$img_collapse = "$IMAGES_DIR/tree_collapse.png";
$img_line     = "$IMAGES_DIR/tree_vertline.png";  
$img_split		= "$IMAGES_DIR/tree_split.png";
$img_end      = "$IMAGES_DIR/tree_end.png";
$img_leaf     = "$IMAGES_DIR/tree_leaf.png";
$img_spc      = "$IMAGES_DIR/tree_space.png";

for ( $i = 0; $i < count( $tree ); $i++ ) {
	$expand[ $i ] = 0;
	$visible[ $i ] = 0;
	$levels[ $i ] = 0;
}

// Get node count.
if ( isset( $p ) && $p!="" ) {
	$explevels = explode( "|", $p );
} else {
	$explevels = array();
}
  
$i = 0;
while ( $i < count( $explevels ) ) {
	$expand[ $explevels[ $i ] ] = 1;
	$i++;
}
  
// Find the subtree's last node.
$lastlevel = $maxlevel;
for ( $i = ( count( $tree ) - 1 ); $i >= 0; $i-- ) {
	if ( $tree[ $i ][ 0 ] < $lastlevel ) {
		for ( $j = $tree[ $i ][ 0 ] + 1; $j <= $maxlevel; $j++ ) {
			$levels[ $j ] = 0;
		}
	}
	if ( $levels[ $tree[ $i ][ 0 ] ] == 0 ) {
		$levels[ $tree[ $i ][ 0 ] ] = 1;
		$tree[ $i ][ 3 ] = 1;
	} else {
		$tree[ $i ][ 3 ] = 0;
	}
	$lastlevel = $tree[$i][0];
}
  
  
// Find the visible nodes
  
// All root nodes are always visible
for ( $i = 0; $i < count( $tree ); $i++ ) {
	if ( $tree[ $i ][ 0 ] == 1 ) {
		$visible[ $i ] = 1;
	}
}

for ( $i = 0; $i < count( $explevels ); $i++ ) {
	$n = $explevels[ $i ];
	if ( ( $visible[ $n ] == 1 ) && ( $expand[ $n ] == 1 ) ) {
		$j = $n + 1;
		while ( $tree[ $j ][ 0 ] > $tree[ $n ][ 0 ] ) {
			if ( $tree[ $j ][ 0 ] == $tree[ $n ][ 0 ] +1 ) {
				$visible[ $j ] = 1;
			}
			$j++;
		}
	}
}

// Prepare the output tree
for ( $i = 0; $i < $maxlevel; $i++ ) {
	$levels[ $i ] = 1;
}

$maxlevel++;
echo "<table cellspacing=0 cellpadding=0 border=0 cols=" . ( $maxlevel + 3 ) . " width=100%>\n";
echo "<tr>";
for ( $i = 0; $i < $maxlevel; $i++ ) {
	echo "<td width=16></td>";
}
echo "<td width=100%>&nbsp;</td></tr>\n";
$cnt = 0;
while ( $cnt < count( $tree ) ) {
	if ( $visible[ $cnt ] ) {
		echo "<tr>";

		// Vertical lines
		$i = 0;
		while ( $i < $tree[ $cnt ][ 0 ] - 1 ) {
			if ( $levels[ $i ] == 1 ) {
				echo "<td><a name='$cnt'></a><img src=\"".$img_line."\"></td>";
			} else {
				echo "<td><a name='$cnt'></a><img src=\"".$img_spc."\"></td>";
			}
			$i++;
		}

		// Break the tree      
		if ( $tree[ $cnt ][ 3 ] == 1 ) {
			echo "<td><img src=\"" . $img_end . "\"></td>";
			$levels[ $tree[ $cnt ][ 0 ] - 1 ] = 0;
		} else {
			echo "<td><img src=\"" . $img_split . "\"></td>";
			$levels[ $tree[ $cnt ][ 0 ] - 1 ] = 1;
		}

		// Find out if the node is a leaf node
		if ( $tree[ $cnt + 1 ][ 0 ] > $tree[ $cnt ][ 0 ] ) {
			// Prepare parameters
			$i = 0;
			$params = "?swiki_id=" . $swiki_id . "&p=";
			while( $i < count( $expand ) ) {
				if ( ( $expand[ $i ] == 1 ) && ( $cnt != $i ) || ( $expand[ $i ] == 0 && $cnt == $i ) ) {
					$params .= $i;
					$params .= "|";
				}
				$i++;
			}
			if ( $expand[ $cnt ] == 0 ) {
				echo "<td><a href=\"map.php" . $params . "#$cnt\"><img src=\"" . $img_expand . "\"></a></td>";
			} else {
				echo "<td><a href=\"map.php" . $params . "#$cnt\"><img src=\"" . $img_collapse . "\"></a></td>";    
			}
		} else {
			// Leaf nodes
			echo "<td><img src=\"" . $img_leaf . "\"></td>";
		}

		// Output
		if ( $tree[ $cnt ][ 2 ] == "" ) {
			echo "<td colspan=" . ( $maxlevel - $tree[ $cnt ][ 0 ] ) . ">" . $tree[ $cnt ][ 1 ] . "</td>";
		} else {
			echo "<td colspan=" . ( $maxlevel - $tree[ $cnt ][ 0 ] ) . "><a href=\"javascript:window.opener.document.location.replace('" . $tree[ $cnt ][ 2 ] . "')\">" . $tree[ $cnt ][ 1 ] . "</a></td>";
		}
		echo "</tr>\n";
	}
	$cnt++;
}
echo "</table>\n";
?>


</body>

</html>
