<?php

include_once( "config.php.inc" );

/*
 * NOME:		db_connect
 * DESCRICAO:		Realiza a conexao com o DBMS MySQL
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Retorna o handler da base de dados
 * OBSERVACOES:		
 */
function db_connect(){

	global $dbhost, $dbuser, $dbpass;

	return(mysql_connect($dbhost, $dbuser, $dbpass));

}

/*
 * NOME:		autentica
 * DESCRICAO:		Autentica administrador e cria variaveis de sessao
 * PAR. ENTRADA:	$usuario - usuario & $passwd - senha & $dbh - handler do banco
 * PAR. SAIDA:		--
 * RETORNO:		TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:		--
 */
function autentica($usuario,$senha,$dbh){
   
      global $dbname;

      # seleciona base de dados
      mysql_select_db($dbname,$dbh);

      $query = mysql_query("select login,email from admin where login='$usuario' AND pass= PASSWORD('$senha')");
      # verifica se o usuário é valido
      if (mysql_num_rows($query) > 0) {
 
            $result  = mysql_fetch_array($query); 
            $usuario = $result["login"];
            $usermail = $result["email"];
 
            session_start(); //Inicializa a sessão
            session_register("usuario","usermail"); //Registra as variáveis na sessão
 
            return true;
     }
     else return false;

}               

/*
 * NOME:		login_swiki
 * DESCRICAO:		
 * PAR. ENTRADA:	$usuario - usuario & $passwd - senha & $dbh - handler do banco
 * PAR. SAIDA:		--
 * RETORNO:		TRUE em caso de sucesso, FALSE em caso de erro
 * OBSERVACOES:		--
 */
function login_swiki($usuario,$passwd,$id,$dbh) {

	global $dbname;

	// SQL que verifica se o usuário está cadastrado
	$sql  = "SELECT id,username FROM swiki WHERE id='$id' AND username='$usuario' AND password=md5('$passwd')";
	$select_db = mysql_select_db($dbname,$dbh);
	$query = mysql_query($sql);

	// Verifica se o usuário é valido
	if (mysql_num_rows($query) > 0) {
		$result  = mysql_fetch_array($query);
        $coduser = $result["id"];
        $namuser = $result["username"];
                
        session_start("login"); //Inicializa a sessão
        session_name();
        session_destroy();
        session_register("namuser","coduser"); //Registra as variáveis na sessão
                
		return true;
	} else {
		return false;
	} 
}

/*
 * NOME:		verifica_session
 * DESCRICAO:		Verifica se as variaveis de sessao estao ativas
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		Continua em caso de sucesso, Msg de erro e saida do script em caso de erro
 * OBSERVACOES:		
 */
function verifica_session() {
	session_start();

	if (!(session_is_registered("usuario") AND session_is_registered("usermail"))) {
		header("Location:$URL_COWEB/acessoilegal.php"); //Redireciona para interface de acesso ilegal
		exit;
	}
}

/*
 * NOME:		logout
 * DESCRICAO:		destroi variaveis de sessao
 * PAR. ENTRADA:	--
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
 */
function logout() {

session_start();
session_unregister("usuario");
session_unregister("usermail");

}

/*
 * NOME:		replace
 * DESCRICAO:	Converte tags para minusculo(XHTML) e converte caracteres especias em entidades HTML.
 * PAR. ENTRADA: texto
 * PAR. SAIDA: texto traduzido em entidades e XHTML
 */
function replace_html_entities( $text ) {
// TODO: Alterar apenas os cdata, não as tags.
	$replace_vars = array(
//		"&" => "&amp;",
//		"`" => "'",
//		"\"" => "&quot;",
//		"<" => "&lt;",
//		">" => "&gt;"
	);


	foreach ( $replace_vars as $i => $value ) {
		$text = eregi_replace( $i, $value, $text );
	}
	return $text;
}

