<html>
<head>
<script LANGUAGE ="JavaScript">
function check(id,index) {
    window.opener.document.location.replace('create.php?ident='+id+'&index='+index);
    window.close();
}
</script>
</head>
<body onLoad="check('<?echo $id?>','<?echo $index?>')">
</body>
</html>
