function Imprime() {
	window.print();
}


function validar( form ) {
	// Verifica se o campo titulo foi preenchido
	if (form.titulo.value == "") {
		alert('O campo t�tulo � de preenchimento obrigat�rio!');
		form.titulo.value = "";
		form.create.titulo.focus();
		return false;
	}
	if (form.passwd.lock == true ) {
		if (form.value == "") {
			alert('O campo de password � de preenchimento obrigat�rio!');
			document.passwd.focus();
			return false;
		}
	}
	if (form.passwd.value != form.repasswd.value) {
		alert('As senhas digitadas n�o coincidem!');
		form.passwd.focus();
		return false;
	}
	return true;
}