/**
* Prepare the data for database's processing.
*/
function prepare_for_db( $index, $content, $title, $author, $keyword ) {
	$data = prepare_for_web( $index, $content, $title, $author, $keyword );


	for ( $i = 0; $i < count( $keyword ); $i++ ) {
		$data[ "keyword" . $i ] = mysql_escape_string( $data[ "keyword" . $i ] );
	}
	$data[ "content" ] = mysql_escape_string( $data[ "content" ] );
	$data[ "title" ] = mysql_escape_string( $data[ "title" ] );
	$data[ "author" ] = mysql_escape_string( $data[ "author" ] );
	
	return $data;
}

/**
* Prepare the data for web.
*/
function prepare_for_web( $index, $content, $title, $author, $keyword ) {
	// Strip slashes if magic_quotes_gpc (auto adding slashes) is active.
	if ( get_magic_quotes_gpc() == 1 ) {
		$index = stripslashes( $index );
		$content = stripslashes( $content );
		$title = stripslashes( $title );
		$author = stripslashes( $author );
		for ( $i = 0; $i < count( $keyword ); $i++ ) {
			$keyword[ $i ] = stripslashes( $keyword[ $i ] );
		}
	}

	$data[ "index" ] = replace_html_entities( trim( $index ) );
	$data[ "title" ] = replace_html_entities( trim( $title ) );
	$data[ "author" ] = replace_html_entities( trim( $author ) );
	$data[ "content" ] = replace_html_entities( trim( $content ) );
	for ( $i = 0; $i < count( $keyword ); $i++ ) {
		$data[ "keyword" . $i ] = replace_html_entities( trim( $keyword[ $i ] ) );
	}

	return $data;
}


/*
 * NOME:		arq
 * DESCRICAO:		aplicacao XML/XSL(via XT) e manipulacao de arquivos.
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:         --
*/
function arq( $id, $xml_document, $path_xml, $path_xsl, $path_html )
{
	global $PATH_JAVA, $PATH_XT;

	$arq_xml = "$path_xml/$id.xml";
	if ( is_file( $arq_xml ) ) {
		chmod( $arq_xml, 0700 );
	}
	$fp = fopen( $arq_xml , "w+" );
	fputs( $fp, $xml_document );
	fclose( $fp );

	// Uso do XT = [path_java] -classpath [classpath_xt] com.jclark.xsl.sax.Driver [xml] [xsl] [html]
	$cmd_line = "$PATH_JAVA -cp \"$PATH_XT\" -Dcom.jclark.xsl.sax.parser=com.jclark.xml.sax.CommentDriver  com.jclark.xsl.sax.Driver $arq_xml $path_xsl $path_html/$id.html 2>&1";
	exec( $cmd_line, $var_array, $status );
	if ( empty( $var_array ) ) {
		return true;
	} else {
		return $var_array;
	}
}

/*
 * NOME:		xml_xsl
 * DESCRICAO:		--
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
*/
function xml_xsl( $wikipage, $path_xml, $path_dtd, $path_xsl, $path_html )
{
	$xml_document .=  <<<END
<?xml version="1.0" encoding="ISO-8859-1"?>
<!DOCTYPE coteia SYSTEM "coteia.dtd">
<page>
<id>{$wikipage[ "ident" ]}</id>
<lock>{$wikipage[ "lock" ]}</lock>
<ann_folder>{$wikipage[ "annotation" ]}</ann_folder>
<chat_folder>{$wikipage[ "chat" ]}</chat_folder>
<id_eclass>{$wikipage[ "eclass" ]}</id_eclass>
<sw_id>{$wikipage[ "id_swiki" ]}</sw_id>"
{$wikipage_web[ "links" ]}
<keyword1>{$wikipage[ "keyword1" ]}</keyword1>
<keyword2>{$wikipage[ "keyword2" ]}</keyword2>
<keyword3>{$wikipage[ "keyword3" ]}</keyword3>
<author>{$wikipage[ "author" ]}</author>
<title>{$wikipage[ "title" ]}</title>
<content>{$wikipage[ "content" ]}</content>
</page>
END;

	$feedback = arq( $wikipage[ "ident" ], $xml_document, $path_xml, $path_xsl, $path_html );

	return $feedback;
}

