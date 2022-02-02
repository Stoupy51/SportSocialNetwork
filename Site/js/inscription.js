// Fonction appelée au chargement complet de la page
window.onload = function() {
	// Fonction appelée lors d'une modification de l'envoi du formulaire
	document.forms["Inscription"].onsubmit = function() {
		var city = document.getElementById('city');
		ajax = new AjaxRequest({
			url: "ajax_inscription.php",
			method: 'post',
			parameters: {
				login: document.getElementById('login').value,
				firstname: document.getElementById('firstname').value,
				lastname: document.getElementById('lastname').value,
				email: document.getElementById('email').value,
				phone: document.getElementById('phone').value,
				password: CryptoJS.SHA512(document.getElementById('password').value),
				passwordConf: CryptoJS.SHA512(document.getElementById('passwordConf').value),
				genre: document.querySelector('#genre:checked').value,
				birth: document.getElementById('birth').value,
				ville: city.options[city.selectedIndex].value
			},
			onSuccess: function(res) {
				if (res == "Inscription bien effectuée") {
					window.location.href = "index.php";
					return false;
				}
				else if (res == "Certaines infos sont déjà utilisées" || res == "Le mot de passe ne correspond pas au mot de passe répété")
					document.getElementById('registerConfirmation').innerText = res;
				else
					document.getElementById('registerConfirmation').innerText = "Erreur : Cette page est bloquée par votre Antivirus Web";
				ajax = null;
			},
			onError: function(status, message) {
				window.alert('Error ' + status + ': ' + message);
			}
		});
		return false;
	}
}

$(document).ready(function(){
	const apiUrl = 'https://geo.api.gouv.fr/communes?codePostal=';
	const format = '&format=json';

	let zipcode = $('#zipcode'); 
	let city = $('#city'); 
	let errorMessage = $('#error-message'); 

	$(zipcode).on('blur', function(){
		let code = $(this).val();
		//console.log(code);
		let url = apiUrl+code+format;
		//console.log(url);

		fetch(url, {method: 'get'}).then(response => response.json()).then(results => {
			//console.log(results);
			$(city).find('option').remove();
			if(results.length){
				$(errorMessage).text('').hide();
				$.each(results, function(key, value){
					//console.log(value);
					console.log(value.nom);
					$(city).append('<option value="'+value.nom+'">'+value.nom+'</option>');
				});
			}
			else{
				if($(zipcode).val()){
					console.log('Erreur de code postal.');
					$(errorMessage).text('Aucune commmune avec ce code postal.').show();
				}
				else{
					$(errorMessage).text('').hide();
				}
			}
		}).catch(err => {
			console.log(err);
			$(city).find('option').remove();
		});
	});
});
