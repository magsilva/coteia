<?php
/*
* Interface de historico das paginas.
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
include_once("function.inc");
include_once("cvs/function_cvs.inc");

// Encontra id_swiki
$ident = $_REQUEST[ "ident" ];
$get_swiki = explode( ".", $ident );
$id_swiki = $get_swiki[ 0 ];
$revision = cvs_get_revisions( $CVS_MODULE . "/" . $ident . ".html" );
?>

<body>

<?php
include( "toolbar.php" );
?>

<form method="post" action="getrevisao_cvs.php">

	<!-- check box para o usuario ter opcao de comparar com versao original ou nao -->
	<input type="checkbox" name="compara" value="1" checked />Comparar com a versao atual
	<br />
	<select name="revisao">
	<?php
		for ( $i = 0; $i < count( $revision ); $i++ ) {
//			print "\t<option value=\"$i\">Em $revision[ $i ]</option>\n";
		}
	?>
	</select>
	<input type="hidden" name="ident" value="<?php echo $ident;?>" />
	<input type="submit" name="submit_btn" value="submit" />
</form>

</body>

</html>
