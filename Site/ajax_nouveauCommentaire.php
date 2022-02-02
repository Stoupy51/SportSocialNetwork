<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["commentaire"]) && !empty($_POST["commentaire"]) && isset($_POST["idPublication"]) && !empty($_POST["idPublication"]))
{
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT MAX(idCommentaire) as "id"
    FROM Commentaire
SQL);
    $stmt->execute();
    $NextId = $stmt->fetch()['id']+1;

	$commentaire = nl2br(htmlspecialchars($_POST["commentaire"]));
	$newPublication = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO commentaire VALUES (:idCommentaire, :idPublication, :idUser, :message)
SQL);
	$newPublication->execute([
		':idCommentaire'=>$NextId,
		':idPublication'=>$_POST["idPublication"],
		':idUser'=>$user->getIdUser(),
		':message'=>$commentaire
	]);
    echo true;
}
