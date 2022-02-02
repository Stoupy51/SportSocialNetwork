//Peut-etre le récup sur données de session?
window.addEventListener("load",function() {
	user = document.getElementById("hiddenUser").value;
	idImageUser = document.getElementById("hiddenIdImage").value;
	document.getElementById("newPostIcon").onclick = function () { newPost();};
});

var ajax = null;
var ajaxConv = null;
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

/////////////////////////////////////////////////////////////
// Fonction créant le formulaire de création d'une annonce //
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

    //Dépôt de l'image du poste
    let dropRegion = document.createElement('div');
    dropRegion.id = "drop-region";
    dropRegion.innerHTML = '<h2 id="drop-file-text">Cliquer ici pour déposer votre image !</h2> <div class="drop-message m-5"> <svg version="1.1" id="add-image" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="56.513px" height="56.513px" viewBox="0 0 316.513 316.513" style="enable-background: new 0 0 316.513 316.513" xml:space="preserve"> <defs> <linearGradient id="gradient"> <stop offset="0%" stop-color="rgba(255,162,109,1)" /> <stop offset="10%" stop-color="rgba(255,162,109,1)" /> <stop offset="37%" stop-color="rgba(223,211,34,1)" /> <stop offset="102%" stop-color="rgba(226,161,31,1)" /> </linearGradient> </defs> <g> <path fill="url(#gradient)" d="M158.253,0C71,0,0.012,71.001,0.012,158.263c0,87.256,70.989,158.25,158.241,158.25 c87.259,0,158.248-70.994,158.248-158.25C316.501,71.001,245.518,0,158.253,0z M230.891,177.982h-57.748v52.914 c0,7.428-4.864,13.438-12.301,13.438c-7.422,0-12.298-6.017-12.298-13.438v-52.914H85.634c-7.44,0-13.454-4.864-13.454-12.298 s6.014-12.301,13.454-12.301h62.909V85.616c0-7.434,4.876-13.453,12.298-13.453c7.437,0,12.301,6.02,12.301,13.453v67.768h57.748 c7.439,0,13.456,4.867,13.456,12.301S238.33,177.982,230.891,177.982z" /> </g> </svg> </div> <div id="image-preview"></div>';

    //Séparateur 
    let seperator = document.createElement('hr');
    seperator.classList.add('sep', 'sep-form');
    
    // Champ de saisie du titre du poste
    let inputTitlePost = document.createElement('div');
    inputTitlePost.innerHTML = '<label> <h2 id="changeEvent">Titre de votre annonce</h2> </label> <div class="input-form-wrapper"> <input type="text" name="post-headline" required /> </div>';
    inputTitlePost.innerHTML += '<label class="mt-3"> <h2 id="changeEvent">Description de votre annonce</h2> </label> <div class="input-form-wrapper"> <textarea class="w-75" type="text" name="post-desc" required></textarea> </div>';
    inputTitlePost.className = "post-title-wrapper";

    // Champ de saisie du message
    let inputMessagePost = document.createElement('div');
    inputMessagePost.innerHTML = '<label> <h2>Description de votre annonce</h2> </label> <div class="input-form-wrapper"> <input type="text" name="post-message" /> </div>';
    inputMessagePost.className = "event-message-wrapper post-title-wrapper";

    //Boutton validation de la création du poste
    let button = document.createElement('button');
    button.classList.add('btn', 'p-1');
    button.innerText = 'Submit Annonce';
    button.setAttribute('type', 'submit');
    button.setAttribute('id', 'submit-post');

    // Assemblage du poste
    form.append(cardHeader);
    form.append(inputTitlePost);

	inputMessagePost.style.display = 'none';
    form.append(inputMessagePost);
    form.append(dropRegion);

    form.append(seperator);
    
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
    }

    //closing the overlay when clicking outside of the post form box
    overlay.addEventListener("click", function (evt) {
        if (overlay !== evt.target) return;
        closeOverlay();
    });
    closeBtn.addEventListener("click", function () {
        closeOverlay();
    });
    document.forms['formPost'].onsubmit = function() {
		console.log("submit");
		new AjaxRequest({
			url: "ajax_nouvelleAnnonce.php",
			method: 'post',
			parameters: {
				titre: document.forms['formPost'].elements['post-headline'].value,
				description: document.forms['formPost'].elements['post-desc'].value,
				image: imageData,
				isEvent: 0
			},
			onSuccess: function(res) {
				console.log(res);
				if (res) {
					window.location.href = "achat.php";
				}
			},
			onError: function(status, message) {
				alert(status, message);
			}
		});
		return false;
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
            url: "create_annonce.php",
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

function nouvelleConvAnnonce(id) {
    if (ajaxConv != null)
		ajaxConv.cancel();
	ajaxConv = new AjaxRequest({
        url: 'ajax_conversationAnnonce.php',
        method: 'post',
        parameters: {
			id: id
		},
        onSuccess: function(res) {
			console.log(res);
			if (res) {
            	window.location.href = "message.php";
			}
			ajaxConv = null;
        },
        onError: function(status, message) {
            window.alert('Error : ' + status + ":" + message)
        }
    });
}
