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
SQL
);
$stmt->execute(array(':lastName' => $lastName, 'firstName' => $firstName));
$other_userId = $stmt->fetchAll()[0]['idUser'];


//Charger les amis de l'utilisateur
if (isset($other_userId)) {
    $stmt = MyPDO::getInstance()->prepare(<<<SQL
        SELECT *
        FROM avoir_en_ami
        WHERE iduser = :currentUser 
        AND use_iduser = :otherUser
    SQL
    );
    $stmt->execute(
        array(
        ':currentUser' => $idUser,
        ':otherUser' => $other_userId
        )
    );
    if ($stmt->fetch() != false) {
        echo true;
    }
}


