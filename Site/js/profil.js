window.addEventListener("load",function() {
	//Des icones
	document.body.innerHTML += '<svg xmlns="http://www.w3.org/2000/svg" style="display: none;"> <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16"> <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/> </symbol> <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16"> <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/> </symbol> <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16"> <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/> </symbol> </svg>';
	let username_container = document.querySelector('.Pseudo');
	const username = username_container.childNodes[1].innerText;
	let myThis = document.getElementById("boutonSabo");
	var videoForm = createVideoPost();
	new AjaxRequest({
		url: 'ajax_isFriend.php',
		method: 'post',
		parameters: {
			user_name: username
		},
		onSuccess: function(res) {
			console.log(res);
			if (res == true) {
				myThis.value = "Se désabonner";
				myThis.classList.add("boutonDesabo");
				myThis.classList.remove("boutonSabo");
			}
			else
			{
				myThis.value = "S'abonner";
				myThis.classList.remove("boutonDesabo");
				myThis.classList.add("boutonSabo");
			}
		},
		onError: function(status, message) {
			window.alert('Error : ' + status + ":" + message)
		}
	});

var divPublications = document.getElementById("divPublications"),
	divVideos = document.getElementById("divVidéos"),
	divAmis = document.getElementById("divAmis");

document.getElementById("Publications").onclick = function affichePublications() {
	console.log("Affichage des publications");
	divPublications.classList.remove("hidden");
	divVideos.classList.add("hidden");
	divAmis.classList.add("hidden");
};
document.getElementById("Vidéos").onclick = function afficheVideos() {
	console.log("Affichage des vidéos");
	divPublications.classList.add("hidden");
	divVideos.classList.remove("hidden");
	divAmis.classList.add("hidden");
	let nbVideos = document.getElementById('nbVideos').innerText;
	console.log(nbVideos);
	var videoContainer = document.querySelector('#video-form-container');
	var videoButton = document.createElement('button');
	videoButton.classList.add('btn', 'p-4');
	videoButton.innerText = 'Submit Post';
	videoButton.setAttribute('type', 'submit');
	videoButton.setAttribute('id', 'submit-post');

	videoButton.addEventListener('click', function () {
		return;
	});
	
	videoContainer.prepend(videoForm);
	
	document.forms['formVideo'].onsubmit = function () { 
		new AjaxRequest(
			{
				url: "ajax_nouvelleVideo.php",
				method: 'post',
				parameters: {
					title: document.forms['formVideo'].elements['video-headline'].value,
					link: document.forms['formVideo'].elements['video-link'].value,
				},
				onSuccess: function (res) {
					if (res == true) {
						window.location.href = window.location.href;
					}
				},
				onError: function (status, message) {
					alert(status, message);
				}
			});
		return false;
	}
};
document.getElementById("Amis").onclick = function afficheAmis() {
	console.log("Affichage des amis");
	divPublications.classList.add("hidden");
	divVideos.classList.add("hidden");
	divAmis.classList.remove("hidden");
};


document.getElementById("boutonSabo").onclick = function boutonAbonnement() {
	var ajax = null;
	let myThis = document.getElementById("boutonSabo");
	if (myThis.value == "S'abonner") {
		let username_container = document.querySelector('.Pseudo');
		const username = username_container.childNodes[1].innerText;
		console.log(username);
		//Ajoute un Ami
		if (ajax != null)
            ajax.cancel();
		ajax = new AjaxRequest(
			{
			url: 'ajax_nouvelleAmi.php',
			method: 'post',
			parameters: {
				user_name: username
			},
			
			onSuccess: function(res) {
				console.log("in ajax rq");
				if (res == true) {
					console.log("returns true");
					
					let newFriendNotif = document.createElement('div');
					newFriendNotif.className = "alert alert-success";
					newFriendNotif.setAttribute('role', "alert");
					newFriendNotif.innerHTML = '<h4 class="alert-heading">Well done!</h4> <p>Vous venez d&rsquo;ajouter '+username+' comme ami</p> </div>';
					let navbar = document.querySelector('.main-bar');
					newFriendNotif.style.zIndex = 10000000000;
					navbar.parentNode.insertBefore(newFriendNotif, navbar);
					
					myThis.value = "Se désabonner";
					myThis.classList.add("boutonDesabo");
					myThis.classList.remove("boutonSabo");
					
					setTimeout(() => {
						$(".alert").alert('close')
					}, 5000);
					
				}
			},
			onError: function(status, message) {
				alert(status, message);
			}
		});
	}
	else {
		let warning = document.createElement('div');
		warning.className = "alert alert-danger d-flex align-items-center alert-dismissible fade show";
		warning.setAttribute('role', "alert");
		warning.innerHTML = '<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>';
		warning.innerHTML += '<strong> ATTENTION!&nbsp;</strong> Etes-vous sur de vouloir vous désabonnez de ce compte?&nbsp;<button class="btn btn-warning" "type="button" onclick="unsub()">Se Désabonner</button><button type="button" onclick="close_popup()" id="close-button" class="close btn btn-warning" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
		let navbar = document.querySelector('.main-bar');
		warning.style.zIndex = 10000000000;
		navbar.parentNode.insertBefore(warning, navbar);
		//navbar.parentNode.insertBefore(warning, navbar.nextSibling);
	}
};

},false)

