<html>

<head>
	<title>Histórico</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<script type="text/javascript" src="coteia.js"></script>
</head>

<?php
include_once("function.inc");
include_once("cvs/function_cvs.inc");

//encontra id_swiki
$id_swiki = $ident[0];
if ($ident[1] != ".") {
	$id_swiki = $ident[0] . $ident[1];
}
?>
<body>

<?php
include( "toolbar.php" );
?>

<?php
//variaveis que devem vir de outro script
$ident = $_REQUEST[ "ident" ];
$comparar = $_REQUEST[ "compara" ];
$revisao = $_REQUEST[ "revisao" ];
$filename = $CVS_MODULE . "/" . $ident . ".html";

// Se a opção "Comparar com versão atual" estiver ativada, mostrar a versão atual.
if ( $comparar == 1 ) {
?>
	<div class="source1">
	<p><b>Versão Atual</b></p>

<?php
	$tmp = cvs_checkout_file( $filename, "HEAD" );
	$tmp = eregi_replace( "<html>.*<h2>", "<h2>", $tmp );

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
echo "<p><b>Versão $revisao</b></p>";
$tmp = cvs_checkout_file( $filename, $revisao );

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
