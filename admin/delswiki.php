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
 
    if (document.formadmin.remove.value == 0) {
        alert('Selecione uma Swiki para ser removida !');
        document.formadmin.remove.focus();
        return false;
    }   
    
  return true;
}    
</script>
<?
        include("header.php");
?>
<center>
<form method="post" action="function.php" name="formadmin" onSubmit="return ValidaForm();">
<table border="1" cellspacing="0" cellpadding="5" class="box-table">
    <tr>
    <td valign="middle" colspan="2" class="table-header">Remover Swiki</td>
    </tr>
	<tr>
	<td valign="middle">Swiki:</td>
	<td>
        <select name="remove">
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
        <input type="submit" name="continua" value="Submit"></td>
        </tr>
	<tr>
        <td valign="middle" colspan="2" class="table-footer">
	<a href="main.php">Menu Principal</a></td>
        </tr>
	<input type="hidden" name="parametro" value="2"> 
	</table></form></center><br>
<?
        include("footer.php");
?>
