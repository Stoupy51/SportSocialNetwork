var ajax = null;
var	myOverlay = document.createElement("div");
var div = document.createElement("div");
var outside = document.createElement("div");
var idMyThis;
var myUser;

myOverlay.hidden = "true";
myOverlay.style	= "z-index:100; opacity:0; position:fixed; top:0; left:0; width:100%; height:100%; background-color: #AAAAAA; overflow-y: scroll;";
myOverlay.classList.add("invisible_scroll");
myOverlay.id = "publication_overlay";

function fadeIn(element) {
	element.removeAttribute("hidden");
	var i = 0;
	function fadeInLoop(element) {         	//  create a loop function
		i += 10;
		setTimeout(function() {				//  call a 3s setTimeout when the loop is called
			element.style.opacity = i+"%";
			if (i < 100) {
				fadeInLoop(element);        //  call the loop function again which will trigger another
			}
  		}, 10)
	}
	fadeInLoop(element)
}
function fadeOut(element) {
	var i = 100;
	function fadeOutLoop(element) {			//  create a loop function
		i -= 10;
		setTimeout(function() {				//  call a 3s setTimeout when the loop is called
			element.style.opacity = i+"%";
			if (i > 0) {
				fadeOutLoop(element);       //  call the loop function again which will trigger another
			}
			else
				element.hidden = "true";
  		}, 10)
	}
	fadeOutLoop(element)
}

function supprimerCommentaire(myThis) {
	console.log(myThis);
	new AjaxRequest({
		url: "ajax_supprimerCommentaire.php",
		method: 'post',
		parameters: {
			id: myThis.id
		},
		onSuccess: function(res) {
			if (res == true)
				fadeOut(myThis);
		},
		onError: function(status, message) {
			alert(status, message);
		}
	});
}

function supprimerPublication(id) {
	new AjaxRequest({
		url: "ajax_supprimerPublication.php",
		method: 'post',
		parameters: {
			id: id
		},
		onSuccess: function(res) {
			console.log(res);
			if (res == true) {
				fadeOut(document.getElementById(id));
				fadeOut(myOverlay);
			}
		},
		onError: function(status, message) {
			alert(status, message);
		}
	});
}

function affichePublication(myThis) {
	idMyThis = myThis.id;
	if (ajax != null)
		ajax.cancel();
	ajax = new AjaxRequest({
		url: "ajax_affichePublication.php",
		method: 'get',
		parameters: {
			idPublication: myThis.id
		},
		onSuccess: function(res) {
			res = JSON.parse(res);
			myOverlay.innerHTML = "";
			div.remove();
			div = document.createElement("div");
			div.id = "divOverlay";
			div.innerHTML = myThis.innerHTML;
			div.style = "position:absolute; top:100px; left:25%; width:50%; background-color:white; margin-bottom: 50px;";
			comments = div.getElementsByClassName("comments")[0];
			if (res.isOwner == true) {
				suppr = document.createElement("img");
				suppr.style = "";
				div.getElementsByClassName("card")[0].innerHTML += '<img src="img/icons/close.png" class="close" style="position:absolute; height:15px; width:15px; right: 0; margin-right:10px; margin-top:10px;" onclick="supprimerPublication('+myThis.id+')"></img>';
			}
			for (let i=0; i<res.commentaires.length; i++) {
				obj = res.commentaires[i];
				comment = document.createElement("div");
				comment.id = obj.idCommentaire;
				comment.style = "color:black;";
				comment.innerHTML = "<strong style='margin-left:10px;'>"+obj.utilisateur+" </strong>"+obj.contenu;
				if (obj.isOwner == true) {
					comment.innerHTML += '<img src="img/icons/close.png" class="close" style="position:absolute; height:15px; width:15px; left:0px; margin-left:8px; margin-top:5px;" onclick="supprimerCommentaire(this.parentNode)"></img>';
				}
				comments.append(comment);
			}
			let myInput = div.getElementsByTagName("input")[0];
			console.log(myInput);
			myInput.addEventListener("keyup", function(event) {
				if (event.keyCode === 13) {
					ajouterCommentaire(this, div, myThis.id);
				}
			});
			invisiblePadding = document.createElement("div");
			invisiblePadding.style = "background-color: rgb(170, 170, 170); padding: 25px;";
			div.appendChild(invisiblePadding);
			myOverlay.appendChild(div);

			outside.remove();
			outside = document.createElement("div");
			outside.style = "position:fixed; top:0; left:0; width:100%; height:100%; z-index:-1";		
			outside.onclick = function() {
				fadeOut(myOverlay);
			};
			myOverlay.appendChild(outside);
			console.log(div);
			
			ajax = null;
			fadeIn(myOverlay);
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}

function ajouterCommentaire(myInput, div, id) {
	console.log(myInput);
	var commentText = myInput.value;
	myInput.value = "";
    if (commentText.length > 0) {
		var comments = div.getElementsByClassName("comments")[0];
		var comment = document.createElement("div");
		comment.style = "color:black; opacity:0";
		comment.hidden = "true";
		comment.innerHTML = "<strong style='margin-left:10px;'>"+myUser+" </strong>"+commentText;
		comments.prepend(comment);
        ajax = new AjaxRequest({
            url : "ajax_nouveauCommentaire.php",
            method : "post",
            parameters : {
                commentaire : commentText,
				idPublication: id
            },
			onSuccess: function(res) {
				if (res == true)
					fadeIn(comment);
			},
			onError: function(status, message) {
				alert(status, message);
			}
        })
    }
}

window.addEventListener("load",function() {
	document.querySelector("body").appendChild(myOverlay);
	myOverlay = document.getElementById("publication_overlay");
	myUser = document.getElementById("hiddenUser").value;
});
