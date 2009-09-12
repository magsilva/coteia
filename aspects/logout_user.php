<?aophp filename="log.aophp" debug="false"

require_once( 'user.inc.php' );


user_logout();


// Redirecionar para login
Header("Location: index.php");

?>
