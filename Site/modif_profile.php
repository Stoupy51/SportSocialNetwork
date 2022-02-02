<?php declare(strict_types=1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();

if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}

$p = new WebPage("Page de Profil");
$user = $authentication->getUser();
$p->appendCssUrl("css/main.css");
$p->appendCssUrl("css/modif_profile.css");
$p->appendCssUrl("css/profil.css");
$p->appendJsUrl("js/main.js");
$p->appendJsUrl("js/ajaxrequest.js");

$p->appendToHead(<<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
HTML
);

// Requête SQL : 

// ---------------  --------------- 

$valid = false;

if(isset($_POST["coords"]) && !empty($_POST["coords"])){
    $coords = $_POST["coords"];
    $modif = MyPDO::getInstance()->prepare(<<<SQL
        UPDATE User
        SET coordonnees = :coords
        WHERE idUser = :id
    
        SQL);
    $modif->execute([":id"=>$user->getIdUser() , ":coords"=>$coords]);
    $valid = true;
}

if(isset($_POST["ville"]) && !empty($_POST["ville"])){
	$ville= $_POST["ville"];
	if($ville!= $user->getVille()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET ville = :ville
			WHERE idUser = :id
	
		SQL);
	
		$modif->execute([":id"=>$user->getIdUser() , ":ville"=>$ville]);
		$valid = true;
	}
}

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

if(isset($_POST["genre"]) && $_POST["genre"] >= 0 && $_POST["genre"] <= 2){
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

$banniere =  "";
if (isset($_FILES["banniere"]["tmp_name"])) {
	if ($_FILES["banniere"]["size"] >= 50000000)
		die ("L'image dépasse la taille autorisée (13 Mo)");
	elseif ($_FILES["banniere"]["tmp_name"] != "")
		$banniere = base64_encode(file_get_contents($_FILES["banniere"]["tmp_name"]));
}
if ($banniere != "") {
	if($banniere != $user->getBanniere()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET banniere = :banniere
			WHERE idUser = :id;

		SQL);
		$modif->execute([":id"=>$user->getIdUser() , ":banniere"=>$banniere]);
		$valid = true;
	}
}

if(isset($_POST["idImage"]) && !empty($_POST["idImage"])){
	$idImage= $_POST["idImage"];
	if($idImage != $user->getidImage()){
		$modif = MyPDO::getInstance()->prepare(<<<SQL
			UPDATE User
			SET idImage = :idImage
			WHERE idUser = :id;


		SQL);

		$modif->execute([":id"=>$user->getIdUser() , ":idImage" =>$idImage]);
		$valid = true;
	}
}

$idImage = $user->getIdImage();
$image =  "";
if (isset($_FILES["photoDeProfil"]["tmp_name"])) {
    if ($_FILES["photoDeProfil"]["size"] >= 50000000)
        die ("L'image dépasse la taille autorisée (13 Mo)");
    elseif ($_FILES["photoDeProfil"]["tmp_name"] != "")
        $image = base64_encode(file_get_contents($_FILES["photoDeProfil"]["tmp_name"]));
}

if ($image != "") {
	//Ajout Image
	if ($idImage != 0) {
			$stmt = MyPDO::getInstance()->prepare(<<<SQL
				UPDATE Image SET content = :contenu
				WHERE idImage = :idImage
	SQL);
			$stmt->execute([':idImage'=>$idImage,':contenu'=>$image]);
	}
	else {
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
			SELECT MAX(idImage) as "idImage" FROM Image
	SQL);
		$stmt->execute();
		$idImage = $stmt->fetch()['idImage']+1;
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
			INSERT INTO image VALUES (:idImage,:contenu);

			UPDATE User SET idImage = :idImage
			WHERE idUser = :idUser
	SQL);
		$stmt->execute([':idImage'=>$idImage,':idUser'=>$user->getIdUser(),':contenu'=>$image]);
	}
	$stmt->closeCursor();
	$valid = true;
}

if($valid){
	$authentication->setUserFromRegister($user->getIdUser());
	http_response_code(308);
	header("Location: profil.php");
	die();
}

$lastName = $user->getLastName();
$firstName = $user->getFirstName();
$birth = $user->getBirth();
$bio = $user->getBio();
$email = $user->getEmail();
$phone = $user->getPhone();
$genre = $user->getGenre();
$banniere = $user->getBanniere();
$ville = $user->getVille();

$genreCheck1 = $genreCheck2 = $genreCheck3 = "";
switch ($genre) {
	case 0:
		$genreCheck1 = "checked";
		break;
	case 1:
		$genreCheck2 = "checked";
		break;
	case 2:
		$genreCheck3 = "checked";
		break;
}

