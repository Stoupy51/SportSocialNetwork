<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["title"]) && isset($_POST["link"]))
{
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT MAX(idVideo) as "id"
    FROM Video
SQL);
    $stmt->execute();
    $NextId = $stmt->fetch()['id']+1;

	$idUser = $user->getIdUser();

    $title = $_POST["title"];
    $link = $_POST["link"];

	$newPublication = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO video VALUES (:idVideo, :idUser, :titre, SYSDATE(), :lien)
SQL);
	$newPublication->execute([
		':idVideo'=>$NextId,
		':idUser'=>$idUser,
		':titre'=>$title,
		':lien'=>$link
	]);
    echo true;
}
