<?php
include_once( dirname(__FILE__) . "/annotation-api.inc" );
include_once( dirname(__FILE__) . "/annotation_tree.php" );
include_once( dirname(__FILE__) . "/../../function.php.inc" );
?>

<?php
if ( ! isset( $_REQUEST[ "action" ]  ) ) {
	$action = "create";
} else {
	$action = $_REQUEST[ "action" ];
}

/**
* Check parameters.
*/
$p = $_REQUEST[ "p" ];
$id_pasta = $_REQUEST[ "id_pasta" ];
$annotates = $_REQUEST[ "annotates" ];
$id_father = $_REQUEST[ "id_father" ];
$id_usuario = $_REQUEST[ "id_usuario" ];
$id_grupo = $_REQUEST[ "id_grupo" ];
$swiki_id = $_REQUEST[ "swiki_id" ];
?>


<?php
echo get_header( _( "CoTeia - Annotations" ) );
?>
</head>

<body>

<?php
if ( $action == "view" ) {
	// Busca dados da anotação
	$XSL_path = "http://coweb.icmc.sc.usp.br/webnote/annotation";
	$aux = get_annotation_xml( $id_anotacao, $XSL_path );

	// TODO: Use "preg_replace".
	$pos_ini = strpos( $aux, "<dc:title>") + 10;
	$pos_end = strpos( $aux, "</dc:title>" );
 	$titulo = substr( $aux, $pos_ini, $pos_end - $pos_ini );
	$pos_ini = strpos($aux, "<an:owner>") + 10;
	$pos_end = strpos($aux, "</an:owner>");
 	$owner = substr($aux,$pos_ini,$pos_end-$pos_ini);
	$pos_ini = strpos($aux, "<an:group>")+10;
	$pos_end = strpos($aux, "</an:group>");
	$group = substr($aux,$pos_ini,$pos_end-$pos_ini);
	$pos_ini = strpos($aux, "<an:creation_date>")+18;
	$pos_end = strpos($aux, "</an:creation_date>");
	$dt_cad = substr($aux,$pos_ini,$pos_end-$pos_ini);
	$pos_ini = strpos($aux, "<body>")+6;
	$pos_end = strpos($aux, "</body>");
	$body = substr($aux,$pos_ini,$pos_end-$pos_ini);
	$body =  eregi_replace("<br/>","<br>","$body"); 
	echo "<table border cellspacing=0 cellpadding=0 width=90% bgcolor=#E1F0FF bordercolor=#C0C0C0 bordercolordark=#C0C0C0 bordercolorlight=#C0C0C0>\n";
	echo "<tr><td><b>Título:</b> $titulo </td></tr>\n";
	echo "<tr><td><b>Autor:</b> $owner </td></tr>\n";
	echo "<tr><td><b>Grupo:</b> $group </td></tr>\n";
	echo "<tr><td><b>Data de criação:</b> $dt_cad </td></tr>\n";
	echo "<tr><td><b>Conteúdo:</b> $body</td></tr>\n";
	echo "</table>\n";
	echo "<form name='form-reply' method='post' action='add-annotation.php' />\n";
	echo "<input name='id_pasta' type='hidden' value='$id_pasta' />\n";
	echo "<input name='id_usuario' type='hidden' value='$id_usuario' />\n";
	echo "<input name='id_grupo' type='hidden' value='$id_grupo' />\n";
	echo "<input name='annotates' type='hidden' value='$annotates' />\n";
	echo "<input name='id_father' type='hidden' value='$id_anotacao' />\n";
	echo "<input name='swiki_id' type='hidden' value='$swiki_id' />\n";
	echo "<input name='reply' type='submit' value='Responder' />\n";
	echo "</form>\n</br>";
}
?>

<form name="form-add-annotation" method="post" action="add-annotation.php">
	<input name="id_pasta" type="hidden" value="<?php echo $id_pasta;?>" />
	<input name="annotates" type="hidden" value="<?php echo $annotates;?>" />
	<input name="id_father" type="hidden" value="<?php echo $id_father;?>" />
	<input name="id_usuario" type="hidden" value="<?php echo $id_usuario;?>" />
	<input name="id_grupo" type="hidden" value="<?php echo $id_grupo;?>" />
	<input name="swiki_id" type="hidden" value="<?php echo $swiki_id;?>" />
	<input name="submit" type="submit" value="<?php echo _( "Create new annotation" ); ?>" />
</form>

<h2><?php echo _( "Annotations" ); ?></h2>
<?
	if ( $p == '' ) {
		$p = "0";
	}
	init( $p, $annotates, $id_pasta, $id_usuario, $id_grupo, $swiki_id );
?>

</body>

</html>
