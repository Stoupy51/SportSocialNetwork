<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$currentUser = $authentication->getUser();

$idUser = $currentUser->getIdUser();
$other_user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';

$lastName = explode(' ', $other_user_name)[0];
$firstName = explode(' ', $other_user_name)[1];
$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT idUser
    FROM user
    WHERE lastname = :lastName
    AND firstname = :firstName
	AND idUser >= 0
SQL
);
$stmt->execute(array(':lastName' => $lastName, 'firstName' => $firstName));
$other_userId = $stmt->fetchAll()[0]['idUser'];
//Ajout Nouvel Ami
if (isset($other_userId)) {
    $stmt = MyPDO::getInstance()->prepare(<<<SQL
        INSERT INTO avoir_en_ami VALUES (:currentUser, :otherUser)
    SQL
    );
    $stmt->execute(
        array(
        ':currentUser' => $idUser,
        ':otherUser' => $other_userId
        )
    );
}
echo true;


