//Peut-etre le récup sur données de session?
window.addEventListener("load",function() {
	user = document.getElementById("hiddenUser").value;
	idImageUser = document.getElementById("hiddenIdImage").value;
	document.getElementById("newPostIcon").onclick = function () { newPost();};
});

var ajax = null,
	ajaxDresse = null,
	ajaxEvent = null;
var commentInput = document.getElementById("input");
var commentButton = document.getElementById("btnn")
var postsInput = document.querySelectorAll('.comment-input');
var postsBtn = document.querySelectorAll('.post-comment');
var commentsBoxs = document.querySelectorAll('.comments');

$(document).ready(function () {
    function LoadPosts() {

    }
});

for (var inputField of postsInput) {
    let id4 = () => {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    inputField.setAttribute("id", id4().toString());

    inputField.addEventListener("keypress", function (evt) {
        if (evt.key == 'Enter') {
            console.log(document.getElementById(evt.target.id).value);
            addComment(evt);
        }
    });
}
for (var button of postsBtn) {
    let id4 = () => {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    button.setAttribute("id", id4().toString());
    commentButton.addEventListener('click', function (evt) {
        addComment(evt);
    });
}
for (var commentsBox of commentsBoxs) {
    let id4 = () => {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    commentsBox.setAttribute("id", id4().toString());
    var commentSpan = document.createElement("div");
    commentSpan.setAttribute("id", id4().toString());
    commentSpan.className = 'comments-span';
    commentSpan.style.display = 'none';
    commentsBox.append(commentSpan);
    var span = document.createElement('span');
    span.setAttribute("id", id4().toString());
}

/*----------------------------------------------------------------------------------------------------------------
Fonction qui rajoute des commentaires sur un post 
*/

function addComment(evt) {
	console.log("hey");
    var commentBoxId = "";
    var input = '';
    var ajax = null;
    if (evt.target.className == "comment-input") {
        input = document.getElementById(evt.target.id);
        commentBoxId = $("#" + evt.target.id).prev().attr("id");
        console.log(input);
    }
    else if (evt.target.className == "post-comment") {
        let inputId = $("#" + evt.target.id).prev().attr("id");
        input = document.getElementById(inputId);
        commentBoxId = $("#" + inputId).prev().attr("id");
        console.log(evt.target.className);
        console.log(commentBoxId);
    }
    console.log(input);
    let comments = document.getElementById(commentBoxId);
    var commentText = input.value;
    let comment = document.createElement('div');
    let spanCommentsId = $("#" + commentBoxId).children(".comments-span").attr("id");
    console.log(spanCommentsId);
    let spanComments = document.getElementById(spanCommentsId);
    input.value = '';
    if (commentText.length > 0) {
        commentText = commentText.length > 60 ? commentText.substring(0, 60) + "..." : commentText;
        comment.innerHTML = '<strong style="color:black">' + user + "&nbsp;&nbsp;" + '</strong>' + commentText;
        ajax = new AjaxRequest({
            url : "ajax_nouveauCommentaire.php",
            method : "post",
            parameters : {
                commentaire : commentText,
            }
        })
        comments.appendChild(comment);
        console.log('Comment successfully send!')
        if (comments.childElementCount >= 6) {
            let countSpan = spanComments.childElementCount;
            span.innerHTML = '';
            let text = comments.childElementCount == 6 ? "Afficher plus..." : "Afficher les " + countSpan + " commentaires...";
            span.innerHTML = text;
            comments.appendChild(span);
            spanComments.appendChild(comment);
            if (spanComments.childElementCount > 1) {
                span.style.cursor = "pointer";
                span.addEventListener("click", function (e) {
                    openOverlay(e);
                });
            }
        }
    }
    else {
        console.log("Not comments to be send(empty comment)");
    }
}

function openOverlay(e) {
    var overlay = document.getElementById("overlay");
    var closeBtn = document.querySelector('.close-overlay');
    var btnIcon = document.querySelector('button > img');

    overlay.classList.add("overlay-transition");
    overlay.style.display = "block";
    closeBtn.style.display = "block";
    btnIcon.style.display = "block";
    document.body.style.overflow = 'hidden';

    let test = document.querySelectorAll(".overlay-post-wrapper");

    for (const post of test) {
        post.remove();
    }

    console.log(e.target.id);

    var current = $("#" + e.target.id);
    console.log(current);

    let commentsBox = current.parent();
    console.log(commentsBox);

    var commentsSpan = $("#" + commentsBox.children(".comments-span").attr("id"));
    console.log(commentsSpan);

    var username = commentsBox.prev().children(".card-header").children(".username-post").text();
    console.log(username);

    var imgSrc = commentsBox.prev().children(".card-img-bottom").attr("src");
    var imgPost = document.createElement('img');

    imgPost.setAttribute("src", imgSrc);
    imgPost.className = "overlay-img-post";
    console.log(imgPost);

    var pfpSrc = commentsBox.prev().children(".card-header").children(".card-avatar").attr("src");
    var pfpPost = document.createElement('img');

    pfpPost.setAttribute("src", pfpSrc);
    pfpPost.className = "overlay-avatar";
    console.log(pfpPost);

    let createOverlayPost = () => {

        let container = document.createElement('div');
        container.className = "overlay-post-wrapper";

        let card = document.createElement('div');
        card.className = 'd-flex flex-row  overlay-post';
        card.style.zIndex = "1000000000000";

        let cardHeaderImg = document.createElement('div');
        cardHeaderImg.className = 'd-flex flex-column img-container';
        cardHeaderImg.appendChild(imgPost);

        let cardSideContent = document.createElement('div');
        cardSideContent.className = 'd-flex flex-column overlay-side-content'

        let cardSideHeader = document.createElement('div');
        cardSideHeader.className = 'd-flex flex-row align-items overlay-header'
        cardSideHeader.appendChild(pfpPost);
        let usernameSpan = document.createElement('span');
        usernameSpan.innerHTML = username;
        cardSideHeader.appendChild(usernameSpan);

        let seperator = document.createElement('hr');
        seperator.className = "sep hr2";

        let cardSideComments = document.createElement('div');
        cardSideComments.className = 'overlay-comments';
        let spanCommentId = commentsBox.children(".comments-span").attr("id");
        let spanComment = document.getElementById(spanCommentId).childNodes;

        for (var commentOverlay of spanComment) {
            cardSideComments.innerHTML += commentOverlay.innerHTML + '<br>';
        }

        console.log(spanComment);

        cardSideContent.appendChild(cardSideHeader);
        cardSideContent.appendChild(seperator);
        cardSideContent.appendChild(cardSideComments);

        card.appendChild(cardHeaderImg);
        card.appendChild(cardSideContent);
        container.appendChild(card);
        return container;
    }
    var createPost = createOverlayPost();
    overlay.appendChild(createPost);
    overlay.addEventListener("click", function (evt) {
        if (overlay !== evt.target) return;
        overlay.classList.add("overlay-transition");
        overlay.style.display = "none";
        closeBtn.style.display = "none";
        document.body.style.overflow = 'scroll';
        document.querySelector(".overlay-post-wrapper").remove();
    });
    closeBtn.addEventListener("click", function () {
        overlay.classList.add("overlay-transition");
        overlay.style.display = "none";
        closeBtn.style.display = "none";
        document.body.style.overflow = 'scroll';
        document.querySelector(".overlay-post-wrapper").remove();
    });
}

//////////////////////////////////////////
// Fonction créant le formulaire de création d'un évènement ou d'une publication
let createPostForm = () => {

    let mainPost = document.createElement('div');
    mainPost.className = "main-post";

    let formMain = document.createElement('div');
    formMain.setAttribute('name', 'post');
    formMain.className = 'post-form';

    let card = document.createElement('div');
    card.classList.add("card", "card-formPost");

    let form = document.createElement('form');
    form.id = "formTest";
    form.setAttribute('name', "formPost");
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Card header with user info
    let cardHeader = document.createElement('div');
    cardHeader.className = 'card-header';
    
    let avatar = document.createElement('img');
    avatar.className = "card-avatar";
    avatar.setAttribute('src', "getImage.php?id="+idImageUser);

    let username = document.createElement('span');
    username.className = "username-post";
    username.innerText = user;
    cardHeader.append(avatar);
    cardHeader.appendChild(username);

    //Checkbox pour séléctionner une publication ou un évènement
    let eventLabel = document.createElement('label');
    eventLabel.innerText = "Publication ou évènement";
    eventLabel.className = 'checkboxLabel';

    let checkbox = document.createElement('input');
    checkbox.setAttribute('type', 'checkbox');
    checkbox.id = "isEvent";
	checkbox.style = "margin-left: 20px; margin-bottom: 0px;";

    //Sélection du sport...
    let sportSelector = document.createElement('div');
    sportSelector.classList.add('d-flex', 'flex-row', 'main-categories-mini', 'event-sport-wrapper');
    sportSelector.innerHTML = '<div class="d-flex flex-column scroll-item-mini m-3"><img src="img/Volley-ball.png" class="scroll-item-img" alt="Icon catégorie"></img><div class="scroll-item-title">Volleyball</div></div><div class="d-flex flex-column scroll-item-mini m-3"><img src="img/basket.jpg" class="scroll-item-img" alt="Icon catégorie"></img><div class="scroll-item-title">Basketballl</div></div><div class="d-flex flex-column scroll-item-mini m-3">         <img src="img/soccer.jpg" class="scroll-item-img" alt="Icon catégorie"></img><div class="scroll-item-title">Soccer</div></div><div class="d-flex flex-column scroll-item-mini m-3"><img src="img/bowling.jpg" class="scroll-item-img" alt="Icon catégorie"></img><div class="scroll-item-title">Bowling</div></div><div class="d-flex flex-column scroll-item-mini m-3"><img src="img/badminton.png" class="scroll-item-img" alt="Icon catégorie"></img><div class="scroll-item-title">Badminton</div></div><div class="d-flex flex-column scroll-item-mini m-3">         <img src="img/run.jpg" class="scroll-item-img" alt="Icon catégorie"></img>         <div class="scroll-item-title">Course</div>       </div>       <div class="d-flex flex-column scroll-item-mini m-3">         <img src="img/muscle.jpg" class="scroll-item-img" alt="Icon catégorie"></img>         <div class="scroll-item-title">Musculation</div>       </div>       <div class="d-flex flex-column scroll-item-mini m-3">         <img src="img/dance.jfif" class="scroll-item-img" alt="Icon catégorie"></img>         <div class="scroll-item-title">Danse</div>       </div>       <div class="d-flex flex-column scroll-item-mini m-3">         <img src="img/lutte.jpg" class="scroll-item-img" alt="Icon catégorie"></img>         <div class="scroll-item-title">Lutte</div>       </div>       <div class="d-flex flex-column scroll-item-mini m-3">         <img src="img/Boxe.png" class="scroll-item-img" alt="Icon catégorie"></img>         <div class="scroll-item-title">Boxe</div>       </div>       <div class="d-flex flex-column scroll-item-mini m-3">         <img src="img/rugby.jfif" class="scroll-item-img" alt="Icon catégorie"></img>         <div class="scroll-item-title">Rugby</div></div><div class="d-flex flex-column scroll-item-mini m-3"><img src="img/climb.png" class="scroll-item-img" alt="Icon catégorie"></img>         <div class="scroll-item-title">Escalade</div>       </div>';
    //Dépôt de l'image du poste
    let dropRegion = document.createElement('div');
    dropRegion.id = "drop-region";
    dropRegion.innerHTML = '<h2 id="drop-file-text">Cliquer ici pour déposer votre image !</h2> <div class="drop-message m-5"> <svg version="1.1" id="add-image" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="56.513px" height="56.513px" viewBox="0 0 316.513 316.513" style="enable-background: new 0 0 316.513 316.513" xml:space="preserve"> <defs> <linearGradient id="gradient"> <stop offset="0%" stop-color="rgba(255,162,109,1)" /> <stop offset="10%" stop-color="rgba(255,162,109,1)" /> <stop offset="37%" stop-color="rgba(223,211,34,1)" /> <stop offset="102%" stop-color="rgba(226,161,31,1)" /> </linearGradient> </defs> <g> <path fill="url(#gradient)" d="M158.253,0C71,0,0.012,71.001,0.012,158.263c0,87.256,70.989,158.25,158.241,158.25 c87.259,0,158.248-70.994,158.248-158.25C316.501,71.001,245.518,0,158.253,0z M230.891,177.982h-57.748v52.914 c0,7.428-4.864,13.438-12.301,13.438c-7.422,0-12.298-6.017-12.298-13.438v-52.914H85.634c-7.44,0-13.454-4.864-13.454-12.298 s6.014-12.301,13.454-12.301h62.909V85.616c0-7.434,4.876-13.453,12.298-13.453c7.437,0,12.301,6.02,12.301,13.453v67.768h57.748 c7.439,0,13.456,4.867,13.456,12.301S238.33,177.982,230.891,177.982z" /> </g> </svg> </div> <div id="image-preview"></div>';

    //Séparateur 
    let seperator = document.createElement('hr');
    seperator.classList.add('sep', 'sep-form');
    
    // Champ de saisie du titre du poste
    let inputTitlePost = document.createElement('div');
    inputTitlePost.innerHTML = '<label> <h2 id="changeEvent">Message de votre publication</h2> </label> <div class="input-form-wrapper"> <input type="text" name="post-headline" required /> </div>';
    inputTitlePost.className = "post-title-wrapper";

    // Champ de saisie du message de l'évènement
    let inputMessagePost = document.createElement('div');
    inputMessagePost.innerHTML = '<label> <h2>Description de votre événement</h2> </label> <div class="input-form-wrapper"> <input type="text" name="post-message" /> </div>';
    inputMessagePost.className = "event-message-wrapper post-title-wrapper";

    // Champ de saisie de la date du poste
    let inputDatePost = document.createElement('h4');
    inputDatePost.className = "text-post-form";
    inputDatePost.innerHTML = '<label class="blanc button px-2"> Date de l&#039évènement <div class="input-form-wrapper"> <input id="event" type="date" name="date-event"> </div> </label>';
    inputDatePost.className = "event-date-wrapper";

    // Champ de saisie du lieu d'un évènement
    let inputLocationPost = document.createElement('h4');
    inputLocationPost.className = "location-post-form";
    inputLocationPost.innerHTML = '<label class="blanc button px-2"> Lieu de l&#039évènement <div class="input-form-wrapper"> <input type="text" id="inputAdresse" name="location-event" list="adresses" /> <input hidden type="text" id="coords-event" name="coords-event" /> </div> </label>'

    // Champ de saisie du nombre de participants
    let participants = document.createElement('h4');
    participants.innerHTML = '<label class="blanc button px-2"> Nombre de participants <div class="input-form-wrapper"> <input type="text" name="event-participants" pattern="[0-9]{2}" /> </div> </label>'
    participants.className = "participants-wrapper";

    let rowContainer = document.createElement('div');
    rowContainer.className = "d-flex flex-row justify-content-center";
    
    // Champ de saisie de l'heure de l'évènement
    let timeForm = document.createElement('div');
    timeForm.className = 'md-form';
    timeForm.id = 'time-select';
    timeForm.innerHTML = '<h4 class="text-post-form"> <label for="input_starttime"> Heure de l&#039évènement <input id="timepicker" name="timepicker" width="276" />  </label> </h4>';

    //Boutton validation de la création du poste
    let button = document.createElement('button');
    button.classList.add('btn', 'p-1');
    button.innerText = 'Submit Post';
    button.setAttribute('type', 'submit');
    button.setAttribute('id', 'submit-post');

    eventLabel.append(checkbox);
    cardHeader.append(eventLabel);
    // Assemblage du poste
    form.append(cardHeader);
    form.append(inputTitlePost);

	inputMessagePost.style.display = 'none';
    form.append(inputMessagePost);
    form.append(dropRegion);

    form.append(seperator);

    inputDatePost.style.display = 'none';
    form.append(inputDatePost);
    form.append(seperator);

    rowContainer.append(inputLocationPost);

    rowContainer.append(participants);

    inputLocationPost.style.display = 'none';
    participants.style.display = 'none';
    form.append(rowContainer);
    form.append(seperator);

    //sportSelector.style.display = 'none';
    //form.append(sportSelector);

    timeForm.style.display = 'none';
    form.append(timeForm);
    
    form.append(button);

    card.append(form);

    formMain.append(card);
    
    mainPost.append(formMain);

    return formMain;
}

function newPost() {
    document.body.style.overflow = 'hidden';
    var overlay = document.getElementById("overlay");
    var closeBtn = document.querySelector('.close-overlay');
    closeBtn.style.display = "block";
    overlay.style.display = "block";
    overlay.style.overflowY = "scroll";
    overlay.style.overflowX = "hidden";
    
    var postToHTML = createPostForm();
    //Append post form into overlay
    $('#overlay').append(postToHTML);

	document.getElementById("inputAdresse").onkeyup = getCoords;



    document.getElementById('isEvent').addEventListener("click", function(e) {
        var locationSelect = document.querySelector('.location-post-form');
        var timeSelect = document.getElementById('time-select');
        var dateSelect = document.querySelector('.event-date-wrapper');
        var sportSelect = document.querySelector('.event-sport-wrapper');
        var messageSelect = document.querySelector('.event-message-wrapper');
        var participantsSelect = document.querySelector('.participants-wrapper');
        if (document.getElementById('isEvent').checked == true) {
            timeSelect.style.display = "block";
            locationSelect.style.display = "block";
            dateSelect.style.display = "block";
            participantsSelect.style.display = "block";
            //sportSelect.style.display  = "block";
            messageSelect.style.display  = "block";

            document.getElementsByName("timepicker")[0].required = "true";
			document.getElementsByName("location-event")[0].required = "true";
            document.getElementsByName("date-event")[0].required = "true";
            document.getElementsByName("event-participants")[0].required = "true";
            document.getElementsByName("post-message")[0].required = "true";
            //document.getElementsByName("sport-select")[0].required = "true";
            document.getElementById('changeEvent').innerText = 'Titre de votre événement';
        }
        else {
            timeSelect.style.display = "none";
            locationSelect.style.display = "none";
            dateSelect.style.display = "none";
            participantsSelect.style.display = "none";
            sportSelect.style.display = "none";
            messageSelect.style.display = "none";

			document.getElementsByName("timepicker")[0].removeAttribute("required");
			document.getElementsByName("location-event")[0].removeAttribute("required");
            document.getElementsByName("date-event")[0].removeAttribute("required");
            document.getElementsByName("event-participants")[0].removeAttribute("required");
            document.getElementsByName("post-message")[0].removeAttribute("required");
			
            document.getElementById('changeEvent').innerText = 'Message de votre publication';
        }
    });
    //Manage Image post with html drag & drop API
    var dropRegion = document.getElementById("drop-region");
    var imagePreview = document.getElementById("image-preview");

    var fileManager = document.createElement("input");
    fileManager.type = "file";
    fileManager.accept = "image/*";
    fileManager.multiple = true;
    dropRegion.addEventListener("click", function (e) {
        fileManager.click();
    });
    fileManager.addEventListener("change", function (e) {
        var files = fileManager.files;
        handleFiles(files);
    });

    dropRegion.addEventListener('drop', function (e) {
        var data = e.dataTransfer,
            files = data.files;
        handleFiles(files);
    }, false);

    function preventDefault(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    dropRegion.addEventListener('dragenter', preventDefault, false);
    dropRegion.addEventListener('dragleave', preventDefault, false);
    dropRegion.addEventListener('dragover', preventDefault, false);
    dropRegion.addEventListener('drop', preventDefault, false);

    function handleFiles(files) {
        for (var file of files) {
            const types = ["image/jpeg", "image/png", "image/gif", "image/jpg"];
            if (types.indexOf(file.type) != -1) {
                previewImg(file);
            }
            else{
                alert("Not correct file format given ! \r\n\r\n(Needs to be jpeg, png or gif)");
            }
        }
    }
    function previewImg(image) {
        document.getElementById("drop-file-text").style.display = "none";
        document.getElementById("add-image").style.display = "none";
        var imgView = document.createElement("div");
        imgView.className = "image-view";
        imagePreview.appendChild(imgView);

        // previewing image
        var img = document.createElement("img");
        imgView.appendChild(img);

        // read the image...
        var reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
			imageData = e.target.result;
        };
        reader.readAsDataURL(image);

        var formData = new FormData();
        formData.append('image', image);
        getAndSendImages(formData)
    }

    function getAndSendImages(imagesData) {
        var imagesUrl = [];
        $.ajax({
            url:'./images.php',
            data:imagesData,
            processData:false,
            contentType:false,
            type:'POST',
            success:function(res){
                imagesUrl.push(res);
                console.log(res);
            }
        });
        //document.forms['formPost'].onsubmit = function () { return submitPost(imagesUrl); }
    }

    //closing the overlay when clicking outside of the post form box
    overlay.addEventListener("click", function (evt) {
        if (overlay !== evt.target) return;
        closeOverlay();
    });
    closeBtn.addEventListener("click", function () {
        closeOverlay();
    });
    if (postToHTML != null) {
        var typeSport = 0;
        $('#timepicker').timepicker({   mode: '24hr',
                                        format: 'HH:MM'});
        $('.gj-modal').css('z-index', '10000000000000');
        
        $('.main-categories-mini').children().on('click', function (e) {
            e.target.style.opacity = 0.3;
            typeSport = e.target.id;
            if(e.target.style.opacity == 0.3){
                e.target.style.opacity = 1;
            }
        })
        
		var imageData;
        document.forms['formPost'].onsubmit = function () {
			console.log("submit");
            
            if (document.getElementById('isEvent').checked == true) {
				getCoords();
				if (ajaxDresse != null)
					ajaxDresse.cancel();
				ajaxDresse = new AjaxRequest({
					url: "https://api-adresse.data.gouv.fr/search/",
					method: 'get',
					parameters: {
						q: document.getElementById('inputAdresse').value,
					},
					onSuccess: function(res) {
						let obj = JSON.parse(res).features[0].geometry.coordinates;
						document.getElementById("coords-event").value = obj[1]+","+obj[0];
						new AjaxRequest({
							url: "ajax_nouvellePublication.php",
							method: 'post',
							parameters: {
								nom: document.forms['formPost'].elements['post-headline'].value,
								message: document.forms['formPost'].elements['post-message'].value,
								image: imageData,
								dateEvent: document.forms['formPost'].elements['date-event'].value,
								lieuEvent: document.forms['formPost'].elements['location-event'].value,
								coords: document.forms['formPost'].elements['coords-event'].value,
								heure : document.forms['formPost'].elements['timepicker'].value,
								isEvent: 1
							},
							onSuccess: function (res) {
								console.log(res);
								if (res != null) {
									window.location.href = ".";
								}
							},
							onError: function (status, message) {
								alert(status, message);
							}
						});		
					},
					onError: function(status, message) {
						window.alert('Error ' + status + ': ' + message);
					}
				});
            }
			else {
				new AjaxRequest(
                {
                    url: "ajax_nouvellePublication.php",
                    method: 'post',
                    parameters: {
                        message: document.forms['formPost'].elements['post-headline'].value,
                        image: imageData,
						isEvent: 0
                    },
                    onSuccess: function (res) {
						console.log(res);
                        if (res!=null) {
                            window.location.href = ".";
                        }
                    },
                    onError: function (status, message) {
                        alert(status, message);
                    }
                });
			}
            return false;
        }
    }
}

function submitPost(images) {
    var eventDate = document.forms['formPost'].elements['date-event'].value;
    var headerTitle = document.forms['formPost'].elements['post-headline'].value;
    new AjaxRequest(
        {
            url: "create_images.php",
            method: 'post',
            parameters: {
                images: images
            },
            onSuccess: function (res) {
                console.log("Image(s) Successfully Send");
            },
            onError: function (status, message) {
                alert(status, message);
            }
        });
    closeOverlay();
    setTimeout(function () {
            createPost(headerTitle, images, eventDate), 2000
    });
}

function closeOverlay() {
    let overlay = document.getElementById("overlay");
    let closeBtn = document.querySelector('.close-overlay');
    overlay.classList.add("overlay-transition");
    overlay.style.display = "none";
    closeBtn.style.display = "none";
    document.body.style.overflowY = 'scroll';
    $(".post-form").remove();
    $('#new-post').click();
}

/* The code for creation of the actual post*/

function createPost(postTitle, imagePost, date) {
    let form = document.forms['formtest'];
    // Création de la requête AJAX
    new AjaxRequest(
        {
            url: "create_post.php",
            method: 'post',
            parameters: {
                headline: postTitle,
                image: imagePost,
                date : date
            },
            onSuccess: function (res) {
                console.log("Post created successfully");
                $(".main-post").prepend(res);
                $(".post-user").animate({"height": "100%"}, "slow");
            },
            onError: function (status, message) {
                alert(status, message);
            }
        });
    return false;
}

function getCoords() {
	// https://api-adresse.data.gouv.fr/search/?q=
	var adresse = document.getElementById('inputAdresse').value; 
	if (ajaxDresse != null)
		ajaxDresse.cancel();
	if (adresse != "")
	ajaxDresse = new AjaxRequest({
		url: "https://api-adresse.data.gouv.fr/search/",
		method: 'get',
		parameters: {
			q: adresse,
		},
		onSuccess: function(res) {
			let obj = JSON.parse(res).features;
			let datalist = document.getElementById("adresses");
			datalist.innerHTML = "";
			for (let i=0; i < obj.length; i++) {
				let option = document.createElement("option");
				option.value = obj[i].properties.label;
				datalist.append(option);
			}
			document.getElementById("coords-event").value = [obj[0].geometry.coordinates[1],obj[0].geometry.coordinates[0]];
			ajaxDresse = null;
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}

function joinEvent(myThis, id) {
	if (ajaxEvent != null)
		ajaxEvent.cancel();
	ajaxEvent = new AjaxRequest({
		url: "ajax_joinEvent.php",
		method: 'get',
		parameters: {
			id: id,
		},
		onSuccess: function(res) {
			myThis.text = " Quitter";
			myThis.onclick = function () { leaveEvent(myThis,id); }
			ajaxEvent = null;
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}

function leaveEvent(myThis, id) {
	if (ajaxEvent != null)
		ajaxEvent.cancel();
	ajaxEvent = new AjaxRequest({
		url: "ajax_leaveEvent.php",
		method: 'get',
		parameters: {
			id: id,
		},
		onSuccess: function(res) {
			myThis.text = " Rejoindre";
			myThis.onclick = function () { joinEvent(myThis,id); }
			ajaxEvent = null;
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}

function acceptUser(myThis, idUser, idEvent) {
	if (ajaxEvent != null)
		ajaxEvent.cancel();
	ajaxEvent = new AjaxRequest({
		url: "ajax_acceptUserInEvent.php",
		method: 'get',
		parameters: {
			idUser: idUser,
			idEvent: idEvent,
		},
		onSuccess: function(res) {
			console.log(myThis.parentNode);
			if (myThis.parentNode.childElementCount <= 3) {
				fadeOut(myThis.parentNode);
			}
			else {
				fadeOut(myThis);
			}
			ajaxEvent = null;
		},
		onError: function(status, message) {
			window.alert('Error ' + status + ': ' + message);
		}
	});
}
