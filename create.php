<?

    include_once("function.inc");
    include_once("cvs/function_cvs.inc");

   if ((!isset($ident)) or (stristr($ident,";"))) 
   #evita ; para concatenacao de comandos SQL
   {
   $st = 1;
   include("erro.php");
   exit();
   }

   $dbh = db_connect();
                
   mysql_select_db($dbname,$dbh);
  
  if ($salva){

    if (stristr($ident,".")) {
	//encontra id_swiki
	$get_swiki = explode(".",$ident);
	$id_swiki = $get_swiki[0];
    }
    else $id_swiki = $ident;
   
   $query =  "select indexador from paginas where ((indexador='$indexador') and ((ident like '$id_swiki.%')  or (ident='$id_swiki')))";
   $result = mysql_query($query,$dbh);

   while ($tupla = mysql_fetch_array($result)) {
   	if (!strcmp(trim($indexador),trim($tupla[indexador])))
        {
	$st = 3;
	include("erro.php");
	exit();
        }//if
   }//while
  
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

		 //grava no BD sem modificacaoes de links
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
        } else $flag_lock = 0;
		
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
 
		//$query_back = "insert into backup (ident,indexador,titulo,conteudo,ip, data,pass, kwd1, kwd2, kwd3,autor) values ('$ident','$indexador','$titulo','$conteudo_puro','$nro_ip', '$data','$passwd','$keyword[1]','$keyword[2]','$keyword[3]','$autor')";
		//$sql_back = mysql_query("$query_back",$dbh);
		}
		else {
		$query = "insert into paginas (ident,indexador,titulo,conteudo,ip, data_criacao, data_ultversao ,pass, kwd1, kwd2, kwd3,autor) values ('$ident','$indexador','$titulo', '$conteudo_puro', '$nro_ip','$data','$data',NULL,'$keyword[1]','$keyword[2]','$keyword[3]','$autor')" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query("$query",$dbh);
 
		//$query_back = "insert into backup (ident,indexador,titulo,conteudo,ip, data,pass, kwd1, kwd2, kwd3,autor) values ('$ident','$indexador','$titulo','$conteudo_puro','$nro_ip','$data', NULL,'$keyword[1]','$keyword[2]','$keyword[3]','$autor')";
		//$sql_back = mysql_query("$query_back",$dbh);
		}

		$query = "insert into gets (id_pag,id_sw,data) values ('$ident','$id_swiki','$data')" or die ("Falha ao inserir no Banco de Dados");
		$sql = mysql_query("$query",$dbh);
		
		}
		else	{
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
<HTML>
<HEAD>
<TITLE> Formulario de Criação </TITLE>
<META http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\"/>
<META content=\"MSHTML 5.50.4134.600\" name=\"GENERATOR\"/>
<script language="javascript">
function validar() {
                
           // Verifica se o campo titulo foi preenchido
                
              if (document.create.titulo.value == "") {
                  alert('O campo título é de preenchimento obrigatório!')
                  document.create.titulo.value = ""
                  document.create.titulo.focus();
                  return false;
              }

           // Verifica se a textarea de conteudo foi preenchida
  
              if (document.create.cria_conteudo.value == "") {
                  alert('O campo de conteúdo é de preenchimento obrigatório!')
                  document.create.cria_conteudo.value = ""
                  document.create.cria_conteudo.focus();
                  return false;
              }

	      if (document.create.passwd.value != document.create.repasswd.value) {
              alert ('As senhas digitadas não coincidem!');
              document.create.passwd.focus();
              return false;
              }
}
</script>
</HEAD>
<BODY text=#000000 vLink=#0000cc aLink=#ffff00 link=#cc0000 bgColor=#ffffff>
		<IMG src="<?echo $URL_IMG?>/viewbw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/editbw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/historybw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/indicebw.png" border=0>
		<img src="<?echo $URL_IMG?>/mapbw.png" border="0"/>
		<IMG src="<?echo $URL_IMG?>/changesbw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/uploadbw.png" border=0>
		<IMG src="<?echo $URL_IMG?>/searchbw.png" border=0>
		<A href="help.php">
		<IMG src="<?echo $URL_IMG?>/helpbw.png" border=0></A>
		<IMG src="<?echo $URL_IMG?>/chatbw.png" border=0>
		<img src="<?echo $URL_IMG?>/notebw.png" border="0"/>
		<img src="<?echo $URL_IMG?>/printbw.png" border="0"/>
<br><br>
<FORM METHOD=POST ACTION="create.php" name="create" onSubmit="return validar();">
<table width="100%" border="0">
  <tr bgcolor="FFFFCC">
      <td><INPUT TYPE="checkbox" name="lock" value="locked"> - Lock<br><br></td>
  </tr>
  <tr bgcolor="FFFFCC">
      <td><INPUT TYPE="password" size="10" name="passwd" onBlur="window.document.create.lock.checked=true;return false;"> - Password<br><br></td>
  </tr>
  <tr bgcolor="FFFFCC">
      <td><INPUT TYPE="password" size="10" name="repasswd"> - Reenter Password</td>
  </tr>
  <tr><td>
                <font color="#000055" size="5" align="center">T&iacute;tulo
                <INPUT TYPE="text" NAME="titulo" VALUE="<?echo $index?>" SIZE="45"></font><br><br>   
        </td></tr>
 <tr><td>
		<font color="#000055" size="5" align="center">Autor
		<INPUT TYPE="text" NAME="cria_autor" SIZE="45"></font><br><br>
 </td></tr>
 <tr><td>
		<font color="#000055" size="5" align="center">Palavras Chave
		</td></tr><tr><td>
		<INPUT TYPE="text" NAME="key1" SIZE="15">
		<INPUT TYPE="text" NAME="key2" SIZE="15">
		<INPUT TYPE="text" NAME="key3" SIZE="15">
		</font><br><br>
  </td></tr>
  <tr>
 <td><font color="#000055" size="6" align="center">Conteúdo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="reset" value="Limpa" >&nbsp;&nbsp;&nbsp;&nbsp;<INPUT TYPE="submit" name="salva" value="Salva" ></td>
	</tr>
  <tr><td> 	
	<TEXTAREA NAME="cria_conteudo" wrap=virtual ROWS="20" COLS="100">Edite o conte&uacute;do aqui...</TEXTAREA></font><br><br>  
  </td></tr>
  </table>
<INPUT TYPE="hidden" name="ident" value="<?echo $ident?>"  >
<INPUT TYPE="hidden" name="indexador" value="<?echo $index?>"  >
</FORM>
</BODY>
</HTML>
<? 
}
?>
