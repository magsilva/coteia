<?php
  
include_once( "../function.inc" );

// Conexão ao sgbd.
$dbh = db_connect();
mysql_select_db( $dbname, $dbh);

// Criação do arquivo com o dump da base.
$arquivo = "script.sql";
$fp = fopen( $arquivo , "w+" );

// Adição do comando para selecionar a base de dados adequada.
fputs( $fp, "USE $dbname");

// Recupera todos os dados das wikipages.
$query = "SELECT * FROM paginas";
$result = mysql_query( $query, $dbh );

// Começa a geração do dump propriamente dito, alterando tupla por tupla e gravando em arquivo.
while ( $tupla = mysql_fetch_array( $result ) ) {
	$ident = $tupla[ "ident" ]; 
	$indexador = $tupla["indexador"];
	$titulo = $tupla["titulo"]; 
	$conteudo_puro = $tupla["conteudo"];
	$nro_ip = $tupla["ip"];
	$data = $tupla["data_criacao"];
	$data2 = $tupla["data_ultversao"];
	$passwd = $tupla["pass"];
	$keyword1 = $tupla["kwd1"];  
	$keyword2 = $tupla["kwd2"];
	$keyword3 = $rtupla["kwd3"];  
	$autor = $tupla["autor"];

	// Criptografa a senha da wikipage.
	$passwd = md5( $passwd );

	// Prepara o comando SQL do dump da tupla.
	$wikipage_dump = "insert into paginas (ident,indexador,titulo,conteudo,ip,data_criacao,data_ultversao,pass, kwd1, kwd2, kwd3,autor) values ('$ident','$indexador','$titulo','$conteudo_puro','$nro_ip','$data','$data2','$passwd','$keyword1','$keyword2','$keyword3','$autor');$final_linha";

	// Salva o comando em arquivo.
	fputs( $fp, $wikipage_dump );
}

// libera a memoria usada pela variavel $sql
mysql_free_result( $result );

?>
