<?
	include("function.inc");

	$sess = new session;

	$sess->destroy();

	header("Location:$URL_SERVER");
?>

