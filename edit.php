<?
/*
* Edit.php
*
* Funcionalidade: Edicao de paginas ja criadas.
*
*/

   if ((!isset($ident)) or (stristr($ident,";"))) 
   #evita ; para concatenacao de comandos SQL
   {
   $st = 1;
   include("erro.php");
   exit();
   }
  
   include_once("function.inc");
   include_once("cvs/function_cvs.inc");

   $dbh = db_connect();
                 
   mysql_select_db($dbname,$dbh);

   $query = "select ident,pass FROM paginas where ident='$ident'";
   $sql = mysql_query("$query",$dbh);

   if (mysql_num_rows($sql) == '0') {
		$st = 1;
		include("erro.php");
		exit();
    }

    
  if ($salva){

	while ($tupla = mysql_fetch_array($sql)) {
		$senha = $tupla[pass];
    }
                        
    if (($senha) && ($passwd))  {
		if ((strcasecmp($senha,$passwd)) != "0") {
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

		//grava no BD sem modificacaoes de links
                $conteudo_puro=$conteudo;

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

		//encontra id_swiki
		$get_swiki = explode(".",$ident);
		$id_swiki = $get_swiki[0];  		
		
		//encontra indexador da pagina - utilizado no linksto
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

		//verifica travamento da pagina
                if ($lock == locked) {
			if (($senha) || ((!$senha) && ($passwd != ''))) {
			$flag_lock = 1;
			} else {
			$flag_lock = 0;
			}
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

		$query_extra = mysql_query("select id_ann,id_chat,id_eclass from swiki where id=\"$id_swiki\"");
        	$result = mysql_fetch_array($query_extra);
        	$annotation = "<ann_folder>$result[id_ann]</ann_folder>";
        	$chat = "<chat_folder>$result[id_chat]</chat_folder>";
		$eclass = "<id_eclass>$result[id_eclass]</id_eclass>";

if (xml_xsl($ident,$conteudo,$titulo,$autor,$keyword,$arq_xsl,$cp_xt,$cp_java,$path_html,$path_xml,$dtd,$node,$id,$lock_xml,$annotation,$chat,$eclass,$others,$linksto_id,$linksto_titulo,$kwd,$aut,$tit,$body)==TRUE) {

		//atualiza arquivo no CVS
                update_cvs($ident,"html/");

		$nro_ip= getenv("REMOTE_ADDR"); 
		$d = getdate();
		$data=$d["year"]."-".$d["mon"]."-".$d["mday"]." ".$d["hours"].":".$d["minutes"].":".$d["seconds"];

		//verifica travamento da pagina
                if ($flag_lock == 1) {
 		$query = "update paginas SET conteudo='$conteudo_puro',titulo='$titulo',kwd1='$keyword[1]',kwd2='$keyword[2]', kwd3='$keyword[3]',autor='$autor',data_ultversao='$data',pass='$passwd' where ident='$ident'" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query("$query",$dbh);

		} else {
 		$query = "update paginas SET conteudo='$conteudo_puro',titulo='$titulo',kwd1='$keyword[1]',kwd2='$keyword[2]', kwd3='$keyword[3]',autor='$autor',data_ultversao='$data',pass=NULL where ident='$ident'" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query("$query",$dbh);

		}	
		  } //xml_xsl
		  else	{
				//nao criou arquivo fisico >> erro 
				$st = 2;
				include("erro.php");
				exit();
		} //xml_xsl

header("Location:mostra.php?ident=$ident");
        
} else {
?>
<HTML>
<?

   $query = "SELECT titulo,conteudo,kwd1,kwd2,kwd3,autor,pass FROM paginas where ident='$ident'";
   $sql = mysql_query("$query",$dbh);
  		while ($tupla = mysql_fetch_array($sql)){
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
<HEAD>
<META http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/>
<META content=\"MSHTML 5.50.4134.600\" name=\"GENERATOR\"/>
<TITLE> Formulário de Edição </TITLE>
<script language="javascript">
function Imprime()
{
	window.print();  
}
function validar() {

           // Verifica se o campo titulo foi preenchido

              if (document.edit.titulo.value == "") {
                  alert('O campo título é de preenchimento obrigatório!')
                  document.edit.titulo.value = ""
                  document.edit.titulo.focus();
                  return false;
              }

           // Verifica se a textarea de conteudo foi preenchida

              if (document.edit.cria_conteudo.value == "") {
                  alert('O campo de conteúdo é de preenchimento obrigatório!')
                  document.edit.cria_conteudo.value = ""
                  document.edit.cria_conteudo.focus();
                  return false;
              }

<? 
	//Verifica se o campo password foi preenchido qdo existir senha

		if ($senha) echo "if (document.edit.passwd.value == \"\") {
		alert('O campo de password é de preenchimento obrigatório!')
		document.edit.passwd.value = \"\"
		document.edit.passwd.focus();
		return false;
		}"; else echo "if (document.edit.passwd.value != document.edit.repasswd.value) {	
	      alert ('As senhas digitadas não coincidem!');
              document.edit.passwd.focus();
	      return false; 
	      }";
?>
}
</script>
</HEAD>
<BODY text=#000000 vLink=#0000cc aLink=#ffff00 link=#cc0000 bgColor=#ffffff>
		<A href="mostra.php?ident=<?echo $ident?>">
		<IMG src="<?echo $URL_IMG?>/view.png" border=0></A>
		<IMG src="<?echo $URL_IMG?>/editbw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/historybw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/indicebw.png" border=0>
		<img src="<?echo $URL_IMG?>/mapbw.png" border="0"/>
		<IMG src="<?echo $URL_IMG?>/changesbw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/uploadbw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/searchbw.png" border=0>
		<A href="help.php">
		<IMG src="<?echo $URL_IMG?>/help.png" border=0></A>
		<IMG src="<?echo $URL_IMG?>/chatbw.png" border=0>
		<img src="<?echo $URL_IMG?>/notebw.png" border="0"/>
		<A href="JavaScript:Imprime()">
		<img src="<?echo $URL_IMG?>/print.png" border="0"/></A>
<br><br>
<FORM method="POST" name="edit" ACTION="edit.php" onSubmit="return validar();">
<table width="760" border="0">
  <tr bgcolor="FFFFCC">
      <td><INPUT TYPE="checkbox" name="lock" value="locked" <?if ($senha) echo CHECKED;?>> - <b>Lock</b></td>
  </tr>
  <tr bgcolor="FFFFCC">
      <td><INPUT TYPE="password" size="10" name="passwd" onBlur="window.document.edit.lock.checked=true;return false;"> - Password</td>
  </tr>
  <?if (!$senha)  echo "<tr bgcolor=\"FFFFCC\"><td><INPUT TYPE=\"password\" size=\"10\" name=\"repasswd\"> - Reenter Password</td></tr>"
  ?>
  <tr><td>
                <font color="#000055" size="5" align="center">T&iacute;tulo
                <INPUT TYPE="text" NAME="titulo" VALUE="<?echo $tit ?>" SIZE="45"></font><br><br>   
        </td></tr>
  <tr> 
	  <td>
		<font color="#000055" size="5" align="center">Autor
		<INPUT TYPE="text" NAME="cria_autor" VALUE="<?echo $autor?>" SIZE="45"></font><br><br>
	</td></tr>
   <tr><td>
		<font color="#000055" size="5" align="center">Palavras Chave
		</td></tr><tr><td>
		<INPUT TYPE="text" NAME="key1" VALUE="<?echo $kwd1?>" SIZE="15">
		<INPUT TYPE="text" NAME="key2" VALUE="<?echo $kwd2?>" SIZE="15">
		<INPUT TYPE="text" NAME="key3" VALUE="<?echo $kwd3?>" SIZE="15">
		</font><br><br>
	</td></tr>
  <tr>
    <td><font color="#000055" size="6" align="center">Conteúdo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="reset" value="Limpa" >&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="submit" name="salva" value="Salva" ></td>
  </tr>
  <tr><td> 	
	<TEXTAREA  NAME="cria_conteudo" type="text" WRAP="virtual" ROWS="20" COLS="100"><?echo $cont?></TEXTAREA></font>
	<br><br>  
  </td></tr>
 </table>
<INPUT TYPE="hidden" name="ident" value="<?echo $ident?>"  >
</FORM>
</BODY>
</HTML>
<?
}

?>