/*
 * NOME:		img_upload
 * DESCRICAO:		--
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
*/
function img_upload($conteudo) {

$continua=true;
$texto = " ".$conteudo;
$n=0;

  while ($continua == true)
        {
        $texto_pos_ini = strpos($texto,"<upl");
	if (!$texto_pos_ini)
                {
                $continua=false;
                } else
                        {
                        $texto_pos_fin = strpos($texto,"</upl>");
                        $texto_final = $texto_pos_fin + 6;
			$tam_upl = $texto_final - $texto_pos_ini;
			$texto_aux[$n] = substr($texto,0,$texto_pos_ini);
			$upload[$n] = substr($texto,$texto_pos_ini,$tam_upl);
			$texto = substr($texto,$texto_final);

	if (stristr($upload[$n],".pdf")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"1\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".htm")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"2\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".doc")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"3\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".ppt")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"4\"",$upload[$n]);
	}
	else if (stristr($upload[$n],".zip")) {
		$upload[$n] = eregi_replace("<upl","<upl id=\"5\"",$upload[$n]);
	}
	else {
		$upload[$n] = eregi_replace("<upl","<upl id=\"6\"",$upload[$n]);
	}
		$conteudo_final = $conteudo_final.$texto_aux[$n].$upload[$n];
		$n++;
		}//else
    }//while

        $conteudo_final = trim($conteudo_final.$texto);
        return $conteudo_final;

}

/*
 * NOME:		link_interno
 * DESCRICAO:	Algoritmo para criação de ligações para as páginas filhas (child).
 * Se id=$ident.x & titulo=tupla[titulo] entao linkar /c.c. [create].
 * PAR. ENTRADA:        --
 * PAR. SAIDA:		--
 * RETORNO:		--
 * OBSERVACOES:
*/
function link_interno( $id, $conteudo, $dbh ) {
	$n = "0";
	$continua = true;
	$texto = "" . $conteudo;
	$open_tag = "<lnk>";
	$close_tag = "</lnk>";
                        
	while ( $continua == true ) {
		$texto_pos_ini = strpos( $texto, $open_tag );
		if ( !$texto_pos_ini ) {
			$continua = false;
		} else {
			$texto_pos_fin = strpos( $texto, $close_tag );
			$texto_ini = $texto_pos_ini + strlen( $open_tag) + 1;
			$texto_tam = $texto_pos_fin - $texto_ini;
			$partes_texto[ $n ] = substr( $texto, $texto_ini, $texto_tam ); //proprio link
			$ini_resto_texto = $texto_pos_fin + strlen( $close_tag ) + 1;
			$texto = substr( $texto, $ini_resto_texto );

			//encontra id_swiki
			$get_swiki = explode( ".", $id );
			$swiki = $get_swiki[ 0 ];

			//encontra paginas dentro de uma mesma swiki
			$query_comp = "select indexador,ident from paginas  where ((ident like '$swiki.%')  or (ident='$swiki'))";
			$result_comp = mysql_query( $query_comp, $dbh );
			while ( $tupla = mysql_fetch_array( $result_comp ) ) {
				//compara link do textarea com uma pagina do banco de dados
				if ( !strcmp( trim( $partes_texto[ $n ] ), $tupla[ "indexador" ] ) ) {
					// Procura por identificador da pagina encontrada
					$query_find =  "select ident,data_ultversao from paginas where ((indexador='" . $tupla[ "indexador" ] . "') and ((ident like '$swiki.%')  or (ident='$swiki')))";
					$result_find = mysql_query( $query_find, $dbh );
					$tupla_find = mysql_fetch_array( $result_find );
					$pagina = $tupla_find[ "ident" ];

					// Acerta o formato da data
					$datetime = explode( " ", $tupla_find[ "data_ultversao" ] );
					$date = explode( "-", $datetime[ 0 ] );
					$data_formato_correto = $date[2] . "-" . $date[1] . "-" . $date[0] . " " . $datetime[1];

					// Já foi encontrada a tupla, pode sair direto do while.
					break;
				}
			}

			// Encontra a posicao correta na variavel, mesmo depois de inserir a linkagem.
			$texto_pos_inicio = strpos( $conteudo, $open_tag . $partes_texto[ $n ] . $close_tag );
			$texto_inicio = $texto_pos_inicio + strlen( $open_tag) + 1;
			$texto_pos_final = $texto_inicio + $texto_tam;

			if ( $tupla != false ) {
				$cont1 = substr_replace( $conteudo, "</a>", $texto_pos_final, 0 );
				$cont2 = substr_replace( $cont1, '<a href="mostra.php?ident=' . $pagina . ' onMouseOver="window.status=\'Última versão: ' . $data_formato_correto . '\'; return true">', $texto_inicio, 0 );
				$texto_final = $cont2;
			} else {
				$num = 1;

				// Procura por novo indice
				$query_search = "select count(*) as CONTADOR from paginas where ((ident like '$id.%') and (ident not like '$id.%.%')) ORDER by ident asc";
				$result_search = mysql_query( $query_search, $dbh );
				$tupla_cont = mysql_fetch_array( $result_search );
				$nro_pages = $tupla_cont[ "CONTADOR" ];

				$num = $nro_pages + 1;
				//indice de novas paginas
				$partes_texto[ $n ] = rawurlencode( $partes_texto[ $n ] );
				$cont1 = substr_replace( $conteudo, "[create]</a>", $texto_pos_final, 0 );
				$cont2 = substr_replace( $cont1, '<a href="create.php?ident=' . $id . '.' . $num . '&index=' . $partes_texto[$n] . '">', $texto_inicio + $texto_tam, 0 );
				$texto_final = $cont2;
			}
			$n++; //incrementa a variavel de links
			$conteudo = $texto_final;
		}
	}
	return trim( $texto_final );
}


