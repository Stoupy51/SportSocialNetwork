<?php declare(strict_types=1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();

if (!$authentication->isUserConnected()) {
    http_response_code(401);
    header("Location: connexion.php");
    die();
}


$user = $authentication->getUser();
$header = SportUtilities::getHeaderPage($user);

$page = new WebPage("Map");

$page->appendCssUrl("css/main.css");
$page->appendCssUrl("css/map.css");
$page->appendCssUrl("https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css");
$page->appendCssUrl("https://unpkg.com/leaflet@1.7.1/dist/leaflet.css");
$page->appendCssUrl("https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css");
$page->appendJsUrl("js/ajaxrequest.js");
$page->appendJsUrl("https://unpkg.com/leaflet@1.7.1/dist/leaflet.js");
$page->appendJsUrl("https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js");

$page->appendContent(<<<HTML

$header
<div id="page" class="d-flex h-100 w-100">
    <div id="map"></div>
</div>
<script src="js/map.js"></script>


HTML);

$Event = MyPDO::getInstance()->prepare(<<<SQL
SELECT NOM,
       COORDONNEES,
       DATEEVENEMENT
FROM Evenement
WHERE COORDONNEES IS NOT NULL
AND DATEEVENEMENT >= SYSDATE()
AND idEvenement IN (SELECT idEvenement
					FROM Publication
					WHERE idUser >= 0)
SQL);
$Event->execute();
foreach($Event->fetchall() as $k)
{
    $nom = $k['NOM'];

    $coord = $k['COORDONNEES'];
    $coordsplit = explode(',',$coord);
    $longitude = floatval($coordsplit[0]);
    $latitude = floatval($coordsplit[1]);
	$trajet = "<img class='ml-2' src='img/icons/route.png' height=24 onclick='trajet($longitude,$latitude)'>";

    $page->appendContent(<<<HTML
    <script>
        var event = L.marker([$longitude,$latitude]).addTo(map).bindPopup("<a href=\"index.php?search=$nom\">$nom</a>$trajet");
    </script>
    HTML);
}

$users = MyPDO::getInstance()->prepare(<<<SQL
SELECT idUser, COORDONNEES FROM User
WHERE COORDONNEES IS NOT NULL
AND COORDONNEES != ""
AND idUser >= 0
SQL);
$users->execute();
foreach($users->fetchall() as $k)
{
    $user = User::createFromId($k["idUser"]);
	$pseudo = $user->getLogin();
	$nom = $user->getName();

    $coord = $k['COORDONNEES'];
    $coordsplit = explode(',',$coord);
    $longitude = floatval($coordsplit[0]);
    $latitude = floatval($coordsplit[1]);

    $page->appendContent(<<<HTML
    <script>
        var user = L.marker([$longitude,$latitude]).addTo(map).bindPopup("<a href=\"profil.php?user=$pseudo\" id=\"userMarker\">$nom</a>"); user._icon.src="img/icons/userMarker.png";
    </script>
    HTML);
}



echo $page->toHTML();