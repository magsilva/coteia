<?
	include_once("function.inc");

	$sess = new coweb_session;

	$sess->destroy();

	header("Location:$URL_SERVER");
?>

