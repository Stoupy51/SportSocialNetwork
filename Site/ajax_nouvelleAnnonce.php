<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["titre"]) && isset($_POST["description"]))
{
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT MAX(idAnnonce) as "id"
    FROM Annonce
SQL);
    $stmt->execute();
    $NextId = $stmt->fetch()['id']+1;

	$idUser = $user->getIdUser();
	$NextIdImage = null;
	if (isset($_POST["image"]) && $_POST["image"] != "undefined") {
		$image = explode(",",$_POST["image"]);
		$image = $image[1];
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT MAX(idImage) as "id"
	FROM image
SQL);
		$stmt->execute();
		$NextIdImage = $stmt->fetch()['id']+1;
		$newImage = MyPDO::getInstance()->prepare(<<<SQL
	INSERT INTO image VALUES (:NextIdImage, :content)
SQL);
		$newImage->execute([':NextIdImage'=>$NextIdImage,':content'=>$image]);
	}
	$titre = nl2br(htmlspecialchars($_POST["titre"]));
	$description = nl2br(htmlspecialchars($_POST["description"]));

	$newAnnonce = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Annonce VALUES (:idAnnonce, :idUser, :idImage, :titre, :description, SYSDATE())
SQL);
	$newAnnonce->execute([
		':idAnnonce'=>$NextId,
        ':idUser'=>$idUser,
		':idImage'=>$NextIdImage,
		':titre'=>$titre,
		':description'=>$description,
	]);

    echo true;
}
