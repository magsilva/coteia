<?php
include_once("function.inc");
global $dbname;
?>

<html>

<head>
	<title>Recent Changes</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php

include( "toolbar.php" );

// Encontra id_swiki
$get_swiki = explode( ".", $ident );
$id_swiki = $get_swiki[0];  

$dbh = db_connect();
# seleciona base de dados
mysql_select_db( $dbname, $dbh );

if ( $submit_btn == "submit" ) {
	if ( $changes_select == 0 ) {
		// Buscar todas as páginas de todos os swikis ordenadas por data.
		$resultA = mysql_query( "select id,titulo from swiki order by titulo", $dbh );
		echo "<br />";
		while ( $tuplaA = mysql_fetch_array( $resultA ) ) {
			$tituloA = $tuplaA[ "titulo" ];
			$idA = $tuplaA[ "id" ];
			$resultB = mysql_query( "SELECT paginas.data_ultversao, paginas.titulo,paginas.ident FROM paginas, gets WHERE gets.id_sw = $idA AND gets.id_pag =paginas.ident ORDER BY paginas.data_ultversao DESC", $dbh );
			$num_rows = mysql_num_rows( $resultB );
			if ( $num_rows != "0" ) {
				echo "<br /><b>$tituloA:</b>";
				while ( $tuplaB = mysql_fetch_array( $resultB ) ) {
					// Acerta o formato da data.
					$datetime = explode(" ",$tuplaB[ "data_ultversao" ] );
					$date = explode("-",$datetime[0]);
					$data_formato_correto = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $datetime[1];
					$tituloB = $tuplaB[ "titulo" ];
					$idB = $tuplaB[ "ident" ];
					echo "<br />\t[$data_formato_correto] - <a href=\"mostra.php?ident=$idB\">$tituloB</a>";
				}
			}
		}
	} else {
		// Buscar as paginas de 1 swiki ordenadas por data  $select é a numero de identificacao do swiki.
		$sql = "SELECT paginas.data_ultversao, paginas.titulo, paginas.ident FROM paginas, gets WHERE gets.id_sw=$changes_select AND gets.id_pag =paginas.ident ORDER BY paginas.data_ultversao DESC";
		$result = mysql_query( $sql, $dbh );
		$num_rows = mysql_num_rows( $result );
		if ( $num_rows != "0" ) {
			while ( $tupla = mysql_fetch_array( $result ) ) {
				// Acerta o formato da data
				$datetime = explode( " ", $tupla["data_ultversao"] );
				$date = explode("-",$datetime[0]);
				$data_formato_correto = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $datetime[1];
				$titulo = $tupla[ "titulo" ];
				$id = $tupla[ "ident" ];
				echo "<br />[$data_formato_correto] - <a href=\"mostra.php?ident=$id\">$titulo</a>";
			}
		} else {
			echo "Não existem páginas criadas.";
		}
	}
} else {
?>

<form method="post" action="changes.php">
	<select name="changes_select">
		<option value="0">Em todas as Swikis</option>
		<?php
			$sql = mysql_query( "SELECT id,titulo FROM swiki order by titulo", $dbh );
			while ( $tupla = mysql_fetch_array( $sql ) ) {
				$titulo = $tupla[ "titulo" ];
				$id_titulo = $tupla[ "id" ];
				if ( $id_titulo != $id_swiki ) {
					echo "<option value=\"$id_titulo\">Em $titulo</option>";
				} else {
					echo "<option value=\"$id_titulo\" selected>Em $titulo</option>";
				}
			}
		?>
	</select>
	<input type="submit" name="submit_btn" value="submit" />
	<input type="hidden" name="ident" value="<?php echo $ident;?>" />
</form>
<?php
}
?>

</body>

</html>
