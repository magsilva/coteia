<?php
include_once( "../function.inc" );

// Faz a conexao com o SGBD e recupera o conteúdo de todas as wikipages.
$dbh = db_connect();
mysql_select_db($dbname,$dbh);

$query =  "select * from paginas order by ident";
$query_result = mysql_query( $query, $dbh );

// Enquanto existir uma tupla, será criado um arquivo XML e um HTML
while ( $resultado = mysql_fetch_array( $query_result ) ) {
	$ident = $resultado["ident"];
	echo "\n$ident...";

	$indexador = $resultado["indexador"];
	$conteudo = $resultado["conteudo"];
	$titulo = $resultado["titulo"];
	$autor = $resultado["autor"];
	$keyword[1] = $resultado["kwd1"];
	$keyword[2] = $resultado["kwd2"];
	$keyword[3] = $resultado["kwd3"];
	$lock = $resultado["pass"];
	
	// Encontra id_swiki
	if (stristr($ident,".")) {
		$get_swiki = explode(".",$ident);
		$id_swiki = $get_swiki[0];
	} else {
		$id_swiki = $ident;
	}

	// Estabelece as ligações com outras wikipages.
	if ( stristr( $conteudo, "<lnk>" ) ) {
		$conteudo = link_interno( $ident, $conteudo, $dbh );
	}

	if ( stristr( $ident, ".") ) {
		//links to this page
		$i = 1;
		$query_wikilinks = mysql_query( "select ident,titulo from paginas where (((ident like '$id_swiki.%')  or (ident='$id_swiki')) and (conteudo like '%<lnk>$indexador</lnk>%'))", $dbh );
		while ($tupla = mysql_fetch_array( $query_wikilinks ) ) {
			$linksto_id[ $i ] = $tupla[ "ident" ];
			$linksto_titulo[ $i ] = $tupla[ "titulo" ];
			$i++;
		}
	} else {
		$linksto_id[1] = "0";
		$linksto_titulo[1] = "Lista de Swikis";
	}

	//verifica travamento da pagina
	if ( strlen($lock) > 0 ) {
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

	$query_extra = mysql_query("select id_ann,id_chat,id_eclass from swiki where id='$id_swiki'");
 	$result = mysql_fetch_array($query_extra); 
 	$annotation = "<ann_folder>" . $result["id_ann"] . "</ann_folder>";
 	$chat = "<chat_folder>" . $result["id_chat"] . "</chat_folder>";
	$eclass = "<id_eclass>" . $result["id_eclass"] . "</id_eclass>";

	// A variavel result recebe TRUE se o XML e o HTML foram criados corretamente. Caso algum erro
	// tenha ocorrido, será retornado um array com as mensagens de erro.
	$result = xml_xsl($ident,$conteudo,$titulo,$autor,$keyword,$arq_xsl,$path_html,$path_xml,$dtd,$node,$id,$lock_xml, $annotation,$chat,$eclass,$others,$linksto_id,$linksto_titulo,$kwd,$aut,$tit,$body);

	// Verificação do resultado obtido na transformação XML -> HTML
	if ( is_bool( $result ) && $result == TRUE ) {
		cvs_update( $ident, $CVS_MODULE );
		echo "Ok";
	} else {
		echo "Erro";
		foreach ( $result as $error_message ) {
  		echo "\n\t$error_message";
		}
	}
}

mysql_free_result( $query_result );

?>
