Requisitos:
- Servidor Apache (com PHP e esse com suporte a MySQL).
- Cliente CVS
- Acesso a um servidor MySQL e CVS.

Obtendo o código via CVS;
 cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs login
 cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs checkout CoTeia

Obtendo o código sem os metadados do CVS:
  cvs -d :pserver:anonymous@magsilva.dynalias.net:/var/lib/cvs export -r HEAD CoTeia

Instalação:
- Copie a CoTeia para um diretório disponibilizável por seu servidor Apache e que possua o PHP ativado.
- Configure o PHP utilizado pelo Apache (arquivo "/etc/php.ini"), habilitando
a diretiva "register_globals".
- Modifique o arquivo "config.php.inc" da CoTeia, configurando as variáveis aplicáveis. Existe um exemplo em "doc/config.php.inc.eg".
- Execute o script "tables/setup.php".
- Execute os esquemas SQL gerados no diretório "tables/" (arquivos com extensão ".sql"). Observe que alguns dos banco de dados utilizados, em especial o de base de usuários compartilhada e cursos do eClass, podem estar disponibilizados na rede. Nesse caso, não é necessário executar os scripts para criar as respectivas bases de dados.
- Tenha certeza de que existe um módulo no repositório CVS conforme especificado no "config.php.inc". O mesmo vale para o usuário, sua senha e permissões.


Copyright:
Este software inclui o software XML Parser de James Clark cujo copyright pode ser encontrado em libs/copyright-xp.txt. Mais informações sobre esse software podem ser encontradas em http://www.jclark.com/xml/xp/index.html.

Este software inclui o software XML Parser de Bill Lindsey cujo copyright pode ser encontrado em libs/copyright-xt.txt. Mais informações sobre esse software podem ser encontradas em http://www.blnz.com/xt/index.html.
