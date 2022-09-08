window.onload = function () {
	
	if (document.getElementById('irpf') && document.getElementById('nif'))
		document.getElementById('irpf').setAttribute('onchange', 'labelNIF();');
	}

function labelNIF () {

	var label  = document.getElementById('nif_field').childNodes[0];
	var orig   = label.innerHTML;
	var irpf   = document.getElementById('irpf');
	var opcion = irpf.options[irpf.selectedIndex].value;
	var textos = JSON.parse(document.getElementById('nif-irpf').getAttribute('data'));

	if (opcion == 'particular' || opcion == 'autonomo')
		label.innerHTML = textos['nif'];

	else if (opcion == 'empresa')
		label.innerHTML = textos['cif'];

	else if (!opcion)
		label.innerHTML = textos['ambos'];
	}