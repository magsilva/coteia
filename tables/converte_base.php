<?php
  
include_once( "../function.inc" );

// Conexão ao sgbd.
$dbh = coteia_connect();

// Recupera todos os dados das wikipages.
$query = "SELECT ident,pass FROM paginas";
$result = mysql_query( $query, $dbh );

// Começa a geração do dump propriamente dito, alterando tupla por tupla e gravando em arquivo.
while ( $tupla = mysql_fetch_array( $result ) ) {
	$ident = $tupla[ "ident" ]; 
	$passwd = $tupla[ "pass" ];

	echo "\n$ident...";

	// Criptografa a senha da wikipage.
	$passwd = md5( $passwd );

	// Prepara o comando SQL do dump da tupla.
	$wikipage_update = "update paginas set pass='$passwd' where ident='$ident'";

	// Atualiza a senha da wikipage.
	$sql = mysql_query( $wikipage_update, $dbh ) or die ("Falha ao inserir no Banco de Dados");
	echo "Ok";
}

// libera a memoria usada pela variavel $sql
mysql_free_result( $result );

?>
