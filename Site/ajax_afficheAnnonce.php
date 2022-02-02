<?php declare(strict_types = 1);
require_once "autoload.php";

header('Content-Type: application/json');

$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_GET["idAnnonce"]) && ctype_digit($_GET["idAnnonce"]))
{
	$post = ["isOwner"=>false];
	$id = $_GET["idAnnonce"];
	$myId = $user->getIdUser();
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idAnnonce
	FROM Annonce
	WHERE idAnnonce = :id
	AND idUser = :idUser
SQL);
	$stmt->execute([":id" => $id, ":idUser" => $myId]);
	if (($line = $stmt->fetch()) !== false) {
		$post["isOwner"] = true;
	}

	echo json_encode($post);
}
