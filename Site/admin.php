<?php declare(strict_types=1);
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
$p = new WebPage("Page d'Administrateur");
$p->appendJsUrl("js/ajaxrequest.js");
$p->appendJsUrl("js/publication_overlay.js");
$p->appendJsUrl("js/admin.js");
$p->appendCssUrl("https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&display=swap");
$p->appendCssUrl("https://fonts.googleapis.com/css2?family=Roboto&display=swap");
$p->appendCssUrl("css/main.css");
$p->appendCssUrl("css/admin.css");
$p->appendToHead(<<<HTML
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
HTML);

$header = SportUtilities::getHeaderPage($user);

$search = $_GET["search"] ?? "";
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idUser FROM User
	WHERE (login LIKE :search
	OR lastName LIKE :search
	OR firstName LIKE :search
	OR CONCAT(firstName," ",lastName) LIKE :search)
	AND idUser >= 0
SQL);
$stmt->execute([":search" => "%".nl2br(htmlspecialchars($search))."%"]);

$users = "";
foreach($stmt->fetchAll() as $fetch) {
	$k = User::createFromId($fetch["idUser"]);
	$banniere = "";
	if (($b = $k->getBanniere()) != "") {
		$banniere = "<img src='data:image;base64, $b' style='margin-right: 5px; margin-left: 1px; height: 24px; object-fit: cover;'>";
	}
	$users .= <<<HTML
<div id="{$k->getIdUser()}" class="d-flex flex-row align-items-center admin-user p-2">
	<img id="{$k->getIdImage()}" name="idImage" src="getImage.php?id={$k->getIdImage()}" class="pp">
	<input class="login" name="login" type="text" value="{$k->getLogin()}">
	Role<input class="role" name="role" type="text" value="{$k->getRole()}" style="width: 5px;">
	Genre<input class="genre" name="genre" type="text" value="{$k->getGenre()}" style="width: 5px;">
	<input class="email" name="email" type="text" value="{$k->getEmail()}">
	<input class="lastname" name="lastname" type="text" value="{$k->getLastName()}">
	<input class="firstname" name="firstname" type="text" value="{$k->getFirstName()}">
	<input class="birth" name="birth" type="date" value="{$k->getBirth()}" style="width: 100%;">
	<input class="phone" name="phone" type="tel" pattern="[0-9]{10}" value="{$k->getPhone()}">
	$banniere
	Bio<input class="bio" name="bio" type="text" value="{$k->getBio()}">
	<button name="editUser" style="background-color:green;" onclick="updateUser(this.parentElement);">Modifier</button>
	<button name="deleteUser" style="margin-left: 5px; background-color:red;" onclick="deleteUser(this.parentElement);">Supprimer</button>
</div>
HTML;
}

//Nombre d'utilisateurs
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idUser) as "count"
	FROM user
	WHERE role != 2;
SQL);
$stmt->execute();
$nbUsers = $stmt->fetch()["count"];
//Nombre de sportifs
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idUser) as "count"
	FROM user
	WHERE role = 0;
SQL);
$stmt->execute();
$nbAthletes = $stmt->fetch()["count"];
//Nombre de coach
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idUser) as "count"
	FROM user
	WHERE role = 1;
SQL);
$stmt->execute();
$nbCoachs = $stmt->fetch()["count"];
//Nombre de publication
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idPublication) as "count"
	FROM publication
	WHERE isEvent = 0;
SQL);
$stmt->execute();
$nbPublications = $stmt->fetch()["count"];
//Nombre d'événements
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idPublication) as "count"
	FROM publication
	WHERE isEvent = 1;
SQL);
$stmt->execute();
$nbEvents = $stmt->fetch()["count"];
//Nombre de messages
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idMessage) as "count"
	FROM message;
SQL);
$stmt->execute();
$nbMessages = $stmt->fetch()["count"];
//Nombre de conversations
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idCommentaire) as "count"
	FROM commentaire;
SQL);
$stmt->execute();
$nbComments = $stmt->fetch()["count"];
//Nombre de commentaires
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idConversation) as "count"
	FROM conversation;
