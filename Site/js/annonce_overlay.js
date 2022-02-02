var ajax = null;
var	myOverlay = document.createElement("div");
var div = document.createElement("div");
var outside = document.createElement("div");
var idMyThis;
var myUser;

myOverlay.hidden = "true";
myOverlay.style	= "z-index:100; opacity:0; position:fixed; top:0; left:0; width:100%; height:100%; background-color: #AAAAAA; overflow-y: scroll;";
myOverlay.classList.add("invisible_scroll");
myOverlay.id = "annonce_overlay";

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

function supprimerAnnonce(id) {
	new AjaxRequest({
		url: "ajax_supprimerAnnonce.php",
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

function afficheAnnonce(myThis) {
	idMyThis = myThis.id;
	if (ajax != null)
		ajax.cancel();
	ajax = new AjaxRequest({
		url: "ajax_afficheAnnonce.php",
		method: 'get',
		parameters: {
			idAnnonce: myThis.id
		},
		onSuccess: function(res) {
			res = JSON.parse(res);
			myOverlay.innerHTML = "";
			div.remove();
			div = document.createElement("div");
			div.id = "divOverlay";
			div.innerHTML = myThis.innerHTML;
			div.style = "position:absolute; top:100px; left:25%; width:50%; background-color:white; margin-bottom: 50px;";
			if (res.isOwner == true) {
				suppr = document.createElement("img");
				suppr.style = "";
				div.getElementsByClassName("card")[0].innerHTML += '<img src="img/icons/close.png" class="close" style="position:absolute; height:15px; width:15px; right: 0; margin-right:10px; margin-top:10px;" onclick="supprimerAnnonce('+myThis.id+')"></img>';
			}
			let myInput = div.getElementsByTagName("input")[0];
			console.log(myInput);

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

window.addEventListener("load",function() {
	document.querySelector("body").appendChild(myOverlay);
	myOverlay = document.getElementById("annonce_overlay");
	myUser = document.getElementById("hiddenUser").value;
});
