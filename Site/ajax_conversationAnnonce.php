<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["id"]) && ctype_digit($_POST["id"])) {
	$annonce = Annonce::createFromId($_POST["id"]);

    $stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT MAX(idConversation) as "id"
    FROM Conversation
SQL);
    $stmt->execute();
    $NextId = $stmt->fetch()['id']+1;

    $newConv = MyPDO::getInstance()->prepare(<<<SQL
        INSERT INTO conversation (idConversation, nomConversation, idImage) VALUES (:idConversation, :texte, :idImage)
SQL);
    $newConv->execute([
		':texte'=>$user->getName().", ".$annonce->getTitre(),
		':idConversation'=>$NextId,
		':idImage'=>$annonce->getIdImage()
	]);

    $add = MyPDO::getInstance()->prepare(<<<SQL
    INSERT INTO Tchatter (idUser, idConversation) VALUES (:idUser, :idConversation);
	INSERT INTO message (idUser, idConversation, texte, dateEnvoi) VALUES (:idUser, :idConversation, :message, SYSDATE());
    INSERT INTO Tchatter (idUser, idConversation) VALUES (:idUserAnnonceur, :idConversation);

SQL);
	$add->execute([
		':idUser'=>$user->getIdUser(),
		':idUserAnnonceur'=>$annonce->getIdUser(),
		':idConversation'=>$NextId,
		':message'=>"Bonjour, je souhaite discuter avec vous de votre annonce '".$annonce->getTitre()."'"
	]);

	echo true;
}

