<?php declare(strict_types=1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}
$p = new WebPage("Page d'accueil");
$p->appendJsUrl("https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js");
$p->appendJsUrl("http://netdna.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js");
$p->appendJsUrl("https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js");
$p->appendJsUrl("js/ajaxrequest.js");
$p->appendJsUrl("js/main.js");
$p->appendJsUrl("js/publication_overlay.js");
$p->appendCssUrl("https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css");
$p->appendCssUrl("https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&display=swap");
$p->appendCssUrl("https://fonts.googleapis.com/css2?family=Roboto&display=swap");
$p->appendCssUrl("css/main.css");
$p->appendToHead(<<<HTML
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
HTML);

$user = $authentication->getUser();
$header = SportUtilities::getHeaderPage($user);

$p->appendContent(<<<HTML
<div class="overlay" id="overlay">
		<button class="btn close-overlay"><img src="./img/icons/close.png"></button>
</div>
<datalist id="adresses">
	<option value="Reims">
</datalist>
$header
<div class="d-flex flex-row main-categories">
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=VolleyBall'">
		<img src="img/Volley-ball.png" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Volleyball</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Basketball'">
		<img src="img/basket.jpg" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Basketball</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Soccer'">
		<img src="img/soccer.jpg" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Soccer</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Bowling'">
		<img src="img/bowling.jpg" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Bowling</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Badminton'">
		<img src="img/badminton.png" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Badminton</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Course'">
		<img src="img/run.jpg" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Course</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Musculation'">
		<img src="img/muscle.jpg" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Musculation</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Danse'">
		<img src="img/dance.jfif" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Danse</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Lutte'">
		<img src="img/lutte.jpg" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Lutte</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Boxe'">
		<img src="img/Boxe.png" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Boxe</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Rugby'">
		<img src="img/rugby.jfif" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Rugby</div>
	</div>
	<div class="d-flex flex-column scroll-item m-3" onclick="window.location.href='index.php?search=Escalade'">
		<img src="img/climb.png" class="scroll-item-img" alt="">
		<div class="scroll-item-title">Escalade</div>
	</div>
</div>
<div class="d-flex flex-row m-3 main-content-wrapper">
HTML);

$search = "";
$searchIdUser = "";
if (isset($_GET["search"])) {
	$search = $_GET["search"];
}
if (isset($_GET["searchIdUser"]))
	$searchIdUser = $_GET["searchIdUser"];
$users = "";
if ($search != "" && $searchIdUser == "")
	$users = SportUtilities::getUsers($search);
$posts = "";
if ($searchIdUser != "")
	$posts = SportUtilities::getPublications($search,$searchIdUser);
else
	$posts = SportUtilities::getPublications($search);

$participants = "";
$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idEvenement, nom
	FROM Evenement
	WHERE idPublication IN (SELECT idPublication FROM Publication WHERE idUser = :id)
	AND idEvenement IN (SELECT idEvenement FROM Identifier2 WHERE demandeAccept = 0)
	AND dateEvenement > SYSDATE()
SQL);
$stmt->execute([":id"=>$user->getIdUser()]);
foreach($stmt->fetchAll() as $k) {
	$idEvent = $k["idEvenement"];
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
SELECT u.idUser, idImage, CONCAT(firstName," ",lastName) as "name", login
FROM Identifier2 i, User u
WHERE idEvenement = :id
AND u.idUser = i.idUser
AND demandeAccept = 0
SQL);
	$stmt->execute([":id"=>$idEvent]);
	$wantJoin = "";
	foreach($stmt->fetchAll() as $line) {
		$login = $line["login"];
		$idUser = $line["idUser"];
		$idImage = $line["idImage"];
		$name = $line["name"];
		$wantJoin .= <<<HTML
		<div class="recommended-user">
			<div class="invite-wrapper">
				<img src="getImage.php?id={$idImage}" class="recommended-user-img">
				<a href="profil.php?user={$login}">$name</a>
				<a href="#" class="friend-accept p-3" onclick="acceptUser(this.parentNode.parentNode, $idUser, $idEvent);">Accepter</a>
			</div>
		</div>
HTML;
	}

	$participants .= <<<HTML
	<div class="mb-5">
		<p>
			Les utilisateurs qui veulent participer à votre activité de <span class="recommendation-name">{$k["nom"]}</span>
		</p>
		<hr class="sep">
$wantJoin
	</div>
HTML;
}

$p->appendContent(<<<HTML
<input hidden id="inputIdUser" value="">
<div class="main-post" id="listePublications">
$users
$posts
	<div class="post-user">
		Plus aucune publication
	</div>
</div>
<div hidden class="d-flex flex-column m-3 mt-5 recommended">
$participants
</div>
HTML);

echo $p->toHTML();
