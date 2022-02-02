<?php declare(strict_types = 1);
require_once "autoload.php";

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}
$user = $authentication->getUser();

$stmt = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Identifier2 VALUES (:idEvent, :idUser, 0)
SQL);
$stmt->execute([":idUser" => $user->getIdUser(), ":idEvent" => $_GET['id']]);
	
echo true;
