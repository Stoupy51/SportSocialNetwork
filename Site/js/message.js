var R = null;
var Rinfo = null;
var myCount = 0;
var loopCondition = false;
var oldLength;
function clickConversation(myThis) {
	loopCondition = true;
	myCount++;
    selections = document.getElementsByClassName("selection");
    for (let i = 0; i < selections.length; i++)
        selections[i].classList.remove("selection");
    myThis.classList.add("selection");
	

    function boutonMessage() {
        var messageForm = document.getElementById("messageForm");
        messageForm.onsubmit = function() {
            if (R != null) R.cancel();
            var message = messageForm.elements['message'];
            R = new AjaxRequest({
                url: 'ajax_message.php',
                method: 'post',
                parameters: {
                    conversation: conversation,
                    message: message.value
                },
                onSuccess: function(res) {
                    afficheMessages();
                    message.value = "";
                },
                onError: function(status, message) {
                    window.alert('Error : ' + status + ":" + message)
                }
            });
            return false;
        }
    }

    function afficheMessages() {
        if (R != null)
            R.cancel();
        R = new AjaxRequest({
            url: 'ajax_afficheMessages.php',
            method: 'post',
            parameters: {
                idConversation: myThis.id
            },
            onSuccess: function(res) {
				let oldScrollTop = document.getElementById("section").scrollTop;
				let oldFocus = document.activeElement.id;
				let inputDiv = div1.getElementsByClassName("messageForm")[0];
				inputDiv.removeAttribute("hidden");
				console.log(inputDiv);
				div1.innerHTML = res;
				div1.getElementsByClassName("messageForm")[0].remove();
				div1.append(inputDiv);
				if (oldFocus == "inputText")
					inputDiv.firstElementChild.firstElementChild.focus();

				let section = document.getElementById("section");
				console.log("old : "+oldLength+", new : "+section.childElementCount);
				console.log("old : "+oldScrollTop+", new : "+section.scrollTop);
				if (oldLength != section.childElementCount) {
					oldLength = section.childElementCount;
					section.scrollTop = oldScrollTop = section.scrollHeight;
				}
				if (section.scrollTop != oldScrollTop) {
					section.scrollTop = oldScrollTop;
				}
                boutonMessage();
            },
            onError: function(status, message) {
                window.alert('Error : ' + status + ":" + message)
            }
        });
    }
    var conversation = myThis.id;
    var div1 = document.getElementById("div1");
	if (div1.getElementsByTagName("section") != null) {
		div1.innerHTML = '<section id="section" class="container"></section><div id="sendMessage" class="mx-auto p-2 messageForm" hidden=""><form method="POST" id="messageForm" style="display:inline;" class="d-flex align-items-center"><input type="text" name="message" id="inputText" placeholder="Ecrire..." class="ecrire p-2 mr-2" style="visibility: visible;"><input type="image" id="mySubmit" class="envoyer" src="img/icons/send.png" style="visibility: visible;"></form></div>';
	}
	oldLength = 0;
    afficheMessages();
	
	function myLoop(oldCount) {         	//  create a loop function
		setTimeout(function() {				//  call a 1s setTimeout when the loop is called
			if (oldCount == myCount && loopCondition) {
				afficheMessages();   		
				myLoop(oldCount);           	//  call the loop function again which will trigger another
			}
  		}, 1000)
	}
	myLoop(myCount);               //  start the loop
}

var AJAX = null;

function nouvelleConversation() {
	loopCondition = false;
    if (AJAX != null)
        AJAX.cancel();
    AJAX = new AjaxRequest({
        url: 'ajax_nouvelleConversation.php',
        method: 'post',
        parameters: {},
        onSuccess: function(res) {
            document.getElementById("div1").innerHTML = res;
        },
        onError: function(status, message) {
            window.alert('Error : ' + status + ":" + message)
        }
    });
}

function addFriend(myThis) {
    var selectFriend = document.getElementsByClassName("addFriend");
    var myBool = true;
    for (let i = 0; i < selectFriend.length; i++)
        if (selectFriend[i] == myThis) {
            myBool = false;
            selectFriend[i].classList.remove("addFriend");
        }
    if (myBool)
        myThis.classList.add("addFriend");
}


function validerConv() {
    var selectFriend = document.getElementsByClassName("addFriend");
    var selections = [];
    for (let i = 0; i < selectFriend.length; i++)
        selections[i] = selectFriend[i].id;
	if (selections.length != 0) {
		if (AJAX != null)
			AJAX.cancel();
		AJAX = new AjaxRequest({
			url: 'ajax_nouvelleConversation.php',
			method: 'post',
			parameters: {
				selection: selections,
				nomConv: document.getElementById('nomConv').value,
			},
			onSuccess: function(res) {
				window.location.href = 'message.php';
			},
			onError: function(status, message) {
				window.alert('Error : ' + status + ":" + message);
			}
		});
	}
}

function leaveConversation(id){
	var element = document.getElementById("section");
	element.parentNode.removeChild(element);
    var Rconv = null;
    if(Rconv != null){
        Rconv.cancel();
    }
    Rconv = new AjaxRequest({
        url: 'ajax_leaveConv.php',
        method: 'post',
        parameters:{
            conversation: id,
        },
        onSuccess: function(res){
            console.log(res);
            window.location.href = 'message.php';
        },
        onError: function(status, message){
            window.alert('Error : ' + status + ":" + message);
        }
    });
}


function infoConversation(id){
    if(Rinfo != null){
        Rinfo.cancel();
    }
    Rinfo = new AjaxRequest({
        url: 'ajax_infoConv.php',
        method: 'post',
        parameters:{
            id: id,
        },
        onSuccess: function(res){
			loopCondition = false;
			if(R != null){
				R.cancel();
			}
			document.getElementById("div1").innerHTML = res;
        },
        onError: function(status, message){
            window.alert('Error : ' + status + ":" + message);
        }
    });
}

function ajouterAmiConv() {
	var selectFriend = document.getElementsByClassName("addFriend");
    var selections = [];
    for (let i = 0; i < selectFriend.length; i++)
        selections[i] = selectFriend[i].id;
	document.getElementById("addFriends").value = selections;
}