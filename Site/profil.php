<?php declare(strict_types=1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();

$idUser = 0;
if (isset($_GET["user"]) && $_GET["user"] >= 0) {
	$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idUser FROM User WHERE login = :login
SQL);
	$stmt->execute(["login"=>$_GET["user"]]);
	if (($id = $stmt->fetch()) !== false)
		$idUser = $id["idUser"];
}
$editProfil = '<div hidden>';
$abonnement = "";
if ($authentication->isUserConnected()) {
	$user = $authentication->getUser();
	if ($idUser == 0) {
		$idUser = $user->getIdUser();
		$abonnement = "hidden";
	}
}
else {
	http_response_code(308);
	header("Location: .");
	die();
}
if ($idUser == $user->getIdUser()) {
	$editProfil = '<div class="d-flex flex-row">';
	$abonnement = "hidden";
}


$p = new WebPage("Page de Profil");
$p->appendCssUrl("css/main.css");
$p->appendCssUrl("css/profil.css");
$p->appendJsUrl("js/ajaxrequest.js");
$p->appendJsUrl("js/profil.js");
$p->appendJsUrl("js/publication_overlay.js");

$p->appendToHead(<<<HTML
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
HTML
);


// Requête SQL : 

// --------------- Requête pour la partie profil (sauf NB amis) --------------- 

$ProfilUser = MyPDO::getInstance()->prepare(<<<SQL
SELECT
    us.idImage as "idImage",
    CONCAT(lastName," ",firstName) as "pseudo",
    role as "role",
    COUNT(pu.idPublication) as "idPublication",
    bio as "bio",
	banniere as "banniere"
FROM User us , Publication pu
WHERE pu.idUser = us.idUser
AND us.idUser = :id
SQL);

$ProfilUser->execute([":id"=>$idUser]);

foreach($ProfilUser->fetchAll() as $k){
    $Image = $k['idImage'];
    $Pseudo = $k['pseudo'];
    $Role = $k['role'];
	switch($Role) {
		case 0:
			$Role = <<<HTML
		<div class="Statut-s justify-content-center align-items-center d-flex flex-row m-3 border p-1">Sportif</div>
HTML;
			break;
		case 1:
			$Role = <<<HTML
		<div class="Statut-e justify-content-center align-items-center d-flex flex-row m-3 border p-1">Coach</div>
HTML;
			break;
		case 2:
			$Role = <<<HTML
		<div class="Statut-a justify-content-center align-items-center d-flex flex-row m-3 border p-1">Admin</div>
HTML;
			break;
	}
    $NbPublication = $k['idPublication'];
    $Bio = $k['bio'];
	$Banniere = $k['banniere'];
	if ($Banniere != "")
		$Banniere = <<<HTML
		<img src="data:image;base64, {$Banniere}" class="banniere" style="filter: blur(50px);">
		<img src="data:image;base64, {$Banniere}" class="banniere">
HTML;
} 


// --------------- Requête NB amis du USER --------------- 

$NbAmis = MyPDO::getInstance()->prepare(<<<SQL
    SELECT Count(USE_IDUSER) as "Count" FROM avoir_en_ami
    WHERE idUser = :id
	AND USE_IDUSER >= 0
SQL);

$NbAmis->execute([":id"=>$idUser]);
$NbAmis = $NbAmis->fetch()["Count"];

// --------------- Afficher les amis du USER --------------- 

$ListeAmis = SportUtilities::getUserFriends($idUser);
$ListePublications = SportUtilities::getUserPublications($idUser);
$listVideos = SportUtilities::getUserVideos($idUser);
// --------------- Requête NB vidéos du USER --------------- 
$nbVideos = MyPDO::getInstance()->prepare(<<<SQL
    SELECT Count(idVideo) as "Count" FROM video
    WHERE idUser = :id
	AND idUser >= 0
SQL);

$nbVideos->execute([":id"=>$idUser]);
$nbVideos = $nbVideos->fetch()["Count"];

if ($nbVideos > 1) {
	$nbVideos = <<<HTML
<p><span id="nbVideos">$nbVideos</span> Vidéos</p>
HTML;
}
else {
	$nbVideos = <<<HTML
<p><span id="nbVideos">$nbVideos</span> Vidéo</p>
HTML;
}



$header = SportUtilities::getHeaderPage($user);

$videoForm = '<div hidden>';

if ($idUser == $user->getIdUser()) {
	$videoForm = '<div class="d-flex flex-column mb-3" id="video-form-container" style="margin-left: auto;margin-right: auto;">';
}

$p->appendContent(<<<HTML
<input hidden id="inputIdUser" value="$idUser">
<!-- Haut de page (recherche) -->
$header

<div class="d-flex flex-column">
	<div class="overlay" id="overlay">
			<button class="btn close-overlay"><img src="./img/icons/close.png"></button>
	</div>
    <!-- première partie de la page de profil (photo bio... -->
	$Banniere
    <div class="d-flex flex-row flex-grow-1 p-2" style="z-index:1;">
        <div class="profile d-flex flex-column mx-5 flex-grow-1">
            <img src="getImage.php?id=$Image">
        </div>
        <div class="d-flex flex-column m-2 flex-grow-1">
            <div class="d-flex flex-row m-2">
                <div class="Pseudo d-flex flex-row m-2 ">
                    <p>$Pseudo<p>
                </div>
$Role
                $editProfil
					<a href="modif_profile.php" class="Modif-pro d-flex flex-row m-3 p-1">
						<input type="button" class="boutonModif" value="Modifier le profil">
					</a>
					<div class="Settings d-flex flex-row m-2">
						<img src="img/settings.png" >
					</div>
				</div>
            </div>

            <div class="d-flex flex-row ml-2" style="margin-top: 6%;">
                <div class="Publication d-flex flex-row m-2 ">
                    <p>$NbPublication Publications<p>
                </div>
                <div class="Publication d-flex flex-row m-2 ">
					<p>$NbAmis Amis</p>
                </div>
				<div class="Publication d-flex flex-row m-2 ">
					$nbVideos
                </div>
                <!-- Bouton s'abonner -->
                <div $abonnement class=" flew-row m-1" >
                    <input type="button" class="boutonSabo" id="boutonSabo" value="S'abonner">
                </div>
            </div>

            <div class="Bio d-flex flex-row m-2">
                <p>$Bio<p>
            </div>
        </div> 
    </div>
    <!-- barre de pagination -->
    <hr style="margin-top:-20px;">
    <div class="d-flex flex-grow-1 p-2 justify-content-center">
        <nav>
            <a class="nav-item" data-active-color="grey" id="Publications">Publications</a>
            <a class="nav-item" data-active-color="blue" id="Vidéos">Vidéos</a>
            <a class="nav-item" data-active-color="red" id="Amis">Amis</a>
            <span class="nav-indicator"></span>
        </nav>
    </div>
</div>
<div id="bottom">
	<div id="divPublications" class="hidden finalDivs">
		<div class="main-post" id="listePublications">
$ListePublications
			<div class="post-user">
				Plus aucune publication
			</div>
		</div>
	</div>

	<div id="divVidéos" class="hidden finalDivs">
		<div class="d-flex flex-column justify-content-center video-list m-2">
			$videoForm</div>
			$listVideos
		</div>
	</div>

	<div id="divAmis" class="hidden finalDivs">
		<div class="d-flex flex-row m-2">
$ListeAmis
		</div>
	</div>
</div>
<!-- Bootstrap JS CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
HTML);


echo $p->toHTML();