function Imprime() {
	window.print();
}


function validar( form ) {
	// Verifica se o campo titulo foi preenchido
	if (form.titulo.value == "") {
		alert('O campo título é de preenchimento obrigatório!');
		form.titulo.value = "";
		form.create.titulo.focus();
		return false;
	}
	if (form.passwd.lock == true ) {
		if (form.value == "") {
			alert('O campo de password é de preenchimento obrigatório!');
			document.passwd.focus();
			return false;
		}
	}
	if (form.passwd.value != form.repasswd.value) {
		alert('As senhas digitadas não coincidem!');
		form.passwd.focus();
		return false;
	}
	return true;
}
