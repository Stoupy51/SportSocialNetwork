<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}

$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();
$idUser = $user->getIdUser();
$firstName = $user->getFirstName();
$lastName = $user->getLastName();
$header = SportUtilities::getHeaderPage($user);

//Requêtes SQL
$recupUser = MyPDO::getInstance()->prepare(<<<SQL
            SELECT c.idConversation as "idConversation"
            FROM conversation c
            JOIN tchatter t ON (c.idConversation = t.idConversation)
            WHERE t.idUser = $idUser
			AND idUser >= 0;
SQL);
$recupUser->execute();
$conversations = "";
foreach($recupUser->fetchAll() as $k){
    $conversation = new Conversation($k['idConversation']);
    $image = $conversation->getIdImage();
    $nom = $conversation->getNomConversation();
	$convID = $conversation->getIdConversation();
    $conversations .= <<<HTML
        <div class="d-flex flex-row align-items-center z-index-1 p-2 recConv" onclick="clickConversation(this);" id="$convID">
            <div class="d-flex mr-auto align-items-center">
                <img src="getImage.php?id=$image" width="70" height="70" title="$nom" style="object-fit: cover; border-radius: 50%; border: 2px solid #151728; margin-right: 5px;">$nom
            </div>
            <div class="d-flex flex-column">
                <div class="d-flex align-item-start justify-content-right mb-5">
                    <img id="$convID" src="img/icons/close.png" width="15" height="15" class="z-index-2 close" onclick="leaveConversation(this.id);">
                </div>
                <div class="d-flex align-item-end justify-content-right">
                    <img id="$convID" src="img/icons/Hamburger_icon.png" width="15" height="15" class="info" onclick="infoConversation(this.id);">
                </div>
            </div>
        </div>
HTML;
}
//Fin Requêtes SQL




//Création de la page
$page = new WebPage("Messages Privés");
$page->appendJsUrl("https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js");
$page->appendJsUrl("js/ajaxrequest.js");
$page->appendJsUrl("js/message.js");
$page->appendCssUrl("css/main.css");
$page->appendCssUrl("css/message.css");
$page->appendToHead(<<<HTML
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
HTML);

$page->appendContent(<<<HTML

$header



<div class="d-flex justify-content-center flex-row">
	<div class="d-flex flex-row w-75">
		<div class="d-flex flex-column w-25 blockconv" style="height:90%;">
			<div class="d-flex borderbot p-2 flex-row">
				<div class="flex-fill p-2 mx-1 my-1">
					<p class="text-center">$firstName $lastName</p>
				</div>
				<div class="flex-fill p-2 mx-1 my-1" style="text-align: center;" onclick="nouvelleConversation();">
					<img src="img/editMessage.png" width="20" height="20" title="Nouveau message">
				</div>
			</div>

			<div class="d-flex float flex-column container" style="overflow-y: scroll; height:75vh; padding-left: 0px; padding-right: 0px">
                <section>$conversations</section>
            </div>
        </div>

        <div class="d-flex flex-grow-1">
            <div id="div1" class="d-flex flex-grow-1 flex-column flex-fill p-2 blockmessage">
				<section id="section" class="container"></section>
				<div hidden id="sendMessage" class="mx-auto p-2 messageForm">
					<form method="POST" id="messageForm" style="display:inline;" class="d-flex align-items-center">
						<input type="text" name="message" id="inputText" placeholder="Ecrire..." class="ecrire p-2 mr-2" style="visibility: visible;">
						<input type="image" id="mySubmit" class="envoyer" src="img/icons/send.png" style="visibility: visible;">
					</form>
				</div>
            </div>
        </div>
    </div>
</div>
HTML);

echo $page->toHTML();
