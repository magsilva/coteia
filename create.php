<?
include_once("function.inc");
include_once("cvs/function_cvs.inc");

// Evita ; para concatenacao de comandos SQL
if ( (!isset($ident)) or (stristr($ident,";") ) ) {
	$st = 1;
	include("erro.php");
	exit();
}

$dbh = db_connect();
                
mysql_select_db($dbname,$dbh);
  
if ($salva) {
	if (stristr($ident,".")) {
		// Encontra id_swiki
		$get_swiki = explode(".",$ident);
		$id_swiki = $get_swiki[0];
	} else {
		 $id_swiki = $ident;
	}

	$query =  "select indexador from paginas where ((indexador='$indexador') and ((ident like '$id_swiki.%')  or (ident='$id_swiki')))";
	$result = mysql_query($query,$dbh);

	while ($tupla = mysql_fetch_array($result)) {
		if (!strcmp(trim($indexador),trim($tupla[indexador]))) {
			$st = 3;
			include("erro.php");
			exit();
	        }
	}
  
	$k[1] = $key1;
	$k[2] = $key2;
	$k[3] = $key3;

	$coweb_tratamento = tratamento($indexador,$cria_conteudo,$titulo,$cria_autor,$k);
	
	$indexador = $coweb_tratamento["index"];
	$conteudo = trim($coweb_tratamento["content"]);
	$titulo = trim($coweb_tratamento["title"]);
	$autor = trim($coweb_tratamento["author"]);
	$keyword[1] = trim($coweb_tratamento["key1"]);
	$keyword[2] = trim($coweb_tratamento["key2"]);
	$keyword[3] = trim($coweb_tratamento["key3"]);
		
	if (stristr($conteudo,"<note/>")) {
		$conteudo = note($conteudo);
	}

	// grava no BD sem modificacaoes de links
	$conteudo_puro=$conteudo;

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

	if (stristr($conteudo,"<lnk>")) {
               $conteudo = link_interno($ident,$conteudo,$dbh);
	}

	if (stristr($ident,".")) {
		//links to this page
		$i = 1;
		$query_swiki = mysql_query("select ident,titulo from paginas where (((ident like '$id_swiki.%')  or (ident='$id_swiki')) and (conteudo like '%<lnk>$indexador</lnk>%'))",$dbh);
		while ($tupla = mysql_fetch_array($query_swiki)) {
			$linksto_id[$i] = $tupla[ident];
	        	$linksto_titulo[$i] = $tupla[titulo];
	        	$i++;
		}
	} else {
               	$linksto_id[1] = "0";
               	$linksto_titulo[1] = "Lista de Swikis";
	}

	//verifica travamento da pagina
        if ($lock == locked) {
		$flag_lock = 1;
        } else {
		$flag_lock = 0;
	}
	
	$cp_java = $PATH_JAVA;
	$cp_xt = $PATH_XT;
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

	$query_extra = mysql_query("select id_ann,id_chat,id_eclass from swiki where id='$id_swiki'");
       	$result = mysql_fetch_array($query_extra); 
       	$annotation = "<ann_folder>$result[id_ann]</ann_folder>";
       	$chat = "<chat_folder>$result[id_chat]</chat_folder>";
	$eclass = "<id_eclass>$result[id_eclass]</id_eclass>";

	if (xml_xsl($ident,$conteudo,$titulo,$autor,$keyword,$arq_xsl,$cp_xt,$cp_java,$path_html,$path_xml,$dtd,$node,$id,$lock_xml,$annotation,$chat,$eclass,$others,$linksto_id,$linksto_titulo,$kwd,$aut,$tit,$body)==TRUE) {
		//adiciona arquivo no CVS
                add_cvs($ident,"html/");

		$nro_ip= getenv("REMOTE_ADDR"); 
		$d = getdate();
    		$data=$d["year"]."-".$d["mon"]."-".$d["mday"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];
		
		if ($flag_lock == 1) {
			$query = "insert into paginas (ident,indexador,titulo,conteudo,ip, data_criacao,data_ultversao,pass, kwd1, kwd2, kwd3,autor) values ('$ident','$indexador','$titulo','$conteudo_puro','$nro_ip','$data','$data', '$passwd','$keyword[1]','$keyword[2]','$keyword[3]','$autor')" or die  ("Falha ao inserir no Banco de Dados");
			$sql = mysql_query("$query",$dbh);
 
		} else {
			$query = "insert into paginas (ident,indexador,titulo,conteudo,ip, data_criacao, data_ultversao ,pass, kwd1, kwd2, kwd3,autor) values ('$ident','$indexador','$titulo', '$conteudo_puro', '$nro_ip','$data','$data',NULL,'$keyword[1]','$keyword[2]','$keyword[3]','$autor')" or die ("Falha ao inserir no Banco de Dados");
			$sql = mysql_query("$query",$dbh);
		}
		
		$query = "insert into gets (id_pag,id_sw,data) values ('$ident','$id_swiki','$data')" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query("$query",$dbh);
	} else {
		//nao criou arquivo fisico >> erro 
		$st = 2;
		include("erro.php" );
		exit();
	}

	if (stristr($ident,".")) {
		$pos_lstdot = strrpos($ident,".");
		$ident_pai = substr($ident,0,$pos_lstdot);
                 
	        include("atualiza.php");
		//atualiza pagina pai
       	        update_cvs($ident_pai,"html/");
       }

	header("Location:mostra.php?ident=$ident");
} else {
?>
<html>

<head>
	<title>Formulario de Criação</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script language="javascript">
	function validar() {
		// Verifica se o campo titulo foi preenchido
		if (document.create.titulo.value == "") {
			alert('O campo título é de preenchimento obrigatório!');
			document.create.titulo.value = "";
			document.create.titulo.focus();
			return false;
		}
		if (document.create.passwd.value != document.create.repasswd.value) {
			alert('As senhas digitadas não coincidem!');
			document.create.passwd.focus();
			return false;
		}
		return true;
	}
	</script>
	<style type="text/css">
		div.content {
			position:relative;
			text-align: left;
			border-style:none;
			margin-top: 3ex;
			float:left;
		}
		div.metadata {
			position: relative;
			text-align: left;
			border-style:none;
			float: left;
		}
		div.lock {
			position: relative;
			text-align: right;
			border-style:none;
			float: right;
		}
		img {
			border-style:none;
		}
	</style>
</head>

<body>
<img src="<?php echo $URL_IMG; ?>/viewbw.png" />
<img src="<?php echo $URL_IMG; ?>/editbw.png" />
<img src="<?php echo $URL_IMG; ?>/historybw.png" />
<img src="<?php echo $URL_IMG; ?>/indicebw.png" />
<img src="<?php echo $URL_IMG; ?>/mapbw.png" />
<img src="<?php echo $URL_IMG; ?>/changesbw.png" />
<img src="<?php echo $URL_IMG; ?>/uploadbw.png" />
<img src="<?php echo $URL_IMG; ?>/searchbw.png" />
<a href="help.php"><img src="<?php echo $URL_IMG; ?>/helpbw.png" /></a>
<img src="<?php echo $URL_IMG; ?>/chatbw.png" />
<img src="<?php echo $URL_IMG; ?>/notebw.png" />
<img src="<?php echo $URL_IMG; ?>/printbw.png" />

<br />

<form method="post" action="create.php" name="create" onSubmit="return validar();">
<div class="lock">
	Lock
	<br /><input type="checkbox" name="lock" value="locked" />

	<br />Password
	<br /><input type="password" size="10" name="passwd" onBlur="window.document.create.lock.checked=true;return false;" />

	<br />Re-enter password
	<br /><input type="password" size="10" name="repasswd" />
	<br />
</div>

<div class="metadata">
<table>
<tr>
	<td>
        	Título
		<br /><input type="text" name="titulo" value="<?echo $index?>" SIZE="45" />
	</td>
</tr>
<tr>
	<td>
		Autor
		<br /><input type="text" name="cria_autor" size="45" />
	</td>
</tr>
<tr>
	<td>
		Palavras-chave:
		<br />
		<INPUT TYPE="text" NAME="key1" SIZE="15" />
		<INPUT TYPE="text" NAME="key2" SIZE="15" />
		<INPUT TYPE="text" NAME="key3" SIZE="15" />
	</td>
</tr>
</table>
</div>

<div class="content" >
	<input type="reset" value="Limpa" />
	<input type="submit" name="salva" value="Salva" />
		<br />
	<textarea name="cria_conteudo" wrap=virtual rows="20" cols="100" style="width: 100%"></textarea>
</div>
 
<input type="hidden" name="ident" value="<?echo $ident?>"  />
<input type="hidden" name="indexador" value="<?echo $index?>"  />
</form>

</body>

</html>
<? 
}
?>
