<?php

set_magic_quotes_runtime(0);
  
include_once( dirname(__FILE__) . '../../function.php.inc" );

function unhtmlentities( $string )
{
   $trans_tbl = get_html_translation_table( HTML_ENTITIES );
   $trans_tbl = array_flip( $trans_tbl );
   return strtr( $string, $trans_tbl );
}


function prepare_content_callback( $text, $element )
{
	$pattern = "/<br\s*\/?>/i";
	$text = preg_replace( $pattern, "", $text );

	//pre-formatado: nao faz parsing e tags sao mostradas na tela
	if ( $element === "pre" ) {
		$text = eregi_replace( "<", "&lt;", $text );
		$text = eregi_replace( ">", "&gt;", $text );
	}
	
	return $text;
}

function prepare_content( $text, $element )
{
	$pattern = "/(<$element\s*.*>)(.*)(<\/$element\s*>)/iUes";
	$result = preg_replace( $pattern, "'\\1' . prepare_content_callback('\\2', \$element) . '\\3'", $text );

	return stripslashes( $result );
}

function br2nl( $text )
{
	$elements = array();
	$elements[] = "table";
	$elements[] = "pre";
	$elements[] = "ul";
	$elements[] = "ol";
	
	foreach ( $elements as $element ) {
		$text = prepare_content( $text, $element );
	}

	return $text;
}

// Conex�o ao sgbd.
$dbh = db_connect();

// Recupera todos os dados das wikipages.
$query = "SELECT ident,pass,indexador,titulo,kwd1,kwd2,kwd3,autor,conteudo FROM paginas order by ident";
# $query = "SELECT ident,pass,indexador,titulo,kwd1,kwd2,kwd3,autor,conteudo FROM paginas where ident='59'";
$result = mysql_query( $query, $dbh );


// Come�a a gera��o do dump propriamente dito, alterando tupla por tupla e gravando em arquivo.
while ( $tupla = mysql_fetch_array( $result ) ) {

	$ident = $tupla[ "ident" ]; 

	// Convert password to new format (MD5)
	$passwd = $tupla[ "pass" ];
	if ( $passwd != NULL ) {
		// Criptografa a senha da wikipage.
        	$passwd = md5( $passwd );
	}

	// Revert the mess the old CoTeia does
	$indexador = unhtmlentities( $tupla[ "indexador" ] );
	$titulo = unhtmlentities( $tupla[ "titulo" ] );
	$kwd1 = unhtmlentities( $tupla[ "kwd1" ] );
	$kwd2 = unhtmlentities( $tupla[ "kwd2" ] );
	$kwd3 = unhtmlentities( $tupla[ "kwd3" ] );
	$autor = unhtmlentities( $tupla[ "autor" ] );
	
	$conteudo = unhtmlentities( $tupla[ "conteudo" ] );
	$conteudo = unhtmlentities( $conteudo );

	$conteudo = br2nl( $conteudo );

	// Prepare the data to insert into MySQL
	$indexador = mysql_escape_string( $indexador );
	$titulo = mysql_escape_string( $titulo );
	$kwd1 = mysql_escape_string( $kwd1 );
	$kwd2 = mysql_escape_string( $kwd2 );
	$kwd3 = mysql_escape_string( $kwd3 );
	$autor = mysql_escape_string( $autor );
	$conteudo = mysql_escape_string( $conteudo );

	echo "\n$ident...";
	// Prepara o comando SQL do dump da tupla.
	if ( $passwd == NULL ) {
		$wikipage_update = "update paginas set pass='NULL',indexador='$indexador',titulo='$titulo',kwd1='$kwd1',kwd2='$kwd2',kwd3='$kwd3',autor='$autor',conteudo='$conteudo' where ident='$ident'";
	} else {
		$wikipage_update = "update paginas set pass='$passwd',indexador='$indexador',titulo='$titulo',kwd1='$kwd1',kwd2='$kwd2',kwd3='$kwd3',autor='$autor',conteudo='$conteudo' where ident='$ident'";
	}

	// Atualiza a senha da wikipage.
 	$sql = mysql_query( $wikipage_update, $dbh ) or die ("Falha ao inserir no Banco de Dados");
	echo "Ok";
}

// libera a memoria usada pela variavel $sql
mysql_free_result( $result );

// The end.
echo "Conversion finished. No error found meanwhile, gambate!";

?>
