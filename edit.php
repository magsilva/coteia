<?php
/*
* Edita páginas ja criadas.
*/


// Evita ; para concatenacao de comandos SQL
if ((!isset($ident)) or (stristr($ident,";"))) {
	$st = 1;
	include( "erro.php" );
	exit();
}
  
include_once("function.inc");
include_once("cvs/function_cvs.inc");

$dbh = db_connect();
mysql_select_db($dbname,$dbh);

$query = "select ident,pass FROM paginas where ident='$ident'";
$sql = mysql_query( "$query", $dbh );
if (mysql_num_rows($sql) == '0') {
	$st = 1;
	include("erro.php");
	exit();
}

    
if ( $salva ) {
	// Retrieve password.
	while ($tupla = mysql_fetch_array($sql)) {
		$senha = $tupla[ "pass" ];
	}

	// Check password (if there is one to check against).
	if ( $senha != NULL ) {
		if ( (strcasecmp($senha, $passwd)) != "0" ) {
			header("Location:senha_incorreta.php");
			exit();
		}
	}

	$k[1] = $key1;
	$k[2] = $key2;
	$k[3] = $key3;
	
	$coweb_tratamento = tratamento(0,$cria_conteudo,$titulo,$cria_autor,$k);
		
	$conteudo = trim($coweb_tratamento["content"]);
	$titulo = trim($coweb_tratamento["title"]);
	$autor = trim($coweb_tratamento["author"]);
	$keyword[1] = trim($coweb_tratamento["key1"]);
	$keyword[2] = trim($coweb_tratamento["key2"]);
	$keyword[3] = trim($coweb_tratamento["key3"]);

	if (stristr($conteudo,"<note/>")) {
		$conteudo = note($conteudo);
	}

	// Grava no BD sem modificacaoes de links
	$conteudo_puro = $conteudo;

	if (stristr($conteudo,"<lnk>")) {
		$conteudo = link_interno($ident,$conteudo,$dbh);
	}

	if (stristr($conteudo,"</upl>")) {
		$conteudo = img_upload($conteudo);
	}

	if (stristr($conteudo,"</table>")) {
		$conteudo = table_pre($conteudo,"table");
	}

	if (stristr($conteudo,"<pre>")) {
		$conteudo = table_pre($conteudo,"pre");
	}

	if (stristr($conteudo,"</ul>")) {
		$conteudo = table_pre($conteudo,"ul");
	}

	if (stristr($conteudo,"</ol>")) {
		$conteudo = table_pre($conteudo,"ol");
	}

	// Encontra identificar da swiki.
	$get_swiki = explode(".", $ident);
	$id_swiki = $get_swiki[0];  		
		
	// Encontra indexador da pagina - utilizado no linksto
	$query = "SELECT indexador FROM paginas where ident='$ident'";
	$sql = mysql_query("$query",$dbh);
	$tupla = mysql_fetch_array($sql);
	$indexador = $tupla[indexador];

	//linksto - estrutura inicial
        if (($id_swiki) != ($ident)) {
       	        $i = 1;
	} else {
               	$i = 2;
               	$linksto_id[1] = "0";
               	$linksto_titulo[1] = "Lista de Swikis";
	}

	$sql_swiki= "select ident,titulo from paginas where (((ident like '$id_swiki.%') or (ident='$id_swiki')) and (conteudo like '%<lnk>$indexador</lnk>%'))";
	$query_swiki =  mysql_query($sql_swiki,$dbh);
	while ($tupla = mysql_fetch_array($query_swiki)) {
		$linksto_id[$i] = $tupla[ident];
		$linksto_titulo[$i] = $tupla[titulo];
		$i++;
	}

	// Verifica travamento da pagina
	if ( $lock == "locked" ) {
		$flag_lock = 1;
	} else {
		$flag_lock = 0;
	}

	$path_xml = $PATH_XML;
	$arq_xsl = $PATH_XSL;
	$path_html = $PATH_XHTML;
	$dtd = "<!DOCTYPE coteia SYSTEM 'coteia.dtd'>";
	$node = "page";
	$id = "id";
	$lock_xml = "<lock>$flag_lock</lock>";
	$others = "<sw_id>$id_swiki</sw_id>";
	$kwd[1] = "kwd1";
	$kwd[2] = "kwd2";
	$kwd[3] = "kwd3";
	$aut = "aut";
	$tit = "tit";
	$body = "bdy";

	$query_extra = mysql_query("select id_ann,id_chat,id_eclass from swiki where id=\"$id_swiki\"");
	$result = mysql_fetch_array($query_extra);
	$annotation = "<ann_folder>$result[id_ann]</ann_folder>";
	$chat = "<chat_folder>$result[id_chat]</chat_folder>";
	$eclass = "<id_eclass>$result[id_eclass]</id_eclass>";

	if (xml_xsl($ident,$conteudo,$titulo,$autor,$keyword,$arq_xsl,$path_html,$path_xml,$dtd,$node,$id,$lock_xml,$annotation,$chat,$eclass,$others,$linksto_id,$linksto_titulo,$kwd,$aut,$tit,$body)==TRUE) {
		//atualiza arquivo no CVS
		cvs_update($ident, $CVS_MODULE);

		$nro_ip= getenv("REMOTE_ADDR"); 
		$d = getdate();
		$data=$d["year"]."-".$d["mon"]."-".$d["mday"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];

		// If the user has cleaned the wikipage's log flag, remove the password.
		if ( $flag_lock == 0 ) {
			$passwd = NULL;
		}
 		$query = "update paginas SET conteudo='$conteudo_puro',titulo='$titulo',kwd1='$keyword[1]',kwd2='$keyword[2]', kwd3='$keyword[3]',autor='$autor',data_ultversao='$data',pass='$passwd' where ident='$ident'" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query("$query",$dbh);
	} else {
		// Could not apply the XT, log the error.
		$st = 2;
		include("erro.php");
		exit();
	} //xml_xsl
	header("Location:mostra.php?ident=$ident");
} else {
?>
<html>
<?php

$query = "SELECT titulo,conteudo,kwd1,kwd2,kwd3,autor,pass FROM paginas where ident='$ident'";
$sql = mysql_query("$query",$dbh);
while ($tupla = mysql_fetch_array($sql)) {
	$conteudo = $tupla[conteudo];
	$kwd1 = $tupla[kwd1];
	$kwd2 = $tupla[kwd2];
	$kwd3 = $tupla[kwd3];
	$autor = $tupla[autor];
	$tit = $tupla[titulo];
	$senha = $tupla[pass];
}
$conteudo = eregi_replace("<br/>","","$conteudo");	
$cont = eregi_replace("<br />","","$conteudo");	

?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<title>Formulário de Edição</title>
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>

<?php
include( "toolbar.php" );
?>

<form method="POST" name="edit" ACTION="edit.php" onSubmit="return validar(this);">
<div class="lock">
  Lock
  <br /><input type="checkbox" name="lock" value="locked" <?php if ($senha) echo checked; ?> />

  <br />Password
  <br /><input type="password" size="10" name="passwd" onChange="form.edit.lock.checked=true;return false;" />
</div>
 
<div class="metadata">
<table>
<tr>
	<td>
	Título
	<br /><input type="text" name="titulo" value="<?php echo $tit;?>" size="45" />
	</td>
</tr>
<tr>
	<td>
	Autor
	<br /><input type="text" name="cria_autor" value=<?php echo $autor; ?> size="45" />
	</td>
</tr>
<tr>
	<td>
	Palavras-chave:
	<br />
	<input type="text" name="key1" size="15" value="<?php echo $kwd1; ?>" />
	<input type="text" name="key2" size="15" value="<?php echo $kwd2; ?>" />
	<input type="text" name="key3" size="15" value="<?php echo $kwd3; ?>" />
	</td>
</tr>
</table>
</div>

<div class="content" >
	<input type="reset" value="Limpa" />
	<input type="submit" name="salva" value="Salva" />
	<br />
	<textarea name="cria_conteudo" wrap=virtual rows="20" cols="100" style="width: 100%"><?php echo $cont; ?></textarea>
</div>
<input type="hidden" name="ident" value="<?php echo $ident; ?>" />
</form>

<?php
}
?>

</body>

</html>
