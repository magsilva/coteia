<?php
/**
* Error reporting page.
*
* Show errors found when running the application, describe the details
* and save the data to later analysis.
*
* Copyright (C) 2004 Marco Aurélio Graciotto Silva
*
* This code is licenced under the GNU General Public License (GPL).
*/
?>


<?php
	echo get_header( _( "An error has been found" ) );
?>

<body>

<?php
include_once( "function.php.inc" );
include( "toolbar.php.inc" );

$date = gmdate( "r" );
$ip = $_SERVER[ "REMOTE_ADDR" ];
$hostname = @gethostbyaddr( $ip );
$page = $_SERVER[ "PHP_SELF" ];
$browser = $_SERVER[ "HTTP_USER_AGENT" ];

if ( is_array( $error_message ) ) {
	$raw_data = $error_message[ 1 ];
	$error_message = $error_message[ 0 ];
}

$log_entry =
	 $date
	. "|" . $ip
	. "|" . $hostname
	. "|" . $browser
	. "|" . $page
	. "|" . $error_message
	. "\n";
$fp = @fopen( "log.txt", "a" );
if ( $fp !== false ) {
	fputs( $fp, $log_entry );
	fclose( $fp );
}

/**
* There are two kind of error messages: those with and without an $error_code.
* Those without an error code are just printed; the others have displayed a
* better description of the kind of error (mostly recurrent errors found
* when running CoTeia.
*/
if ( !isset( $error_code ) ) {
?>
	<h2><?php echo _( "An error has occurred" ); ?></h2>

	<div class="alert">
		<?php echo $error_message; ?>
	</div>

<?php
	if ( isset( $raw_data ) ) {
?>
	<div class="raw_data">
<pre>
<?php echo htmlspecialchars( $raw_data ) ?>
</pre>
	</div>
<?php
	}
?>

<?php
} else {

//st = 1 => Erro no BD.
//st = 2 => nao criou arquivo fisico
//st = 3 => erro de indexador ao criar nova pagina
//st = 4 => senha incorreta

switch ( $error_code ) {
	case 4:
?>
		<h2><?php echo _( "Incorrect password" ); ?></h2>
		<p><?php echo _( "The username or password were incorrect. Please, try again. If the problem persists, report it to the <a href=\"mailto:$ADMIN_EMAIL\">admin ($ADMIN)</a>." ); ?></p>
<?php
		break;
?>


<?php
	case 2:
?>
		<h2><?php echo _( "An error was found in the document XML structure" ); ?></h2>

		<p><?php echo _( "A error was detected in the wikipage you just edited. The most commons errors are: " ); ?></p>
		<ul>
				<li><?php echo _( "Atribute values for an XML element not delimited by quotes. Example: &lt;img src=figure.jpg /&gt; <strong>(wrong)</strong> and &lt;img src=\"figure.jpg\" /&gt; <strong>(right)</strong>." ); ?></li>
				<li><?php echo _( "Tags incorrectly closed. Example: &lt;i&gt;text <strong>wrong</strong> &lt;i&gt;text&lt;/i&gt;  <strong>(right)</strong>, &lt;br&gt; <strong>(wrong)</strong> and &lt;br /&gt; <strong>(right)</strong>." ); ?></li>
				<li><?php echo _( "The tags are incorrectly nested. Example: &lt;i&gt;&lt;b&gt;text&lt;i&gt;&lt;b&gt; <strong>(wrong)</strong> and &lt;i&gt;&lt;b&gt;text&lt;b&gt;&lt;i&gt; <strong>(right)</strong>." ); ?></li>
		</ul>

		<br />

		<p><?php echo _( "Details about the errors found:" ); ?></p>
		<div class="alert">
		<?php
			foreach ( $result as $error_message ) {
				echo "\n", $error_message, "<br />";
			}
		?>
		</div>
<?php
		break;
?>


<?php
	case 1:
?>
		<h2><?php echo _( "Error on database access" ); ?></h2>

		<p><?php echo _( "An error was found when accessing the database. Please, try again alter. If the error persists, send an email to <a href=\"mailto:$ADMIN_EMAIL\">($ADMIN)</a> or report it using the <a href=\"http://www.magsilva.dynalias.net/webTools/BugzillaSAFE/enter_bug.cgi?product=CoTeia\">Bugzilla</a>." ); ?></p>

		<p><?php echo _( "Details about the error found:" ); ?></p>
		<div class="alert">
		<?php
		  echo "\n", mysql_error();
		?>
		</div>
<?php
		break;
?>


<?php
	case 0:
?>

		<h2><?php echo _( "Unknown error: CoTeia couldn't understand the request" ); ?></h2>

		<p><?php echo _( "CoTeia detected an error on your requisition. The possible reasons are:" ); ?></p>
		<ul>
			<li><?php echo _( "Invallid (non-existant) wikipage requested" ); ?></li>
			<li><?php echo _( "An unknown and imprevisible server's error" ); ?></li>
			<li><?php echo _( "A CoTeia's bug" ); ?></li>
		</ul>

		<p><?php echo _( "If the problem persists, send an email to <a href=\"mailto:$ADMIN_EMAIL\">($ADMIN)</a> and report the error found. You may also file a bug report at <a href=\"http://www.magsilva.dynalias.net/webTools/BugzillaSAFE/enter_bug.cgi?product=CoTeia\">Bugzilla</a>."); ?></p>
<?php
		break;
?>

<?php
}
}
?>

<br />
<br />
<div align="center">
	<p><strong><a href="javascript:history.go(-1)"><?php echo _( "Return to the previous page" ); ?></a></strong></p>
</div>

</body>

</html>

<?php
	exit(1);
?>
