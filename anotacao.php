<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>GroupNote - CoTeia</title>
</head>
<body text="#000000" vLink="#cc0000" aLink="#cccc00" link="#0000ff" bgColor="#ffffff">
<?php
    include_once("function.inc");
    include_once("arvore_anotacoes.php");
    //echo "id swiki: $sw_id iduser: $id_usuario idgroup:$id_grupo<br>";
    if ($mostra == "true"){

        // apresenta a anotação
        // Busca dados da anotação
        $XSL_path = "http://coweb.icmc.sc.usp.br/webnote/annotation";
        $aux = get_annotation_xml($id_anotacao, $XSL_path);

        $pos_ini = strpos($aux, "<dc:title>")+10;
        $pos_end = strpos($aux, "</dc:title>");
        $titulo = substr($aux,$pos_ini,$pos_end-$pos_ini);
        $pos_ini = strpos($aux, "<an:owner>")+10;
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
	echo "<tr><td><b>T&iacute;tulo:</b> $titulo </td></tr>\n";
	echo "<tr><td><b>Autor:</b> $owner </td></tr>\n";
	echo "<tr><td><b>Grupo:</b> $group </td></tr>\n";
	echo "<tr><td><b>Data de cria&ccedil;&atilde;o:</b> $dt_cad </td></tr>\n";
	echo "<tr><td><b>Conte&uacute;do:</b> $body</td></tr>\n";
	echo "</table>\n";
        echo "<form name='form-reply' method='post' action='add-annotation.php'>\n";
	echo "<input name='id_pasta' type='hidden' value='$id_pasta'>\n";
	echo "<input name='id_usuario' type='hidden' value='$id_usuario'>\n";
	echo "<input name='id_grupo' type='hidden' value='$id_grupo'>\n";
	echo "<input name='annotates' type='hidden' value='$annotates'>\n";
	echo "<input name='id_father' type='hidden' value='$id_anotacao'>\n";
	echo "<input name='sw_id' type='hidden' value='$sw_id'>\n";

	echo "<input name='reply' type='submit' value='Responder'>\n";
	echo "</form>\n</br>";
	} // fim if

?>

<form name="form-add-annotation" method="post" action="add-annotation.php">
<input name="id_pasta" type="hidden" value="<?echo $id_pasta;?>">
<input name="annotates" type="hidden" value="<?echo $annotates?>">
<input name="id_father" type="hidden" value="<?echo $id_father?>">
<input name="id_usuario" type="hidden" value="<?echo $id_usuario?>">
<input name="id_grupo" type="hidden" value="<?echo $id_grupo?>">
<input name="sw_id" type="hidden" value="<?echo $sw_id?>">

<input name="enviar" type="submit" value="Criar Nova Anota&ccedil;&atilde;o"><br>
</form>

<table border=1 cellspacing=0 width=100%>
<tr><td bgcolor=#0099FF align=center><font size=2><b>Anota&ccedil;&otilde;es</b></font></td></tr>
</table>

<?
  if ($p == '') $p = "0";
  init($p,$annotates, $id_pasta, $id_usuario, $id_grupo, $sw_id);
?>
<br><br>
<center><a href="javascript:this.close()">Fechar Janela</a></center>
</body>
</html>
