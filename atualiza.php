<? 
/* 
* Atualiza a página-pai da página recém-alterada (com a alteracao de determinada página, sua
* página pai pode ou não sofrer alterações.
*/
if ( ! is_null( $ident_pai ) ) {
	$dbh = db_connect();
	mysql_select_db($dbname,$dbh);

	$query = "select * from paginas where ident='$ident_pai'";  
	$result = mysql_query($query,$dbh);

	while ($tupla = mysql_fetch_array($result)){
		$titulo = $tupla[ "titulo" ];
		$conteudo = $tupla[ "conteudo" ];
		$keyword[1] = $tupla[ "kwd1" ];
		$keyword[2] = $tupla[ "kwd2" ];
		$keyword[3] = $tupla[ "kwd3" ];
		$autor = $tupla[ "autor" ];
		$senha = $tupla[ "pass" ];

		if ( stristr( $ident_pai, "." ) ) {
			//encontra id_swiki
 			$get_swiki = explode( ".", $ident_pai );
			$id_swiki = $get_swiki[ 0 ];
		} else {
			$id_swiki = $ident_pai;
		}

		if ( stristr( $conteudo, "<lnk>" ) ) {
			$conteudo = link_interno($ident_pai,$conteudo,$dbh);
		}

		if ($senha) {
			$lock = "<lock>1</lock>";
		} else {
			$lock = "<lock>0</lock>";
		}
	
		// Links to this page.
		$query_swiki =  mysql_query( "select indexador from paginas where ident='$ident_pai'", $dbh );
		$tupla = mysql_fetch_array( $query_swiki );
		$indexador_atual_links=$tupla[ "indexador" ];

		$linksto_id_atua = array();
		$linksto_titulo_atual = array();
		if ( stristr( $ident_pai, "." ) ) {
			$i = 1;
		} else {
			$i = 2;
			$linksto_id_atua[ 1 ]= "0";
			$linksto_titulo_atua[ 1 ]= "Lista de Swikis";
		}
		$query_swiki =  mysql_query( "select ident,titulo from paginas where (((ident like '$id_swiki.%') or (ident='$id_swiki')) and (conteudo like '%<lnk>$indexador_atual_links</lnk>%'))", $dbh );
		while ( $tupla = mysql_fetch_array( $query_swiki ) ) {
			$linksto_id_atua[ $i ] = $tupla[ "ident" ];
			$linksto_titulo_atua[$i]=$tupla[ "titulo" ];
			$i++;
		}

		$path_xml = $PATH_XML;
		$arq_xsl = $PATH_XSL;
		$path_html = $PATH_XHTML;
		$dtd = "<!DOCTYPE coteia SYSTEM 'coteia.dtd'>";
		$node = "page";
		$tag_id = "id";
		$others = "<sw_id>$id_swiki</sw_id>";
		$kwd[1] = "kwd1";
		$kwd[2] = "kwd2";
		$kwd[3] = "kwd3";
		$aut = "aut";
		$tit = "tit";
		$body = "bdy";

		$query_extra = mysql_query("select id_ann,id_chat,id_eclass from swiki where id='$id_swiki'");
		$result = mysql_fetch_array( $query_extra );
		$annotation = "<ann_folder>" . $result["id_ann"] . "</ann_folder>";
		$chat = "<chat_folder>" . $result["id_chat"] . "</chat_folder>";
		$eclass = "<id_eclass>" . $result["id_eclass"] . "</id_eclass>";

		$feedback = xml_xsl($ident_pai,$conteudo,$titulo,$autor,$keyword,$arq_xsl,$path_html,$path_xml,$dtd,$node,$tag_id,$lock,$annotation,$chat,$eclass,$others,$linksto_id_atua,$linksto_titulo_atua,$kwd,$aut,$tit,$body);

		cvs_update( $ident_pai, $CVS_MODULE);

		if ( $feedback==0 ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
