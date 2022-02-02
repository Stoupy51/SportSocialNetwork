<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}

$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();
$idUser = $user->getIdUser();
$firstName = $user->getFirstName();
$lastName = $user->getLastName();
$header = SportUtilities::getHeaderPage($user);

$page = new WebPage("Achat");

$page->appendJsUrl("https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js");
$page->appendJsUrl("http://netdna.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js");
$page->appendJsUrl("https://unpkg.com/gijgo@1.9.13/js/gijgo.min.js");
$page->appendJsUrl("js/ajaxrequest.js");
$page->appendJsUrl("js/annonce.js");
$page->appendJsUrl("js/annonce_overlay.js");
$page->appendCssUrl("https://unpkg.com/gijgo@1.9.13/css/gijgo.min.css");
$page->appendCssUrl("https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@700&display=swap");
$page->appendCssUrl("https://fonts.googleapis.com/css2?family=Roboto&display=swap");
$page->appendCssUrl("css/main.css");

$page->appendToHead(<<<HTML
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
HTML);

$search = "";
$searchIdUser = "";
if (isset($_GET["search"])) {
	$search = $_GET["search"];
}
if (isset($_GET["searchIdUser"]))
	$searchIdUser = $_GET["searchIdUser"];
$posts = "";
if ($searchIdUser != "")
	$posts = SportUtilities::getAnnonce($search,$searchIdUser);
else
	$posts = SportUtilities::getAnnonce($search);

$page->appendContent(<<<HTML
<div class="overlay" id="overlay">
		<button class="btn close-overlay"><img src="./img/icons/close.png"></button>
</div>
$header
<div class="main-post" id="listePublications">
$posts
	<div class="post-user">
		Plus aucune annonce
	</div>
</div>



HTML);

echo $page->toHTML();