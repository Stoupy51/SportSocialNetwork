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
SELECT idEvenement
FROM Evenement
WHERE idEvenement = :idEvent
AND idPublication IN (
	SELECT idPublication
	FROM Publication
	WHERE idUser = :idUser
)
SQL);
$stmt->execute([":idUser" => $user->getIdUser(), ":idEvent" => $_GET['idEvent']]);

if ($stmt->fetch() !== false) {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
UPDATE Identifier2 SET demandeAccept = 1
WHERE idEvenement = :idEvent
AND idUser = :idUser
SQL);
	$stmt->execute([":idUser" => $_GET['idUser'], ":idEvent" => $_GET['idEvent']]);
		
	echo true;	
}
