<?php
include_once("function.inc");
include_once("cvs/function_cvs.inc");

$dbh = db_connect();
mysql_select_db( $dbname, $dbh );
  
if ( isset( $_REQUEST[ "save" ] ) ) {
	$id_swiki = extract_swiki_id( $ident );
	if ( $id_swiki == false ) {
		$st = 0;
		include( "erro.php" );
	}

	/**
	* Avoid the duplication of index for a given wikipage within it's home swiki.
	*/
	$query = "select count(*) as counter from paginas where indexador='" . 
		$_REQUEST[ "index" ] . "' and ( ident like '$id_swiki.%' or ident='$id_swiki' )";
	$result = mysql_query( $query, $dbh );
	$tuple = mysql_fetch_array( $result );
	if ( intval( $tuple[ "counter" ] ) > 0 ) {
		$st = 0;
		include("erro.php");
	}

	$wikipage_web = prepare_for_web( $_REQUEST[ "index" ], $_REQUEST[ "content" ], $_REQUEST[ "title" ], $_REQUEST[ "author" ], $_REQUEST[ "keyword" ] );

	if ( match_empty_tag( $wikipage[ "content" ], "note" ) ) {
		$wikipage[ "content" ] = note( $wikipage[ "content" ] ) ;
	}

	if ( match_start_tag( $wikipage[ "content" ], "lnk" ) ) {
		$wikipage[ "content" ] = link_interno( $wikipage[ "ident" ], $wikipage[ "content" ], $dbh );
	}

	$wikipage_db = prepare_for_db( $_REQUEST[ "index" ], $_REQUEST[ "content" ], $_REQUEST[ "title" ], $_REQUEST[ "author" ], $_REQUEST[ "keyword" ] );

	if ( strpos( $id_swiki, "." ) != false ) {
		$i = 0;
		$query_swiki = mysql_query( "select ident,titulo from paginas where ( ident like '$id_swiki.%' or ident='$id_swiki' ) and ( conteudo like '%" . $_REQUEST[ "$index" ]%" . ')", $dbh );
		while ( $tuple = mysql_fetch_array( $query_swiki ) ) {
			if ( match_tag( $tuple[ "titulo" ], "lnk", $_REQUEST[ "$index" ] ) ) {
				$linksto_id[$i] = $tuple[ "ident" ];
				$linksto_titulo[$i] = $tuple[ "titulo" ];
				$i++;
			}
		}
	} else {
		$linksto_id[0] = "0";
		$linksto_titulo[0] = "Lista de Swikis";
	}

	$links = "";
	$i = 0;
  if ( $linksto_id[0] == "0" ) {
    $links = $links . "<ref id=\"index.php\">$linksto_titulo[0]</ref>\n";
		$i++;
	}
	for ( ; $i < count( $linksto_id ); $i++ ) {
		$links = $links . "<ref id=\"mostra.php?ident=$linksto_id[$i]\">$linksto_titulo[$i]</ref>\n";
	}
	$wikipage_web[ "links" ] = $links;

	// Verifica travamento da pagina
	if ( $_REQUEST[ "lock" ] == "locked" ) {
		$wikipage_web[ "lock" ] = true;
	} else {
		$wikipage_web[ "lock" ] = "false";
	}

	$wikipage_web[ "id_swiki" ] = $id_swiki;
	
	$path_xml = $PATH_XML;
	$arq_xsl = $PATH_XSL;
	$path_html = $PATH_XHTML;

	$query_extra = mysql_query( "select id_ann, id_chat, id_eclass from swiki where id='$id_swiki'" );
 	$result = mysql_fetch_array( $query_extra );
 	$wikipage_web[ "annotation "] = $result[ "id_ann"];
 	$wikipage_web[ "chat" ]  = $result[ "id_chat" ];
	$wikipage_web[ "eclass" ] = $result[ "id_eclass" ];



	$result = xml_xsl( $wikipage_web, $path_xml, $path_dtd, $path_xsl, $path_html );
	if ( is_bool( $result ) && $result == TRUE ) {
		//adiciona arquivo no CVS
		cvs_add($ident, $CVS_MODULE);

		$nro_ip= getenv("REMOTE_ADDR"); 
		$d = getdate();
		$data=$d["year"]."-".$d["mon"]."-".$d["mday"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];
		
		if ($wikipage_web[ "lock" ] == true ) {
			$passwd = "NULL";
		} else {
			$passwd = "'" . md5( $_REQUEST[ "passwd" ] ) . "'";
		}

		$query = "insert into paginas (ident,indexador,titulo,conteudo,ip,data_criacao,data_ultversao,pass,kwd1,kwd2,kwd3,autor) values ('$wikipage_db[ident]','$wikipage[index]','$wikipage[title]','$wikipage[content]','$nro_ip','$data','$data',$passwd,'$wikipage_db[keyword1]','$wikipage_db[keyword2]','$wikipage_db[keyword3]','$wikipage_db[autor]')";
		$sql = mysql_query( $query, $dbh );
		if ( $sql == false ) {
			$st = 0;
			include( "erro.php" );
		}

 		$query = "insert into gets (id_pag,id_sw,data) values ('$wikipage_db[ident]','$id_swiki','$data')" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query( $query, $dbh );
		if ( $sql == false ) {
			$st = 0;
			include( "erro.php" );
		}
	} else {
		// The XML transformation didn't work.
		$st = 2;
		include("erro.php" );
	}

	foreach ( $linksto_id as $ident_pai ) {
		include( "atualiza.php" );
	}

	header("Location:mostra.php?ident=$wikipage[ident]");
} else {
?>
<html>

<head>
	<title>Formulário de Criação</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<script type="text/javascript" src="coteia.js"></script>
	<link href="coteia.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
include( "toolbar.php" );
?>

<br />

<form method="post" action="create.php" name="create" onSubmit="return validar(this);">
<div class="lock">
	Lock
	<br /><input type="checkbox" name="lock" value="locked" />

	<br />Password
	<br /><input type="password" size="10" name="passwd" onChange="window.document.create.lock.checked=true;return false;" />

	<br />Re-enter password
	<br /><input type="password" size="10" name="repasswd" onChange="window.document.create.lock.checked=true;return false;" />
	<br />
</div>

<div class="metadata">
<table>
<tr>
	<td>
		Título
		<br /><input type="text" name="title" value="<?php echo $_REQUEST[ "index" ]; ?>" size="45" />
	</td>
</tr>
<tr>
	<td>
		Autor
		<br /><input type="text" name="author" size="45" />
	</td>
</tr>
<tr>
	<td>
		Palavras-chave:
		<br />
		<input type="text" name="keywords[1]" size="15" />
		<input type="text" name="keywords[2]" size="15" />
		<input type="text" name="keywords[3]" size="15" />
	</td>
</tr>
</table>
</div>

<div class="content" >
	<input type="reset" value="Limpa" onClick="return confirm('Are you sure? This will restore the original text\n(in another words, you will lose every change made to the text)')"; />
	<input type="submit" name="save" value="Salvar" />
	<br />
	<textarea name="content" wrap=virtual rows="20" cols="100" style="width: 100%"></textarea>
</div>
 
<input type="hidden" name="ident" value="<?php echo $_REQUEST[ "ident" ];?>" />
<input type="hidden" name="index" value="<?php echo $_REQUEST[ "index" ];?>" />
</form>

</body>

</html>
<? 
}
?>
