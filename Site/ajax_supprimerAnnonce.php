<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["id"])) {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
SELECT idAnnonce, idImage FROM Annonce WHERE idAnnonce = :idAnnonce AND idUser = :idUser
SQL);
	$stmt->execute([':idUser'=>$user->getIdUser(),':idAnnonce'=>$_POST["id"]]);
	if (($line = $stmt->fetch()) !== false) {
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Annonce
WHERE idAnnonce = :idAnnonce;

DELETE FROM Image
WHERE idImage = :idImage
SQL);
		$stmt->execute([':idAnnonce'=>$_POST["id"],':idImage'=>$line['idImage']]);
		echo true;
	}
	else
		echo false;
}
