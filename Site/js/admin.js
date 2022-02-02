var ajax = null;

function searchActivities() {
	var startingDate = document.getElementById("startingDate"),
		endingDate = document.getElementById("endingDate"),
		activities = document.getElementById("activities");
	if (ajax != null)
		ajax.cancel();
	ajax = new AjaxRequest({
		url: "ajax_adminActivities.php",
		method: 'get',
		parameters: {
			startingDate: startingDate.value,
			endingDate: endingDate.value
		},
		onSuccess: function(res) {
			activities.innerHTML = res;
			ajax = null;
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}

function fade(element) {
	if (element.hasAttribute("hidden")) {
		fadeInHeight(element);
	}
	else {
		fadeOutHeight(element);
		ajax = null;
	}
}

function fadeInHeight(element) {
	element.removeAttribute("hidden");
	var i = 0;
	function fadeInHeightLoop(element) {
		i += 10;
		setTimeout(function() {
			element.style.height = i+"px";
			if (i < 370) {
				fadeInHeightLoop(element);
			}
  		}, 10)
	}
	fadeInHeightLoop(element)
}
function fadeOutHeight(element) {
	var i = 370;
	function fadeOutHeightLoop(element) {
		console.log(i);
		i -= 10;
		setTimeout(function() {
			element.style.height = i+"px";
			if (i > 0) {
				fadeOutHeightLoop(element);
			}
			else
				element.hidden = "true";
  		}, 10)
	}
	fadeOutHeightLoop(element)
}

function fadeActivity() {
	var hiddenActivities = document.getElementById("hiddenActivities");
	if (hiddenActivities.hasAttribute("hidden")) {
		fadeIn(hiddenActivities);
	}
	else {
		fadeOut(hiddenActivities);
	}
}

function updatedUser(user) {
	var i = 255;
	function updatedUserLoop(user) {
		i -= 25;
		setTimeout(function() {
			user.style.backgroundColor = "rgb("+i+",255,"+i+")";
			if (i > 0) {
				updatedUserLoop(user);
			}
			else
				updatedUserLoop2(user);
  		}, 10)
	}
	function updatedUserLoop2(user) {
		i += 25;
		setTimeout(function() {
			user.style.backgroundColor = "rgb("+i+",255,"+i+")";
			if (i < 255) {
				updatedUserLoop2(user);
			}
  		}, 10)
	}
	updatedUserLoop(user);
}

function notUpdatedUser(user) {
	var i = 255;
	function notUpdatedUserLoop(user) {
		i -= 25;
		setTimeout(function() {
			user.style.backgroundColor = "rgb(255,"+i+","+i+")";
			if (i > 0) {
				notUpdatedUserLoop(user);
			}
			else
			notUpdatedUserLoop2(user);
  		}, 10)
	}
	function notUpdatedUserLoop2(user) {
		i += 25;
		setTimeout(function() {
			user.style.backgroundColor = "rgb(255,"+i+","+i+")";
			if (i < 255) {
				notUpdatedUserLoop2(user);
			}
  		}, 10)
	}
	notUpdatedUserLoop(user);
}

function fadeOutUser(element) {
	var i = 100;
	function fadeOutUserLoop(element) {			//  create a loop function
		i -= 5;
		setTimeout(function() {				//  call a 3s setTimeout when the loop is called
			element.style.opacity = i+"%";
			if (i > 0) {
				fadeOutUserLoop(element);       //  call the loop function again which will trigger another
			}
			else {
				element.hidden = "true";
				element.className = "";
			}
  		}, 20)
	}
	fadeOutUserLoop(element)
}

function updateUser(user) {
	if (ajax != null)
		ajax.cancel();
	ajax = new AjaxRequest({
		url: "ajax_adminUpdateUser.php",
		method: 'post',
		parameters: {
			idUser: user.id,
			login: user.getElementsByClassName("login")[0].value,
			role: user.getElementsByClassName("role")[0].value,
			genre: user.getElementsByClassName("genre")[0].value,
			email: user.getElementsByClassName("email")[0].value,
			lastname: user.getElementsByClassName("lastname")[0].value,
			firstname: user.getElementsByClassName("firstname")[0].value,
			birth: user.getElementsByClassName("birth")[0].value,
			phone: user.getElementsByClassName("phone")[0].value,
			bio: user.getElementsByClassName("bio")[0].value
		},
		onSuccess: function(res) {
			if (res == true) {
				updatedUser(user);
			}
			else {
				notUpdatedUser(user);
			}
			ajax = null;
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}

function deleteUser(user) {
	if (ajax != null)
		ajax.cancel();
	ajax = new AjaxRequest({
		url: "ajax_adminDeleteUser.php",
		method: 'post',
		parameters: {
			idUser: user.id
		},
		onSuccess: function(res) {
			if (res == true) {
				fadeOutUser(user);
			}
			ajax = null;
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}
