<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["id"])) {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
SELECT idPublication, idImage FROM Publication WHERE idPublication = :idPublication
SQL);
	$stmt->execute([':idPublication'=>$_POST["id"]]);
	if (($line = $stmt->fetch()) !== false) {
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Commentaire
WHERE idPublication = :idPublication;

DELETE FROM Evenement
WHERE idPublication = :idPublication;

DELETE FROM Publication
WHERE idPublication = :idPublication;

DELETE FROM Image
WHERE idImage = :idImage;
SQL);
		$stmt->execute([':idUser'=>$user->getIdUser(),':idPublication'=>$_POST["id"],':idImage'=>$line['idImage']]);
		echo true;
	}
	else
		echo false;
}