/**
* Extract the swiki identificator from a full wikipage identificator.
*/
function extract_parent_wikipage( $ident ) {
	if ( ! check_wikipage_id( $ident ) ) {
		return false;
	}

	$childest_id = strrpos( $ident, "." );
	$parent_id = $ident; // This holds true if the wikipage is the main swiki's page.
	if ( $childest_id != false ) {
		$parent_id = substr( $ident, 0, $childest_id );
  }

	return $parent_id;
}

/**
* Extract the swiki identificator from a full wikipage identificator.
*/
function extract_swiki_id( $ident ) {
	if ( ! check_wikipage_id( $ident ) ) {
		return false;
	}

	if ( strpos( $ident, "." ) ) {
		$get_swiki = explode( ".", $ident );
    $id_swiki = $get_swiki[ 0 ];
  } else {
    $id_swiki = $ident;
  }

	return $id_swiki;
} 

/**
* Check if a wikipage identificator is valid.
*
* id ::= [0-9]+ ( "." [0-9] )*
*/
function check_wikipage_id( $ident ) {
	return ( preg_match( "/^[\d]+(\.([\d]+))*\$/", $ident ) > 0 ) ? true : false;
}

/**
* Check if a string matches the start tag for the given element.
*/
function match_start_tag( $content, $type ) {
	return ( preg_match( "/.*<$type\s.*>.*/", $content ) > 0 ) ? true : false;
}

/**
* Check if a string matches the end tag for the given element type.
*/
function match_end_tag( $content, $type ) {
	return ( preg_match( "/.*<\/$type\s*>.*/", $content ) > 0 ) ? true : false;
}

/**
* Check if a string matches the tag for the given empty element type.
*/
function match_empty_tag( $content, $type ) {
	return ( preg_match( "/.*<$type\s*\/>.*/", $content ) > 0 ) ? true : false;
}

/**
* Check if the content holds a element type with the specified character data.
*/
function match_tag( $content, $type, $cdata ) {
	return ( preg_match( "/.*<$type\s.*>\s*$cdata\s*<\/$type\s*>.*/", $content ) > 0 ) ? true : false;
}

/**
* Handle errors found.
*/
function show_error( $st, $message ) {
  include( "err.inc" );
}

?>
