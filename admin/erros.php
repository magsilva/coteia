<?
   include("function.inc");

   $sess = new session;

   $sess->read();

   if (isset($par) && $par=="1") {
	$fp = @fopen("../log.txt","w"); 
	@fputs($fp,"");
	@fclose ($fp);
   }
?>
<html>
<head>
<script language="JavaScript">
function deluser(url){
     
  res=window.confirm("Deseja realmente limpar o arquivo de log ?");
  if(res){
    window.location.replace(url);
  }
}
</script>
<?
        include("header.php");
?>
<center>
<table border="1" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td valign="middle" class="table-header">Log de Erros</td>
    </tr>
<?
	$msgs = 0;
	if( file_exists('../log.txt')) {
		$fd = @fopen ("../log.txt", "r");
		while (!feof ($fd)) {
    			$buffer = @fgets($fd, 4096);
			if ($buffer != "") {
				echo "<tr><td valign=middle>";
				$partes = explode("|", $buffer);
    				echo $partes[0]." | ";
    				echo $partes[1]." | ";
	    			echo $partes[3]." | ";
    				echo $partes[4];
				$msgs = 1;
				echo "</td></tr>";
			}
		}
	}
	@fclose ($fd);

	if (($msgs == 1) && ($sess_val == "admin")){
		echo "<tr>
        <td valing=\"middle\" class=\"left-nobold\">
		<img src=\"../imagem/checked.png\">&nbsp;&nbsp;&nbsp;
	    <a href=javascript:deluser('erros.php?par=1');>Limpar Arquivo</a></td>
        </tr>";
	} 
	if ($msgs == 0) echo "<tr><td valing=\"middle\">O arquivo de <i>log</i> est&aacute; vazio !</td></tr>";
?>
        <tr>
        <td valign="middle" class="table-footer">
        <a href="main.php">Menu Principal</a></td>
        </tr>
        </table></center><br>
<?
        include("footer.php");
?> 
