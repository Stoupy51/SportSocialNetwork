<?php declare(strict_types = 1);
require_once "autoload.php";

header('Content-Type: application/json');

$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_GET["idPublication"]) && ctype_digit($_GET["idPublication"]))
{
	$post = ["isOwner"=>false];
	$id = $_GET["idPublication"];
	$myId = $user->getIdUser();
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idPublication
	FROM Publication
	WHERE idPublication = :id
	AND idUser = :idUser
SQL);
	$stmt->execute([":id" => $id, ":idUser" => $myId]);
	if (($line = $stmt->fetch()) !== false) {
		$post["isOwner"] = true;
	}
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idCommentaire, u.idUser, CONCAT(firstName,' ',lastName) as "utilisateur", contenu
	FROM Commentaire c, User u
	WHERE idPublication = :id
	AND u.idUser = c.idUser
	ORDER BY idCommentaire DESC
SQL);
	$comments = [];
	$stmt->execute([":id" => $id]);
	$stmt = $stmt->fetchAll();
	foreach ($stmt as $k) {
		if ($k["idUser"] == $myId)
			$comments[] = ["isOwner"=>true] + $k;
		else
			$comments[] = ["isOwner"=>false] + $k;
	}

	$post["commentaires"] = $comments;
	echo json_encode($post);
}
