Requisitos:
- Servidor Apache (com PHP e esse com suporte a MySQL).
- Cliente CVS
- Acesso a um servidor MySQL e CVS

Obtendo o c�digo via CVS;
 cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs login
 cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs checkout CoTeia

Obtendo o c�digo sem os metadados do CVS:
  cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs export -r HEAD CoTeia

Instala��o:
- Copie a CoTeia para um diret�rio disponibiliz�vel por seu servidor Apache e que possua o PHP ativado.
- Configure o PHP utilizado pelo Apache (arquivo "/etc/php.ini"), habilitando
a diretiva "register_globals".
- Modifique o arquivo "config.php.inc" da CoTeia.






- Execute o script "tables/tables.php"
- Execute o schema SQL gerado ("tables/tables.txt") no MySQL.



Copyright notices:

This software includes the software XML Parser from James Clark whose copyright
can be found at libs/copyright-xp.txt. More specific information about this software
can be found at http://www.jclark.com/xml/xp/index.html.

This software includes the software XML Parser from Bill Lindsey whose copyright
can be found at libs/copyright-xt.txt. More specific information about this software
can be found at http://www.blnz.com/xt/index.html.
