<?
   include_once("function.inc");

   $sess = new coweb_session;

   $sess->read();
?>
<html>
<head>
<script LANGUAGE="JavaScript">
function ValidaForm() 
{
 
    if (document.formadmin.atualiza.value == 0) {
        alert('Selecione uma Swiki !');
        document.formadmin.atualiza.focus();
        return false;
    }   
    
  return true;
}    
</script>
<?
        include("header.php");
?>
<center>
<form method="post" action="atualiza_swiki.php" name="formadmin" onSubmit="return 
ValidaForm();">
<table border="1" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td valign="middle" colspan="2" class="table-header">Atualizar Swiki</td>
    </tr>
	<tr>
	<td valign="middle">Swiki:</td>
	<td>
        <select name="atualiza">
        <option value="0" selected>Escolha Swiki</option></font>
        <?
	    $dbh = db_connect();

		# seleciona base de dados
		mysql_select_db($dbname,$dbh);
      
        
        $sql = mysql_query("SELECT id,titulo FROM swiki order by titulo"); 
        
        while ($tupla = mysql_fetch_array($sql)){
	    $titulo = $tupla[titulo];
            $id_titulo = $tupla[id];
            echo "<option value=$id_titulo>$titulo</option>";
        }
        ?>
        </select></td></tr>
        <tr>
	<td valign="middle" colspan="2">
        <input type="submit" name="continua" value="Continua"></td>
        </tr>
	<tr>
        <td valign="middle" colspan="2" class="table-footer">
	<a href="main.php">Menu Principal</a></td>
        </tr>
	</table></form></center><br>
<?
        include("footer.php");
?>
