<?php declare(strict_types=1);
require_once "autoload.php";

if (isset($_POST["email"]) && isset($_POST["login"]) && isset($_POST["password"]) && isset($_POST["passwordConf"]) && isset($_POST["lastname"]) && isset($_POST["firstname"])  && isset($_POST["genre"]) && isset($_POST["birth"]) && isset($_POST["ville"])) {
$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT MAX(idUser) as "id"
    FROM User
SQL);
$stmt->execute();
$NextId = $stmt->fetch()['id']+1;
$login = htmlspecialchars($_POST["login"]);
$email = $_POST["email"];
$lastname = $_POST["lastname"];
$firstname = $_POST["firstname"];
$phone = $_POST["phone"];
$password = $_POST["password"];
$ville = $_POST["ville"];
$renseignements = [
	':idUser'=>$NextId,
	':login'=>$login,
	':password'=>$password,
	':genre'=>$_POST["genre"],
	':email'=>$email,
	':lastname'=>$lastname,
	':firstname'=>$firstname,
	':birth'=>$_POST["birth"],
	':phone'=>$phone,
	':ville'=>$ville,
	':sports'=>"",
	':abilities'=>""
];
$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT idUser
    FROM User
    WHERE login = :login
	OR email = :email
	OR CONCAT(lastname,firstname) = CONCAT(:lastname,:firstname)
	OR phone = :phone
SQL);
$stmt->execute([':login'=>$login,':email'=>$email,':lastname'=>$lastname,':firstname'=>$firstname,':phone'=>$phone]);
$valide = true;
if ($stmt->fetch() !== false)
    $valide = false;

if ($password == $_POST["passwordConf"] && $valide != false) {
    $stmt = MyPDO::getInstance()->prepare(<<<SQL
        INSERT INTO User (idUser, login, password, role, genre, email, lastname, firstname, birth, phone, sports, abilities, ville) VALUES(:idUser, :login, :password, 0, :genre, :email, :lastname, :firstname, :birth, :phone, :sports, :abilities, :ville)
SQL);
    $stmt->execute($renseignements);
	$authentication = new SecureUserAuthentication();
	$authentication->setUserFromRegister($NextId);
	echo "Inscription bien effectuée";
}
else {
	if ($valide == false)
    	echo "Certaines infos sont déjà utilisées";
	else
		echo "Le mot de passe ne correspond pas au mot de passe répété";
}
}

// Réception de 'error' en GET
if (isset($_GET['error'])) {
    header('HTTP/1.1 400 Bad Request', true, 400);
    echo "Code erreur OK après réception de '{$_GET['error']}'";
    return;
}
