<?php declare(strict_types = 1);
require_once "autoload.php";

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}
$user = $authentication->getUser();
if ($user->getRole() != 2) {
	http_response_code(403);
    header("Location: .");
    die();
}


$stmt = MyPDO::getInstance()->prepare(<<<SQL

UPDATE Message SET idUser = idUser * -1
WHERE idUser = :id;

UPDATE User SET idUser = idUser * -1
WHERE idUser = :id;

UPDATE avoir_en_ami SET idUser = idUser * -1
WHERE idUser = :id;

UPDATE avoir_en_ami SET use_idUser = use_idUser * -1
WHERE use_idUser = :id;

SQL);
$stmt->execute([":id"=>$_POST["idUser"]]);

echo true;