function close_popup() {
	$(".alert").alert('close');
}

function unsub(){
	let myThis = document.getElementById("boutonSabo");
	let username_container = document.querySelector('.Pseudo');
	const username = username_container.childNodes[1].innerText;
	//Supprime un Ami
	new AjaxRequest({
		url: "ajax_supprimerAmi.php",
		method: 'post',
		parameters: {
			user_name: username
		},
		onSuccess: function(res) {
			console.log('deleted: ', res);
			if (res == true) {
				myThis.value = "S'abonner";
				myThis.classList.remove("boutonDesabo");
				myThis.classList.add("boutonSabo");

				setTimeout(() => {
					$(".alert").alert('close')
				}, 1000);
			}
		},
		onError: function(status, message) {
			alert(status, message);
		}
	});
}

//////////////////////////////////////////
// Fonction créant le formulaire de création d'une vidéo
let createVideoPost = () => {

    let mainPost = document.createElement('div');
    mainPost.className = "main-post";

    let formMain = document.createElement('div');
    formMain.setAttribute('name', 'post');

    let card = document.createElement('div');
    card.classList.add("card", "card-formPost");

    let form = document.createElement('form');
    form.id = "form-video-id";
	form.style = "display: initial;"
	form.method = "POST";
    form.setAttribute('name', "formVideo");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Card header with user info
    let cardHeader = document.createElement('div');
    cardHeader.className = 'card-header';
    

    let username = document.createElement('span');
    username.className = "username-post";
    username.innerText = document.getElementById("hiddenUser").value;
    cardHeader.appendChild(username);

    let videoLabel = document.createElement('label');
    videoLabel.innerText = "Création d'une Vidéo";


    //Séparateur 
    let seperator = document.createElement('hr');
    seperator.className = "sep";
	seperator.style = "width: 100%";
    
    // Champ de saisie du titre du poste de la vidéo
    let inputTitlePost = document.createElement('div');
    inputTitlePost.innerHTML = '<label> <h2>Titre de votre vidéo</h2> </label> <div class="input-form-wrapper"> <input type="text" name="video-headline" required style="width: 100%;" /> </div>';
    inputTitlePost.className = "video-title-wrapper";

    // Champ de saisie du lien du poste de la vidéo
    let inputVideoLink = document.createElement('div');
    inputVideoLink.innerHTML = '<label> <h2>Lien Youtube &trade; de votre vidéo</h2> <br>Doit être un lien <strong>Youtube Intégré</strong></br></label> <div class="input-form-wrapper"> <input type="text" name="video-link" placeholder="https://www.youtube.com/embed/P5gMnjo5Tro" style="width: 100%;" required /> </div>';
    inputVideoLink.className = "video-link-wrapper";

    //Bouton validation de la création du poste
    let button = document.createElement('button');
    button.classList.add('btn', 'p-1');
    button.innerText = 'Submit Post';
    button.setAttribute('type', 'submit');
	button.style = "background-color: orange;"

    // Assemblage du poste
    form.append(cardHeader);

	form.append(inputTitlePost);

    form.append(seperator);

	form.append(inputVideoLink);
	
    form.append(button);

    card.append(form);

    formMain.append(card);
    
    mainPost.append(formMain);

    return formMain;
}