<HTML>
<HEAD>
<TITLE> Historico </TITLE>
<META http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/>
<META content=\"MSHTML 5.50.4134.600\" name=\"GENERATOR\"/>
<script language="JavaScript">
function AbreMapa(id)
	{
	window.open('map.php?id='+id,'janelamap','toolbar=no,directories=no,location=no,scrollbars=yes,menubar=no,status=no,resizable=yes,width=520,height=480');
	}
function AbreChat(swiki)
	{
	window.open('chat.php?swiki='+swiki,'janela_chat','toolbar=no,directories=no,location=no,scrollbars=yes,menubars=no,status=no,resizable=yes,width=700,height=500');
	}
function Imprime()
	{
	window.print();  
	}
</script> 
</HEAD>
<?
include_once("function.inc");
include_once("cvs/function_cvs.inc");

        //encontra id_swiki
        $id_swiki = $ident[0];
                         
        if ($ident[1]!="."){
                $id_swiki = $ident[0].$ident[1];
	}
?>  
<BODY text=#000000 vLink=#0000cc aLink=#ffff00 link=#cc0000 bgColor=#ffffff>
		<A href="mostra.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/view.png" border=0></A>
		<IMG src="<?echo $URL_IMG?>/editbw.png" border=0>
                <A href="history.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/history.png" border=0></A>
		<A href="mostra.php?ident=<?echo $id_swiki?>">
		<IMG src="<?echo $URL_IMG?>/indice.png" border=0></A>
		<A href="JavaScript:AbreMapa(<?echo $id_swiki?>)">
	        <img src="<?echo $URL_IMG?>/map.png" border="0"/></A>
		<A href="changes.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/changes.png" border=0></A>
                <A href="upload.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/upload.png" border=0></A>
		<A href="search.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/search.png" border=0></A>
		<A href="help.php">
		<IMG src="<?echo $URL_IMG?>/help.png" border=0></A>
		<A href="JavaScript:AbreChat(<?echo $id_swiki?>)">
		<img src="<?echo $URL_IMG?>/chat.png" border="0"/></A>
		<img src="<?echo $URL_IMG?>/notebw.png" border="0"/>
		<A href="JavaScript:Imprime()">
		<img src="<?echo $URL_IMG?>/print.png" border="0"/></A>
<br><br>
<?

//variaveis que devem vir de outro script
$ident = $HTTP_POST_VARS["ident"];
$comparar = $HTTP_POST_VARS["compara"];

//arquivo a ser feito checkout
$arquivo = $ident.".html";

//nro da revisao
$revisao = $HTTP_POST_VARS["revisao"];

$ModuloCheckout = "html/";

$username = "anonymous";

// checar se o usuario selecionou uma revisao correta ou se deixou na palavra "historico"

if ($revisao == 0) {

 echo '<H2>ERRO!</H2><BR><FONT FACE="ARIAL" SIZE="3"><CENTER>Por favor, selecione uma versao valida na pagina anterior.';
 echo "</CENTER>";

}

else {

// para adicionar um arquivo ao repositorio, deve-se primeiro fazer um checkout do local onde
// sera armazenado este arquivo. Para isso, eh utilizado um diretorio na maquina local. Podemos
// fazer uma pequena rotina pra verificar se o diretorio existe e para cria-lo automaticamente.
// Posteriormente ele deve ser excluido.

$caminho = cria_dir($parent);
$caminho .= "/"; 
chdir($caminho);

$caminho_checkout = $caminho.$ModuloCheckout;

//observacao: esses comandos tambem podem ser executados com popen
//coloca o arquivo salvo (ou do qual foi feito um upload) dentro do diretorio de trabalho

$comando_revisao = "cvs -d :pserver:".$username."@".$host.":".$caminho_repositorio." co -r ".$revisao." ".$ModuloCheckout.$arquivo;
exec($comando_revisao);

//se a opcao "Comparar com versao atual" estiver ativada, mostrar a versao atual,
//montando a tabela para exibir as duas lado a lado...

if ($compara == 1) {
?>

<TABLE BORDER = "0" WIDTH="100%" BGCOLOR="#FFFFFF" CELLSPACING="6" CELLPADDING="3">
<tr>
<TD WIDTH="50%" BGCOLOR="#EAEAEA" VALIGN="top">
<P><b>Vers&atilde;o Atual</b></P>

<?php
	$arquivo_original = $PATH_FILES.$arquivo;
	if ($fp_exibir = fopen($arquivo_original, "r")) {

		//descartar primeira parte do arquivo... primeira a coisa a pegar eh <h2>
		do {
		 $linha = fgets($fp_exibir,1024);
		} while (strncmp ($linha, "<h2>", 4) );

		do {
		//parsing: substituir todos os links de <a href=...>...</a> por
		//<font style="text-decoration: underline; color: blue;">...</font>

		$tmp .= $linha; //construir uma string gigante com todo o conteudo do arquivo

		$linha = fgets ($fp_exibir, 400);

		} while (!feof($fp_exibir) ); 

		//remover caracteres de quebra de linha e tabs da nova string

		$original = "'(\s)'";
		$conteudo = preg_replace ($original, "\\1", $tmp);
		$conteudo = eregi_replace("</body>","",$conteudo);
		$conteudo = eregi_replace("</html>","",$conteudo);

		$original = array ("'(<a [^>].*?>)'si",
				   "'</a>'si");
		$nova = array ('<font style="text-decoration: underline; color: blue;">',
			       "</font>");
		
		print preg_replace ($original, $nova, $conteudo);
		print "\n";
	}

	else print "erro ao abrir o arquivo original...";
?>
</TD>
<TD WIDTH="50%" BGCOLOR="#FFFFFF" VALIGN="top">

<?php
}


//Agora, o arquivo $ident.html jah se encontra no diretorio $caminho_checkout
//Fazer a exibicao na tela.

echo "<p><b>Vers&atilde;o $revisao</b></p>";

chdir($caminho_checkout);
if ($fp_exibir2 = fopen($caminho_checkout.$arquivo, "r")) {

	//descartar primeira parte do arquivo... primeira a coisa a pegar eh <h2>
	do {
	 $linha = fgets($fp_exibir2,1024);
	} while (strncmp ($linha, "<h2>", 4) );

	$tmp = "";

	do {
	//parsing: substituir todos os links de <a href=...>...</a> por
	//<font style="text-decoration: underline; color: blue;">...</font>

	$tmp .= $linha; //construir uma string gigante com todo o conteudo do arquivo

	$linha = fgets ($fp_exibir2, 400);

	} while (!feof($fp_exibir2) ); 

	//remover caracteres de quebra de linha e tabs da nova string

	$original = "'(\s)'";
	$conteudo = preg_replace ($original, "\\1", $tmp);
	$conteudo = eregi_replace("</body>","",$conteudo);
	$conteudo = eregi_replace("</html>","",$conteudo);

	$original = array ("'(<a [^>].*?>)'si",
	                   "'</a>'si");
	$nova = array ('<font style="text-decoration: underline; color: blue;">',
	               "</font>");
        
	print preg_replace ($original, $nova, $conteudo);
	print "\n";

}
else print "erro ao abrir o arquivo";

//se imprimiu o arquivo original tambem, eh necessario finalizar a tabela.

fclose($fp_exibir2);

if ($compara == 1) {

?>
</td>
</tr>
</table>
<?php
fclose($fp_exibir);
}

apaga_dir($caminho);

} //fim else (trat. de erro, sem revisao selecionada)
?>

</body>
</html>

