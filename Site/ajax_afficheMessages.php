<?php declare(strict_types = 1);
require_once "autoload.php";

$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if(isset($_POST["idConversation"])){ try {
	$conversation = new Conversation($_POST["idConversation"]);
    $messages = $conversation->getMessages();
	$page = "";
    $lastUserMessage = "";
    foreach($messages as $k => $v){
        $texte = $v['texte'];
        $newtext = wordwrap($texte, 50, "<br />\r\n", true);

        $dateEnvoi = $v['dateEnvoi'];
        $dateEnvoi = explode(" ",$dateEnvoi)[1]; // $dateEnvoi = 15:18

        $userMessage = $v['user'];

        $userImage = $userMessage->getIdImage();
		
        if($userMessage->getIdUser() == $user->getIdUser()) {
            $page .= <<<HTML
                <div class="d-flex flex-row align-items-center">
                    <div class="border myMessage p-2">
                        <p id="message">$newtext</p>
                    </div>
                    <p id="date">$dateEnvoi</p>
                </div>

HTML;
            $lastUserMessage = $userMessage;
        }

		else {
            if($lastUserMessage == $userMessage){
                $page .= <<<HTML
                    <div class="border otherMessage p-2 sameUserMessage">
                        <p id="message">$newtext</p>
                    </div>

HTML;
            } else {
                $page .= <<<HTML
                <div class="d-flex flex-column">
                    <div class="d-flex flex-row align-items-end">
                        <p id="message">{$userMessage->getName()}</p>
                        <p id="date"> : $dateEnvoi</p>
                    </div>
    
                    <div class="d-flex flex-row align-items-center">
                        <img src="getImage.php?id=$userImage" class="imageUserMessage">
                        <div class="border otherMessage p-2">
                            <p id="message">$newtext</p>
                        </div>
                    </div>
                </div>
HTML;
                $lastUserMessage = $userMessage;
                
            }
        }
    }
	echo <<<HTML
<section id="section" class="container">
	$page
</section>
<div id="sendMessage" class="mx-auto p-2 messageForm">
	<form method="POST" id="messageForm" style="display:inline;" class="d-flex align-items-center">
		<input type="text" name="message" id="inputText" placeholder="Ecrire..." class="ecrire p-2 mr-2" style="visibility: visible;">
		<input type="image" id="mySubmit" class="envoyer" src="img/icons/send.png" style="visibility: visible;">
	</form>
</div>
HTML;
}

catch (Exception) { echo "Conversation supprimée"; }
}