SQL);
$stmt->execute();
$nbConversations = $stmt->fetch()["count"];
//////////////////////////////////////////////////////////////////////////////////////////////

$p->appendContent(<<<HTML
<div class="overlay" id="overlay">
	<button class="btn close-overlay"><img src="./img/icons/close.png"></button>
</div>
$header
<div class="d-flex flex-column justify-content-center align-items-center pb-5">
	<div id="categorieUtilisateurs" class="m-3 d-flex flex-column w-75 categories">
		<div style="position: absolute; width:75%; height:113px;" onclick="fade(this.parentElement.children[2]);"></div>
		<span class="mx-auto">Utilisateurs</span>
		<div hidden id="listeUtilisateurs" class="scroll" style="height: 0px;">
			<hr style="border-top: 2px solid orange; margin:0; position: absolute; width: 74.9%;">
			$users
		</div>
	</div>
	<div id="categorieActivites" class="m-3 d-flex flex-column w-75 categories" onclick="fadeActivity();">
		<span class="mx-auto">Activités</span>
	</div>
	<div id="categorieStatistiques" class="m-3 d-flex flex-column w-75 categories" onclick="fade(this.childNodes[3]);">
		<span class="mx-auto">Statistiques</span>
		<div hidden id="listeStatistiques" class="scroll invisible_scroll" style="height: 0px;">
			<hr style="border-top: 2px solid orange; margin:0; position: absolute; width: 74.9%;">
			<div class="d-flex flex-row statistiques pt-3 px-3 pr-5">
				<p>Nombre d'utilisateur :</p>
				<p class="ml-auto">{$nbUsers}</p>
			</div>
			<div class="d-flex flex-row statistiques px-3 pr-5">
				<p>Nombre de sportifs :</p>
				<p class="ml-auto">{$nbAthletes}</p>
			</div>
			<div class="d-flex flex-row statistiques px-3 pr-5">
				<p>Nombre de coachs :</p>
				<p class="ml-auto">{$nbCoachs}</p>
			</div>
			<div class="d-flex flex-row statistiques px-3 pr-5">
				<p>Nombre de publications :</p>
				<p class="ml-auto">{$nbPublications}</p>
			</div>
			<div class="d-flex flex-row statistiques px-3 pr-5">
				<p>Nombre d'événements :</p>
				<p class="ml-auto">{$nbEvents}</p>
			</div>
			<div class="d-flex flex-row statistiques px-3 pr-5">
				<p>Nombre de messages :</p>
				<p class="ml-auto">{$nbMessages}</p>
			</div>
			<div class="d-flex flex-row statistiques px-3 pr-5">
				<p>Nombre de commentaires :</p>
				<p class="ml-auto">{$nbComments}</p>
			</div>
			<div class="d-flex flex-row statistiques px-3 pr-5">
				<p>Nombre de conversations :</p>
				<p class="ml-auto">{$nbConversations}</p>
			</div>
		</div>
	</div>
	<div hidden id="hiddenActivities">
		<div class="d-flex flex-row align-items-center justify-content-center searchActivities">
			<h1 style="margin-right: 50px;">Activités :</h1>
			<h4 class="ml-5 mr-3 p-2">Choisissez une date de début :</h4>
			<input class="p-2" id="startingDate" type="date">
			<h4 class="mx-3 p-2">Choisissez une date de fin :</h4>
			<input class="p-2 mr-3" id="endingDate" type="date">
			<button class="p-3" name="searchActivities" onclick="searchActivities()">Rechercher</button>
		</div>
		<div class="main-post" id="activities"></div>
	</div>
</div>
HTML);

/**Utilisation du même truc que sur la page de profil mais avec des dates
.<<<HTML
	<div class="mt-5 d-flex align-items-center justify-content-center">
		<h1 style="text-decoration: underline;">Activités du 11/11/1111 au 22/22/2222</h1>
	</div>

HTML);
**/

echo $p->toHTML();
