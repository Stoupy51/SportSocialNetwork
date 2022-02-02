<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (
	isset($_POST["idConv"]) && ctype_digit($_POST["idConv"])
	&& isset($_POST["nomConv"]) && !empty($_POST["nomConv"])
	&& isset($_POST["idImage"]) && ctype_digit($_POST["idImage"])
) {
	$idConv			= $_POST["idConv"];
	$nomConv		= nl2br(htmlspecialchars($_POST["nomConv"]));
	$idImage 		= $_POST["idImage"];
	$addFriends		= $_POST["addFriends"];

	$image =  "";
	if (isset($_FILES["imageConv"]["tmp_name"])) {
		if ($_FILES["imageConv"]["size"] >= 50000000)
			die ("L'image dépasse la taille autorisée (13 Mo)");
		elseif ($_FILES["imageConv"]["tmp_name"] != "")
			$image = base64_encode(file_get_contents($_FILES["imageConv"]["tmp_name"]));
	}
	
	if ($image != "") {
		//Ajout Image
		if ($idImage >= 1) {
			$stmt = MyPDO::getInstance()->prepare(<<<SQL
	UPDATE Image SET content = :contenu
	WHERE idImage = :idImage
SQL);
			$stmt->execute([':idImage'=>$idImage,':contenu'=>$image]);
		}
		else {
			$stmt = MyPDO::getInstance()->prepare(<<<SQL
				SELECT MAX(idImage) as "idImage" FROM Image
		SQL);
			$stmt->execute();
			$idImage = $stmt->fetch()['idImage']+1;
			$stmt = MyPDO::getInstance()->prepare(<<<SQL
				INSERT INTO image VALUES (:idImage,:contenu);
	
				UPDATE Conversation SET idImage = :idImage
				WHERE idConversation = :idConv
		SQL);
			$stmt->execute([':idImage'=>$idImage,':idConv'=>$idConv,':contenu'=>$image]);
		}
		$stmt->closeCursor();
	}

	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	UPDATE Conversation SET nomConversation = :nomConv
	WHERE idConversation = :idConv
	AND idConversation IN ( SELECT idConversation
							FROM Tchatter
							WHERE idUser = :idUser )
SQL);
	$stmt->execute([':idConv'=>$idConv, ':nomConv'=>$nomConv, ':idUser'=>$user->getIdUser()]);

	if (isset($_POST["addFriends"]) && !empty($_POST["addFriends"])) {
		$add = MyPDO::getInstance()->prepare(<<<SQL
		INSERT INTO Tchatter (idUser, idConversation) VALUES (:idUser, :idConversation)
	SQL);
		foreach(explode(",",$_POST["addFriends"]) as $k => $v){
			$add->execute([':idUser'=>$v,':idConversation'=>$idConv]);
		}
	}
	http_response_code(308);
	header("Location: message.php");
	die();
}


















else if (isset($_POST["id"]) && ctype_digit($_POST["id"])) {

$idConv = $_POST["id"];
$page="";
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT nomConversation, idImage FROM Conversation
	WHERE idConversation = :id;
SQL);
$stmt->execute([':id'=>$idConv]);
$line = $stmt->fetch();
$nomConversation = $line['nomConversation'];
$idImage = $line['idImage'];
$imageConv = "";
if ($idImage != NULL) {
	$imageConv = "<img src='getImage.php?id=$idImage'>";
}
else
	$idImage = 0;

$recupFriend = MyPDO::getInstance()->prepare(<<<SQL
    SELECT use_idUser as "id"
    FROM avoir_en_ami
    WHERE idUser = :id
	AND idUser >= 0
	AND use_idUser NOT IN ( SELECT idUser
							FROM Tchatter
							WHERE idConversation = :idConv
							AND idUser >= 0 );
SQL);
$recupFriend->execute([":id"=>$user->getIdUser(), ":idConv"=>$idConv]);
$friends = "<div class='container' style='height: 50vh;'>";
foreach($recupFriend->fetchAll() as $k) {
	$friend = User::createFromId($k['id']);
	$image = $friend->getIdImage();
	$nom = $friend->getName();
	$friends .= <<<HTML
    <div class="flex-fill p-2 border mx-1 my-1" onclick="addFriend(this);" id="{$friend->getIdUser()}">
        <img src="getImage.php?id=$image" width="70" height="70" title="$nom" style="object-fit: cover;"> $nom
    </div>
HTML;
}

$page .= <<<HTML
<form id="modifierConv" method="POST" enctype="multipart/form-data" action="ajax_infoConv.php" class="d-flex border flex-column h-100 w-100 m-auto">
    <div name="part1" class="d-flex flex-column flex-fill border p-2">
		<div class="profile mb-3">
			Image de la conversation :
			<input type="file" name="imageConv" accept="image/png, image/jpeg, image/jpg">
			$imageConv
        </div>
    </div>
    <div name="part2" class="d-flex border flex-column flex-fill">
        $friends
		</div>
		<input hidden name="addFriends" id="addFriends" type="text" value="">
		<input hidden name="idImage" type="text" value="$idImage">
		<input hidden name="idConv" type="text" placeholder="$nomConversation" value="$idConv">
		<input name="nomConv" type="text" placeholder="$nomConversation" value="$nomConversation">
		<button type="submit" onclick="ajouterAmiConv()">Valider</button>
		<button onclick="document.getElementById('div1').innerHTML = '';">Annuler</button>
    </div>
</form>

HTML;

echo $page;
}
