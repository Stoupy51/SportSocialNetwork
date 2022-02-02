<?php declare(strict_types = 1);
require_once "autoload.php";

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}
$user = $authentication->getUser();
if ($user->getRole() != 2) {
	http_response_code(403);
    header("Location: .");
    die();
}


$user = User::createFromId($_POST["idUser"]);
$valid = false;

if(isset($_POST["firstname"]) && !empty($_POST["firstname"])){
	$firstName = $_POST["firstname"];
	if($firstName != $user->getFirstName()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET firstName = :firstname
			WHERE idUser = :id
	
		SQL);
	
		$modif->execute([":id"=>$user->getIdUser() , ":firstname"=>$firstName]);
		$valid = true;
	}
}

if(isset($_POST["lastname"]) && !empty($_POST["lastname"])){
	$lastName = $_POST["lastname"];
	if($lastName != $user->getLastName()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET lastName = :lastname
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":lastname"=>$lastName]);
		$valid = true;
	}
}

if(isset($_POST["birth"]) && !empty($_POST["birth"])){
	$birth = $_POST["birth"];
	if($birth  != $user->getBirth()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET birth = :birth
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":birth"=>$birth ]);
		$valid = true;
	}
}

if(isset($_POST["login"]) && !empty($_POST["login"])){
	$login = $_POST["login"];
	if($login  != $user->getLogin()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET login = :login
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":login"=>$login ]);
		$valid = true;
	}
}
if(isset($_POST["bio"]) && !empty($_POST["bio"])){
	$bio = $_POST["bio"];
	if($bio != $user->getBio()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET bio = :bio
			WHERE idUser = :id;
		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":bio"=>$bio ]);
		$valid = true;
	}
}

if(isset($_POST["email"]) && !empty($_POST["email"])){
	$email= $_POST["email"];
	if($email != $user->getEmail()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET email = :email
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":email"=>$email]);
		$valid = true;
	}
}

if(isset($_POST["phone "]) && !empty($_POST["phone "])){
	$phone = $_POST["phone "];
	if($phone != $user->getPhone()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET phone = :phone
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":phone"=>$phone]);
		$valid = true;
	}
}

if(isset($_POST["role"]) && $_POST["role"] >= 0 && $_POST["role"] <= 2) {
	$role = $_POST["role"];
	if($role != $user->getRole()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET role = :role
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":role"=>$role]);
		$valid = true;
	}
}

if(isset($_POST["genre"]) && $_POST["genre"] >= 0 && $_POST["genre"] <= 2) {
	$genre = $_POST["genre"];
	if($genre != $user->getGenre()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET genre = :genre
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":genre"=>$genre]);
		$valid = true;
	}
}

echo $valid;
