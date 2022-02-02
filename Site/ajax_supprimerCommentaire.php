<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["id"]))
{
    $stmt = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Commentaire
WHERE idCommentaire = :idCommentaire
AND idUser = :idUser
SQL);
    $stmt->execute([':idUser'=>$user->getIdUser(),':idCommentaire'=>$_POST["id"]]);
	echo true;
}
