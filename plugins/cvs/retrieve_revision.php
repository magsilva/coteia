<html>

<head>
	<title>Hist�rico</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<?php
include_once( "../../function.inc" );
include_once( "cvs-api.inc" );

//encontra id_swiki
$ident = $_REQUEST[ "ident" ];
if ( check_wikipage_id( $ident ) == false ) {
  show_error( 0 );
}
?>
<body>

<?php
	include( "../../toolbar.inc" );
?>

<?php
//variaveis que devem vir de outro script
$comparar = $_REQUEST[ "compara" ];
$revisao = $_REQUEST[ "revisao" ];
$filename = $CVS_MODULE . "/" . $ident . ".html";

// Se a op��o "Comparar com vers�o atual" estiver ativada, mostrar a vers�o atual.
if ( $_REQUEST[ "compare" ] ) {
?>
	<div class="source1">
	<p><b>Vers�o Atual</b></p>

<?php
	$tmp = cvs_checkout_file( $filename, "HEAD" );
	$tmp = preg_replace( "'<html>.*?<h2>'si", "<h2>", $tmp, 1 );

	$original = "'(\s)'";
	$conteudo = preg_replace( $original, "\\1", $tmp );
	$conteudo = eregi_replace( "</body>", "", $conteudo );
	$conteudo = eregi_replace( "</html>", "", $conteudo );

	$original = array( "'(<a [^>].*?>)'si", "'</a>'si" );
	$nova = array( '<font style="text-decoration: underline; color: blue;">', "</font>" );
	echo preg_replace( $original, $nova, $conteudo );
	echo "</div>";
}

if ( $comparar ) {
	echo '<div class="source2">';
} else {
	echo '<div class="source2" style="width: 100%">';
}
echo "<p><b>Vers�o $revisao</b></p>";
$tmp = cvs_checkout_file( $filename, $revisao );
$tmp = preg_replace( "'<html>.*?<h2>'si", "<h2>", $tmp, 1 );

//remover caracteres de quebra de linha e tabs da nova string
$original = "'(\s)'";
$conteudo = preg_replace( $original, "\\1", $tmp );
$conteudo = eregi_replace( "</body>","",$conteudo );
$conteudo = eregi_replace( "</html>","",$conteudo );

$original = array( "'(<a [^>].*?>)'si", "'</a>'si" );
$nova = array( '<font style="text-decoration: underline; color: blue;">', "</font>" );
        
print preg_replace( $original, $nova, $conteudo );
echo "</div>";
?>

</body>

</html>