$Banniere = <<<HTML
		<img src="data:image;base64, {$banniere}" class="banniere" style="height: 275px; z-index:-1; object-fit: cover; filter: blur(50px);">
		<img src="data:image;base64, {$banniere}" class="banniere" style="height: 275px; z-index:-1; object-fit: cover;">
HTML;
$header = SportUtilities::getHeaderPage($user);

$p->appendContent(<<<HTML
$header

<datalist id ="adresses">
	<option value="Reims">
</datalist>

<div class="pt-4 formModifProfile d-flex flex-column justify-content-between align-items-center flex-row flex-grow-1">
	<form method="POST" enctype="multipart/form-data" class="justify-content-center align-items-center flex-grow-1" id="modifProfile" name="modifProfile" action="modif_profile.php" style="display: initial; z-index:1;">
		$Banniere
		<input type="file" id="banniere" name="banniere" accept="image/png, image/jpeg, image/jpg" class="align-items-center justify-content-center" style="color: white;">

		<div class="profile mx-auto flex-grow-1" style="z-index:1; margin-top:0;">
			<img src="getImage.php?id={$user->getIdImage()}">
		</div>

		<input type="file" id="photoDeProfil" name="photoDeProfil" accept="image/png, image/jpeg, image/jpg" class="align-items-center">

		<div class="d-flex flex-row align-items-around justify-content-around p-3 textForm">
			<div class="text button d-flex flex-column align-items-center p-3">
				Nom :
				<label class="blanc button px-2">
					<input name="lastname" type="text" value="{$lastName}" required>
				</label>
			</div>
			<div class="text button d-flex flex-column align-items-center p-3">
				Prénom
				<label class="blanc button px-2">
					<input name="firstname" type="text" value="{$firstName}" required>
				</label>
			</div>
		</div>
		<div class="d-flex flex-row justify-content-center textForm">
			<div class="text button d-flex flex-column align-items-center p-3">
				Date de Naissance
				<label class="blanc button px-2">
					<input name="birth" type="date" value="{$birth}" required>
				</label>
			</div>
			<div class="text button d-flex flex-column align-items-center p-3">
				Mot de passe
				<label class="blanc button px-2">
					<input name="password" type="password" required>
				</label>
			</div>
		</div>
		<div class="d-flex flex-row textForm">
			<div class="text button d-flex flex-column  p-3">
				Biographie
				<label class="blanc button px-2 align-top">
					<input name="bio" type="text" value="{$bio}" style="height: 170px;" required>
				</label>
			</div>
			<div class="text button d-flex flex-column align-items-center p-3">
				E-mail
				<label class="blanc button px-2">
					<input name="email" type="mail" value="{$email}" required>
				</label>
				<div class="d-flex flex-row justify-content-center textForm">
					<div class="text button d-flex flex-column align-items-center p-3">
						Num de téléphone
						<label class="blanc button px-2">
							<input placeholder="Ex: 0654254789" name="phone" type="tel" pattern="[0-9]{10}" value="{$phone}" required>
						</label>
					</div>
				</div>
			</div>
		</div>


		<div class="d-flex flex-row align-items-around justify-content-around p-3 textForm">
			<div class="text button d-flex flex-column align-items-center p-4">
					Ville
				<label class="blanc button px-2">
					<input name="ville" type="text" value="{$ville}" required>
				</label>
			</div>
			<div class="text button d-flex flex-column align-items-center p-4">
				Adresse
				<label class="blanc button px-2 ">
					<input type="text" id="inputAdresse" list="adresses" placeholder="Ne pas mettre votre habitat" onkeyup="getCoords();">	
					<!-- Recup les coordonnees -->
					<input hidden name="coords" id="coords-event">
				</label>
			</div>
		</div>


		<div class="text button d-flex flex-column align-items-center p-3 textForm">
			Genre
			<div class="d-flex flex-row">
				<label class="p-2">
					<input id="genre" name="genre" type="radio" value="0" $genreCheck1>
					Homme
				</label>
				<label class="p-2">
					<input id="genre" name="genre" type="radio" value="1" $genreCheck2>
					Femme
				</label>
				<label class="p-2">
					<input id="genre" name="genre" type="radio" value="2" $genreCheck3>
					Autres
				</label>
			</div>
		</div>
		<div class="register2 button d-flex flex-column align-items-center p-3 textForm">
			<button class="Inscriptionvalider p-1 align-items-center p-3" type="submit">Mettre à jour</button>
		</div>
	</form>
</div>
HTML);

echo $p->toHTML();